<?php
require_once 'auth.php';
require_once 'customer_auth.php';
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/security.php';

// Require admin login
requireLogin();

// Require POST method and CSRF token
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: manage_customers.php');
    exit;
}

requireCSRFToken();

$customerId = $_POST['id'] ?? '';

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
    // Also delete all orders for this customer with file locking
    $ordersFile = 'data/orders.json';
    $orders = safeReadJSON($ordersFile);
    
    if (is_array($orders)) {
        $orders = array_filter($orders, function($order) use ($customerId) {
            return ($order['customer_id'] ?? '') !== $customerId;
        });
        $orders = array_values($orders);
        safeWriteJSON($ordersFile, $orders);
    }
    
    header('Location: manage_customers.php?deleted=1');
} else {
    header('Location: manage_customers.php?error=1');
}
exit;
?>
