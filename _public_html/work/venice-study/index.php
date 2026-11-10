<?php
// Edit this project’s page title, description, colors, and HTML below.
$pageTitle = 'Venice Study';
$pageDescription = 'An architectural illustration of Venice, focused on buildings, streets, and line work.';
$theme = [
    'canvas' => '#eee8dc',
    'ink' => '#202020',
    'accent' => '#202020',
    'rail' => '#23883c',
    'footer_background' => '#eee8dc',
    'footer_text' => '#202020',
    'footer_word' => '#202020',
];

require __DIR__ . '/../../includes/project-header.php';
?>

    <section class="jb-detail-hero" aria-labelledby="project-title" style="--hero-position: center; --hero-fit: cover; --hero-overlay: #000000; --hero-overlay-opacity: 0.14; --hero-text: #ffffff">
        <img src="/assets/work/venice-study/venice-illustration.jpg" alt="Architectural line illustration of Venice">
        <div class="jb-detail-hero-shade" aria-hidden="true"></div>
        <div class="jb-detail-hero-copy">
            <p style="color:#ffffff">Architectural illustration · Line study</p>
            <h1 id="project-title" style="color:#ffffff">Venice Study</h1>
        </div>
    </section>
    <section class="jb-detail-intro" style="--intro-background: #eee8dc; --intro-text: #202020">
        <div class="jb-detail-info-module">
            <h2 style="color:#202020">Venice Study</h2>
            <dl class="jb-detail-meta">
                <div>
                    <dt style="color:#202020">Role</dt>
                    <dd style="color:#202020">Illustrator</dd>
                </div>
                <div>
                    <dt style="color:#202020">Disciplines</dt>
                    <dd style="color:#202020">Architectural Drawing, Composition, Linework</dd>
                </div>
                <div>
                    <dt style="color:#202020">Built with</dt>
                    <dd style="color:#202020">Digital Illustration</dd>
                </div>
                <div>
                    <dt style="color:#202020">Client</dt>
                    <dd style="color:#202020">Independent Study</dd>
                </div>
            </dl>
        </div>
        <div class="jb-detail-story-module">
            <div class="jb-detail-intro-copy">
                <p style="color:#202020">An architectural illustration of Venice, focused on buildings, streets, and line work.</p>
            </div>
        </div>
    </section>
    <figure class="jb-project-media is-portrait" style="--media-background:#101010">
        <img src="/assets/work/venice-study/venice-illustration.jpg" alt="Full Venice architectural illustration" width="1600" height="2134" loading="lazy">
    </figure>

<?php require __DIR__ . '/../../includes/project-footer.php'; ?>
