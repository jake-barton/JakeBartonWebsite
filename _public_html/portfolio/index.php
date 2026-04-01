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
  /* ── Work section (mirrors homepage exactly) ─────────── */
  .container-wide { max-width:1300px;margin:0 auto;padding:0 var(--spacing-md); }

  /* Hero */
  .port-hero { padding: clamp(7rem,14vw,11rem) 0 clamp(3rem,6vw,5rem); }
  .port-hero-eyebrow {
    font-size:0.7rem;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;
    color:var(--text-faint);margin-bottom:1.25rem;display:block;
  }
  .port-hero-title {
    font-family:var(--font-display);
    font-size:clamp(3.5rem,9vw,7.5rem);
    font-weight:800;
    letter-spacing:-0.04em;
    line-height:1;
    color:var(--text);
    margin-bottom:1.5rem;
  }
  .port-hero-sub {
    font-size:clamp(0.95rem,1.8vw,1.15rem);
    color:var(--text-muted);
    line-height:1.75;
    max-width:520px;
  }

  /* Label row */
  .work-label-row {
    display:flex;align-items:baseline;gap:1.25rem;
    margin-bottom:3rem;padding-bottom:1.25rem;border-bottom:1px solid var(--border);
  }
  .work-label-num { font-family:var(--font-display);font-size:0.72rem;font-weight:800;letter-spacing:0.12em;color:var(--text-faint); }
  .work-label-text { font-family:var(--font-display);font-size:0.72rem;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:var(--text-faint);flex:1; }
  .work-label-link { font-size:0.78rem;color:var(--text-faint);text-decoration:none;transition:color 0.2s; }
  .work-label-link:hover { color:var(--text); }

  /* Featured hero card */
  .work-hero-card {
    display:block;position:relative;width:100%;
    height:clamp(300px,50vw,580px);overflow:hidden;text-decoration:none;margin-bottom:1px;
  }
  .work-hero-video, .work-hero-img {
    position:absolute;inset:0;width:100%;height:100%;object-fit:cover;
    transition:transform 0.8s cubic-bezier(0.16,1,0.3,1);
  }
  .work-hero-card:hover .work-hero-video,
  .work-hero-card:hover .work-hero-img { transform:scale(1.04); }
  .work-hero-gradient { position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,0.9) 0%,rgba(0,0,0,0.3) 50%,transparent 100%); }
  .work-hero-content { position:absolute;bottom:0;left:0;right:0;padding:clamp(1.5rem,4vw,3rem);display:flex;flex-direction:column;gap:0.6rem; }
  .work-hero-tags { display:flex;gap:0.5rem;flex-wrap:wrap; }
  .work-hero-title { font-family:var(--font-display);font-size:clamp(2rem,5vw,4.5rem);font-weight:800;letter-spacing:-0.03em;color:var(--text);line-height:1;margin:0; }
  .work-hero-desc { font-size:0.95rem;color:rgba(255,255,255,0.7);line-height:1.65;max-width:60ch;margin:0; }
  .work-hero-cta { font-size:0.8rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:var(--text);margin-top:0.5rem;transition:letter-spacing 0.3s; }
  .work-hero-card:hover .work-hero-cta { letter-spacing:0.18em; }
  .work-hero-badge { position:absolute;top:1.5rem;right:1.5rem;font-size:0.65rem;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:var(--text);border:1px solid rgba(255,255,255,0.3);padding:0.3rem 0.75rem;border-radius:99px;backdrop-filter:blur(8px); }

  /* Work index list */
  .work-list { border-top:1px solid var(--border);margin-top:0; }
  .work-list-item {
    display:grid;grid-template-columns:100px 1fr auto;align-items:center;
    gap:1.5rem 2rem;padding:1.75rem 0;border-bottom:1px solid var(--border);
    text-decoration:none;color:var(--text);transition:background 0.25s;cursor:pointer;
  }
  .work-list-item:hover { background:rgba(255,255,255,0.025); }
  .work-list-media { width:100px;height:68px;border-radius:6px;overflow:hidden;flex-shrink:0; }
  .work-list-media img, .work-list-media video { width:100%;height:100%;object-fit:cover;display:block; }
  .work-list-info { display:flex;align-items:flex-start;gap:1.25rem; }
  .work-list-num { font-family:var(--font-display);font-size:0.68rem;font-weight:800;letter-spacing:0.1em;color:var(--text-faint);padding-top:0.2em;flex-shrink:0;min-width:20px; }
  .work-list-title { font-family:var(--font-display);font-size:clamp(1rem,2vw,1.35rem);font-weight:800;letter-spacing:-0.025em;color:var(--text);margin:0 0 0.35rem; }
  .work-list-desc { font-size:0.85rem;color:var(--text-muted);line-height:1.6;margin:0 0 0.5rem; }
  .work-list-tags { display:flex;gap:0.4rem;flex-wrap:wrap; }
  .work-list-arrow { font-size:1.25rem;color:var(--text-faint);transition:transform 0.25s,color 0.25s;flex-shrink:0;padding-right:0.5rem; }
  .work-list-item:hover .work-list-arrow { transform:translate(3px,-3px);color:var(--text); }

  /* Reveal system */
  .reveal-up,.reveal-row { opacity:0;transition:opacity 0.7s cubic-bezier(0.16,1,0.3,1),transform 0.7s cubic-bezier(0.16,1,0.3,1); }
  .reveal-up { transform:translateY(48px); }
  .reveal-row { transform:translateY(40px);border-bottom:1px solid transparent;transition:opacity 0.65s cubic-bezier(0.16,1,0.3,1),transform 0.65s cubic-bezier(0.16,1,0.3,1),border-color 0.65s ease; }
  .reveal-up.is-visible,.reveal-row.is-visible { opacity:1;transform:none;border-color:var(--border); }
  .stagger-reveal > * { opacity:0;transform:translateY(32px);transition:opacity 0.6s cubic-bezier(0.16,1,0.3,1),transform 0.6s cubic-bezier(0.16,1,0.3,1); }
  .stagger-reveal.is-visible > *:nth-child(1){opacity:1;transform:none;transition-delay:0.05s}
  .stagger-reveal.is-visible > *:nth-child(2){opacity:1;transform:none;transition-delay:0.12s}
  .stagger-reveal.is-visible > *:nth-child(3){opacity:1;transform:none;transition-delay:0.19s}

  /* Stats bar */
  .port-stats { display:flex;align-items:center;gap:0;margin-top:clamp(3rem,6vw,5rem);border-top:1px solid var(--border);border-bottom:1px solid var(--border);padding:1.5rem 0; }
  .port-stat { flex:1;text-align:center;padding:0 1rem; }
  .port-stat-num { font-family:var(--font-display);font-size:clamp(1.6rem,3vw,2.2rem);font-weight:800;letter-spacing:-0.04em;color:var(--text);display:block; }
  .port-stat-label { font-size:0.72rem;font-weight:600;letter-spacing:0.1em;text-transform:uppercase;color:var(--text-faint);display:block;margin-top:0.25rem; }
  .port-stat-divider { width:1px;height:2.5rem;background:var(--border);flex-shrink:0; }

  /* Placeholder media blocks */
  .media-placeholder { width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#111; }
  .media-placeholder span { font-size:0.6rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:var(--text-faint); }

  /* Section divider label */
  .section-divider-row { display:flex;align-items:baseline;gap:1.25rem;padding:3rem 0 2rem;border-bottom:1px solid var(--border);margin-bottom:0; }
  .section-divider-row span { font-family:var(--font-display);font-size:0.72rem;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:var(--text-faint); }

  @media(max-width:768px){
    .work-list-item { grid-template-columns:72px 1fr auto;gap:0.75rem 1rem; }
    .work-list-media { width:72px;height:52px; }
  }
  @media(max-width:540px){
    .work-list-item { grid-template-columns:1fr auto;gap:0.5rem; }
    .work-list-media { display:none; }
    .port-stats { flex-wrap:wrap;gap:1.5rem; }
    .port-stat-divider { display:none; }
  }
  </style>
</head>
<body>

  <div id="scroll-progress" style="position:fixed;top:0;left:0;height:2px;width:0%;background:var(--accent);z-index:100001;transition:width 0.1s linear;pointer-events:none"></div>
  <div id="cursor-glow" style="position:fixed;top:0;left:0;width:420px;height:420px;border-radius:50%;background:radial-gradient(circle,rgba(255,255,255,0.05) 0%,transparent 70%);pointer-events:none;z-index:0;transform:translate(-50%,-50%);transition:opacity 0.3s ease;opacity:0"></div>

  <header class="site-nav" id="site-nav">
    <a href="../index.php" class="nav-logo"><img src="../assets/images/jb-logo.png" alt="JB" class="nav-logo-img"><span class="nav-logo-text">JB</span></a>
    <nav class="nav-links">
      <a href="../index.php#about">About</a>
      <a href="../index.php#skills">Skills</a>
      <a href="./">Portfolio</a>
      <a href="../assets/Jake%20Barton%20-%20Resume.pdf" class="btn btn-secondary btn-sm" download>Resume</a>
      <a href="../index.php#contact">Contact</a>
    </nav>
  </header>

  <main class="site-content">

    <!-- ── Hero ───────────────────────────────────────────── -->
    <section class="port-hero container-wide">
      <span class="port-hero-eyebrow reveal-up">All Work</span>
      <h1 class="port-hero-title reveal-up" style="transition-delay:0.06s">Portfolio.</h1>
      <p class="port-hero-sub reveal-up" style="transition-delay:0.12s">Games, websites, and everything in between — three disciplines, one through-line.</p>
      <div class="port-stats stagger-reveal">
        <div class="port-stat">
          <span class="port-stat-num">3+</span>
          <span class="port-stat-label">Years Building</span>
        </div>
        <div class="port-stat-divider"></div>
        <div class="port-stat">
          <span class="port-stat-num">20+</span>
          <span class="port-stat-label">Projects Built</span>
        </div>
        <div class="port-stat-divider"></div>
        <div class="port-stat">
          <span class="port-stat-num">3</span>
          <span class="port-stat-label">Disciplines</span>
        </div>
        <div class="port-stat-divider"></div>
        <div class="port-stat">
          <span class="port-stat-num">1</span>
          <span class="port-stat-label">Shipped Game</span>
        </div>
      </div>
    </section>

    <!-- ── Work section ───────────────────────────────────── -->
    <section style="padding-bottom:clamp(4rem,8vw,8rem);">
      <div class="container-wide">

        <div class="work-label-row reveal-up">
          <span class="work-label-num">01</span>
          <span class="work-label-text">Selected Work</span>
        </div>

        <!-- Featured: Phase Runner -->
        <a href="game-programming/" class="work-hero-card reveal-up" style="transition-delay:0.08s">
          <video class="work-hero-video" autoplay muted loop playsinline preload="auto">
            <source src="../assets/images/phase-runner-screen.mp4" type="video/mp4">
          </video>
          <div class="work-hero-gradient"></div>
          <div class="work-hero-content">
            <div class="work-hero-tags">
              <span class="tag">Game Programming</span>
              <span class="tag tag-muted">Godot 4</span>
              <span class="tag tag-muted">GDScript</span>
              <span class="tag tag-muted">Solo</span>
            </div>
            <h2 class="work-hero-title">Phase Runner</h2>
            <p class="work-hero-desc">2D side-scrolling shooter with custom physics, 10+ weapons, procedural level chunks, and invincibility dash. Solo-developed and live on itch.io.</p>
            <span class="work-hero-cta">View Case Study →</span>
          </div>
          <div class="work-hero-badge">Featured</div>
        </a>

        <!-- Work list -->
        <div class="work-list">

          <a href="game-programming/" class="work-list-item reveal-row">
            <div class="work-list-media video-card">
              <video autoplay muted loop playsinline preload="metadata">
                <source src="../assets/images/vr-gameplay.mp4" type="video/mp4">
              </video>
            </div>
            <div class="work-list-info">
              <span class="work-list-num">01</span>
              <div>
                <h3 class="work-list-title">VR Rhythm Game</h3>
                <p class="work-list-desc">Lead Programmer on a 5-person team — body-movement dragon controller in Unreal Engine 5, C++, OpenXR.</p>
                <div class="work-list-tags">
                  <span class="tag tag-muted">UE5</span>
                  <span class="tag tag-muted">C++</span>
                  <span class="tag tag-muted">VR</span>
                </div>
              </div>
            </div>
            <span class="work-list-arrow">↗</span>
          </a>

          <a href="game-programming/" class="work-list-item reveal-row">
            <div class="work-list-media video-card">
              <video autoplay muted loop playsinline preload="metadata">
                <source src="../assets/images/environment-scene.mp4" type="video/mp4">
              </video>
            </div>
            <div class="work-list-info">
              <span class="work-list-num">02</span>
              <div>
                <h3 class="work-list-title">Mediterranean Environment</h3>
                <p class="work-list-desc">Photorealistic real-time 3D scene built in Unreal Engine 5 — Lumen GI, Nanite, custom material layering.</p>
                <div class="work-list-tags">
                  <span class="tag tag-muted">3D Art</span>
                  <span class="tag tag-muted">Unreal 5</span>
                  <span class="tag tag-muted">Real-time</span>
                </div>
              </div>
            </div>
            <span class="work-list-arrow">↗</span>
          </a>

          <a href="game-programming/" class="work-list-item reveal-row">
            <div class="work-list-media video-card">
              <video autoplay muted loop playsinline preload="metadata">
                <source src="../assets/images/penguins-creed.mp4" type="video/mp4">
              </video>
            </div>
            <div class="work-list-info">
              <span class="work-list-num">03</span>
              <div>
                <h3 class="work-list-title">Penguins Creed</h3>
                <p class="work-list-desc">Third-person stealth-action in UE5 — patrol AI with Behavior Trees, line-of-sight detection, NavMesh chase logic.</p>
                <div class="work-list-tags">
                  <span class="tag tag-muted">UE5</span>
                  <span class="tag tag-muted">Blueprints</span>
                  <span class="tag tag-muted">AI Systems</span>
                </div>
              </div>
            </div>
            <span class="work-list-arrow">↗</span>
          </a>

          <a href="web-programming/" class="work-list-item reveal-row">
            <div class="work-list-media">
              <img src="../assets/images/mariokart.png" alt="Mario Kart Recreation">
            </div>
            <div class="work-list-info">
              <span class="work-list-num">04</span>
              <div>
                <h3 class="work-list-title">Mario Kart Recreation</h3>
                <p class="work-list-desc">SNES Mode-7 renderer in vanilla JS — raycasting, sprite sheets, lap logic, and multi-character selection. No libraries.</p>
                <div class="work-list-tags">
                  <span class="tag tag-muted">JavaScript</span>
                  <span class="tag tag-muted">Web Game</span>
                  <span class="tag tag-muted">Mode-7</span>
                </div>
              </div>
            </div>
            <span class="work-list-arrow">↗</span>
          </a>

          <a href="professional-works/" class="work-list-item reveal-row">
            <div class="work-list-media">
              <img src="professional-works/33-miles-graphics/images/full/33-miles-01-grain-regular.png" alt="33 Miles Brand Work" style="object-fit:cover">
            </div>
            <div class="work-list-info">
              <span class="work-list-num">05</span>
              <div>
                <h3 class="work-list-title">33Miles Band Graphics</h3>
                <p class="work-list-desc">Paid brand &amp; merchandise design for a signed Christian music group — logo, merch, tour visuals.</p>
                <div class="work-list-tags">
                  <span class="tag tag-muted">Branding</span>
                  <span class="tag tag-muted">Illustrator</span>
                  <span class="tag tag-muted">Print</span>
                </div>
              </div>
            </div>
            <span class="work-list-arrow">↗</span>
          </a>

          <a href="tshirt-designs/" class="work-list-item reveal-row">
            <div class="work-list-media">
              <img src="../assets/images/shelcover.png" alt="T-Shirt Designs" style="object-fit:cover">
            </div>
            <div class="work-list-info">
              <span class="work-list-num">06</span>
              <div>
                <h3 class="work-list-title">T-Shirt Design Portfolio</h3>
                <p class="work-list-desc">15+ apparel runs designed and produced for Pi Kappa Phi — custom illustration, vendor management, print production.</p>
                <div class="work-list-tags">
                  <span class="tag tag-muted">Apparel</span>
                  <span class="tag tag-muted">Illustration</span>
                  <span class="tag tag-muted">Print</span>
                </div>
              </div>
            </div>
            <span class="work-list-arrow">↗</span>
          </a>

          <a href="web-programming/" class="work-list-item reveal-row">
            <div class="work-list-media">
              <div class="media-placeholder"><span>PHP / JS</span></div>
            </div>
            <div class="work-list-info">
              <span class="work-list-num">07</span>
              <div>
                <h3 class="work-list-title">jakebartoncreative.com</h3>
                <p class="work-list-desc">This portfolio — PHP, vanilla JS, GSAP animations, IntersectionObserver scroll reveals, and GitHub Actions deploy.</p>
                <div class="work-list-tags">
                  <span class="tag tag-muted">PHP</span>
                  <span class="tag tag-muted">JavaScript</span>
                  <span class="tag tag-muted">GSAP</span>
                </div>
              </div>
            </div>
            <span class="work-list-arrow">↗</span>
          </a>

        </div><!-- /.work-list -->

        <!-- Divider: Browse by Discipline -->
        <div class="section-divider-row reveal-up" style="margin-top:4rem">
          <span class="work-label-num">02</span>
          <span>Browse by Discipline</span>
        </div>

        <div class="work-list">

          <a href="game-programming/" class="work-list-item reveal-row">
            <div class="work-list-media">
              <img src="../assets/images/phaserunnercover.png" alt="Game Programming">
            </div>
            <div class="work-list-info">
              <span class="work-list-num">GP</span>
              <div>
                <h3 class="work-list-title">Game Programming</h3>
                <p class="work-list-desc">Godot 4, Unreal Engine 5, C++ — from solo indie games to team-built VR experiences. Includes 3 full case studies.</p>
                <div class="work-list-tags">
                  <span class="tag tag-muted">Godot 4</span>
                  <span class="tag tag-muted">UE5</span>
                  <span class="tag tag-muted">C++</span>
                </div>
              </div>
            </div>
            <span class="work-list-arrow">↗</span>
          </a>

          <a href="web-programming/" class="work-list-item reveal-row">
            <div class="work-list-media">
              <img src="../assets/images/mariokart.png" alt="Web Programming">
            </div>
            <div class="work-list-info">
              <span class="work-list-num">WP</span>
              <div>
                <h3 class="work-list-title">Web Programming</h3>
                <p class="work-list-desc">Full-stack PHP apps, JavaScript tools, AI integrations, e-commerce, and this portfolio itself.</p>
                <div class="work-list-tags">
                  <span class="tag tag-muted">PHP</span>
                  <span class="tag tag-muted">JavaScript</span>
                  <span class="tag tag-muted">Next.js</span>
                </div>
              </div>
            </div>
            <span class="work-list-arrow">↗</span>
          </a>

          <a href="art/" class="work-list-item reveal-row">
            <div class="work-list-media">
              <img src="professional-works/33-miles-graphics/images/full/33-miles-01-grain-regular.png" alt="Art & Design" style="object-fit:cover">
            </div>
            <div class="work-list-info">
              <span class="work-list-num">AD</span>
              <div>
                <h3 class="work-list-title">Art &amp; Design</h3>
                <p class="work-list-desc">Client graphics, band merch, apparel design, 3D modelling, and photorealistic environment art.</p>
                <div class="work-list-tags">
                  <span class="tag tag-muted">Maya</span>
                  <span class="tag tag-muted">Illustrator</span>
                  <span class="tag tag-muted">Blender</span>
                </div>
              </div>
            </div>
            <span class="work-list-arrow">↗</span>
          </a>

          <a href="games/" class="work-list-item reveal-row">
            <div class="work-list-media">
              <img src="../assets/images/phaserunnercover.png" alt="Playable Games">
            </div>
            <div class="work-list-info">
              <span class="work-list-num">PG</span>
              <div>
                <h3 class="work-list-title">Playable Games</h3>
                <p class="work-list-desc">Run Phase Runner and Mario Kart right here in the browser — no install required.</p>
                <div class="work-list-tags">
                  <span class="tag tag-muted">Browser</span>
                  <span class="tag tag-muted">WebGL</span>
                  <span class="tag tag-muted">JavaScript</span>
                </div>
              </div>
            </div>
            <span class="work-list-arrow">↗</span>
          </a>

        </div><!-- /.discipline list -->

      </div><!-- /.container-wide -->
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
    /* Scroll progress */
    (function(){var b=document.getElementById('scroll-progress');if(!b)return;window.addEventListener('scroll',function(){var s=window.scrollY,t=document.documentElement.scrollHeight-window.innerHeight;b.style.width=(t>0?(s/t)*100:0)+'%';},{passive:true});})();
    /* Cursor glow */
    (function(){var g=document.getElementById('cursor-glow');if(!g||window.matchMedia('(pointer:coarse)').matches)return;var cx=0,cy=0,tx=0,ty=0;document.addEventListener('mousemove',function(e){tx=e.clientX;ty=e.clientY;g.style.opacity='1';});document.addEventListener('mouseleave',function(){g.style.opacity='0';});function lerp(a,b,t){return a+(b-a)*t;}(function loop(){cx=lerp(cx,tx,0.07);cy=lerp(cy,ty,0.07);g.style.left=cx+'px';g.style.top=cy+'px';requestAnimationFrame(loop);})();})();
    /* Scroll reveal */
    (function(){
      var singles=document.querySelectorAll('.reveal-up');
      var sObs=new IntersectionObserver(function(entries){entries.forEach(function(e){if(e.isIntersecting){e.target.classList.add('is-visible');sObs.unobserve(e.target);}});},{threshold:0.12,rootMargin:'0px 0px -40px 0px'});
      singles.forEach(function(el){sObs.observe(el);});

      var rows=document.querySelectorAll('.reveal-row');
      var rObs=new IntersectionObserver(function(entries){entries.forEach(function(e){if(e.isIntersecting){var siblings=e.target.parentElement.querySelectorAll('.reveal-row');var idx=0;siblings.forEach(function(s,i){if(s===e.target)idx=i;});e.target.style.transitionDelay=(idx*0.06)+'s';e.target.classList.add('is-visible');rObs.unobserve(e.target);}});},{threshold:0.08,rootMargin:'0px 0px -20px 0px'});
      rows.forEach(function(el){rObs.observe(el);});

      var stagger=document.querySelectorAll('.stagger-reveal');
      var gObs=new IntersectionObserver(function(entries){entries.forEach(function(e){if(e.isIntersecting){e.target.classList.add('is-visible');gObs.unobserve(e.target);}});},{threshold:0.1,rootMargin:'0px 0px -30px 0px'});
      stagger.forEach(function(el){gObs.observe(el);});
    })();
  </script>

</body>
</html>
