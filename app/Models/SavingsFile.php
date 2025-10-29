<?php
namespace App\Models;

class SavingsFile extends BaseModel {
    protected $table = 'savings_files';
    
    public function getSavingsFiles($savingsId) {
        return $this->db->fetchAll("SELECT * FROM {$this->table} WHERE savings_id = ? ORDER BY created_at DESC", [$savingsId]);
    }
}
