<?php
namespace Core;

class FileUploader {
    
    /**
     * Upload file
     * 
     * @param array $file $_FILES array element
     * @param string $entity Entity type (client, loan, savings)
     * @param int $entityId Entity ID
     * @param string $category File category
     * @return array
     */
    public static function upload($file, $entity, $entityId, $category = 'general') {
        // Validate file
        $validation = self::validateFile($file);
        if (!$validation['valid']) {
            return ['success' => false, 'message' => $validation['message']];
        }
        
        try {
            // Create directory structure
            $uploadDir = UPLOAD_PATH . $entity . '/' . $entityId . '/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Generate unique filename
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '_' . time() . '.' . $extension;
            $filepath = $uploadDir . $filename;
            
            // Move uploaded file
            if (!move_uploaded_file($file['tmp_name'], $filepath)) {
                return ['success' => false, 'message' => 'Failed to upload file'];
            }
            
            // Store file info in database
            $db = \Core\Database::getInstance();
            $fileData = [
                'name' => sanitize($file['name']),
                'file_name' => $filename,
                'file_path' => str_replace(UPLOAD_PATH, '', $filepath),
                'file_size' => $file['size'],
                'file_type' => $file['type'],
                'category' => $category,
                'created_by_id' => \Core\Session::getUserId(),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            // Insert into appropriate table
            $table = self::getFileTable($entity);
            $fileData[$entity . '_id'] = $entityId;
            
            $fileId = $db->insert($table, $fileData);
            
            // Log activity
            (new \App\Models\User())->logActivity('Upload File', \Core\Session::getUserId(), ucfirst($entity) . 'File', $fileId);
            
            return [
                'success' => true,
                'file_id' => $fileId,
                'filename' => $filename,
                'filepath' => $filepath
            ];
            
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Error uploading file: ' . $e->getMessage()];
        }
    }
    
    /**
     * Validate uploaded file
     */
    private static function validateFile($file) {
        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['valid' => false, 'message' => 'File upload error'];
        }
        
        // Check file size
        if ($file['size'] > MAX_FILE_SIZE) {
            $maxMB = MAX_FILE_SIZE / 1048576;
            return ['valid' => false, 'message' => "File size exceeds {$maxMB}MB limit"];
        }
        
        // Check file extension
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, ALLOWED_EXTENSIONS)) {
            return ['valid' => false, 'message' => 'File type not allowed'];
        }
        
        return ['valid' => true];
    }
    
    /**
     * Get file table name based on entity
     */
    private static function getFileTable($entity) {
        $tables = [
            'client' => 'client_files',
            'loan' => 'loan_files',
            'savings' => 'savings_files',
            'group' => 'group_files'
        ];
        
        return $tables[$entity] ?? 'files';
    }
    
    /**
     * Get files for entity
     */
    public static function getFiles($entity, $entityId) {
        $db = \Core\Database::getInstance();
        $table = self::getFileTable($entity);
        $column = $entity . '_id';
        
        return $db->fetchAll("
            SELECT f.*, u.first_name, u.last_name
            FROM {$table} f
            LEFT JOIN users u ON f.created_by_id = u.id
            WHERE f.{$column} = ?
            ORDER BY f.created_at DESC
        ", [$entityId]);
    }
    
    /**
     * Delete file
     */
    public static function deleteFile($entity, $fileId) {
        try {
            $db = \Core\Database::getInstance();
            $table = self::getFileTable($entity);
            
            // Get file info
            $file = $db->fetchOne("SELECT * FROM {$table} WHERE id = ?", [$fileId]);
            
            if (!$file) {
                return ['success' => false, 'message' => 'File not found'];
            }
            
            // Delete physical file
            $filepath = UPLOAD_PATH . $file['file_path'];
            if (file_exists($filepath)) {
                unlink($filepath);
            }
            
            // Delete database record
            $db->delete($table, 'id = ?', [$fileId]);
            
            // Log activity
            (new \App\Models\User())->logActivity('Delete File', \Core\Session::getUserId(), ucfirst($entity) . 'File', $fileId);
            
            return ['success' => true, 'message' => 'File deleted'];
            
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Error deleting file: ' . $e->getMessage()];
        }
    }
    
    /**
     * Download file
     */
    public static function downloadFile($entity, $fileId) {
        $db = \Core\Database::getInstance();
        $table = self::getFileTable($entity);
        
        $file = $db->fetchOne("SELECT * FROM {$table} WHERE id = ?", [$fileId]);
        
        if (!$file) {
            return false;
        }
        
        $filepath = UPLOAD_PATH . $file['file_path'];
        
        if (!file_exists($filepath)) {
            return false;
        }
        
        // Set headers for download
        header('Content-Type: ' . $file['file_type']);
        header('Content-Disposition: attachment; filename="' . $file['name'] . '"');
        header('Content-Length: ' . filesize($filepath));
        
        readfile($filepath);
        exit;
    }
}
