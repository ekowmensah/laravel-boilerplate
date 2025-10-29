<?php
namespace App\Models;

class ClientFileType extends BaseModel {
    protected $table = 'client_file_types';
    
    public function all() {
        return $this->db->fetchAll("SELECT * FROM {$this->table} ORDER BY name ASC");
    }
}
