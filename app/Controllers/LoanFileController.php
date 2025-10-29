<?php
namespace App\Controllers;

use App\Models\LoanFile;

class LoanFileController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        $loanId = $this->params['loan_id'] ?? null;
        if (!$loanId) {
            $this->redirect('loans');
        }
        
        $model = new LoanFile();
        $files = $model->getLoanFiles($loanId);
        
        $this->view('loan-files/index', [
            'pageTitle' => 'Loan Documents',
            'loan_id' => $loanId,
            'files' => $files
        ]);
    }
    
    public function upload() {
        $this->requireAuth();
        
        $loanId = $this->params['loan_id'] ?? null;
        if (!$loanId) {
            $this->redirect('loans');
        }
        
        $errors = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model = new LoanFile();
            if ($model->uploadFile($loanId, $_POST, $_FILES)) {
                $this->setFlash('Document uploaded successfully', 'success');
                $this->redirect('loan-files/index/' . $loanId);
            } else {
                $errors[] = 'Failed to upload document';
            }
        }
        
        $this->view('loan-files/upload', [
            'pageTitle' => 'Upload Document',
            'loan_id' => $loanId,
            'errors' => $errors
        ]);
    }
}
