<?php
namespace App\Models;

class Setting extends BaseModel {
    protected $table = 'settings';
    
    public function getByKey($key) {
        return $this->db->fetchOne("SELECT * FROM {$this->table} WHERE setting_key = ?", [$key]);
    }
    
    public function getValue($key, $default = null) {
        $setting = $this->getByKey($key);
        return $setting['setting_value'] ?? $default;
    }
    
    public function setValue($key, $value) {
        $setting = $this->getByKey($key);
        if ($setting) {
            return $this->db->update($this->table, [
                'setting_value' => $value,
                'updated_at' => date('Y-m-d H:i:s')
            ], "setting_key = ?", [$key]);
        }
        return false;
    }
    
    public function getAllByCategory($category) {
        return $this->db->fetchAll("SELECT * FROM {$this->table} WHERE category = ? ORDER BY `order` ASC", [$category]);
    }
    
    public function generateAccountNumber($type = 'client', $branchId = null, $productId = null) {
        // Get prefix and format from settings
        $prefix = $this->getValue("{$type}.reference_prefix", 'C19/');
        $format = $this->getValue("{$type}.reference_format", 'Sequence Number');
        
        // Generate sequence number based on format
        $sequence = $this->getNextSequence($type, $branchId, $productId);
        
        switch ($format) {
            case 'YEAR/Sequence Number (SL/2014/001)':
                return $prefix . date('Y') . '/' . str_pad($sequence, 3, '0', STR_PAD_LEFT);
                
            case 'YEAR/MONTH/Sequence Number (SL/2014/08/001)':
                return $prefix . date('Y') . '/' . date('m') . '/' . str_pad($sequence, 3, '0', STR_PAD_LEFT);
                
            case 'Random Number':
                return $prefix . rand(10000, 99999);
                
            case 'Branch Sequence Number':
                return $prefix . ($branchId ?? '1') . str_pad($sequence, 4, '0', STR_PAD_LEFT);
                
            case 'Branch Product Sequence Number':
                return $prefix . ($branchId ?? '1') . ($productId ?? '1') . str_pad($sequence, 3, '0', STR_PAD_LEFT);
                
            case 'Sequence Number':
            default:
                return $prefix . str_pad($sequence, 5, '0', STR_PAD_LEFT);
        }
    }
    
    private function getNextSequence($type, $branchId = null, $productId = null) {
        // Get the last account number from the respective table
        $table = $type === 'loan' ? 'loans' : ($type === 'savings' ? 'savings' : 'clients');
        
        $query = "SELECT COUNT(*) as count FROM {$table}";
        $params = [];
        
        if ($branchId) {
            $query .= " WHERE branch_id = ?";
            $params[] = $branchId;
        }
        
        $result = $this->db->fetchOne($query, $params);
        return ($result['count'] ?? 0) + 1;
    }
}
