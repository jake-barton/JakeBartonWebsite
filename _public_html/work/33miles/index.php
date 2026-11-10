<?php
// Edit this project’s page title, description, colors, and HTML below.
$pageTitle = '33Miles';
$pageDescription = 'Social graphics for 33Miles, including profile artwork, announcements, square posts, and a Facebook banner.';
$theme = [
    'canvas' => '#e6dfcf',
    'ink' => '#191919',
    'accent' => '#c9c399',
    'rail' => '#23883c',
    'footer_background' => '#191919',
    'footer_text' => '#ffffff',
    'footer_word' => '#c9c399',
];

require __DIR__ . '/../../includes/project-header.php';
?>

    <section class="jb-detail-hero" aria-labelledby="project-title" style="--hero-position: center; --hero-fit: cover; --hero-overlay: #000000; --hero-overlay-opacity: 0.22; --hero-text: #ffffff">
        <img src="/assets/project-covers/33miles-cover.jpg" alt="33Miles campaign artwork">
        <div class="jb-detail-hero-shade" aria-hidden="true"></div>
        <div class="jb-detail-hero-copy">
            <p style="color:#ffffff">Artist campaign · Visual identity</p>
            <h1 id="project-title" style="color:#ffffff">33Miles</h1>
        </div>
    </section>
    <section class="jb-detail-intro" style="--intro-background: #e6dfcf; --intro-text: #191919">
        <div class="jb-detail-info-module">
            <h2 style="color:#191919">33Miles</h2>
            <dl class="jb-detail-meta">
                <div>
                    <dt style="color:#191919">Role</dt>
                    <dd style="color:#191919">Graphic Designer</dd>
                </div>
                <div>
                    <dt style="color:#191919">Disciplines</dt>
                    <dd style="color:#191919">Art Direction, Social Design, Campaign</dd>
                </div>
                <div>
                    <dt style="color:#191919">Built with</dt>
                    <dd style="color:#191919">Photoshop, Illustrator</dd>
                </div>
                <div>
                    <dt style="color:#191919">Client</dt>
                    <dd style="color:#191919">33Miles</dd>
                </div>
            </dl>
        </div>
        <div class="jb-detail-story-module">
            <div class="jb-detail-intro-copy">
                <p style="color:#191919">Social graphics for 33Miles, including profile artwork, announcements, square posts, and a Facebook banner.</p>
                <p style="color:#191919">I worked on the typography, image layouts, and artwork for the different formats.</p>
            </div>
        </div>
    </section>
    <figure class="jb-project-media" style="--media-background:#101010">
        <img src="/assets/work/33miles/facebook-banner.png" alt="33Miles Facebook campaign banner" loading="lazy">
    </figure>
    <figure class="jb-project-media" style="--media-background:#101010">
        <img src="/assets/work/33miles/profile-art.png" alt="33Miles profile artwork" width="1601" height="1601" loading="lazy">
    </figure>
    <figure class="jb-project-media" style="--media-background:#101010">
        <img src="/assets/work/33miles/campaign-01.png" alt="33Miles square campaign graphic" width="1601" height="1601" loading="lazy">
    </figure>
    <figure class="jb-project-media" style="--media-background:#101010">
        <img src="/assets/work/33miles/campaign-07.png" alt="Alternate 33Miles campaign graphic" width="1601" height="1601" loading="lazy">
    </figure>

<?php require __DIR__ . '/../../includes/project-footer.php'; ?>
