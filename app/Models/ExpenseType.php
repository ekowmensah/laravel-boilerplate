<?php
namespace App\Models;

class ExpenseType extends BaseModel {
    protected $table = 'expense_types';
    
    public function all() {
        return $this->db->fetchAll("SELECT * FROM {$this->table} ORDER BY name ASC");
    }
}
