<?php
/**
 * Shared Header - Jake Barton Website
 * Includes navigation and meta tags
 */

// Determine if we're in a subdirectory
$depth = isset($page_depth) ? $page_depth : 0;
$base_path = str_repeat('../', $depth);

// Set page title
$page_title = isset($custom_title) ? $custom_title . ' - ' . SITE_NAME : SITE_NAME;
$page_description = isset($custom_description) ? $custom_description : SITE_DESCRIPTION;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo htmlspecialchars($page_description); ?>">
    <meta name="keywords" content="game design, 3D art, game developer, unreal engine, unity, godot, graphic design, birmingham, samford university">
    <meta name="author" content="<?php echo FULL_NAME; ?>">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo SITE_URL . $_SERVER['REQUEST_URI']; ?>">
    <meta property="og:title" content="<?php echo htmlspecialchars($page_title); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($page_description); ?>">
    <meta property="og:image" content="<?php echo SITE_URL; ?>/assets/images/og-image.jpg">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?php echo SITE_URL . $_SERVER['REQUEST_URI']; ?>">
    <meta property="twitter:title" content="<?php echo htmlspecialchars($page_title); ?>">
    <meta property="twitter:description" content="<?php echo htmlspecialchars($page_description); ?>">
    <meta property="twitter:image" content="<?php echo SITE_URL; ?>/assets/images/og-image.jpg">
    
    <title><?php echo htmlspecialchars($page_title); ?></title>
    
    <!-- Style Kit CSS (order matters) -->
    <link rel="stylesheet" href="<?php echo $base_path; ?>assets/css/base.css">
    <link rel="stylesheet" href="<?php echo $base_path; ?>assets/css/animations.css">
    <link rel="stylesheet" href="<?php echo $base_path; ?>assets/css/components.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="<?php echo $base_path; ?>assets/images/favicon.svg">
    
    <!-- Scripts -->
    <?php if (isset($include_gallery) && $include_gallery): ?>
    <script src="<?php echo $base_path; ?>assets/js/gallery.js" defer></script>
    <?php endif; ?>
</head>
<body>

  <!-- Navigation -->
  <header class="site-nav" id="site-nav">
    <a href="<?php echo $base_path; ?>index.php" class="nav-logo">JB</a>
    <nav class="nav-links">
            <div class="nav-logo">JB</div>
            <ul>
                <li><a href="<?php echo $base_path; ?>index.php" <?php echo (get_current_page() == 'index.php' && $depth == 0) ? 'class="active"' : ''; ?>>Home</a></li>
                <li><a href="<?php echo $base_path; ?>index.php#about" <?php echo (get_current_page() == 'index.php' && isset($_GET['section']) && $_GET['section'] == 'about') ? 'class="active"' : ''; ?>>About</a></li>
                <li><a href="<?php echo $base_path; ?>index.php#skills" <?php echo (get_current_page() == 'index.php' && isset($_GET['section']) && $_GET['section'] == 'skills') ? 'class="active"' : ''; ?>>Skills</a></li>
                <li><a href="<?php echo $base_path; ?>portfolio/" <?php echo (strpos($_SERVER['REQUEST_URI'], 'portfolio') !== false) ? 'class="active"' : ''; ?>>Portfolio</a></li>
                <li><a href="<?php echo $base_path; ?>index.php#contact" <?php echo (get_current_page() == 'index.php' && isset($_GET['section']) && $_GET['section'] == 'contact') ? 'class="active"' : ''; ?>>Contact</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
