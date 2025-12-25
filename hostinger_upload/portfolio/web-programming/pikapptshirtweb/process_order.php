<?php
require_once 'customer_auth.php';

header('Content-Type: application/json');

// Check if customer is logged in
if (!isCustomerLoggedIn()) {
    echo json_encode(['success' => false, 'error' => 'Please login to place an order', 'redirect' => 'customer_login.php']);
    exit;
}

$customerId = getCustomerId();
$customerName = getCustomerName();
$customerEmail = getCustomerEmail();

// Get form data
$productId = $_POST['product_id'] ?? '';
$productName = $_POST['product_name'] ?? '';
$productPrice = $_POST['product_price'] ?? '';
$size = $_POST['size'] ?? '';
$quantity = intval($_POST['quantity'] ?? 1);
$notes = $_POST['notes'] ?? '';

// Validate data
if (empty($productId) || empty($size)) {
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit;
}

// Create order object
$order = [
    'id' => uniqid(),
    'product_id' => $productId,
    'product_name' => $productName,
    'product_price' => $productPrice,
    'customer_id' => $customerId,
    'customer_name' => $customerName,
    'customer_email' => $customerEmail,
    'size' => $size,
    'quantity' => $quantity,
    'notes' => $notes,
    'date' => date('Y-m-d H:i:s'),
    'status' => 'pending'
];

// Load existing orders
$file = 'data/orders.json';
$orders = [];

if (file_exists($file)) {
    $json = file_get_contents($file);
    $orders = json_decode($json, true);
    if (!is_array($orders)) {
        $orders = [];
    }
}

// Add new order
$orders[] = $order;

// Ensure data directory exists
if (!file_exists('data')) {
    mkdir('data', 0755, true);
}

// Save to file
if (file_put_contents($file, json_encode($orders, JSON_PRETTY_PRINT))) {
    // Send order confirmation email
    sendOrderConfirmationEmail($customerEmail, $customerName, $order);
    
    echo json_encode(['success' => true, 'order_id' => $order['id']]);
} else {
    echo json_encode(['success' => false, 'error' => 'Could not save order']);
}
?>
