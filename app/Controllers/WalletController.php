<?php
namespace App\Controllers;

class WalletController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        $this->view('wallet/index', [
            'pageTitle' => 'Wallet Management'
        ]);
    }
    
    public function transactions() {
        $this->requireAuth();
        
        $this->view('wallet/transactions', [
            'pageTitle' => 'Wallet Transactions'
        ]);
    }
}
