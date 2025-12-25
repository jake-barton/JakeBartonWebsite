<?php
require_once 'auth.php';
require_once 'customer_auth.php';

// Require admin login
requireLogin();

$customerId = $_GET['id'] ?? '';

if (empty($customerId)) {
    header('Location: manage_customers.php');
    exit;
}

// Load customers
$customers = getCustomers();

// Find and remove the customer
$customers = array_filter($customers, function($customer) use ($customerId) {
    return $customer['id'] !== $customerId;
});

// Re-index array
$customers = array_values($customers);

// Save customers
if (saveCustomers($customers)) {
    // Also delete all orders for this customer
    $ordersFile = 'data/orders.json';
    if (file_exists($ordersFile)) {
        $orders = json_decode(file_get_contents($ordersFile), true);
        if (is_array($orders)) {
            $orders = array_filter($orders, function($order) use ($customerId) {
                return ($order['customer_id'] ?? '') !== $customerId;
            });
            $orders = array_values($orders);
            file_put_contents($ordersFile, json_encode($orders, JSON_PRETTY_PRINT));
        }
    }
    
    header('Location: manage_customers.php?deleted=1');
} else {
    header('Location: manage_customers.php?error=1');
}
exit;
?>
