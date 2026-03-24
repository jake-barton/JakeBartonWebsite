<?php
require_once 'auth.php';

// Require admin login
requireLogin();

header('Content-Type: application/json');

$customerId = $_GET['customer_id'] ?? '';

if (empty($customerId)) {
    echo json_encode(['success' => false, 'error' => 'No customer ID provided']);
    exit;
}

// Load orders
$file = 'data/orders.json';
$orders = [];

if (file_exists($file)) {
    $json = file_get_contents($file);
    $orders = json_decode($json, true);
    if (!is_array($orders)) {
        $orders = [];
    }
}

// Filter orders for this customer
$customerOrders = array_filter($orders, function($order) use ($customerId) {
    return ($order['customer_id'] ?? '') === $customerId;
});

// Re-index array
$customerOrders = array_values($customerOrders);

echo json_encode(['success' => true, 'orders' => $customerOrders]);
?>
