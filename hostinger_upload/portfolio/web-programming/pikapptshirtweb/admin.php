<?php
require_once 'auth.php';

// Require login to access admin panel
requireLogin();

// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

// Simple admin panel for managing products
function getProducts() {
    $file = 'data/products.json';
    if (file_exists($file)) {
        $json = file_get_contents($file);
        return json_decode($json, true);
    }
    return [];
}

$products = getProducts();
$message = '';

if (isset($_GET['deleted'])) {
    $message = 'Product deleted successfully!';
}
if (isset($_GET['added'])) {
    $message = 'Product added successfully!';
}
if (isset($_GET['updated'])) {
    $message = 'Product updated successfully!';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Pi Kappa Phi T-Shirt Shop</title>
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
                <h1 class="logo"><span class="greek-letters">ΠΚΦ</span> | Admin Panel</h1>
                <div class="nav-links">
                    <a href="index.php" class="nav-link">Home</a>
                    <a href="admin.php" class="nav-link active">Products</a>
                    <a href="manage_customers.php" class="nav-link">Brothers</a>
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
            <div class="admin-header">
                <h2 class="section-title">Product Management</h2>
                <div class="admin-welcome">
                    <p>Welcome, <strong><?php echo htmlspecialchars($_SESSION['admin_name']); ?></strong></p>
                    <button class="cta-button" onclick="openAddModal()">Add New Product</button>
                </div>
            </div>

            <?php if ($message): ?>
                <div class="alert success"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>

            <div class="products-table">
                <?php if (empty($products)): ?>
                    <div class="empty-state">
                        <p>No products yet. Add your first product!</p>
                    </div>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td>
                                        <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="table-image">
                                    </td>
                                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                                    <td><?php echo htmlspecialchars($product['description']); ?></td>
                                    <td>$<?php echo number_format($product['price'], 2); ?></td>
                                    <td>
                                        <?php 
                                        $isActive = !isset($product['active']) || $product['active'] === true;
                                        echo $isActive ? '<span class="status-badge active">Active</span>' : '<span class="status-badge inactive">Inactive</span>';
                                        ?>
                                    </td>
                                    <td class="actions">
                                        <button class="edit-btn" onclick='editProduct(<?php echo json_encode($product); ?>)'>Edit</button>
                                        <a href="product_orders.php?id=<?php echo htmlspecialchars($product['id']); ?>" class="view-btn">View Orders</a>
                                        <button class="delete-btn" onclick="deleteProduct('<?php echo htmlspecialchars($product['id']); ?>')">Delete</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Add/Edit Product Modal -->
    <div id="productModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeProductModal()">&times;</span>
            <h2 class="modal-title" id="modalTitle">Add New Product</h2>
            <form id="productForm" action="save_product.php" method="POST">
                <input type="hidden" id="productId" name="id">
                <input type="hidden" id="productImage" name="image">
                
                <div class="form-group">
                    <label for="productName">Product Name</label>
                    <input type="text" id="productNameInput" name="name" required>
                </div>

                <div class="form-group">
                    <label for="productDescription">Description</label>
                    <textarea id="productDescription" name="description" rows="3" required></textarea>
                </div>

                <div class="form-group">
                    <label for="productPrice">Price ($)</label>
                    <input type="number" id="productPrice" name="price" step="0.01" min="0" required>
                </div>

                <div class="form-group">
                    <label for="productImageUpload">Upload Image/PDF</label>
                    <input type="file" id="productImageUpload" accept="image/*,.svg,application/pdf">
                    <small>JPG, PNG, GIF, WEBP, SVG, or PDF (Max 5MB) - Leave blank to keep existing image when editing</small>
                    <div id="uploadProgress" style="display: none;">Uploading...</div>
                    <div id="currentImage" style="display: none; margin-top: 0.5rem;">
                        <small>Current: <span id="currentImageName"></span></small>
                    </div>
                </div>

                <div class="form-group">
                    <label for="productActive">
                        <input type="checkbox" id="productActive" name="active" value="1" checked>
                        Active (visible to customers)
                    </label>
                    <small>Uncheck to hide this product from the store</small>
                </div>

                <button type="submit" class="submit-button">Save Product</button>
            </form>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <img src="vectors/starshield.svg" alt="Pi Kappa Phi Star & Shield" class="starshield-logo">
            <p>&copy; <?php echo date('Y'); ?> Pi Kappa Phi. All rights reserved.</p>
        </div>
    </footer>

    <script src="admin.js"></script>
</body>
</html>
