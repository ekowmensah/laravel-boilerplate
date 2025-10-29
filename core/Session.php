<?php
namespace Core;

class Session {
    
    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            session_name(SESSION_NAME);
            session_start();
        }
    }
    
    public static function set($key, $value) {
        self::start();
        $_SESSION[$key] = $value;
    }
    
    public static function get($key, $default = null) {
        self::start();
        return $_SESSION[$key] ?? $default;
    }
    
    public static function has($key) {
        self::start();
        return isset($_SESSION[$key]);
    }
    
    public static function remove($key) {
        self::start();
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }
    
    public static function destroy() {
        self::start();
        session_unset();
        session_destroy();
    }
    
    public static function setFlash($key, $message, $type = 'info') {
        self::set('flash_' . $key, [
            'message' => $message,
            'type' => $type
        ]);
    }
    
    public static function getFlash($key) {
        $flash = self::get('flash_' . $key);
        self::remove('flash_' . $key);
        return $flash;
    }
    
    public static function isLoggedIn() {
        return self::has('user_id');
    }
    
    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            header('Location: ' . BASE_URL . 'auth/login');
            exit;
        }
    }
    
    public static function getUserId() {
        return self::get('user_id');
    }
    
    public static function getUser() {
        return self::get('user');
    }
}
