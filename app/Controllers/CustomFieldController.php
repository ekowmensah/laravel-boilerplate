<?php
namespace App\Controllers;

use App\Models\CustomField;

class CustomFieldController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        $model = new CustomField();
        $fields = $model->all();
        
        $this->view('custom-fields/index', [
            'pageTitle' => 'Custom Fields',
            'fields' => $fields
        ]);
    }
    
    public function create() {
        $this->requireAuth();
        
        $errors = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model = new CustomField();
            if ($model->createField($_POST)) {
                $this->setFlash('Custom field created successfully', 'success');
                $this->redirect('custom-fields');
            } else {
                $errors[] = 'Failed to create custom field';
            }
        }
        
        $this->view('custom-fields/create', [
            'pageTitle' => 'Create Custom Field',
            'errors' => $errors
        ]);
    }
}
