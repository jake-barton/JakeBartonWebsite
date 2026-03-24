# Customer Account System

## Overview
The Pi Kappa Phi Apparel website now includes a customer account system that requires users to register and verify their Samford University email address before placing orders.

## Features

### 1. **Registration** (`customer_register.php`)
- Only `@samford.edu` email addresses are accepted
- Requires: Full Name, Samford Email, Password (min 6 characters)
- Sends styled HTML verification email automatically
- Email uses Pi Kappa Phi branding (royal blue, gold, Greek letters)

### 2. **Email Verification** (`verify_customer.php`)
- Customers must click the verification link in their email
- Token-based verification system
- One-time use tokens

### 3. **Login** (`customer_login.php`)
- Requires verified email address
- Secure password authentication (passwords are hashed)
- Session-based authentication

### 4. **Customer Dashboard** (`customer_dashboard.php`)
- View all orders placed by the customer
- See order statistics (total orders, total items)
- Cancel orders directly from dashboard

### 5. **Order Process** (updated)
- Customers must be logged in to place orders
- Customer name and email automatically populated from account
- Orders are linked to customer accounts via `customer_id`
- Supports multi-size ordering

## File Structure

```
├── customer_auth.php           # Customer authentication library
├── customer_register.php       # Registration page
├── customer_login.php          # Login page
├── customer_dashboard.php      # Customer order dashboard
├── customer_logout.php         # Logout handler
├── verify_customer.php         # Email verification handler
├── data/
│   └── customers.json         # Customer accounts
```

## Security Features

1. **Email Domain Restriction**: Only `@samford.edu` emails allowed
2. **Password Hashing**: Uses PHP's `password_hash()` with bcrypt
3. **Email Verification**: Prevents fake accounts
4. **Session-Based Auth**: Secure session management
5. **Order Ownership**: Customers can only cancel their own orders

## Testing the System

### Local Development
The system uses PHP's built-in `mail()` function. For local testing, you have a few options:

**Option 1: Use a local mail server (recommended for testing)**
```bash
# macOS - use built-in postfix
sudo postfix start
```

**Option 2: Use MailHog for testing (catches all emails)**
```bash
# Install MailHog
brew install mailhog
# Run MailHog
mailhog
# View emails at http://localhost:8025
```

**Option 3: Test with real emails**
- Configure PHP's mail settings in `php.ini`
- Or update the `sendVerificationEmail()` function to use SMTP

### Testing Steps
1. Go to `http://localhost:8080/customer_register.php`
2. Register with a `@samford.edu` email
3. Check your email for the styled verification message
4. Click the verification button in the email
5. Login at `http://localhost:8080/customer_login.php`
6. Place orders from the home page
7. View/cancel orders in "My Orders"

## Production Deployment

### Email Configuration

The system is already configured to send emails using PHP's `mail()` function with styled HTML templates. 

**For production on a server with mail configured:**
- No changes needed - emails will be sent automatically
- Update the domain in `customer_auth.php` from `localhost:8080` to your production domain

**For production with SMTP (recommended for reliability):**

1. **Install PHPMailer**:
   ```bash
   composer require phpmailer/phpmailer
   ```

2. **Update `customer_auth.php`** - Replace the `sendVerificationEmail()` function:
   ```php
   use PHPMailer\PHPMailer\PHPMailer;
   
   function sendVerificationEmail($email, $token, $name) {
       $mail = new PHPMailer(true);
       // Configure SMTP settings
       $mail->isSMTP();
       $mail->Host = 'smtp.samford.edu'; // Use Samford's SMTP
       $mail->SMTPAuth = true;
       $mail->Username = 'your-email@samford.edu';
       $mail->Password = 'your-password';
       $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
       $mail->Port = 587;
       
       // Email content
       $mail->setFrom('noreply@samford.edu', 'Pi Kappa Phi Apparel');
       $mail->addAddress($email, $name);
       $mail->Subject = 'Verify Your Pi Kappa Phi Apparel Account';
       
       $verificationLink = "https://yourdomain.com/verify_customer.php?token=" . urlencode($token);
       $mail->Body = "Hi $name,\n\nPlease verify your email: $verificationLink";
       
       return $mail->send();
   }
   ```

## Admin vs Customer

- **Admin Panel**: Accessible via `admin.php` with PIN 1904
  - Manage products
  - View all orders for each product
  - Cancel any order
  
- **Customer Dashboard**: Accessible after login
  - View only their own orders
  - Cancel only their own orders
  - Place new orders

## Data Structure

### Customer Object (`data/customers.json`)
```json
{
    "id": "cust_abc123",
    "name": "John Doe",
    "email": "jdoe@samford.edu",
    "password": "$2y$10$hashed_password",
    "verification_token": "token_or_null",
    "verified": true,
    "created_at": "2025-12-08 10:30:00"
}
```

### Order Object (updated `data/orders.json`)
```json
{
    "id": "unique_id",
    "product_id": "product_id",
    "product_name": "Product Name",
    "product_price": "25.00",
    "customer_id": "cust_abc123",
    "customer_name": "John Doe",
    "customer_email": "jdoe@samford.edu",
    "size": "L",
    "quantity": 2,
    "notes": "Optional notes",
    "date": "2025-12-08 10:35:00",
    "status": "pending"
}
```

## Navigation Updates

The main navigation now shows:
- **Logged Out**: Home | Shop | Login | Sign Up | Admin
- **Logged In**: Home | Shop | My Orders | Logout (Name) | Admin
