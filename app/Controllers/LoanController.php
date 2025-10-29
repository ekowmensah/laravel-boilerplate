<?php
namespace App\Controllers;

use App\Models\Loan;
use App\Models\Client;
use App\Models\LoanProduct;
use App\Models\LoanRepaymentSchedule;
use App\Models\LoanTransaction;

class LoanController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        $status = $_GET['status'] ?? 'all';
        $clientType = $_GET['client_type'] ?? 'all';
        
        $loanModel = new Loan();
        $loans = $loanModel->getAllWithFilters($status, $clientType);
        
        $this->view('loans/index', [
            'pageTitle' => 'Loans Management',
            'loans' => $loans,
            'status' => $status,
            'clientType' => $clientType
        ]);
    }
    
    public function create() {
        $this->requireAuth();
        
        $errors = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $loanModel = new Loan();
            $result = $loanModel->createLoan($_POST);
            
            if ($result['success']) {
                $this->setFlash('Loan created successfully', 'success');
                $this->redirect('loans/view/' . $result['loan_id']);
            } else {
                $errors = $result['errors'];
            }
        }
        
        $this->view('loans/create', [
            'pageTitle' => 'Create New Loan',
            'errors' => $errors,
            'clients' => (new Client())->getActive(),
            'loanProducts' => (new LoanProduct())->getActive()
        ]);
    }
    
    public function viewLoan() {
        $this->requireAuth();
        
        $id = $this->params['id'] ?? null;
        if (!$id) {
            $this->redirect('loans');
        }
        
        $loanModel = new Loan();
        $loan = $loanModel->getWithDetails($id);
        
        if (!$loan) {
            $this->setFlash('Loan not found', 'danger');
            $this->redirect('loans');
        }
        
        $schedule = $loanModel->getRepaymentSchedule($id);
        $transactions = $loanModel->getTransactions($id);
        
        $this->view('loans/view', [
            'pageTitle' => 'Loan Details',
            'loan' => $loan,
            'schedule' => $schedule,
            'transactions' => $transactions
        ]);
    }
    
    public function approve() {
        $this->requireAuth();
        
        $id = $this->params['id'] ?? null;
        if (!$id) {
            $this->redirect('loans');
        }
        
        $loanModel = new Loan();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $loanModel->approveLoan($id, $_POST);
            
            if ($result['success']) {
                $this->setFlash('Loan approved successfully', 'success');
                $this->redirect('loans/view/' . $id);
            } else {
                $this->setFlash($result['message'], 'danger');
                $this->redirect('loans');
            }
        }
        
        $loan = $loanModel->find($id);
        
        $this->view('loans/approve', [
            'pageTitle' => 'Approve Loan',
            'loan' => $loan
        ]);
    }
    
    public function disburse() {
        $this->requireAuth();
        
        $id = $this->params['id'] ?? null;
        if (!$id) {
            $this->redirect('loans');
        }
        
        $loanModel = new Loan();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $loanModel->disburseLoan($id, $_POST);
            
            if ($result['success']) {
                $this->setFlash('Loan disbursed successfully', 'success');
                $this->redirect('loans/view/' . $id);
            } else {
                $this->setFlash($result['message'], 'danger');
                $this->redirect('loans');
            }
        }
        
        $loan = $loanModel->getWithDetails($id);
        
        $this->view('loans/disburse', [
            'pageTitle' => 'Disburse Loan',
            'loan' => $loan
        ]);
    }
    
    public function repayment() {
        $this->requireAuth();
        
        $id = $this->params['id'] ?? null;
        if (!$id) {
            $this->redirect('loans');
        }
        
        $loanModel = new Loan();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $result = $loanModel->processRepayment($id, $_POST);
                
                if ($result['success']) {
                    $this->setFlash($result['message'] . ' - Amount: ' . formatCurrency($_POST['amount']), 'success');
                } else {
                    $this->setFlash('Error: ' . $result['message'], 'danger');
                }
            } catch (\Exception $e) {
                $this->setFlash('Exception: ' . $e->getMessage(), 'danger');
            }
            $this->redirect('loans/view/' . $id);
        }
        
        $loan = $loanModel->getWithDetails($id);
        
        $this->view('loans/repayment', [
            'pageTitle' => 'Loan Repayment',
            'loan' => $loan
        ]);
    }
    
    public function schedule() {
        $this->requireAuth();
        
        $id = $this->params['id'] ?? null;
        if (!$id) {
            $this->redirect('loans');
        }
        
        $loanModel = new Loan();
        $scheduleModel = new LoanRepaymentSchedule();
        
        $loan = $loanModel->getWithDetails($id);
        $schedule = $scheduleModel->getByLoanId($id);
        
        // Calculate counts
        $paidCount = 0;
        $pendingCount = 0;
        $overdueCount = 0;
        
        foreach ($schedule as $installment) {
            $totalDue = ($installment['principal'] ?? 0) + ($installment['interest'] ?? 0) + 
                       ($installment['fees'] ?? 0) + ($installment['penalties'] ?? 0);
            $totalPaid = ($installment['principal_repaid_derived'] ?? 0) + 
                        ($installment['interest_repaid_derived'] ?? 0) + 
                        ($installment['fees_repaid_derived'] ?? 0) + 
                        ($installment['penalties_repaid_derived'] ?? 0);
            $balance = $totalDue - $totalPaid;
            
            if ($balance <= 0) {
                $paidCount++;
            } elseif (!empty($installment['due_date']) && strtotime($installment['due_date']) < time()) {
                $overdueCount++;
            } else {
                $pendingCount++;
            }
        }
        
        $this->view('loans/schedule', [
            'pageTitle' => 'Repayment Schedule',
            'loan' => $loan,
            'schedule' => $schedule,
            'paidCount' => $paidCount,
            'pendingCount' => $pendingCount,
            'overdueCount' => $overdueCount
        ]);
    }
    
    public function transactions() {
        $this->requireAuth();
        
        $id = $this->params['id'] ?? null;
        if (!$id) {
            $this->redirect('loans');
        }
        
        $loanModel = new Loan();
        $transactionModel = new LoanTransaction();
        
        $loan = $loanModel->getWithDetails($id);
        $transactions = $transactionModel->getByLoanId($id);
        
        $this->view('loans/transactions', [
            'pageTitle' => 'Loan Transactions',
            'loan' => $loan,
            'transactions' => $transactions
        ]);
    }
}
