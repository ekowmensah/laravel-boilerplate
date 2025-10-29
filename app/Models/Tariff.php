<?php
namespace App\Models;

class Tariff extends BaseModel {
    protected $table = 'tariffs';
    
    public function all() {
        return $this->db->fetchAll("SELECT * FROM {$this->table} ORDER BY name ASC");
    }
}
