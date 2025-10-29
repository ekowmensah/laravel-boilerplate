<?php
namespace App\Models;

class Investor extends BaseModel {
    protected $table = 'investors';
    
    public function all() {
        return $this->db->fetchAll("SELECT * FROM {$this->table} ORDER BY name ASC");
    }
    
    public function createInvestor($data) {
        return $this->db->insert($this->table, [
            'created_by_id' => $_SESSION['user_id'] ?? null,
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'notes' => $data['notes'] ?? null,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    public function getFunds($investorId) {
        return $this->db->fetchAll("SELECT * FROM investor_funds WHERE investor_id = ? ORDER BY date DESC", [$investorId]);
    }
}
