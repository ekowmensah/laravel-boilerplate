<?php
namespace App\Controllers;

class ReportController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        $this->view('reports/index', [
            'pageTitle' => 'Reports & Analytics'
        ]);
    }
    
    public function loanPortfolio() {
        $this->requireAuth();
        
        $filters = $_GET;
        $data = \Core\ReportGenerator::loanPortfolio($filters);
        
        // Export options
        if (isset($_GET['export'])) {
            if ($_GET['export'] === 'csv') {
                $headers = ['Account', 'Client', 'Product', 'Principal', 'Outstanding', 'Status'];
                $rows = [];
                foreach ($data['details'] as $loan) {
                    $rows[] = [
                        $loan['account_number'],
                        $loan['first_name'] . ' ' . $loan['last_name'],
                        $loan['product_name'],
                        $loan['principal'],
                        $loan['principal_outstanding_derived'],
                        $loan['status']
                    ];
                }
                \Core\ReportGenerator::exportCSV($rows, 'loan_portfolio_' . date('Y-m-d') . '.csv', $headers);
            }
        }
        
        $this->view('reports/loan-portfolio', [
            'pageTitle' => 'Loan Portfolio Report',
            'data' => $data,
            'branches' => (new \App\Models\Branch())->getActive()
        ]);
    }
    
    public function savingsBalance() {
        $this->requireAuth();
        
        $filters = $_GET;
        $data = \Core\ReportGenerator::savingsBalanceReport($filters);
        
        if (isset($_GET['export']) && $_GET['export'] === 'csv') {
            $headers = ['Account', 'Client', 'Product', 'Branch', 'Balance', 'Deposits', 'Withdrawals'];
            $rows = [];
            foreach ($data['accounts'] as $account) {
                $rows[] = [
                    $account['account_number'],
                    $account['first_name'] . ' ' . $account['last_name'],
                    $account['product_name'],
                    $account['branch_name'],
                    $account['balance_derived'],
                    $account['total_deposits_derived'],
                    $account['total_withdrawals_derived']
                ];
            }
            \Core\ReportGenerator::exportCSV($rows, 'savings_balance_' . date('Y-m-d') . '.csv', $headers);
        }
        
        $this->view('reports/savings-balance', [
            'pageTitle' => 'Savings Balance Report',
            'data' => $data,
            'branches' => (new \App\Models\Branch())->getActive()
        ]);
    }
    
    public function arrears() {
        $this->requireAuth();
        
        $filters = $_GET;
        $data = \Core\ReportGenerator::arrearsReport($filters);
        
        if (isset($_GET['export']) && $_GET['export'] === 'csv') {
            $headers = ['Account', 'Client', 'Product', 'Days Overdue', 'Overdue Principal', 'Overdue Interest', 'Total Outstanding'];
            $rows = [];
            foreach ($data['arrears'] as $loan) {
                $rows[] = [
                    $loan['account_number'],
                    $loan['first_name'] . ' ' . $loan['last_name'],
                    $loan['product_name'],
                    $loan['days_overdue'],
                    $loan['overdue_principal'],
                    $loan['overdue_interest'],
                    $loan['total_outstanding_derived']
                ];
            }
            \Core\ReportGenerator::exportCSV($rows, 'arrears_' . date('Y-m-d') . '.csv', $headers);
        }
        
        $this->view('reports/arrears', [
            'pageTitle' => 'Arrears Report',
            'data' => $data,
            'branches' => (new \App\Models\Branch())->getActive()
        ]);
    }
    
    public function disbursement() {
        $this->requireAuth();
        
        $filters = $_GET;
        $data = \Core\ReportGenerator::disbursementReport($filters);
        
        if (isset($_GET['export']) && $_GET['export'] === 'csv') {
            $headers = ['Account', 'Client', 'Product', 'Branch', 'Officer', 'Amount', 'Date'];
            $rows = [];
            foreach ($data['disbursements'] as $loan) {
                $rows[] = [
                    $loan['account_number'],
                    $loan['first_name'] . ' ' . $loan['last_name'],
                    $loan['product_name'],
                    $loan['branch_name'],
                    $loan['officer_first_name'] . ' ' . $loan['officer_last_name'],
                    $loan['amount'],
                    $loan['disbursed_on_date']
                ];
            }
            \Core\ReportGenerator::exportCSV($rows, 'disbursement_' . date('Y-m-d') . '.csv', $headers);
        }
        
        $this->view('reports/disbursement', [
            'pageTitle' => 'Disbursement Report',
            'data' => $data,
            'branches' => (new \App\Models\Branch())->getActive()
        ]);
    }
    
    public function collection() {
        $this->requireAuth();
        
        $filters = $_GET;
        $data = \Core\ReportGenerator::collectionReport($filters);
        
        if (isset($_GET['export']) && $_GET['export'] === 'csv') {
            $headers = ['Date', 'Account', 'Client', 'Branch', 'Amount', 'Principal', 'Interest', 'Fees', 'Penalties'];
            $rows = [];
            foreach ($data['collections'] as $payment) {
                $rows[] = [
                    $payment['payment_date'],
                    $payment['account_number'],
                    $payment['first_name'] . ' ' . $payment['last_name'],
                    $payment['branch_name'],
                    $payment['amount'],
                    $payment['principal_repaid_derived'],
                    $payment['interest_repaid_derived'],
                    $payment['fees_repaid_derived'],
                    $payment['penalties_repaid_derived']
                ];
            }
            \Core\ReportGenerator::exportCSV($rows, 'collection_' . date('Y-m-d') . '.csv', $headers);
        }
        
        $this->view('reports/collection', [
            'pageTitle' => 'Collection Report',
            'data' => $data,
            'branches' => (new \App\Models\Branch())->getActive()
        ]);
    }
}
