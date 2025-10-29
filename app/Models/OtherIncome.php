<?php
namespace App\Models;

class OtherIncome extends BaseModel {
    protected $table = 'other_income';
    
    public function all() {
        return $this->db->fetchAll("SELECT oi.*, oit.name as type_name 
                                     FROM {$this->table} oi
                                     LEFT JOIN other_income_types oit ON oi.other_income_type_id = oit.id
                                     ORDER BY oi.date DESC");
    }
}
