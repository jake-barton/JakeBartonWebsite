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

// Save to file
if (file_put_contents($file, json_encode($newProducts, JSON_PRETTY_PRINT))) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Could not save']);
}
?>
