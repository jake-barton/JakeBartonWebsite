<?php
// Edit this project’s page title, description, colors, and HTML below.
$pageTitle = 'Volleyball Game';
$pageDescription = 'A volleyball game made in Unreal Engine, with custom movement and ball interactions.';
$theme = [
    'canvas' => '#f0e7d3',
    'ink' => '#141414',
    'accent' => '#183f78',
    'rail' => '#23883c',
    'footer_background' => '#f0e7d3',
    'footer_text' => '#141414',
    'footer_word' => '#183f78',
];

require __DIR__ . '/../../includes/project-header.php';
?>

    <section class="jb-detail-hero" aria-labelledby="project-title" style="--hero-position: center; --hero-fit: cover; --hero-overlay: #000000; --hero-overlay-opacity: 0.28; --hero-text: #ffffff">
        <video src="/assets/work/volleyball-game/hover-preview.mp4" poster="/assets/work/volleyball-game/hover-preview-poster.webp" muted autoplay loop playsinline preload="metadata" disablepictureinpicture disableremoteplayback tabindex="-1"></video>
        <div class="jb-detail-hero-shade" aria-hidden="true"></div>
        <div class="jb-detail-hero-copy">
            <p style="color:#ffffff">Unreal Engine · Gameplay programming</p>
            <h1 id="project-title" style="color:#ffffff">Volleyball Game</h1>
        </div>
    </section>
    <section class="jb-detail-intro" style="--intro-background: #f0e7d3; --intro-text: #141414">
        <div class="jb-detail-info-module">
            <h2 style="color:#141414">Volleyball Game</h2>
            <dl class="jb-detail-meta">
                <div>
                    <dt style="color:#141414">Role</dt>
                    <dd style="color:#141414">Gameplay Programmer</dd>
                </div>
                <div>
                    <dt style="color:#141414">Disciplines</dt>
                    <dd style="color:#141414">Gameplay Programming, Movement</dd>
                </div>
                <div>
                    <dt style="color:#141414">Built with</dt>
                    <dd style="color:#141414">Unreal Engine 5.5, Blueprints</dd>
                </div>
                <div>
                    <dt style="color:#141414">Client</dt>
                    <dd style="color:#141414">Coursework</dd>
                </div>
            </dl>
        </div>
        <div class="jb-detail-story-module">
            <div class="jb-detail-intro-copy">
                <p style="color:#141414">A volleyball game made in Unreal Engine, with custom movement and ball interactions.</p>
                <p style="color:#141414">I wanted volleyball to be easy to pick up while still giving the player a sense of movement and impact. I worked on the camera, movement, and ball interactions.</p>
            </div>
        </div>
    </section>
<nav class="jb-case-nav" aria-label="Project chapters">
    <a href="#movement">Movement</a>
    <a href="#camera">Camera and aiming</a>
    <a href="#jump">Jump input</a>
    <a href="#mobility">Dash and dive</a>
</nav>
<section class="jb-case-chapter" id="movement" aria-labelledby="movement-title">
    <div class="jb-case-copy">
        <h2 id="movement-title">Playing volleyball</h2>
        <p>I hadn’t seen volleyball handled well in many games outside motion controls, like Nintendo Switch. I wanted to take a stab at it: keep it easy on the player, but make each movement and hit feel physical.</p>
        <p>I tried moving between first- and third-person views to help players track the ball and feel the change when it reached their side of the court.</p>
    </div>
    <div class="jb-case-visual">
        <figure class="jb-project-media" style="--media-background:#101010">
            <video src="/assets/work/volleyball-game/gameplay-video.mp4" poster="/assets/work/volleyball-game/gameplay-video-poster.webp" aria-label="Volleyball gameplay showing serving, movement, and ball interactions." muted autoplay loop playsinline preload="metadata" disablepictureinpicture disableremoteplayback tabindex="-1">
            </video>
        </figure>
    </div>
</section>

<section class="jb-case-chapter" id="camera" aria-labelledby="camera-title">
    <div class="jb-case-copy">
        <h2 id="camera-title">Tracking the ball and aiming</h2>
        <p>The camera needed the most testing. Following the ball helped players keep it in view, but then they couldn’t aim their shots.</p>
        <p>I made shots aim toward the player’s momentum. That let movement carry the aiming while the camera tracked the ball.</p>
        <p>The saved Blueprint here handles the view separately: it smoothly follows the ball without rotating gameplay aim.</p>
    </div>
    <div class="jb-case-visual">
        <?php jb_source_viewer(['volley-camera'], 'Ball tracking', false, false); ?>
    </div>
</section>

<section class="jb-case-chapter" id="jump" aria-labelledby="jump-title">
    <div class="jb-case-copy">
        <h2 id="jump-title">Jump input</h2>
        <p>The movement inputs also need to be easy to act on. A jump press creates one short-lived request. The graph sets JumpHeld, records when the buffer expires, and calls Try Buffered Jump.</p>
        <p>The graph’s own comment makes the boundary clear: one short request from a press, rather than repeated submissions while the button is held.</p>
    </div>
    <div class="jb-case-visual">
        <?php jb_source_viewer(['volley-jump'], 'Jump input', false, false); ?>
    </div>
</section>

<section class="jb-case-chapter" id="mobility" aria-labelledby="mobility-title">
    <div class="jb-case-copy">
        <h2 id="mobility-title">Dash or dive</h2>
        <p>The same mobility input routes to different actions based on the character’s state.</p>
        <p>RequestMobilityAction checks whether the character is falling. It requests a dive in the air and a dash on the ground.</p>
    </div>
    <div class="jb-case-visual">
        <?php jb_source_viewer(['volley-mobility', 'volley-dive'], 'Mobility routing', false, false); ?>
    </div>
</section>


<?php require __DIR__ . '/../../includes/project-footer.php'; ?>
