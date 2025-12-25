<?php
require_once 'auth.php';
require_once 'customer_auth.php';

// Require login to access admin panel
requireLogin();

// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

$customers = getCustomers();
$message = '';

if (isset($_GET['deleted'])) {
    $message = 'Customer account deleted successfully!';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Customers - Pi Kappa Phi T-Shirt Shop</title>
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
                    <a href="admin.php" class="nav-link">Products</a>
                    <a href="manage_customers.php" class="nav-link active">Customers</a>
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
                <h2 class="section-title">Customer Management</h2>
                <div class="admin-welcome">
                    <p>Welcome, <strong><?php echo htmlspecialchars($_SESSION['admin_name']); ?></strong></p>
                    <p class="customer-count"><?php echo count($customers); ?> registered customers</p>
                </div>
            </div>

            <?php if ($message): ?>
                <div class="alert success"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>

            <div class="products-table">
                <?php if (empty($customers)): ?>
                    <div class="empty-state">
                        <p>No customer accounts yet.</p>
                    </div>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Verified</th>
                                <th>Registered</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($customers as $customer): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($customer['name']); ?></td>
                                    <td><?php echo htmlspecialchars($customer['email']); ?></td>
                                    <td>
                                        <?php 
                                        $verified = $customer['verified'] ?? false;
                                        echo $verified ? '<span class="status-badge active">Verified</span>' : '<span class="status-badge inactive">Not Verified</span>';
                                        ?>
                                    </td>
                                    <td><?php echo date('M j, Y', strtotime($customer['created_at'])); ?></td>
                                    <td class="actions">
                                        <button class="view-btn" onclick="viewCustomerOrders('<?php echo htmlspecialchars($customer['id']); ?>', '<?php echo htmlspecialchars($customer['name']); ?>')">View Orders</button>
                                        <button class="delete-btn" onclick="deleteCustomer('<?php echo htmlspecialchars($customer['id']); ?>', '<?php echo htmlspecialchars($customer['name']); ?>')">Delete</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Customer Orders Modal -->
    <div id="customerOrdersModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeOrdersModal()">&times;</span>
            <h2 class="modal-title" id="customerOrdersTitle">Customer Orders</h2>
            <div id="customerOrdersContent"></div>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <img src="vectors/starshield.svg" alt="Pi Kappa Phi Star & Shield" class="starshield-logo">
            <p>&copy; <?php echo date('Y'); ?> Pi Kappa Phi. All rights reserved.</p>
        </div>
    </footer>

    <script>
        function deleteCustomer(customerId, customerName) {
            if (confirm(`Are you sure you want to delete the account for ${customerName}?\n\nThis will permanently delete their account and all associated orders.`)) {
                window.location.href = `delete_customer.php?id=${customerId}`;
            }
        }

        function viewCustomerOrders(customerId, customerName) {
            document.getElementById('customerOrdersTitle').textContent = `Orders for ${customerName}`;
            
            // Fetch customer orders
            fetch(`get_customer_orders.php?customer_id=${customerId}`)
                .then(response => response.json())
                .then(data => {
                    const content = document.getElementById('customerOrdersContent');
                    
                    if (data.orders && data.orders.length > 0) {
                        let html = '<div class="orders-list">';
                        data.orders.forEach(order => {
                            html += `
                                <div class="order-item">
                                    <h3>${order.product_name}</h3>
                                    <p><strong>Size:</strong> ${order.size}</p>
                                    <p><strong>Quantity:</strong> ${order.quantity}</p>
                                    <p><strong>Price:</strong> $${order.product_price}</p>
                                    <p><strong>Total:</strong> $${(order.product_price * order.quantity).toFixed(2)}</p>
                                    <p><strong>Date:</strong> ${new Date(order.date).toLocaleDateString()}</p>
                                    <p><strong>Status:</strong> ${order.status}</p>
                                    ${order.notes ? `<p><strong>Notes:</strong> ${order.notes}</p>` : ''}
                                </div>
                            `;
                        });
                        html += '</div>';
                        content.innerHTML = html;
                    } else {
                        content.innerHTML = '<p>No orders found for this customer.</p>';
                    }
                    
                    document.getElementById('customerOrdersModal').style.display = 'block';
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading customer orders');
                });
        }

        function closeOrdersModal() {
            document.getElementById('customerOrdersModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('customerOrdersModal');
            if (event.target == modal) {
                closeOrdersModal();
            }
        }
    </script>
</body>
</html>
