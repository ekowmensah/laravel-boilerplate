<?php
namespace App\Controllers;

use App\Models\OtherIncome;

class OtherIncomeController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        $model = new OtherIncome();
        $income = $model->all();
        
        $this->view('other-income/index', [
            'pageTitle' => 'Other Income',
            'income' => $income
        ]);
    }
}
