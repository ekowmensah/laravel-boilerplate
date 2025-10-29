<?php
namespace App\Controllers;

use App\Models\User;
use Core\Session;

class AuthController extends BaseController {
    
    public function login() {
        if (Session::isLoggedIn()) {
            $this->redirect('dashboard');
        }
        
        $error = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';
            
            if (empty($email) || empty($password)) {
                $error = 'Please enter both email and password';
            } else {
                $userModel = new User();
                $user = $userModel->findByEmail($email);
                
                if ($user && password_verify($password, $user['password'])) {
                    Session::set('user_id', $user['id']);
                    Session::set('user', [
                        'id' => $user['id'],
                        'first_name' => $user['first_name'],
                        'last_name' => $user['last_name'],
                        'email' => $user['email'],
                        'role' => 'admin'
                    ]);
                    
                    $userModel->logActivity('User Login', $user['id']);
                    
                    $this->redirect('dashboard');
                } else {
                    $error = 'Invalid email or password';
                }
            }
        }
        
        $this->view('auth/login', ['error' => $error]);
    }
    
    public function logout() {
        Session::destroy();
        $this->redirect('auth/login');
    }
}
