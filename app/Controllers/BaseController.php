<?php
namespace App\Controllers;

use Core\Session;

abstract class BaseController {
    protected $params = [];
    
    public function __construct($params = []) {
        $this->params = $params;
        Session::start();
    }
    
    protected function view($path, $data = []) {
        extract($data);
        $file = APP_PATH . '/Views/' . str_replace('.', '/', $path) . '.php';
        
        if (file_exists($file)) {
            require $file;
        } else {
            throw new \Exception("View not found: {$path}");
        }
    }
    
    protected function redirect($url) {
        header("Location: " . BASE_URL . $url);
        exit;
    }
    
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    protected function requireAuth() {
        if (!Session::isLoggedIn()) {
            $this->redirect('auth/login');
        }
    }
    
    protected function setFlash($message, $type = 'info') {
        Session::setFlash('message', $message, $type);
    }
    
    protected function getFlash() {
        return Session::getFlash('message');
    }
}
