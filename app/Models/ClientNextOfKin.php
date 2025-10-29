<?php
namespace App\Models;

class ClientNextOfKin extends BaseModel {
    protected $table = 'client_next_of_kin';
    
    public function getClientNextOfKin($clientId) {
        return $this->db->fetchAll("SELECT nok.*, cr.name as relationship_name 
                                     FROM {$this->table} nok
                                     LEFT JOIN client_relationships cr ON nok.client_relationship_id = cr.id
                                     WHERE nok.client_id = ?
                                     ORDER BY nok.created_at DESC", [$clientId]);
    }
    
    public function createNextOfKin($clientId, $data) {
        return $this->db->insert($this->table, [
            'created_by_id' => $_SESSION['user_id'] ?? null,
            'client_id' => $clientId,
            'client_relationship_id' => $data['relationship_id'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'gender' => $data['gender'] ?? null,
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'address' => $data['address'] ?? null,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}
