<?php
namespace Core;

class ReportGenerator {
    
    /**
     * Generate Loan Portfolio Report
     */
    public static function loanPortfolio($filters = []) {
        $db = \Core\Database::getInstance();
        
        $where = ['1=1'];
        $params = [];
        
        if (!empty($filters['branch_id'])) {
            $where[] = 'l.branch_id = ?';
            $params[] = $filters['branch_id'];
        }
        
        if (!empty($filters['status'])) {
            $where[] = 'l.status = ?';
            $params[] = $filters['status'];
        }
        
        if (!empty($filters['date_from'])) {
            $where[] = 'l.disbursed_on_date >= ?';
            $params[] = $filters['date_from'];
        }
        
        if (!empty($filters['date_to'])) {
            $where[] = 'l.disbursed_on_date <= ?';
            $params[] = $filters['date_to'];
        }
        
        $whereClause = implode(' AND ', $where);
        
        // Summary by status
        $summary = $db->fetchAll("
            SELECT 
                l.status,
                COUNT(*) as count,
                SUM(l.principal) as total_principal,
                SUM(l.principal_outstanding_derived) as total_outstanding,
                SUM(l.principal_repaid_derived) as total_repaid
            FROM loans l
            WHERE {$whereClause}
            GROUP BY l.status
        ", $params);
        
        // Detailed list
        $details = $db->fetchAll("
            SELECT 
                l.*,
                c.first_name,
                c.last_name,
                c.account_number as client_account,
                lp.name as product_name,
                b.name as branch_name
            FROM loans l
            LEFT JOIN clients c ON l.client_id = c.id
            LEFT JOIN loan_products lp ON l.loan_product_id = lp.id
            LEFT JOIN branches b ON l.branch_id = b.id
            WHERE {$whereClause}
            ORDER BY l.disbursed_on_date DESC
        ", $params);
        
        return [
            'summary' => $summary,
            'details' => $details,
            'filters' => $filters
        ];
    }
    
    /**
     * Generate Arrears Report
     */
    public static function arrearsReport($filters = []) {
        $db = \Core\Database::getInstance();
        
        $where = ['l.status = ?'];
        $params = ['active'];
        
        if (!empty($filters['branch_id'])) {
            $where[] = 'l.branch_id = ?';
            $params[] = $filters['branch_id'];
        }
        
        $whereClause = implode(' AND ', $where);
        
        $arrears = $db->fetchAll("
            SELECT 
                l.id,
                l.account_number,
                c.first_name,
                c.last_name,
                c.mobile,
                lp.name as product_name,
                b.name as branch_name,
                l.principal_outstanding_derived,
                l.interest_outstanding_derived,
                l.total_outstanding_derived,
                MIN(lrs.due_date) as first_overdue_date,
                DATEDIFF(CURDATE(), MIN(lrs.due_date)) as days_overdue,
                SUM(lrs.principal - lrs.principal_repaid_derived - lrs.principal_written_off_derived) as overdue_principal,
                SUM(lrs.interest - lrs.interest_repaid_derived - lrs.interest_written_off_derived) as overdue_interest
            FROM loans l
            LEFT JOIN clients c ON l.client_id = c.id
            LEFT JOIN loan_products lp ON l.loan_product_id = lp.id
            LEFT JOIN branches b ON l.branch_id = b.id
            LEFT JOIN loan_repayment_schedules lrs ON l.id = lrs.loan_id
            WHERE {$whereClause}
            AND lrs.due_date < CURDATE()
            AND (lrs.principal - lrs.principal_repaid_derived - lrs.principal_written_off_derived) > 0
            GROUP BY l.id
            ORDER BY days_overdue DESC, l.total_outstanding_derived DESC
        ", $params);
        
        return [
            'arrears' => $arrears,
            'filters' => $filters
        ];
    }
    
    /**
     * Generate Disbursement Report
     */
    public static function disbursementReport($filters = []) {
        $db = \Core\Database::getInstance();
        
        $where = ['l.status IN (?, ?)'];
        $params = ['active', 'closed'];
        
        if (!empty($filters['branch_id'])) {
            $where[] = 'l.branch_id = ?';
            $params[] = $filters['branch_id'];
        }
        
        if (!empty($filters['date_from'])) {
            $where[] = 'l.disbursed_on_date >= ?';
            $params[] = $filters['date_from'];
        }
        
        if (!empty($filters['date_to'])) {
            $where[] = 'l.disbursed_on_date <= ?';
            $params[] = $filters['date_to'];
        }
        
        $whereClause = implode(' AND ', $where);
        
        $disbursements = $db->fetchAll("
            SELECT 
                l.id,
                l.account_number,
                l.disbursed_on_date,
                c.first_name,
                c.last_name,
                lp.name as product_name,
                b.name as branch_name,
                u.first_name as officer_first_name,
                u.last_name as officer_last_name,
                l.principal_disbursed_derived as amount
            FROM loans l
            LEFT JOIN clients c ON l.client_id = c.id
            LEFT JOIN loan_products lp ON l.loan_product_id = lp.id
            LEFT JOIN branches b ON l.branch_id = b.id
            LEFT JOIN users u ON l.loan_officer_id = u.id
            WHERE {$whereClause}
            ORDER BY l.disbursed_on_date DESC
        ", $params);
        
        $total = array_sum(array_column($disbursements, 'amount'));
        
        return [
            'disbursements' => $disbursements,
            'total' => $total,
            'filters' => $filters
        ];
    }
    
    /**
     * Generate Collection Report
     */
    public static function collectionReport($filters = []) {
        $db = \Core\Database::getInstance();
        
        $where = ['lt.loan_transaction_type_id = ?'];
        $params = [2]; // Repayment type
        
        if (!empty($filters['branch_id'])) {
            $where[] = 'l.branch_id = ?';
            $params[] = $filters['branch_id'];
        }
        
        if (!empty($filters['date_from'])) {
            $where[] = 'lt.submitted_on >= ?';
            $params[] = $filters['date_from'];
        }
        
        if (!empty($filters['date_to'])) {
            $where[] = 'lt.submitted_on <= ?';
            $params[] = $filters['date_to'];
        }
        
        $whereClause = implode(' AND ', $where);
        
        $collections = $db->fetchAll("
            SELECT 
                lt.id,
                lt.submitted_on as payment_date,
                l.account_number,
                c.first_name,
                c.last_name,
                b.name as branch_name,
                lt.amount,
                lt.principal_repaid_derived,
                lt.interest_repaid_derived,
                lt.fees_repaid_derived,
                lt.penalties_repaid_derived
            FROM loan_transactions lt
            LEFT JOIN loans l ON lt.loan_id = l.id
            LEFT JOIN clients c ON l.client_id = c.id
            LEFT JOIN branches b ON l.branch_id = b.id
            WHERE {$whereClause}
            ORDER BY lt.submitted_on DESC
        ", $params);
        
        $total = array_sum(array_column($collections, 'amount'));
        
        return [
            'collections' => $collections,
            'total' => $total,
            'filters' => $filters
        ];
    }
    
    /**
     * Generate Savings Balance Report
     */
    public static function savingsBalanceReport($filters = []) {
        $db = \Core\Database::getInstance();
        
        $where = ['s.status = ?'];
        $params = ['active'];
        
        if (!empty($filters['branch_id'])) {
            $where[] = 's.branch_id = ?';
            $params[] = $filters['branch_id'];
        }
        
        $whereClause = implode(' AND ', $where);
        
        $accounts = $db->fetchAll("
            SELECT 
                s.id,
                s.account_number,
                c.first_name,
                c.last_name,
                sp.name as product_name,
                b.name as branch_name,
                s.balance_derived,
                s.total_deposits_derived,
                s.total_withdrawals_derived,
                s.activated_on_date
            FROM savings s
            LEFT JOIN clients c ON s.client_id = c.id
            LEFT JOIN savings_products sp ON s.savings_product_id = sp.id
            LEFT JOIN branches b ON s.branch_id = b.id
            WHERE {$whereClause}
            ORDER BY s.balance_derived DESC
        ", $params);
        
        $totalBalance = array_sum(array_column($accounts, 'balance_derived'));
        
        return [
            'accounts' => $accounts,
            'total_balance' => $totalBalance,
            'filters' => $filters
        ];
    }
    
    /**
     * Export to CSV
     */
    public static function exportCSV($data, $filename, $headers) {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Write headers
        fputcsv($output, $headers);
        
        // Write data
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
        
        fclose($output);
        exit;
    }
    
    /**
     * Generate HTML for printing
     */
    public static function generateHTML($title, $content, $filters = []) {
        $html = '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>' . htmlspecialchars($title) . '</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                h1 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #007bff; color: white; }
                tr:nth-child(even) { background-color: #f2f2f2; }
                .filters { background: #f8f9fa; padding: 10px; margin: 10px 0; border-radius: 5px; }
                .total { font-weight: bold; background-color: #e9ecef; }
                @media print {
                    .no-print { display: none; }
                }
            </style>
        </head>
        <body>
            <h1>' . htmlspecialchars($title) . '</h1>
            <p><strong>Generated:</strong> ' . date('F d, Y H:i:s') . '</p>';
        
        if (!empty($filters)) {
            $html .= '<div class="filters"><strong>Filters:</strong> ';
            foreach ($filters as $key => $value) {
                if (!empty($value)) {
                    $html .= ucfirst(str_replace('_', ' ', $key)) . ': ' . htmlspecialchars($value) . ' | ';
                }
            }
            $html .= '</div>';
        }
        
        $html .= $content;
        $html .= '<div class="no-print" style="margin-top: 20px;">
                    <button onclick="window.print()">Print Report</button>
                    <button onclick="window.close()">Close</button>
                  </div>
        </body>
        </html>';
        
        return $html;
    }
}
