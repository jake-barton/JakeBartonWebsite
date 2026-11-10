<?php
// Edit this project’s page title, description, colors, and HTML below.
$pageTitle = 'Ding Dong';
$pageDescription = 'A two-player co-op horror game. I led programming and created the first-person hands and NPC artwork.';
$theme = [
    'canvas' => '#f0e7d3',
    'ink' => '#141414',
    'accent' => '#4c7e24',
    'rail' => '#23883c',
    'footer_background' => '#101510',
    'footer_text' => '#f0e7d3',
    'footer_word' => '#90e72c',
];

require __DIR__ . '/../../includes/project-header.php';
?>

    <section class="jb-detail-hero" aria-labelledby="project-title" style="--hero-position: center; --hero-fit: contain; --hero-overlay: #000000; --hero-overlay-opacity: 0.18; --hero-text: #ffffff">
        <video src="/assets/work/ding-dong/hover-preview.mp4" poster="/assets/work/ding-dong/hover-preview-poster.webp" muted autoplay loop playsinline preload="metadata" disablepictureinpicture disableremoteplayback tabindex="-1"></video>
        <div class="jb-detail-hero-shade" aria-hidden="true"></div>
        <div class="jb-detail-hero-copy">
            <p style="color:#ffffff">Game development · Character artwork</p>
            <h1 id="project-title" style="color:#ffffff">Ding Dong</h1>
        </div>
    </section>
    <section class="jb-detail-intro" style="--intro-background: #f0e7d3; --intro-text: #141414">
        <div class="jb-detail-info-module">
            <h2 style="color:#141414">Ding Dong</h2>
            <dl class="jb-detail-meta">
                <div>
                    <dt style="color:#141414">Role</dt>
                    <dd style="color:#141414">Lead Programmer &amp; Artist</dd>
                </div>
                <div>
                    <dt style="color:#141414">Disciplines</dt>
                    <dd style="color:#141414">Gameplay programming, Multiplayer, UI, Hands &amp; NPC artwork</dd>
                </div>
                <div>
                    <dt style="color:#141414">Built with</dt>
                    <dd style="color:#141414">Unreal Engine, Blueprints, C++</dd>
                </div>
                <div>
                    <dt style="color:#141414">Client</dt>
                    <dd style="color:#141414">Studio495 · Samford student game project</dd>
                </div>
            </dl>
        </div>
        <div class="jb-detail-story-module">
            <div class="jb-detail-intro-copy">
                <p style="color:#141414">Ding Dong is a two-player co-op horror game made with the Studio495 team. One player orders food from inside a house while the other handles deliveries.</p>
                <p style="color:#141414">I built the phone, ordering and delivery systems, hunger mechanics, player interactions, and multiplayer menus. I also created the first-person hands and NPC artwork.</p>
            </div>
        </div>
    </section>
<nav class="jb-case-nav" aria-label="Project chapters">
    <a href="#co-op">Co-op</a>
    <a href="#phone">Phone</a>
    <a href="#gps">GPS</a>
    <a href="#map">Map alignment</a>
    <a href="#artwork">Artwork</a>
</nav>
<section class="jb-case-chapter" id="co-op" aria-labelledby="co-op-title">
    <div class="jb-case-copy">
        <h2 id="co-op-title">Two-player co-op</h2>
        <p>I wanted to push my multiplayer programming skills, especially Unreal’s replication system. One player stays inside ordering food, while the other handles deliveries.</p>
        <p>That split also created a comedic effect while we were working on the game, and the team enjoyed it. The phone became the connection between the two players.</p>
    </div>
    <div class="jb-case-visual">
        <figure class="jb-project-media" style="--media-background:#101010">
            <video src="/assets/work/ding-dong/phone-and-multiplayer.mp4" poster="/assets/work/ding-dong/phone-and-multiplayer-poster.webp" aria-label="Two-player lobby, first-person hands, in-game phone, food ordering, and messaging." muted autoplay loop playsinline preload="metadata" disablepictureinpicture disableremoteplayback tabindex="-1">
            </video>
        </figure>
    </div>
</section>

<section class="jb-case-chapter" id="phone" aria-labelledby="phone-title">
    <div class="jb-case-copy">
        <h2 id="phone-title">Phone replication</h2>
        <p>The hardest part was getting texts to appear correctly on the other player’s phone. Tracing that problem took a long time.</p>
        <p>The saved project uses both the BPI_Telephony interface and an event dispatcher. SendMessage passes the message to a reliable server RPC. The server records it in MessageLog, then a multicast calls NewMessageReceived. The phone widget binds to that dispatcher to update the screen.</p>
    </div>
    <div class="jb-case-visual">
        <?php jb_source_viewer(['ding-messages'], 'Phone replication', false, false); ?>
    </div>
</section>

<section class="jb-case-chapter" id="gps" aria-labelledby="gps-title">
    <div class="jb-case-copy">
        <h2 id="gps-title">A* in Blueprints</h2>
        <p>I placed road-node Blueprints along the roads throughout the map. The algorithm connects those nodes and searches for the best route to the player’s destination.</p>
        <p>Building the A* algorithm in Blueprints took a week. The length and complexity of the graph made it one of the most difficult systems I worked on.</p>
        <p>The search grew into a 116-node graph, with a separate helper for choosing the next road node by its F score.</p>
    </div>
    <div class="jb-case-visual">
        <?php jb_source_viewer(['ding-astar', 'ding-lowest-score'], 'GPS search', false, false); ?>
    </div>
</section>

<section class="jb-case-chapter" id="map" aria-labelledby="map-title">
    <div class="jb-case-copy">
        <h2 id="map-title">Drawing the route</h2>
        <p>Once the route worked, drawing it correctly on the phone became the main problem. I spent much of that time tuning the map offset so the drawn path lined up with the roads.</p>
        <p>WorldToMapPixel is the conversion between the road nodes’ world positions and the coordinates used by the map. It is a separate part of the system from finding the route itself.</p>
    </div>
    <div class="jb-case-visual">
        <?php jb_source_viewer(['ding-map'], 'Phone map coordinates', false, false); ?>
    </div>
</section>

<section class="jb-case-chapter" id="artwork" aria-labelledby="artwork-title">
    <div class="jb-case-copy">
        <h2 id="artwork-title">Hands and NPCs</h2>
        <p>Alongside programming, I created all of the first-person hands and NPC artwork.</p>
        <p>The team’s intro cutscene shows the characters in context. Ding Dong was a Studio495 team project; my role was lead programming and the hands and NPC artwork.</p>
    </div>
    <div class="jb-case-visual">
        <figure class="jb-project-media" style="--media-background:#101010">
            <video src="/assets/work/ding-dong/intro-cutscene.mp4" poster="/assets/work/ding-dong/intro-cutscene-poster.webp" aria-label="Full Ding Dong intro cutscene." muted autoplay loop playsinline preload="metadata" disablepictureinpicture disableremoteplayback tabindex="-1">
            </video>
        </figure>
    </div>
</section>


<?php require __DIR__ . '/../../includes/project-footer.php'; ?>
