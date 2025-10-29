<?php
namespace App\Controllers;

use App\Models\Client;
use App\Models\Loan;
use App\Models\Savings;

class DashboardController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        $clientModel = new Client();
        $loanModel = new Loan();
        $savingsModel = new Savings();
        
        $stats = [
            'total_clients' => $clientModel->countActive(),
            'total_loans' => $loanModel->countActive(),
            'total_savings' => $savingsModel->countActive(),
            'active_loan_amount' => $loanModel->getTotalOutstanding(),
            'total_savings_balance' => $savingsModel->getTotalBalance(),
            'pending_applications' => $loanModel->countPendingApplications(),
            'overdue_loans' => $loanModel->countOverdue(),
        ];
        
        $recentLoans = $loanModel->getRecent(10);
        $recentTransactions = $loanModel->getRecentTransactions(10);
        
        $this->view('dashboard/index', [
            'pageTitle' => 'Dashboard',
            'stats' => $stats,
            'recentLoans' => $recentLoans,
            'recentTransactions' => $recentTransactions
        ]);
    }
}
