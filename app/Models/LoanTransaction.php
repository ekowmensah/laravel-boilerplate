<?php
namespace App\Models;

class LoanTransaction extends BaseModel {
    protected $table = 'loan_transactions';
    
    public function getByLoanId($loanId) {
        return $this->db->fetchAll("
            SELECT lt.*, ltt.name as transaction_type_name,
                   u.first_name as user_first_name, u.last_name as user_last_name
            FROM {$this->table} lt
            LEFT JOIN loan_transaction_types ltt ON lt.loan_transaction_type_id = ltt.id
            LEFT JOIN users u ON lt.created_by_id = u.id
            WHERE lt.loan_id = ?
            ORDER BY lt.submitted_on DESC, lt.created_at DESC
        ", [$loanId]);
    }
    
    public function getRecent($limit = 50) {
        return $this->db->fetchAll("
            SELECT lt.*, l.account_number as loan_account,
                   c.first_name, c.last_name,
                   ltt.name as transaction_type_name
            FROM {$this->table} lt
            LEFT JOIN loans l ON lt.loan_id = l.id
            LEFT JOIN clients c ON l.client_id = c.id
            LEFT JOIN loan_transaction_types ltt ON lt.loan_transaction_type_id = ltt.id
            ORDER BY lt.created_at DESC
            LIMIT ?
        ", [$limit]);
    }
}
