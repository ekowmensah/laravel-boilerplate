<?php
namespace App\Models;

class SavingsProduct extends BaseModel {
    protected $table = 'savings_products';
    
    public function getActive() {
        return $this->where('active', 1);
    }
}
