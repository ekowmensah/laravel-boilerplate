<?php
namespace App\Models;

class AssetType extends BaseModel {
    protected $table = 'asset_types';
    
    public function all() {
        return $this->db->fetchAll("SELECT * FROM {$this->table} ORDER BY name ASC");
    }
}
