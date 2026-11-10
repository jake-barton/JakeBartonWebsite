<?php
// Edit this project’s page title, description, colors, and HTML below.
$pageTitle = 'Phase Runner';
$pageDescription = 'A solo platformer made in Godot in fall 2024. I worked on movement, hazards, scoring, menus, and game-state logic.';
$theme = [
    'canvas' => '#e7e6df',
    'ink' => '#111111',
    'accent' => '#111111',
    'rail' => '#23883c',
    'footer_background' => '#e7e6df',
    'footer_text' => '#111111',
    'footer_word' => '#111111',
];

require __DIR__ . '/../../includes/project-header.php';
?>

    <section class="jb-detail-hero" aria-labelledby="project-title" style="--hero-position: center; --hero-fit: cover; --hero-overlay: #000000; --hero-overlay-opacity: 0.22; --hero-text: #ffffff">
        <video src="/assets/work/phase-runner/movement.mp4" poster="/assets/work/phase-runner/movement-poster.webp" muted autoplay loop playsinline preload="metadata" disablepictureinpicture disableremoteplayback tabindex="-1"></video>
        <div class="jb-detail-hero-shade" aria-hidden="true"></div>
        <div class="jb-detail-hero-copy">
            <p style="color:#ffffff">Godot · Gameplay programming</p>
            <h1 id="project-title" style="color:#ffffff">Phase Runner</h1>
        </div>
    </section>
    <section class="jb-detail-intro" style="--intro-background: #e7e6df; --intro-text: #111111">
        <div class="jb-detail-info-module">
            <h2 style="color:#111111">Phase Runner</h2>
            <dl class="jb-detail-meta">
                <div>
                    <dt style="color:#111111">Role</dt>
                    <dd style="color:#111111">Solo Developer</dd>
                </div>
                <div>
                    <dt style="color:#111111">Disciplines</dt>
                    <dd style="color:#111111">Game Design, Programming, Pixel Art</dd>
                </div>
                <div>
                    <dt style="color:#111111">Built with</dt>
                    <dd style="color:#111111">Godot, GDScript, WebAssembly</dd>
                </div>
                <div>
                    <dt style="color:#111111">Client</dt>
                    <dd style="color:#111111">Independent Project</dd>
                </div>
            </dl>
        </div>
        <div class="jb-detail-story-module">
            <div class="jb-detail-intro-copy">
                <p style="color:#111111">A solo platformer made in Godot in fall 2024. I worked on movement, hazards, scoring, menus, and game-state logic.</p>
                <p style="color:#111111">The game was exported for browser play.</p>
            </div>
        </div>
    </section>
    <nav class="jb-case-nav" aria-label="Project chapters">
        <a href="#movement">Movement</a>
        <a href="#speed">Speed curve</a>
        <a href="#damage">Taking damage</a>
    </nav>

    <section class="jb-case-chapter" id="movement" aria-labelledby="movement-title">
        <div class="jb-case-copy">
            <h2 id="movement-title">Building speed over time</h2>
            <p>I wanted the movement to get faster the longer the player went without taking damage.</p>
            <p>After constant testing, I settled on an exponential curve. Staying clear of hits lets the speed build, so the pace of the run changes as you play.</p>
        </div>
        <div class="jb-case-visual">
            <figure class="jb-project-media" style="--media-background:#101010">
                <video src="/assets/work/phase-runner/movement.mp4" poster="/assets/work/phase-runner/movement-poster.webp" aria-label="Phase Runner gameplay showing movement, combat, and damage." muted autoplay loop playsinline preload="metadata" disablepictureinpicture disableremoteplayback tabindex="-1"></video>
            </figure>
        </div>
    </section>

    <section class="jb-case-chapter" id="speed" aria-labelledby="speed-title">
        <div class="jb-case-copy">
            <h2 id="speed-title">The speed curve</h2>
            <p>The running update moves the current speed toward a maximum each frame. The exponential term uses the frame’s elapsed time to calculate that change.</p>
            <p>This is the running part of the player’s physics update. Dashing is handled separately.</p>
        </div>
        <div class="jb-case-visual">
            <?php jb_source_viewer(['phase-speed'], 'Running speed', false, false); ?>
        </div>
    </section>

    <section class="jb-case-chapter" id="damage" aria-labelledby="damage-title">
        <div class="jb-case-copy">
            <h2 id="damage-title">Losing that speed</h2>
            <p>When the player gets hit, the speed they built up disappears. I wanted that sudden change to make everything feel slower.</p>
            <p>The damage function resets the current speed to the starting pace. It also handles the health loss, hit sound, camera shake, and animation.</p>
        </div>
        <div class="jb-case-visual">
            <?php jb_source_viewer(['phase-damage'], 'Damage and speed reset', false, false); ?>
        </div>
    </section>

<?php require __DIR__ . '/../../includes/project-footer.php'; ?>
