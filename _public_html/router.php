<?php
// Simple router for PHP built-in server
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove query string from URI
if ($uri === '/' || $uri === '') {
    require 'index.php';
    exit;
}

// Check if file exists
$file = __DIR__ . urldecode($uri);
if (file_exists($file) && !is_dir($file)) {
    return false; // Serve the file directly
}

// Try with .php extension
if (file_exists($file . '.php')) {
    require $file . '.php';
    exit;
}

// Default to index.php or index.html if not found
if (is_dir($file) && file_exists($file . '/index.php')) {
    require $file . '/index.php';
    exit;
}
if (is_dir($file) && file_exists($file . '/index.html')) {
    require $file . '/index.html';
    exit;
}

// 404
http_response_code(404);
echo "404 Not Found";
