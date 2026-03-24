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

// Get form data and sanitize
$id = isset($_POST['id']) && $_POST['id'] !== '' ? sanitize($_POST['id']) : uniqid();
$name = sanitize($_POST['name'] ?? '');
$description = sanitize($_POST['description'] ?? '');
$price = floatval($_POST['price'] ?? 0);
$image = sanitize($_POST['image'] ?? '');
$active = isset($_POST['active']) && ($_POST['active'] === 'true' || $_POST['active'] === '1' || $_POST['active'] === 'on');

// Validate data
if (empty($name) || empty($description) || $price <= 0 || empty($image)) {
    echo json_encode(['success' => false, 'error' => 'Invalid data']);
    exit;
}

// Create product object
$product = [
    'id' => $id,
    'name' => $name,
    'description' => $description,
    'price' => $price,
    'image' => $image,
    'active' => $active
];

// Update or add product
$found = false;
foreach ($products as $key => $p) {
    if ($p['id'] === $id) {
        $products[$key] = $product;
        $found = true;
        break;
    }
}

if (!$found) {
    $products[] = $product;
}

// Save to file with file locking
if (safeWriteJSON($file, $products)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Could not save']);
}
?>
