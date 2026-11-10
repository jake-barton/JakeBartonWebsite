<?php
// Edit this project’s page title, description, colors, and HTML below.
$pageTitle = 'Pi Kappa Phi Store';
$pageDescription = 'An apparel-ordering website for Pi Kappa Phi. Members can browse products, select sizes and quantities, and submit orders.';
$theme = [
    'canvas' => '#f4f0df',
    'ink' => '#003087',
    'accent' => '#d4af37',
    'rail' => '#23883c',
    'footer_background' => '#003087',
    'footer_text' => '#ffffff',
    'footer_word' => '#d4af37',
];

require __DIR__ . '/../../includes/project-header.php';
?>

    <section class="jb-detail-hero" aria-labelledby="project-title" style="--hero-position: center; --hero-fit: cover; --hero-overlay: #003087; --hero-overlay-opacity: 0.18; --hero-text: #ffffff">
        <img src="/assets/project-covers/pikappaphi-cover.svg" alt="Pi Kappa Phi apparel graphics">
        <div class="jb-detail-hero-shade" aria-hidden="true"></div>
        <div class="jb-detail-hero-copy">
            <p style="color:#ffffff">Commerce platform · Pi Kappa Phi</p>
            <h1 id="project-title" style="color:#ffffff">Pi Kappa Phi Store</h1>
        </div>
    </section>
    <section class="jb-detail-intro" style="--intro-background: #f4f0df; --intro-text: #003087">
        <div class="jb-detail-info-module">
            <h2 style="color:#003087">Pi Kappa Phi Store</h2>
            <dl class="jb-detail-meta">
                <div>
                    <dt style="color:#003087">Role</dt>
                    <dd style="color:#003087">Designer + Developer</dd>
                </div>
                <div>
                    <dt style="color:#003087">Disciplines</dt>
                    <dd style="color:#003087">Product Design, Full-Stack Web, Security</dd>
                </div>
                <div>
                    <dt style="color:#003087">Built with</dt>
                    <dd style="color:#003087">PHP, JavaScript, JSON, CSS</dd>
                </div>
                <div>
                    <dt style="color:#003087">Client</dt>
                    <dd style="color:#003087">Pi Kappa Phi</dd>
                </div>
            </dl>
        </div>
        <div class="jb-detail-story-module">
            <div class="jb-detail-intro-copy">
                <p style="color:#003087">An apparel-ordering website for Pi Kappa Phi. Members can browse products, select sizes and quantities, and submit orders.</p>
                <p style="color:#003087">I built the storefront and administration pages in PHP and JavaScript. Products, customers, and orders use JSON storage.</p>
            </div>
        </div>
    </section>
    <figure class="jb-project-media" style="--media-background:#101010">
        <img src="/assets/work/pi-kappa-phi-store/banner-logo.svg" alt="Pi Kappa Phi store banner identity" loading="lazy">
    </figure>
    <figure class="jb-project-media" style="--media-background:#101010">
        <img src="/assets/work/pi-kappa-phi-store/storefront-card.png" alt="Pi Kappa Phi apparel storefront card design" width="1450" height="604" loading="lazy">
    </figure>
    <figure class="jb-project-media" style="--media-background:#101010">
        <img src="/assets/work/pi-kappa-phi-store/star-shield.svg" alt="Pi Kappa Phi star shield mark" loading="lazy">
    </figure>

<?php require __DIR__ . '/../../includes/project-footer.php'; ?>
