<?php
namespace App\Models;

class ClientIdentificationType extends BaseModel {
    protected $table = 'client_identification_types';
    
    public function all() {
        return $this->db->fetchAll("SELECT * FROM {$this->table} ORDER BY name ASC");
    }
}
