<?php
require_once 'customer_auth.php';
require_once __DIR__ . '/includes/csrf.php';
requireCustomerLogin();

$customerId = getCustomerId();
$customerName = getCustomerName();
$customerEmail = getCustomerEmail();

// Get customer's orders
function getCustomerOrders($customerId) {
    $file = 'data/orders.json';
    if (file_exists($file)) {
        $json = file_get_contents($file);
        $allOrders = json_decode($json, true) ?: [];
        return array_filter($allOrders, function($order) use ($customerId) {
            return ($order['customer_id'] ?? '') === $customerId;
        });
    }
    return [];
}

$orders = getCustomerOrders($customerId);
$totalOrders = count($orders);
$totalItems = array_sum(array_column($orders, 'quantity'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - Pi Kappa Phi Apparel</title>
    <?php csrfMetaTag(); ?>
    <link rel="icon" type="image/svg+xml" href="vectors/starshield.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="nav-content">
                <h1 class="logo"><span class="greek-letters">ΠΚΦ</span>| Apparel</h1>
                <div class="nav-links">
                    <a href="index.php" class="nav-link">Home</a>
                    <a href="customer_dashboard.php" class="nav-link active">My Orders</a>
                    <a href="customer_logout.php" class="nav-link">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <section class="admin-section">
        <div class="container">
            <div class="admin-header">
                <div>
                    <h2 class="section-title">Welcome, <?php echo htmlspecialchars($customerName); ?>!</h2>
                    <p style="color: #666; margin-top: 0.5rem;"><?php echo htmlspecialchars($customerEmail); ?></p>
                </div>
            </div>

            <!-- Statistics Overview -->
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Total Orders</h3>
                    <p class="stat-number"><?php echo $totalOrders; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Total Items</h3>
                    <p class="stat-number"><?php echo $totalItems; ?></p>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="products-table">
                <h3 class="card-title" style="text-align: center;">Your Orders</h3>
                <?php if (empty($orders)): ?>
                    <div class="empty-state">
                        <p>You haven't placed any orders yet.</p>
                        <a href="index.php" class="cta-button" style="margin-top: 1rem;">Browse Products</a>
                    </div>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Product</th>
                                <th>Size</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_reverse($orders) as $order): ?>
                                <tr id="order-<?php echo htmlspecialchars($order['id'] ?? ''); ?>">
                                    <td><code><?php echo htmlspecialchars(substr($order['id'] ?? 'N/A', 0, 8)); ?></code></td>
                                    <td><?php echo htmlspecialchars($order['product_name'] ?? 'N/A'); ?></td>
                                    <td><span class="size-badge"><?php echo htmlspecialchars($order['size'] ?? 'N/A'); ?></span></td>
                                    <td><?php echo htmlspecialchars($order['quantity'] ?? 1); ?></td>
                                    <td>$<?php echo number_format($order['product_price'] ?? 0, 2); ?></td>
                                    <td><?php echo htmlspecialchars($order['date'] ?? $order['timestamp'] ?? 'N/A'); ?></td>
                                    <td>
                                        <button class="btn-cancel" onclick="cancelOrder('<?php echo htmlspecialchars($order['id'] ?? ''); ?>')">Cancel</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <img src="vectors/starshield.svg" alt="Pi Kappa Phi" class="starshield-logo">
            <p>&copy; <?php echo date('Y'); ?> Pi Kappa Phi. All rights reserved.</p>
        </div>
    </footer>

    <script>
        function cancelOrder(orderId) {
            if (!confirm('Are you sure you want to cancel this order?')) {
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            fetch('cancel_order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ order_id: orderId })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('Order cancelled successfully!');
                    window.location.reload();
                } else {
                    alert('Error cancelling order: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error cancelling order. Please try again.');
            });
        }
    </script>
</body>
</html>
