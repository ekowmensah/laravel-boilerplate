<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'c19banking');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Application Configuration
define('APP_NAME', 'C19 Banking - Savings & Loans System');
define('APP_VERSION', '2.0.0');
define('BASE_URL', 'http://localhost/c19banking/public/');
define('TIMEZONE', 'Africa/Accra');

// Paths
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('CORE_PATH', ROOT_PATH . '/core');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('UPLOAD_PATH', ROOT_PATH . '/uploads/');

// Session Configuration
define('SESSION_LIFETIME', 3600); // 1 hour
define('SESSION_NAME', 'C19_BANKING_SESSION');

// File Upload Configuration
define('MAX_FILE_SIZE', 5242880); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx']);

// Pagination
define('RECORDS_PER_PAGE', 25);

// Currency Settings
define('DEFAULT_CURRENCY', 'GHS');
define('CURRENCY_SYMBOL', 'GH₵');
define('CURRENCY_POSITION', 'left');

// Date Format
define('DATE_FORMAT', 'Y-m-d');
define('DATETIME_FORMAT', 'Y-m-d H:i:s');
define('DISPLAY_DATE_FORMAT', 'd M, Y');

// Set timezone
date_default_timezone_set(TIMEZONE);

// Error Reporting (Set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Autoloader
spl_autoload_register(function ($class) {
    // Convert namespace to file path
    // App\Controllers\DashboardController -> app/Controllers/DashboardController.php
    // Core\Database -> core/Database.php
    
    $file = ROOT_PATH . '/' . str_replace('\\', '/', $class) . '.php';
    
    if (file_exists($file)) {
        require_once $file;
        return true;
    }
    
    return false;
});
