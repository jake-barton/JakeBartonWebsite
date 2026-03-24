<?php
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/security.php';

header('Content-Type: application/json');

// Validate CSRF token
requireCSRFToken();

// Load existing products with file locking
$file = 'data/products.json';
$products = safeReadJSON($file);

if (!is_array($products)) {
    $products = [];
}

// Get product ID
$id = $_POST['id'] ?? '';

if (empty($id)) {
    echo json_encode(['success' => false, 'error' => 'No ID provided']);
    exit;
}

// Remove product
$newProducts = [];
foreach ($products as $product) {
    if ($product['id'] !== $id) {
        $newProducts[] = $product;
    }
}

// Save to file with file locking
if (safeWriteJSON($file, $newProducts)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Could not save']);
}
?>
