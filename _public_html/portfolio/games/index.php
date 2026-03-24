<?php
// Games Portfolio
$pageTitle = "Game Projects";

$games = [
    [
        'id' => 1,
        'title' => 'Phase Runner',
        'description' => 'A fast-paced endless runner where you phase through dimensions to avoid obstacles. Built with Godot Engine.',
        'year' => '2024',
        'tech' => ['Godot', 'GDScript', 'Web Export'],
        'thumbnail' => 'phase-runner/PhaseRunnerWeb.png',
        'playLink' => 'https://clervercarpet99.itch.io/phase-runner',
        'external' => true,
        'controls' => 'Keyboard: Arrow Keys or WASD to move'
    ],
    [
        'id' => 2,
        'title' => "Captain's Log",
        'description' => 'Pixel art tileset and sprite design for a retro-style adventure game. Custom hand-crafted tiles and character sprites.',
        'year' => '2024',
        'tech' => ['Pixel Art', 'Game Design', 'Sprites & Tiles'],
        'thumbnail' => '../captainslogtruetiles.png',
        'playLink' => null,
        'isArt' => true
    ],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - Jake Barton</title>
    <link rel="stylesheet" href="../../assets/css/base.css">
    <link rel="stylesheet" href="../../assets/css/animations.css">
    <link rel="stylesheet" href="../../assets/css/components.css">
</head>
<body>

  <!-- Scroll progress line -->
  <div id="scroll-progress" style="position:fixed;top:0;left:0;height:2px;width:0%;background:var(--accent);z-index:100001;transition:width 0.1s linear;pointer-events:none"></div>

  <!-- Ambient cursor glow -->
  <div id="cursor-glow" style="position:fixed;top:0;left:0;width:420px;height:420px;border-radius:50%;background:radial-gradient(circle,rgba(255,255,255,0.05) 0%,transparent 70%);pointer-events:none;z-index:0;transform:translate(-50%,-50%);transition:opacity 0.3s ease;opacity:0"></div>  </div>
    <header class="site-nav" id="site-nav">
        <a href="../../index.php" class="nav-logo">JB</a>
        <nav class="nav-links">
            <a href="../../index.php#about">About</a>
            <a href="../../index.php#skills">Skills</a>
            <a href="../">Portfolio</a>
            <a href="../../assets/Jake%20Barton%20-%20Resume.pdf" class="btn btn-secondary btn-sm" download>Resume</a>
            <a href="../../index.php#contact">Contact</a>
        </nav>
        <button class="nav-toggle" aria-label="Open menu">
            <span></span><span></span><span></span>
        </button>
    </header>

    <div class="stagger-menu-overlay" id="stagger-menu">
        <nav>
            <a href="../../index.php">Home</a>
            <a href="../">Portfolio</a>
            <a href="../../index.php#about">About</a>
            <a href="../../index.php#contact">Contact</a>
            <a href="../../assets/Jake%20Barton%20-%20Resume.pdf" download>Resume</a>
        </nav>
    </div>

    <main class="site-content">

        <!-- Hero -->
        <section class="section" style="padding-top: 140px; padding-bottom: 60px; text-align: center;">
            <div style="max-width: 800px; margin: 0 auto; padding: 0 var(--container-pad);">
                <p class="eyebrow hero-eyebrow">Portfolio → Playable Games</p>
                <h1 class="reveal" style="font-family:var(--font-display);font-size:clamp(2.5rem,6vw,4.5rem);font-weight:700;letter-spacing:-0.02em;line-height:1.1;color:var(--text);margin-bottom:1rem">Game Projects</h1>
                <div class="divider reveal" style="margin:1.5rem auto;max-width:80px"></div>
                <p class="reveal" style="color: var(--text-muted); font-size: 1.1rem; margin-top: 0.5rem; max-width: 700px; margin-left: auto; margin-right: auto; line-height:1.75;transition-delay:0.12s">
                    Built with <strong style="color: var(--accent-light);">Godot</strong>,
                    <strong style="color: var(--accent-light);">Unreal Engine 5</strong>,
                    and <strong style="color: var(--accent-light);">Unity</strong> — click to play right here.
                </p>
            </div>
        </section>

        <!-- Games List -->
        <section class="section-sm">
            <div style="max-width: 1100px; margin: 0 auto; padding: 0 var(--container-pad);">
                <div class="section-header reveal" style="margin-bottom:2rem">
                    <span class="eyebrow">Playable</span>
                    <h2>All Games</h2>
                </div>
                <?php foreach ($games as $game): ?>
                <div class="glass-card tilt-card reveal" style="display: grid; grid-template-columns: 380px 1fr; gap: 0; overflow: hidden; margin-bottom: 40px; padding: 0;">
                    <div style="position: relative; overflow: hidden; min-height: 280px;">
                        <img src="<?php echo $game['thumbnail']; ?>"
                             alt="<?php echo htmlspecialchars($game['title']); ?>"
                             style="width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.4s ease;">
                    </div>
                    <div style="padding: 40px;">
                        <p class="eyebrow"><?php echo $game['year']; ?></p>
                        <h3 style="font-size: 2.2rem; margin-bottom: 1rem;"><?php echo strtoupper(htmlspecialchars($game['title'])); ?></h3>
                        <p style="color: var(--text-muted); line-height: 1.8; margin-bottom: 1.5rem;"><?php echo htmlspecialchars($game['description']); ?></p>

                        <div style="display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 1.5rem;" class="stagger-pop">
                            <?php foreach ($game['tech'] as $tech): ?>
                                <span class="skill-pill"><?php echo $tech; ?></span>
                            <?php endforeach; ?>
                        </div>

                        <?php if (!empty($game['controls'])): ?>
                        <p style="color: var(--text-muted); font-size: 0.9rem; padding: 12px 16px; border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; margin-bottom: 1.5rem;">
                            <strong style="color: var(--accent);">Controls:</strong> <?php echo $game['controls']; ?>
                        </p>
                        <?php endif; ?>

                        <?php if (!empty($game['playLink'])): ?>
                        <a href="<?php echo $game['playLink']; ?>" class="btn-primary magnetic" <?php if (!empty($game['external'])): ?>target="_blank"<?php endif; ?>>Play Now →</a>
                        <?php elseif (isset($game['isArt']) && $game['isArt']): ?>
                        <span class="btn-secondary" style="opacity: 0.6; cursor: default;">Game Art</span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>

                <div class="glass-card reveal" style="text-align: center; padding: 40px;">
                    <p style="color: var(--text-muted);">More games coming soon — check back regularly for new projects.</p>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="section-sm" style="text-align: center;">
            <a href="../" class="btn-secondary magnetic reveal">← Back to Portfolio</a>
        </section>

    </main>

    <footer class="site-footer">
        <div class="container">
            <div class="footer-inner">
                <span class="footer-copy">&copy; <?php echo date('Y'); ?> Jake Barton. All rights reserved.</span>
                <div class="footer-socials">
                    <a href="https://www.linkedin.com/in/jakebartoncreative" target="_blank" class="btn-icon" aria-label="LinkedIn">in</a>
                    <a href="https://instagram.com/jakebarton13" target="_blank" class="btn-icon" aria-label="Instagram">IG</a>
                </div>
            </div>
        </div>
    </footer>

    <canvas id="beams-canvas"></canvas>
    <script src="../../assets/js/beams-bg.js"></script>
    <script src="../../assets/js/cursor-ribbons.js"></script>
    <script src="../../assets/js/fuzzy-text.js"></script>
    <script src="../../assets/js/effects-stylekit.js"></script>
    <script>
      (function(){var b=document.getElementById('scroll-progress');if(!b)return;window.addEventListener('scroll',function(){var s=window.scrollY,t=document.documentElement.scrollHeight-window.innerHeight;b.style.width=(t>0?(s/t)*100:0)+'%';},{passive:true});})();
      (function(){var g=document.getElementById('cursor-glow');if(!g||window.matchMedia('(pointer:coarse)').matches)return;var cx=0,cy=0,tx=0,ty=0;document.addEventListener('mousemove',function(e){tx=e.clientX;ty=e.clientY;g.style.opacity='1';});document.addEventListener('mouseleave',function(){g.style.opacity='0';});function lerp(a,b,t){return a+(b-a)*t;}(function loop(){cx=lerp(cx,tx,0.07);cy=lerp(cy,ty,0.07);g.style.left=cx+'px';g.style.top=cy+'px';requestAnimationFrame(loop);})();})();
    </script>

</body>
</html>
