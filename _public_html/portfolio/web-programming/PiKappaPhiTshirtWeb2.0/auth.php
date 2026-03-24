<?php
// Include security utilities
require_once __DIR__ . '/includes/security.php';

// Start secure session
initSecureSession();

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

// Check if user is authorized (owner)
function isOwner() {
    return isset($_SESSION['is_owner']) && $_SESSION['is_owner'] === true;
}

// Get authorized emails
function getAuthorizedEmails() {
    $file = 'data/authorized_emails.json';
    if (file_exists($file)) {
        $json = file_get_contents($file);
        return json_decode($json, true);
    }
    return [];
}

// Check if email is authorized
function isEmailAuthorized($email) {
    $authorizedEmails = getAuthorizedEmails();
    return in_array(strtolower($email), array_map('strtolower', $authorizedEmails));
}

// Get admin PIN
function getAdminPin() {
    $file = 'data/config.json';
    if (file_exists($file)) {
        $json = file_get_contents($file);
        $config = json_decode($json, true);
        return $config['admin_pin'] ?? '1904';
    }
    return '1904';
}

// Get all admins
function getAdmins() {
    $file = 'data/admins.json';
    if (file_exists($file)) {
        $json = file_get_contents($file);
        return json_decode($json, true);
    }
    return [];
}

// Save admin
function saveAdmin($email, $name) {
    $admins = getAdmins();
    
    // Check if email already exists
    foreach ($admins as $admin) {
        if (strtolower($admin['email']) === strtolower($email)) {
            return false; // Email already registered
        }
    }
    
    $newAdmin = [
        'id' => uniqid(),
        'email' => $email,
        'name' => $name,
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    $admins[] = $newAdmin;
    
    $file = 'data/admins.json';
    return file_put_contents($file, json_encode($admins, JSON_PRETTY_PRINT));
}

// Login admin
function loginAdmin($email, $pin) {
    // Check PIN first
    if ($pin !== getAdminPin()) {
        return ['success' => false, 'message' => 'Incorrect PIN.'];
    }
    
    // Check if email is authorized
    if (!isEmailAuthorized($email)) {
        return ['success' => false, 'message' => 'Email not authorized. Contact site owner.'];
    }
    
    // Check if admin exists
    $admins = getAdmins();
    $adminExists = false;
    $adminName = '';
    $adminId = '';
    
    foreach ($admins as $admin) {
        if (strtolower($admin['email']) === strtolower($email)) {
            $adminExists = true;
            $adminName = $admin['name'];
            $adminId = $admin['id'];
            break;
        }
    }
    
    if (!$adminExists) {
        return ['success' => false, 'message' => 'Account not found. Please create an account first.'];
    }
    
    // Regenerate session ID to prevent fixation
    regenerateSession();
    
    // Set session
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_email'] = $email;
    $_SESSION['admin_name'] = $adminName;
    $_SESSION['admin_id'] = $adminId;
    $authorizedEmails = getAuthorizedEmails();
    $_SESSION['is_owner'] = !empty($authorizedEmails) && (strtolower($email) === strtolower($authorizedEmails[0]));
    
    return ['success' => true, 'message' => 'Login successful!'];
}

// Logout admin
function logoutAdmin() {
    session_destroy();
}

// Redirect if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

// Redirect if not owner
function requireOwner() {
    if (!isOwner()) {
        header('Location: admin.php');
        exit;
    }
}
?>
