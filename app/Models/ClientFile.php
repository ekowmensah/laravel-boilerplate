<?php
namespace App\Models;

class ClientFile extends BaseModel {
    protected $table = 'client_files';
    
    public function getClientFiles($clientId) {
        return $this->db->fetchAll("SELECT cf.*, cft.name as file_type_name 
                                     FROM {$this->table} cf
                                     LEFT JOIN client_file_types cft ON cf.client_file_type_id = cft.id
                                     WHERE cf.client_id = ?
                                     ORDER BY cf.created_at DESC", [$clientId]);
    }
    
    public function uploadFile($clientId, $data, $files) {
        // Handle file upload logic here
        return $this->db->insert($this->table, [
            'created_by_id' => $_SESSION['user_id'] ?? null,
            'client_id' => $clientId,
            'client_file_type_id' => $data['file_type_id'],
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'file' => $data['file_path'] ?? null, // After upload
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}
