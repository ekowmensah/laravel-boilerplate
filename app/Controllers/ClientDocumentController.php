<?php
namespace App\Controllers;

use App\Models\ClientFile;
use App\Models\ClientFileType;

class ClientDocumentController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        $clientId = $this->params['client_id'] ?? null;
        if (!$clientId) {
            $this->redirect('clients');
        }
        
        $fileModel = new ClientFile();
        $files = $fileModel->getClientFiles($clientId);
        
        $this->view('client-documents/index', [
            'pageTitle' => 'Client Documents',
            'client_id' => $clientId,
            'files' => $files
        ]);
    }
    
    public function upload() {
        $this->requireAuth();
        
        $clientId = $this->params['client_id'] ?? null;
        if (!$clientId) {
            $this->redirect('clients');
        }
        
        $errors = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fileModel = new ClientFile();
            if ($fileModel->uploadFile($clientId, $_POST, $_FILES)) {
                $this->setFlash('Document uploaded successfully', 'success');
                $this->redirect('client-documents/index/' . $clientId);
            } else {
                $errors[] = 'Failed to upload document';
            }
        }
        
        $this->view('client-documents/upload', [
            'pageTitle' => 'Upload Document',
            'client_id' => $clientId,
            'errors' => $errors,
            'fileTypes' => (new ClientFileType())->all()
        ]);
    }
}
