<?php
/**
 * Contact Form Handler - Enhanced Security
 * Works on all platforms with CSRF protection and rate limiting
 */

require_once 'includes/config.php';

header('Content-Type: application/json');

// Check if form was submitted via POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        'success' => false,
        'errors' => ['Invalid request method']
    ]);
    exit;
}

// Verify CSRF Token — token is required; empty or missing = reject
$csrf_token = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
if (empty($csrf_token) || !verify_csrf_token($csrf_token)) {
    echo json_encode([
        'success' => false,
        'errors' => ['Security validation failed. Please refresh and try again.']
    ]);
    exit;
}

// Check Rate Limiting (increased to 10 per hour to allow more submissions)
if (!check_rate_limit('contact_form', 10)) {
    echo json_encode([
        'success' => false,
        'errors' => ['Too many requests. Please try again in a few minutes.']
    ]);
    exit;
}

// Get and sanitize form data
$name = isset($_POST['name']) ? sanitize_input($_POST['name']) : '';
$email = isset($_POST['email']) ? sanitize_input($_POST['email']) : '';
$message = isset($_POST['message']) ? sanitize_input($_POST['message']) : '';
$honeypot = isset($_POST['website']) ? $_POST['website'] : ''; // Spam trap

// Check honeypot (if filled, it's a bot)
if (!empty($honeypot)) {
    echo json_encode([
        'success' => true,
        'message' => 'Thank you! Your message has been received.'
    ]);
    exit;
}

// Validation
$errors = [];

if (empty($name) || strlen($name) < 2) {
    $errors[] = "Name must be at least 2 characters";
}

if (empty($email)) {
    $errors[] = "Email is required";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format";
}

if (empty($message) || strlen($message) < 10) {
    $errors[] = "Message must be at least 10 characters";
}

// Check for suspicious content
if (preg_match('/<script|javascript:|onclick=/i', $name . $email . $message)) {
    $errors[] = "Invalid content detected";
}

// If there are errors, return them
if (!empty($errors)) {
    echo json_encode([
        'success' => false,
        'errors' => $errors
    ]);
    exit;
}

// Prepare email
$to = CONTACT_EMAIL;
$subject = "Website Contact Form - Message from " . $name;

// Create email body
$email_body = "You have received a new message from your website contact form.\n\n";
$email_body .= "Name: " . $name . "\n";
$email_body .= "Email: " . $email . "\n";
$email_body .= "Date: " . date('Y-m-d H:i:s') . "\n";
$email_body .= "IP Address: " . $_SERVER['REMOTE_ADDR'] . "\n\n";
$email_body .= "Message:\n" . $message . "\n";

// Headers
$headers = "From: " . $email . "\r\n";
$headers .= "Reply-To: " . $email . "\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// Send email
$mail_sent = @mail($to, $subject, $email_body, $headers);

// Always return success to prevent info leakage
echo json_encode([
    'success' => true,
    'message' => 'Thank you! Your message has been sent successfully. I will get back to you soon!'
]);
?>
