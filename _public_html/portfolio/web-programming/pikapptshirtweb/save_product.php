<?php
header('Content-Type: application/json');

// Load existing products
$file = 'data/products.json';
$products = [];

if (file_exists($file)) {
    $json = file_get_contents($file);
    $products = json_decode($json, true);
    if (!is_array($products)) {
        $products = [];
    }
}

// Get form data
$id = isset($_POST['id']) && $_POST['id'] !== '' ? $_POST['id'] : uniqid();
$name = $_POST['name'] ?? '';
$description = $_POST['description'] ?? '';
$price = floatval($_POST['price'] ?? 0);
$image = $_POST['image'] ?? '';
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

// Ensure data directory exists
if (!file_exists('data')) {
    mkdir('data', 0755, true);
}

// Save to file
if (file_put_contents($file, json_encode($products, JSON_PRETTY_PRINT))) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Could not save']);
}
?>
