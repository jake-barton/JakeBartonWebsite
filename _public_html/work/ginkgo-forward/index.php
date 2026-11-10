<?php
// Edit this project’s page title, description, colors, and HTML below.
$pageTitle = 'Ginkgo Forward';
$pageDescription = 'A PHP website with procedurally generated ginkgo leaves and branches.';
$theme = [
    'canvas' => '#f0e7d3',
    'ink' => '#141414',
    'accent' => '#294651',
    'rail' => '#23883c',
    'footer_background' => '#f0e7d3',
    'footer_text' => '#141414',
    'footer_word' => '#294651',
];

require __DIR__ . '/../../includes/project-header.php';
?>

    <section class="jb-detail-hero" aria-labelledby="project-title" style="--hero-position: center; --hero-fit: cover; --hero-overlay: #000000; --hero-overlay-opacity: 0.35; --hero-text: #ffffff">
        <img src="/assets/work/ginkgo-forward/website.webp" alt="Ginkgo Forward project image">
        <div class="jb-detail-hero-shade" aria-hidden="true"></div>
        <div class="jb-detail-hero-copy">
            <p style="color:#ffffff">Web development</p>
            <h1 id="project-title" style="color:#ffffff">Ginkgo Forward</h1>
        </div>
    </section>
    <section class="jb-detail-intro" style="--intro-background: #f0e7d3; --intro-text: #141414">
        <div class="jb-detail-info-module">
            <h2 style="color:#141414">Ginkgo Forward</h2>
            <dl class="jb-detail-meta">
                <div>
                    <dt style="color:#141414">Role</dt>
                    <dd style="color:#141414">Web Developer</dd>
                </div>
                <div>
                    <dt style="color:#141414">Disciplines</dt>
                    <dd style="color:#141414">Web development, Graphic design, Programming</dd>
                </div>
                <div>
                    <dt style="color:#141414">Built with</dt>
                    <dd style="color:#141414">PHP, JavaScript, Canvas, SVG</dd>
                </div>
                <div>
                    <dt style="color:#141414">Client</dt>
                    <dd style="color:#141414">Website project</dd>
                </div>
            </dl>
        </div>
        <div class="jb-detail-story-module">
            <div class="jb-detail-intro-copy">
                <p style="color:#141414">A PHP website with procedurally generated ginkgo leaves and branches.</p>
                <p style="color:#141414">The artwork uses Canvas and SVG with shared seeded geometry.</p>
            </div>
        </div>
    </section>
    <figure class="jb-project-media" style="--media-background:#101010">
        <img src="/assets/work/ginkgo-forward/website.webp" alt="Ginkgo Forward website with generated leaf artwork." width="1440" height="960" loading="lazy">
    </figure>

<?php require __DIR__ . '/../../includes/project-footer.php'; ?>
