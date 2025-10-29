<?php
namespace App\Controllers;

use App\Models\ClientIdentification;
use App\Models\ClientIdentificationType;

class ClientIdentificationController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        $clientId = $this->params['client_id'] ?? null;
        if (!$clientId) {
            $this->redirect('clients');
        }
        
        $model = new ClientIdentification();
        $identifications = $model->getClientIdentifications($clientId);
        
        $this->view('client-identification/index', [
            'pageTitle' => 'Client Identification',
            'client_id' => $clientId,
            'identifications' => $identifications
        ]);
    }
    
    public function create() {
        $this->requireAuth();
        
        $clientId = $this->params['client_id'] ?? null;
        if (!$clientId) {
            $this->redirect('clients');
        }
        
        $errors = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model = new ClientIdentification();
            if ($model->createIdentification($clientId, $_POST)) {
                $this->setFlash('Identification added successfully', 'success');
                $this->redirect('client-identification/index/' . $clientId);
            } else {
                $errors[] = 'Failed to add identification';
            }
        }
        
        $this->view('client-identification/create', [
            'pageTitle' => 'Add Identification',
            'client_id' => $clientId,
            'errors' => $errors,
            'types' => (new ClientIdentificationType())->all()
        ]);
    }
}
