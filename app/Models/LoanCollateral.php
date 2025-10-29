<?php
namespace App\Models;

class LoanCollateral extends BaseModel {
    protected $table = 'loan_collateral';
    
    /**
     * Get collateral for a loan
     */
    public function getByLoan($loanId) {
        return $this->where('loan_id', $loanId);
    }
    
    /**
     * Add collateral to loan
     */
    public function addCollateral($data) {
        $errors = $this->validateCollateral($data);
        
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }
        
        try {
            $collateralData = [
                'loan_id' => $data['loan_id'],
                'collateral_type_id' => $data['collateral_type_id'],
                'name' => sanitize($data['name']),
                'description' => sanitize($data['description'] ?? ''),
                'value' => $data['value'],
                'status' => 'pending',
                'created_by_id' => \Core\Session::getUserId()
            ];
            
            $collateralId = $this->create($collateralData);
            
            (new User())->logActivity('Add Loan Collateral', \Core\Session::getUserId(), 'LoanCollateral', $collateralId);
            
            return ['success' => true, 'collateral_id' => $collateralId];
            
        } catch (\Exception $e) {
            return ['success' => false, 'errors' => ['Error adding collateral: ' . $e->getMessage()]];
        }
    }
    
    /**
     * Release collateral
     */
    public function releaseCollateral($id) {
        try {
            $this->update($id, [
                'status' => 'released',
                'released_on_date' => date('Y-m-d'),
                'released_by_user_id' => \Core\Session::getUserId()
            ]);
            
            (new User())->logActivity('Release Collateral', \Core\Session::getUserId(), 'LoanCollateral', $id);
            
            return ['success' => true, 'message' => 'Collateral released'];
            
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    private function validateCollateral($data) {
        $errors = [];
        
        if (empty($data['loan_id'])) $errors[] = 'Loan is required';
        if (empty($data['collateral_type_id'])) $errors[] = 'Collateral type is required';
        if (empty($data['name'])) $errors[] = 'Collateral name is required';
        if (empty($data['value']) || $data['value'] <= 0) $errors[] = 'Collateral value is required';
        
        return $errors;
    }
}
