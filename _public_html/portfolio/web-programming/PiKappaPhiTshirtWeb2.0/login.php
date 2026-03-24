<?php
require_once 'auth.php';
require_once __DIR__ . '/includes/csrf.php';

// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

// If already logged in, redirect to admin
if (isLoggedIn()) {
    header('Location: admin.php');
    exit;
}

$error = '';
$success = '';
$showLogin = true;

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    // Validate CSRF token
    requireCSRFToken();
    
    if ($_POST['action'] === 'login') {
        $email = $_POST['email'] ?? '';
        $pin = $_POST['pin'] ?? '';
        
        $result = loginAdmin($email, $pin);
        
        if ($result['success']) {
            header('Location: admin.php');
            exit;
        } else {
            $error = $result['message'];
        }
    } elseif ($_POST['action'] === 'create') {
        $email = $_POST['email'] ?? '';
        $name = $_POST['name'] ?? '';
        $pin = $_POST['pin'] ?? '';
        
        // Check if email is authorized
        if (!isEmailAuthorized($email)) {
            $error = 'Email not authorized. Contact site owner for access.';
        } elseif ($pin !== getAdminPin()) {
            $error = 'Incorrect PIN.';
        } elseif (empty($name)) {
            $error = 'Please enter your name.';
        } else {
            if (saveAdmin($email, $name)) {
                // Automatically log in the new admin
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_email'] = $email;
                $_SESSION['admin_name'] = $name;
                $_SESSION['is_owner'] = (strtolower($email) === strtolower(getAuthorizedEmails()[0]));
                
                header('Location: admin.php');
                exit;
            } else {
                $error = 'Email already registered. Please login instead.';
                $showLogin = true;
            }
        }
    }
}

// Toggle between login and create account
if (isset($_GET['create'])) {
    $showLogin = false;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Pi Kappa Phi Apparel</title>
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
                    <a href="index.php#products" class="nav-link">Shop</a>
                </div>
            </div>
        </div>
    </nav>

    <section class="admin-section">
        <div class="container">
            <div class="login-container">
                <h2 class="section-title">Admin Access</h2>
                
                <?php if ($error): ?>
                    <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert success"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>

                <?php if ($showLogin): ?>
                    <!-- Login Form -->
                    <div class="auth-card">
                        <h3 class="auth-title">Login to Admin Panel</h3>
                        <form method="POST" class="auth-form">
                            <?php csrfField(); ?>
                            <input type="hidden" name="action" value="login">
                            
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" required placeholder="your@email.com">
                            </div>
                            
                            <div class="form-group">
                                <label for="pin">Admin PIN</label>
                                <input type="password" id="pin" name="pin" required placeholder="Enter 4-digit PIN" maxlength="4">
                                <small>Enter the admin PIN provided to you</small>
                            </div>
                            
                            <button type="submit" class="submit-button">Login</button>
                        </form>
                        
                        <div class="auth-switch">
                            <p>Don't have an account? <a href="?create=1">Create Account</a></p>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Create Account Form -->
                    <div class="auth-card">
                        <h3 class="auth-title">Create Admin Account</h3>
                        <form method="POST" class="auth-form">
                            <?php csrfField(); ?>
                            <input type="hidden" name="action" value="create">
                            
                            <div class="form-group">
                                <label for="name">Full Name</label>
                                <input type="text" id="name" name="name" required placeholder="John Doe">
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" required placeholder="your@email.com">
                                <small>Must be authorized by site owner</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="pin">Admin PIN</label>
                                <input type="password" id="pin" name="pin" required placeholder="Enter 4-digit PIN" maxlength="4">
                                <small>Enter the admin PIN provided to you</small>
                            </div>
                            
                            <button type="submit" class="submit-button">Create Account</button>
                        </form>
                        
                        <div class="auth-switch">
                            <p>Already have an account? <a href="login.php">Login</a></p>
                        </div>
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
