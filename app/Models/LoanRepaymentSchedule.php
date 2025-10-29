<?php
namespace App\Models;

class LoanRepaymentSchedule extends BaseModel {
    protected $table = 'loan_repayment_schedules';
    
    public function getByLoanId($loanId) {
        return $this->db->fetchAll("
            SELECT * FROM {$this->table} 
            WHERE loan_id = ? 
            ORDER BY due_date ASC
        ", [$loanId]);
    }
    
    public function getUpcoming($loanId, $limit = 5) {
        return $this->db->fetchAll("
            SELECT * FROM {$this->table} 
            WHERE loan_id = ? 
            AND due_date >= CURDATE()
            ORDER BY due_date ASC
            LIMIT ?
        ", [$loanId, $limit]);
    }
    
    public function getOverdue($loanId) {
        return $this->db->fetchAll("
            SELECT * FROM {$this->table} 
            WHERE loan_id = ? 
            AND due_date < CURDATE()
            AND (principal - principal_repaid_derived - principal_written_off_derived) > 0
            ORDER BY due_date ASC
        ", [$loanId]);
    }
}
