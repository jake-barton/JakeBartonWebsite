<?php
require_once 'customer_auth.php';

// If already logged in, redirect to dashboard
if (isCustomerLoggedIn()) {
    header('Location: customer_dashboard.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    
    if (empty($email)) {
        $error = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format';
    } else {
        // Process forgot password request
        $result = sendPasswordResetEmail($email);
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
    <title>Forgot Password - Pi Kappa Phi Apparel</title>
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
                <h2 class="auth-title">Reset Your Password</h2>
                <p class="auth-subtitle">Enter your email address and we'll send you a reset link</p>
                
                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <p style="margin: 0 0 1rem 0;">
                            <strong>Check your Samford email!</strong><br>
                            We've sent a password reset link to your inbox. The link will expire in 1 hour.
                        </p>
                        <a href="customer_login.php" class="cta-button" style="margin-top: 1rem; display: inline-block;">Back to Login</a>
                    </div>
                <?php else: ?>
                    <form method="POST" class="auth-form">
                        <div class="form-group">
                            <label for="email">Samford Email</label>
                            <input type="email" id="email" name="email" required placeholder="your.name@samford.edu" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                        </div>
                        
                        <button type="submit" class="submit-button">Send Reset Link</button>
                    </form>
                    
                    <p class="auth-footer">
                        Remember your password? <a href="customer_login.php">Login here</a>
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
