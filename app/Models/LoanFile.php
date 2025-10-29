<?php
namespace App\Models;

class LoanFile extends BaseModel {
    protected $table = 'loan_files';
    
    public function getLoanFiles($loanId) {
        return $this->db->fetchAll("SELECT * FROM {$this->table} WHERE loan_id = ? ORDER BY created_at DESC", [$loanId]);
    }
    
    public function uploadFile($loanId, $data, $files) {
        return $this->db->insert($this->table, [
            'created_by_id' => $_SESSION['user_id'] ?? null,
            'loan_id' => $loanId,
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'file' => $data['file_path'] ?? null,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}
