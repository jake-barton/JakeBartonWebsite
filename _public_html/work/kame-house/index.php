<?php
// Edit this project’s page title, description, colors, and HTML below.
$pageTitle = 'Kame House';
$pageDescription = 'A 3D environment study based on Kame House from Dragon Ball.';
$theme = [
    'canvas' => '#f7e8c6',
    'ink' => '#17344e',
    'accent' => '#da6d8a',
    'rail' => '#23883c',
    'footer_background' => '#4aaed1',
    'footer_text' => '#17344e',
    'footer_word' => '#17344e',
];

require __DIR__ . '/../../includes/project-header.php';
?>

    <section class="jb-detail-hero" aria-labelledby="project-title" style="--hero-position: center; --hero-fit: cover; --hero-overlay: #17344e; --hero-overlay-opacity: 0.2; --hero-text: #ffffff">
        <img src="/assets/work/kame-house/final-render-1.png" alt="Final Unreal Engine render of Kame House">
        <div class="jb-detail-hero-shade" aria-hidden="true"></div>
        <div class="jb-detail-hero-copy">
            <p style="color:#ffffff">Environment art · Unreal Engine</p>
            <h1 id="project-title" style="color:#ffffff">Kame House</h1>
        </div>
    </section>
    <section class="jb-detail-intro" style="--intro-background: #f7e8c6; --intro-text: #17344e">
        <div class="jb-detail-info-module">
            <h2 style="color:#17344e">Kame House</h2>
            <dl class="jb-detail-meta">
                <div>
                    <dt style="color:#17344e">Role</dt>
                    <dd style="color:#17344e">Environment Artist</dd>
                </div>
                <div>
                    <dt style="color:#17344e">Disciplines</dt>
                    <dd style="color:#17344e">Modeling, Materials, Lighting, Composition</dd>
                </div>
                <div>
                    <dt style="color:#17344e">Built with</dt>
                    <dd style="color:#17344e">Unreal Engine, Maya, Substance</dd>
                </div>
                <div>
                    <dt style="color:#17344e">Client</dt>
                    <dd style="color:#17344e">3D Coursework</dd>
                </div>
            </dl>
        </div>
        <div class="jb-detail-story-module">
            <div class="jb-detail-intro-copy">
                <p style="color:#17344e">A 3D environment study based on Kame House from Dragon Ball.</p>
                <p style="color:#17344e">The images show the greyscale scene and final renders with materials, lighting, water, and vegetation.</p>
            </div>
        </div>
    </section>
    <figure class="jb-project-media" style="--media-background:#101010">
        <img src="/assets/work/kame-house/greyscale-render.png" alt="Greyscale process render of the Kame House scene" width="1920" height="1080" loading="lazy">
    </figure>
    <figure class="jb-project-media" style="--media-background:#101010">
        <img src="/assets/work/kame-house/final-render-2.png" alt="Alternate final render of Kame House" width="1920" height="1080" loading="lazy">
    </figure>

<?php require __DIR__ . '/../../includes/project-footer.php'; ?>
