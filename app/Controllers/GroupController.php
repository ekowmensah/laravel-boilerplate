<?php
namespace App\Controllers;

use App\Models\Client;
use App\Models\Branch;
use App\Models\User;

class GroupController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        $clientModel = new Client();
        $groups = $clientModel->getGroups();
        
        $this->view('groups/index', [
            'pageTitle' => 'Groups Management',
            'groups' => $groups
        ]);
    }
    
    public function create() {
        $this->requireAuth();
        
        $errors = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $clientModel = new Client();
            $result = $clientModel->createGroup($_POST);
            
            if ($result['success']) {
                $this->setFlash('Group created successfully', 'success');
                $this->redirect('groups');
            } else {
                $errors = $result['errors'];
            }
        }
        
        $this->view('groups/create', [
            'pageTitle' => 'Create New Group',
            'errors' => $errors,
            'branches' => (new Branch())->getActive(),
            'loanOfficers' => (new User())->getActive(),
            'clientTypes' => (new \App\Models\ClientType())->all()
        ]);
    }
    
    public function addMember() {
        $this->requireAuth();
        
        $id = $this->params['id'] ?? null;
        if (!$id) {
            $this->redirect('groups');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $clientModel = new Client();
            $result = $clientModel->addGroupMember($id, $_POST);
            
            if ($result['success']) {
                $this->setFlash('Member added successfully', 'success');
            } else {
                $this->setFlash($result['message'], 'danger');
            }
            $this->redirect('groups/view/' . $id);
        }
        
        $clientModel = new Client();
        $group = $clientModel->find($id);
        $availableClients = $clientModel->getAvailableForGroup();
        
        $this->view('groups/add-member', [
            'pageTitle' => 'Add Group Member',
            'group' => $group,
            'clients' => $availableClients
        ]);
    }
    
    public function removeMember() {
        $this->requireAuth();
        
        $groupId = $this->params['id'] ?? null;
        $memberId = $_POST['member_id'] ?? null;
        
        if (!$groupId || !$memberId) {
            $this->setFlash('Invalid request', 'danger');
            $this->redirect('groups');
        }
        
        $clientModel = new Client();
        $result = $clientModel->removeGroupMember($groupId, $memberId);
        
        if ($result['success']) {
            $this->setFlash($result['message'], 'success');
        } else {
            $this->setFlash($result['message'], 'danger');
        }
        
        $this->redirect('groups/view/' . $groupId);
    }
    
    public function viewGroup() {
        $this->requireAuth();
        
        $id = $this->params['id'] ?? null;
        if (!$id) {
            $this->redirect('groups');
        }
        
        $clientModel = new Client();
        $group = $clientModel->getWithDetails($id);
        
        if (!$group) {
            $this->setFlash('Group not found', 'danger');
            $this->redirect('groups');
        }
        
        $members = $clientModel->getGroupMembers($id);
        
        // Get group loans
        $loanModel = new \App\Models\Loan();
        $loans = $loanModel->getByGroup($id);
        
        $this->view('groups/view', [
            'pageTitle' => 'Group Details',
            'group' => $group,
            'members' => $members,
            'loans' => $loans ?? []
        ]);
    }
}
