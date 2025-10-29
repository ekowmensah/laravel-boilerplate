<?php
namespace App\Models;

class LoanApplication extends BaseModel {
    protected $table = 'loan_applications';
    
    public function all() {
        return $this->db->fetchAll("SELECT la.*, c.first_name, c.last_name, lp.name as product_name 
                                     FROM {$this->table} la
                                     LEFT JOIN clients c ON la.client_id = c.id
                                     LEFT JOIN loan_products lp ON la.loan_product_id = lp.id
                                     ORDER BY la.created_at DESC");
    }
    
    public function createApplication($data) {
        return $this->db->insert($this->table, [
            'created_by_id' => $_SESSION['user_id'] ?? null,
            'client_id' => $data['client_id'],
            'loan_product_id' => $data['loan_product_id'],
            'amount' => $data['amount'],
            'status' => 'pending',
            'notes' => $data['notes'] ?? null,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}
