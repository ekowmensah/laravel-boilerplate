<?php
namespace App\Controllers;

use App\Models\Tariff;

class TariffController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        $model = new Tariff();
        $tariffs = $model->all();
        
        $this->view('tariffs/index', [
            'pageTitle' => 'Tariffs',
            'tariffs' => $tariffs
        ]);
    }
}
