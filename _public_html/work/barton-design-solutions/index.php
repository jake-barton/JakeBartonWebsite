<?php
// Edit this project’s page title, description, colors, and HTML below.
$pageTitle = 'Barton Design Solutions';
$pageDescription = 'The website for my design business, with a portfolio gallery, business information, and a contact form.';
$theme = [
    'canvas' => '#f0e7d3',
    'ink' => '#141414',
    'accent' => '#015e96',
    'rail' => '#23883c',
    'footer_background' => '#f0e7d3',
    'footer_text' => '#141414',
    'footer_word' => '#015e96',
];

require __DIR__ . '/../../includes/project-header.php';
?>

    <section class="jb-detail-hero" aria-labelledby="project-title" style="--hero-position: center; --hero-fit: cover; --hero-overlay: #000000; --hero-overlay-opacity: 0.35; --hero-text: #ffffff">
        <video src="/assets/work/barton-design-solutions/hover-preview.mp4" poster="/assets/work/barton-design-solutions/hover-preview-poster.webp" muted autoplay loop playsinline preload="metadata" disablepictureinpicture disableremoteplayback tabindex="-1"></video>
        <div class="jb-detail-hero-shade" aria-hidden="true"></div>
        <div class="jb-detail-hero-copy">
            <p style="color:#ffffff">Web development</p>
            <h1 id="project-title" style="color:#ffffff">Barton Design Solutions</h1>
        </div>
    </section>
    <section class="jb-detail-intro" style="--intro-background: #f0e7d3; --intro-text: #141414">
        <div class="jb-detail-info-module">
            <h2 style="color:#141414">Barton Design Solutions</h2>
            <dl class="jb-detail-meta">
                <div>
                    <dt style="color:#141414">Role</dt>
                    <dd style="color:#141414">Designer &amp; Developer</dd>
                </div>
                <div>
                    <dt style="color:#141414">Disciplines</dt>
                    <dd style="color:#141414">Web development, Graphic design, Business</dd>
                </div>
                <div>
                    <dt style="color:#141414">Built with</dt>
                    <dd style="color:#141414">PHP, JavaScript, Three.js, HTML, CSS</dd>
                </div>
                <div>
                    <dt style="color:#141414">Client</dt>
                    <dd style="color:#141414">Barton Design Solutions</dd>
                </div>
            </dl>
        </div>
        <div class="jb-detail-story-module">
            <div class="jb-detail-intro-copy">
                <p style="color:#141414">The website for my design business, with a portfolio gallery, business information, and a contact form.</p>
                <p style="color:#141414">I built the PHP site and browser interactions, including a scroll-driven 3D presentation and an interactive artwork gallery.</p>
            </div>
        </div>
    </section>
    <figure class="jb-project-media" style="--media-background:#101010">
        <img src="/assets/work/barton-design-solutions/website.webp" alt="Barton Design Solutions homepage." width="1440" height="960" loading="lazy">
    </figure>
    <div class="jb-project-links">
        <a class="jb-fold-underline" href="https://bartondesignsolutions.com/" target="_blank" rel="noopener">Visit website</a>
    </div>

<?php require __DIR__ . '/../../includes/project-footer.php'; ?>
