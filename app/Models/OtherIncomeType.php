<?php
namespace App\Models;

class OtherIncomeType extends BaseModel {
    protected $table = 'other_income_types';
    
    public function all() {
        return $this->db->fetchAll("SELECT * FROM {$this->table} ORDER BY name ASC");
    }
}
