<?php
namespace Core;

class InterestCalculator {
    
    /**
     * Calculate interest for savings account
     * 
     * @param array $savings Savings account data
     * @param string $startDate Start date for calculation
     * @param string $endDate End date for calculation
     * @return array
     */
    public static function calculateSavingsInterest($savings, $startDate, $endDate) {
        $interestRate = $savings['interest_rate'] / 100; // Convert to decimal
        $balance = $savings['balance_derived'];
        $calculationType = $savings['interest_calculation_type'];
        $compoundingPeriod = $savings['compounding_period'];
        $minBalance = $savings['minimum_balance_for_interest_calculation'];
        
        // Only calculate if balance meets minimum
        if ($balance < $minBalance) {
            return [
                'interest' => 0,
                'message' => 'Balance below minimum for interest calculation'
            ];
        }
        
        $start = new \DateTime($startDate);
        $end = new \DateTime($endDate);
        $days = $start->diff($end)->days;
        
        $interest = 0;
        
        if ($calculationType === 'daily_balance') {
            // Simple daily balance calculation
            $interest = self::calculateDailyBalance($balance, $interestRate, $days, $compoundingPeriod);
        } else {
            // Average daily balance
            $interest = self::calculateAverageDailyBalance($savings['id'], $interestRate, $startDate, $endDate, $compoundingPeriod);
        }
        
        return [
            'interest' => round($interest, 2),
            'days' => $days,
            'rate' => $savings['interest_rate'],
            'balance' => $balance
        ];
    }
    
    /**
     * Calculate using daily balance method
     */
    private static function calculateDailyBalance($balance, $rate, $days, $compounding) {
        // Annual rate to daily rate
        $dailyRate = $rate / 365;
        
        switch ($compounding) {
            case 'daily':
                // Compound daily
                $interest = $balance * (pow(1 + $dailyRate, $days) - 1);
                break;
                
            case 'monthly':
                // Simple interest, compound monthly
                $months = floor($days / 30);
                $interest = $balance * ($rate / 12) * $months;
                break;
                
            case 'annually':
                // Simple interest for the period
                $interest = $balance * $dailyRate * $days;
                break;
                
            default:
                $interest = $balance * $dailyRate * $days;
        }
        
        return $interest;
    }
    
    /**
     * Calculate using average daily balance
     */
    private static function calculateAverageDailyBalance($savingsId, $rate, $startDate, $endDate, $compounding) {
        $db = \Core\Database::getInstance();
        
        // Get all transactions in the period
        $transactions = $db->fetchAll("
            SELECT submitted_on, amount, savings_transaction_type_id
            FROM savings_transactions
            WHERE savings_id = ?
            AND submitted_on BETWEEN ? AND ?
            ORDER BY submitted_on
        ", [$savingsId, $startDate, $endDate]);
        
        // Calculate average balance
        $start = new \DateTime($startDate);
        $end = new \DateTime($endDate);
        $totalDays = $start->diff($end)->days;
        
        $balanceSum = 0;
        $currentBalance = 0;
        
        // Get starting balance
        $startingBalance = $db->fetchOne("
            SELECT balance_derived
            FROM savings
            WHERE id = ?
        ", [$savingsId]);
        
        $currentBalance = $startingBalance['balance_derived'];
        
        // Adjust for transactions
        foreach ($transactions as $transaction) {
            if ($transaction['savings_transaction_type_id'] == 1) {
                // Deposit
                $currentBalance -= $transaction['amount'];
            } else {
                // Withdrawal
                $currentBalance += $transaction['amount'];
            }
        }
        
        // Use current balance as average (simplified)
        $averageBalance = $currentBalance;
        
        // Calculate interest
        $dailyRate = $rate / 365;
        $interest = $averageBalance * $dailyRate * $totalDays;
        
        return $interest;
    }
    
    /**
     * Post interest to savings account
     */
    public static function postInterest($savingsId, $interestAmount, $postingDate) {
        $db = \Core\Database::getInstance();
        
        try {
            $db->beginTransaction();
            
            // Get current savings data
            $savings = $db->fetchOne("SELECT * FROM savings WHERE id = ?", [$savingsId]);
            
            // Update balance
            $newBalance = $savings['balance_derived'] + $interestAmount;
            $db->update('savings', [
                'balance_derived' => $newBalance,
                'total_interest_posted_derived' => $savings['total_interest_posted_derived'] + $interestAmount,
                'last_interest_posting_date' => $postingDate
            ], 'id = ?', [$savingsId]);
            
            // Create interest transaction
            $transactionData = [
                'savings_id' => $savingsId,
                'branch_id' => $savings['branch_id'],
                'savings_transaction_type_id' => 3, // Interest posting
                'amount' => $interestAmount,
                'balance_derived' => $newBalance,
                'submitted_on' => $postingDate,
                'created_by_id' => \Core\Session::getUserId() ?? 1,
                'reversed' => 0,
                'notes' => 'Interest posting'
            ];
            
            $db->insert('savings_transactions', $transactionData);
            
            // Log activity
            (new \App\Models\User())->logActivity('Post Interest', \Core\Session::getUserId() ?? 1, 'Savings', $savingsId);
            
            $db->commit();
            
            return [
                'success' => true,
                'interest' => $interestAmount,
                'new_balance' => $newBalance
            ];
            
        } catch (\Exception $e) {
            $db->rollback();
            return [
                'success' => false,
                'message' => 'Error posting interest: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Calculate and post interest for all eligible accounts
     */
    public static function postInterestBatch($postingDate = null) {
        if (!$postingDate) {
            $postingDate = date('Y-m-d');
        }
        
        $db = \Core\Database::getInstance();
        
        // Get all active savings accounts
        $accounts = $db->fetchAll("
            SELECT * FROM savings
            WHERE status = 'active'
            AND interest_rate > 0
        ");
        
        $results = [
            'total' => count($accounts),
            'success' => 0,
            'failed' => 0,
            'total_interest' => 0
        ];
        
        foreach ($accounts as $account) {
            // Calculate interest from last posting date
            $startDate = $account['last_interest_posting_date'] ?? $account['activated_on_date'];
            $endDate = $postingDate;
            
            $calculation = self::calculateSavingsInterest($account, $startDate, $endDate);
            
            if ($calculation['interest'] > 0) {
                $result = self::postInterest($account['id'], $calculation['interest'], $postingDate);
                
                if ($result['success']) {
                    $results['success']++;
                    $results['total_interest'] += $calculation['interest'];
                } else {
                    $results['failed']++;
                }
            }
        }
        
        return $results;
    }
}
