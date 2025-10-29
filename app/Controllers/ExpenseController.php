<?php
namespace App\Controllers;

use App\Models\Expense;
use App\Models\ExpenseType;

class ExpenseController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        $expenseModel = new Expense();
        $expenses = $expenseModel->all();
        
        $this->view('expenses/index', [
            'pageTitle' => 'Expense Management',
            'expenses' => $expenses
        ]);
    }
    
    public function create() {
        $this->requireAuth();
        
        $errors = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $expenseModel = new Expense();
            if ($expenseModel->createExpense($_POST)) {
                $this->setFlash('Expense recorded successfully', 'success');
                $this->redirect('expenses');
            } else {
                $errors[] = 'Failed to record expense';
            }
        }
        
        $this->view('expenses/create', [
            'pageTitle' => 'Record Expense',
            'errors' => $errors,
            'expenseTypes' => (new ExpenseType())->all()
        ]);
    }
    
    public function viewExpense() {
        $this->requireAuth();
        
        $id = $this->params['id'] ?? null;
        if (!$id) {
            $this->redirect('expenses');
        }
        
        $expenseModel = new Expense();
        $expense = $expenseModel->find($id);
        
        if (!$expense) {
            $this->setFlash('Expense not found', 'danger');
            $this->redirect('expenses');
        }
        
        $this->view('expenses/view', [
            'pageTitle' => 'Expense Details',
            'expense' => $expense
        ]);
    }
}
