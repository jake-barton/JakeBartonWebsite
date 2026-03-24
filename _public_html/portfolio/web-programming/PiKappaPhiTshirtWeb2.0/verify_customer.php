<?php
require_once 'customer_auth.php';

$token = $_GET['token'] ?? '';
$result = null;

if (!empty($token)) {
    $result = verifyCustomer($token);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification - Pi Kappa Phi Apparel</title>
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
                </div>
            </div>
        </div>
    </nav>

    <section class="auth-section">
        <div class="container">
            <div class="auth-card">
                <?php if ($result && $result['success']): ?>
                    <h2 class="auth-title">✓ Email Verified!</h2>
                    <div class="alert alert-success">
                        <?php echo htmlspecialchars($result['message']); ?>
                    </div>
                    <p style="margin-top: 1rem; text-align: center;">
                        You can now login to your account.
                    </p>
                    <a href="customer_login.php" class="cta-button" style="margin-top: 1rem; display: inline-block;">Go to Login</a>
                <?php elseif ($result): ?>
                    <h2 class="auth-title">Verification Failed</h2>
                    <div class="alert alert-error">
                        <?php echo htmlspecialchars($result['error']); ?>
                    </div>
                    <p style="margin-top: 1rem; text-align: center;">
                        <a href="customer_register.php">Create a new account</a>
                    </p>
                <?php else: ?>
                    <h2 class="auth-title">Invalid Token</h2>
                    <div class="alert alert-error">
                        No verification token provided.
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
