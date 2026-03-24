# Quick Action Checklist - Security & UX Fixes

## ✅ COMPLETED (Ready to Use)

### Core Security Infrastructure
- [x] `/includes/csrf.php` - CSRF protection system
- [x] `/includes/security.php` - Security utilities  
- [x] `auth.php` - Secure sessions
- [x] `customer_auth.php` - Secure sessions + HTTPS links

### Order System Security
- [x] `process_order.php` - Server-side price validation + CSRF
- [x] `index.php` - CSRF meta tag
- [x] `script.js` - CSRF token in AJAX requests

### XSS Protection
- [x] `manage_customers.php` - HTML escaping in JavaScript

### Mobile Responsiveness
- [x] `styles.css` - Complete mobile CSS (480px, 768px, 1024px breakpoints)

## ⚠️ TODO - High Priority

### 1. Add CSRF to Admin Actions (30-60 minutes)

**manage_access.php:**
```php
// Line 2-3: Add after require_once 'auth.php';
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/security.php';

// Line ~18: Add after if ($_SERVER['REQUEST_METHOD'] === 'POST') {
requireCSRFToken();

// Line ~72: Add in <head> after <title>
<?php csrfMetaTag(); ?>

// Lines with <form method="POST">: Add inside each form
<?php csrfField(); ?>

// Replace htmlspecialchars() with e() function
```

**delete_customer.php:**
```php
// Line 2: Add
require_once __DIR__ . '/includes/csrf.php';

// Line ~8-9: Change from GET to POST + add validation
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: manage_customers.php');
    exit;
}
requireCSRFToken();
$customerId = $_POST['id'] ?? '';

// In manage_customers.php, change delete button to form:
<form method="POST" action="delete_customer.php" style="display:inline;">
    <?php csrfField(); ?>
    <input type="hidden" name="id" value="<?php echo e($customer['id']); ?>">
    <button type="submit" class="delete-btn" onclick="return confirm('Delete?')">Delete</button>
</form>
```

**cancel_order.php:**
```php
// Line 2: Add
require_once __DIR__ . '/includes/csrf.php';

// Line ~15: Add after header('Content-Type: application/json');
requireCSRFToken();

// In calling JavaScript, add CSRF token to request:
fetch('cancel_order.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify({order_id: orderId})
})
```

**save_product.php:**
```php
// Line 2: Add
require_once __DIR__ . '/includes/csrf.php';

// Line ~16: Add after if ($_SERVER['REQUEST_METHOD'] === 'POST') {
requireCSRFToken();

// In admin.php product form, add:
<?php csrfField(); ?>
```

**delete_product.php:**
```php
// Line 2: Add
require_once __DIR__ . '/includes/csrf.php';

// Line ~16: Change from GET to POST + validate
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: admin.php');
    exit;
}
requireCSRFToken();
$productId = $_POST['id'] ?? '';

// In admin.php, change delete link to form:
<form method="POST" action="delete_product.php" style="display:inline;">
    <?php csrfField(); ?>
    <input type="hidden" name="id" value="<?php echo e($product['id']); ?>">
    <button type="submit" onclick="return confirm('Delete?')">Delete</button>
</form>
```

**login.php (Admin):**
```php
// Line 2: Add
require_once __DIR__ . '/includes/csrf.php';

// Line ~30: Add in each form section for both login AND create
<?php csrfField(); ?>

// In POST handlers, add:
requireCSRFToken();
```

**customer_login.php:**
```php
// Line 2: Add
require_once __DIR__ . '/includes/csrf.php';

// Add in form:
<?php csrfField(); ?>

// In POST handler:
requireCSRFToken();
```

**customer_register.php:**
```php
// Line 2: Add
require_once __DIR__ . '/includes/csrf.php';

// Add in form:
<?php csrfField(); ?>

// In POST handler:
requireCSRFToken();
```

### 2. Add File Locking to Remaining Files (20-30 minutes)

**Pattern to Follow:**
```php
// OLD:
$data = json_decode(file_get_contents($file), true);
file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));

// NEW:
$data = safeReadJSON($file);
safeWriteJSON($file, $data);
```

**Files to Update:**
- `save_product.php` (~line 59)
- `manage_access.php` (~lines 27, 47, 57)
- `delete_customer.php` (~line 27)
- `delete_product.php` (~line 30)
- `auth.php` - `saveAdmin()` function
- `customer_auth.php` - All customer save functions

### 3. Standardize Page Titles (10 minutes)

**Pattern:** `<title>[Page Name] - Pi Kappa Phi Apparel</title>`

**Files:**
- `admin.php` - "Admin Panel - Pi Kappa Phi Apparel"
- `customer_login.php` - "Login - Pi Kappa Phi Apparel"
- `customer_register.php` - "Sign Up - Pi Kappa Phi Apparel"
- `login.php` - "Admin Login - Pi Kappa Phi Apparel"
- `manage_customers.php` - "Manage Customers - Pi Kappa Phi Apparel"
- `orders.php` - "All Orders - Pi Kappa Phi Apparel"
- `product_orders.php` - "Product Orders - Pi Kappa Phi Apparel"

**Logo Consistency:**
Change all instances to: `<span class="greek-letters">ΠΚΦ</span> | Apparel`
(Note the space before the pipe)

## 📋 Testing After Changes

### Test CSRF Protection:
1. Try submitting any form without the CSRF token → Should fail with 403
2. Try submitting with invalid token → Should fail with 403
3. Try submitting normally → Should work

### Test Order System:
1. Try to order with manipulated price (dev tools) → Should use database price
2. Try to order with invalid size → Should fail
3. Normal order → Should work

### Test Mobile:
1. Open site on iPhone/Android
2. Check navigation works
3. Check products display correctly
4. Try placing an order
5. Check admin panel on tablet

### Test All Forms:
1. Admin login/create
2. Customer login/register
3. Product create/edit/delete
4. Customer delete
5. Order cancel
6. Access management

## 🎯 Time Estimate

- CSRF Protection: 30-60 minutes
- File Locking: 20-30 minutes
- Title Standardization: 10 minutes
- Testing: 30-45 minutes

**Total: 1.5 - 2.5 hours**

## 📝 Notes

- All security functions are ready to use
- Mobile CSS is complete
- Core vulnerabilities (price manipulation, XSS) are fixed
- Remaining work is applying existing patterns to additional files
- Template file available: `manage_access_secure.php`

## 🚨 Priority Order

1. **CSRF on delete actions** (delete_customer.php, delete_product.php) - Prevents unauthorized deletions
2. **CSRF on login forms** (login.php, customer_login.php, customer_register.php) - Prevents account takeover
3. **CSRF on data modification** (save_product.php, manage_access.php, cancel_order.php)
4. **File locking** - Prevents data corruption
5. **Title standardization** - UX polish

## ✨ Quick Win

If short on time, prioritize:
1. delete_customer.php + delete_product.php (CSRF) - 15 minutes
2. login.php + customer_login.php (CSRF) - 15 minutes
3. Test deletions and logins - 10 minutes

This covers the highest-risk actions in 40 minutes!
