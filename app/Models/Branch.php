<?php
namespace App\Models;

class Branch extends BaseModel {
    protected $table = 'branches';
    
    public function getActive() {
        return $this->where('active', 1);
    }
}
