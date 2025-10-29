<?php
namespace App\Controllers;

use App\Models\Fund;

class FundController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        $model = new Fund();
        $funds = $model->all();
        
        $this->view('funds/index', [
            'pageTitle' => 'Funds',
            'funds' => $funds
        ]);
    }
}
