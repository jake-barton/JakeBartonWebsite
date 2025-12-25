<?php
require_once 'auth.php';
requireLogin();

// Get product ID from URL
$productId = $_GET['id'] ?? '';

if (empty($productId)) {
    header('Location: admin.php');
    exit;
}

// Get products
function getProducts() {
    $file = 'data/products.json';
    if (file_exists($file)) {
        $json = file_get_contents($file);
        return json_decode($json, true) ?: [];
    }
    return [];
}

// Get orders for this product
function getOrdersForProduct($productId) {
    $file = 'data/orders.json';
    if (file_exists($file)) {
        $json = file_get_contents($file);
        $allOrders = json_decode($json, true) ?: [];
        return array_filter($allOrders, function($order) use ($productId) {
            return ($order['product_id'] ?? '') === $productId;
        });
    }
    return [];
}

$products = getProducts();
$product = null;

foreach ($products as $p) {
    if ($p['id'] === $productId) {
        $product = $p;
        break;
    }
}

if (!$product) {
    header('Location: admin.php');
    exit;
}

$orders = getOrdersForProduct($productId);

// Calculate statistics
$totalOrders = count($orders);
$sizeBreakdown = ['S' => 0, 'M' => 0, 'L' => 0, 'XL' => 0, 'XXL' => 0];
$totalQuantity = 0;

foreach ($orders as $order) {
    $size = $order['size'] ?? '';
    $quantity = intval($order['quantity'] ?? 1);
    
    if (isset($sizeBreakdown[$size])) {
        $sizeBreakdown[$size] += $quantity;
    }
    $totalQuantity += $quantity;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders: <?php echo htmlspecialchars($product['name']); ?> - Pi Kappa Phi Apparel</title>
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
                <h1 class="logo"><span class="greek-letters">ΠΚΦ</span> | Product Orders</h1>
                <div class="nav-links">
                    <a href="index.php" class="nav-link">Home</a>
                    <a href="admin.php" class="nav-link">Products</a>
                    <a href="manage_customers.php" class="nav-link">Customers</a>
                    <?php if (isOwner()): ?>
                        <a href="manage_access.php" class="nav-link">Manage Access</a>
                    <?php endif; ?>
                    <a href="logout.php" class="nav-link">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <section class="admin-section">
        <div class="container">
            <div class="product-header">
                <div class="product-header-info">
                    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-header-image">
                    <div>
                        <h2 class="section-title"><?php echo htmlspecialchars($product['name']); ?></h2>
                        <p class="product-header-description"><?php echo htmlspecialchars($product['description']); ?></p>
                        <p class="product-header-price">$<?php echo number_format($product['price'], 2); ?></p>
                    </div>
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
                    <p class="stat-number"><?php echo $totalQuantity; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Size Breakdown</h3>
                    <div class="size-breakdown">
                        <?php foreach ($sizeBreakdown as $size => $count): ?>
                            <div class="size-stat">
                                <span class="size-label"><?php echo $size; ?>:</span>
                                <span class="size-count"><?php echo $count; ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="products-table">
                <h3 class="card-title">All Orders for This Product</h3>
                <?php if (empty($orders)): ?>
                    <div class="empty-state">
                        <p>No orders yet for this product.</p>
                    </div>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer Name</th>
                                <th>Email</th>
                                <th>Size</th>
                                <th>Quantity</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_reverse($orders) as $order): ?>
                                <tr id="order-<?php echo htmlspecialchars($order['id'] ?? ''); ?>">
                                    <td><code><?php echo htmlspecialchars(substr($order['id'] ?? 'N/A', 0, 8)); ?></code></td>
                                    <td><?php echo htmlspecialchars($order['customer_name'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($order['customer_email'] ?? 'N/A'); ?></td>
                                    <td><span class="size-badge"><?php echo htmlspecialchars($order['size'] ?? 'N/A'); ?></span></td>
                                    <td><?php echo htmlspecialchars($order['quantity'] ?? 1); ?></td>
                                    <td><?php echo htmlspecialchars($order['date'] ?? $order['timestamp'] ?? 'N/A'); ?></td>
                                    <td>
                                        <button class="btn-cancel" onclick="cancelOrder('<?php echo htmlspecialchars($order['id'] ?? ''); ?>', '<?php echo htmlspecialchars($order['customer_name'] ?? 'Customer'); ?>')">Cancel</button>
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
        function cancelOrder(orderId, customerName) {
            if (!confirm(`Are you sure you want to cancel the order for ${customerName}?`)) {
                return;
            }

            fetch('cancel_order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ order_id: orderId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the row from the table
                    const row = document.getElementById(`order-${orderId}`);
                    if (row) {
                        row.style.opacity = '0';
                        setTimeout(() => {
                            row.remove();
                            // Reload the page to update statistics
                            window.location.reload();
                        }, 300);
                    }
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
