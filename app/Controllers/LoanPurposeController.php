<?php
namespace App\Controllers;

use App\Models\LoanPurpose;

class LoanPurposeController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        $model = new LoanPurpose();
        $purposes = $model->all();
        
        $this->view('loan-purposes/index', [
            'pageTitle' => 'Loan Purposes',
            'purposes' => $purposes
        ]);
    }
}
