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
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    if (empty($name) || empty($email) || empty($password)) {
        $error = 'All fields are required';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters';
    } else {
        $result = createCustomer($name, $email, $password);
        if ($result['success']) {
            $success = $result['message'];
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
    <title>Create Account - Pi Kappa Phi Apparel</title>
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
                    <a href="customer_login.php" class="nav-link">Login</a>
                </div>
            </div>
        </div>
    </nav>

    <section class="auth-section">
        <div class="container">
            <div class="auth-card">
                <h2 class="auth-title">Create Your Account</h2>
                <p class="auth-subtitle">Join Pi Kappa Phi Apparel with your Samford email</p>
                
                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <p style="margin: 0 0 1rem 0;">
                            <strong>Check your Samford email!</strong><br>
                            We've sent a verification link to your inbox. Don't forget to check your spam folder if you don't see it within a few minutes.
                        </p>
                        <a href="customer_login.php<?php echo $returnToOrder ? '?return=order' : ''; ?>" class="cta-button" style="margin-top: 1rem; display: inline-block;">Go to Login</a>
                    </div>
                <?php else: ?>
                    <form method="POST" class="auth-form">
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Samford Email</label>
                            <input type="email" id="email" name="email" required placeholder="your.name@samford.edu" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                            <small>Only @samford.edu email addresses are allowed</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" required minlength="6">
                            <small>At least 6 characters</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm_password">Confirm Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" required minlength="6">
                        </div>
                        
                        <button type="submit" class="submit-button">Create Account</button>
                    </form>
                    
                    <p class="auth-footer">
                        Already have an account? <a href="customer_login.php<?php echo $returnToOrder ? '?return=order' : ''; ?>">Login here</a>
                    </p>
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
