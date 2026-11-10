<?php
// Edit this project’s page title, description, colors, and HTML below.
$pageTitle = 'Shel Silverstein Website';
$pageDescription = 'An interactive website about Shel Silverstein, with book illustrations, biographical information, and an animated gallery.';
$theme = [
    'canvas' => '#ffffff',
    'ink' => '#111111',
    'accent' => '#111111',
    'rail' => '#23883c',
    'footer_background' => '#ffffff',
    'footer_text' => '#111111',
    'footer_word' => '#111111',
];

require __DIR__ . '/../../includes/project-header.php';
?>

    <section class="jb-detail-hero" aria-labelledby="project-title" style="--hero-position: center; --hero-fit: cover; --hero-overlay: #000000; --hero-overlay-opacity: 0.15; --hero-text: #ffffff">
        <img src="/assets/project-covers/shelcover.png" alt="Shel Silverstein editorial website cover">
        <div class="jb-detail-hero-shade" aria-hidden="true"></div>
        <div class="jb-detail-hero-copy">
            <p style="color:#ffffff">Editorial web · Interactive archive</p>
            <h1 id="project-title" style="color:#ffffff">Shel Silverstein Website</h1>
        </div>
    </section>
    <section class="jb-detail-intro" style="--intro-background: #ffffff; --intro-text: #111111">
        <div class="jb-detail-info-module">
            <h2 style="color:#111111">Shel Silverstein Website</h2>
            <dl class="jb-detail-meta">
                <div>
                    <dt style="color:#111111">Role</dt>
                    <dd style="color:#111111">Designer + Developer</dd>
                </div>
                <div>
                    <dt style="color:#111111">Disciplines</dt>
                    <dd style="color:#111111">Editorial Design, Interaction, Front-End</dd>
                </div>
                <div>
                    <dt style="color:#111111">Built with</dt>
                    <dd style="color:#111111">HTML, CSS, JavaScript, SVG</dd>
                </div>
                <div>
                    <dt style="color:#111111">Client</dt>
                    <dd style="color:#111111">Academic Project</dd>
                </div>
            </dl>
        </div>
        <div class="jb-detail-story-module">
            <div class="jb-detail-intro-copy">
                <p style="color:#111111">An interactive website about Shel Silverstein, with book illustrations, biographical information, and an animated gallery.</p>
                <p style="color:#111111">I built the page layout, SVG presentation, navigation, and scrolling interactions.</p>
            </div>
        </div>
    </section>
    <figure class="jb-project-media is-portrait" style="--media-background:#101010">
        <img src="/assets/work/shel-silverstein/shel-portrait.jpg" alt="Portrait of Shel Silverstein" width="1196" height="1571" loading="lazy">
    </figure>
    <figure class="jb-project-media" style="--media-background:#101010">
        <img src="/assets/work/shel-silverstein/the-giving-tree.svg" alt="The Giving Tree illustration asset" loading="lazy">
    </figure>
    <figure class="jb-project-media" style="--media-background:#101010">
        <img src="/assets/work/shel-silverstein/the-missing-piece.svg" alt="The Missing Piece illustration asset" loading="lazy">
    </figure>
    <figure class="jb-project-media" style="--media-background:#101010">
        <img src="/assets/work/shel-silverstein/light-in-the-attic.svg" alt="A Light in the Attic illustration asset" loading="lazy">
    </figure>

<?php require __DIR__ . '/../../includes/project-footer.php'; ?>
