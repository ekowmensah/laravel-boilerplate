<?php
namespace App\Controllers;

use App\Models\Client;
use App\Models\ClientType;
use App\Models\Branch;
use App\Models\User;
use App\Models\Country;

class ClientController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        $clientModel = new Client();
        $clients = $clientModel->getAllWithDetails();
        
        $this->view('clients/index', [
            'pageTitle' => 'Clients Management',
            'clients' => $clients
        ]);
    }
    
    public function create() {
        $this->requireAuth();
        
        $errors = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $clientModel = new Client();
            $result = $clientModel->createClient($_POST);
            
            if ($result['success']) {
                $this->setFlash('Client created successfully', 'success');
                $this->redirect('clients');
            } else {
                $errors = $result['errors'];
            }
        }
        
        $this->view('clients/create', [
            'pageTitle' => 'Add New Client',
            'errors' => $errors,
            'clientTypes' => (new ClientType())->all(),
            'branches' => (new Branch())->getActive(),
            'loanOfficers' => (new User())->getActive(),
            'countries' => (new Country())->all()
        ]);
    }
    
    public function viewClient() {
        $this->requireAuth();
        
        $id = $this->params['id'] ?? null;
        if (!$id) {
            $this->redirect('clients');
        }
        
        $clientModel = new Client();
        $client = $clientModel->getWithDetails($id);
        
        if (!$client) {
            $this->setFlash('Client not found', 'danger');
            $this->redirect('clients');
        }
        
        $this->view('clients/view', [
            'pageTitle' => 'Client Details',
            'client' => $client
        ]);
    }
    
    public function edit() {
        $this->requireAuth();
        
        $id = $this->params['id'] ?? null;
        if (!$id) {
            $this->redirect('clients');
        }
        
        $clientModel = new Client();
        $client = $clientModel->find($id);
        
        if (!$client) {
            $this->setFlash('Client not found', 'danger');
            $this->redirect('clients');
        }
        
        $errors = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $clientModel->updateClient($id, $_POST);
            
            if ($result['success']) {
                $this->setFlash('Client updated successfully', 'success');
                $this->redirect('clients');
            } else {
                $errors = $result['errors'];
            }
        }
        
        $this->view('clients/edit', [
            'pageTitle' => 'Edit Client',
            'client' => $client,
            'errors' => $errors,
            'clientTypes' => (new ClientType())->all(),
            'branches' => (new Branch())->getActive(),
            'loanOfficers' => (new User())->getActive(),
            'countries' => (new Country())->all()
        ]);
    }
}
