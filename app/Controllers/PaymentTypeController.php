<?php
namespace App\Controllers;

use App\Models\PaymentType;

class PaymentTypeController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        $model = new PaymentType();
        $types = $model->all();
        
        $this->view('payment-types/index', [
            'pageTitle' => 'Payment Types',
            'types' => $types
        ]);
    }
    
    public function create() {
        $this->requireAuth();
        
        $errors = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model = new PaymentType();
            if ($model->create($_POST)) {
                $this->setFlash('Payment type created successfully', 'success');
                $this->redirect('payment-types');
            } else {
                $errors[] = 'Failed to create payment type';
            }
        }
        
        $this->view('payment-types/create', [
            'pageTitle' => 'Create Payment Type',
            'errors' => $errors
        ]);
    }
}
