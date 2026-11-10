<?php
// Edit this project’s page title, description, colors, and HTML below.
$pageTitle = 'Motion Graphics';
$pageDescription = 'Bayside, Still Sleepless, and tennis videos made in After Effects.';
$theme = [
    'canvas' => '#f0e7d3',
    'ink' => '#141414',
    'accent' => '#41616a',
    'rail' => '#23883c',
    'footer_background' => '#f0e7d3',
    'footer_text' => '#141414',
    'footer_word' => '#41616a',
];

require __DIR__ . '/../../includes/project-header.php';
?>

    <section class="jb-detail-hero" aria-labelledby="project-title" style="--hero-position: center; --hero-fit: cover; --hero-overlay: #000000; --hero-overlay-opacity: 0.35; --hero-text: #ffffff">
        <video src="/assets/work/bayside-lyric-video/hover-preview.mp4" poster="/assets/work/bayside-lyric-video/hover-preview-poster.webp" muted autoplay loop playsinline preload="metadata" disablepictureinpicture disableremoteplayback tabindex="-1"></video>
        <div class="jb-detail-hero-shade" aria-hidden="true"></div>
        <div class="jb-detail-hero-copy">
            <p style="color:#ffffff">Motion design · Video editing</p>
            <h1 id="project-title" style="color:#ffffff">Motion Graphics</h1>
        </div>
    </section>
    <section class="jb-detail-intro" style="--intro-background: #f0e7d3; --intro-text: #141414">
        <div class="jb-detail-info-module">
            <h2 style="color:#141414">Motion Graphics</h2>
            <dl class="jb-detail-meta">
                <div>
                    <dt style="color:#141414">Role</dt>
                    <dd style="color:#141414">Motion Designer &amp; Video Editor</dd>
                </div>
                <div>
                    <dt style="color:#141414">Disciplines</dt>
                    <dd style="color:#141414">Motion graphics, Video editing</dd>
                </div>
                <div>
                    <dt style="color:#141414">Built with</dt>
                    <dd style="color:#141414">After Effects</dd>
                </div>
                <div>
                    <dt style="color:#141414">Client</dt>
                    <dd style="color:#141414">Coursework</dd>
                </div>
            </dl>
        </div>
        <div class="jb-detail-story-module">
            <div class="jb-detail-intro-copy">
                <p style="color:#141414">I made these three videos in After Effects: a Bayside lyric video, a Still Sleepless animated poster, and a tennis video.</p>
                <p style="color:#141414">Coursework projects. Music and third-party footage and artwork belong to their respective creators.</p>
            </div>
        </div>
    </section>
    <figure class="jb-project-media" style="--media-background:#101010">
        <video src="/assets/work/bayside-lyric-video/video.mp4" poster="/assets/work/bayside-lyric-video/preview-poster.webp" aria-label="Bayside lyric video, edited and animated in After Effects." muted autoplay loop playsinline preload="metadata" disablepictureinpicture disableremoteplayback tabindex="-1"></video>
    </figure>
    <section class="jb-media-pair is-portraits" aria-label="Project media">
        <figure>
            <video src="/assets/work/still-sleepless/video.mp4" poster="/assets/work/still-sleepless/preview-poster.webp" aria-label="Still Sleepless animated music poster." muted playsinline preload="metadata" disablepictureinpicture disableremoteplayback tabindex="-1" autoplay loop></video>
        </figure>
        <figure>
            <video src="/assets/work/tennis-video/video.mp4" poster="/assets/work/tennis-video/preview-poster.webp" aria-label="Tennis footage with editing and motion graphics." muted playsinline preload="metadata" disablepictureinpicture disableremoteplayback tabindex="-1" autoplay loop></video>
        </figure>
    </section>

<?php require __DIR__ . '/../../includes/project-footer.php'; ?>
