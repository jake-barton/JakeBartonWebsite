// Smooth scrolling for navigation links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Order Modal Functions
function openOrderModal(product) {
    const modal = document.getElementById('orderModal');
    document.getElementById('productId').value = product.id;
    document.getElementById('productName').value = product.name;
    document.getElementById('productPrice').value = product.price;
    document.getElementById('modalProductName').textContent = product.name + ' - $' + product.price;
    
    modal.classList.add('active');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeOrderModal() {
    const modal = document.getElementById('orderModal');
    modal.classList.remove('active');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
    document.getElementById('orderForm').reset();
}

// Close modal when clicking outside
document.getElementById('orderModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeOrderModal();
    }
});

// Handle order form submission
document.getElementById('orderForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    // Collect selected sizes and quantities
    const selectedSizes = [];
    document.querySelectorAll('.size-checkbox:checked').forEach(checkbox => {
        const size = checkbox.dataset.size;
        const quantity = parseInt(document.querySelector(`.size-quantity[data-size="${size}"]`).value);
        selectedSizes.push({ size, quantity });
    });
    
    if (selectedSizes.length === 0) {
        showPopup('Please select at least one size.', 'error');
        return;
    }
    
    // Submit each size/quantity combination as a separate order
    let allSuccess = true;
    let needsLogin = false;
    
    for (const item of selectedSizes) {
        const orderData = new FormData();
        orderData.append('product_id', formData.get('product_id'));
        orderData.append('product_name', formData.get('product_name'));
        orderData.append('product_price', formData.get('product_price'));
        orderData.append('size', item.size);
        orderData.append('quantity', item.quantity);
        orderData.append('notes', formData.get('notes'));
        
        // Add CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (csrfToken) {
            orderData.append('csrf_token', csrfToken);
        }
        
        try {
            const response = await fetch('process_order.php', {
                method: 'POST',
                body: orderData
            });
            
            const result = await response.json();
            if (!result.success) {
                if (result.redirect) {
                    // User needs to login - save order state
                    needsLogin = true;
                    saveOrderState(formData, selectedSizes);
                    break;
                }
                allSuccess = false;
                console.error('Order failed for', item.size);
            }
        } catch (error) {
            console.error('Error:', error);
            allSuccess = false;
        }
    }
    
    if (needsLogin) {
        showLoginPrompt();
    } else if (allSuccess) {
        showPopup('Order placed successfully!<br>View your orders in "My Orders".', 'success');
        closeOrderModal();
        this.reset();
        document.querySelectorAll('.size-quantity').forEach(input => input.disabled = true);
        clearOrderState();
    } else {
        showPopup('Some orders failed. Please try again.', 'error');
    }
});

// Enable/disable quantity inputs based on checkbox
document.querySelectorAll('.size-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const size = this.dataset.size;
        const quantityInput = document.querySelector(`.size-quantity[data-size="${size}"]`);
        quantityInput.disabled = !this.checked;
        if (!this.checked) {
            quantityInput.value = 1;
        }
    });
});


// Navbar scroll effect
let lastScroll = 0;
const navbar = document.querySelector('.navbar');

window.addEventListener('scroll', () => {
    const currentScroll = window.pageYOffset;
    
    if (currentScroll > lastScroll && currentScroll > 100) {
        navbar.style.transform = 'translateY(-100%)';
    } else {
        navbar.style.transform = 'translateY(0)';
    }
    
    lastScroll = currentScroll;
});

// Add active class to nav links on scroll
const sections = document.querySelectorAll('section[id]');
const navLinks = document.querySelectorAll('.nav-link');

window.addEventListener('scroll', () => {
    let current = '';
    
    sections.forEach(section => {
        const sectionTop = section.offsetTop;
        const sectionHeight = section.clientHeight;
        if (pageYOffset >= sectionTop - 200) {
            current = section.getAttribute('id');
        }
    });
    
    navLinks.forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href') === '#' + current) {
            link.classList.add('active');
        }
    });
});

// Custom Popup Functions
function showPopup(message, type = 'success') {
    // Remove any existing popup
    const existingPopup = document.querySelector('.custom-popup');
    if (existingPopup) {
        existingPopup.classList.remove('show');
        setTimeout(() => existingPopup.remove(), 500);
    }
    
    const popup = document.createElement('div');
    popup.className = `custom-popup ${type}`;
    popup.innerHTML = message;
    document.body.appendChild(popup);
    
    // Trigger animation
    setTimeout(() => popup.classList.add('show'), 10);
    
    // Auto-remove after 4 seconds
    setTimeout(() => {
        popup.classList.remove('show');
        setTimeout(() => popup.remove(), 500);
    }, 4000);
}

function showLoginPrompt() {
    // Remove any existing popup
    const existingPopup = document.querySelector('.custom-popup');
    if (existingPopup) {
        existingPopup.classList.remove('show');
        setTimeout(() => existingPopup.remove(), 500);
    }
    
    const popup = document.createElement('div');
    popup.className = 'custom-popup login-prompt';
    popup.innerHTML = `
        <h3>Sign in to continue</h3>
        <p>Please log in or create an account to place your order.</p>
        <div class="popup-buttons">
            <button onclick="redirectToLogin()" class="popup-btn login-btn">Log In</button>
            <button onclick="redirectToSignup()" class="popup-btn signup-btn">Sign Up</button>
        </div>
    `;
    document.body.appendChild(popup);
    
    // Trigger animation
    setTimeout(() => popup.classList.add('show'), 10);
}

function redirectToLogin() {
    window.location.href = 'customer_login.php?return=order';
}

function redirectToSignup() {
    window.location.href = 'customer_register.php?return=order';
}

// Order State Management (localStorage)
function saveOrderState(formData, selectedSizes) {
    const orderState = {
        productId: formData.get('product_id'),
        productName: formData.get('product_name'),
        productPrice: formData.get('product_price'),
        sizes: selectedSizes,
        notes: formData.get('notes'),
        timestamp: Date.now()
    };
    localStorage.setItem('pendingOrder', JSON.stringify(orderState));
}

function clearOrderState() {
    localStorage.removeItem('pendingOrder');
}

function restoreOrderState() {
    const savedState = localStorage.getItem('pendingOrder');
    if (!savedState) return;
    
    try {
        const orderState = JSON.parse(savedState);
        
        // Check if order is less than 1 hour old
        const oneHour = 60 * 60 * 1000;
        if (Date.now() - orderState.timestamp > oneHour) {
            clearOrderState();
            return;
        }
        
        // Find the product and open modal
        const products = document.querySelectorAll('.product-card');
        for (const card of products) {
            const orderBtn = card.querySelector('.order-button');
            if (orderBtn) {
                const onclickAttr = orderBtn.getAttribute('onclick');
                if (onclickAttr && onclickAttr.includes(orderState.productId)) {
                    // Trigger the order button
                    orderBtn.click();
                    
                    // Wait for modal to open, then restore selections
                    setTimeout(() => {
                        // Restore notes
                        const notesField = document.getElementById('notes');
                        if (notesField && orderState.notes) {
                            notesField.value = orderState.notes;
                        }
                        
                        // Restore size selections
                        orderState.sizes.forEach(item => {
                            const checkbox = document.querySelector(`.size-checkbox[data-size="${item.size}"]`);
                            const quantityInput = document.querySelector(`.size-quantity[data-size="${item.size}"]`);
                            if (checkbox && quantityInput) {
                                checkbox.checked = true;
                                quantityInput.disabled = false;
                                quantityInput.value = item.quantity;
                            }
                        });
                        
                        // Show notification
                        showPopup('Your order has been restored! Please review and submit.', 'success');
                    }, 500);
                    
                    break;
                }
            }
        }
        
        clearOrderState();
    } catch (error) {
        console.error('Error restoring order:', error);
        clearOrderState();
    }
}

// Check for pending order on page load
if (window.location.pathname.includes('index.php') || window.location.pathname === '/') {
    window.addEventListener('load', () => {
        // Check if we're returning from login/signup
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('from') === 'auth') {
            setTimeout(restoreOrderState, 1000);
        }
    });
}
