<?php
namespace App\Models;

class SavingsCharge extends BaseModel {
    protected $table = 'savings_charges';
    
    public function all() {
        return $this->db->fetchAll("SELECT sc.*, sct.name as type_name 
                                     FROM {$this->table} sc
                                     LEFT JOIN savings_charge_types sct ON sc.savings_charge_type_id = sct.id
                                     ORDER BY sc.name ASC");
    }
    
    public function createCharge($data) {
        return $this->db->insert($this->table, [
            'created_by_id' => $_SESSION['user_id'] ?? null,
            'savings_charge_type_id' => $data['charge_type_id'],
            'name' => $data['name'],
            'amount' => $data['amount'] ?? null,
            'percentage' => $data['percentage'] ?? null,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}
