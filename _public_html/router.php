<?php
// Simple router for PHP built-in server
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Preserve the game's original portfolio URL and old direct-entry links.
if (preg_match('#^/MarioKartLatest(?:/(?:index\.(?:html|php))?)?$#i', (string) $uri)) {
    header('Location: /games/mario-kart/', true, 301);
    exit;
}

// CMS-managed work pages use one renderer while retaining clean URLs.
if (preg_match('#^/work/([a-z0-9-]+)/?$#', (string) $uri, $matches)) {
    $existing = __DIR__ . $uri;
    if (!is_dir($existing)) {
        $_GET['slug'] = $matches[1];
        require __DIR__ . '/work/detail.php';
        exit;
    }
}

// Remove query string from URI
if ($uri === '/' || $uri === '') {
    require 'index.php';
    exit;
}

// Check if file exists
$file = __DIR__ . urldecode($uri);
// PHP's development server does not handle byte ranges for MP4 files. Browser
// seeking needs them to correct drift and restart linked recordings together.
$videoPath = realpath($file);
if ($videoPath !== false && str_starts_with($videoPath, __DIR__ . DIRECTORY_SEPARATOR)
    && is_file($videoPath) && strtolower(pathinfo($videoPath, PATHINFO_EXTENSION)) === 'mp4') {
    $size = filesize($videoPath);
    $start = 0;
    $end = $size - 1;
    header('Content-Type: video/mp4');
    header('Accept-Ranges: bytes');
    if (isset($_SERVER['HTTP_RANGE'])) {
        $range = trim($_SERVER['HTTP_RANGE']);
        if (!preg_match('/^bytes=(\d*)-(\d*)$/', $range, $parts) || ($parts[1] === '' && $parts[2] === '')) {
            http_response_code(416);
            header('Content-Range: bytes */' . $size);
            exit;
        }
        if ($parts[1] === '') {
            $start = max(0, $size - (int) $parts[2]);
        } else {
            $start = (int) $parts[1];
            if ($parts[2] !== '') $end = min($end, (int) $parts[2]);
        }
        if ($start > $end || $start >= $size) {
            http_response_code(416);
            header('Content-Range: bytes */' . $size);
            exit;
        }
        http_response_code(206);
        header('Content-Range: bytes ' . $start . '-' . $end . '/' . $size);
    }
    header('Content-Length: ' . ($end - $start + 1));
    if ($_SERVER['REQUEST_METHOD'] === 'HEAD') exit;
    $stream = fopen($videoPath, 'rb');
    fseek($stream, $start);
    $remaining = $end - $start + 1;
    while ($remaining > 0 && !feof($stream) && !connection_aborted()) {
        $chunk = fread($stream, min(65536, $remaining));
        if ($chunk === false || $chunk === '') break;
        echo $chunk;
        $remaining -= strlen($chunk);
    }
    fclose($stream);
    exit;
}
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
