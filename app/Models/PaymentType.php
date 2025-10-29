<?php
namespace App\Models;

class PaymentType extends BaseModel {
    protected $table = 'payment_types';
    
    public function all() {
        return $this->db->fetchAll("SELECT * FROM {$this->table} ORDER BY name ASC");
    }
}
