# Pi Kappa Phi T-Shirt Shop

A modern, minimalist t-shirt ordering website built with PHP, featuring Pi Kappa Phi's official royal blue and gold colors with smooth animations throughout.

## 🎨 Official Brand Colors

- **Royal Blue**: #003087 (Primary)
- **Gold**: #D4AF37 (Secondary)
- **Typography**: Brandon Grotesque (Bold & Regular)

## 🚀 How to Launch from VS Code

### Method 1: Using Tasks (Recommended)
1. Press `Cmd+Shift+P` (Command Palette)
2. Type "Tasks: Run Task"
3. Select **"Launch Site"**

This will:
- Stop any existing PHP server
- Start a fresh PHP server on port 8080
- Open the website in your default browser

### Method 2: Using Terminal in VS Code
1. Open Terminal in VS Code (`Ctrl+~` or Terminal menu)
2. Run: `php -S localhost:8080`
3. Open browser to: http://localhost:8080/index.php

### Method 3: Using the Launch Script
From the terminal:
```bash
./launch.sh
```

## 📦 Quick Commands

**Start Server:**
```bash
php -S localhost:8080
```

**Open Main Site:**
```bash
open http://localhost:8080/index.php
```

**Open Admin Panel:**
```bash
open http://localhost:8080/admin.php
```

## 💡 Features

- **Modern Design**: Clean white background with royal blue and gold accents
- **Smooth Animations**: Fade-ins, slide-ups, hover effects throughout
- **Easy Product Management**: Admin panel to add, edit, and delete products
- **Order System**: Customer-facing order form with size selection
- **Responsive Design**: Works perfectly on all devices
- **No Database Required**: Uses JSON files for simple data storage
- **Cache-Busting**: Built-in headers prevent caching issues

## 📁 File Structure

```
PiKappaPhiTshirtWeb/
├── index.php              # Main storefront page
├── admin.php              # Admin panel for product management
├── styles.css             # All styling with Pi Kappa Phi brand colors
├── script.js              # Frontend JavaScript for main site
├── admin.js               # Frontend JavaScript for admin panel
├── save_product.php       # API endpoint to save products
├── delete_product.php     # API endpoint to delete products
├── process_order.php      # API endpoint to process orders
├── launch.sh              # Quick launch script
├── data/
│   ├── products.json      # Product listings
│   └── orders.json        # Customer orders
└── .vscode/
    ├── tasks.json         # VS Code tasks
    └── launch.json        # VS Code launch config
```

## 🎯 Usage

### For Customers
1. Browse products on the homepage
2. Click "Order Now" on any product
3. Fill out the order form
4. Submit your order

### For Administrators
1. Go to the Admin panel at `/admin.php`
2. Click "Add New Product" to create a new listing
3. Fill in product details (name, description, price, image URL)
4. Edit or delete existing products as needed

### Adding Product Images
You can use:
- Direct image URLs from image hosting services
- Unsplash or similar free stock photo services
- Your own hosted images

## 🎨 Customization

### Colors
Edit the CSS variables in `styles.css`:
```css
:root {
    --pikapp-blue: #003087;
    --pikapp-gold: #D4AF37;
}
```

### Branding
The site uses Pi Kappa Phi's official brand colors and typography as specified in their brand guidelines:
- Brandon Grotesque (primary font)
- Royal Blue and Gold color scheme
- Greek letters (Π Κ Φ) throughout

## 📝 Data Storage

- **Products**: Stored in `data/products.json`
- **Orders**: Stored in `data/orders.json`

Both files use JSON format for easy reading and editing.

## ⚠️ Security Notes

**Important**: This is a basic implementation. For production use, consider:
- Adding authentication to the admin panel
- Implementing CSRF protection
- Sanitizing all user inputs properly
- Using a proper database instead of JSON files
- Adding SSL/HTTPS
- Implementing payment processing

## 🌐 Browser Support

- Chrome/Edge (latest)
- Firefox (latest)
- Safari (latest)
- Mobile browsers

## 📧 Support

For issues or questions, refer to the code comments or modify as needed for your chapter's specific requirements.

---

**Exceptional starts here.** ΠΚΦ
