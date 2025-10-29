<?php
// Helper Functions

function redirect($url) {
    header("Location: " . BASE_URL . $url);
    exit;
}

function formatCurrency($amount) {
    $formatted = number_format($amount, 2);
    if (CURRENCY_POSITION === 'left') {
        return CURRENCY_SYMBOL . $formatted;
    }
    return $formatted . CURRENCY_SYMBOL;
}

function formatDate($date, $format = DISPLAY_DATE_FORMAT) {
    if (empty($date)) return '';
    return date($format, strtotime($date));
}

function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

function generateReference($prefix, $number) {
    return $prefix . str_pad($number, 6, '0', STR_PAD_LEFT);
}

function getStatusBadge($status) {
    $badges = [
        'pending' => 'warning',
        'approved' => 'info',
        'active' => 'success',
        'closed' => 'secondary',
        'rejected' => 'danger',
        'written_off' => 'dark',
        'submitted' => 'primary',
        'inactive' => 'secondary',
        'deceased' => 'dark',
        'overpaid' => 'info'
    ];
    
    $class = $badges[$status] ?? 'secondary';
    return "<span class='badge badge-{$class}'>" . ucfirst($status) . "</span>";
}

function view($path, $data = []) {
    extract($data);
    $file = __DIR__ . '/../app/Views/' . str_replace('.', '/', $path) . '.php';
    
    if (file_exists($file)) {
        require $file;
    } else {
        throw new Exception("View not found: {$path}");
    }
}

function asset($path) {
    return BASE_URL . 'public/assets/' . $path;
}

function url($path = '') {
    return BASE_URL . $path;
}
