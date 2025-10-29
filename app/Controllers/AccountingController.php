<?php
namespace App\Controllers;

class AccountingController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        $this->view('accounting/index', [
            'pageTitle' => 'Accounting & General Ledger'
        ]);
    }
    
    public function chartOfAccounts() {
        $this->requireAuth();
        
        $this->view('accounting/chart-of-accounts', [
            'pageTitle' => 'Chart of Accounts'
        ]);
    }
    
    public function journalEntries() {
        $this->requireAuth();
        
        $this->view('accounting/journal-entries', [
            'pageTitle' => 'Journal Entries'
        ]);
    }
    
    public function trialBalance() {
        $this->requireAuth();
        
        $this->view('accounting/trial-balance', [
            'pageTitle' => 'Trial Balance'
        ]);
    }
    
    public function financialStatements() {
        $this->requireAuth();
        
        $this->view('accounting/financial-statements', [
            'pageTitle' => 'Financial Statements'
        ]);
    }
}
