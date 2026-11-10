<?php
// Edit this project’s page title, description, colors, and HTML below.
$pageTitle = 'Everybody Golf';
$pageDescription = 'Everybody Golf is a Mac golf game with an iPhone motion-controller companion. I created the game and companion app.';
$theme = [
    'canvas' => '#f0e7d3',
    'ink' => '#141414',
    'accent' => '#007d67',
    'rail' => '#23883c',
    'footer_background' => '#f0e7d3',
    'footer_text' => '#141414',
    'footer_word' => '#007d67',
];

require __DIR__ . '/../../includes/project-header.php';
?>

    <section class="jb-detail-hero" aria-labelledby="project-title" style="--hero-position: center; --hero-fit: cover; --hero-overlay: #000000; --hero-overlay-opacity: 0.35; --hero-text: #ffffff">
        <video src="/assets/work/everybody-golf/hover-preview.mp4" poster="/assets/work/everybody-golf/hover-preview-poster.webp" muted autoplay loop playsinline preload="metadata" disablepictureinpicture disableremoteplayback tabindex="-1"></video>
        <div class="jb-detail-hero-shade" aria-hidden="true"></div>
        <div class="jb-detail-hero-copy">
            <p style="color:#ffffff">Games · Beta</p>
            <h1 id="project-title" style="color:#ffffff">Everybody Golf</h1>
        </div>
    </section>
    <section class="jb-detail-intro" style="--intro-background: #f0e7d3; --intro-text: #141414">
        <div class="jb-detail-info-module">
            <h2 style="color:#141414">Everybody Golf</h2>
            <dl class="jb-detail-meta">
                <div>
                    <dt style="color:#141414">Role</dt>
                    <dd style="color:#141414">Sole Creator</dd>
                </div>
                <div>
                    <dt style="color:#141414">Disciplines</dt>
                    <dd style="color:#141414">Game development, Programming</dd>
                </div>
                <div>
                    <dt style="color:#141414">Built with</dt>
                    <dd style="color:#141414">Unreal Engine, iPhone motion input</dd>
                </div>
                <div>
                    <dt style="color:#141414">Client</dt>
                    <dd style="color:#141414">Independent project</dd>
                </div>
            </dl>
        </div>
        <div class="jb-detail-story-module">
            <div class="jb-detail-intro-copy">
                <p style="color:#141414">Everybody Golf is a Mac golf game with an iPhone motion-controller companion. I created the game and companion app.</p>
                <p style="color:#141414">The available build is a Mac beta. Players connect their phones on the same local network, join with a room code, and use the phone to swing.</p>
            </div>
        </div>
    </section>
<nav class="jb-case-nav" aria-label="Project chapters">
    <a href="#control">Phone control</a>
    <a href="#flight">Ball flight</a>
    <a href="#rolling">Rolling</a>
    <a href="#input">Swing input</a>
</nav>
<section class="jb-case-chapter" id="control" aria-labelledby="control-title">
    <div class="jb-case-copy">
        <h2 id="control-title">Phone-controlled golf</h2>
        <p>I wanted to feel in control of the ball. I created both the Mac game and the iPhone controller, so the swing on the phone and the shot on screen had to work together.</p>
        <p>Early shots did not behave like golf balls. They did not fly naturally, and the ball could keep rolling for far too long.</p>
    </div>
    <div class="jb-case-visual">
        <section class="jb-media-pair is-gameplay-phone" aria-label="Project media" data-synced-videos>
            <figure>
                <video src="/assets/work/everybody-golf/gameplay-aligned.mp4" poster="/assets/work/everybody-golf/gameplay-aligned-poster.webp" aria-label="Everybody Golf Mac gameplay" muted playsinline preload="metadata" disablepictureinpicture disableremoteplayback tabindex="-1">
                </video>
            </figure>
            <figure>
                <video src="/assets/work/everybody-golf/phone-controller-aligned.mp4" poster="/assets/work/everybody-golf/phone-controller-aligned-poster.webp" aria-label="Everybody Golf iPhone controller" muted playsinline preload="metadata" disablepictureinpicture disableremoteplayback tabindex="-1">
                </video>
            </figure>
        </section>
    </div>
</section>

<section class="jb-case-chapter" id="flight" aria-labelledby="flight-title">
    <div class="jb-case-copy">
        <h2 id="flight-title">Ball flight</h2>
        <p>Much of the work was a repeated visual test: take a shot, look at how it moved, change the parameters, and try again.</p>
        <p>Adding different launch heights and ball trails made the impact on trajectory much clearer. I could see what each change was doing, and that helped the ball feel natural.</p>
        <p>This is the project’s C++ calculation for airflow, drag, lift, and spin decay.</p>
    </div>
    <div class="jb-case-visual">
        <?php jb_source_viewer(['golf-flight', 'golf-spin'], 'Ball flight', false, false); ?>
    </div>
</section>

<section class="jb-case-chapter" id="rolling" aria-labelledby="rolling-title">
    <div class="jb-case-copy">
        <h2 id="rolling-title">Stopping the roll</h2>
        <p>The ball rolling forever was one of the early problems. I kept testing and adjusting the physics until the motion felt right.</p>
        <p>The rolling-resistance function works against horizontal velocity. It limits that force to what is needed to stop in the current frame, rather than pushing the ball back in the opposite direction.</p>
    </div>
    <div class="jb-case-visual">
        <?php jb_source_viewer(['golf-rolling'], 'Rolling resistance', false, false); ?>
    </div>
</section>

<section class="jb-case-chapter" id="input" aria-labelledby="input-title">
    <div class="jb-case-copy">
        <h2 id="input-title">Reading the swing</h2>
        <p>The controller’s motion also needs to become something the game can use. This function converts the iPhone sample into Unreal coordinates and compares it with the calibrated orientation.</p>
        <p>It then measures the rotation rate along the swing axis and keeps track of whether the swing button is held.</p>
    </div>
    <div class="jb-case-visual">
        <?php jb_source_viewer(['golf-phone'], 'Phone swing input', false, false); ?>
    </div>
</section>


<?php require __DIR__ . '/../../includes/project-footer.php'; ?>
