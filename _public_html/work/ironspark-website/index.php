<?php
// Edit this project’s page title, description, colors, and HTML below.
$pageTitle = 'IronSpark Website';
$pageDescription = 'A website for IronSpark Studios, with sections for animation, games, interactive projects, and studio information.';
$theme = [
    'canvas' => '#f0e7d3',
    'ink' => '#141414',
    'accent' => '#4e5c2f',
    'rail' => '#23883c',
    'footer_background' => '#f0e7d3',
    'footer_text' => '#141414',
    'footer_word' => '#4e5c2f',
];

require __DIR__ . '/../../includes/project-header.php';
?>

    <section class="jb-detail-hero" aria-labelledby="project-title" style="--hero-position: center; --hero-fit: cover; --hero-overlay: #000000; --hero-overlay-opacity: 0.35; --hero-text: #ffffff">
        <img src="/assets/work/ironspark-website/website.webp" alt="IronSpark Website project image">
        <div class="jb-detail-hero-shade" aria-hidden="true"></div>
        <div class="jb-detail-hero-copy">
            <p style="color:#ffffff">Web development</p>
            <h1 id="project-title" style="color:#ffffff">IronSpark Website</h1>
        </div>
    </section>
    <section class="jb-detail-intro" style="--intro-background: #f0e7d3; --intro-text: #141414">
        <div class="jb-detail-info-module">
            <h2 style="color:#141414">IronSpark Website</h2>
            <dl class="jb-detail-meta">
                <div>
                    <dt style="color:#141414">Role</dt>
                    <dd style="color:#141414">Web Developer</dd>
                </div>
                <div>
                    <dt style="color:#141414">Disciplines</dt>
                    <dd style="color:#141414">Web development, Programming</dd>
                </div>
                <div>
                    <dt style="color:#141414">Built with</dt>
                    <dd style="color:#141414">PHP, JavaScript, HTML, CSS</dd>
                </div>
                <div>
                    <dt style="color:#141414">Client</dt>
                    <dd style="color:#141414">IronSpark Studios</dd>
                </div>
            </dl>
        </div>
        <div class="jb-detail-story-module">
            <div class="jb-detail-intro-copy">
                <p style="color:#141414">A website for IronSpark Studios, with sections for animation, games, interactive projects, and studio information.</p>
                <p style="color:#141414">My work includes the website’s page structure, responsive layouts, navigation, and presentation of studio work.</p>
                <p style="color:#141414">Studio artwork belongs to the project artists.</p>
            </div>
        </div>
    </section>
    <figure class="jb-project-media" style="--media-background:#101010">
        <img src="/assets/work/ironspark-website/website.webp" alt="IronSpark Studios homepage." width="1440" height="960" loading="lazy">
    </figure>
    <div class="jb-project-links">
        <a class="jb-fold-underline" href="https://ironsparkstudios.com/" target="_blank" rel="noopener">IronSpark website</a>
    </div>

<?php require __DIR__ . '/../../includes/project-footer.php'; ?>
