<?php
// CSRF Protection Functions

/**
 * Generate a CSRF token and store it in the session
 */
function generateCSRFToken() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
    return $_SESSION['csrf_token'];
}

/**
 * Get the current CSRF token
 */
function getCSRFToken() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    return $_SESSION['csrf_token'] ?? generateCSRFToken();
}

/**
 * Validate CSRF token from request
 */
function validateCSRFToken($token) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (empty($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Require valid CSRF token or exit with error
 */
function requireCSRFToken() {
    $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    
    if (!validateCSRFToken($token)) {
        http_response_code(403);
        if (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Invalid CSRF token']);
        } else {
            echo 'Invalid CSRF token. Please refresh the page and try again.';
        }
        exit;
    }
}

/**
 * Output a hidden CSRF token input field
 */
function csrfField() {
    $token = getCSRFToken();
    echo '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
}

/**
 * Get CSRF token meta tag for AJAX requests
 */
function csrfMetaTag() {
    $token = getCSRFToken();
    echo '<meta name="csrf-token" content="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
}
?>
