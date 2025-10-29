<?php
namespace App\Models;

class Role extends BaseModel {
    protected $table = 'roles';
    
    public function all() {
        return $this->db->fetchAll("SELECT * FROM {$this->table} ORDER BY name ASC");
    }
    
    public function find($id) {
        return $this->db->fetchOne("SELECT * FROM {$this->table} WHERE id = ?", [$id]);
    }
    
    public function create($data) {
        return $this->db->insert($this->table, [
            'name' => $data['name'],
            'display_name' => $data['display_name'],
            'description' => $data['description'] ?? null,
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
                'updated_at' => date('Y-m-d H:i:s')
            ],
            "id = ?",
            [$id]
        );
    }
    
    public function delete($id) {
        // Delete role permissions first
        $this->db->delete('role_has_permissions', 'role_id = ?', [$id]);
        
        // Delete user roles
        $this->db->delete('model_has_roles', 'role_id = ?', [$id]);
        
        // Delete role
        return $this->db->delete($this->table, 'id = ?', [$id]);
    }
    
    public function getPermissions($roleId) {
        return $this->db->fetchAll(
            "SELECT p.* FROM permissions p
             INNER JOIN role_has_permissions rp ON p.id = rp.permission_id
             WHERE rp.role_id = ?
             ORDER BY p.name ASC",
            [$roleId]
        );
    }
    
    public function assignPermission($roleId, $permissionId) {
        return $this->db->insert('role_has_permissions', [
            'role_id' => $roleId,
            'permission_id' => $permissionId
        ]);
    }
    
    public function removePermission($roleId, $permissionId) {
        return $this->db->delete('role_has_permissions', 'role_id = ? AND permission_id = ?', [$roleId, $permissionId]);
    }
    
    public function syncPermissions($roleId, $permissionIds) {
        // Delete existing permissions
        $this->db->delete('role_has_permissions', 'role_id = ?', [$roleId]);
        
        // Add new permissions
        if (!empty($permissionIds)) {
            foreach ($permissionIds as $permissionId) {
                $this->db->insert('role_has_permissions', [
                    'role_id' => $roleId,
                    'permission_id' => $permissionId
                ]);
            }
        }
        
        return true;
    }
    
    public function getUserCount($roleId) {
        $result = $this->db->fetchOne("SELECT COUNT(*) as count FROM user_roles WHERE role_id = ?", [$roleId]);
        return $result['count'] ?? 0;
    }
}
