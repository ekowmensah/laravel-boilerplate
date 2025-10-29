<?php
namespace App\Controllers;

use App\Models\Income;
use App\Models\IncomeType;

class IncomeController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        $incomeModel = new Income();
        $income = $incomeModel->all();
        
        $this->view('income/index', [
            'pageTitle' => 'Income Management',
            'income' => $income
        ]);
    }
    
    public function create() {
        $this->requireAuth();
        
        $errors = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $incomeModel = new Income();
            if ($incomeModel->createIncome($_POST)) {
                $this->setFlash('Income recorded successfully', 'success');
                $this->redirect('income');
            } else {
                $errors[] = 'Failed to record income';
            }
        }
        
        $this->view('income/create', [
            'pageTitle' => 'Record Income',
            'errors' => $errors,
            'incomeTypes' => (new IncomeType())->all()
        ]);
    }
}
