<?php
namespace App\Models;

class ClientIdentification extends BaseModel {
    protected $table = 'client_identification';
    
    public function getClientIdentifications($clientId) {
        return $this->db->fetchAll("SELECT ci.*, cit.name as type_name 
                                     FROM {$this->table} ci
                                     LEFT JOIN client_identification_types cit ON ci.client_identification_type_id = cit.id
                                     WHERE ci.client_id = ?
                                     ORDER BY ci.created_at DESC", [$clientId]);
    }
    
    public function createIdentification($clientId, $data) {
        return $this->db->insert($this->table, [
            'created_by_id' => $_SESSION['user_id'] ?? null,
            'client_id' => $clientId,
            'client_identification_type_id' => $data['type_id'],
            'identification_number' => $data['identification_number'],
            'issue_date' => $data['issue_date'] ?? null,
            'expiry_date' => $data['expiry_date'] ?? null,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}
