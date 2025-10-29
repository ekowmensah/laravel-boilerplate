<?php
namespace App\Controllers;

use App\Models\LoanCharge;
use App\Models\LoanChargeType;

class LoanChargeController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        $model = new LoanCharge();
        $charges = $model->all();
        
        $this->view('loan-charges/index', [
            'pageTitle' => 'Loan Charges',
            'charges' => $charges
        ]);
    }
    
    public function create() {
        $this->requireAuth();
        
        $errors = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model = new LoanCharge();
            if ($model->createCharge($_POST)) {
                $this->setFlash('Charge created successfully', 'success');
                $this->redirect('loan-charges');
            } else {
                $errors[] = 'Failed to create charge';
            }
        }
        
        $this->view('loan-charges/create', [
            'pageTitle' => 'Create Loan Charge',
            'errors' => $errors,
            'chargeTypes' => (new LoanChargeType())->all()
        ]);
    }
}
