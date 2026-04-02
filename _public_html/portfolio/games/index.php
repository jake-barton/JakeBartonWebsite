<?php
// Games Portfolio
$pageTitle = "Playable Games";

$games = [
    [
        'id' => 1,
        'title' => 'Phase Runner',
        'subtitle' => 'Solo-built endless runner · Godot 4',
        'description' => 'A fast-paced endless runner where you phase through dimensions to avoid obstacles. Solo-built with Godot 4 — custom physics, 10+ weapons, procedural level chunks.',
        'year' => '2024',
        'tech' => ['Godot 4', 'GDScript', 'WebGL Export'],
        'thumbnail' => '../../assets/images/phaserunnercover.png',
        'video'     => '../../assets/images/phase-runner-screen.mp4',
        'playLink' => 'phase-runner/',
        'external' => false,
        'controls' => 'Arrow Keys / WASD to move · Z or Space to shoot'
    ],
    [
        'id' => 2,
        'title' => 'Mario Kart Recreation',
        'subtitle' => 'SNES Mode-7 renderer · JavaScript',
        'description' => 'A from-scratch SNES Mode-7 renderer in JavaScript — raycasting, sprite sheets, lap timing, character selection, and full race logic. No game engine.',
        'year' => '2024',
        'tech' => ['JavaScript', 'Mode-7 Rendering', 'Canvas API'],
        'thumbnail' => '../../assets/images/mariokart.png',
        'video'     => '../../assets/images/mariokart.mp4',
        'playLink' => '/MarioKartLatest/',
        'external' => false,
        'controls' => 'Arrow Keys to steer · Z to accelerate · X to brake/reverse'
    ],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> — Jake Barton</title>
    <link rel="icon" type="image/svg+xml" href="../../assets/images/favicon.svg?v=20260325">
    <link rel="stylesheet" href="../../assets/css/base.css">
    <link rel="stylesheet" href="../../assets/css/animations.css">
    <link rel="stylesheet" href="../../assets/css/components.css">
    <style>
    /* ── Accordion stack ─────────────────────────────── */
    .games-stack {
        display: flex;
        flex-direction: column;
        gap: 0;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 var(--spacing-md);
    }

    .game-item {
        position: relative;
        overflow: hidden;
        border-radius: 0;
        border-top: 1px solid rgba(255,255,255,0.08);
        cursor: pointer;
        /* collapsed height */
        height: 88px;
        transition: height 0.65s cubic-bezier(0.16,1,0.3,1);
        background: #0a0a0a;
    }
    .game-item:last-child { border-bottom: 1px solid rgba(255,255,255,0.08); }
    .game-item.is-open {
        height: 540px;
        border-color: rgba(255,255,255,0.18);
    }

    /* ── Media layer (fills full card) ─────────────── */
    .gi-media {
        position: absolute;
        inset: 0;
        z-index: 0;
    }
    .gi-media video,
    .gi-media img {
        width: 100%; height: 100%;
        object-fit: cover;
        opacity: 0;
        transition: opacity 0.5s ease;
    }
    .game-item.is-open .gi-media video,
    .game-item.is-open .gi-media img { opacity: 0.55; }

    .gi-overlay {
        position: absolute; inset: 0; z-index: 1;
        background: linear-gradient(to right, rgba(0,0,0,0.92) 0%, rgba(0,0,0,0.5) 50%, rgba(0,0,0,0.1) 100%);
        opacity: 0;
        transition: opacity 0.5s ease;
    }
    .game-item.is-open .gi-overlay { opacity: 1; }

    /* ── Collapsed row ──────────────────────────────── */
    .gi-row {
        position: relative; z-index: 2;
        display: flex;
        align-items: center;
        gap: 2rem;
        padding: 0 2.5rem;
        height: 88px;
        flex-shrink: 0;
    }
    .gi-num {
        font-family: var(--font-display);
        font-size: 0.68rem;
        font-weight: 800;
        letter-spacing: 0.12em;
        color: var(--text-faint);
        width: 2rem;
        flex-shrink: 0;
    }
    .gi-row-title {
        font-family: var(--font-display);
        font-size: clamp(1.1rem, 2.5vw, 1.6rem);
        font-weight: 800;
        letter-spacing: -0.025em;
        color: var(--text);
        flex: 1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        transition: color 0.25s;
    }
    .gi-row-sub {
        font-size: 0.8rem;
        color: var(--text-faint);
        letter-spacing: 0.04em;
        flex-shrink: 0;
        transition: opacity 0.3s;
    }
    .game-item.is-open .gi-row-sub { opacity: 0; }
    .gi-row-year {
        font-family: var(--font-display);
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        color: var(--text-faint);
        flex-shrink: 0;
    }
    .gi-arrow {
        font-size: 1rem;
        color: var(--text-faint);
        flex-shrink: 0;
        transition: transform 0.4s cubic-bezier(0.16,1,0.3,1), color 0.2s;
    }
    .game-item.is-open .gi-arrow { transform: rotate(45deg); color: var(--text); }

    /* ── Expanded content ───────────────────────────── */
    .gi-body {
        position: relative; z-index: 2;
        padding: 0 2.5rem 2.5rem;
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 2rem;
        align-items: end;
        opacity: 0;
        transform: translateY(16px);
        transition: opacity 0.45s cubic-bezier(0.16,1,0.3,1) 0.15s,
                    transform 0.45s cubic-bezier(0.16,1,0.3,1) 0.15s;
        pointer-events: none;
    }
    .game-item.is-open .gi-body {
        opacity: 1;
        transform: none;
        pointer-events: auto;
    }
    .gi-desc {
        font-size: 1rem;
        color: rgba(255,255,255,0.7);
        line-height: 1.75;
        max-width: 560px;
        margin-bottom: 1.25rem;
    }
    .gi-tags { display: flex; gap: 0.4rem; flex-wrap: wrap; margin-bottom: 1.25rem; }
    .gi-controls {
        font-size: 0.8rem;
        color: var(--text-faint);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 8px;
        padding: 0.6rem 1rem;
        margin-bottom: 1.5rem;
        display: inline-block;
    }
    .gi-controls strong { color: rgba(255,255,255,0.6); }
    .gi-play-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        background: var(--text);
        color: var(--bg);
        font-family: var(--font-display);
        font-weight: 800;
        font-size: 0.9rem;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        padding: 0.9rem 2rem;
        border-radius: 99px;
        text-decoration: none;
        transition: transform 0.2s, background 0.2s;
        white-space: nowrap;
    }
    .gi-play-btn:hover { transform: scale(1.04); background: #e8e8e8; }

    /* ── Hero section ───────────────────────────────── */
    .games-hero {
        padding: clamp(8rem,14vw,11rem) var(--spacing-md) clamp(3rem,5vw,4rem);
        max-width: 1200px;
        margin: 0 auto;
    }
    .games-hero h1 {
        font-family: var(--font-display);
        font-size: clamp(3.5rem, 9vw, 7rem);
        font-weight: 800;
        letter-spacing: -0.05em;
        line-height: 0.95;
        margin: 0 0 1.5rem;
    }
    .games-hero h1 em {
        font-style: italic;
        font-family: 'Playfair Display', Georgia, serif;
        font-weight: 400;
        color: var(--text-muted);
    }
    .games-hero p {
        color: var(--text-muted);
        font-size: 1rem;
        max-width: 480px;
        line-height: 1.8;
    }

    @media(max-width:700px) {
        .game-item.is-open { height: 680px; }
        .gi-body { grid-template-columns: 1fr; }
        .gi-row { padding: 0 1.25rem; gap: 1rem; }
        .gi-body { padding: 0 1.25rem 2rem; }
        .gi-row-sub { display: none; }
    }
    </style>
</head>
<body>

  <div id="scroll-progress" style="position:fixed;top:0;left:0;height:2px;width:0%;background:var(--accent);z-index:100001;transition:width 0.1s linear;pointer-events:none"></div>
  <div id="cursor-glow" style="position:fixed;top:0;left:0;width:420px;height:420px;border-radius:50%;background:radial-gradient(circle,rgba(255,255,255,0.05) 0%,transparent 70%);pointer-events:none;z-index:0;transform:translate(-50%,-50%);transition:opacity 0.3s ease;opacity:0"></div>

  <header class="site-nav" id="site-nav">
    <a href="../../index.php" class="nav-logo"><img src="../../assets/images/jb-logo.png" alt="JB" class="nav-logo-img"><span class="nav-logo-text">JB</span></a>
    <nav class="nav-links">
      <a href="../../index.php#about">About</a>
      <a href="../../index.php#skills">Skills</a>
      <a href="../">Portfolio</a>
      <a href="../../assets/Jake%20Barton%20-%20Resume.pdf" class="btn btn-secondary btn-sm" download>Resume</a>
      <a href="../../index.php#contact">Contact</a>
    </nav>
  </header>

  <main class="site-content">

    <!-- Hero -->
    <div class="games-hero">
      <p style="font-size:0.7rem;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:var(--text-faint);margin-bottom:1rem">Portfolio → Playable Games</p>
      <h1>Play<br><em>Now.</em></h1>
      <p>Two games, playable right in your browser. Click any row to expand, then hit Play.</p>
    </div>

    <!-- Accordion stack -->
    <div class="games-stack">
      <?php foreach ($games as $i => $game): ?>
      <div class="game-item <?php echo $i === 0 ? 'is-open' : ''; ?>" data-index="<?php echo $i; ?>">

        <!-- Media background -->
        <div class="gi-media">
          <?php if (!empty($game['video'])): ?>
          <video autoplay muted loop playsinline preload="metadata">
            <source src="<?php echo $game['video']; ?>" type="video/mp4">
          </video>
          <?php else: ?>
          <img src="<?php echo $game['thumbnail']; ?>" alt="<?php echo htmlspecialchars($game['title']); ?>">
          <?php endif; ?>
        </div>
        <div class="gi-overlay"></div>

        <!-- Collapsed row (always visible) -->
        <div class="gi-row">
          <span class="gi-num">0<?php echo $i + 1; ?></span>
          <span class="gi-row-title"><?php echo htmlspecialchars($game['title']); ?></span>
          <span class="gi-row-sub"><?php echo htmlspecialchars($game['subtitle']); ?></span>
          <span class="gi-row-year"><?php echo $game['year']; ?></span>
          <span class="gi-arrow">+</span>
        </div>

        <!-- Expanded body -->
        <div class="gi-body">
          <div>
            <p class="gi-desc"><?php echo htmlspecialchars($game['description']); ?></p>
            <div class="gi-tags">
              <?php foreach ($game['tech'] as $tech): ?>
              <span class="tag tag-muted"><?php echo $tech; ?></span>
              <?php endforeach; ?>
            </div>
            <?php if (!empty($game['controls'])): ?>
            <div class="gi-controls"><strong>Controls:</strong> <?php echo htmlspecialchars($game['controls']); ?></div>
            <?php endif; ?>
          </div>
          <?php if (!empty($game['playLink'])): ?>
          <a href="<?php echo $game['playLink']; ?>" class="gi-play-btn" <?php if (!empty($game['external'])): ?>target="_blank"<?php endif; ?>>
            ▶ Play Now
          </a>
          <?php endif; ?>
        </div>

      </div>
      <?php endforeach; ?>

      <!-- Coming soon row -->
      <div class="game-item" style="cursor:default;opacity:0.45;">
        <div class="gi-row">
          <span class="gi-num">03</span>
          <span class="gi-row-title">More Coming Soon</span>
          <span class="gi-row-sub">Check back soon</span>
          <span class="gi-row-year">2025</span>
        </div>
      </div>
    </div>

    <div style="text-align:center;padding:4rem 0 6rem;">
      <a href="../" class="btn btn-secondary">← Back to Portfolio</a>
    </div>

  </main>

  <footer class="site-footer">
    <div class="container">
      <div class="footer-inner">
        <span class="footer-copy">&copy; <?php echo date('Y'); ?> Jake Barton. All rights reserved.</span>
        <div class="footer-socials">
          <a href="https://www.linkedin.com/in/jakebartoncreative" target="_blank" class="btn-icon" aria-label="LinkedIn">in</a>
          <a href="https://instagram.com/jakebarton13" target="_blank" class="btn-icon" aria-label="Instagram">IG</a>
          <a href="https://github.com/jake-barton" target="_blank" class="btn-icon" aria-label="GitHub">GH</a>
        </div>
      </div>
    </div>
  </footer>

  <canvas id="beams-canvas"></canvas>
  <script src="../../assets/js/beams-bg.js"></script>
  <script src="../../assets/js/cursor-ribbons.js"></script>
  <script src="../../assets/js/effects-stylekit.js"></script>
  <script src="../../assets/js/staggered-menu.js"></script>
  <script>
    /* Scroll progress */
    (function(){var b=document.getElementById('scroll-progress');if(!b)return;window.addEventListener('scroll',function(){var s=window.scrollY,t=document.documentElement.scrollHeight-window.innerHeight;b.style.width=(t>0?(s/t)*100:0)+'%';},{passive:true});})();
    /* Cursor glow */
    (function(){var g=document.getElementById('cursor-glow');if(!g||window.matchMedia('(pointer:coarse)').matches)return;var cx=0,cy=0,tx=0,ty=0;document.addEventListener('mousemove',function(e){tx=e.clientX;ty=e.clientY;g.style.opacity='1';});document.addEventListener('mouseleave',function(){g.style.opacity='0';});function lerp(a,b,t){return a+(b-a)*t;}(function loop(){cx=lerp(cx,tx,0.07);cy=lerp(cy,ty,0.07);g.style.left=cx+'px';g.style.top=cy+'px';requestAnimationFrame(loop);})();})();

    /* Accordion — click to expand, also auto-expand on scroll into center */
    (function() {
      var items = Array.from(document.querySelectorAll('.game-item[data-index]'));

      function openItem(el) {
        items.forEach(function(item) { item.classList.remove('is-open'); });
        el.classList.add('is-open');
        /* play video on expand */
        var vid = el.querySelector('video');
        if (vid) vid.play();
      }

      /* Click */
      items.forEach(function(item) {
        item.addEventListener('click', function(e) {
          /* don't intercept clicks on the play button */
          if (e.target.closest('.gi-play-btn')) return;
          if (item.classList.contains('is-open')) return;
          openItem(item);
        });
      });

      /* Scroll — expand whichever item's center is closest to viewport center */
      var ticking = false;
      window.addEventListener('scroll', function() {
        if (ticking) return;
        ticking = true;
        requestAnimationFrame(function() {
          ticking = false;
          var mid = window.innerHeight / 2;
          var best = null, bestDist = Infinity;
          items.forEach(function(item) {
            var rect = item.getBoundingClientRect();
            var itemMid = rect.top + rect.height / 2;
            var dist = Math.abs(itemMid - mid);
            if (dist < bestDist) { bestDist = dist; best = item; }
          });
          if (best && !best.classList.contains('is-open') && bestDist < window.innerHeight * 0.45) {
            openItem(best);
          }
        });
      }, { passive: true });
    })();
  </script>

</body>
</html>


$games = [
    [
        'id' => 1,
        'title' => 'Phase Runner',
        'description' => 'A fast-paced endless runner where you phase through dimensions to avoid obstacles. Solo-built with Godot 4 — custom physics, 10+ weapons, procedural level chunks.',
        'year' => '2024',
        'tech' => ['Godot 4', 'GDScript', 'WebGL Export'],
        'thumbnail' => '../../assets/images/phaserunnercover.png',
        'playLink' => 'phase-runner/',
        'external' => false,
        'controls' => 'Keyboard: Arrow Keys or WASD to move, Z / Space to shoot'
    ],
    [
        'id' => 2,
        'title' => 'Mario Kart Recreation',
        'description' => 'A from-scratch SNES Mode-7 renderer in JavaScript — raycasting, sprite sheets, lap timing, character selection, and full race logic. No game engine.',
        'year' => '2024',
        'tech' => ['JavaScript', 'Mode-7 Rendering', 'Canvas API'],
        'thumbnail' => '../../assets/images/mariokart.png',
        'video'     => '../../assets/images/mariokart.mp4',
        'playLink' => '/MarioKartLatest/',
        'external' => false,
        'controls' => 'Arrow Keys to steer, Z to accelerate, X to brake/reverse'
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
  <div id="cursor-glow" style="position:fixed;top:0;left:0;width:420px;height:420px;border-radius:50%;background:radial-gradient(circle,rgba(255,255,255,0.05) 0%,transparent 70%);pointer-events:none;z-index:0;transform:translate(-50%,-50%);transition:opacity 0.3s ease;opacity:0"></div>
    <header class="site-nav" id="site-nav">
        <a href="../../index.php" class="nav-logo">JB</a>
        <nav class="nav-links">
            <a href="../../index.php#about">About</a>
            <a href="../../index.php#skills">Skills</a>
            <a href="../">Portfolio</a>
            <a href="../../assets/Jake%20Barton%20-%20Resume.pdf" class="btn btn-secondary btn-sm" download>Resume</a>
            <a href="../../index.php#contact">Contact</a>
        </nav>
    </header>


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
                        <?php if (!empty($game['video'])): ?>
                        <video autoplay muted loop playsinline
                               style="width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.4s ease;">
                            <source src="<?php echo $game['video']; ?>" type="video/mp4">
                        </video>
                        <?php else: ?>
                        <img src="<?php echo $game['thumbnail']; ?>"
                             alt="<?php echo htmlspecialchars($game['title']); ?>"
                             style="width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.4s ease;">
                        <?php endif; ?>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="../../assets/js/staggered-menu.js"></script>
    <script>
      (function(){var b=document.getElementById('scroll-progress');if(!b)return;window.addEventListener('scroll',function(){var s=window.scrollY,t=document.documentElement.scrollHeight-window.innerHeight;b.style.width=(t>0?(s/t)*100:0)+'%';},{passive:true});})();
      (function(){var g=document.getElementById('cursor-glow');if(!g||window.matchMedia('(pointer:coarse)').matches)return;var cx=0,cy=0,tx=0,ty=0;document.addEventListener('mousemove',function(e){tx=e.clientX;ty=e.clientY;g.style.opacity='1';});document.addEventListener('mouseleave',function(){g.style.opacity='0';});function lerp(a,b,t){return a+(b-a)*t;}(function loop(){cx=lerp(cx,tx,0.07);cy=lerp(cy,ty,0.07);g.style.left=cx+'px';g.style.top=cy+'px';requestAnimationFrame(loop);})();})();
    </script>

</body>
</html>
