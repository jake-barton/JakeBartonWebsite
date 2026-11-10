<?php
// Edit this project’s page title, description, colors, and HTML below.
$pageTitle = 'VR Rhythm Game';
$pageDescription = 'A two-person Unreal Engine VR rhythm game. I programmed the entire game, including hand-controlled dragon flight.';
$theme = [
    'canvas' => '#f0e7d3',
    'ink' => '#141414',
    'accent' => '#8b437f',
    'rail' => '#23883c',
    'footer_background' => '#f0e7d3',
    'footer_text' => '#141414',
    'footer_word' => '#8b437f',
];

require __DIR__ . '/../../includes/project-header.php';
?>

    <section class="jb-detail-hero" aria-labelledby="project-title" style="--hero-position: center; --hero-fit: cover; --hero-overlay: #000000; --hero-overlay-opacity: 0.35; --hero-text: #ffffff">
        <video src="/assets/work/vr-rhythm-game/dragon-flight.mp4" poster="/assets/work/vr-rhythm-game/dragon-flight-poster.webp" muted autoplay loop playsinline preload="metadata" disablepictureinpicture disableremoteplayback tabindex="-1"></video>
        <div class="jb-detail-hero-shade" aria-hidden="true"></div>
        <div class="jb-detail-hero-copy">
            <p style="color:#ffffff">Games</p>
            <h1 id="project-title" style="color:#ffffff">VR Rhythm Game</h1>
        </div>
    </section>
    <section class="jb-detail-intro" style="--intro-background: #f0e7d3; --intro-text: #141414">
        <div class="jb-detail-info-module">
            <h2 style="color:#141414">VR Rhythm Game</h2>
            <dl class="jb-detail-meta">
                <div>
                    <dt style="color:#141414">Role</dt>
                    <dd style="color:#141414">Sole Programmer</dd>
                </div>
                <div>
                    <dt style="color:#141414">Disciplines</dt>
                    <dd style="color:#141414">Game development, Programming</dd>
                </div>
                <div>
                    <dt style="color:#141414">Built with</dt>
                    <dd style="color:#141414">Unreal Engine 5, VR</dd>
                </div>
                <div>
                    <dt style="color:#141414">Client</dt>
                    <dd style="color:#141414">Coursework</dd>
                </div>
            </dl>
        </div>
        <div class="jb-detail-story-module">
            <div class="jb-detail-intro-copy">
                <p style="color:#141414">A VR rhythm game made in Unreal Engine 5 with a team of two in 2024.</p>
                <p style="color:#141414">I programmed the entire game, including the dragon controller.</p>
            </div>
        </div>
    </section>
    <section class="jb-case-chapter" id="dragon" aria-labelledby="dragon-title">
        <div class="jb-case-copy">
            <h2 id="dragon-title">Steering the dragon</h2>
            <p>The dragon controller was the hardest part of the game to solve. The player’s hand positions needed to control which way the dragon moved.</p>
            <p>I surrounded the player with overlapping collision boxes. When a hand entered one of those areas, I applied force to move the dragon in that direction.</p>
        </div>
        <div class="jb-case-visual">
            <figure class="jb-project-media" style="--media-background:#101010">
                <video src="/assets/work/vr-rhythm-game/dragon-flight.mp4" poster="/assets/work/vr-rhythm-game/dragon-flight-poster.webp" aria-label="Hand-controlled dragon flight through the stone rings." muted autoplay loop playsinline preload="metadata" disablepictureinpicture disableremoteplayback tabindex="-1"></video>
            </figure>
        </div>
    </section>

<?php require __DIR__ . '/../../includes/project-footer.php'; ?>
