#!/bin/bash

# Automatic sync from _public_html to hostinger_upload
# Run this after making any changes to keep deployment folder updated

set -e
cd "$(dirname "$0")"
python3 scripts/build-games.py

echo "🔄 Syncing _public_html to hostinger_upload..."

# Create/update deployment directory
DEPLOY_DIR="hostinger_upload"
mkdir -p "$DEPLOY_DIR"

# Sync files (rsync preserves structure and only copies changed files)
rsync -av --delete \
  --exclude='test.php' \
  --exclude='readme.html' \
  --exclude='README.txt' \
  --exclude='.DS_Store' \
  --exclude='.env' \
  --exclude='.env.*' \
  --exclude='.git/' \
  --exclude='.gitignore' \
  --exclude='convert_images.sh' \
  --exclude='SETUP_GUIDE.md' \
  _public_html/ "$DEPLOY_DIR/"

# Add the test.php file
cat > "$DEPLOY_DIR/test.php" << 'EOF'
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deployment Test - Jake Barton</title>
    <style>
        body {
            background: #000;
            color: #fff;
            font-family: Arial, sans-serif;
            padding: 40px;
            line-height: 1.6;
        }
        .success { color: #00ff00; }
        .error { color: #ff0000; }
        .test-box {
            border: 2px solid #fff;
            padding: 20px;
            margin: 20px 0;
        }
        h1 { 
            font-size: 3rem; 
            border-bottom: 3px solid #fff;
            padding-bottom: 20px;
        }
        .code {
            background: #1a1a1a;
            padding: 10px;
            border-left: 3px solid #fff;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <h1>🔧 Jake Barton Website - Deployment Test</h1>
    
    <div class="test-box">
        <h2>Test 1: PHP Working?</h2>
        <?php if (true): ?>
            <p class="success">✅ YES! PHP is working correctly!</p>
            <p>PHP Version: <?php echo phpversion(); ?></p>
        <?php else: ?>
            <p class="error">❌ PHP is not working</p>
        <?php endif; ?>
    </div>

    <div class="test-box">
        <h2>Test 2: File Structure</h2>
        <?php
        $files_to_check = [
            'index.php' => 'Main homepage',
            'work/index.php' => 'Work index',
            'includes/projects.php' => 'Public project registry',
            'includes/project-header.php' => 'Shared project header',
            'includes/rail-resume.php' => 'Shared resume icon',
            'assets/css/home.css' => 'Homepage stylesheet',
            'assets/css/typography.css' => 'Shared typography',
            'assets/js/project-media.js' => 'Project media playback',
            'assets/documents/jake-barton-resume.pdf' => 'Resume PDF',
            'games/mario-kart/index.html' => 'Built browser game'
        ];
        
        foreach ($files_to_check as $file => $description) {
            if (file_exists($file)) {
                echo "<p class='success'>✅ $file - $description</p>";
            } else {
                echo "<p class='error'>❌ $file - MISSING! $description</p>";
            }
        }
        ?>
    </div>

    <div class="test-box">
        <h2>Test 3: CSS Loading</h2>
        <p>This diagnostic uses inline styles. Open the homepage and Work index to verify the shared stylesheets and responsive layout.</p>
    </div>

    <div class="test-box">
        <h2>Test 4: Links</h2>
        <p>Try these links:</p>
        <ul>
            <li><a href="index.php" style="color: #00ff00;">Main Homepage</a></li>
            <li><a href="work/" style="color: #00ff00;">Work</a></li>
            <li><a href="work/barton-design-solutions/" style="color: #00ff00;">Barton Design Solutions</a></li>
        </ul>
        <p>If these links work, your file structure is correct! ✅</p>
    </div>

    <div class="test-box">
        <h2>Current Directory</h2>
        <p class="code"><?php echo getcwd(); ?></p>
    </div>

    <div class="test-box">
        <h2>📋 Next Steps</h2>
        <?php
        $all_files_exist = true;
        foreach ($files_to_check as $file => $description) {
            if (!file_exists($file)) {
                $all_files_exist = false;
                break;
            }
        }
        
        if ($all_files_exist):
        ?>
            <p class="success">🎉 ALL FILES ARE IN THE RIGHT PLACE!</p>
            <p>You can now:</p>
            <ol>
                <li><a href="index.php" style="color: #00ff00;">Visit your homepage</a></li>
                <li>Delete this test.php file (optional)</li>
                <li>Enjoy your website!</li>
            </ol>
        <?php else: ?>
            <p class="error">⚠️ SOME FILES ARE MISSING!</p>
            <p>Please check that you uploaded:</p>
            <ul>
                <li>index.php to the ROOT of public_html</li>
                <li>assets/ folder to the ROOT of public_html</li>
                <li>work/ and includes/ folders to the ROOT of public_html</li>
            </ul>
            <p>Do NOT create an extra folder inside public_html!</p>
        <?php endif; ?>
    </div>

    <p style="margin-top: 40px; color: #999;">
        Made by Jake Barton | <?php echo date('Y'); ?>
    </p>
</body>
</html>
EOF

echo "✅ Sync complete!"
echo ""
echo "📦 hostinger_upload/ is now ready to drag & drop to Hostinger!"
echo ""
echo "Files synced:"
echo "  ✓ index.php"
echo "  ✓ assets/"
echo "  ✓ work/ and includes/"
echo "  ✓ test.php"
