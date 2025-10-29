<?php
namespace App\Models;

class IncomeType extends BaseModel {
    protected $table = 'income_types';
    
    public function all() {
        return $this->db->fetchAll("SELECT * FROM {$this->table} ORDER BY name ASC");
    }
}
