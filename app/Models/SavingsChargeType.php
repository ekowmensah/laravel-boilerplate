<?php
namespace App\Models;

class SavingsChargeType extends BaseModel {
    protected $table = 'savings_charge_types';
    
    public function all() {
        return $this->db->fetchAll("SELECT * FROM {$this->table} ORDER BY name ASC");
    }
}
