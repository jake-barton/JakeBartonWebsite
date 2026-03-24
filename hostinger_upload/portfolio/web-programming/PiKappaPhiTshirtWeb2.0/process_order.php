<?php
require_once 'customer_auth.php';
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/security.php';

header('Content-Type: application/json');

// Check if customer is logged in
if (!isCustomerLoggedIn()) {
    echo json_encode(['success' => false, 'error' => 'Please login to place an order', 'redirect' => 'customer_login.php']);
    exit;
}

// Validate CSRF token
requireCSRFToken();

$customerId = getCustomerId();
$customerName = getCustomerName();
$customerEmail = getCustomerEmail();

// Get form data
$productId = sanitize($_POST['product_id'] ?? '');
$size = sanitize($_POST['size'] ?? '');
$quantity = intval($_POST['quantity'] ?? 1);
$notes = sanitize($_POST['notes'] ?? '');

// Validate required data
if (empty($productId) || empty($size)) {
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit;
}

// Validate quantity
if ($quantity < 1 || $quantity > 99) {
    echo json_encode(['success' => false, 'error' => 'Invalid quantity']);
    exit;
}

// Validate size
if (!isValidSize($size)) {
    echo json_encode(['success' => false, 'error' => 'Invalid size selection']);
    exit;
}

// Get product from database (server-side validation)
$product = getProductById($productId);

if (!$product) {
    echo json_encode(['success' => false, 'error' => 'Product not found']);
    exit;
}

// Check if product is active
if (!isset($product['active']) || !$product['active']) {
    echo json_encode(['success' => false, 'error' => 'Product is no longer available']);
    exit;
}

// Use server-side product data (prevent price manipulation)
$productName = $product['name'];
$productPrice = floatval($product['price']);

// Create order object
$order = [
    'id' => uniqid(),
    'product_id' => $productId,
    'product_name' => $productName,
    'product_price' => $productPrice,
    'customer_id' => $customerId,
    'customer_name' => $customerName,
    'customer_email' => $customerEmail,
    'size' => strtoupper($size),
    'quantity' => $quantity,
    'notes' => $notes,
    'date' => date('Y-m-d H:i:s'),
    'status' => 'pending'
];

// Load existing orders with file locking
$file = 'data/orders.json';
$orders = safeReadJSON($file);

// Add new order
$orders[] = $order;

// Save to file with file locking
if (safeWriteJSON($file, $orders)) {
    // Send order confirmation email
    sendOrderConfirmationEmail($customerEmail, $customerName, $order);
    
    echo json_encode(['success' => true, 'order_id' => $order['id']]);
} else {
    echo json_encode(['success' => false, 'error' => 'Could not save order']);
}
?>
