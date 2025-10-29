<?php
namespace App\Models;

class User extends BaseModel {
    protected $table = 'users';
    
    public function findByEmail($email) {
        return $this->fetchOne(
            "SELECT * FROM {$this->table} WHERE email = ?",
            [$email]
        );
    }
    
    public function getActive() {
        return $this->fetchAll(
            "SELECT id, first_name, last_name, email FROM {$this->table} ORDER BY first_name"
        );
    }
    
    public function logActivity($description, $userId, $subjectType = null, $subjectId = null) {
        $data = [
            'description' => $description,
            'causer_id' => $userId,
            'causer_type' => 'User',
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->db->insert('activity_log', $data);
    }
    
    public function getFieldAgents() {
        return $this->fetchAll("
            SELECT u.*, 
                   b.name as branch_name,
                   COUNT(DISTINCT l.id) as total_loans,
                   SUM(CASE WHEN l.status = 'active' THEN l.principal_outstanding_derived ELSE 0 END) as portfolio_value
            FROM {$this->table} u
            LEFT JOIN branches b ON u.branch_id = b.id
            LEFT JOIN loans l ON u.id = l.loan_officer_id
            GROUP BY u.id
            ORDER BY u.first_name, u.last_name
        ");
    }
    
    public function getAgentWithStats($id) {
        return $this->fetchOne("
            SELECT u.*, 
                   b.name as branch_name,
                   COUNT(DISTINCT l.id) as total_loans,
                   SUM(CASE WHEN l.status = 'active' THEN l.principal_outstanding_derived ELSE 0 END) as portfolio_value,
                   COUNT(DISTINCT c.id) as total_clients
            FROM {$this->table} u
            LEFT JOIN branches b ON u.branch_id = b.id
            LEFT JOIN loans l ON u.id = l.loan_officer_id
            LEFT JOIN clients c ON u.id = c.loan_officer_id
            WHERE u.id = ?
            GROUP BY u.id
        ", [$id]);
    }
}
