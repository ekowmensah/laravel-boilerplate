<?php
namespace App\Controllers;

use App\Models\ClientNextOfKin;
use App\Models\ClientRelationship;

class ClientNextOfKinController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        $clientId = $this->params['client_id'] ?? null;
        if (!$clientId) {
            $this->redirect('clients');
        }
        
        $model = new ClientNextOfKin();
        $nextOfKin = $model->getClientNextOfKin($clientId);
        
        $this->view('client-next-of-kin/index', [
            'pageTitle' => 'Next of Kin',
            'client_id' => $clientId,
            'nextOfKin' => $nextOfKin
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
            $model = new ClientNextOfKin();
            if ($model->createNextOfKin($clientId, $_POST)) {
                $this->setFlash('Next of kin added successfully', 'success');
                $this->redirect('client-next-of-kin/index/' . $clientId);
            } else {
                $errors[] = 'Failed to add next of kin';
            }
        }
        
        $this->view('client-next-of-kin/create', [
            'pageTitle' => 'Add Next of Kin',
            'client_id' => $clientId,
            'errors' => $errors,
            'relationships' => (new ClientRelationship())->all()
        ]);
    }
}
