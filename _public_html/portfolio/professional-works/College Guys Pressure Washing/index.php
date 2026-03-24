<?php
// College Guys Pressure Washing Graphics Portfolio
$pageTitle = "College Guys Pressure Washing - Professional Graphics";

// Design data for College Guys Pressure Washing project
$designs = [
    [
        'id' => 1,
        'title' => 'College Guys Banner',
        'description' => 'Professional banner graphic for College Guys Pressure Washing',
        'year' => '2024',
        'category' => 'banner',
        'thumbnail' => 'College Guys Pressure Washing Banner.svg',
        'full' => 'College Guys Pressure Washing Banner.svg'
    ],
    [
        'id' => 2,
        'title' => 'College Guys Backdrop',
        'description' => 'Professional backdrop graphic for College Guys Pressure Washing',
        'year' => '2024',
        'category' => 'backdrop',
        'thumbnail' => 'College Guys Pressure Washing Backdrop.svg',
        'full' => 'College Guys Pressure Washing Backdrop.svg'
    ],
];

// Get unique categories for filtering
$categories = array_unique(array_column($designs, 'category'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - Jake Barton</title>
    <link rel="stylesheet" href="../../../assets/css/base.css">
    <link rel="stylesheet" href="../../../assets/css/animations.css">
    <link rel="stylesheet" href="../../../assets/css/components.css">
    <style>
        .filter-controls { display: flex; gap: 12px; flex-wrap: wrap; justify-content: center; margin-bottom: 40px; }
        .filter-btn { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.12); color: var(--text-muted); padding: 10px 24px; border-radius: 100px; cursor: pointer; font-family: var(--font-body); font-size: 0.9rem; transition: all 0.3s ease; }
        .filter-btn:hover, .filter-btn.active { background: var(--accent); border-color: var(--accent); color: #fff; }
        .gallery-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 24px; }
        .gallery-item { background: var(--bg-card); border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; overflow: hidden; cursor: pointer; transition: all 0.35s ease; }
        .gallery-item:hover { transform: translateY(-6px); border-color: var(--accent); box-shadow: 0 12px 40px rgba(255,255,255,0.08); }
        .gallery-item img { width: 100%; aspect-ratio: 16/9; object-fit: contain; background: #111; padding: 16px; display: block; }
        .gallery-item-info { padding: 16px 20px; }
        .gallery-item-info h3 { font-size: 1rem; font-weight: 600; margin-bottom: 4px; color: var(--text); }
        .gallery-item-info p { color: var(--text-muted); font-size: 0.85rem; }
        .modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.9); z-index: 1000; align-items: center; justify-content: center; }
        .modal.open { display: flex; }
        .modal-content { background: var(--bg-card); border: 1px solid rgba(255,255,255,0.12); border-radius: 20px; max-width: 90vw; max-height: 90vh; overflow: hidden; display: grid; grid-template-columns: 1fr 340px; }
        .modal-content img { max-height: 80vh; width: 100%; object-fit: contain; background: #111; padding: 24px; }
        .modal-info { padding: 40px; display: flex; flex-direction: column; justify-content: center; }
        .modal-info h2 { font-size: 1.8rem; margin-bottom: 12px; color: var(--text); }
        .modal-info p { color: var(--text-muted); margin-bottom: 8px; }
        .close { position: absolute; top: 20px; right: 28px; font-size: 2rem; cursor: pointer; color: var(--text-muted); }
        .close:hover { color: var(--accent); }
        .modal-nav { position: absolute; top: 50%; transform: translateY(-50%); font-size: 2rem; cursor: pointer; color: var(--text-muted); padding: 12px; background: rgba(0,0,0,0.5); border-radius: 50%; transition: color 0.2s; }
        .modal-nav:hover { color: var(--accent); }
        .modal-prev { left: 20px; }
        .modal-next { right: 20px; }
        @media (max-width: 768px) {
            .modal-content { grid-template-columns: 1fr; }
            .modal-content img { max-height: 50vh; }
        }
    </style>
</head>
<body>  </div>


    <header class="site-nav" id="site-nav">
        <a href="../../../index.php" class="nav-logo">JB</a>
        <nav class="nav-links">
            <a href="../../../index.php#about">About</a>
            <a href="../../../index.php#skills">Skills</a>
            <a href="../../">Portfolio</a>
            <a href="../../../assets/Jake%20Barton%20-%20Resume.pdf" class="btn btn-secondary btn-sm" download>Resume</a>
            <a href="../../../index.php#contact">Contact</a>
        </nav>
    </header>


    <main class="site-content">

        <section class="section" style="padding-top: 140px; padding-bottom: 60px; text-align: center;">
            <div class="container" style="max-width: 800px;">
                                                                <h1 class="reveal" style="font-family:var(--font-display);font-size:clamp(2.5rem,6vw,4.5rem);font-weight:700;letter-spacing:-0.02em;line-height:1.1;color:var(--text);margin-bottom:1rem">College Guys Pressure Washing</h1>
                <div class="divider reveal" style="margin:1.5rem auto;max-width:80px"></div>
                <p class="reveal" style="font-size: 1.2rem; margin-top: 1.5rem;">
                    Branding &amp; marketing graphics for <strong style="color: var(--accent);">College Guys Pressure Washing</strong>,
                    a local pressure washing business — banner and backdrop graphics for branding and marketing.
                </p>
            </div>
        </section>

        <section class="section-sm">
            <div class="container">
                <div class="filter-controls reveal">
                    <button class="filter-btn active" data-filter="all">All Graphics</button>
                    <?php foreach ($categories as $cat): ?>
                        <button class="filter-btn" data-filter="<?php echo $cat; ?>"><?php echo ucfirst($cat); ?></button>
                    <?php endforeach; ?>
                </div>

                <div class="gallery-grid">
                    <?php foreach ($designs as $design): ?>
                        <div class="gallery-item"
                             data-title="<?php echo htmlspecialchars($design['title']); ?>"
                             data-description="<?php echo htmlspecialchars($design['description']); ?>"
                             data-year="<?php echo $design['year']; ?>"
                             data-category="<?php echo $design['category']; ?>"
                             data-full="<?php echo $design['full']; ?>">
                            <img src="<?php echo $design['thumbnail']; ?>"
                                 alt="<?php echo htmlspecialchars($design['title']); ?>"
                                 loading="lazy">
                            <div class="gallery-item-info">
                                <h3><?php echo htmlspecialchars($design['title']); ?></h3>
                                <p><?php echo ucfirst($design['category']); ?> &bull; <?php echo $design['year']; ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <p class="reveal" style="text-align: center; color: var(--text-muted); margin-top: 40px;">
                    Click any design to view full size. Professional client work for branding and marketing materials.
                </p>
            </div>
        </section>

        <section class="section-sm" style="text-align: center;">
            <div class="container">
                <a href="../" class="btn btn-secondary">&#8592; Back to Professional Works</a>
            </div>
        </section>

    </main>

    <!-- Modal -->
    <div id="designModal" class="modal" role="dialog" aria-modal="true">
        <span class="close" id="modalClose">&times;</span>
        <span class="modal-nav modal-prev" id="modalPrev">&#10094;</span>
        <span class="modal-nav modal-next" id="modalNext">&#10095;</span>
        <div class="modal-content">
            <img id="modalImage" src="" alt="">
            <div class="modal-info">
                <p class="eyebrow" id="modalYear"></p>
                <h2 id="modalTitle"></h2>
                <p id="modalDescription"></p>
            </div>
        </div>
    </div>

    <footer class="site-footer">
        <div class="container">
            <div class="footer-inner">
                <span class="footer-copy">&copy; <?php echo date('Y'); ?> Jake Barton. All rights reserved.</span>
                <div class="footer-socials">
                    <a href="https://www.linkedin.com/in/jakebartoncreative" target="_blank" class="btn-icon" aria-label="LinkedIn">in</a>
                    <a href="https://instagram.com/jakebarton13" target="_blank" class="btn-icon" aria-label="Instagram">IG</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="../../../assets/js/beams-bg.js"></script>
    <script src="../../../assets/js/cursor-ribbons.js"></script>
    <script src="../../../assets/js/fuzzy-text.js"></script>
    <script src="../../../assets/js/effects-stylekit.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="../../../assets/js/staggered-menu.js"></script>
    <script src="../../../assets/js/gallery.js"></script>

</body>
</html>
