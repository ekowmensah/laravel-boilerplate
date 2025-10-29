<?php
namespace App\Models;

class Fund extends BaseModel {
    protected $table = 'funds';
    
    public function all() {
        return $this->db->fetchAll("SELECT * FROM {$this->table} ORDER BY name ASC");
    }
}
