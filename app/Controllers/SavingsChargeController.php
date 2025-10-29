<?php
namespace App\Controllers;

use App\Models\SavingsCharge;
use App\Models\SavingsChargeType;

class SavingsChargeController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        $model = new SavingsCharge();
        $charges = $model->all();
        
        $this->view('savings-charges/index', [
            'pageTitle' => 'Savings Charges',
            'charges' => $charges
        ]);
    }
    
    public function create() {
        $this->requireAuth();
        
        $errors = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model = new SavingsCharge();
            if ($model->createCharge($_POST)) {
                $this->setFlash('Charge created successfully', 'success');
                $this->redirect('savings-charges');
            } else {
                $errors[] = 'Failed to create charge';
            }
        }
        
        $this->view('savings-charges/create', [
            'pageTitle' => 'Create Savings Charge',
            'errors' => $errors,
            'chargeTypes' => (new SavingsChargeType())->all()
        ]);
    }
}
