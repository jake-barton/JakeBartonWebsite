<?php
require_once 'customer_auth.php';

// Check if returning from order process
$returnToOrder = isset($_GET['return']) && $_GET['return'] === 'order';

// If already logged in, redirect appropriately
if (isCustomerLoggedIn()) {
    if ($returnToOrder) {
        header('Location: index.php?from=auth#products');
    } else {
        header('Location: customer_dashboard.php');
    }
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'All fields are required';
    } else {
        $result = validateCustomerLogin($email, $password);
        if ($result['success']) {
            // Check if we should return to order
            if ($returnToOrder) {
                header('Location: index.php?from=auth#products');
            } else {
                header('Location: customer_dashboard.php');
            }
            exit;
        } else {
            $error = $result['error'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pi Kappa Phi Apparel</title>
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
                <h1 class="logo"><span class="greek-letters">ΠΚΦ</span> Apparel</h1>
                <div class="nav-links">
                    <a href="index.php" class="nav-link">Home</a>
                    <a href="customer_register.php" class="nav-link">Sign Up</a>
                </div>
            </div>
        </div>
    </nav>

    <section class="auth-section">
        <div class="container">
            <div class="auth-card">
                <h2 class="auth-title">Welcome Back</h2>
                <p class="auth-subtitle">Login to view and manage your orders</p>
                
                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                
                <form method="POST" class="auth-form">
                    <div class="form-group">
                        <label for="email">Samford Email</label>
                        <input type="email" id="email" name="email" required placeholder="your.name@samford.edu" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                        <small><a href="forgot_password.php" style="color: #005596; text-decoration: none;">Forgot password?</a></small>
                    </div>
                    
                    <button type="submit" class="submit-button">Login</button>
                </form>
                
                <p class="auth-footer">
                    Don't have an account? <a href="customer_register.php<?php echo $returnToOrder ? '?return=order' : ''; ?>">Sign up here</a>
                </p>
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
