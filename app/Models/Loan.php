<?php
namespace App\Models;

class Loan extends BaseModel {
    protected $table = 'loans';
    
    public function getAllWithFilters($status = 'all', $clientType = 'all') {
        $where = [];
        $params = [];
        
        if ($status !== 'all') {
            $where[] = "l.status = ?";
            $params[] = $status;
        }
        
        if ($clientType !== 'all') {
            $where[] = "l.client_type = ?";
            $params[] = $clientType;
        }
        
        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
        
        return $this->fetchAll("
            SELECT l.*, 
                   c.first_name, c.last_name, c.account_number as client_account,
                   lp.name as product_name,
                   b.name as branch_name,
                   u.first_name as officer_first_name,
                   u.last_name as officer_last_name
            FROM {$this->table} l
            LEFT JOIN clients c ON l.client_id = c.id
            LEFT JOIN loan_products lp ON l.loan_product_id = lp.id
            LEFT JOIN branches b ON l.branch_id = b.id
            LEFT JOIN users u ON l.loan_officer_id = u.id
            {$whereClause}
            ORDER BY l.created_at DESC
        ", $params);
    }
    
    public function getWithDetails($id) {
        return $this->fetchOne("
            SELECT l.*, 
                   c.first_name, c.last_name, c.account_number as client_account,
                   lp.name as product_name,
                   b.name as branch_name
            FROM {$this->table} l
            LEFT JOIN clients c ON l.client_id = c.id
            LEFT JOIN loan_products lp ON l.loan_product_id = lp.id
            LEFT JOIN branches b ON l.branch_id = b.id
            WHERE l.id = ?
        ", [$id]);
    }
    
    public function countActive() {
        $result = $this->fetchOne("SELECT COUNT(*) as count FROM {$this->table} WHERE status IN ('active', 'approved')");
        return $result['count'] ?? 0;
    }
    
    public function getTotalOutstanding() {
        $result = $this->fetchOne("SELECT SUM(principal_outstanding_derived) as total FROM {$this->table} WHERE status = 'active'");
        return $result['total'] ?? 0;
    }
    
    public function countPendingApplications() {
        $result = $this->fetchOne("SELECT COUNT(*) as count FROM loan_applications WHERE status = 'pending'");
        return $result['count'] ?? 0;
    }
    
    public function countOverdue() {
        $result = $this->fetchOne("
            SELECT COUNT(DISTINCT loan_id) as count 
            FROM loan_repayment_schedules 
            WHERE due_date < CURDATE() 
            AND (principal - principal_repaid_derived - principal_written_off_derived) > 0
        ");
        return $result['count'] ?? 0;
    }
    
    public function getRecent($limit = 10) {
        return $this->fetchAll("
            SELECT l.*, c.first_name, c.last_name, lp.name as product_name
            FROM {$this->table} l
            LEFT JOIN clients c ON l.client_id = c.id
            LEFT JOIN loan_products lp ON l.loan_product_id = lp.id
            ORDER BY l.created_at DESC
            LIMIT ?
        ", [$limit]);
    }
    
    public function getRecentTransactions($limit = 10) {
        return $this->fetchAll("
            SELECT lt.*, l.account_number as loan_account, c.first_name, c.last_name,
                   ltt.name as transaction_type
            FROM loan_transactions lt
            LEFT JOIN {$this->table} l ON lt.loan_id = l.id
            LEFT JOIN clients c ON l.client_id = c.id
            LEFT JOIN loan_transaction_types ltt ON lt.loan_transaction_type_id = ltt.id
            ORDER BY lt.created_at DESC
            LIMIT ?
        ", [$limit]);
    }
    
    public function getRepaymentSchedule($loanId) {
        return $this->fetchAll("
            SELECT * FROM loan_repayment_schedules 
            WHERE loan_id = ? 
            ORDER BY installment
        ", [$loanId]);
    }
    
    public function getTransactions($loanId) {
        return $this->fetchAll("
            SELECT lt.*, ltt.name as transaction_type
            FROM loan_transactions lt
            LEFT JOIN loan_transaction_types ltt ON lt.loan_transaction_type_id = ltt.id
            WHERE lt.loan_id = ?
            ORDER BY lt.created_at DESC
        ", [$loanId]);
    }
    
    /**
     * Create a new loan with schedule
     */
    public function createLoan($data) {
        $errors = $this->validateLoan($data);
        
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }
        
        try {
            $this->db->beginTransaction();
            
            // Get loan product details
            $product = $this->fetchOne("SELECT * FROM loan_products WHERE id = ?", [$data['loan_product_id']]);
            
            // Generate account number using settings
            $settingModel = new Setting();
            $accountNumber = $settingModel->generateAccountNumber('loan', $data['branch_id'], $data['loan_product_id']);
            
            // Calculate expected dates
            $submittedDate = date('Y-m-d');
            $expectedDisbursementDate = $data['expected_disbursement_date'] ?? $submittedDate;
            $firstPaymentDate = $data['first_payment_date'] ?? null;
            
            $maturityDate = \Core\LoanCalculator::calculateMaturityDate(
                $expectedDisbursementDate,
                $data['loan_term'],
                $data['repayment_frequency'],
                $data['repayment_frequency_type']
            );
            
            // Prepare loan data
            $loanData = [
                'created_by_id' => \Core\Session::getUserId(),
                'client_type' => $data['client_type'] ?? 'client',
                'client_id' => $data['client_id'],
                'group_id' => $data['group_id'] ?? null,
                'branch_id' => $data['branch_id'],
                'currency_id' => $product['currency_id'],
                'loan_product_id' => $data['loan_product_id'],
                'loan_transaction_processing_strategy_id' => $product['loan_transaction_processing_strategy_id'],
                'fund_id' => $product['fund_id'],
                'loan_purpose_id' => $data['loan_purpose_id'],
                'loan_officer_id' => $data['loan_officer_id'],
                'account_number' => $accountNumber,
                'principal' => $data['principal'],
                'applied_amount' => $data['principal'],
                'interest_rate' => $data['interest_rate'],
                'loan_term' => $data['loan_term'],
                'repayment_frequency' => $data['repayment_frequency'],
                'repayment_frequency_type' => $data['repayment_frequency_type'],
                'interest_rate_type' => $data['interest_rate_type'],
                'interest_methodology' => $data['interest_methodology'],
                'grace_on_principal_paid' => $data['grace_on_principal_paid'] ?? 0,
                'grace_on_interest_paid' => $data['grace_on_interest_paid'] ?? 0,
                'grace_on_interest_charged' => $data['grace_on_interest_charged'] ?? 0,
                'submitted_on_date' => $submittedDate,
                'submitted_by_user_id' => \Core\Session::getUserId(),
                'expected_disbursement_date' => $expectedDisbursementDate,
                'expected_first_payment_date' => $firstPaymentDate,
                'expected_maturity_date' => $maturityDate,
                'status' => 'submitted',
                'amortization_method' => $product['amortization_method'],
                'interest_calculation_period_type' => $product['interest_calculation_period_type'],
                'days_in_year' => $product['days_in_year'],
                'days_in_month' => $product['days_in_month']
            ];
            
            $loanId = $this->create($loanData);
            
            // Log activity
            (new \App\Models\User())->logActivity('Create Loan', \Core\Session::getUserId(), 'Loan', $loanId);
            
            $this->db->commit();
            
            return ['success' => true, 'loan_id' => $loanId];
            
        } catch (\Exception $e) {
            $this->db->rollback();
            return ['success' => false, 'errors' => ['Error creating loan: ' . $e->getMessage()]];
        }
    }
    
    /**
     * Approve loan
     */
    public function approveLoan($id, $data) {
        try {
            $loan = $this->find($id);
            
            if (!$loan) {
                return ['success' => false, 'message' => 'Loan not found'];
            }
            
            if ($loan['status'] !== 'submitted') {
                return ['success' => false, 'message' => 'Only submitted loans can be approved'];
            }
            
            $this->db->beginTransaction();
            
            $updateData = [
                'status' => 'approved',
                'approved_on_date' => date('Y-m-d'),
                'approved_by_user_id' => \Core\Session::getUserId(),
                'approved_amount' => $data['approved_amount'] ?? $loan['principal'],
                'approved_notes' => $data['notes'] ?? null
            ];
            
            $this->update($id, $updateData);
            
            // Log activity
            (new \App\Models\User())->logActivity('Approve Loan', \Core\Session::getUserId(), 'Loan', $id);
            
            $this->db->commit();
            
            return ['success' => true, 'message' => 'Loan approved successfully'];
            
        } catch (\Exception $e) {
            $this->db->rollback();
            return ['success' => false, 'message' => 'Error approving loan: ' . $e->getMessage()];
        }
    }
    
    /**
     * Disburse loan
     */
    public function disburseLoan($id, $data) {
        try {
            $loan = $this->find($id);
            
            if (!$loan) {
                return ['success' => false, 'message' => 'Loan not found'];
            }
            
            if ($loan['status'] !== 'approved') {
                return ['success' => false, 'message' => 'Only approved loans can be disbursed'];
            }
            
            $this->db->beginTransaction();
            
            $disbursementDate = $data['disbursement_date'] ?? date('Y-m-d');
            $firstPaymentDate = $data['first_payment_date'] ?? null;
            
            // Generate repayment schedule
            $scheduleParams = [
                'principal' => $loan['approved_amount'] ?? $loan['principal'],
                'interest_rate' => $loan['interest_rate'],
                'loan_term' => $loan['loan_term'],
                'repayment_frequency' => $loan['repayment_frequency'],
                'repayment_frequency_type' => $loan['repayment_frequency_type'],
                'interest_methodology' => $loan['interest_methodology'],
                'interest_rate_type' => $loan['interest_rate_type'],
                'disbursement_date' => $disbursementDate,
                'first_payment_date' => $firstPaymentDate,
                'grace_on_principal_paid' => $loan['grace_on_principal_paid'],
                'grace_on_interest_paid' => $loan['grace_on_interest_paid'],
                'grace_on_interest_charged' => $loan['grace_on_interest_charged']
            ];
            
            $schedule = \Core\LoanCalculator::calculateSchedule($scheduleParams);
            
            // Calculate totals
            $totalPrincipal = array_sum(array_column($schedule, 'principal'));
            $totalInterest = array_sum(array_column($schedule, 'interest'));
            
            // Update loan
            $updateData = [
                'status' => 'active',
                'disbursed_on_date' => $disbursementDate,
                'disbursed_by_user_id' => \Core\Session::getUserId(),
                'disbursed_notes' => $data['notes'] ?? null,
                'first_payment_date' => $firstPaymentDate ?? $schedule[0]['due_date'],
                'principal_disbursed_derived' => $totalPrincipal,
                'principal_outstanding_derived' => $totalPrincipal,
                'interest_disbursed_derived' => $totalInterest,
                'interest_outstanding_derived' => $totalInterest,
                'total_disbursed_derived' => $totalPrincipal + $totalInterest,
                'total_outstanding_derived' => $totalPrincipal + $totalInterest
            ];
            
            $this->update($id, $updateData);
            
            // Insert repayment schedule
            foreach ($schedule as $installment) {
                $installment['loan_id'] = $id;
                $installment['created_by_id'] = \Core\Session::getUserId();
                $this->db->insert('loan_repayment_schedules', $installment);
            }
            
            // Create disbursement transaction
            $transactionData = [
                'loan_id' => $id,
                'branch_id' => $loan['branch_id'],
                'loan_transaction_type_id' => 1, // Disbursement
                'amount' => $totalPrincipal,
                'principal_repaid_derived' => 0,
                'interest_repaid_derived' => 0,
                'fees_repaid_derived' => 0,
                'penalties_repaid_derived' => 0,
                'overpayment_derived' => 0,
                'submitted_on' => $disbursementDate,
                'created_by_id' => \Core\Session::getUserId(),
                'reversed' => 0
            ];
            
            $this->db->insert('loan_transactions', $transactionData);
            
            // Log activity
            (new \App\Models\User())->logActivity('Disburse Loan', \Core\Session::getUserId(), 'Loan', $id);
            
            $this->db->commit();
            
            return ['success' => true, 'message' => 'Loan disbursed successfully'];
            
        } catch (\Exception $e) {
            $this->db->rollback();
            return ['success' => false, 'message' => 'Error disbursing loan: ' . $e->getMessage()];
        }
    }
    
    /**
     * Process loan repayment
     */
    public function processRepayment($id, $data) {
        try {
            $loan = $this->getWithDetails($id);
            
            if (!$loan) {
                return ['success' => false, 'message' => 'Loan not found'];
            }
            
            if ($loan['status'] !== 'active') {
                return ['success' => false, 'message' => 'Only active loans can receive repayments'];
            }
            
            $this->db->beginTransaction();
            
            $amount = $data['amount'];
            $paymentDate = $data['payment_date'] ?? date('Y-m-d');
            
            // Allocate repayment (Penalties -> Fees -> Interest -> Principal)
            $allocation = [
                'penalties' => 0,
                'fees' => 0,
                'interest' => 0,
                'principal' => 0,
                'overpayment' => 0
            ];
            
            $remaining = $amount;
            
            // 1. Allocate to penalties
            $penaltiesOutstanding = $loan['penalties_outstanding_derived'] ?? 0;
            if ($remaining > 0 && $penaltiesOutstanding > 0) {
                $allocation['penalties'] = min($remaining, $penaltiesOutstanding);
                $remaining -= $allocation['penalties'];
            }
            
            // 2. Allocate to fees
            $feesOutstanding = $loan['fees_outstanding_derived'] ?? 0;
            if ($remaining > 0 && $feesOutstanding > 0) {
                $allocation['fees'] = min($remaining, $feesOutstanding);
                $remaining -= $allocation['fees'];
            }
            
            // 3. Allocate to interest
            $interestOutstanding = $loan['interest_outstanding_derived'] ?? 0;
            if ($remaining > 0 && $interestOutstanding > 0) {
                $allocation['interest'] = min($remaining, $interestOutstanding);
                $remaining -= $allocation['interest'];
            }
            
            // 4. Allocate to principal
            $principalOutstanding = $loan['principal_outstanding_derived'] ?? 0;
            if ($remaining > 0 && $principalOutstanding > 0) {
                $allocation['principal'] = min($remaining, $principalOutstanding);
                $remaining -= $allocation['principal'];
            }
            
            // 5. Any remaining is overpayment
            if ($remaining > 0) {
                $allocation['overpayment'] = $remaining;
            }
            
            // Update loan derived columns
            $updateData = [
                'principal_repaid_derived' => ($loan['principal_repaid_derived'] ?? 0) + $allocation['principal'],
                'principal_outstanding_derived' => ($loan['principal_outstanding_derived'] ?? 0) - $allocation['principal'],
                'interest_repaid_derived' => ($loan['interest_repaid_derived'] ?? 0) + $allocation['interest'],
                'interest_outstanding_derived' => ($loan['interest_outstanding_derived'] ?? 0) - $allocation['interest'],
                'fees_repaid_derived' => ($loan['fees_repaid_derived'] ?? 0) + $allocation['fees'],
                'fees_outstanding_derived' => ($loan['fees_outstanding_derived'] ?? 0) - $allocation['fees'],
                'penalties_repaid_derived' => ($loan['penalties_repaid_derived'] ?? 0) + $allocation['penalties'],
                'penalties_outstanding_derived' => ($loan['penalties_outstanding_derived'] ?? 0) - $allocation['penalties'],
                'total_repaid_derived' => ($loan['total_repaid_derived'] ?? 0) + $amount,
                'total_outstanding_derived' => ($loan['total_outstanding_derived'] ?? 0) - $amount
            ];
            
            // Check if loan is fully paid
            if ($updateData['total_outstanding_derived'] <= 0) {
                $updateData['status'] = 'closed';
                $updateData['closed_on_date'] = $paymentDate;
                $updateData['closed_by_user_id'] = \Core\Session::getUserId();
            }
            
            $this->update($id, $updateData);
            
            // Create repayment transaction
            $transactionData = [
                'loan_id' => $id,
                'branch_id' => $loan['branch_id'],
                'loan_transaction_type_id' => 2, // Repayment
                'amount' => $amount,
                'credit' => $amount,
                'debit' => 0,
                'principal_repaid_derived' => $allocation['principal'],
                'interest_repaid_derived' => $allocation['interest'],
                'fees_repaid_derived' => $allocation['fees'],
                'penalties_repaid_derived' => $allocation['penalties'],
                'submitted_on' => $paymentDate,
                'created_by_id' => \Core\Session::getUserId(),
                'reversed' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $insertResult = $this->db->insert('loan_transactions', $transactionData);
            
            if (!$insertResult) {
                throw new \Exception('Failed to create transaction record');
            }
            
            // Update schedule (mark installments as paid)
            // This is a simplified version - you may want more sophisticated logic
            $this->updateScheduleAfterPayment($id, $allocation);
            
            // Log activity
            (new \App\Models\User())->logActivity('Process Loan Repayment', \Core\Session::getUserId(), 'Loan', $id);
            
            $this->db->commit();
            
            return [
                'success' => true, 
                'message' => 'Repayment processed successfully',
                'allocation' => $allocation
            ];
            
        } catch (\Exception $e) {
            $this->db->rollback();
            return ['success' => false, 'message' => 'Error processing repayment: ' . $e->getMessage()];
        }
    }
    
    /**
     * Update schedule after payment
     */
    private function updateScheduleAfterPayment($loanId, $allocation) {
        // Simplified schedule update - just mark the payment
        // In a production system, you'd want more sophisticated logic
        // to update individual installments
        
        try {
            // Get unpaid installments
            $installments = $this->fetchAll("
                SELECT * FROM loan_repayment_schedules 
                WHERE loan_id = ? 
                AND (principal - principal_repaid_derived) > 0
                ORDER BY installment
            ", [$loanId]);
            
            if (empty($installments)) {
                return;
            }
            
            $remainingPrincipal = $allocation['principal'];
            $remainingInterest = $allocation['interest'];
        
        foreach ($installments as $installment) {
            if ($remainingPrincipal <= 0 && $remainingInterest <= 0) break;
            
            $principalDue = $installment['principal'] - $installment['principal_repaid_derived'];
            $interestDue = $installment['interest'] - $installment['interest_repaid_derived'];
            
            $principalPaid = min($remainingPrincipal, $principalDue);
            $interestPaid = min($remainingInterest, $interestDue);
            
            $this->db->update('loan_repayment_schedules', [
                'principal_repaid_derived' => $installment['principal_repaid_derived'] + $principalPaid,
                'interest_repaid_derived' => $installment['interest_repaid_derived'] + $interestPaid,
                'paid_by_date' => ($principalPaid >= $principalDue && $interestPaid >= $interestDue) ? date('Y-m-d') : null
            ], 'id = ?', [$installment['id']]);
            
            $remainingPrincipal -= $principalPaid;
            $remainingInterest -= $interestPaid;
        }
        } catch (\Exception $e) {
            // Silently fail schedule update - payment is already recorded
        }
    }
    
    /**
     * Write off loan
     */
    public function writeOffLoan($id, $data) {
        try {
            $loan = $this->find($id);
            
            if (!$loan || $loan['status'] !== 'active') {
                return ['success' => false, 'message' => 'Only active loans can be written off'];
            }
            
            $this->db->beginTransaction();
            
            $writeOffDate = $data['write_off_date'] ?? date('Y-m-d');
            $outstanding = $loan['total_outstanding_derived'];
            
            // Update loan
            $this->update($id, [
                'status' => 'written_off',
                'written_off_on_date' => $writeOffDate,
                'written_off_by_user_id' => \Core\Session::getUserId(),
                'written_off_notes' => $data['notes'] ?? null,
                'principal_written_off_derived' => $loan['principal_written_off_derived'] + $loan['principal_outstanding_derived'],
                'interest_written_off_derived' => $loan['interest_written_off_derived'] + $loan['interest_outstanding_derived'],
                'fees_written_off_derived' => $loan['fees_written_off_derived'] + $loan['fees_outstanding_derived'],
                'penalties_written_off_derived' => $loan['penalties_written_off_derived'] + $loan['penalties_outstanding_derived'],
                'total_written_off_derived' => $loan['total_written_off_derived'] + $outstanding,
                'principal_outstanding_derived' => 0,
                'interest_outstanding_derived' => 0,
                'fees_outstanding_derived' => 0,
                'penalties_outstanding_derived' => 0,
                'total_outstanding_derived' => 0
            ]);
            
            // Create write-off transaction
            $transactionData = [
                'loan_id' => $id,
                'branch_id' => $loan['branch_id'],
                'loan_transaction_type_id' => 4, // Write-off
                'amount' => $outstanding,
                'submitted_on' => $writeOffDate,
                'created_by_id' => \Core\Session::getUserId(),
                'reversed' => 0
            ];
            
            $this->db->insert('loan_transactions', $transactionData);
            
            // Update schedule
            $this->db->query("
                UPDATE loan_repayment_schedules
                SET principal_written_off_derived = principal - principal_repaid_derived,
                    interest_written_off_derived = interest - interest_repaid_derived
                WHERE loan_id = ?
            ", [$id]);
            
            (new User())->logActivity('Write Off Loan', \Core\Session::getUserId(), 'Loan', $id);
            
            $this->db->commit();
            
            return ['success' => true, 'message' => 'Loan written off successfully'];
            
        } catch (\Exception $e) {
            $this->db->rollback();
            return ['success' => false, 'message' => 'Error writing off loan: ' . $e->getMessage()];
        }
    }
    
    /**
     * Reschedule loan
     */
    public function rescheduleLoan($id, $data) {
        try {
            $loan = $this->find($id);
            
            if (!$loan || $loan['status'] !== 'active') {
                return ['success' => false, 'message' => 'Only active loans can be rescheduled'];
            }
            
            $this->db->beginTransaction();
            
            // Delete old schedule
            $this->db->delete('loan_repayment_schedules', 'loan_id = ?', [$id]);
            
            // Generate new schedule
            $scheduleParams = [
                'principal' => $loan['principal_outstanding_derived'],
                'interest_rate' => $data['new_interest_rate'] ?? $loan['interest_rate'],
                'loan_term' => $data['new_loan_term'] ?? $loan['loan_term'],
                'repayment_frequency' => $loan['repayment_frequency'],
                'repayment_frequency_type' => $loan['repayment_frequency_type'],
                'interest_methodology' => $loan['interest_methodology'],
                'interest_rate_type' => $loan['interest_rate_type'],
                'disbursement_date' => $data['reschedule_date'] ?? date('Y-m-d'),
                'first_payment_date' => $data['first_payment_date'] ?? null,
                'grace_on_principal_paid' => $data['grace_on_principal_paid'] ?? 0,
                'grace_on_interest_paid' => $data['grace_on_interest_paid'] ?? 0,
                'grace_on_interest_charged' => $data['grace_on_interest_charged'] ?? 0
            ];
            
            $schedule = \Core\LoanCalculator::calculateSchedule($scheduleParams);
            
            // Calculate new totals
            $totalInterest = array_sum(array_column($schedule, 'interest'));
            
            // Update loan
            $this->update($id, [
                'rescheduled_on_date' => $data['reschedule_date'] ?? date('Y-m-d'),
                'rescheduled_by_user_id' => \Core\Session::getUserId(),
                'rescheduled_notes' => $data['notes'] ?? null,
                'interest_rate' => $data['new_interest_rate'] ?? $loan['interest_rate'],
                'loan_term' => $data['new_loan_term'] ?? $loan['loan_term'],
                'interest_outstanding_derived' => $totalInterest,
                'total_outstanding_derived' => $loan['principal_outstanding_derived'] + $totalInterest
            ]);
            
            // Insert new schedule
            foreach ($schedule as $installment) {
                $installment['loan_id'] = $id;
                $installment['created_by_id'] = \Core\Session::getUserId();
                $this->db->insert('loan_repayment_schedules', $installment);
            }
            
            (new User())->logActivity('Reschedule Loan', \Core\Session::getUserId(), 'Loan', $id);
            
            $this->db->commit();
            
            return ['success' => true, 'message' => 'Loan rescheduled successfully'];
            
        } catch (\Exception $e) {
            $this->db->rollback();
            return ['success' => false, 'message' => 'Error rescheduling loan: ' . $e->getMessage()];
        }
    }
    
    /**
     * Validate loan data
     */
    private function validateLoan($data) {
        $errors = [];
        
        if (empty($data['client_id'])) $errors[] = 'Client is required';
        if (empty($data['loan_product_id'])) $errors[] = 'Loan product is required';
        if (empty($data['principal']) || $data['principal'] <= 0) $errors[] = 'Principal amount is required';
        if (empty($data['interest_rate'])) $errors[] = 'Interest rate is required';
        if (empty($data['loan_term']) || $data['loan_term'] <= 0) $errors[] = 'Loan term is required';
        if (empty($data['branch_id'])) $errors[] = 'Branch is required';
        if (empty($data['loan_officer_id'])) $errors[] = 'Loan officer is required';
        if (empty($data['loan_purpose_id'])) $errors[] = 'Loan purpose is required';
        
        return $errors;
    }
}
