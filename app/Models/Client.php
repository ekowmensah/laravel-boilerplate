<?php
namespace App\Models;

use Core\Session;

class Client extends BaseModel {
    protected $table = 'clients';
    
    public function getAllWithDetails() {
        return $this->fetchAll("
            SELECT c.*, 
                   ct.name as client_type_name,
                   b.name as branch_name,
                   u.first_name as officer_first_name,
                   u.last_name as officer_last_name,
                   co.name as country_name
            FROM {$this->table} c
            LEFT JOIN client_types ct ON c.client_type_id = ct.id
            LEFT JOIN branches b ON c.branch_id = b.id
            LEFT JOIN users u ON c.loan_officer_id = u.id
            LEFT JOIN countries co ON c.country_id = co.id
            ORDER BY c.created_at DESC
        ");
    }
    
    public function getWithDetails($id) {
        return $this->fetchOne("
            SELECT c.*, 
                   ct.name as client_type_name,
                   b.name as branch_name,
                   u.first_name as officer_first_name,
                   u.last_name as officer_last_name,
                   co.name as country_name
            FROM {$this->table} c
            LEFT JOIN client_types ct ON c.client_type_id = ct.id
            LEFT JOIN branches b ON c.branch_id = b.id
            LEFT JOIN users u ON c.loan_officer_id = u.id
            LEFT JOIN countries co ON c.country_id = co.id
            WHERE c.id = ?
        ", [$id]);
    }
    
    public function getActive() {
        return $this->where('status', 'active');
    }
    
    public function countActive() {
        $result = $this->fetchOne("SELECT COUNT(*) as count FROM {$this->table} WHERE status = 'active'");
        return $result['count'] ?? 0;
    }
    
    public function createClient($data) {
        $errors = $this->validateClient($data);
        
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }
        
        try {
            $this->db->beginTransaction();
            
            // Generate account number using settings
            $settingModel = new Setting();
            $accountNumber = $settingModel->generateAccountNumber('client', $data['branch_id'] ?? null);
            
            $clientData = [
                'created_by_id' => Session::getUserId(),
                'branch_id' => $data['branch_id'],
                'loan_officer_id' => $data['loan_officer_id'] ?: null,
                'account_number' => $accountNumber,
                'first_name' => sanitize($data['first_name']),
                'middle_name' => sanitize($data['middle_name'] ?? ''),
                'last_name' => sanitize($data['last_name']),
                'gender' => $data['gender'] ?? 'unspecified',
                'status' => 'pending',
                'marital_status' => $data['marital_status'] ?? 'unspecified',
                'country_id' => $data['country_id'] ?? null,
                'client_type_id' => $data['client_type_id'],
                'mobile' => sanitize($data['mobile'] ?? ''),
                'phone' => sanitize($data['phone'] ?? ''),
                'email' => sanitize($data['email'] ?? ''),
                'dob' => $data['dob'] ?? null,
                'address' => sanitize($data['address'] ?? ''),
                'city' => sanitize($data['city'] ?? ''),
                'state' => sanitize($data['state'] ?? ''),
                'zip' => sanitize($data['zip'] ?? ''),
                'employer' => sanitize($data['employer'] ?? ''),
                'notes' => sanitize($data['notes'] ?? ''),
                'created_date' => date('Y-m-d')
            ];
            
            $clientId = $this->create($clientData);
            
            // Log activity
            (new User())->logActivity('Create Client', Session::getUserId(), 'Client', $clientId);
            
            $this->db->commit();
            
            return ['success' => true, 'id' => $clientId];
            
        } catch (\Exception $e) {
            $this->db->rollback();
            return ['success' => false, 'errors' => ['Error creating client: ' . $e->getMessage()]];
        }
    }
    
    public function updateClient($id, $data) {
        $errors = $this->validateClient($data, $id);
        
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }
        
        try {
            $clientData = [
                'branch_id' => $data['branch_id'],
                'loan_officer_id' => $data['loan_officer_id'] ?: null,
                'first_name' => sanitize($data['first_name']),
                'middle_name' => sanitize($data['middle_name'] ?? ''),
                'last_name' => sanitize($data['last_name']),
                'gender' => $data['gender'] ?? 'unspecified',
                'marital_status' => $data['marital_status'] ?? 'unspecified',
                'country_id' => $data['country_id'] ?? null,
                'client_type_id' => $data['client_type_id'],
                'mobile' => sanitize($data['mobile'] ?? ''),
                'phone' => sanitize($data['phone'] ?? ''),
                'email' => sanitize($data['email'] ?? ''),
                'dob' => $data['dob'] ?? null,
                'address' => sanitize($data['address'] ?? ''),
                'city' => sanitize($data['city'] ?? ''),
                'state' => sanitize($data['state'] ?? ''),
                'zip' => sanitize($data['zip'] ?? ''),
                'employer' => sanitize($data['employer'] ?? ''),
                'notes' => sanitize($data['notes'] ?? '')
            ];
            
            $this->update($id, $clientData);
            
            // Log activity
            (new User())->logActivity('Update Client', Session::getUserId(), 'Client', $id);
            
            return ['success' => true];
            
        } catch (\Exception $e) {
            return ['success' => false, 'errors' => ['Error updating client: ' . $e->getMessage()]];
        }
    }
    
    private function validateClient($data, $id = null) {
        $errors = [];
        
        if (empty($data['first_name'])) $errors[] = 'First name is required';
        if (empty($data['last_name'])) $errors[] = 'Last name is required';
        if (empty($data['client_type_id'])) $errors[] = 'Client type is required';
        if (empty($data['branch_id'])) $errors[] = 'Branch is required';
        
        return $errors;
    }
    
    public function getGroups() {
        return $this->fetchAll("
            SELECT c.*, 
                   b.name as branch_name,
                   u.first_name as officer_first_name,
                   u.last_name as officer_last_name,
                   COUNT(DISTINCT l.id) as total_loans,
                   SUM(CASE WHEN l.status = 'active' THEN l.principal_outstanding_derived ELSE 0 END) as outstanding_loans
            FROM {$this->table} c
            LEFT JOIN branches b ON c.branch_id = b.id
            LEFT JOIN users u ON c.loan_officer_id = u.id
            LEFT JOIN loans l ON c.id = l.client_id
            WHERE c.client_type_id = (SELECT id FROM client_types WHERE name = 'Group' LIMIT 1)
            GROUP BY c.id
            ORDER BY c.created_at DESC
        ");
    }
    
    public function getGroupMembers($groupId) {
        // Groups functionality not implemented in database
        // Return empty array for now
        return [];
        
        /* Original code - requires group_members table
        return $this->fetchAll("
            SELECT gm.*, c.first_name, c.last_name, c.account_number, c.mobile
            FROM group_members gm
            LEFT JOIN {$this->table} c ON gm.client_id = c.id
            WHERE gm.group_id = ?
            ORDER BY gm.created_at
        ", [$groupId]);
        */
    }
    
    /**
     * Create group
     */
    public function createGroup($data) {
        $errors = $this->validateClient($data);
        
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }
        
        try {
            // Find group client type
            $groupType = $this->fetchOne("SELECT id FROM client_types WHERE name = 'Group' LIMIT 1");
            if (!$groupType) {
                return ['success' => false, 'errors' => ['Group client type not found in database']];
            }
            
            $data['client_type_id'] = $groupType['id'];
            $result = $this->createClient($data);
            
            return $result;
            
        } catch (\Exception $e) {
            return ['success' => false, 'errors' => ['Error creating group: ' . $e->getMessage()]];
        }
    }
    
    /**
     * Add member to group
     */
    public function addGroupMember($groupId, $data) {
        try {
            $this->db->beginTransaction();
            
            // Check if client is already in a group
            $existing = $this->fetchOne("SELECT * FROM group_members WHERE client_id = ?", [$data['client_id']]);
            if ($existing) {
                return ['success' => false, 'message' => 'Client is already a member of another group'];
            }
            
            $memberData = [
                'group_id' => $groupId,
                'client_id' => $data['client_id'],
                'joined_date' => date('Y-m-d'),
                'created_by_id' => \Core\Session::getUserId(),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $this->db->insert('group_members', $memberData);
            
            (new User())->logActivity('Add Group Member', \Core\Session::getUserId(), 'Group', $groupId);
            
            $this->db->commit();
            
            return ['success' => true, 'message' => 'Member added successfully'];
            
        } catch (\Exception $e) {
            $this->db->rollback();
            return ['success' => false, 'message' => 'Error adding member: ' . $e->getMessage()];
        }
    }
    
    /**
     * Remove member from group
     */
    public function removeGroupMember($groupId, $memberId) {
        try {
            $this->db->beginTransaction();
            
            $this->db->delete('group_members', 'id = ?', [$memberId]);
            
            (new User())->logActivity('Remove Group Member', \Core\Session::getUserId(), 'Group', $groupId);
            
            $this->db->commit();
            
            return ['success' => true, 'message' => 'Member removed successfully'];
            
        } catch (\Exception $e) {
            $this->db->rollback();
            return ['success' => false, 'message' => 'Error removing member: ' . $e->getMessage()];
        }
    }
    
    /**
     * Get clients available for group membership
     */
    public function getAvailableForGroup() {
        return $this->fetchAll("
            SELECT c.* 
            FROM {$this->table} c
            LEFT JOIN group_members gm ON c.id = gm.client_id
            WHERE gm.id IS NULL
            AND c.client_type_id != (SELECT id FROM client_types WHERE name = 'Group' LIMIT 1)
            AND c.status = 'active'
            ORDER BY c.first_name, c.last_name
        ");
    }
}

