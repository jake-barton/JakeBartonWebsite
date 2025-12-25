<?php
// Customer Authentication Library

session_start();

// Check if customer is logged in
function isCustomerLoggedIn() {
    return isset($_SESSION['customer_id']) && isset($_SESSION['customer_email']);
}

// Require customer login
function requireCustomerLogin() {
    if (!isCustomerLoggedIn()) {
        header('Location: customer_login.php');
        exit;
    }
}

// Get current customer ID
function getCustomerId() {
    return $_SESSION['customer_id'] ?? null;
}

// Get current customer email
function getCustomerEmail() {
    return $_SESSION['customer_email'] ?? null;
}

// Get current customer name
function getCustomerName() {
    return $_SESSION['customer_name'] ?? null;
}

// Login customer
function loginCustomer($customerId, $email, $name) {
    $_SESSION['customer_id'] = $customerId;
    $_SESSION['customer_email'] = $email;
    $_SESSION['customer_name'] = $name;
}

// Logout customer
function logoutCustomer() {
    unset($_SESSION['customer_id']);
    unset($_SESSION['customer_email']);
    unset($_SESSION['customer_name']);
    session_destroy();
}

// Load customers from JSON
function getCustomers() {
    $file = 'data/customers.json';
    if (file_exists($file)) {
        $json = file_get_contents($file);
        return json_decode($json, true) ?: [];
    }
    return [];
}

// Save customers to JSON
function saveCustomers($customers) {
    $file = 'data/customers.json';
    $dir = dirname($file);
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    return file_put_contents($file, json_encode($customers, JSON_PRETTY_PRINT));
}

// Find customer by email
function findCustomerByEmail($email) {
    $customers = getCustomers();
    foreach ($customers as $customer) {
        if (strtolower($customer['email']) === strtolower($email)) {
            return $customer;
        }
    }
    return null;
}

// Find customer by ID
function findCustomerById($id) {
    $customers = getCustomers();
    foreach ($customers as $customer) {
        if ($customer['id'] === $id) {
            return $customer;
        }
    }
    return null;
}

// Validate Samford email
function isSamfordEmail($email) {
    return preg_match('/@samford\.edu$/i', $email);
}

// Generate verification token
function generateVerificationToken() {
    return bin2hex(random_bytes(32));
}

// Send verification email
function sendVerificationEmail($email, $token, $name) {
    // Include config for base URL
    require_once __DIR__ . '/config.php';
    $baseUrl = getBaseUrl();
    
    $verificationLink = $baseUrl . "/verify_customer.php?token=" . urlencode($token);
    
    $subject = "Verify Your Pi Kappa Phi Apparel Account";
    
    // HTML Email Template - Matches website design exactly
    $htmlMessage = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            body {
                margin: 0;
                padding: 20px;
                font-family: "Montserrat", "Brandon Grotesque", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
                background: linear-gradient(135deg, #e6eef7 0%, #faf7f0 100%);
            }
            .email-wrapper {
                max-width: 600px;
                margin: 0 auto;
                background: #ffffff;
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 8px 32px rgba(0, 48, 135, 0.15);
            }
            .header {
                background: linear-gradient(135deg, #005596 0%, #065189 100%);
                padding: 50px 20px;
                text-align: center;
                border-bottom: 4px solid #E7A614;
                position: relative;
            }
            .header::after {
                content: "";
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(231, 166, 20, 0.1), transparent);
            }
            .logo-banner {
                max-width: 250px;
                height: auto;
                margin-bottom: 15px;
            }
            .greek-letters {
                font-family: "Times New Roman", Symbol, "Lucida Sans Unicode", serif;
                font-size: 56px;
                color: #E7A614;
                font-weight: bold;
                letter-spacing: 12px;
                text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
                margin-bottom: 10px;
            }
            .header-title {
                color: #ffffff;
                font-size: 28px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 2px;
                font-family: "Montserrat", "Brandon Grotesque", sans-serif;
            }
            .content {
                padding: 50px 40px;
                background: #ffffff;
            }
            .greeting {
                font-size: 24px;
                font-weight: 700;
                color: #005596;
                margin-bottom: 25px;
                font-family: "Montserrat", "Brandon Grotesque", sans-serif;
            }
            .message {
                font-size: 16px;
                line-height: 1.8;
                color: #212121;
                margin-bottom: 25px;
                font-family: "Montserrat", "Brandon Grotesque", sans-serif;
                font-weight: 400;
            }
            .button-container {
                text-align: center;
                margin: 40px 0;
            }
            .verify-button {
                display: inline-block;
                padding: 1rem 2.5rem;
                background: #005596;
                color: #ffffff !important;
                text-decoration: none;
                border: 2px solid #005596;
                border-radius: 50px;
                font-weight: 700;
                font-size: 16px;
                text-transform: uppercase;
                letter-spacing: 1px;
                box-shadow: 0 4px 15px rgba(0, 85, 150, 0.2);
                font-family: "Montserrat", "Brandon Grotesque", sans-serif;
                transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
                position: relative;
                overflow: hidden;
            }
            .verify-button:hover {
                border-color: #E7A614;
                color: #005596;
                transform: translateY(-4px) scale(1.02);
                box-shadow: 0 12px 35px rgba(0, 85, 150, 0.3);
                background: #E7A614;
            }
            .divider {
                height: 2px;
                background: linear-gradient(90deg, transparent, #E7A614, transparent);
                margin: 30px 0;
            }
            .alternative-link {
                margin-top: 30px;
                padding: 25px;
                background: linear-gradient(135deg, #e6eef7 0%, #faf7f0 100%);
                border-radius: 8px;
                border-left: 4px solid #005596;
                font-size: 13px;
                color: #5a5a5a;
                word-break: break-all;
                font-family: "Montserrat", "Brandon Grotesque", sans-serif;
            }
            .alternative-link-title {
                font-weight: 700;
                color: #005596;
                margin-bottom: 10px;
                font-size: 14px;
            }
            .footer {
                background: linear-gradient(135deg, #005596 0%, #065189 100%);
                padding: 40px 20px;
                text-align: center;
                color: #ffffff;
                border-top: 3px solid #E7A614;
                position: relative;
            }
            .footer::before {
                content: "";
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(231, 166, 20, 0.1), transparent);
            }
            .starshield {
                width: 50px;
                height: auto;
                margin-bottom: 15px;
                opacity: 0.9;
            }
            .footer-greek {
                font-family: "Times New Roman", Symbol, "Lucida Sans Unicode", serif;
                font-size: 32px;
                color: #E7A614;
                letter-spacing: 6px;
                margin-bottom: 12px;
                font-weight: 400;
            }
            .footer-text {
                font-size: 14px;
                font-weight: 500;
                margin: 8px 0;
                font-family: "Montserrat", "Brandon Grotesque", sans-serif;
                color: rgba(255, 255, 255, 0.95);
            }
            .footer-motto {
                font-style: italic;
                color: #E7A614;
                font-weight: 600;
                margin: 10px 0;
            }
            .footer-copyright {
                margin-top: 20px;
                font-size: 12px;
                opacity: 0.7;
                font-weight: 400;
            }
            @media only screen and (max-width: 600px) {
                .content {
                    padding: 30px 20px;
                }
                .greeting {
                    font-size: 20px;
                }
                .greek-letters {
                    font-size: 40px;
                    letter-spacing: 8px;
                }
                .verify-button {
                    padding: 15px 35px;
                    font-size: 14px;
                }
            }
        </style>
    </head>
    <body>
        <div class="email-wrapper">
            <div class="header">
                <div class="greek-letters">ΠΚΦ</div>
                <div class="header-title">Apparel</div>
            </div>
            
            <div class="content">
                <div class="greeting">Hey ' . htmlspecialchars($name) . ',</div>
                
                <div class="message">
                    Thanks for signing up! Just need you to verify your email real quick.
                </div>
                
                <div class="button-container">
                    <a href="' . htmlspecialchars($verificationLink) . '" class="verify-button">Verify Email</a>
                </div>
                
                <div class="divider"></div>
                
                <div class="alternative-link">
                    <div class="alternative-link-title">Link not working?</div>
                    Copy and paste this link into your browser:<br><br>
                    <a href="' . htmlspecialchars($verificationLink) . '" style="color: #003087; font-weight: 600;">' . htmlspecialchars($verificationLink) . '</a>
                </div>
                
                <div class="message" style="margin-top: 35px; font-size: 14px; color: #5a5a5a;">
                    If you didn\'t sign up, just ignore this.
                </div>
            </div>
            
            <div class="footer">
                <div class="footer-greek">ΠΚΦ</div>
                <div class="footer-text">Pi Kappa Phi</div>
                <div class="footer-text footer-motto">Leaders among Men</div>
                <div class="footer-text footer-copyright" style="margin-top: 15px;">
                    &copy; ' . date('Y') . ' Pi Kappa Phi
                </div>
            </div>
        </div>
    </body>
    </html>
    ';
    
    // Plain text version for email clients that don't support HTML
    $textMessage = "Hey $name,\n\n";
    $textMessage .= "Thanks for signing up! Just need you to verify your email:\n\n";
    $textMessage .= "$verificationLink\n\n";
    $textMessage .= "If you didn't sign up, just ignore this.\n\n";
    $textMessage .= "ΠΚΦ\n";
    $textMessage .= "Pi Kappa Phi";
    
    // Email headers - Multipart for better compatibility
    $boundary = md5(time());
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/alternative; boundary=\"{$boundary}\"\r\n";
    $headers .= "From: Pi Kappa Phi Apparel <noreply@samford.edu>\r\n";
    $headers .= "Reply-To: noreply@samford.edu\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    // Build multipart message
    $message = "--{$boundary}\r\n";
    $message .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $message .= $textMessage . "\r\n\r\n";
    $message .= "--{$boundary}\r\n";
    $message .= "Content-Type: text/html; charset=UTF-8\r\n";
    $message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $message .= $htmlMessage . "\r\n\r\n";
    $message .= "--{$boundary}--";
    
    // Send email
    return mail($email, $subject, $message, $headers);
}

// Create new customer
function createCustomer($name, $email, $password) {
    $customers = getCustomers();
    
    // Check if email already exists
    if (findCustomerByEmail($email)) {
        return ['success' => false, 'error' => 'Email already registered'];
    }
    
    // Validate Samford email
    if (!isSamfordEmail($email)) {
        return ['success' => false, 'error' => 'Only @samford.edu email addresses are allowed'];
    }
    
    $customer = [
        'id' => uniqid('cust_'),
        'name' => $name,
        'email' => $email,
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'verification_token' => generateVerificationToken(),
        'verified' => false,
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    $customers[] = $customer;
    
    if (saveCustomers($customers)) {
        // Send verification email
        sendVerificationEmail($email, $customer['verification_token'], $name);
        return ['success' => true, 'message' => 'Account created! Please check your email to verify your account.'];
    }
    
    return ['success' => false, 'error' => 'Failed to create account'];
}

// Verify customer email
function verifyCustomer($token) {
    $customers = getCustomers();
    $updated = false;
    
    foreach ($customers as &$customer) {
        if ($customer['verification_token'] === $token) {
            $customer['verified'] = true;
            $customer['verification_token'] = null;
            $updated = true;
            break;
        }
    }
    
    if ($updated) {
        saveCustomers($customers);
        return ['success' => true, 'message' => 'Email verified successfully!'];
    }
    
    return ['success' => false, 'error' => 'Invalid or expired verification token'];
}

// Validate customer login
function validateCustomerLogin($email, $password) {
    $customer = findCustomerByEmail($email);
    
    if (!$customer) {
        return ['success' => false, 'error' => 'Invalid email or password'];
    }
    
    if (!$customer['verified']) {
        return ['success' => false, 'error' => 'Please verify your email before logging in'];
    }
    
    if (password_verify($password, $customer['password'])) {
        loginCustomer($customer['id'], $customer['email'], $customer['name']);
        return ['success' => true, 'message' => 'Login successful'];
    }
    
    return ['success' => false, 'error' => 'Invalid email or password'];
}

// Send order confirmation email
function sendOrderConfirmationEmail($customerEmail, $customerName, $order) {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $domain = $_SERVER['HTTP_HOST'];
    $baseUrl = $protocol . '://' . $domain;
    
    $dashboardUrl = $baseUrl . '/customer_dashboard.php';
    
    // Calculate total
    $total = number_format($order['product_price'] * $order['quantity'], 2);
    
    // Format size display
    $sizeDisplay = is_array($order['size']) ? implode(', ', $order['size']) : $order['size'];
    
    $subject = "Order Confirmed - Pi Kappa Phi Apparel";
    
    // HTML Email
    $htmlMessage = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            margin: 0;
            padding: 20px;
            font-family: "Montserrat", "Brandon Grotesque", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #e6eef7 0%, #faf7f0 100%);
        }
        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 48, 135, 0.15);
        }
        .header {
            background: linear-gradient(135deg, #005596 0%, #065189 100%);
            padding: 50px 20px;
            text-align: center;
            border-bottom: 4px solid #E7A614;
            position: relative;
        }
        .header::after {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(231, 166, 20, 0.1), transparent);
            animation: shimmer 3s infinite;
        }
        @keyframes shimmer {
            100% { left: 100%; }
        }
        .greek-letters {
            font-family: "Times New Roman", Symbol, "Lucida Sans Unicode", serif;
            font-size: 56px;
            color: #E7A614;
            font-weight: 400;
            letter-spacing: 12px;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            margin-bottom: 10px;
        }
        .header-title {
            color: #ffffff;
            font-size: 28px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-family: "Montserrat", "Brandon Grotesque", sans-serif;
        }
        .content {
            padding: 50px 40px;
            background: #ffffff;
        }
        .greeting {
            font-size: 24px;
            font-weight: 700;
            color: #005596;
            margin-bottom: 25px;
            font-family: "Montserrat", "Brandon Grotesque", sans-serif;
        }
        .message {
            font-size: 16px;
            line-height: 1.8;
            color: #212121;
            margin-bottom: 25px;
            font-family: "Montserrat", "Brandon Grotesque", sans-serif;
            font-weight: 400;
        }
        .order-box {
            margin-top: 30px;
            padding: 25px;
            background: linear-gradient(135deg, #e6eef7 0%, #faf7f0 100%);
            border-radius: 8px;
            border-left: 4px solid #005596;
            font-size: 13px;
            color: #5a5a5a;
            font-family: "Montserrat", "Brandon Grotesque", sans-serif;
        }
        .order-title {
            font-weight: 700;
            color: #005596;
            margin-bottom: 10px;
            font-size: 14px;
        }
        .order-details {
            font-size: 13px;
            color: #5a5a5a;
            line-height: 1.8;
        }
        .order-details strong {
            color: #005596;
            font-weight: 700;
        }
        .order-row {
            padding: 5px 0;
        }
        .total-row {
            font-size: 14px;
            font-weight: 700;
            color: #005596;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 2px solid #E7A614;
        }
        .button-container {
            text-align: center;
            margin: 40px 0;
        }
        .verify-button {
            display: inline-block;
            padding: 1rem 2.5rem;
            background: #005596;
            color: #ffffff !important;
            text-decoration: none;
            border: 2px solid #005596;
            border-radius: 50px;
            font-weight: 700;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 4px 15px rgba(0, 85, 150, 0.2);
            font-family: "Montserrat", "Brandon Grotesque", sans-serif;
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            position: relative;
            overflow: hidden;
        }
        .verify-button:hover {
            border-color: #E7A614;
            color: #005596;
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 12px 35px rgba(0, 85, 150, 0.3);
            background: #E7A614;
        }
        .divider {
            height: 2px;
            background: linear-gradient(90deg, transparent, #E7A614, transparent);
            margin: 30px 0;
        }
        .footer {
            background: linear-gradient(135deg, #005596 0%, #065189 100%);
            padding: 40px 20px;
            text-align: center;
            color: #ffffff;
            border-top: 3px solid #E7A614;
            position: relative;
        }
        .footer::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(231, 166, 20, 0.1), transparent);
            animation: shimmer 3s infinite;
        }
        .footer-greek {
            font-family: "Times New Roman", Symbol, "Lucida Sans Unicode", serif;
            font-size: 32px;
            color: #E7A614;
            letter-spacing: 6px;
            margin-bottom: 12px;
            font-weight: 400;
        }
        .footer-text {
            font-size: 14px;
            font-weight: 500;
            margin: 8px 0;
            font-family: "Montserrat", "Brandon Grotesque", sans-serif;
            color: rgba(255, 255, 255, 0.95);
        }
        .footer-motto {
            font-style: italic;
            color: #E7A614;
            font-weight: 600;
            margin: 10px 0;
        }
        .footer-copyright {
            margin-top: 20px;
            font-size: 12px;
            opacity: 0.7;
            font-weight: 400;
        }
        @media only screen and (max-width: 600px) {
            .content {
                padding: 30px 20px;
            }
            .greeting {
                font-size: 20px;
            }
            .greek-letters {
                font-size: 40px;
                letter-spacing: 8px;
            }
            .verify-button {
                padding: 15px 35px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="header">
            <div class="greek-letters">ΠΚΦ</div>
            <div class="header-title">Apparel</div>
        </div>
        
        <div class="content">
            <div class="greeting">Hey ' . htmlspecialchars($customerName) . ',</div>
            
            <div class="message">
                Your order has been confirmed and is being processed! We\'ll get it ready for you.
            </div>
            
            <div class="order-box">
                <div class="order-title">Order Details</div>
                <div class="order-details">
                    <div class="order-row"><strong>Product:</strong> ' . htmlspecialchars($order['product_name']) . '</div>
                    <div class="order-row"><strong>Size:</strong> ' . htmlspecialchars($sizeDisplay) . '</div>
                    <div class="order-row"><strong>Quantity:</strong> ' . htmlspecialchars($order['quantity']) . '</div>
                    <div class="order-row"><strong>Price:</strong> $' . htmlspecialchars($order['product_price']) . ' each</div>
                    ' . ($order['notes'] ? '<div class="order-row"><strong>Notes:</strong> ' . htmlspecialchars($order['notes']) . '</div>' : '') . '
                    <div class="order-row"><strong>Order Date:</strong> ' . date('F j, Y', strtotime($order['date'])) . '</div>
                    <div class="total-row">Total: $' . $total . '</div>
                </div>
            </div>
            
            <div class="message">
                You can track your order and manage it from your dashboard.
            </div>
            
            <div class="button-container">
                <a href="' . htmlspecialchars($dashboardUrl) . '" class="verify-button">View My Orders</a>
            </div>
            
            <div class="divider"></div>
            
            <div class="message" style="margin-top: 35px; font-size: 14px; color: #5a5a5a;">
                Questions? Just reply to this email or reach out to us anytime.
            </div>
        </div>
        
        <div class="footer">
            <div class="footer-greek">ΠΚΦ</div>
            <div class="footer-text">Pi Kappa Phi</div>
            <div class="footer-text footer-motto">Leaders among Men</div>
            <div class="footer-text footer-copyright" style="margin-top: 15px;">
                &copy; ' . date('Y') . ' Pi Kappa Phi
            </div>
        </div>
    </div>
</body>
</html>';
    
    // Plain text version
    $textMessage = "Hey $customerName,\n\n";
    $textMessage .= "Your order has been confirmed and is being processed!\n\n";
    $textMessage .= "ORDER DETAILS:\n";
    $textMessage .= "Product: {$order['product_name']}\n";
    $textMessage .= "Size: $sizeDisplay\n";
    $textMessage .= "Quantity: {$order['quantity']}\n";
    $textMessage .= "Price: \${$order['product_price']} each\n";
    if ($order['notes']) {
        $textMessage .= "Notes: {$order['notes']}\n";
    }
    $textMessage .= "Order Date: " . date('F j, Y', strtotime($order['date'])) . "\n";
    $textMessage .= "Total: \$$total\n\n";
    $textMessage .= "View your orders: $dashboardUrl\n\n";
    $textMessage .= "ΠΚΦ\n";
    $textMessage .= "Pi Kappa Phi - Alpha Eta";
    
    // Email headers - Multipart for better compatibility
    $boundary = md5(time() . 'order');
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/alternative; boundary=\"{$boundary}\"\r\n";
    $headers .= "From: Pi Kappa Phi Apparel <noreply@{$domain}>\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    // Build multipart message
    $message = "--{$boundary}\r\n";
    $message .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $message .= $textMessage . "\r\n\r\n";
    $message .= "--{$boundary}\r\n";
    $message .= "Content-Type: text/html; charset=UTF-8\r\n";
    $message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $message .= $htmlMessage . "\r\n\r\n";
    $message .= "--{$boundary}--";
    
    return mail($customerEmail, $subject, $message, $headers);
}

// Password Reset Functions

// Send password reset email
function sendPasswordResetEmail($email) {
    $customers = getCustomers();
    
    // Check if email exists
    $customer = null;
    foreach ($customers as $c) {
        if (strtolower($c['email']) === strtolower($email)) {
            $customer = $c;
            break;
        }
    }
    
    if (!$customer) {
        // For security, don't reveal if email exists or not
        return ['success' => true, 'message' => 'If an account exists with this email, a reset link has been sent.'];
    }
    
    // Check if account is verified
    if (!$customer['verified']) {
        return ['success' => false, 'error' => 'Please verify your email address first before resetting your password.'];
    }
    
    // Generate reset token
    $token = bin2hex(random_bytes(32));
    $expiry = time() + 3600; // 1 hour expiry
    
    // Save reset token
    $resetTokens = getPasswordResetTokens();
    $resetTokens[] = [
        'token' => $token,
        'email' => $email,
        'expiry' => $expiry,
        'used' => false
    ];
    savePasswordResetTokens($resetTokens);
    
    // Send email with proper base URL
    require_once __DIR__ . '/config.php';
    $baseUrl = getBaseUrl();
    $resetLink = $baseUrl . "/reset_password.php?token=" . $token;
    
    $subject = "Reset Your Password - Pi Kappa Phi Apparel";
    
    // Plain text version
    $textMessage = "Hi {$customer['name']},\n\n";
    $textMessage .= "We received a request to reset your password for your Pi Kappa Phi Apparel account.\n\n";
    $textMessage .= "Click the link below to reset your password:\n";
    $textMessage .= $resetLink . "\n\n";
    $textMessage .= "This link will expire in 1 hour.\n\n";
    $textMessage .= "If you didn't request this, please ignore this email.\n\n";
    $textMessage .= "Best regards,\n";
    $textMessage .= "Pi Kappa Phi Apparel Team";
    
    // HTML version
    $htmlMessage = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
    </head>
    <body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f4f4f4; padding: 20px;">
            <tr>
                <td align="center">
                    <table width="600" cellpadding="0" cellspacing="0" border="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                        <!-- Header -->
                        <tr>
                            <td style="background: linear-gradient(135deg, #005596 0%, #E7A614 100%); padding: 40px 20px; text-align: center;">
                                <h1 style="color: #ffffff; margin: 0; font-size: 28px; font-weight: bold;">ΠΚΦ</h1>
                                <p style="color: #ffffff; margin: 10px 0 0 0; font-size: 16px;">Pi Kappa Phi Apparel</p>
                            </td>
                        </tr>
                        
                        <!-- Content -->
                        <tr>
                            <td style="padding: 40px 30px;">
                                <h2 style="color: #333333; margin: 0 0 20px 0; font-size: 24px;">Reset Your Password</h2>
                                <p style="color: #666666; line-height: 1.6; margin: 0 0 20px 0;">Hi ' . htmlspecialchars($customer['name']) . ',</p>
                                <p style="color: #666666; line-height: 1.6; margin: 0 0 20px 0;">We received a request to reset your password for your Pi Kappa Phi Apparel account.</p>
                                <p style="color: #666666; line-height: 1.6; margin: 0 0 30px 0;">Click the button below to reset your password:</p>
                                
                                <!-- Button -->
                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <td align="center" style="padding: 10px 0;">
                                            <a href="' . $resetLink . '" style="background-color: #005596; color: #ffffff; padding: 15px 40px; text-decoration: none; border-radius: 50px; display: inline-block; font-weight: bold; font-size: 16px;">Reset Password</a>
                                        </td>
                                    </tr>
                                </table>
                                
                                <p style="color: #999999; font-size: 14px; line-height: 1.6; margin: 30px 0 0 0;">This link will expire in 1 hour.</p>
                                <p style="color: #999999; font-size: 14px; line-height: 1.6; margin: 10px 0 0 0;">If you didn\'t request this, please ignore this email.</p>
                            </td>
                        </tr>
                        
                        <!-- Footer -->
                        <tr>
                            <td style="background-color: #f8f9fa; padding: 20px 30px; text-align: center; border-top: 1px solid #e0e0e0;">
                                <p style="color: #999999; margin: 0; font-size: 12px;">© ' . date('Y') . ' Pi Kappa Phi. All rights reserved.</p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
    </html>';
    
    // Create unique boundary
    $boundary = md5(time() . 'reset');
    
    $headers = "From: Pi Kappa Phi Apparel <noreply@pikappapparel.com>\r\n";
    $headers .= "Reply-To: noreply@pikappapparel.com\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/alternative; boundary=\"{$boundary}\"\r\n";
    
    // Build multipart message
    $message = "--{$boundary}\r\n";
    $message .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $message .= $textMessage . "\r\n\r\n";
    $message .= "--{$boundary}\r\n";
    $message .= "Content-Type: text/html; charset=UTF-8\r\n";
    $message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $message .= $htmlMessage . "\r\n\r\n";
    $message .= "--{$boundary}--";
    
    if (mail($email, $subject, $message, $headers)) {
        return ['success' => true, 'message' => 'Password reset link has been sent to your email.'];
    } else {
        return ['success' => false, 'error' => 'Failed to send reset email. Please try again.'];
    }
}

// Validate password reset token
function validatePasswordResetToken($token) {
    $resetTokens = getPasswordResetTokens();
    
    foreach ($resetTokens as $resetToken) {
        if ($resetToken['token'] === $token) {
            if ($resetToken['used']) {
                return ['valid' => false, 'error' => 'This reset link has already been used.'];
            }
            if ($resetToken['expiry'] < time()) {
                return ['valid' => false, 'error' => 'This reset link has expired. Please request a new one.'];
            }
            return ['valid' => true, 'email' => $resetToken['email']];
        }
    }
    
    return ['valid' => false, 'error' => 'Invalid reset link.'];
}

// Reset password
function resetPassword($token, $newPassword) {
    // Validate token
    $tokenData = validatePasswordResetToken($token);
    if (!$tokenData['valid']) {
        return ['success' => false, 'error' => $tokenData['error']];
    }
    
    $email = $tokenData['email'];
    
    // Update password
    $customers = getCustomers();
    $updated = false;
    
    for ($i = 0; $i < count($customers); $i++) {
        if (strtolower($customers[$i]['email']) === strtolower($email)) {
            $customers[$i]['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
            $updated = true;
            break;
        }
    }
    
    if (!$updated) {
        return ['success' => false, 'error' => 'Account not found.'];
    }
    
    // Save updated customers
    if (!saveCustomers($customers)) {
        return ['success' => false, 'error' => 'Failed to update password.'];
    }
    
    // Mark token as used
    $resetTokens = getPasswordResetTokens();
    for ($i = 0; $i < count($resetTokens); $i++) {
        if ($resetTokens[$i]['token'] === $token) {
            $resetTokens[$i]['used'] = true;
            break;
        }
    }
    savePasswordResetTokens($resetTokens);
    
    return ['success' => true, 'message' => 'Password has been reset successfully!'];
}

// Get password reset tokens
function getPasswordResetTokens() {
    $file = 'data/password_resets.json';
    if (file_exists($file)) {
        $json = file_get_contents($file);
        $tokens = json_decode($json, true);
        return is_array($tokens) ? $tokens : [];
    }
    return [];
}

// Save password reset tokens
function savePasswordResetTokens($tokens) {
    $file = 'data/password_resets.json';
    if (!file_exists('data')) {
        mkdir('data', 0755, true);
    }
    return file_put_contents($file, json_encode($tokens, JSON_PRETTY_PRINT)) !== false;
}
