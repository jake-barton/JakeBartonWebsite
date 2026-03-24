<?php
require_once 'customer_auth.php';
require_once __DIR__ . '/includes/csrf.php';

// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

// Load products from JSON file
function getProducts() {
    $file = 'data/products.json';
    if (file_exists($file)) {
        $json = file_get_contents($file);
        $allProducts = json_decode($json, true);
        // Return all products (both active and inactive)
        return $allProducts;
    }
    return [];
}

$products = getProducts();
$isLoggedIn = isCustomerLoggedIn();
$customerName = $isLoggedIn ? getCustomerName() : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pi Kappa Phi Apparel</title>
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
                <h1 class="logo"><span class="greek-letters">ΠΚΦ</span> | Apparel</h1>
                <div class="nav-links">
                    <a href="#home" class="nav-link active">Home</a>
                    <a href="#products" class="nav-link">Shop</a>
                    <?php if ($isLoggedIn): ?>
                        <a href="customer_dashboard.php" class="nav-link">My Orders</a>
                    <?php else: ?>
                        <a href="customer_login.php" class="nav-link">Login</a>
                        <a href="customer_register.php" class="nav-link">Sign Up</a>
                    <?php endif; ?>
                    <a href="admin.php" class="nav-link">Admin</a>
                </div>
            </div>
        </div>
    </nav>

    <section id="home" class="hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-logo">
                    <img src="vectors/banner_logo.svg" alt="Pi Kappa Phi" class="banner-logo">
                </div>
                <h3 class="hero-title">Apparel</h3>
                <p class="hero-subtitle">Exceptional Starts Here</p>
                <a href="#products" class="cta-button">Shop Now</a>
            </div>
        </div>
    </section>

    <section id="products" class="products-section">
        <div class="container">
            <h2 class="section-title">Our Collection</h2>
            <div class="products-grid">
                <?php if (empty($products)): ?>
                    <div class="empty-state">
                        <p>No products available yet. Check back soon!</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($products as $product): ?>
                        <?php 
                        $isActive = !isset($product['active']) || $product['active'] === true;
                        ?>
                        <div class="product-card <?php echo !$isActive ? 'inactive-product' : ''; ?>" data-id="<?php echo htmlspecialchars($product['id']); ?>">
                            <?php if (!$isActive): ?>
                                <div class="inactive-badge">Inactive</div>
                            <?php endif; ?>
                            <div class="product-image">
                                <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                            </div>
                            <div class="product-info">
                                <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                                <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
                                <div class="product-footer">
                                    <span class="product-price">$<?php echo number_format($product['price'], 2); ?></span>
                                    <?php if ($isActive): ?>
                                        <button class="order-button" onclick="openOrderModal(<?php echo htmlspecialchars(json_encode($product)); ?>)">
                                            Order Now
                                        </button>
                                    <?php else: ?>
                                        <button class="order-button" disabled style="cursor: not-allowed; opacity: 0.5;">
                                            Not Available
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Order Modal -->
    <div id="orderModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeOrderModal()">&times;</span>
            <h2 class="modal-title">Place Your Order</h2>
            <form id="orderForm" action="process_order.php" method="POST">
                <input type="hidden" id="productId" name="product_id">
                <input type="hidden" id="productName" name="product_name">
                <input type="hidden" id="productPrice" name="product_price">
                
                <div class="form-group">
                    <label>Product</label>
                    <p id="modalProductName" class="product-display"></p>
                </div>

                <div class="form-group">
                    <label>Select Sizes & Quantities</label>
                    <div class="size-selector">
                        <div class="size-option">
                            <input type="checkbox" class="size-checkbox" data-size="S">
                            <label>Small (S)</label>
                            <input type="number" class="size-quantity" data-size="S" min="1" value="1" disabled>
                        </div>
                        <div class="size-option">
                            <input type="checkbox" class="size-checkbox" data-size="M">
                            <label>Medium (M)</label>
                            <input type="number" class="size-quantity" data-size="M" min="1" value="1" disabled>
                        </div>
                        <div class="size-option">
                            <input type="checkbox" class="size-checkbox" data-size="L">
                            <label>Large (L)</label>
                            <input type="number" class="size-quantity" data-size="L" min="1" value="1" disabled>
                        </div>
                        <div class="size-option">
                            <input type="checkbox" class="size-checkbox" data-size="XL">
                            <label>X-Large (XL)</label>
                            <input type="number" class="size-quantity" data-size="XL" min="1" value="1" disabled>
                        </div>
                        <div class="size-option">
                            <input type="checkbox" class="size-checkbox" data-size="XXL">
                            <label>XX-Large (XXL)</label>
                            <input type="number" class="size-quantity" data-size="XXL" min="1" value="1" disabled>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="notes">Special Instructions (Optional)</label>
                    <textarea id="notes" name="notes" rows="3"></textarea>
                </div>

                <button type="submit" class="submit-button">Submit Order</button>
            </form>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <img src="vectors/starshield.svg" alt="Pi Kappa Phi Star & Shield" class="starshield-logo">
            <p>&copy; <?php echo date('Y'); ?> Pi Kappa Phi. All rights reserved.</p>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>
