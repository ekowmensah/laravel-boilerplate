<?php
namespace App\Models;

class LoanPurpose extends BaseModel {
    protected $table = 'loan_purposes';
    
    public function getActive() {
        return $this->all();
    }
}
