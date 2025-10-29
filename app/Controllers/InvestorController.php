<?php
namespace App\Controllers;

use App\Models\Investor;
use App\Models\InvestorFund;

class InvestorController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        $investorModel = new Investor();
        $investors = $investorModel->all();
        
        $this->view('investors/index', [
            'pageTitle' => 'Investor Management',
            'investors' => $investors
        ]);
    }
    
    public function create() {
        $this->requireAuth();
        
        $errors = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $investorModel = new Investor();
            if ($investorModel->createInvestor($_POST)) {
                $this->setFlash('Investor created successfully', 'success');
                $this->redirect('investors');
            } else {
                $errors[] = 'Failed to create investor';
            }
        }
        
        $this->view('investors/create', [
            'pageTitle' => 'Add Investor',
            'errors' => $errors
        ]);
    }
    
    public function viewInvestor() {
        $this->requireAuth();
        
        $id = $this->params['id'] ?? null;
        if (!$id) {
            $this->redirect('investors');
        }
        
        $investorModel = new Investor();
        $investor = $investorModel->find($id);
        $funds = $investorModel->getFunds($id);
        
        if (!$investor) {
            $this->setFlash('Investor not found', 'danger');
            $this->redirect('investors');
        }
        
        $this->view('investors/view', [
            'pageTitle' => 'Investor Details',
            'investor' => $investor,
            'funds' => $funds
        ]);
    }
}
