<?php
// Edit this project’s page title, description, colors, and HTML below.
$pageTitle = 'Mode 7 Engine';
$pageDescription = 'A JavaScript kart-racing engine that runs in the browser. I built the perspective renderer, player controls, AI racers, items, collisions, menus, and race logic.';
$theme = [
    'canvas' => '#f0e7d3',
    'ink' => '#141414',
    'accent' => '#174ec2',
    'rail' => '#23883c',
    'footer_background' => '#f0e7d3',
    'footer_text' => '#141414',
    'footer_word' => '#174ec2',
];

require __DIR__ . '/../../includes/project-header.php';
?>

    <section class="jb-detail-hero" aria-labelledby="project-title" style="--hero-position: center; --hero-fit: cover; --hero-overlay: #000000; --hero-overlay-opacity: 0.2; --hero-text: #ffffff">
        <video src="/assets/home/media/mode7.mp4" poster="/assets/work/mode-7-engine/preview-poster.webp" muted autoplay loop playsinline preload="metadata" disablepictureinpicture disableremoteplayback tabindex="-1"></video>
        <div class="jb-detail-hero-shade" aria-hidden="true"></div>
        <div class="jb-detail-hero-copy">
            <p style="color:#ffffff">Rendering experiment · JavaScript</p>
            <h1 id="project-title" style="color:#ffffff">Mode 7 Engine</h1>
        </div>
    </section>
    <section class="jb-detail-intro" style="--intro-background: #f0e7d3; --intro-text: #141414">
        <div class="jb-detail-info-module">
            <h2 style="color:#141414">Mode 7 Engine</h2>
            <dl class="jb-detail-meta">
                <div>
                    <dt style="color:#141414">Role</dt>
                    <dd style="color:#141414">Engine Programmer</dd>
                </div>
                <div>
                    <dt style="color:#141414">Disciplines</dt>
                    <dd style="color:#141414">Rendering, Gameplay Systems, UI</dd>
                </div>
                <div>
                    <dt style="color:#141414">Built with</dt>
                    <dd style="color:#141414">JavaScript, Canvas, ES Modules</dd>
                </div>
                <div>
                    <dt style="color:#141414">Client</dt>
                    <dd style="color:#141414">Independent Project</dd>
                </div>
            </dl>
        </div>
        <div class="jb-detail-story-module">
            <div class="jb-detail-intro-copy">
                <p style="color:#141414">A JavaScript kart-racing engine that runs in the browser. I built the perspective renderer, player controls, AI racers, items, collisions, menus, and race logic.</p>
                <p style="color:#141414">The playable demo uses Mario Kart characters and track artwork. The code recreates the game’s rendering and gameplay systems.</p>
            </div>
        </div>
    </section>
<nav class="jb-case-nav" aria-label="Project chapters">
    <a href="#projection">Projection</a>
    <a href="#collision">Collision masks</a>
    <a href="#play">Play</a>
</nav>
<section class="jb-case-chapter" id="projection" aria-labelledby="projection-title">
    <div class="jb-case-copy">
        <h2 id="projection-title">Rendering a 2D track</h2>
        <p>Growing up, I thought this version of Mario Kart was truly 3D. Finding out it was a 2D game made me want to understand how it worked. Solving that rendering math in JavaScript was the reward for this project.</p>
        <p>The renderer samples a 2D track image and projects it into the camera view one row at a time. Camera height and distance from the horizon control where each row lands on the track.</p>
        <p>It keeps the image buffer between frames. Trigonometry is calculated once per frame, and values shared by a row stay outside the inner pixel loop.</p>
    </div>
    <div class="jb-case-visual">
        <?php jb_source_viewer(['mode7-ground'], 'Track rendering', false, false); ?>
    </div>
</section>

<section class="jb-case-chapter" id="collision" aria-labelledby="collision-title">
    <div class="jb-case-copy">
        <h2 id="collision-title">Collision on a hidden map</h2>
        <p>Collision was the hardest part. I had to work out where the racers and walls were on the track while the camera showed them in perspective.</p>
        <p>I drew masks on matching maps hidden from the main view: directions for the AI, collision areas for the karts, and walls that push a racer back when hit.</p>
        <p>The code reads pixel colors from the track mask, then uses that result to apply a wall bounce or off-track drag. The collision check stays on the 2D map while the renderer handles the camera view.</p>
    </div>
    <div class="jb-case-visual">
        <?php jb_source_viewer(['mode7-mask', 'mode7-collisions'], 'Collision masks', false, false); ?>
    </div>
</section>

<section class="jb-case-chapter jb-case-play" id="play" aria-label="Playable game">
    <section class="jb-game" data-game-embed aria-label="Mario Kart racing demo">
        <div class="jb-game-viewport" data-game-viewport data-lenis-prevent>
            <iframe src="/games/mario-kart/" title="Playable Mario Kart racing demo" allow="fullscreen" allowfullscreen>
            </iframe>
        </div>
    </section>
    <section class="jb-game-screens" aria-label="Game screenshots">
        <figure>
            <img src="/assets/work/mode-7-engine/character-select.png" alt="Character selection in the browser game, with Mario preview and the eight-racer grid." width="1020" height="896" loading="lazy">
        </figure>
        <figure>
            <img src="/assets/work/mode-7-engine/race-start.png" alt="Live Mario Circuit gameplay with the perspective road, AI racers, race timer and minimap." width="1020" height="896" loading="lazy">
        </figure>
    </section>
</section>


<?php require __DIR__ . '/../../includes/project-footer.php'; ?>
