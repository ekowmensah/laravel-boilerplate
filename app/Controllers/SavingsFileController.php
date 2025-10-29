<?php
namespace App\Controllers;

use App\Models\SavingsFile;

class SavingsFileController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        $savingsId = $this->params['savings_id'] ?? null;
        if (!$savingsId) {
            $this->redirect('savings');
        }
        
        $model = new SavingsFile();
        $files = $model->getSavingsFiles($savingsId);
        
        $this->view('savings-files/index', [
            'pageTitle' => 'Savings Documents',
            'savings_id' => $savingsId,
            'files' => $files
        ]);
    }
}
