<?php
namespace App\Models;

class ClientRelationship extends BaseModel {
    protected $table = 'client_relationships';
    
    public function all() {
        return $this->db->fetchAll("SELECT * FROM {$this->table} ORDER BY name ASC");
    }
}
