<?php
namespace App\Models;

class Expense extends BaseModel {
    protected $table = 'expenses';
    
    public function all() {
        return $this->db->fetchAll("SELECT e.*, et.name as expense_type_name, b.name as branch_name 
                                     FROM {$this->table} e
                                     LEFT JOIN expense_types et ON e.expense_type_id = et.id
                                     LEFT JOIN branches b ON e.branch_id = b.id
                                     ORDER BY e.date DESC");
    }
    
    public function createExpense($data) {
        return $this->db->insert($this->table, [
            'created_by_id' => $_SESSION['user_id'] ?? null,
            'expense_type_id' => $data['expense_type_id'],
            'branch_id' => $data['branch_id'] ?? null,
            'amount' => $data['amount'],
            'date' => $data['date'],
            'description' => $data['description'] ?? null,
            'notes' => $data['notes'] ?? null,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}
