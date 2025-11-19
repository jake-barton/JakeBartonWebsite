<?php
// 33Miles Graphics Portfolio
$pageTitle = "33Miles - Professional Graphics";

// Design data for 33Miles project
$designs = [
    // Selected graphics only
    [
        'id' => 1,
        'title' => '33Miles Graphic 01 - Striped',
        'description' => 'Social media and event advertisement graphic for Christian band 33Miles',
        'year' => '2024',
        'thumbnail' => 'images/thumbnails/33-miles-01-grain-striped.png',
        'full' => 'images/full/33-miles-01-grain-striped.png'
    ],
    [
        'id' => 3,
        'title' => '33Miles Graphic 02',
        'description' => 'Social media and event advertisement graphic for Christian band 33Miles',
        'year' => '2024',
        'thumbnail' => 'images/thumbnails/33-miles-02-grain-regular.png',
        'full' => 'images/full/33-miles-02-grain-regular.png'
    ],
    [
        'id' => 5,
        'title' => '33Miles Graphic 03',
        'description' => 'Social media and event advertisement graphic for Christian band 33Miles',
        'year' => '2024',
        'thumbnail' => 'images/thumbnails/33-miles-03-grain-regular.png',
        'full' => 'images/full/33-miles-03-grain-regular.png'
    ],
    [
        'id' => 6,
        'title' => '33Miles Graphic 04',
        'description' => 'Social media and event advertisement graphic for Christian band 33Miles',
        'year' => '2024',
        'thumbnail' => 'images/thumbnails/33-miles-04-grain-regular.png',
        'full' => 'images/full/33-miles-04-grain-regular.png'
    ],
    [
        'id' => 7,
        'title' => '33Miles Graphic 05',
        'description' => 'Social media and event advertisement graphic for Christian band 33Miles',
        'year' => '2024',
        'thumbnail' => 'images/thumbnails/33-miles-05-grain-regular.png',
        'full' => 'images/full/33-miles-05-grain-regular.png'
    ],
    [
        'id' => 8,
        'title' => '33Miles Graphic 06',
        'description' => 'Social media and event advertisement graphic for Christian band 33Miles',
        'year' => '2024',
        'thumbnail' => 'images/thumbnails/33-miles-06-grain-regular.png',
        'full' => 'images/full/33-miles-06-grain-regular.png'
    ],
    [
        'id' => 9,
        'title' => '33Miles Graphic 07',
        'description' => 'Social media and event advertisement graphic for Christian band 33Miles',
        'year' => '2024',
        'thumbnail' => 'images/thumbnails/33-miles-07-grain-regular.png',
        'full' => 'images/full/33-miles-07-grain-regular.png'
    ],
    [
        'id' => 10,
        'title' => '33Miles Graphic 08',
        'description' => 'Social media and event advertisement graphic for Christian band 33Miles',
        'year' => '2024',
        'thumbnail' => 'images/thumbnails/33-miles-08-grain-regular.png',
        'full' => 'images/full/33-miles-08-grain-regular.png'
    ],
];

// Get unique categories for filtering
// Categories removed; page now shows a curated selection only.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - Jake Barton</title>
    <link rel="stylesheet" href="../../../assets/css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Oswald:wght@400;700&display=swap" rel="stylesheet">
    <script src="../../../assets/js/effects.js" defer></script>
</head>
<body>
    <div class="animated-bg"></div>

    <header>
        <nav>
            <a href="../../../index.php" class="nav-logo" style="text-decoration: none; color: inherit;">JB</a>
            <button class="nav-toggle" aria-controls="primary-menu" aria-expanded="false" aria-label="Open menu">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </button>
            <ul id="primary-menu">
                <li class="mobile-visible"><a href="../../../index.php">Home</a></li>
                <li class="mobile-visible"><a href="../../">Portfolio</a></li>
                <li><a href="../">Professional Works</a></li>
                <li><a href="../../games/">Games</a></li>
                <li><a href="../../tshirt-designs/">T-Shirt Designs</a></li>
                <li><a href="../../../assets/Jake%20Barton%20-%20Resume.pdf" download>Resume</a></li>
                <li class="mobile-visible"><a href="../../../index.php#contact">Contact</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <div class="content-section" style="text-align: center; padding: 80px;">
            <h1 style="font-size: 4.5rem;">33MILES - PROFESSIONAL GRAPHICS</h1>
            <p style="font-size: 1.2rem; color: var(--text-muted); margin-top: 25px; max-width: 900px; margin-left: auto; margin-right: auto;">
                Professional graphic design work for <strong style="color: var(--accent-white); font-family: 'Bebas Neue', sans-serif; letter-spacing: 2px;">33MILES</strong>, 
                a Christian band. Created social media graphics and event advertisement materials featuring both grainy textured and clean modern designs.
            </p>
        </div>

        <div class="content-section">
            <!-- Filter Controls removed as requested -->
            <div class="filter-controls" style="display:none"></div>

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
                Click on any design to view it in full size. This project showcases professional client work for social media and event marketing.
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

    <script src="../../../assets/js/gallery.js"></script>
</body>
</html>
