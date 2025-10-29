<?php
namespace App\Controllers;

use App\Models\Savings;
use App\Models\Client;
use App\Models\SavingsProduct;

class SavingsController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        $savingsModel = new Savings();
        $savings = $savingsModel->getAllWithDetails();
        
        $this->view('savings/index', [
            'pageTitle' => 'Savings Management',
            'savings' => $savings
        ]);
    }
    
    public function create() {
        $this->requireAuth();
        
        $errors = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $savingsModel = new Savings();
            $result = $savingsModel->createSavings($_POST);
            
            if ($result['success']) {
                $this->setFlash('Savings account created successfully', 'success');
                $this->redirect('savings');
            } else {
                $errors = $result['errors'];
            }
        }
        
        $this->view('savings/create', [
            'pageTitle' => 'Create Savings Account',
            'errors' => $errors,
            'clients' => (new Client())->getActive(),
            'products' => (new SavingsProduct())->getActive(),
            'branches' => (new \App\Models\Branch())->getActive()
        ]);
    }
    
    public function approve() {
        $this->requireAuth();
        
        $id = $this->params['id'] ?? null;
        if (!$id) {
            $this->redirect('savings');
        }
        
        $savingsModel = new Savings();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $savingsModel->approveSavings($id, $_POST);
            
            if ($result['success']) {
                $this->setFlash($result['message'], 'success');
            } else {
                $this->setFlash($result['message'], 'danger');
            }
            $this->redirect('savings/view/' . $id);
        }
        
        $savings = $savingsModel->find($id);
        
        $this->view('savings/approve', [
            'pageTitle' => 'Approve Savings Account',
            'savings' => $savings
        ]);
    }
    
    public function activate() {
        $this->requireAuth();
        
        $id = $this->params['id'] ?? null;
        if (!$id) {
            $this->redirect('savings');
        }
        
        $savingsModel = new Savings();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $savingsModel->activateSavings($id, $_POST);
            
            if ($result['success']) {
                $this->setFlash($result['message'], 'success');
            } else {
                $this->setFlash($result['message'], 'danger');
            }
            $this->redirect('savings/view/' . $id);
        }
        
        $savings = $savingsModel->find($id);
        
        $this->view('savings/activate', [
            'pageTitle' => 'Activate Savings Account',
            'savings' => $savings
        ]);
    }
    
    public function deposit() {
        $this->requireAuth();
        
        $id = $this->params['id'] ?? null;
        if (!$id) {
            $this->redirect('savings');
        }
        
        $savingsModel = new Savings();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $savingsModel->processDeposit($id, $_POST);
            
            if ($result['success']) {
                $this->setFlash($result['message'], 'success');
            } else {
                $this->setFlash($result['message'], 'danger');
            }
            $this->redirect('savings/view/' . $id);
        }
        
        $savings = $savingsModel->getWithDetails($id);
        
        $this->view('savings/deposit', [
            'pageTitle' => 'Savings Deposit',
            'savings' => $savings
        ]);
    }
    
    public function withdraw() {
        $this->requireAuth();
        
        $id = $this->params['id'] ?? null;
        if (!$id) {
            $this->redirect('savings');
        }
        
        $savingsModel = new Savings();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $savingsModel->processWithdrawal($id, $_POST);
            
            if ($result['success']) {
                $this->setFlash($result['message'], 'success');
            } else {
                $this->setFlash($result['message'], 'danger');
            }
            $this->redirect('savings/view/' . $id);
        }
        
        $savings = $savingsModel->getWithDetails($id);
        
        $this->view('savings/withdraw', [
            'pageTitle' => 'Savings Withdrawal',
            'savings' => $savings
        ]);
    }
    
    public function viewSavings() {
        $this->requireAuth();
        
        $id = $this->params['id'] ?? null;
        if (!$id) {
            $this->redirect('savings');
        }
        
        $savingsModel = new Savings();
        $savings = $savingsModel->getWithDetails($id);
        
        if (!$savings) {
            $this->setFlash('Savings account not found', 'danger');
            $this->redirect('savings');
        }
        
        $transactions = $savingsModel->getTransactions($id);
        
        $this->view('savings/view', [
            'pageTitle' => 'Savings Account Details',
            'savings' => $savings,
            'transactions' => $transactions
        ]);
    }
}
