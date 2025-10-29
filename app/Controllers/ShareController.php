<?php
namespace App\Controllers;

class ShareController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        $this->view('shares/index', [
            'pageTitle' => 'Share Management'
        ]);
    }
    
    public function products() {
        $this->requireAuth();
        
        $this->view('shares/products', [
            'pageTitle' => 'Share Products'
        ]);
    }
    
    public function transactions() {
        $this->requireAuth();
        
        $this->view('shares/transactions', [
            'pageTitle' => 'Share Transactions'
        ]);
    }
}
