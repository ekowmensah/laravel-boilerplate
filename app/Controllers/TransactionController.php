<?php
namespace App\Controllers;

use App\Models\Loan;
use App\Models\Savings;

class TransactionController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        $type = $_GET['type'] ?? 'all';
        
        $loanModel = new Loan();
        $savingsModel = new Savings();
        
        $loanTransactions = $loanModel->getRecentTransactions(50);
        $savingsTransactions = $savingsModel->getRecentTransactions(50);
        
        // Merge and sort
        $allTransactions = array_merge($loanTransactions, $savingsTransactions);
        usort($allTransactions, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        
        $this->view('transactions/index', [
            'pageTitle' => 'All Transactions',
            'transactions' => $allTransactions,
            'type' => $type
        ]);
    }
}
