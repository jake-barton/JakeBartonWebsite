<?php
/**
 * Configuration file for Pi Kappa Phi T-Shirt Website
 * This file contains the domain configuration for email links and URLs
 */

// Define the base URL for your hosted website
// Change this when deploying to production
define('SITE_BASE_URL', 'https://jakebartoncreative.com/portfolio/web-programming/pikapptshirtweb');

// Alternative: Auto-detect (use this if you want automatic detection)
function getBaseUrl() {
    // Check if we're on localhost
    $isLocalhost = (
        $_SERVER['HTTP_HOST'] === 'localhost' || 
        $_SERVER['HTTP_HOST'] === '127.0.0.1' ||
        strpos($_SERVER['HTTP_HOST'], 'localhost:') === 0 ||
        strpos($_SERVER['HTTP_HOST'], '127.0.0.1:') === 0
    );
    
    if ($isLocalhost) {
        // Local development
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
        $host = $_SERVER['HTTP_HOST'];
        return $protocol . $host;
    } else {
        // Production - use the defined constant
        return SITE_BASE_URL;
    }
}

?>
