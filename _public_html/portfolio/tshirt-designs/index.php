<?php
// T-Shirt Design Portfolio
$pageTitle = "T-Shirt Designs Portfolio";

// Design data - Add your designs here!
$designs = [
    // 2025 Designs
    [
        'id' => 1,
        'title' => 'Fall Recruitment 2025 - Design 1',
        'description' => 'Custom recruitment t-shirt design for Pi Kappa Phi Fall 2025',
        'year' => '2025',
        'thumbnail' => 'images/thumbnails/Fall Recruitment \'25-01.svg',
        'full' => 'images/full/Fall Recruitment \'25-01.svg'
    ],
    [
        'id' => 2,
        'title' => 'Fall Recruitment 2025 - Design 2',
        'description' => 'Custom recruitment t-shirt design for Pi Kappa Phi Fall 2025',
        'year' => '2025',
        'thumbnail' => 'images/thumbnails/Fall Recruitment \'25-02.svg',
        'full' => 'images/full/Fall Recruitment \'25-02.svg'
    ],
    [
        'id' => 3,
        'title' => 'Barn Bash 2025',
        'description' => 'Custom event t-shirt design for Barn Bash 2025',
        'year' => '2025',
        'thumbnail' => 'images/thumbnails/Barn Bash 2025.svg',
        'full' => 'images/full/Barn Bash 2025.svg'
    ],
    
    // 2024 Designs
    [
        'id' => 4,
        'title' => 'Southern Gents - Design 1',
        'description' => 'Custom t-shirt design based on album cover - Southern Gents collection',
        'year' => '2025',
        'thumbnail' => 'images/thumbnails/SouthernGents-01.svg',
        'full' => 'images/full/SouthernGents/SouthernGents-01.svg'
    ],
    [
        'id' => 8,
        'title' => 'Southern Gents - Design 5',
        'description' => 'Custom t-shirt design based on album cover - Southern Gents collection',
        'year' => '2025',
        'thumbnail' => 'images/thumbnails/SouthernGents_Artboard 1-05.svg',
        'full' => 'images/full/SouthernGents/SouthernGents_Artboard 1-05.svg'
    ],
    [
        'id' => 9,
        'title' => 'Southern Gents 2024',
        'description' => 'Custom t-shirt design based on album cover - Southern Gents collection',
        'year' => '2024',
        'thumbnail' => 'images/thumbnails/Southern Gents 2024.svg',
        'full' => 'images/full/SouthernGents/Southern Gents 2024.svg'
    ],
    [
        'id' => 10,
        'title' => 'Barn Bash 2024',
        'description' => 'Custom event t-shirt design for Barn Bash 2024',
        'year' => '2024',
        'thumbnail' => 'images/thumbnails/Barn Bash 2024.svg',
        'full' => 'images/full/Barn Bash 2024.svg'
    ],
    [
        'id' => 11,
        'title' => 'Caribbean Party',
        'description' => 'Custom event t-shirt design for Caribbean themed party',
        'year' => '2024',
        'thumbnail' => 'images/thumbnails/Caribbean Party.svg',
        'full' => 'images/full/Caribbean Party.svg'
    ],
    [
        'id' => 12,
        'title' => 'Rose Ball',
        'description' => 'Custom formal event t-shirt design for Rose Ball',
        'year' => '2024',
        'thumbnail' => 'images/thumbnails/Rose Ball.svg',
        'full' => 'images/full/RoseBall/Rose Ball.svg'
    ],
    [
        'id' => 13,
        'title' => 'PGA Polo',
        'description' => 'Custom polo shirt design for Pi Kappa Phi',
        'year' => '2024',
        'thumbnail' => 'images/thumbnails/PGA Polo.svg',
        'full' => 'images/full/PGA Polo.svg'
    ],
    [
        'id' => 14,
        'title' => 'Samford Film Club',
        'description' => 'Custom t-shirt design for Samford Film Club',
        'year' => '2024',
        'thumbnail' => 'images/thumbnails/Samford Film.svg',
        'full' => 'images/full/Samford Film.svg'
    ],
    // Add more designs here as you upload them
];

// Get unique years for filtering
$years = array_unique(array_column($designs, 'year'));
sort($years);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - Jake Barton</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Oswald:wght@400;700&display=swap" rel="stylesheet">
    <script src="../../assets/js/effects.js" defer></script>
</head>
<body>
    <div class="animated-bg"></div>

    <header>
        <nav>
            <a href="../../index.php" class="nav-logo" style="text-decoration: none; color: inherit;">JB</a>
            <button class="nav-toggle" aria-controls="primary-menu" aria-expanded="false" aria-label="Open menu">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </button>
            <ul id="primary-menu">
                <li class="mobile-visible"><a href="../../index.php">Home</a></li>
                <li class="mobile-visible"><a href="../">Portfolio</a></li>
                <li><a href="../professional-works/">Professional Works</a></li>
                <li><a href="../games/">Games</a></li>
                <li><a href="./">T-Shirt Designs</a></li>
                <li><a href="../../assets/Jake%20Barton%20-%20Resume.pdf" download>Resume</a></li>
                <li class="mobile-visible"><a href="../../index.php#contact">Contact</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <div class="content-section" style="text-align: center; padding: 80px;">
            <h1 style="font-size: 4.5rem;"><?php echo strtoupper($pageTitle); ?></h1>
            <p style="font-size: 1.2rem; color: var(--text-muted); margin-top: 25px; max-width: 900px; margin-left: auto; margin-right: auto;">
                A showcase of custom t-shirt designs I've created as <strong style="color: var(--accent-white); font-family: 'Bebas Neue', sans-serif; letter-spacing: 2px;">T-SHIRT CHAIR</strong> 
                for Pi Kappa Phi Fraternity - Alpha Eta Chapter over the past two years.
            </p>
        </div>

        <div class="content-section">
            <!-- Filter Controls -->
            <div class="filter-controls">
                <button class="filter-btn active" data-filter="all"><span>All Designs</span></button>
                <?php foreach ($years as $year): ?>
                    <button class="filter-btn" data-filter="<?php echo $year; ?>"><span><?php echo $year; ?></span></button>
                <?php endforeach; ?>
            </div>

            <!-- Gallery Grid -->
            <div class="gallery-grid">
                <?php foreach ($designs as $design): ?>
                    <div class="gallery-item" 
                         data-title="<?php echo htmlspecialchars($design['title']); ?>"
                         data-description="<?php echo htmlspecialchars($design['description']); ?>"
                         data-year="<?php echo $design['year']; ?>"
                         data-full="<?php echo $design['full']; ?>">
                        <img src="<?php echo $design['thumbnail']; ?>" 
                             alt="<?php echo htmlspecialchars($design['title']); ?>"
                             loading="lazy">
                        <div class="gallery-item-info">
                            <h3><?php echo htmlspecialchars($design['title']); ?></h3>
                            <p><?php echo $design['year']; ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="content-section" style="text-align: center;">
            <p style="color: var(--text-muted); font-size: 1.1rem;">
                Click on any design to view it in full size. More designs will be added as they are created!
            </p>
        </div>
    </div>

    <!-- Modal for full-size images -->
    <div id="designModal" class="modal">
        <span class="close">&times;</span>
        <span class="modal-nav modal-prev">&#10094;</span>
        <span class="modal-nav modal-next">&#10095;</span>
        <div class="modal-content">
            <img id="modalImage" src="" alt="">
            <div class="modal-info">
                <h2 id="modalTitle"></h2>
                <p id="modalDescription"></p>
                <p><strong style="color: var(--accent-blue);">Year:</strong> <span id="modalYear"></span></p>
            </div>
        </div>
    </div>

    <script src="../../assets/js/gallery.js"></script>
    
    <script>
        // Mobile nav toggle
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.querySelector('.nav-toggle');
            const menu = document.getElementById('primary-menu');
            if (!btn || !menu) return;
            
            btn.addEventListener('click', function(e){
                e.stopPropagation();
                const expanded = btn.getAttribute('aria-expanded') === 'true';
                btn.setAttribute('aria-expanded', expanded ? 'false' : 'true');
                menu.classList.toggle('open');
            });
            
            // Close menu when clicking outside
            document.addEventListener('click', function(e) {
                if (menu.classList.contains('open') && !menu.contains(e.target) && !btn.contains(e.target)) {
                    menu.classList.remove('open');
                    btn.setAttribute('aria-expanded', 'false');
                }
            });
            
            // Close on Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && menu.classList.contains('open')) {
                    menu.classList.remove('open');
                    btn.setAttribute('aria-expanded', 'false');
                }
            });
        });
    </script>
</body>
</html>
