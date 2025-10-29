<?php
namespace App\Models;

class Permission extends BaseModel {
    protected $table = 'permissions';
    
    public function all() {
        return $this->db->fetchAll("SELECT * FROM {$this->table} ORDER BY module, name ASC");
    }
    
    public function find($id) {
        return $this->db->fetchOne("SELECT * FROM {$this->table} WHERE id = ?", [$id]);
    }
    
    public function create($data) {
        return $this->db->insert($this->table, [
            'name' => $data['name'],
            'display_name' => $data['display_name'],
            'description' => $data['description'] ?? null,
            'module' => $data['module'] ?? 'Core',
            'guard_name' => 'web',
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    public function update($id, $data) {
        return $this->db->update(
            $this->table,
            [
                'name' => $data['name'],
                'display_name' => $data['display_name'],
                'description' => $data['description'] ?? null,
                'module' => $data['module'] ?? 'Core',
                'updated_at' => date('Y-m-d H:i:s')
            ],
            "id = ?",
            [$id]
        );
    }
    
    public function delete($id) {
        // Delete from role_has_permissions first
        $this->db->delete('role_has_permissions', 'permission_id = ?', [$id]);
        
        // Delete permission
        return $this->db->delete($this->table, 'id = ?', [$id]);
    }
    
    public function getByCategory() {
        $permissions = $this->db->fetchAll("SELECT * FROM {$this->table} ORDER BY module, name ASC");
        
        $grouped = [];
        foreach ($permissions as $permission) {
            $module = $permission['module'] ?? 'Core';
            if (!isset($grouped[$module])) {
                $grouped[$module] = [];
            }
            $grouped[$module][] = $permission;
        }
        
        return $grouped;
    }
    
    public function seedDefaultPermissions() {
        $permissions = [
            // Dashboard
            ['name' => 'view_dashboard', 'display_name' => 'View Dashboard', 'category' => 'dashboard'],
            
            // Clients
            ['name' => 'view_clients', 'display_name' => 'View Clients', 'category' => 'clients'],
            ['name' => 'create_clients', 'display_name' => 'Create Clients', 'category' => 'clients'],
            ['name' => 'edit_clients', 'display_name' => 'Edit Clients', 'category' => 'clients'],
            ['name' => 'delete_clients', 'display_name' => 'Delete Clients', 'category' => 'clients'],
            
            // Groups
            ['name' => 'view_groups', 'display_name' => 'View Groups', 'category' => 'groups'],
            ['name' => 'create_groups', 'display_name' => 'Create Groups', 'category' => 'groups'],
            ['name' => 'edit_groups', 'display_name' => 'Edit Groups', 'category' => 'groups'],
            ['name' => 'delete_groups', 'display_name' => 'Delete Groups', 'category' => 'groups'],
            
            // Loans
            ['name' => 'view_loans', 'display_name' => 'View Loans', 'category' => 'loans'],
            ['name' => 'create_loans', 'display_name' => 'Create Loans', 'category' => 'loans'],
            ['name' => 'approve_loans', 'display_name' => 'Approve Loans', 'category' => 'loans'],
            ['name' => 'disburse_loans', 'display_name' => 'Disburse Loans', 'category' => 'loans'],
            ['name' => 'repay_loans', 'display_name' => 'Process Repayments', 'category' => 'loans'],
            ['name' => 'writeoff_loans', 'display_name' => 'Write-off Loans', 'category' => 'loans'],
            
            // Savings
            ['name' => 'view_savings', 'display_name' => 'View Savings', 'category' => 'savings'],
            ['name' => 'create_savings', 'display_name' => 'Create Savings', 'category' => 'savings'],
            ['name' => 'approve_savings', 'display_name' => 'Approve Savings', 'category' => 'savings'],
            ['name' => 'deposit_savings', 'display_name' => 'Process Deposits', 'category' => 'savings'],
            ['name' => 'withdraw_savings', 'display_name' => 'Process Withdrawals', 'category' => 'savings'],
            
            // Reports
            ['name' => 'view_reports', 'display_name' => 'View Reports', 'category' => 'reports'],
            ['name' => 'export_reports', 'display_name' => 'Export Reports', 'category' => 'reports'],
            
            // Settings
            ['name' => 'manage_settings', 'display_name' => 'Manage Settings', 'category' => 'settings'],
            ['name' => 'manage_users', 'display_name' => 'Manage Users', 'category' => 'settings'],
            ['name' => 'manage_roles', 'display_name' => 'Manage Roles', 'category' => 'settings'],
            ['name' => 'manage_branches', 'display_name' => 'Manage Branches', 'category' => 'settings'],
            ['name' => 'manage_products', 'display_name' => 'Manage Products', 'category' => 'settings'],
        ];
        
        foreach ($permissions as $permission) {
            $this->create($permission);
        }
        
        return true;
    }
}
