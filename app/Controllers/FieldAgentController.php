<?php
namespace App\Controllers;

use App\Models\User;
use App\Models\Branch;

class FieldAgentController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        $userModel = new User();
        $agents = $userModel->getFieldAgents();
        
        $this->view('field-agents/index', [
            'pageTitle' => 'Field Agents Management',
            'agents' => $agents
        ]);
    }
    
    public function create() {
        $this->requireAuth();
        
        $errors = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle agent creation
            $this->setFlash('Field agent created successfully', 'success');
            $this->redirect('field-agents');
        }
        
        $this->view('field-agents/create', [
            'pageTitle' => 'Add Field Agent',
            'errors' => $errors,
            'branches' => (new Branch())->getActive()
        ]);
    }
    
    public function viewAgent() {
        $this->requireAuth();
        
        $id = $this->params['id'] ?? null;
        if (!$id) {
            $this->redirect('field-agents');
        }
        
        $userModel = new User();
        $agent = $userModel->getAgentWithStats($id);
        
        if (!$agent) {
            $this->setFlash('Field agent not found', 'danger');
            $this->redirect('field-agents');
        }
        
        $this->view('field-agents/view', [
            'pageTitle' => 'Field Agent Details',
            'agent' => $agent
        ]);
    }
}
