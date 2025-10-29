<?php
namespace App\Controllers;

class CommunicationController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        $this->view('communication/index', [
            'pageTitle' => 'Communication Management'
        ]);
    }
    
    public function sms() {
        $this->requireAuth();
        
        $this->view('communication/sms', [
            'pageTitle' => 'SMS Management'
        ]);
    }
    
    public function email() {
        $this->requireAuth();
        
        $this->view('communication/email', [
            'pageTitle' => 'Email Management'
        ]);
    }
    
    public function campaigns() {
        $this->requireAuth();
        
        $this->view('communication/campaigns', [
            'pageTitle' => 'Campaigns'
        ]);
    }
}
