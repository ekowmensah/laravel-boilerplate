<?php
namespace App\Controllers;

class PayrollController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        $this->view('payroll/index', [
            'pageTitle' => 'Payroll Management'
        ]);
    }
    
    public function employees() {
        $this->requireAuth();
        
        $this->view('payroll/employees', [
            'pageTitle' => 'Employee Management'
        ]);
    }
    
    public function process() {
        $this->requireAuth();
        
        $this->view('payroll/process', [
            'pageTitle' => 'Process Payroll'
        ]);
    }
}
