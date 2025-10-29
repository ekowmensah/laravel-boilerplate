<?php
namespace App\Models;

class LoanCharge extends BaseModel {
    protected $table = 'loan_charges';
    
    /**
     * Get charges for a loan
     */
    public function getByLoan($loanId) {
        return $this->fetchAll("
            SELECT lc.*, lco.name as charge_name, lco.amount as charge_amount, lco.charge_type
            FROM {$this->table} lc
            LEFT JOIN loan_charge_options lco ON lc.loan_charge_option_id = lco.id
            WHERE lc.loan_id = ?
            ORDER BY lc.created_at
        ", [$loanId]);
    }
    
    /**
     * Add charge to loan
     */
    public function addCharge($data) {
        $errors = $this->validateCharge($data);
        
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }
        
        try {
            $this->db->beginTransaction();
            
            // Get charge option details
            $chargeOption = $this->fetchOne("SELECT * FROM loan_charge_options WHERE id = ?", [$data['loan_charge_option_id']]);
            
            $amount = $data['amount'] ?? $chargeOption['amount'];
            
            $chargeData = [
                'loan_id' => $data['loan_id'],
                'loan_charge_option_id' => $data['loan_charge_option_id'],
                'amount' => $amount,
                'amount_paid_derived' => 0,
                'amount_waived_derived' => 0,
                'amount_written_off_derived' => 0,
                'amount_outstanding_derived' => $amount,
                'is_penalty' => $chargeOption['is_penalty'] ?? 0,
                'charge_time_type' => $chargeOption['charge_time_type'],
                'due_date' => $data['due_date'] ?? null,
                'created_by_id' => \Core\Session::getUserId()
            ];
            
            $chargeId = $this->create($chargeData);
            
            // Update loan fees
            $loan = $this->fetchOne("SELECT * FROM loans WHERE id = ?", [$data['loan_id']]);
            $this->db->update('loans', [
                'fees_disbursed_derived' => $loan['fees_disbursed_derived'] + $amount,
                'fees_outstanding_derived' => $loan['fees_outstanding_derived'] + $amount,
                'total_disbursed_derived' => $loan['total_disbursed_derived'] + $amount,
                'total_outstanding_derived' => $loan['total_outstanding_derived'] + $amount
            ], 'id = ?', [$data['loan_id']]);
            
            (new User())->logActivity('Add Loan Charge', \Core\Session::getUserId(), 'LoanCharge', $chargeId);
            
            $this->db->commit();
            
            return ['success' => true, 'charge_id' => $chargeId];
            
        } catch (\Exception $e) {
            $this->db->rollback();
            return ['success' => false, 'errors' => ['Error adding charge: ' . $e->getMessage()]];
        }
    }
    
    /**
     * Waive charge
     */
    public function waiveCharge($id) {
        try {
            $this->db->beginTransaction();
            
            $charge = $this->find($id);
            $outstanding = $charge['amount_outstanding_derived'];
            
            $this->update($id, [
                'amount_waived_derived' => $charge['amount_waived_derived'] + $outstanding,
                'amount_outstanding_derived' => 0,
                'waived_on_date' => date('Y-m-d'),
                'waived_by_user_id' => \Core\Session::getUserId()
            ]);
            
            // Update loan
            $loan = $this->fetchOne("SELECT * FROM loans WHERE id = ?", [$charge['loan_id']]);
            $this->db->update('loans', [
                'fees_waived_derived' => $loan['fees_waived_derived'] + $outstanding,
                'fees_outstanding_derived' => $loan['fees_outstanding_derived'] - $outstanding,
                'total_waived_derived' => $loan['total_waived_derived'] + $outstanding,
                'total_outstanding_derived' => $loan['total_outstanding_derived'] - $outstanding
            ], 'id = ?', [$charge['loan_id']]);
            
            (new User())->logActivity('Waive Loan Charge', \Core\Session::getUserId(), 'LoanCharge', $id);
            
            $this->db->commit();
            
            return ['success' => true, 'message' => 'Charge waived'];
            
        } catch (\Exception $e) {
            $this->db->rollback();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    private function validateCharge($data) {
        $errors = [];
        
        if (empty($data['loan_id'])) $errors[] = 'Loan is required';
        if (empty($data['loan_charge_option_id'])) $errors[] = 'Charge type is required';
        
        return $errors;
    }
}
