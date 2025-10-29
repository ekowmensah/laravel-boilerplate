<?php
namespace App\Models;

class Income extends BaseModel {
    protected $table = 'income';
    
    public function all() {
        return $this->db->fetchAll("SELECT i.*, it.name as income_type_name, b.name as branch_name 
                                     FROM {$this->table} i
                                     LEFT JOIN income_types it ON i.income_type_id = it.id
                                     LEFT JOIN branches b ON i.branch_id = b.id
                                     ORDER BY i.date DESC");
    }
    
    public function createIncome($data) {
        return $this->db->insert($this->table, [
            'created_by_id' => $_SESSION['user_id'] ?? null,
            'income_type_id' => $data['income_type_id'],
            'branch_id' => $data['branch_id'] ?? null,
            'amount' => $data['amount'],
            'date' => $data['date'],
            'description' => $data['description'] ?? null,
            'notes' => $data['notes'] ?? null,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}
