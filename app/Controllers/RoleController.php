<?php
namespace App\Controllers;

use App\Models\Role;
use App\Models\Permission;

class RoleController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        $roleModel = new Role();
        $roles = $roleModel->all();
        
        $this->view('roles/index', [
            'pageTitle' => 'Roles Management',
            'roles' => $roles
        ]);
    }
    
    public function create() {
        $this->requireAuth();
        
        $errors = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $roleModel = new Role();
            
            if ($roleModel->create($_POST)) {
                $this->setFlash('Role created successfully', 'success');
                $this->redirect('roles');
            } else {
                $errors[] = 'Failed to create role';
            }
        }
        
        $this->view('roles/create', [
            'pageTitle' => 'Create Role',
            'errors' => $errors
        ]);
    }
    
    public function edit() {
        $this->requireAuth();
        
        $id = $this->params['id'] ?? null;
        if (!$id) {
            $this->redirect('roles');
        }
        
        $roleModel = new Role();
        $role = $roleModel->find($id);
        
        if (!$role) {
            $this->setFlash('Role not found', 'danger');
            $this->redirect('roles');
        }
        
        $errors = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($roleModel->update($id, $_POST)) {
                $this->setFlash('Role updated successfully', 'success');
                $this->redirect('roles');
            } else {
                $errors[] = 'Failed to update role';
            }
        }
        
        $this->view('roles/edit', [
            'pageTitle' => 'Edit Role',
            'role' => $role,
            'errors' => $errors
        ]);
    }
    
    public function permissions() {
        $this->requireAuth();
        
        $id = $this->params['id'] ?? null;
        if (!$id) {
            $this->redirect('roles');
        }
        
        $roleModel = new Role();
        $permissionModel = new Permission();
        
        $role = $roleModel->find($id);
        if (!$role) {
            $this->setFlash('Role not found', 'danger');
            $this->redirect('roles');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $permissionIds = $_POST['permissions'] ?? [];
            $roleModel->syncPermissions($id, $permissionIds);
            $this->setFlash('Permissions updated successfully', 'success');
            $this->redirect('roles');
        }
        
        $allPermissions = $permissionModel->getByCategory();
        $rolePermissions = $roleModel->getPermissions($id);
        $rolePermissionIds = array_column($rolePermissions, 'id');
        
        $this->view('roles/permissions', [
            'pageTitle' => 'Manage Permissions',
            'role' => $role,
            'permissions' => $allPermissions,
            'rolePermissionIds' => $rolePermissionIds
        ]);
    }
    
    public function delete() {
        $this->requireAuth();
        
        $id = $this->params['id'] ?? null;
        if (!$id) {
            $this->redirect('roles');
        }
        
        $roleModel = new Role();
        
        if ($roleModel->delete($id)) {
            $this->setFlash('Role deleted successfully', 'success');
        } else {
            $this->setFlash('Failed to delete role', 'danger');
        }
        
        $this->redirect('roles');
    }
}
