<?php
namespace App\Controllers;

use App\Models\Branch;
use App\Models\LoanProduct;
use App\Models\SavingsProduct;
use App\Models\User;
use App\Models\Setting;

class SettingsController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        $this->view('settings/index', [
            'pageTitle' => 'System Settings'
        ]);
    }
    
    public function branches() {
        $this->requireAuth();
        
        $branchModel = new Branch();
        $branches = $branchModel->all();
        
        $this->view('settings/branches', [
            'pageTitle' => 'Branch Management',
            'branches' => $branches
        ]);
    }
    
    public function products() {
        $this->requireAuth();
        
        $loanProductModel = new LoanProduct();
        $savingsProductModel = new SavingsProduct();
        
        $loanProducts = $loanProductModel->all();
        $savingsProducts = $savingsProductModel->all();
        
        $this->view('settings/products', [
            'pageTitle' => 'Product Management',
            'loanProducts' => $loanProducts,
            'savingsProducts' => $savingsProducts
        ]);
    }
    
    public function users() {
        $this->requireAuth();
        
        $userModel = new User();
        $users = $userModel->all();
        
        $this->view('settings/users', [
            'pageTitle' => 'User Management',
            'users' => $users
        ]);
    }
    
    public function system() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle system settings update
            $this->setFlash('Settings updated successfully', 'success');
            $this->redirect('settings/system');
        }
        
        $this->view('settings/system', [
            'pageTitle' => 'System Settings'
        ]);
    }
    
    public function accountNumbers() {
        $this->requireAuth();
        
        $settingModel = new Setting();
        $success = null;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $type = $_POST['type'] ?? '';
            $prefix = $_POST['prefix'] ?? '';
            $format = $_POST['format'] ?? '';
            
            // Update settings
            $settingModel->setValue("{$type}.reference_prefix", $prefix);
            $settingModel->setValue("{$type}.reference_format", $format);
            
            $success = ucfirst($type) . ' account number settings updated successfully!';
        }
        
        // Get current settings
        $clientPrefix = $settingModel->getValue('client.reference_prefix', 'C19/');
        $clientFormat = $settingModel->getValue('client.reference_format', 'Branch Sequence Number');
        $loanPrefix = $settingModel->getValue('loan.reference_prefix', 'C19/L/');
        $loanFormat = $settingModel->getValue('loan.reference_format', 'Branch Product Sequence Number');
        $savingsPrefix = $settingModel->getValue('savings.reference_prefix', 'C19/');
        $savingsFormat = $settingModel->getValue('savings.reference_format', 'Branch Product Sequence Number');
        
        $this->view('settings/account-numbers', [
            'pageTitle' => 'Account Number Settings',
            'success' => $success,
            'clientPrefix' => $clientPrefix,
            'clientFormat' => $clientFormat,
            'loanPrefix' => $loanPrefix,
            'loanFormat' => $loanFormat,
            'savingsPrefix' => $savingsPrefix,
            'savingsFormat' => $savingsFormat
        ]);
    }
}
