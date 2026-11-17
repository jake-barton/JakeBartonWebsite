<?php
$pageTitle = 'BBQ';
$pageDescription = 'A first-person stealth horror game made in Godot, inspired by Fried Green Tomatoes. Play the browser version with a keyboard and mouse.';
$skipPageLoader = true;
$pageStyles = ['/assets/css/bbq.css?v=20261117-bbq'];
$theme = [
    'canvas' => '#e8dfcc', 'ink' => '#18160f', 'accent' => '#7f3e29',
    'rail' => '#23883c', 'footer_background' => '#1b1a15',
    'footer_text' => '#e6d7b3', 'footer_word' => '#b8a774',
];
require __DIR__ . '/../../includes/project-header.php';
?>
<section class="jb-detail-hero" aria-labelledby="project-title" style="--hero-position:center;--hero-fit:cover;--hero-overlay:#000000;--hero-overlay-opacity:0.2;--hero-text:#ffffff">
    <img src="/assets/work/bbq/meal.webp" alt="The opening meal at the cafe in BBQ." width="1280" height="720">
    <div class="jb-detail-hero-shade" aria-hidden="true"></div>
    <div class="jb-detail-hero-copy"><p>Godot · First-person horror</p><h1 id="project-title">BBQ</h1></div>
</section>
<section class="jb-detail-intro" style="--intro-background:#e8dfcc;--intro-text:#18160f">
    <div class="jb-detail-info-module">
        <h2>BBQ</h2>
        <dl class="jb-detail-meta">
            <div><dt>Role</dt><dd>Game Design &amp; Development</dd></div>
            <div><dt>Disciplines</dt><dd>Gameplay, Level Design, Sound</dd></div>
            <div><dt>Built with</dt><dd>Godot 4.5.1, GDScript</dd></div>
            <div><dt>Context</dt><dd>Fiction and Film project</dd></div>
        </dl>
    </div>
    <div class="jb-detail-story-module"><div class="jb-detail-intro-copy">
        <p>BBQ is a first-person stealth horror game inspired by <em>Fried Green Tomatoes</em>. A visit to the Whistle Stop cafe becomes a nightmare: investigate the kitchen, distract the hunter, restore power to the delivery shutter, and escape.</p>
        <p>I assembled the cafe and developed its interactions, hunter behavior, and staged sequences in Godot. The models, textures, audio, and fonts include credited third-party assets. This student interpretation is not an official adaptation of the film.</p>
        <p><a class="jb-fold-underline" href="#play">Play BBQ</a> &nbsp; <a class="jb-fold-underline" href="/assets/work/bbq/credits.txt" target="_blank" rel="noopener">Asset credits</a></p>
    </div></div>
</section>
<section class="jb-bbq-play" id="play" aria-labelledby="bbq-play-title" data-bbq-player>
    <div class="jb-bbq-toolbar">
        <h2 id="bbq-play-title">Play BBQ</h2>
        <div class="jb-bbq-actions">
            <button type="button" data-bbq-fullscreen>Fullscreen</button>

            <a class="jb-fold-underline" href="/games/bbq/?v=6cb567b189e63db5" target="_blank" rel="noopener">Open game in new tab</a>
        </div>
    </div>
    <p class="jb-bbq-input-note">Keyboard and mouse. Contains horror, blood, and sudden sounds.</p>
    <div class="jb-game-viewport jb-bbq-stage" data-bbq-stage data-lenis-prevent>
        <iframe src="/games/bbq/?v=6cb567b189e63db5" title="Play BBQ — first-person horror game" allow="fullscreen; autoplay" allowfullscreen></iframe>
    </div>
    <p class="jb-bbq-status" role="status" aria-live="polite" data-bbq-status></p>
    <details class="jb-bbq-controls"><summary>Controls</summary>
        <dl><div><dt>WASD / mouse</dt><dd>Move / look</dd></div><div><dt>Shift / C</dt><dd>Sprint / crouch</dd></div><div><dt>Q / F</dt><dd>Flashlight / throw a dish</dd></div><div><dt>E / hold E</dt><dd>Interact / wind the shutter</dd></div><div><dt>Tab / Esc</dt><dd>Case notes / pause and release the mouse</dd></div><div><dt>Enter / R</dt><dd>Start or skip the opening / retry after capture</dd></div></dl>
    </details>
    <noscript><p>JavaScript is required to run the browser game.</p></noscript>
</section>
<figure class="jb-project-media" style="--media-background:#171610"><img src="/assets/work/bbq/kitchen_entrance.webp" alt="The kitchen entrance and blood trail inside BBQ's cafe." width="1280" height="720" loading="lazy"></figure>
<script src="/assets/js/bbq-game.js?v=20261117-bbq" defer></script>
<?php require __DIR__ . '/../../includes/project-footer.php'; ?>
