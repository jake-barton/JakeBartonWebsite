<?php
require_once 'auth.php';
require_once 'customer_auth.php';

// Check if either admin or customer is logged in
$isAdmin = isLoggedIn();
$isCustomer = isCustomerLoggedIn();

if (!$isAdmin && !$isCustomer) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

header('Content-Type: application/json');

// Get the JSON input
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data || !isset($data['order_id'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
    exit;
}

$orderId = $data['order_id'];

// Load orders
$file = 'data/orders.json';
if (!file_exists($file)) {
    echo json_encode(['success' => false, 'error' => 'Orders file not found']);
    exit;
}

$json = file_get_contents($file);
$orders = json_decode($json, true) ?: [];

// Find and remove the order
$found = false;
$filteredOrders = [];

foreach ($orders as $order) {
    if (($order['id'] ?? '') === $orderId) {
        // If customer is trying to cancel, verify it's their order
        if ($isCustomer && !$isAdmin) {
            $customerId = getCustomerId();
            if (($order['customer_id'] ?? '') !== $customerId) {
                echo json_encode(['success' => false, 'error' => 'You can only cancel your own orders']);
                exit;
            }
        }
        
        $found = true;
        // Skip this order (effectively deleting it)
        continue;
    }
    $filteredOrders[] = $order;
}

if (!$found) {
    echo json_encode(['success' => false, 'error' => 'Order not found']);
    exit;
}

// Save the updated orders
if (file_put_contents($file, json_encode($filteredOrders, JSON_PRETTY_PRINT))) {
    echo json_encode(['success' => true, 'message' => 'Order cancelled successfully']);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to save changes']);
}
