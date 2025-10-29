<?php
namespace App\Models;

class Asset extends BaseModel {
    protected $table = 'assets';
    
    public function all() {
        return $this->db->fetchAll("SELECT a.*, at.name as asset_type_name, b.name as branch_name 
                                     FROM {$this->table} a
                                     LEFT JOIN asset_types at ON a.asset_type_id = at.id
                                     LEFT JOIN branches b ON a.branch_id = b.id
                                     ORDER BY a.created_at DESC");
    }
    
    public function createAsset($data) {
        return $this->db->insert($this->table, [
            'created_by_id' => $_SESSION['user_id'] ?? null,
            'asset_type_id' => $data['asset_type_id'],
            'branch_id' => $data['branch_id'] ?? null,
            'name' => $data['name'],
            'serial_number' => $data['serial_number'] ?? null,
            'purchase_date' => $data['purchase_date'],
            'purchase_price' => $data['purchase_price'],
            'current_value' => $data['current_value'] ?? $data['purchase_price'],
            'depreciation_rate' => $data['depreciation_rate'] ?? 0,
            'status' => $data['status'] ?? 'active',
            'notes' => $data['notes'] ?? null,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    public function updateAsset($id, $data) {
        return $this->db->update($this->table, [
            'asset_type_id' => $data['asset_type_id'],
            'branch_id' => $data['branch_id'] ?? null,
            'name' => $data['name'],
            'serial_number' => $data['serial_number'] ?? null,
            'purchase_date' => $data['purchase_date'],
            'purchase_price' => $data['purchase_price'],
            'current_value' => $data['current_value'],
            'depreciation_rate' => $data['depreciation_rate'] ?? 0,
            'status' => $data['status'],
            'notes' => $data['notes'] ?? null,
            'updated_at' => date('Y-m-d H:i:s')
        ], "id = ?", [$id]);
    }
    
    public function getDepreciation($assetId) {
        return $this->db->fetchAll("SELECT * FROM asset_depreciation WHERE asset_id = ? ORDER BY year DESC", [$assetId]);
    }
    
    public function getMaintenance($assetId) {
        return $this->db->fetchAll("SELECT am.*, amt.name as maintenance_type 
                                     FROM asset_maintenance am
                                     LEFT JOIN asset_maintenance_types amt ON am.asset_maintenance_type_id = amt.id
                                     WHERE am.asset_id = ? 
                                     ORDER BY am.maintenance_date DESC", [$assetId]);
    }
}
