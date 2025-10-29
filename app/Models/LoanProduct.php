<?php
namespace App\Models;

class LoanProduct extends BaseModel {
    protected $table = 'loan_products';
    
    public function getActive() {
        return $this->where('active', 1);
    }
}
