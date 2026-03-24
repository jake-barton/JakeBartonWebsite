<?php
// Security utility functions

/**
 * Escape HTML output to prevent XSS
 */
function escape($value) {
    if (is_array($value)) {
        return array_map('escape', $value);
    }
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Alias for escape function
 */
function e($value) {
    return escape($value);
}

/**
 * Sanitize input
 */
function sanitize($value) {
    if (is_array($value)) {
        return array_map('sanitize', $value);
    }
    return trim(strip_tags($value ?? ''));
}

/**
 * File locking wrapper for JSON writes
 */
function safeWriteJSON($filepath, $data, $prettyPrint = true) {
    $flags = LOCK_EX;
    $json = json_encode($data, $prettyPrint ? JSON_PRETTY_PRINT : 0);
    
    if ($json === false) {
        return false;
    }
    
    // Ensure directory exists
    $dir = dirname($filepath);
    if (!file_exists($dir)) {
        mkdir($dir, 0755, true);
    }
    
    // Use file_put_contents with LOCK_EX for atomic writes
    $result = file_put_contents($filepath, $json, $flags);
    
    return $result !== false;
}

/**
 * File locking wrapper for JSON reads
 */
function safeReadJSON($filepath, $assoc = true) {
    if (!file_exists($filepath)) {
        return $assoc ? [] : null;
    }
    
    $fp = fopen($filepath, 'r');
    if (!$fp) {
        return $assoc ? [] : null;
    }
    
    // Get shared lock for reading
    if (flock($fp, LOCK_SH)) {
        $content = fread($fp, filesize($filepath));
        flock($fp, LOCK_UN);
        fclose($fp);
        
        return json_decode($content, $assoc);
    }
    
    fclose($fp);
    return $assoc ? [] : null;
}

/**
 * Initialize secure session settings
 */
function initSecureSession() {
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    
    // Prevent session fixation
    ini_set('session.use_strict_mode', 1);
    
    // Set secure cookie parameters
    $cookieParams = [
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
        'httponly' => true,
        'samesite' => 'Strict'
    ];
    
    session_set_cookie_params($cookieParams);
    
    session_start();
    
    // Regenerate session ID on first start
    if (!isset($_SESSION['_session_initialized'])) {
        session_regenerate_id(true);
        $_SESSION['_session_initialized'] = true;
    }
}

/**
 * Regenerate session ID (call on login/privilege escalation)
 */
function regenerateSession() {
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_regenerate_id(true);
    }
}

/**
 * Validate price against product database
 */
function validateProductPrice($productId, $submittedPrice) {
    $products = safeReadJSON('data/products.json');
    
    foreach ($products as $product) {
        if ($product['id'] === $productId) {
            return abs(floatval($product['price']) - floatval($submittedPrice)) < 0.01;
        }
    }
    
    return false;
}

/**
 * Get product details from database
 */
function getProductById($productId) {
    $products = safeReadJSON('data/products.json');
    
    foreach ($products as $product) {
        if ($product['id'] === $productId) {
            return $product;
        }
    }
    
    return null;
}

/**
 * Validate size option
 */
function isValidSize($size) {
    $validSizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
    return in_array(strtoupper($size), $validSizes);
}
?>
