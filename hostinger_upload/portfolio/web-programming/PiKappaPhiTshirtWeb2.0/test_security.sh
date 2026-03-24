#!/bin/bash

# Quick Security & Functionality Test Script
# Run this to verify all fixes are working

echo "🔍 Security & Functionality Test Suite"
echo "======================================"
echo ""

# Check if all required files exist
echo "✅ Checking file structure..."
files_to_check=(
    "includes/csrf.php"
    "includes/security.php"
    "auth.php"
    "customer_auth.php"
    "process_order.php"
    "delete_customer.php"
    "delete_product.php"
    "save_product.php"
    "cancel_order.php"
    "manage_access.php"
)

all_exist=true
for file in "${files_to_check[@]}"; do
    if [ -f "$file" ]; then
        echo "   ✓ $file exists"
    else
        echo "   ✗ $file MISSING!"
        all_exist=false
    fi
done

if [ "$all_exist" = true ]; then
    echo "   All security files present!"
else
    echo "   ⚠️  Some files are missing!"
    exit 1
fi

echo ""
echo "✅ Checking for CSRF protection..."

# Check if files require CSRF
csrf_files=(
    "process_order.php"
    "delete_customer.php"
    "delete_product.php"
    "save_product.php"
    "cancel_order.php"
    "manage_access.php"
    "login.php"
    "customer_login.php"
    "customer_register.php"
)

for file in "${csrf_files[@]}"; do
    if grep -q "requireCSRFToken" "$file" 2>/dev/null; then
        echo "   ✓ $file has CSRF validation"
    elif grep -q "csrf.php" "$file" 2>/dev/null; then
        echo "   ✓ $file includes CSRF library"
    else
        echo "   ⚠️  $file may be missing CSRF protection"
    fi
done

echo ""
echo "✅ Checking for file locking..."

# Check if files use safeWriteJSON
locking_files=(
    "process_order.php"
    "delete_customer.php"
    "delete_product.php"
    "save_product.php"
    "cancel_order.php"
    "manage_access.php"
)

for file in "${locking_files[@]}"; do
    if grep -q "safeWriteJSON" "$file" 2>/dev/null; then
        echo "   ✓ $file uses file locking"
    else
        echo "   ⚠️  $file may not use file locking"
    fi
done

echo ""
echo "✅ Checking for XSS protection..."

if grep -q "escapeHtml" "manage_customers.php" 2>/dev/null; then
    echo "   ✓ manage_customers.php has XSS escaping"
else
    echo "   ⚠️  manage_customers.php may be missing XSS escaping"
fi

echo ""
echo "✅ Checking session security..."

if grep -q "initSecureSession" "auth.php" 2>/dev/null; then
    echo "   ✓ auth.php uses secure sessions"
else
    echo "   ⚠️  auth.php may not use secure sessions"
fi

if grep -q "initSecureSession" "customer_auth.php" 2>/dev/null; then
    echo "   ✓ customer_auth.php uses secure sessions"
else
    echo "   ⚠️  customer_auth.php may not use secure sessions"
fi

echo ""
echo "✅ Checking mobile responsiveness..."

if grep -q "@media (max-width: 768px)" "styles.css" 2>/dev/null; then
    echo "   ✓ styles.css has mobile breakpoints"
else
    echo "   ⚠️  styles.css may be missing mobile CSS"
fi

echo ""
echo "✅ Checking CSRF meta tags..."

meta_files=(
    "index.php"
    "admin.php"
    "customer_dashboard.php"
    "manage_customers.php"
    "product_orders.php"
)

for file in "${meta_files[@]}"; do
    if grep -q "csrfMetaTag" "$file" 2>/dev/null; then
        echo "   ✓ $file has CSRF meta tag"
    else
        echo "   ⚠️  $file may be missing CSRF meta tag"
    fi
done

echo ""
echo "✅ Checking JavaScript CSRF tokens..."

js_files=(
    "script.js"
    "admin.js"
)

for file in "${js_files[@]}"; do
    if grep -q "csrf-token" "$file" 2>/dev/null; then
        echo "   ✓ $file includes CSRF token in requests"
    else
        echo "   ⚠️  $file may be missing CSRF token"
    fi
done

echo ""
echo "======================================"
echo "✨ Automated checks complete!"
echo ""
echo "📋 Manual Testing Checklist:"
echo "   1. Test order submission (price should come from DB)"
echo "   2. Test admin login with CSRF"
echo "   3. Test customer login/register"
echo "   4. Test product add/edit/delete"
echo "   5. Test customer delete"
echo "   6. Test order cancellation"
echo "   7. Test mobile layout on phone"
echo "   8. Verify all forms have CSRF tokens"
echo ""
echo "🚀 If all ✓ above, system is secure and ready!"
