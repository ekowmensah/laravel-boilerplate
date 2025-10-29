<?php
namespace App\Controllers;

use App\Models\Asset;
use App\Models\AssetType;
use App\Models\Branch;

class AssetController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        $assetModel = new Asset();
        $assets = $assetModel->all();
        
        $this->view('assets/index', [
            'pageTitle' => 'Asset Management',
            'assets' => $assets
        ]);
    }
    
    public function create() {
        $this->requireAuth();
        
        $errors = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $assetModel = new Asset();
            $result = $assetModel->createAsset($_POST);
            
            if ($result) {
                $this->setFlash('Asset created successfully', 'success');
                $this->redirect('assets');
            } else {
                $errors[] = 'Failed to create asset';
            }
        }
        
        $this->view('assets/create', [
            'pageTitle' => 'Add Asset',
            'errors' => $errors,
            'assetTypes' => (new AssetType())->all(),
            'branches' => (new Branch())->all()
        ]);
    }
    
    public function viewAsset() {
        $this->requireAuth();
        
        $id = $this->params['id'] ?? null;
        if (!$id) {
            $this->redirect('assets');
        }
        
        $assetModel = new Asset();
        $asset = $assetModel->find($id);
        
        if (!$asset) {
            $this->setFlash('Asset not found', 'danger');
            $this->redirect('assets');
        }
        
        $this->view('assets/view', [
            'pageTitle' => 'Asset Details',
            'asset' => $asset
        ]);
    }
    
    public function edit() {
        $this->requireAuth();
        
        $id = $this->params['id'] ?? null;
        if (!$id) {
            $this->redirect('assets');
        }
        
        $assetModel = new Asset();
        $asset = $assetModel->find($id);
        
        if (!$asset) {
            $this->setFlash('Asset not found', 'danger');
            $this->redirect('assets');
        }
        
        $errors = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($assetModel->updateAsset($id, $_POST)) {
                $this->setFlash('Asset updated successfully', 'success');
                $this->redirect('assets');
            } else {
                $errors[] = 'Failed to update asset';
            }
        }
        
        $this->view('assets/edit', [
            'pageTitle' => 'Edit Asset',
            'asset' => $asset,
            'errors' => $errors,
            'assetTypes' => (new AssetType())->all(),
            'branches' => (new Branch())->all()
        ]);
    }
    
    public function depreciation() {
        $this->requireAuth();
        
        $id = $this->params['id'] ?? null;
        if (!$id) {
            $this->redirect('assets');
        }
        
        $assetModel = new Asset();
        $asset = $assetModel->find($id);
        $depreciation = $assetModel->getDepreciation($id);
        
        $this->view('assets/depreciation', [
            'pageTitle' => 'Asset Depreciation',
            'asset' => $asset,
            'depreciation' => $depreciation
        ]);
    }
    
    public function maintenance() {
        $this->requireAuth();
        
        $id = $this->params['id'] ?? null;
        if (!$id) {
            $this->redirect('assets');
        }
        
        $assetModel = new Asset();
        $asset = $assetModel->find($id);
        $maintenance = $assetModel->getMaintenance($id);
        
        $this->view('assets/maintenance', [
            'pageTitle' => 'Asset Maintenance',
            'asset' => $asset,
            'maintenance' => $maintenance
        ]);
    }
    
    public function delete() {
        $this->requireAuth();
        
        $id = $this->params['id'] ?? null;
        if (!$id) {
            $this->redirect('assets');
        }
        
        $assetModel = new Asset();
        if ($assetModel->delete($id)) {
            $this->setFlash('Asset deleted successfully', 'success');
        } else {
            $this->setFlash('Failed to delete asset', 'danger');
        }
        
        $this->redirect('assets');
    }
}
