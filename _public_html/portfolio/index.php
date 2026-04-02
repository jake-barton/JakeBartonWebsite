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
  /* ── Layout ─────────────────────────────────────────── */
  .pw { max-width:1300px;margin:0 auto;padding:0 var(--spacing-md); }

  /* ── Hero — left-right split ───────────────────────── */
  .port-hero {
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:3rem;
    align-items:end;
    padding:clamp(8rem,14vw,12rem) 0 clamp(3rem,5vw,4rem);
  }
  .port-hero-left h1 {
    font-family:var(--font-display);
    font-size:clamp(4rem,10vw,8rem);
    font-weight:800;
    letter-spacing:-0.05em;
    line-height:0.95;
    color:var(--text);
    margin:0 0 1.5rem;
  }
  .port-hero-left h1 em {
    font-style:italic;
    font-family:'Playfair Display',Georgia,serif;
    font-weight:400;
    color:var(--text-muted);
  }
  .port-hero-right {
    padding-bottom:0.5rem;
  }
  .port-hero-right p {
    font-size:clamp(0.95rem,1.6vw,1.1rem);
    color:var(--text-muted);
    line-height:1.8;
    margin-bottom:2rem;
  }
  .port-hero-stats {
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:1px;
    background:var(--border);
    border:1px solid var(--border);
    border-radius:10px;
    overflow:hidden;
  }
  .port-hero-stat {
    background:var(--bg);
    padding:1.25rem 1.5rem;
  }
  .port-hero-stat-num {
    font-family:var(--font-display);
    font-size:clamp(1.8rem,3vw,2.4rem);
    font-weight:800;
    letter-spacing:-0.04em;
    color:var(--text);
    display:block;
    line-height:1;
  }
  .port-hero-stat-label {
    font-size:0.72rem;
    font-weight:600;
    letter-spacing:0.1em;
    text-transform:uppercase;
    color:var(--text-faint);
    display:block;
    margin-top:0.35rem;
  }

  /* ── Filter tabs ────────────────────────────────────── */
  .filter-row {
    display:flex;
    align-items:center;
    gap:0.5rem;
    flex-wrap:wrap;
    padding:2rem 0;
    border-top:1px solid var(--border);
    border-bottom:1px solid var(--border);
    margin-bottom:3rem;
  }
  .filter-label {
    font-size:0.7rem;
    font-weight:700;
    letter-spacing:0.15em;
    text-transform:uppercase;
    color:var(--text-faint);
    margin-right:0.75rem;
  }
  .filter-btn {
    font-size:0.8rem;
    font-weight:600;
    letter-spacing:0.05em;
    padding:0.4rem 1rem;
    border-radius:99px;
    border:1px solid rgba(255,255,255,0.12);
    background:transparent;
    color:var(--text-muted);
    cursor:pointer;
    transition:all 0.2s;
    text-decoration:none;
    display:inline-block;
  }
  .filter-btn:hover,.filter-btn.active {
    background:var(--text);
    color:var(--bg);
    border-color:var(--text);
  }

  /* ── Bento grid ─────────────────────────────────────── */
  .bento {
    display:grid;
    grid-template-columns:repeat(12,1fr);
    grid-auto-rows:80px;
    gap:12px;
    margin-bottom:5rem;
  }

  /* Card base */
  .b-card {
    position:relative;
    overflow:hidden;
    border-radius:12px;
    background:#0f0f0f;
    border:1px solid rgba(255,255,255,0.07);
    text-decoration:none;
    display:block;
    transition:border-color 0.3s,transform 0.4s cubic-bezier(0.16,1,0.3,1);
  }
  .b-card:hover { border-color:rgba(255,255,255,0.22);transform:translateY(-3px); }
  .b-card video,.b-card img.b-media {
    position:absolute;inset:0;width:100%;height:100%;
    object-fit:cover;display:block;
    transition:transform 0.8s cubic-bezier(0.16,1,0.3,1);
    opacity:0.75;
  }
  .b-card:hover video,.b-card:hover img.b-media { transform:scale(1.05);opacity:0.9; }
  .b-overlay {
    position:absolute;inset:0;
    background:linear-gradient(to top,rgba(0,0,0,0.88) 0%,rgba(0,0,0,0.2) 55%,transparent 100%);
  }
  .b-content {
    position:absolute;bottom:0;left:0;right:0;
    padding:1.25rem 1.5rem;
  }
  .b-eyebrow {
    font-size:0.65rem;font-weight:700;letter-spacing:0.14em;text-transform:uppercase;
    color:rgba(255,255,255,0.5);margin-bottom:0.4rem;display:block;
  }
  .b-title {
    font-family:var(--font-display);font-weight:800;letter-spacing:-0.025em;
    color:var(--text);line-height:1.1;margin:0 0 0.4rem;
  }
  .b-desc { font-size:0.82rem;color:rgba(255,255,255,0.6);line-height:1.5;margin:0; }
  .b-tags { display:flex;gap:0.35rem;flex-wrap:wrap;margin-bottom:0.5rem; }
  .b-arrow {
    position:absolute;top:1.25rem;right:1.25rem;
    font-size:1rem;color:rgba(255,255,255,0.35);
    transition:color 0.2s,transform 0.2s;
  }
  .b-card:hover .b-arrow { color:var(--text);transform:translate(2px,-2px); }
  .b-badge {
    position:absolute;top:1.25rem;left:1.25rem;
    font-size:0.6rem;font-weight:700;letter-spacing:0.16em;text-transform:uppercase;
    color:var(--text);border:1px solid rgba(255,255,255,0.3);
    padding:0.28rem 0.7rem;border-radius:99px;backdrop-filter:blur(8px);
    background:rgba(0,0,0,0.4);
  }

  /* ── Bento size presets ─────────────────────────────── */
  /* Hero wide — spans 8 cols, 7 rows */
  .b-hero { grid-column:span 8;grid-row:span 7; }
  /* Tall right — spans 4 cols, 7 rows */
  .b-tall { grid-column:span 4;grid-row:span 7; }
  /* Wide half — 6 cols, 5 rows */
  .b-wide { grid-column:span 6;grid-row:span 5; }
  /* Square — 4 cols, 5 rows */
  .b-sq { grid-column:span 4;grid-row:span 5; }
  /* Third — 4 cols, 4 rows */
  .b-third { grid-column:span 4;grid-row:span 4; }
  /* Quarter — 3 cols, 4 rows */
  .b-qtr { grid-column:span 3;grid-row:span 4; }
  /* Full strip — 12 cols, 3 rows */
  .b-strip { grid-column:span 12;grid-row:span 3; }
  /* Two-third — 8 cols, 4 rows */
  .b-two-third { grid-column:span 8;grid-row:span 4; }

  /* Title size by card size */
  .b-hero .b-title { font-size:clamp(1.8rem,4vw,3.2rem); }
  .b-tall .b-title { font-size:clamp(1.4rem,2.5vw,2rem); }
  .b-wide .b-title,.b-sq .b-title { font-size:clamp(1.2rem,2vw,1.7rem); }
  .b-third .b-title,.b-qtr .b-title { font-size:1.1rem; }
  .b-strip .b-title { font-size:1.1rem; }

  /* Strip layout — horizontal */
  .b-strip .b-content { padding:1rem 1.5rem;display:flex;align-items:center;gap:1.5rem; }
  .b-strip .b-text { flex:1; }
  .b-strip .b-title { margin-bottom:0; }
  .b-strip .b-desc { display:none; }

  /* Placeholder (no media) */
  .b-no-media { background:#0d0d0d; }
  .b-no-media .b-overlay { background:none; }
  .b-no-media .b-content { bottom:auto;top:0;height:100%;display:flex;flex-direction:column;justify-content:flex-end; }

  /* Color accent cards */
  .b-accent-game { background:linear-gradient(135deg,#0d0d0d 0%,#0f1a2e 100%); }
  .b-accent-web  { background:linear-gradient(135deg,#0d0d0d 0%,#1a1a0e 100%); }
  .b-accent-art  { background:linear-gradient(135deg,#0d0d0d 0%,#1a0e1a 100%); }

  /* ── Section heading row ────────────────────────────── */
  .section-row {
    display:flex;align-items:baseline;gap:1.5rem;
    border-bottom:1px solid var(--border);
    padding-bottom:1.25rem;margin-bottom:2.5rem;
  }
  .section-num { font-family:var(--font-display);font-size:0.7rem;font-weight:800;letter-spacing:0.12em;color:var(--text-faint); }
  .section-title { font-family:var(--font-display);font-size:0.72rem;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:var(--text-faint);flex:1; }

  /* ── Discipline index list (different from homepage — no media, text-only) */
  .disc-list { list-style:none;padding:0;margin:0 0 5rem; }
  .disc-item {
    display:grid;
    grid-template-columns:3rem 1fr auto;
    align-items:center;
    gap:1.5rem 2rem;
    padding:1.5rem 0;
    border-bottom:1px solid var(--border);
    text-decoration:none;
    color:var(--text);
    transition:padding-left 0.3s cubic-bezier(0.16,1,0.3,1);
  }
  .disc-item:hover { padding-left:0.75rem; }
  .disc-num { font-family:var(--font-display);font-size:0.68rem;font-weight:800;letter-spacing:0.1em;color:var(--text-faint); }
  .disc-title { font-family:var(--font-display);font-size:clamp(1.3rem,3vw,2.2rem);font-weight:800;letter-spacing:-0.03em;line-height:1; }
  .disc-sub { font-size:0.85rem;color:var(--text-muted);line-height:1.6;margin-top:0.3rem; }
  .disc-tags { display:flex;gap:0.4rem;flex-wrap:wrap;margin-top:0.5rem; }
  .disc-arrow { font-size:1.5rem;color:var(--text-faint);transition:transform 0.25s,color 0.25s;flex-shrink:0; }
  .disc-item:hover .disc-arrow { transform:translate(4px,-4px);color:var(--text); }

  /* ── Bento card entrance ───────────────────────────── */
  @keyframes cardIn {
    from { opacity:0; transform:translateY(28px); }
    to   { opacity:1; transform:none; }
  }
  .bento.is-visible .b-card {
    animation: cardIn 0.55s cubic-bezier(0.16,1,0.3,1) both;
  }
  .bento.is-visible .b-card:nth-child(1)  { animation-delay: 0.04s }
  .bento.is-visible .b-card:nth-child(2)  { animation-delay: 0.09s }
  .bento.is-visible .b-card:nth-child(3)  { animation-delay: 0.14s }
  .bento.is-visible .b-card:nth-child(4)  { animation-delay: 0.19s }
  .bento.is-visible .b-card:nth-child(5)  { animation-delay: 0.24s }
  .bento.is-visible .b-card:nth-child(6)  { animation-delay: 0.29s }
  .bento.is-visible .b-card:nth-child(7)  { animation-delay: 0.34s }
  .bento.is-visible .b-card:nth-child(8)  { animation-delay: 0.39s }
  .bento.is-visible .b-card:nth-child(9)  { animation-delay: 0.44s }
  .bento.is-visible .b-card:nth-child(10) { animation-delay: 0.49s }
  .bento.is-visible .b-card:nth-child(11) { animation-delay: 0.54s }
  .bento.is-visible .b-card:nth-child(12) { animation-delay: 0.59s }
  .bento.is-visible .b-card:nth-child(13) { animation-delay: 0.64s }
  .bento.is-visible .b-card:nth-child(14) { animation-delay: 0.69s }
  .bento.is-visible .b-card:nth-child(14) { animation-delay: 0.69s }


  .bento:not(.is-visible) .b-card { opacity: 0; }
  /* bento itself shouldn't slide — only its cards animate */
  .bento.rv { transform: none !important; }

  .rv { opacity:0;transform:translateY(40px);transition:opacity 0.7s cubic-bezier(0.16,1,0.3,1),transform 0.7s cubic-bezier(0.16,1,0.3,1); }
  .rv.is-visible { opacity:1;transform:none; }
  .rv-row { opacity:0;transform:translateY(28px);transition:opacity 0.6s cubic-bezier(0.16,1,0.3,1),transform 0.6s cubic-bezier(0.16,1,0.3,1); }
  .rv-row.is-visible { opacity:1;transform:none; }
  .rv-stagger > * { opacity:0;transform:translateY(32px);transition:opacity 0.55s cubic-bezier(0.16,1,0.3,1),transform 0.55s cubic-bezier(0.16,1,0.3,1); }
  .rv-stagger.is-visible > *:nth-child(1){opacity:1;transform:none;transition-delay:0.04s}
  .rv-stagger.is-visible > *:nth-child(2){opacity:1;transform:none;transition-delay:0.10s}
  .rv-stagger.is-visible > *:nth-child(3){opacity:1;transform:none;transition-delay:0.16s}
  .rv-stagger.is-visible > *:nth-child(4){opacity:1;transform:none;transition-delay:0.22s}
  .rv-stagger.is-visible > *:nth-child(5){opacity:1;transform:none;transition-delay:0.28s}
  .rv-stagger.is-visible > *:nth-child(6){opacity:1;transform:none;transition-delay:0.34s}
  .rv-stagger.is-visible > *:nth-child(7){opacity:1;transform:none;transition-delay:0.40s}
  .rv-stagger.is-visible > *:nth-child(8){opacity:1;transform:none;transition-delay:0.46s}

  @media(max-width:900px){
    .port-hero { grid-template-columns:1fr;gap:2rem;padding-top:7rem; }
    .b-hero { grid-column:span 12;grid-row:span 6; }
    .b-tall { grid-column:span 12;grid-row:span 5; }
    .b-wide { grid-column:span 12;grid-row:span 5; }
    .b-sq   { grid-column:span 12;grid-row:span 5; }
    .b-third{ grid-column:span 12;grid-row:span 4; }
    .b-qtr  { grid-column:span 6; grid-row:span 4; }
    .b-two-third { grid-column:span 12;grid-row:span 4; }
    .b-strip{ grid-column:span 12;grid-row:span 3; }
    .disc-title { font-size:1.4rem; }
  }
  @media(max-width:540px){
    .b-qtr { grid-column:span 12;grid-row:span 4; }
    .filter-btn { font-size:0.75rem;padding:0.35rem 0.8rem; }
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

    <!-- ── HERO ─────────────────────────────────────────── -->
    <div class="pw">
      <div class="port-hero">
        <div class="port-hero-left">
          <p class="rv" style="font-size:0.7rem;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:var(--text-faint);margin-bottom:1rem">All Work</p>
          <h1 class="rv" style="transition-delay:0.06s">All<br><em>Projects.</em></h1>
        </div>
        <div class="port-hero-right rv" style="transition-delay:0.12s">
          <p>Games, websites, and everything in between. Three disciplines — gameplay programming, web development, and art &amp; design — built from scratch and shipped.</p>
          <div class="port-hero-stats rv-stagger">
            <div class="port-hero-stat">
              <span class="port-hero-stat-num">20+</span>
              <span class="port-hero-stat-label">Projects Built</span>
            </div>
            <div class="port-hero-stat">
              <span class="port-hero-stat-num">3+</span>
              <span class="port-hero-stat-label">Years Building</span>
            </div>
            <div class="port-hero-stat">
              <span class="port-hero-stat-num">1</span>
              <span class="port-hero-stat-label">Shipped Game</span>
            </div>
            <div class="port-hero-stat">
              <span class="port-hero-stat-num">3</span>
              <span class="port-hero-stat-label">Disciplines</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ── BENTO GRID ────────────────────────────────────── -->
    <div class="pw" style="padding-bottom:clamp(4rem,8vw,8rem)">

      <!-- Filter row -->
      <div class="filter-row rv">
        <span class="filter-label">Filter</span>
        <a href="#all" class="filter-btn active" data-filter="all">All</a>
        <a href="#games" class="filter-btn" data-filter="game">Games</a>
        <a href="#web" class="filter-btn" data-filter="web">Web</a>
        <a href="#art" class="filter-btn" data-filter="art">Art &amp; Design</a>
      </div>

      <!-- Grid -->
      <div class="bento rv">

        <!-- 1. Phase Runner — hero -->
        <a href="game-programming/" class="b-card b-hero" data-cat="game">
          <video class="b-media" muted loop playsinline preload="none"
                 data-src="../assets/images/phase-runner-screen.mp4"
                 poster="../assets/images/phaserunnercover.png">
          </video>
          <div class="b-overlay"></div>
          <div class="b-content">
            <div class="b-tags">
              <span class="tag">Game Programming</span>
              <span class="tag tag-muted">Godot 4</span>
              <span class="tag tag-muted">GDScript</span>
              <span class="tag tag-muted">Solo</span>
            </div>
            <h2 class="b-title">Phase Runner</h2>
            <p class="b-desc">Custom physics, 10+ weapons, procedural level chunks — solo-built and live on itch.io.</p>
          </div>
          <div class="b-badge">Featured</div>
          <span class="b-arrow">↗</span>
        </a>

        <!-- 2. VR Rhythm Game — tall -->
        <a href="game-programming/" class="b-card b-tall b-accent-game" data-cat="game">
          <video class="b-media" muted loop playsinline preload="none"
                 data-src="../assets/images/vr-gameplay.mp4"
                 poster="../assets/images/phaserunnercover.png">
          </video>
          <div class="b-overlay"></div>
          <div class="b-content">
            <span class="b-eyebrow">Lead Programmer · 5-Person Team</span>
            <div class="b-tags">
              <span class="tag tag-muted">UE5</span>
              <span class="tag tag-muted">C++</span>
              <span class="tag tag-muted">VR</span>
            </div>
            <h2 class="b-title">VR Rhythm Game</h2>
            <p class="b-desc">Body-movement dragon controller in Unreal Engine 5. Custom C++ locomotion, OpenXR.</p>
          </div>
          <span class="b-arrow">↗</span>
        </a>

        <!-- 3. Penguins Creed — wide -->
        <a href="game-programming/" class="b-card b-wide b-accent-game" data-cat="game">
          <video class="b-media" muted loop playsinline preload="none"
                 data-src="../assets/images/penguins-creed.mp4"
                 poster="../assets/images/shelcover.png">
          </video>
          <div class="b-overlay"></div>
          <div class="b-content">
            <span class="b-eyebrow">UE5 · Behavior Trees</span>
            <h2 class="b-title">Penguins Creed</h2>
            <p class="b-desc">Third-person stealth-action — patrol AI, line-of-sight detection, NavMesh chase.</p>
          </div>
          <span class="b-arrow">↗</span>
        </a>

        <!-- 4. 33 Miles — square -->
        <a href="professional-works/33-miles-graphics/" class="b-card b-sq" data-cat="art">
          <img class="b-media" src="professional-works/33-miles-graphics/images/thumbnails/33-miles-01-grain-regular.png" alt="33 Miles" style="opacity:0.85">
          <div class="b-overlay"></div>
          <div class="b-content">
            <span class="b-eyebrow">Paid Client Work</span>
            <div class="b-tags">
              <span class="tag tag-muted">Branding</span>
              <span class="tag tag-muted">Illustrator</span>
            </div>
            <h2 class="b-title">33 Miles Band Graphics</h2>
          </div>
          <span class="b-arrow">↗</span>
        </a>

        <!-- 5. Mario Kart — wide -->
        <a href="/MarioKartLatest/" class="b-card b-wide b-accent-web" data-cat="web">
          <video class="b-media" muted loop playsinline preload="none"
                 data-src="../assets/images/mariokart.mp4"
                 poster="../assets/images/mariokart.png">
          </video>
          <div class="b-overlay"></div>
          <div class="b-content">
            <span class="b-eyebrow">Web Game · Playable</span>
            <div class="b-tags">
              <span class="tag tag-muted">JavaScript</span>
              <span class="tag tag-muted">Mode-7</span>
            </div>
            <h2 class="b-title">Mario Kart Recreation</h2>
            <p class="b-desc">SNES Mode-7 renderer from scratch — raycasting, sprite sheets, lap logic. Play now.</p>
          </div>
          <div class="b-badge">Playable ↗</div>
          <span class="b-arrow">↗</span>
        </a>

        <!-- 6. Mediterranean Environment — placeholder (coming soon) -->
        <a href="art/" class="b-card b-sq b-accent-art" data-cat="art">
          <video class="b-media" muted loop playsinline preload="none"
                 data-src="../assets/images/environment-scene.mp4"
                 poster="../assets/images/shelcover.png">
          </video>
          <div class="b-overlay"></div>
          <div class="b-content">
            <span class="b-eyebrow">3D Art · Unreal Engine 5</span>
            <div class="b-tags">
              <span class="tag tag-muted">Lumen GI</span>
              <span class="tag tag-muted">Nanite</span>
            </div>
            <h2 class="b-title">Mediterranean Environment</h2>
            <p class="b-desc">Photorealistic coastal scene — custom lighting, modular arch, atmospheric FX.</p>
          </div>
          <div class="b-badge">Coming Soon</div>
        </a>

        <!-- 7. T-Shirt Designs — third -->
        <a href="tshirt-designs/" class="b-card b-third" data-cat="art">
          <img class="b-media" src="tshirt-designs/images/thumbnails/Caribbean%20Party.svg" alt="T-Shirt Designs" style="object-fit:contain;padding:0.75rem;opacity:0.9">
          <div class="b-overlay"></div>
          <div class="b-content">
            <span class="b-eyebrow">15+ Apparel Runs</span>
            <h2 class="b-title">T-Shirt Designs</h2>
          </div>
          <span class="b-arrow">↗</span>
        </a>

        <!-- 8. Pi Kappa Phi T-Shirt Web — third -->
        <a href="web-programming/PiKappaPhiTshirtWeb2.0/" class="b-card b-third b-accent-web" data-cat="web">
          <img class="b-media" src="web-programming/PiKappaPhiTshirtWeb2.0/vectors/banner_logo.svg" alt="Pi Kappa Phi Web" style="object-fit:contain;padding:1.25rem;opacity:0.85">
          <div class="b-overlay" style="background:linear-gradient(to top,rgba(0,0,0,0.92) 0%,rgba(0,0,0,0.1) 100%)"></div>
          <div class="b-content">
            <span class="b-eyebrow">Full-Stack PHP App</span>
            <h2 class="b-title">PKP T-Shirt Platform</h2>
          </div>
          <span class="b-arrow">↗</span>
        </a>

        <!-- 9. TechBirmingham — third -->
        <a href="web-programming/" class="b-card b-third b-accent-web" data-cat="web" id="tb-card">
          <!-- Static logo shown by default -->
          <img class="b-media" src="../assets/images/tb-logo.jpg" alt="TechBirmingham" style="object-fit:contain;padding:1.5rem;opacity:0.6;transition:opacity 0.3s;">
          <!-- Lottie loading screen shown on hover -->
          <div id="tb-lottie-wrap" style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;opacity:0;transition:opacity 0.3s;pointer-events:none;background:#fff;border-radius:12px;">
            <lottie-player
              id="tb-lottie"
              src="/portfolio/web-programming/TechBirminghamSponsorAI/public/Frame-1-Playful.json"
              background="white"
              speed="1"
              style="width:100%;height:100%;"
            ></lottie-player>
            <!-- Dark gradient so white card text stays readable over white animation -->
            <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,0.82) 0%,rgba(0,0,0,0.1) 55%,transparent 100%);border-radius:12px;pointer-events:none;"></div>
          </div>
          <div class="b-overlay" style="background:linear-gradient(to top,rgba(0,0,0,0.92) 0%,rgba(0,0,0,0.1) 100%);transition:opacity 0.3s;"></div>
          <div class="b-content">
            <span class="b-eyebrow">AI · Next.js</span>
            <h2 class="b-title">TechBirmingham Sponsor AI</h2>
          </div>
          <span class="b-arrow">↗</span>
        </a>

        <!-- 11. College Guys PW — qtr -->
        <a href="professional-works/College%20Guys%20Pressure%20Washing/" class="b-card b-qtr b-accent-art" data-cat="art">
          <img class="b-media" src="professional-works/College%20Guys%20Pressure%20Washing/College%20Guys%20Pressure%20Washing%20Banner.svg" alt="College Guys Pressure Washing" style="object-fit:contain;padding:1rem;opacity:0.75">
          <div class="b-overlay" style="background:linear-gradient(to top,rgba(0,0,0,0.92) 0%,rgba(0,0,0,0.1) 100%)"></div>
          <div class="b-content">
            <span class="b-eyebrow">Client Work</span>
            <h2 class="b-title">College Guys Pressure Washing</h2>
          </div>
          <span class="b-arrow">↗</span>
        </a>

        <!-- 12. Venice — qtr -->
        <a href="art/" class="b-card b-qtr b-accent-art" data-cat="art">
          <img class="b-media" src="../assets/images/venice-art.jpg" alt="Venice" style="object-fit:cover;object-position:center top;opacity:0.85">
          <div class="b-overlay" style="background:linear-gradient(to top,rgba(0,0,0,0.88) 0%,rgba(0,0,0,0.1) 100%)"></div>
          <div class="b-content">
            <span class="b-eyebrow">Juried Art Show 2025</span>
            <h2 class="b-title">Venice</h2>
          </div>
          <div class="b-badge">Fine Art</div>
          <span class="b-arrow">↗</span>
        </a>

        <!-- 13. This portfolio — qtr -->
        <a href="../index.php" class="b-card b-qtr b-accent-web" data-cat="web">
          <img class="b-media" src="../assets/images/jb-logo.png" alt="Jake Barton Portfolio" style="object-fit:contain;padding:1.5rem;opacity:0.4">
          <div class="b-overlay" style="background:linear-gradient(to top,rgba(0,0,0,0.92) 0%,rgba(0,0,0,0.1) 100%)"></div>
          <div class="b-content">
            <span class="b-eyebrow">PHP · JS · GSAP</span>
            <h2 class="b-title">jakebartoncreative.com</h2>
          </div>
          <span class="b-arrow">↗</span>
        </a>

        <!-- 14. DeskPet — qtr -->
        <a href="game-programming/DeskPet/DeskPet_Simple.dmg" download class="b-card b-qtr b-accent-game" data-cat="game">
          <img class="b-media" src="game-programming/DeskPet/AppIcon.png" alt="DeskPet" style="object-fit:contain;padding:1.25rem;opacity:0.85">
          <div class="b-overlay" style="background:linear-gradient(to top,rgba(0,0,0,0.88) 0%,rgba(0,0,0,0.1) 100%)"></div>
          <div class="b-content">
            <span class="b-eyebrow">macOS App</span>
            <h2 class="b-title">DeskPet</h2>
            <p class="b-desc" style="font-size:0.78rem">Download for Mac →</p>
          </div>
        </a>

      </div><!-- /.bento -->

      <!-- ── DISCIPLINE INDEX ─────────────────────────── -->
      <div class="section-row rv">
        <span class="section-num">02</span>
        <span class="section-title">Browse by Discipline</span>
      </div>

      <ul class="disc-list">
        <li><a href="game-programming/" class="disc-item rv-row">
          <span class="disc-num">01</span>
          <div>
            <div class="disc-title">Game Programming</div>
            <div class="disc-sub">Godot 4, Unreal Engine 5, C++ — solo indie games to team-built VR. 3 full case studies.</div>
            <div class="disc-tags">
              <span class="tag tag-muted">Godot 4</span>
              <span class="tag tag-muted">UE5</span>
              <span class="tag tag-muted">C++</span>
              <span class="tag tag-muted">GDScript</span>
            </div>
          </div>
          <span class="disc-arrow">↗</span>
        </a></li>

        <li><a href="web-programming/" class="disc-item rv-row">
          <span class="disc-num">02</span>
          <div>
            <div class="disc-title">Web Programming</div>
            <div class="disc-sub">Full-stack PHP apps, JavaScript tools, AI integrations, and e-commerce platforms.</div>
            <div class="disc-tags">
              <span class="tag tag-muted">PHP</span>
              <span class="tag tag-muted">JavaScript</span>
              <span class="tag tag-muted">Next.js</span>
              <span class="tag tag-muted">MySQL</span>
            </div>
          </div>
          <span class="disc-arrow">↗</span>
        </a></li>

        <li><a href="art/" class="disc-item rv-row">
          <span class="disc-num">03</span>
          <div>
            <div class="disc-title">Art &amp; Design</div>
            <div class="disc-sub">3D modelling, photorealistic environment art, client branding, and apparel design.</div>
            <div class="disc-tags">
              <span class="tag tag-muted">Maya</span>
              <span class="tag tag-muted">Blender</span>
              <span class="tag tag-muted">Illustrator</span>
              <span class="tag tag-muted">Substance</span>
            </div>
          </div>
          <span class="disc-arrow">↗</span>
        </a></li>

        <li><a href="games/" class="disc-item rv-row">
          <span class="disc-num">04</span>
          <div>
            <div class="disc-title">Playable Games</div>
            <div class="disc-sub">Phase Runner and Mario Kart — run them right here in your browser.</div>
            <div class="disc-tags">
              <span class="tag tag-muted">Browser</span>
              <span class="tag tag-muted">WebGL</span>
              <span class="tag tag-muted">JavaScript</span>
            </div>
          </div>
          <span class="disc-arrow">↗</span>
        </a></li>

        <li><a href="professional-works/" class="disc-item rv-row">
          <span class="disc-num">05</span>
          <div>
            <div class="disc-title">Professional Works</div>
            <div class="disc-sub">Paid client projects — band graphics, pressure washing brand, apparel campaigns.</div>
            <div class="disc-tags">
              <span class="tag tag-muted">Client Work</span>
              <span class="tag tag-muted">Branding</span>
              <span class="tag tag-muted">Print</span>
            </div>
          </div>
          <span class="disc-arrow">↗</span>
        </a></li>
      </ul>

    </div><!-- /.pw -->

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
    /* Scroll reveals */
    (function(){
      var singles=document.querySelectorAll('.rv');
      var sObs=new IntersectionObserver(function(e){e.forEach(function(x){if(x.isIntersecting){x.target.classList.add('is-visible');sObs.unobserve(x.target);}});},{threshold:0.1,rootMargin:'0px 0px -40px 0px'});
      singles.forEach(function(el){sObs.observe(el);});

      var rows=document.querySelectorAll('.rv-row');
      var rObs=new IntersectionObserver(function(e){e.forEach(function(x){if(x.isIntersecting){var sibs=x.target.parentElement.querySelectorAll('.rv-row');var idx=0;sibs.forEach(function(s,i){if(s===x.target)idx=i;});x.target.style.transitionDelay=(idx*0.07)+'s';x.target.classList.add('is-visible');rObs.unobserve(x.target);}});},{threshold:0.08,rootMargin:'0px 0px -20px 0px'});
      rows.forEach(function(el){rObs.observe(el);});

      var grps=document.querySelectorAll('.rv-stagger');
      var gObs=new IntersectionObserver(function(e){e.forEach(function(x){if(x.isIntersecting){x.target.classList.add('is-visible');gObs.unobserve(x.target);}});},{threshold:0.08,rootMargin:'0px 0px -30px 0px'});
      grps.forEach(function(el){gObs.observe(el);});
    })();
    /* Lazy-load bento videos — IntersectionObserver */
    (function(){
      var vids = document.querySelectorAll('.b-media[data-src]');
      if (!vids.length) return;
      var vObs = new IntersectionObserver(function(entries){
        entries.forEach(function(entry){
          var v = entry.target;
          if (entry.isIntersecting) {
            if (v.dataset.src && !v.getAttribute('src')) {
              v.src = v.dataset.src;
              v.load();
            }
            v.play().catch(function(){});
          } else {
            if (!v.paused) v.pause();
          }
        });
      }, { threshold: 0.25 });
      vids.forEach(function(v){ vObs.observe(v); });
    })();
    /* Filter */
    (function(){
      var btns=document.querySelectorAll('.filter-btn');
      var cards=document.querySelectorAll('.b-card[data-cat]');
      btns.forEach(function(btn){
        btn.addEventListener('click',function(e){
          e.preventDefault();
          btns.forEach(function(b){b.classList.remove('active');});
          btn.classList.add('active');
          var f=btn.getAttribute('data-filter');
          cards.forEach(function(c){
            if(f==='all'||c.getAttribute('data-cat')===f){
              c.style.opacity='1';c.style.pointerEvents='auto';
            } else {
              c.style.opacity='0.15';c.style.pointerEvents='none';
            }
          });
        });
      });
    })();
  </script>

  <!-- Lottie Web player -->
  <script src="https://unpkg.com/@lottiefiles/lottie-player@2/dist/lottie-player.js"></script>
  <script>
    /* TechBirmingham card — play Lottie on hover */
    (function() {
      var card  = document.getElementById('tb-card');
      var wrap  = document.getElementById('tb-lottie-wrap');
      var logo  = card ? card.querySelector('img.b-media') : null;
      var overlay = card ? card.querySelector('.b-overlay') : null;
      var player = document.getElementById('tb-lottie');
      if (!card || !wrap || !player) return;

      card.addEventListener('mouseenter', function() {
        wrap.style.opacity = '1';
        if (logo) logo.style.opacity = '0';
        player.stop();
        player.play();
      });
      card.addEventListener('mouseleave', function() {
        wrap.style.opacity = '0';
        if (logo) logo.style.opacity = '0.6';
        player.stop();
      });
    })();
  </script>
</body>
</html>
