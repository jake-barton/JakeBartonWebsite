<?php
require_once 'auth.php';
requireLogin();

// Get all orders
function getOrders() {
    $file = 'data/orders.json';
    if (file_exists($file)) {
        $json = file_get_contents($file);
        return json_decode($json, true) ?: [];
    }
    return [];
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

$orders = getOrders();
$products = getProducts();

// Create product lookup
$productLookup = [];
foreach ($products as $product) {
    $productLookup[$product['id']] = $product['name'];
}

// Calculate statistics
$totalOrders = count($orders);
$ordersByProduct = [];
$sizesByProduct = [];
$totalSizes = ['S' => 0, 'M' => 0, 'L' => 0, 'XL' => 0, 'XXL' => 0];

foreach ($orders as $order) {
    $productId = $order['product_id'] ?? '';
    $size = $order['size'] ?? '';
    
    // Count orders by product
    if (!isset($ordersByProduct[$productId])) {
        $ordersByProduct[$productId] = 0;
    }
    $ordersByProduct[$productId]++;
    
    // Count sizes by product
    if (!isset($sizesByProduct[$productId])) {
        $sizesByProduct[$productId] = ['S' => 0, 'M' => 0, 'L' => 0, 'XL' => 0, 'XXL' => 0];
    }
    if (isset($sizesByProduct[$productId][$size])) {
        $sizesByProduct[$productId][$size]++;
    }
    
    // Total sizes
    if (isset($totalSizes[$size])) {
        $totalSizes[$size]++;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders - Pi Kappa Phi Apparel</title>
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
                <h1 class="logo"><span class="greek-letters">ΠΚΦ</span> | Orders</h1>
                <div class="nav-links">
                    <a href="admin.php" class="nav-link">Products</a>
                    <a href="orders.php" class="nav-link active">Orders</a>
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
            <h2 class="section-title">Order Management</h2>

            <!-- Statistics Overview -->
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Total Orders</h3>
                    <p class="stat-number"><?php echo $totalOrders; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Size Breakdown</h3>
                    <div class="size-breakdown">
                        <?php foreach ($totalSizes as $size => $count): ?>
                            <div class="size-stat">
                                <span class="size-label"><?php echo $size; ?>:</span>
                                <span class="size-count"><?php echo $count; ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Orders by Product -->
            <?php if (!empty($ordersByProduct)): ?>
                <div class="access-card">
                    <h3 class="card-title">Orders by Product</h3>
                    <?php foreach ($ordersByProduct as $productId => $count): ?>
                        <div class="product-order-summary">
                            <h4><?php echo htmlspecialchars($productLookup[$productId] ?? 'Unknown Product'); ?></h4>
                            <p><strong>Total Orders:</strong> <?php echo $count; ?></p>
                            <div class="size-breakdown">
                                <?php foreach ($sizesByProduct[$productId] as $size => $sizeCount): ?>
                                    <?php if ($sizeCount > 0): ?>
                                        <div class="size-stat">
                                            <span class="size-label"><?php echo $size; ?>:</span>
                                            <span class="size-count"><?php echo $sizeCount; ?></span>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- All Orders Table -->
            <div class="products-table">
                <h3 class="card-title">All Orders</h3>
                <?php if (empty($orders)): ?>
                    <div class="empty-state">
                        <p>No orders yet.</p>
                    </div>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Product</th>
                                <th>Customer</th>
                                <th>Email</th>
                                <th>Size</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_reverse($orders) as $order): ?>
                                <tr>
                                    <td><code><?php echo htmlspecialchars(substr($order['id'] ?? 'N/A', 0, 8)); ?></code></td>
                                    <td><?php echo htmlspecialchars($order['product_name'] ?? ($productLookup[$order['product_id'] ?? ''] ?? 'Unknown')); ?></td>
                                    <td><?php echo htmlspecialchars($order['customer_name'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($order['customer_email'] ?? 'N/A'); ?></td>
                                    <td><span class="size-badge"><?php echo htmlspecialchars($order['size'] ?? 'N/A'); ?></span></td>
                                    <td><?php echo htmlspecialchars($order['date'] ?? $order['timestamp'] ?? 'N/A'); ?></td>
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
</body>
</html>
