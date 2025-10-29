<?php
namespace App\Controllers;

use App\Models\Currency;

class CurrencyController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        $model = new Currency();
        $currencies = $model->all();
        
        $this->view('currencies/index', [
            'pageTitle' => 'Currencies',
            'currencies' => $currencies
        ]);
    }
}
