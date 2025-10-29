<?php
namespace App\Controllers;

use App\Models\LoanCollateral;
use App\Models\LoanCollateralType;

class LoanCollateralController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        $loanId = $this->params['loan_id'] ?? null;
        if (!$loanId) {
            $this->redirect('loans');
        }
        
        $model = new LoanCollateral();
        $collateral = $model->getLoanCollateral($loanId);
        
        $this->view('loan-collateral/index', [
            'pageTitle' => 'Loan Collateral',
            'loan_id' => $loanId,
            'collateral' => $collateral
        ]);
    }
    
    public function create() {
        $this->requireAuth();
        
        $loanId = $this->params['loan_id'] ?? null;
        if (!$loanId) {
            $this->redirect('loans');
        }
        
        $errors = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model = new LoanCollateral();
            if ($model->createCollateral($loanId, $_POST)) {
                $this->setFlash('Collateral added successfully', 'success');
                $this->redirect('loan-collateral/index/' . $loanId);
            } else {
                $errors[] = 'Failed to add collateral';
            }
        }
        
        $this->view('loan-collateral/create', [
            'pageTitle' => 'Add Collateral',
            'loan_id' => $loanId,
            'errors' => $errors,
            'types' => (new LoanCollateralType())->all()
        ]);
    }
}
