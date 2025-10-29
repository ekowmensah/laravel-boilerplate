<?php
namespace App\Models;

class LoanGuarantor extends BaseModel {
    protected $table = 'loan_guarantors';
    
    /**
     * Get guarantors for a loan
     */
    public function getByLoan($loanId) {
        return $this->where('loan_id', $loanId);
    }
    
    /**
     * Add guarantor to loan
     */
    public function addGuarantor($data) {
        $errors = $this->validateGuarantor($data);
        
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }
        
        try {
            $guarantorData = [
                'loan_id' => $data['loan_id'],
                'client_id' => $data['client_id'] ?? null,
                'first_name' => sanitize($data['first_name']),
                'last_name' => sanitize($data['last_name']),
                'gender' => $data['gender'] ?? 'unspecified',
                'mobile' => sanitize($data['mobile'] ?? ''),
                'email' => sanitize($data['email'] ?? ''),
                'dob' => $data['dob'] ?? null,
                'address' => sanitize($data['address'] ?? ''),
                'city' => sanitize($data['city'] ?? ''),
                'relationship' => sanitize($data['relationship'] ?? ''),
                'amount' => $data['amount'],
                'status' => 'pending',
                'created_by_id' => \Core\Session::getUserId()
            ];
            
            $guarantorId = $this->create($guarantorData);
            
            (new User())->logActivity('Add Loan Guarantor', \Core\Session::getUserId(), 'LoanGuarantor', $guarantorId);
            
            return ['success' => true, 'guarantor_id' => $guarantorId];
            
        } catch (\Exception $e) {
            return ['success' => false, 'errors' => ['Error adding guarantor: ' . $e->getMessage()]];
        }
    }
    
    /**
     * Approve guarantor
     */
    public function approveGuarantor($id) {
        try {
            $this->update($id, [
                'status' => 'approved',
                'approved_on_date' => date('Y-m-d'),
                'approved_by_user_id' => \Core\Session::getUserId()
            ]);
            
            (new User())->logActivity('Approve Guarantor', \Core\Session::getUserId(), 'LoanGuarantor', $id);
            
            return ['success' => true, 'message' => 'Guarantor approved'];
            
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    private function validateGuarantor($data) {
        $errors = [];
        
        if (empty($data['loan_id'])) $errors[] = 'Loan is required';
        if (empty($data['first_name'])) $errors[] = 'First name is required';
        if (empty($data['last_name'])) $errors[] = 'Last name is required';
        if (empty($data['amount']) || $data['amount'] <= 0) $errors[] = 'Guarantee amount is required';
        
        return $errors;
    }
}
