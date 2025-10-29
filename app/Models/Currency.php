<?php
namespace App\Models;

class Currency extends BaseModel {
    protected $table = 'currencies';
    
    public function all() {
        return $this->db->fetchAll("SELECT * FROM {$this->table} ORDER BY name ASC");
    }
}
