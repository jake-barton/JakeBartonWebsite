<?php
/**
 * Jake Barton Website - Configuration File
 * Centralized settings and constants
 */

// Site Information
define('SITE_NAME', 'Jake Barton - Game Designer & 3D Artist');
define('SITE_URL', 'https://jakebarton.com'); // Update with your actual domain
define('SITE_DESCRIPTION', 'Portfolio of Jake Barton - Game Designer, 3D Artist, and Developer specializing in Unreal Engine, Unity, Godot, and graphic design.');

// Contact Information
define('CONTACT_EMAIL', 'jbarton4@samford.edu');
define('CONTACT_PHONE', '615.943.9722');
define('CONTACT_ADDRESS', '4147 Miles Johnson Pkwy, Birmingham, AL');
define('INSTAGRAM_HANDLE', 'jakebarton13');

// Personal Information
define('FULL_NAME', 'Jake Barton');
define('UNIVERSITY', 'Samford University');
define('LOCATION', 'Birmingham, AL');
define('MAJOR', 'Game Design & 3D Animation');
define('MINOR', 'Computer Science');
define('YEAR', 'Junior');

// Security Settings
define('CSRF_TOKEN_NAME', 'csrf_token');
define('CSRF_TOKEN_EXPIRY', 3600); // 1 hour

// Rate Limiting
define('CONTACT_FORM_LIMIT', 3); // Max submissions per hour
define('RATE_LIMIT_WINDOW', 3600); // 1 hour in seconds

// Development/Production Mode
define('IS_DEVELOPMENT', false); // Production mode — errors hidden from users
define('SHOW_ERRORS', IS_DEVELOPMENT);

// Error Reporting
error_reporting(IS_DEVELOPMENT ? E_ALL : 0);
ini_set('display_errors', IS_DEVELOPMENT ? 1 : 0);

// Session Configuration
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Generate CSRF Token
 */
function generate_csrf_token() {
    if (empty($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
        $_SESSION[CSRF_TOKEN_NAME . '_time'] = time();
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

/**
 * Verify CSRF Token
 */
function verify_csrf_token($token) {
    if (empty($_SESSION[CSRF_TOKEN_NAME]) || empty($_SESSION[CSRF_TOKEN_NAME . '_time'])) {
        return false;
    }
    
    // Check if token expired
    if (time() - $_SESSION[CSRF_TOKEN_NAME . '_time'] > CSRF_TOKEN_EXPIRY) {
        unset($_SESSION[CSRF_TOKEN_NAME]);
        unset($_SESSION[CSRF_TOKEN_NAME . '_time']);
        return false;
    }
    
    return hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
}

/**
 * Check Rate Limiting
 */
function check_rate_limit($key, $limit = CONTACT_FORM_LIMIT, $window = RATE_LIMIT_WINDOW) {
    $rate_key = 'rate_limit_' . $key;
    
    if (!isset($_SESSION[$rate_key])) {
        $_SESSION[$rate_key] = ['count' => 0, 'start_time' => time()];
    }
    
    $rate_data = $_SESSION[$rate_key];
    
    // Reset if window has passed
    if (time() - $rate_data['start_time'] > $window) {
        $_SESSION[$rate_key] = ['count' => 1, 'start_time' => time()];
        return true;
    }
    
    // Check if limit exceeded
    if ($rate_data['count'] >= $limit) {
        return false;
    }
    
    // Increment count
    $_SESSION[$rate_key]['count']++;
    return true;
}

/**
 * Sanitize Input Data
 */
function sanitize_input($data) {
    if (is_array($data)) {
        return array_map('sanitize_input', $data);
    }
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Get Current Page for Navigation
 */
function get_current_page() {
    $page = basename($_SERVER['PHP_SELF']);
    return $page;
}

/**
 * Generate Asset URL with Version for Cache Busting
 */
function asset_url($path) {
    $file_path = $_SERVER['DOCUMENT_ROOT'] . $path;
    if (file_exists($file_path)) {
        $version = filemtime($file_path);
        return $path . '?v=' . $version;
    }
    return $path;
}
