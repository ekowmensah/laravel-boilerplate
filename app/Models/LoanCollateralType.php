<?php
namespace App\Models;

class LoanCollateralType extends BaseModel {
    protected $table = 'loan_collateral_types';
    
    public function all() {
        return $this->db->fetchAll("SELECT * FROM {$this->table} ORDER BY name ASC");
    }
}
