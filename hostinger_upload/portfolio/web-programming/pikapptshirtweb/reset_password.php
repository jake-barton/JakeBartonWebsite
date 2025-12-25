<?php
require_once 'customer_auth.php';

// If already logged in, redirect to dashboard
if (isCustomerLoggedIn()) {
    header('Location: customer_dashboard.php');
    exit;
}

$error = '';
$success = '';
$token = $_GET['token'] ?? '';

// Validate token
if (empty($token)) {
    $error = 'Invalid or missing reset token';
} else {
    $tokenData = validatePasswordResetToken($token);
    if (!$tokenData['valid']) {
        $error = $tokenData['error'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($error)) {
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $resetToken = $_POST['token'] ?? '';
    
    if (empty($password) || empty($confirmPassword)) {
        $error = 'All fields are required';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters';
    } else {
        $result = resetPassword($resetToken, $password);
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
    <title>Reset Password - Pi Kappa Phi Apparel</title>
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
                <h2 class="auth-title">Set New Password</h2>
                <p class="auth-subtitle">Enter your new password below</p>
                
                <?php if ($error): ?>
                    <div class="alert alert-error">
                        <?php echo htmlspecialchars($error); ?>
                        <p style="margin-top: 1rem;">
                            <a href="forgot_password.php" class="cta-button" style="display: inline-block;">Request New Reset Link</a>
                        </p>
                    </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <p style="margin: 0 0 1rem 0;">
                            <strong>Password Reset Complete!</strong><br>
                            You can now log in with your new password.
                        </p>
                        <a href="customer_login.php" class="cta-button" style="margin-top: 1rem; display: inline-block;">Go to Login</a>
                    </div>
                <?php elseif (empty($error)): ?>
                    <form method="POST" class="auth-form">
                        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                        
                        <div class="form-group">
                            <label for="password">New Password</label>
                            <input type="password" id="password" name="password" required minlength="6">
                            <small>At least 6 characters</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm_password">Confirm New Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" required minlength="6">
                        </div>
                        
                        <button type="submit" class="submit-button">Reset Password</button>
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
