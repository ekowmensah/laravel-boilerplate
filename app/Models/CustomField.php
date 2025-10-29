<?php
namespace App\Models;

class CustomField extends BaseModel {
    protected $table = 'custom_fields';
    
    public function all() {
        return $this->db->fetchAll("SELECT * FROM {$this->table} ORDER BY category, name ASC");
    }
    
    public function createField($data) {
        return $this->db->insert($this->table, [
            'created_by_id' => $_SESSION['user_id'] ?? null,
            'category' => $data['category'],
            'name' => $data['name'],
            'type' => $data['type'],
            'required' => $data['required'] ?? 0,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}
