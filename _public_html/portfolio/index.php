<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Portfolio — Jake Barton</title>
  <link rel="icon" type="image/svg+xml" href="../assets/images/favicon.svg?v=20260325">
  <link rel="stylesheet" href="../assets/css/base.css">
  <link rel="stylesheet" href="../assets/css/animations.css">
  <link rel="stylesheet" href="../assets/css/components.css">
  <style>
    .portfolio-hero-title {
      font-family: var(--font-display);
      font-size: clamp(3rem, 8vw, 6rem);
      font-weight: 700;
      letter-spacing: -0.02em;
      line-height: 1.05;
      color: var(--text);
      margin-bottom: 1rem;
    }
    .portfolio-hero-title em {
      color: var(--accent-light);
      font-style: italic;
    }
    .discipline-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 1.25rem;
    }
    @media (max-width: 768px) { .discipline-grid { grid-template-columns: 1fr; } }
    .discipline-card {
      text-decoration: none;
      display: block;
    }
    .discipline-card .work-card-img {
      height: 200px;
      overflow: hidden;
      border-radius: var(--radius-lg) var(--radius-lg) 0 0;
    }
    .discipline-card .work-card-img img {
      width: 100%; height: 100%; object-fit: cover;
      transition: transform 0.45s ease;
    }
    .discipline-card:hover .work-card-img img { transform: scale(1.04); }
    .sub-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 1rem;
    }
    @media (max-width: 768px) { .sub-grid { grid-template-columns: 1fr; } }
    .sub-card {
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 1rem;
      padding: 1.25rem 1.5rem;
    }
    .sub-card-icon {
      font-size: 0.65rem;
      font-weight: 700;
      letter-spacing: 0.07em;
      font-family: monospace;
      color: var(--accent);
      flex-shrink: 0;
      line-height: 1;
      min-width: 2.5rem;
      text-align: center;
    }
    .sub-card h3 { font-size: 0.95rem; font-weight: 600; color: var(--text); margin-bottom: 0.2rem; }
    .sub-card p { font-size: 0.8rem; color: var(--text-muted); margin: 0; }
  </style>
</head>
<body>

  <!-- Scroll progress line -->
  <div id="scroll-progress" style="position:fixed;top:0;left:0;height:2px;width:0%;background:var(--accent);z-index:100001;transition:width 0.1s linear;pointer-events:none"></div>

  <!-- Ambient cursor glow -->
  <div id="cursor-glow" style="position:fixed;top:0;left:0;width:420px;height:420px;border-radius:50%;background:radial-gradient(circle,rgba(255,255,255,0.05) 0%,transparent 70%);pointer-events:none;z-index:0;transform:translate(-50%,-50%);transition:opacity 0.3s ease;opacity:0"></div>  </div>

  <header class="site-nav" id="site-nav">
    <a href="../index.php" class="nav-logo">JB</a>
    <nav class="nav-links">
      <a href="../index.php#about">About</a>
      <a href="../index.php#skills">Skills</a>
      <a href="./">Portfolio</a>
      <a href="../assets/Jake%20Barton%20-%20Resume.pdf" download>Resume</a>
      <a href="../index.php#contact">Contact</a>
    </nav>
  </header>


  <main class="site-content">

    <!-- ── Hero ─────────────────────────────────────────── -->
    <section class="section" style="padding-top:calc(var(--spacing-2xl) + 4rem)">
      <div class="container">
        <span class="eyebrow hero-eyebrow">All Work</span>
        <h1 class="portfolio-hero-title reveal">
          Games, websites,<br><em>and everything in between.</em>
        </h1>
        <p class="reveal" style="font-size:1.1rem;max-width:540px;color:var(--text-muted);margin-top:1rem;line-height:1.75;transition-delay:0.12s">
          Three disciplines, one through-line: every project is a problem I wanted to solve.
        </p>
      </div>
    </section>

    <!-- ── Three main disciplines ────────────────────────── -->
    <section class="section-sm">
      <div class="container">
        <div class="section-header reveal" style="margin-bottom:2rem">
          <span class="eyebrow">Disciplines</span>
          <h2>What I Build</h2>
        </div>
        <div class="discipline-grid stagger-children">

          <a href="game-programming/" class="discipline-card work-card tilt-card reveal">
            <div class="work-card-img">
              <img src="../assets/images/phaserunnercover.png" alt="Game Programming">
            </div>
            <div class="work-card-body">
              <div class="work-card-tags stagger-pop" style="margin-bottom:0.6rem">
                <span class="tag">Godot</span>
                <span class="tag tag-muted">Unreal 5</span>
                <span class="tag tag-muted">C++</span>
              </div>
              <h2 class="work-card-title" style="font-size:1.2rem">Game Programming</h2>
              <p class="work-card-desc">Custom engines, platformers, VR experiences, and desktop companions.</p>
              <span class="work-card-cta" style="margin-top:0.75rem;display:inline-block">View Projects →</span>
            </div>
          </a>

          <a href="web-programming/" class="discipline-card work-card tilt-card reveal">
            <div class="work-card-img">
              <img src="../assets/images/mariokart.png" alt="Web Programming">
            </div>
            <div class="work-card-body">
              <div class="work-card-tags stagger-pop" style="margin-bottom:0.6rem">
                <span class="tag">PHP</span>
                <span class="tag tag-muted">JavaScript</span>
                <span class="tag tag-muted">Next.js</span>
              </div>
              <h2 class="work-card-title" style="font-size:1.2rem">Web Programming</h2>
              <p class="work-card-desc">Full-stack apps, AI tools, e-commerce, and this portfolio itself.</p>
              <span class="work-card-cta" style="margin-top:0.75rem;display:inline-block">View Projects →</span>
            </div>
          </a>

          <a href="art/" class="discipline-card work-card tilt-card reveal">
            <div class="work-card-img">
              <img src="professional-works/33-miles-graphics/images/full/33-miles-01-grain-regular.png" alt="Art and Design" style="object-fit:cover">
            </div>
            <div class="work-card-body">
              <div class="work-card-tags stagger-pop" style="margin-bottom:0.6rem">
                <span class="tag">Branding</span>
                <span class="tag tag-muted">Illustration</span>
                <span class="tag tag-muted">Print</span>
              </div>
              <h2 class="work-card-title" style="font-size:1.2rem">Art &amp; Design</h2>
              <p class="work-card-desc">Client graphics, band merch, apparel, and 3D modelling work.</p>
              <span class="work-card-cta" style="margin-top:0.75rem;display:inline-block">View Work →</span>
            </div>
          </a>

        </div>
      </div>
    </section>

    <!-- ── Divider + sub-categories ─────────────────────── -->
    <section class="section-sm">
      <div class="container">
        <div class="divider reveal" style="margin-bottom:3rem"></div>
        <div class="section-header reveal">
          <span class="eyebrow">Sub-categories</span>
          <h2>Browse by Focus</h2>
        </div>
        <div class="sub-grid stagger-children" style="margin-top:2rem">

          <a href="professional-works/" class="glass-card sub-card tilt-card">
            <span class="sub-card-icon">PRO</span>
            <div>
              <h3>Professional Works</h3>
              <p>Client projects &amp; paid graphic design</p>
            </div>
          </a>

          <a href="tshirt-designs/" class="glass-card sub-card tilt-card">
            <span class="sub-card-icon">TEE</span>
            <div>
              <h3>T-Shirt Designs</h3>
              <p>Custom apparel for Pi Kappa Phi</p>
            </div>
          </a>

          <a href="games/" class="glass-card sub-card tilt-card">
            <span class="sub-card-icon">GD</span>
            <div>
              <h3>Playable Games</h3>
              <p>Run them right here in the browser</p>
            </div>
          </a>

        </div>
      </div>
    </section>

    <!-- ── Stats ─────────────────────────────────────────── -->
    <section class="section-sm" style="text-align:center">
      <div class="container">
        <div class="hero-stats reveal" style="display:inline-flex;margin-top:0">
          <div class="hero-stat"><span class="hero-stat-num" data-count="3" data-suffix="+">3+</span><span class="hero-stat-label">Years Building</span></div>
          <div class="hero-stat-divider"></div>
          <div class="hero-stat"><span class="hero-stat-num" data-count="20" data-suffix="+">20+</span><span class="hero-stat-label">Projects</span></div>
          <div class="hero-stat-divider"></div>
          <div class="hero-stat"><span class="hero-stat-num" data-count="3" data-suffix="">3</span><span class="hero-stat-label">Disciplines</span></div>
        </div>
      </div>
    </section>

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
  <script src="../assets/js/beams-bg.js"></script>
  <script src="../assets/js/cursor-ribbons.js"></script>
  <script src="../assets/js/fuzzy-text.js"></script>
  <script src="../assets/js/effects-stylekit.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
  <script src="../assets/js/staggered-menu.js"></script>
  <script>
    (function(){var b=document.getElementById('scroll-progress');if(!b)return;window.addEventListener('scroll',function(){var s=window.scrollY,t=document.documentElement.scrollHeight-window.innerHeight;b.style.width=(t>0?(s/t)*100:0)+'%';},{passive:true});})();
    (function(){var g=document.getElementById('cursor-glow');if(!g||window.matchMedia('(pointer:coarse)').matches)return;var cx=0,cy=0,tx=0,ty=0;document.addEventListener('mousemove',function(e){tx=e.clientX;ty=e.clientY;g.style.opacity='1';});document.addEventListener('mouseleave',function(){g.style.opacity='0';});function lerp(a,b,t){return a+(b-a)*t;}(function loop(){cx=lerp(cx,tx,0.07);cy=lerp(cy,ty,0.07);g.style.left=cx+'px';g.style.top=cy+'px';requestAnimationFrame(loop);})();})();
  </script>

</body>
</html>
