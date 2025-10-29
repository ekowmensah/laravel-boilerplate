<?php
namespace App\Controllers;

use App\Models\LoanApplication;
use App\Models\LoanProduct;
use App\Models\Client;

class LoanApplicationController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        $model = new LoanApplication();
        $applications = $model->all();
        
        $this->view('loan-applications/index', [
            'pageTitle' => 'Loan Applications',
            'applications' => $applications
        ]);
    }
    
    public function create() {
        $this->requireAuth();
        
        $errors = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model = new LoanApplication();
            if ($model->createApplication($_POST)) {
                $this->setFlash('Application submitted successfully', 'success');
                $this->redirect('loan-applications');
            } else {
                $errors[] = 'Failed to submit application';
            }
        }
        
        $this->view('loan-applications/create', [
            'pageTitle' => 'New Loan Application',
            'errors' => $errors,
            'products' => (new LoanProduct())->all(),
            'clients' => (new Client())->all()
        ]);
    }
}
