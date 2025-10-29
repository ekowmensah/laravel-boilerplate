<?php
namespace App\Models;

class Savings extends BaseModel {
    protected $table = 'savings';
    
    public function countActive() {
        $result = $this->fetchOne("SELECT COUNT(*) as count FROM {$this->table} WHERE status = 'active'");
        return $result['count'] ?? 0;
    }
    
    public function getTotalBalance() {
        $result = $this->fetchOne("SELECT SUM(balance_derived) as total FROM {$this->table} WHERE status = 'active'");
        return $result['total'] ?? 0;
    }
    
    public function getAllWithDetails() {
        return $this->fetchAll("
            SELECT s.*, 
                   c.first_name, c.last_name, c.account_number as client_account,
                   sp.name as product_name,
                   b.name as branch_name
            FROM {$this->table} s
            LEFT JOIN clients c ON s.client_id = c.id
            LEFT JOIN savings_products sp ON s.savings_product_id = sp.id
            LEFT JOIN branches b ON s.branch_id = b.id
            ORDER BY s.created_at DESC
        ");
    }
    
    public function getWithDetails($id) {
        return $this->fetchOne("
            SELECT s.*, 
                   c.first_name, c.last_name, c.account_number as client_account,
                   sp.name as product_name,
                   b.name as branch_name
            FROM {$this->table} s
            LEFT JOIN clients c ON s.client_id = c.id
            LEFT JOIN savings_products sp ON s.savings_product_id = sp.id
            LEFT JOIN branches b ON s.branch_id = b.id
            WHERE s.id = ?
        ", [$id]);
    }
    
    public function getTransactions($savingsId) {
        return $this->fetchAll("
            SELECT st.*, stt.name as transaction_type
            FROM savings_transactions st
            LEFT JOIN savings_transaction_types stt ON st.savings_transaction_type_id = stt.id
            WHERE st.savings_id = ?
            ORDER BY st.created_at DESC
        ", [$savingsId]);
    }
    
    public function getRecentTransactions($limit = 50) {
        return $this->fetchAll("
            SELECT 'savings' as trans_type, st.*, 
                   s.account_number as account,
                   c.first_name, c.last_name,
                   stt.name as transaction_name
            FROM savings_transactions st
            LEFT JOIN {$this->table} s ON st.savings_id = s.id
            LEFT JOIN clients c ON s.client_id = c.id
            LEFT JOIN savings_transaction_types stt ON st.savings_transaction_type_id = stt.id
            ORDER BY st.created_at DESC
            LIMIT ?
        ", [$limit]);
    }
    
    /**
     * Create savings account
     */
    public function createSavings($data) {
        $errors = $this->validateSavings($data);
        
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }
        
        try {
            $this->db->beginTransaction();
            
            // Get product details
            $product = $this->fetchOne("SELECT * FROM savings_products WHERE id = ?", [$data['savings_product_id']]);
            
            // Generate account number using settings
            $settingModel = new Setting();
            $accountNumber = $settingModel->generateAccountNumber('savings', $data['branch_id'], $data['savings_product_id']);
            
            $savingsData = [
                'created_by_id' => \Core\Session::getUserId(),
                'currency_id' => $product['currency_id'],
                'savings_officer_id' => $data['savings_officer_id'] ?? null,
                'savings_product_id' => $data['savings_product_id'],
                'client_type' => $data['client_type'] ?? 'client',
                'client_id' => $data['client_id'],
                'group_id' => $data['group_id'] ?? null,
                'branch_id' => $data['branch_id'],
                'account_number' => $accountNumber,
                'interest_rate' => $data['interest_rate'] ?? $product['default_interest_rate'],
                'interest_rate_type' => $product['interest_rate_type'],
                'compounding_period' => $product['compounding_period'],
                'interest_posting_period_type' => $product['interest_posting_period_type'],
                'interest_calculation_type' => $product['interest_calculation_type'],
                'minimum_balance_for_interest_calculation' => $product['minimum_balance_for_interest_calculation'],
                'lockin_period' => $product['lockin_period'],
                'lockin_type' => $product['lockin_type'],
                'allow_overdraft' => $product['allow_overdraft'],
                'overdraft_limit' => $product['overdraft_limit'],
                'submitted_on_date' => date('Y-m-d'),
                'submitted_by_user_id' => \Core\Session::getUserId(),
                'status' => 'submitted'
            ];
            
            $savingsId = $this->create($savingsData);
            
            // Log activity
            (new \App\Models\User())->logActivity('Create Savings Account', \Core\Session::getUserId(), 'Savings', $savingsId);
            
            $this->db->commit();
            
            return ['success' => true, 'savings_id' => $savingsId];
            
        } catch (\Exception $e) {
            $this->db->rollback();
            return ['success' => false, 'errors' => ['Error creating savings account: ' . $e->getMessage()]];
        }
    }
    
    /**
     * Approve savings account
     */
    public function approveSavings($id, $data) {
        try {
            $savings = $this->find($id);
            
            if (!$savings || $savings['status'] !== 'submitted') {
                return ['success' => false, 'message' => 'Only submitted accounts can be approved'];
            }
            
            $this->db->beginTransaction();
            
            $this->update($id, [
                'status' => 'approved',
                'approved_on_date' => date('Y-m-d'),
                'approved_by_user_id' => \Core\Session::getUserId(),
                'approved_notes' => $data['notes'] ?? null
            ]);
            
            (new \App\Models\User())->logActivity('Approve Savings', \Core\Session::getUserId(), 'Savings', $id);
            
            $this->db->commit();
            
            return ['success' => true, 'message' => 'Savings account approved'];
            
        } catch (\Exception $e) {
            $this->db->rollback();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Activate savings account
     */
    public function activateSavings($id, $data) {
        try {
            $savings = $this->find($id);
            
            if (!$savings || $savings['status'] !== 'approved') {
                return ['success' => false, 'message' => 'Only approved accounts can be activated'];
            }
            
            $this->db->beginTransaction();
            
            $this->update($id, [
                'status' => 'active',
                'activated_on_date' => date('Y-m-d'),
                'activated_by_user_id' => \Core\Session::getUserId(),
                'activated_notes' => $data['notes'] ?? null
            ]);
            
            (new \App\Models\User())->logActivity('Activate Savings', \Core\Session::getUserId(), 'Savings', $id);
            
            $this->db->commit();
            
            return ['success' => true, 'message' => 'Savings account activated'];
            
        } catch (\Exception $e) {
            $this->db->rollback();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Process deposit
     */
    public function processDeposit($id, $data) {
        try {
            $savings = $this->find($id);
            
            if (!$savings || $savings['status'] !== 'active') {
                return ['success' => false, 'message' => 'Only active accounts can receive deposits'];
            }
            
            $amount = $data['amount'];
            if ($amount <= 0) {
                return ['success' => false, 'message' => 'Amount must be greater than zero'];
            }
            
            $this->db->beginTransaction();
            
            // Update balance
            $newBalance = $savings['balance_derived'] + $amount;
            $this->update($id, [
                'balance_derived' => $newBalance,
                'total_deposits_derived' => $savings['total_deposits_derived'] + $amount
            ]);
            
            // Create transaction
            $transactionData = [
                'savings_id' => $id,
                'branch_id' => $savings['branch_id'],
                'savings_transaction_type_id' => 1, // Deposit
                'amount' => $amount,
                'balance_derived' => $newBalance,
                'submitted_on' => $data['transaction_date'] ?? date('Y-m-d'),
                'created_by_id' => \Core\Session::getUserId(),
                'reversed' => 0,
                'notes' => $data['notes'] ?? null
            ];
            
            $this->db->insert('savings_transactions', $transactionData);
            
            (new \App\Models\User())->logActivity('Savings Deposit', \Core\Session::getUserId(), 'Savings', $id);
            
            $this->db->commit();
            
            return ['success' => true, 'message' => 'Deposit processed successfully', 'new_balance' => $newBalance];
            
        } catch (\Exception $e) {
            $this->db->rollback();
            return ['success' => false, 'message' => 'Error processing deposit: ' . $e->getMessage()];
        }
    }
    
    /**
     * Process withdrawal
     */
    public function processWithdrawal($id, $data) {
        try {
            $savings = $this->find($id);
            
            if (!$savings || $savings['status'] !== 'active') {
                return ['success' => false, 'message' => 'Only active accounts can process withdrawals'];
            }
            
            $amount = $data['amount'];
            if ($amount <= 0) {
                return ['success' => false, 'message' => 'Amount must be greater than zero'];
            }
            
            if ($amount > $savings['balance_derived']) {
                return ['success' => false, 'message' => 'Insufficient balance'];
            }
            
            // Check lockin period
            if ($savings['lockin_period'] > 0) {
                $activatedDate = new \DateTime($savings['activated_on_date']);
                $today = new \DateTime();
                $diff = $activatedDate->diff($today);
                
                $periodInDays = 0;
                switch ($savings['lockin_type']) {
                    case 'days':
                        $periodInDays = $diff->days;
                        break;
                    case 'weeks':
                        $periodInDays = floor($diff->days / 7);
                        break;
                    case 'months':
                        $periodInDays = $diff->m + ($diff->y * 12);
                        break;
                    case 'years':
                        $periodInDays = $diff->y;
                        break;
                }
                
                if ($periodInDays < $savings['lockin_period']) {
                    return ['success' => false, 'message' => 'Account is still in lock-in period'];
                }
            }
            
            $this->db->beginTransaction();
            
            // Update balance
            $newBalance = $savings['balance_derived'] - $amount;
            $this->update($id, [
                'balance_derived' => $newBalance,
                'total_withdrawals_derived' => $savings['total_withdrawals_derived'] + $amount
            ]);
            
            // Create transaction
            $transactionData = [
                'savings_id' => $id,
                'branch_id' => $savings['branch_id'],
                'savings_transaction_type_id' => 2, // Withdrawal
                'amount' => $amount,
                'balance_derived' => $newBalance,
                'submitted_on' => $data['transaction_date'] ?? date('Y-m-d'),
                'created_by_id' => \Core\Session::getUserId(),
                'reversed' => 0,
                'notes' => $data['notes'] ?? null
            ];
            
            $this->db->insert('savings_transactions', $transactionData);
            
            (new \App\Models\User())->logActivity('Savings Withdrawal', \Core\Session::getUserId(), 'Savings', $id);
            
            $this->db->commit();
            
            return ['success' => true, 'message' => 'Withdrawal processed successfully', 'new_balance' => $newBalance];
            
        } catch (\Exception $e) {
            $this->db->rollback();
            return ['success' => false, 'message' => 'Error processing withdrawal: ' . $e->getMessage()];
        }
    }
    
    /**
     * Validate savings data
     */
    private function validateSavings($data) {
        $errors = [];
        
        if (empty($data['client_id'])) $errors[] = 'Client is required';
        if (empty($data['savings_product_id'])) $errors[] = 'Savings product is required';
        if (empty($data['branch_id'])) $errors[] = 'Branch is required';
        
        return $errors;
    }
}

