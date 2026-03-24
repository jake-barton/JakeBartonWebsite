<?php
require_once 'auth.php';
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/security.php';

// Require login and owner status
requireLogin();
requireOwner();

// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

$success = '';
$error = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    // Validate CSRF token
    requireCSRFToken();
    
    if ($_POST['action'] === 'add_email') {
        $email = trim($_POST['email'] ?? '');
        
        if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $authorizedEmails = getAuthorizedEmails();
            
            if (!in_array(strtolower($email), array_map('strtolower', $authorizedEmails))) {
                $authorizedEmails[] = $email;
                safeWriteJSON('data/authorized_emails.json', $authorizedEmails);
                $success = "Email '$email' has been authorized.";
            } else {
                $error = "Email '$email' is already authorized.";
            }
        } else {
            $error = 'Please enter a valid email address.';
        }
    } elseif ($_POST['action'] === 'remove_email') {
        $email = $_POST['email'] ?? '';
        $authorizedEmails = getAuthorizedEmails();
        
        // Prevent removing the owner email
        if (strtolower($email) === strtolower($authorizedEmails[0])) {
            $error = 'Cannot remove owner email.';
        } else {
            $authorizedEmails = array_values(array_filter($authorizedEmails, function($e) use ($email) {
                return strtolower($e) !== strtolower($email);
            }));
            
            safeWriteJSON('data/authorized_emails.json', $authorizedEmails);
            $success = "Email '$email' has been removed from authorized list.";
        }
    } elseif ($_POST['action'] === 'update_pin') {
        $newPin = $_POST['new_pin'] ?? '';
        
        if (strlen($newPin) === 4 && ctype_digit($newPin)) {
            $config = safeReadJSON('data/config.json');
            $config['admin_pin'] = $newPin;
            safeWriteJSON('data/config.json', $config);
            $success = 'Admin PIN has been updated.';
        } else {
            $error = 'PIN must be exactly 4 digits.';
        }
    }
}

$authorizedEmails = getAuthorizedEmails();
$admins = getAdmins();
$currentPin = getAdminPin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Access - Pi Kappa Phi Apparel</title>
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
                    <a href="index.php" class="nav-link">Home</a>
                    <a href="admin.php" class="nav-link">Products</a>
                    <a href="manage_customers.php" class="nav-link">Customers</a>
                    <a href="manage_access.php" class="nav-link active">Manage Access</a>
                    <a href="logout.php" class="nav-link">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <section class="admin-section">
        <div class="container">
            <div class="admin-header">
                <h2 class="section-title">Manage Access</h2>
                <p class="owner-badge">Owner: <?php echo e($_SESSION['admin_email']); ?></p>
            </div>

            <?php if ($success): ?>
                <div class="alert success"><?php echo e($success); ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert error"><?php echo e($error); ?></div>
            <?php endif; ?>

            <!-- Update PIN Section -->
            <div class="access-card">
                <h3 class="card-title">Admin PIN</h3>
                <p class="card-description">Current PIN: <strong><?php echo e($currentPin); ?></strong></p>
                <form method="POST" class="inline-form">
                    <?php csrfField(); ?>
                    <input type="hidden" name="action" value="update_pin">
                    <div class="form-row">
                        <input type="password" name="new_pin" placeholder="New 4-digit PIN" maxlength="4" required>
                        <button type="submit" class="submit-button">Update PIN</button>
                    </div>
                </form>
            </div>

            <!-- Authorized Emails Section -->
            <div class="access-card">
                <h3 class="card-title">Authorized Emails</h3>
                <p class="card-description">Only these emails can create admin accounts</p>
                
                <form method="POST" class="inline-form">
                    <?php csrfField(); ?>
                    <input type="hidden" name="action" value="add_email">
                    <div class="form-row">
                        <input type="email" name="email" placeholder="email@example.com" required>
                        <button type="submit" class="submit-button">Add Email</button>
                    </div>
                </form>

                <div class="email-list">
                    <?php foreach ($authorizedEmails as $email): ?>
                        <div class="email-item">
                            <span class="email-text"><?php echo e($email); ?></span>
                            <?php if (strtolower($email) === strtolower($authorizedEmails[0])): ?>
                                <span class="owner-tag">Owner</span>
                            <?php else: ?>
                                <form method="POST" style="display: inline;">
                                    <?php csrfField(); ?>
                                    <input type="hidden" name="action" value="remove_email">
                                    <input type="hidden" name="email" value="<?php echo e($email); ?>">
                                    <button type="submit" class="delete-btn small" onclick="return confirm('Remove this email?')">Remove</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Registered Admins Section -->
            <div class="access-card">
                <h3 class="card-title">Registered Admin Accounts</h3>
                <?php if (empty($admins)): ?>
                    <p class="empty-state">No admin accounts registered yet.</p>
                <?php else: ?>
                    <div class="admins-list">
                        <?php foreach ($admins as $admin): ?>
                            <div class="admin-item">
                                <div class="admin-info">
                                    <strong><?php echo e($admin['name']); ?></strong>
                                    <span><?php echo e($admin['email']); ?></span>
                                    <small>Created: <?php echo e($admin['created_at']); ?></small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
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
