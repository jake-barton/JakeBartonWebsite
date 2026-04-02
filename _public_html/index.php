<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jake Barton — Gameplay Programmer & Technical Designer</title>
    <link rel="icon" type="image/svg+xml" href="assets/images/favicon.svg?v=20260325">
    <link rel="alternate icon" href="assets/images/favicon.svg?v=20260325">

    <!-- Style Kit CSS (order matters) -->
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/animations.css">
    <link rel="stylesheet" href="assets/css/components.css">
</head>
<body>
  <!-- Scroll progress line -->
  <div id="scroll-progress" style="position:fixed;top:0;left:0;height:2px;width:0%;background:var(--accent);z-index:100001;transition:width 0.1s linear;pointer-events:none"></div>

  <!-- Ambient cursor glow -->
  <div id="cursor-glow" style="position:fixed;top:0;left:0;width:420px;height:420px;border-radius:50%;background:radial-gradient(circle,rgba(255,255,255,0.05) 0%,transparent 70%);pointer-events:none;z-index:0;transform:translate(-50%,-50%);transition:opacity 0.3s ease;opacity:0"></div>


<?php
require_once __DIR__ . '/includes/content.php';

// Legacy aliases so existing $contact references below still work
$contact = [
    'email'     => $content['email'],
    'phone'     => $content['phone'],
    'website'   => $content['website'],
    'address'   => $content['location'],
    'instagram' => $content['instagram'],
    'github'    => $content['github'],
    'youtube'   => '',
];
?>

  <!-- Navigation -->
  <header class="site-nav" id="site-nav">
    <a href="#home" class="nav-logo">
      <img src="assets/images/jb-logo.png" alt="Jake Barton" class="nav-logo-img">
      <span class="nav-logo-text">JB</span>
    </a>
    <nav class="nav-links">
      <a href="#about">About</a>
      <a href="#skills">Skills</a>
      <a href="portfolio/">Portfolio</a>
      <a href="assets/Jake%20Barton%20-%20Resume.pdf" download>Resume</a>
      <a href="#contact">Contact</a>
    </nav>
    <!-- sm-toggle injected by staggered-menu.js -->
  </header>
  <!-- sm-panel + sm-prelayers injected by staggered-menu.js -->

  <!-- Main Content -->
  <main class="site-content">

    <!-- ── Hero Section ──────────────────────────────────── -->
    <section class="hero" id="home">

      <!-- Top meta row -->
      <div class="hero-meta">
        <span class="hero-eyebrow-tag"><?php echo $content['hero_eyebrow']; ?></span>
        <span class="hero-location-tag">Birmingham, AL</span>
      </div>

      <!-- The name — takes up the full visual weight -->
      <div class="hero-name-block">
        <div class="xl-reveal"><span><?php echo explode(' ', $content['name'])[0]; ?></span></div>
        <div class="xl-reveal" style="transition-delay:0.08s"><span><?php echo explode(' ', $content['name'])[1]; ?></span></div>
      </div>

      <!-- Social icons under name -->
      <div class="hero-social-icons">
        <a href="https://github.com/jake-barton" target="_blank" rel="noopener" class="hero-social-link" aria-label="GitHub">
          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 0C5.37 0 0 5.37 0 12c0 5.3 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61-.546-1.385-1.335-1.755-1.335-1.755-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23A11.52 11.52 0 0 1 12 5.803c1.02.005 2.047.138 3.006.404 2.29-1.552 3.297-1.23 3.297-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.807 5.625-5.479 5.92.43.372.823 1.102.823 2.222 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 21.795 24 17.295 24 12c0-6.63-5.37-12-12-12z"/>
          </svg>
        </a>
        <a href="https://www.linkedin.com/in/jakebartoncreative" target="_blank" rel="noopener" class="hero-social-link" aria-label="LinkedIn">
          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
          </svg>
        </a>
      </div>
      <div class="hero-bottom">
        <div class="hero-bottom-left">
          <p class="hero-tagline">
            Game developer building<br>
            <em class="rotating-text" data-words='<?php echo json_encode($content['hero_rotating_words']); ?>' data-interval="2800"><?php echo $content['hero_rotating_words'][0]; ?></em><br>
            from game engines to the browser.
          </p>
          <div class="hero-cta">
            <a href="/portfolio/" class="btn btn-primary">See My Work</a>
            <a href="#contact" class="btn btn-ghost">Get in Touch</a>
          </div>
        </div>
        <div class="hero-bottom-right">
          <p class="hero-subtitle"><?php echo $content['hero_subtitle']; ?></p>
          <div class="hero-status-badge">
            <span class="hero-status-pulse"></span>Open to Work — Birmingham, AL
          </div>
        </div>
      </div>

    </section>

    <!-- ── Stats bar (full-bleed) ────────────────────────── -->
    <div class="stats-bar">
      <?php foreach ($content['hero_stats'] as $i => $stat): ?>
        <?php if ($i > 0): ?><div class="stats-bar-divider"></div><?php endif; ?>
        <div class="stats-bar-item">
          <span class="stats-bar-num" data-count="<?php echo $stat['num']; ?>" data-suffix="<?php echo $stat['suffix']; ?>"><?php echo $stat['num'] . $stat['suffix']; ?></span>
          <span class="stats-bar-label"><?php echo $stat['label']; ?></span>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- ── Scrolling ticker ──────────────────────────────── -->
    <div class="bold-ticker" aria-hidden="true">
      <div class="bold-ticker-track">
        <?php for ($i = 0; $i < 2; $i++): ?>
          <span>Game Design</span><span class="bull">·</span>
          <span>Unreal Engine 5</span><span class="bull">·</span>
          <span>3D Art</span><span class="bull">·</span>
          <span>Web Development</span><span class="bull">·</span>
          <span>C++</span><span class="bull">·</span>
          <span>JavaScript</span><span class="bull">·</span>
          <span>VR Development</span><span class="bull">·</span>
          <span>Level Design</span><span class="bull">·</span>
          <span>Godot 4</span><span class="bull">·</span>
          <span>UI / UX</span><span class="bull">·</span>
          <span>Samford University</span><span class="bull">·</span>
        <?php endfor; ?>
      </div>
    </div>

    <!-- ── Selected Work  (sticky scroll accordion) ──────── -->
    <section class="work-section" id="work">

      <!-- Section label row (outside sticky, scrolls normally) -->
      <div class="work-label-row">
        <span class="work-label-num">01</span>
        <span class="work-label-text">Selected Work</span>
        <a href="/portfolio/" class="work-label-link">View All →</a>
      </div>

      <!-- Scroll tunnel — its height gives the scroll room -->
      <div class="work-scroll-tunnel" id="workTunnel">

        <!-- Sticky viewport — this never moves -->
        <div class="work-sticky" id="workSticky">
          <div class="wa-stack" id="waStack">

            <!-- Card 0 — Phase Runner (featured) -->
            <a href="https://clervercarpet99.itch.io/phase-runner" target="_blank"
               class="wa-card" data-idx="0">
              <!-- full-bleed media -->
              <video class="wa-media" autoplay muted loop playsinline preload="auto">
                <source src="assets/images/phase-runner-screen.mp4" type="video/mp4">
              </video>
              <div class="wa-grad"></div>
              <!-- expanded content -->
              <div class="wa-content">
                <div class="wa-tags">
                  <span class="tag">Game Design</span>
                  <span class="tag tag-muted">Godot 4</span>
                  <span class="tag tag-muted">GDScript</span>
                  <span class="tag tag-muted">Solo</span>
                </div>
                <h2 class="wa-title">Phase Runner</h2>
                <p class="wa-desc">2D side-scrolling shooter with custom physics, 10+ weapons, procedural level chunks, and invincibility dash. Solo-developed and live on itch.io.</p>
                <span class="wa-cta">Play on itch.io ↗</span>
              </div>
              <div class="wa-badge">Featured</div>
              <!-- collapsed bar -->
              <div class="wa-bar">
                <span class="wa-bar-num">01</span>
                <div class="wa-bar-thumb">
                  <video autoplay muted loop playsinline preload="metadata">
                    <source src="assets/images/phase-runner-screen.mp4" type="video/mp4">
                  </video>
                </div>
                <span class="wa-bar-title">Phase Runner</span>
                <div class="wa-bar-tags">
                  <span class="tag tag-muted">Game Design</span>
                  <span class="tag tag-muted">Godot 4</span>
                </div>
                <span class="wa-bar-arrow">↗</span>
              </div>
            </a>

            <!-- Card 1 — Mediterranean Environment -->
            <a href="portfolio/game-programming/" class="wa-card" data-idx="1">
              <video class="wa-media" autoplay muted loop playsinline preload="metadata">
                <source src="assets/images/environment-scene.mp4" type="video/mp4">
              </video>
              <div class="wa-grad"></div>
              <div class="wa-content">
                <div class="wa-tags">
                  <span class="tag tag-muted">3D Art</span>
                  <span class="tag tag-muted">Unreal 5</span>
                  <span class="tag tag-muted">Real-time</span>
                </div>
                <h2 class="wa-title">Mediterranean Environment</h2>
                <p class="wa-desc">Real-time 3D scene built in Unreal Engine 5 — custom lighting, modular architecture, atmospheric FX.</p>
                <span class="wa-cta">View Project ↗</span>
              </div>
              <div class="wa-bar">
                <span class="wa-bar-num">02</span>
                <div class="wa-bar-thumb">
                  <video autoplay muted loop playsinline preload="metadata">
                    <source src="assets/images/environment-scene.mp4" type="video/mp4">
                  </video>
                </div>
                <span class="wa-bar-title">Mediterranean Environment</span>
                <div class="wa-bar-tags">
                  <span class="tag tag-muted">3D Art</span>
                  <span class="tag tag-muted">Unreal 5</span>
                </div>
                <span class="wa-bar-arrow">↗</span>
              </div>
            </a>

            <!-- Card 2 — VR Rhythm Game -->
            <a href="portfolio/game-programming/" class="wa-card" data-idx="2">
              <video class="wa-media" autoplay muted loop playsinline preload="metadata">
                <source src="assets/images/vr-gameplay.mp4" type="video/mp4">
              </video>
              <div class="wa-grad"></div>
              <div class="wa-content">
                <div class="wa-tags">
                  <span class="tag tag-muted">VR</span>
                  <span class="tag tag-muted">Unreal 5</span>
                  <span class="tag tag-muted">C++</span>
                </div>
                <h2 class="wa-title">VR Rhythm Game</h2>
                <p class="wa-desc">Body-movement dragon controller in Unreal Engine 5 — C++ gameplay, VR locomotion, rhythm mechanics.</p>
                <span class="wa-cta">View Project ↗</span>
              </div>
              <div class="wa-bar">
                <span class="wa-bar-num">03</span>
                <div class="wa-bar-thumb">
                  <video autoplay muted loop playsinline preload="metadata">
                    <source src="assets/images/vr-gameplay.mp4" type="video/mp4">
                  </video>
                </div>
                <span class="wa-bar-title">VR Rhythm Game</span>
                <div class="wa-bar-tags">
                  <span class="tag tag-muted">VR</span>
                  <span class="tag tag-muted">C++</span>
                </div>
                <span class="wa-bar-arrow">↗</span>
              </div>
            </a>

            <!-- Card 3 — Penguins Creed -->
            <a href="portfolio/game-programming/" class="wa-card" data-idx="3">
              <video class="wa-media" autoplay muted loop playsinline preload="metadata">
                <source src="assets/images/penguins-creed.mp4" type="video/mp4">
              </video>
              <div class="wa-grad"></div>
              <div class="wa-content">
                <div class="wa-tags">
                  <span class="tag tag-muted">Game Design</span>
                  <span class="tag tag-muted">Unreal 5</span>
                  <span class="tag tag-muted">Blueprints</span>
                </div>
                <h2 class="wa-title">Penguins Creed</h2>
                <p class="wa-desc">Third-person action game with stealth mechanics, AI patrol systems, and a penguin protagonist.</p>
                <span class="wa-cta">View Project ↗</span>
              </div>
              <div class="wa-bar">
                <span class="wa-bar-num">04</span>
                <div class="wa-bar-thumb">
                  <video autoplay muted loop playsinline preload="metadata">
                    <source src="assets/images/penguins-creed.mp4" type="video/mp4">
                  </video>
                </div>
                <span class="wa-bar-title">Penguins Creed</span>
                <div class="wa-bar-tags">
                  <span class="tag tag-muted">Game Design</span>
                  <span class="tag tag-muted">Blueprints</span>
                </div>
                <span class="wa-bar-arrow">↗</span>
              </div>
            </a>

            <!-- Card 4 — Mario Kart Recreation -->
            <a href="/MarioKartLatest/" class="wa-card" data-idx="4">
              <video class="wa-media" autoplay muted loop playsinline preload="metadata">
                <source src="assets/images/mariokart.mp4" type="video/mp4">
              </video>
              <div class="wa-grad"></div>
              <div class="wa-content">
                <div class="wa-tags">
                  <span class="tag tag-muted">Web Game</span>
                  <span class="tag tag-muted">JavaScript</span>
                  <span class="tag tag-muted">Playable</span>
                </div>
                <h2 class="wa-title">Mario Kart Recreation</h2>
                <p class="wa-desc">Mode-7 SNES renderer in vanilla JS — raycasting, sprite sheets, full lap logic. Playable in browser.</p>
                <span class="wa-cta">Play Now ↗</span>
              </div>
              <div class="wa-badge">Playable</div>
              <div class="wa-bar">
                <span class="wa-bar-num">05</span>
                <div class="wa-bar-thumb">
                  <video autoplay muted loop playsinline preload="metadata">
                    <source src="assets/images/mariokart.mp4" type="video/mp4">
                  </video>
                </div>
                <span class="wa-bar-title">Mario Kart Recreation</span>
                <div class="wa-bar-tags">
                  <span class="tag tag-muted">Web Game</span>
                  <span class="tag tag-muted">JS</span>
                </div>
                <span class="wa-bar-arrow">↗</span>
              </div>
            </a>

            <!-- Card 5 — Venice -->
            <a href="portfolio/art/" class="wa-card" data-idx="5">
              <img class="wa-media" src="assets/images/venice-art.jpg" alt="Venice" style="object-position:center top">
              <div class="wa-grad"></div>
              <div class="wa-content">
                <div class="wa-tags">
                  <span class="tag tag-muted">Fine Art</span>
                  <span class="tag tag-muted">Digital</span>
                  <span class="tag tag-muted">Juried Show</span>
                </div>
                <h2 class="wa-title">Venice</h2>
                <p class="wa-desc">Digital art piece accepted into the Samford University Juried Art Show 2025.</p>
                <span class="wa-cta">View Project ↗</span>
              </div>
              <div class="wa-bar">
                <span class="wa-bar-num">06</span>
                <div class="wa-bar-thumb">
                  <img src="assets/images/venice-art.jpg" alt="Venice" style="object-position:center top">
                </div>
                <span class="wa-bar-title">Venice</span>
                <div class="wa-bar-tags">
                  <span class="tag tag-muted">Fine Art</span>
                  <span class="tag tag-muted">Digital</span>
                </div>
                <span class="wa-bar-arrow">↗</span>
              </div>
            </a>

            <!-- Card 6 — 33 Miles Band Graphics -->
            <a href="portfolio/professional-works/" class="wa-card" data-idx="6">
              <img class="wa-media" src="assets/images/33miles-cover.png" alt="33Miles Band Graphics">
              <div class="wa-grad"></div>
              <div class="wa-content">
                <div class="wa-tags">
                  <span class="tag tag-muted">Client Work</span>
                  <span class="tag tag-muted">Illustrator</span>
                </div>
                <h2 class="wa-title">33 Miles Band</h2>
                <p class="wa-desc">Paid brand &amp; merchandise design for a signed Christian music group.</p>
                <span class="wa-cta">View Project ↗</span>
              </div>
              <div class="wa-bar">
                <span class="wa-bar-num">07</span>
                <div class="wa-bar-thumb">
                  <img src="assets/images/33miles-cover.png" alt="33Miles">
                </div>
                <span class="wa-bar-title">33 Miles Band Graphics</span>
                <div class="wa-bar-tags">
                  <span class="tag tag-muted">Client Work</span>
                  <span class="tag tag-muted">Illustrator</span>
                </div>
                <span class="wa-bar-arrow">↗</span>
              </div>
            </a>

          </div><!-- /wa-stack -->
        </div><!-- /work-sticky -->
      </div><!-- /work-scroll-tunnel -->

      <div class="work-footer">
        <a href="portfolio/" class="btn btn-primary">Explore Full Portfolio →</a>
      </div>

    </section>

    <!-- ── Skills Section ────────────────────────────────── -->
    <section class="section" id="skills">
      <div class="container">
        <div class="section-label-row reveal-up">
          <span class="work-label-num">02</span>
          <span class="work-label-text">Toolkit</span>
        </div>
        <h2 class="section-big-heading reveal-up">Skills &amp; Tools</h2>

        <div class="skills-grid stagger-reveal reveal-up">

          <div class="skill-group glass-card">
            <div class="skill-group-header">
              <span class="skill-group-icon">GD</span>
              <h3 class="skill-group-title">Game Development</h3>
            </div>
            <div class="skill-group-pills stagger-pop">
              <span class="skill-pill primary"><span class="dot"></span>Unreal Engine 5</span>
              <span class="skill-pill primary"><span class="dot"></span>Godot 4</span>
              <span class="skill-pill primary"><span class="dot"></span>Unreal Blueprint</span>
              <span class="skill-pill"><span class="dot"></span>Unity</span>
              <span class="skill-pill"><span class="dot"></span>Level Design</span>
              <span class="skill-pill"><span class="dot"></span>Game Programming</span>
            </div>
          </div>

          <div class="skill-group glass-card">
            <div class="skill-group-header">
              <span class="skill-group-icon">&lt;/&gt;</span>
              <h3 class="skill-group-title">Programming</h3>
            </div>
            <div class="skill-group-pills stagger-pop">
              <span class="skill-pill primary"><span class="dot"></span>C++</span>
              <span class="skill-pill primary"><span class="dot"></span>Python</span>
              <span class="skill-pill primary"><span class="dot"></span>JavaScript</span>
              <span class="skill-pill"><span class="dot"></span>HTML &amp; CSS</span>
              <span class="skill-pill"><span class="dot"></span>PHP</span>
              <span class="skill-pill"><span class="dot"></span>Web Development</span>
            </div>
          </div>

          <div class="skill-group glass-card">
            <div class="skill-group-header">
              <span class="skill-group-icon">ART</span>
              <h3 class="skill-group-title">Art &amp; Design</h3>
            </div>
            <div class="skill-group-pills stagger-pop">
              <span class="skill-pill primary"><span class="dot"></span>Autodesk Maya</span>
              <span class="skill-pill primary"><span class="dot"></span>Adobe Illustrator</span>
              <span class="skill-pill"><span class="dot"></span>Blender</span>
              <span class="skill-pill"><span class="dot"></span>Substance Painter</span>
              <span class="skill-pill"><span class="dot"></span>Photoshop</span>
              <span class="skill-pill"><span class="dot"></span>Figma</span>
            </div>
          </div>

        </div>
      </div>
    </section>

    <!-- ── About Section ─────────────────────────────────── -->
    <section class="about-section" id="about">
      <div class="container">
        <div class="section-label-row reveal-up">
          <span class="work-label-num">03</span>
          <span class="work-label-text">About Me</span>
        </div>
      </div>

      <!-- Full-bleed heading + image side-by-side -->
      <div class="about-editorial">
        <div class="about-editorial-text reveal-left">
          <h2 class="about-big-heading"><?php echo $content['about_heading']; ?></h2>
          <?php foreach ($content['about_paragraphs'] as $i => $para): ?>
          <p class="about-para" style="transition-delay:<?php echo 0.1 + $i * 0.08; ?>s"><?php echo $para; ?></p>
          <?php endforeach; ?>
          <div class="about-actions reveal-up" style="transition-delay:0.35s">
            <a href="/portfolio/" class="btn btn-primary">View Portfolio</a>
            <a href="https://github.com/<?php echo $content['github']; ?>" target="_blank" class="btn btn-ghost">GitHub</a>
          </div>
        </div>
        <div class="about-editorial-creds reveal-right">
          <div class="cred-card glass-card">
            <div class="cred-row">
              <span class="cred-label">Degree</span>
              <span class="cred-value"><?php echo $content['major']; ?></span>
            </div>
            <div class="cred-row">
              <span class="cred-label">Minor</span>
              <span class="cred-value"><?php echo $content['minor']; ?></span>
            </div>
            <div class="cred-row">
              <span class="cred-label">University</span>
              <span class="cred-value"><?php echo $content['university']; ?></span>
            </div>
            <div class="cred-row">
              <span class="cred-label">GPA</span>
              <span class="cred-value" style="font-weight:700"><?php echo $content['gpa']; ?></span>
            </div>
            <div class="cred-row">
              <span class="cred-label">Graduation</span>
              <span class="cred-value"><?php echo $content['grad_date']; ?></span>
            </div>
            <div class="cred-row">
              <span class="cred-label">Location</span>
              <span class="cred-value"><?php echo $content['location']; ?></span>
            </div>
            <div class="cred-row" style="border-bottom:none">
              <span class="cred-label">Status</span>
              <span class="cred-value"><span style="color:#3ddb74">●</span> Open to Work</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ── Experience (horizontal index) ─────────────────── -->
    <section class="section" id="experience">
      <div class="container">
        <div class="section-label-row reveal-up">
          <span class="work-label-num">04</span>
          <span class="work-label-text">Experience &amp; Leadership</span>
        </div>
        <div class="exp-list stagger-reveal">
          <?php foreach ($content['experience'] as $i => $exp): ?>
          <div class="exp-item reveal-up" style="transition-delay:<?php echo $i * 0.07; ?>s">
            <span class="exp-dates"><?php echo $exp['dates']; ?></span>
            <div class="exp-main">
              <h3 class="exp-role"><?php echo $exp['role']; ?></h3>
              <p class="exp-org" style="<?php echo $exp['org_style']; ?>"><?php echo $exp['org']; ?></p>
            </div>
            <ul class="exp-bullets">
              <?php foreach ($exp['bullets'] as $b): ?>
              <li><?php echo $b; ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- ── Contact Section ───────────────────────────────── -->
    <section class="section" id="contact">
      <div class="container">
        <div class="section-label-row reveal-up">
          <span class="work-label-num">05</span>
          <span class="work-label-text">Get In Touch</span>
        </div>

        <div class="contact-grid stagger-reveal">
          <!-- Contact Form -->
          <div class="glass-card reveal-left">
            <h3 style="margin-bottom:1.5rem;font-size:1.3rem;letter-spacing:-0.02em">Send Me a Message</h3>
            <form id="contactForm" method="post">
              <div class="form-group">
                <label class="form-label">Your Name</label>
                <input type="text" name="name" id="contactName" required class="form-input" placeholder="Jane Smith">
              </div>
              <div class="form-group">
                <label class="form-label">Your Email</label>
                <input type="email" name="email" id="contactEmail" required class="form-input" placeholder="you@company.com">
              </div>
              <div class="form-group">
                <label class="form-label">Message</label>
                <textarea name="message" id="contactMessage" rows="5" required class="form-input" placeholder="Tell me about the role or project..."></textarea>
              </div>
              <div id="formMessage" style="margin-bottom:1rem;padding:0.75rem;display:none;border-radius:var(--radius-md);font-size:0.9rem"></div>
              <button type="submit" id="submitBtn" class="btn btn-primary" style="width:100%">Send Message →</button>
            </form>
          </div>

          <!-- Contact Info -->
          <div class="reveal-right" style="display:flex;flex-direction:column;gap:1.25rem">
            <div class="glass-card">
              <h3 style="margin-bottom:1.25rem;font-size:1.3rem;letter-spacing:-0.02em">Contact Info</h3>
              <div style="display:flex;flex-direction:column;gap:1rem">
                <div>
                  <p class="form-label">Email</p>
                  <a href="mailto:<?php echo $contact['email']; ?>" style="color:var(--text);font-size:1rem"><?php echo $contact['email']; ?></a>
                </div>
                <div>
                  <p class="form-label">Phone</p>
                  <a href="tel:+16159439722" style="color:var(--text);font-size:1rem"><?php echo $contact['phone']; ?></a>
                </div>
                <div>
                  <p class="form-label">Location</p>
                  <p style="color:var(--text);font-size:1rem"><?php echo $contact['address']; ?></p>
                </div>
              </div>
            </div>
            <div class="glass-card">
              <h3 style="margin-bottom:1.25rem;font-size:1.3rem;letter-spacing:-0.02em">Find Me Online</h3>
              <div style="display:flex;flex-direction:column;gap:0.75rem">
                <a href="https://www.linkedin.com/in/jakebartoncreative" target="_blank" class="btn btn-secondary" style="justify-content:flex-start">
                  <span>in</span>&nbsp; linkedin.com/in/jakebartoncreative
                </a>
                <?php if (!empty($contact['github'])): ?>
                <a href="https://github.com/<?php echo $contact['github']; ?>" target="_blank" class="btn btn-secondary" style="justify-content:flex-start">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" style="flex-shrink:0"><path d="M12 2C6.477 2 2 6.477 2 12c0 4.418 2.865 8.166 6.839 9.489.5.092.682-.217.682-.482 0-.237-.009-.868-.013-1.703-2.782.604-3.369-1.341-3.369-1.341-.454-1.155-1.11-1.463-1.11-1.463-.908-.62.069-.608.069-.608 1.003.07 1.531 1.03 1.531 1.03.892 1.529 2.341 1.087 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.11-4.555-4.943 0-1.091.39-1.984 1.029-2.683-.103-.253-.446-1.27.098-2.647 0 0 .84-.269 2.75 1.025A9.564 9.564 0 0112 6.844a9.59 9.59 0 012.504.337c1.909-1.294 2.747-1.025 2.747-1.025.546 1.377.203 2.394.1 2.647.64.699 1.028 1.592 1.028 2.683 0 3.842-2.339 4.687-4.566 4.935.359.309.678.919.678 1.852 0 1.336-.012 2.415-.012 2.744 0 .267.18.578.688.48C19.138 20.163 22 16.418 22 12c0-5.523-4.477-10-10-10z"/></svg>
                  &nbsp;github.com/<?php echo $contact['github']; ?>
                </a>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ── CTA Banner ──────────────────────────────────────── -->
    <section class="cta-full">
      <div class="container">
        <span class="cta-full-eyebrow reveal-up"><?php echo $content['cta_eyebrow']; ?></span>
        <h2 class="cta-full-heading reveal-up" style="transition-delay:0.1s"><?php echo $content['cta_heading']; ?></h2>
        <p class="cta-full-sub reveal-up" style="transition-delay:0.22s"><?php echo $content['cta_sub']; ?></p>
        <div class="cta-full-actions reveal-up" style="transition-delay:0.34s">
          <a href="mailto:<?php echo $content['email']; ?>" class="btn btn-primary">Email Me →</a>
          <a href="https://www.linkedin.com/in/<?php echo $content['linkedin']; ?>" target="_blank" class="btn btn-ghost">LinkedIn</a>
        </div>
      </div>
    </section>

  </main>

  <!-- ── Footer ────────────────────────────────────────────── -->
  <footer class="site-footer">
    <div class="footer-inner container-wide">
      <a href="/" class="footer-logo nav-logo">
        <img src="assets/images/jb-logo.png" alt="JB" class="nav-logo-img">
        <span class="nav-logo-text">JB</span>
      </a>
      <nav class="footer-nav" aria-label="Footer navigation">
        <a href="/portfolio/">Work</a>
        <a href="#about">About</a>
        <a href="#skills">Skills</a>
        <a href="#contact">Contact</a>
      </nav>
      <div class="footer-socials">
        <a href="https://www.linkedin.com/in/jakebartoncreative" target="_blank" class="btn-icon" aria-label="LinkedIn">in</a>
        <?php if (!empty($contact['github'])): ?>
        <a href="https://github.com/<?php echo $contact['github']; ?>" target="_blank" class="btn-icon" aria-label="GitHub">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.477 2 2 6.477 2 12c0 4.418 2.865 8.166 6.839 9.489.5.092.682-.217.682-.482 0-.237-.009-.868-.013-1.703-2.782.604-3.369-1.341-3.369-1.341-.454-1.155-1.11-1.463-1.11-1.463-.908-.62.069-.608.069-.608 1.003.07 1.531 1.03 1.531 1.03.892 1.529 2.341 1.087 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.11-4.555-4.943 0-1.091.39-1.984 1.029-2.683-.103-.253-.446-1.27.098-2.647 0 0 .84-.269 2.75 1.025A9.564 9.564 0 0112 6.844a9.59 9.59 0 012.504.337c1.909-1.294 2.747-1.025 2.747-1.025.546 1.377.203 2.394.1 2.647.64.699 1.028 1.592 1.028 2.683 0 3.842-2.339 4.687-4.566 4.935.359.309.678.919.678 1.852 0 1.336-.012 2.415-.012 2.744 0 .267.18.578.688.48C19.138 20.163 22 16.418 22 12c0-5.523-4.477-10-10-10z"/></svg>
        </a>
        <?php endif; ?>
      </div>
      <span class="footer-copy">© <?php echo date('Y'); ?> Jake Barton</span>
    </div>
  </footer>

  <style>
    /* ═══════════════════════════════════════════════════════
       Homepage — inline styles (layout-specific overrides)
    ═══════════════════════════════════════════════════════ */

    /* ── Film grain overlay ─────────────────────────────── */
    body::before {
      content: '';
      position: fixed;
      inset: -50%;
      width: 200%;
      height: 200%;
      background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 512 512' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='1'/%3E%3C/svg%3E");
      background-size: 200px 200px;
      opacity: 0.028;
      pointer-events: none;
      z-index: 99998;
      animation: grain-shift 0.8s steps(1) infinite;
    }

    /* ── Scroll progress bar ────────────────────────────── */
    .scroll-progress {
      position: fixed;
      top: 0; left: 0;
      height: 2px;
      background: var(--text);
      width: 0%;
      z-index: 99999;
      transition: width 0.1s linear;
    }

    /* ── Nav logo ───────────────────────────────────────── */
    .nav-logo-img {
      width: 28px; height: 28px;
      object-fit: contain;
      filter: invert(1);
      display: inline-block;
    }
    .nav-logo-text { display: none; }
    .nav-logo { display: flex; align-items: center; gap: 0.5rem; }

    /* ── Hero ───────────────────────────────────────────── */
    .hero {
      min-height: 100svh;
      display: flex;
      flex-direction: column;
      justify-content: flex-end;
      padding: 2rem var(--spacing-md) 6rem;
      max-width: 1400px;
      margin: 0 auto;
      width: 100%;
    }
    .hero-meta {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: clamp(4rem, 12vh, 8rem);
      width: 100%;
    }
    .hero-eyebrow-tag {
      font-size: 0.68rem;
      font-weight: 600;
      letter-spacing: 0.18em;
      text-transform: uppercase;
      color: var(--text-faint);
      border: 1px solid var(--border);
      padding: 0.3rem 0.75rem;
      border-radius: 99px;
    }
    .hero-location-tag {
      font-size: 0.68rem;
      font-weight: 600;
      letter-spacing: 0.18em;
      text-transform: uppercase;
      color: var(--text-faint);
    }
    .hero-name-block {
      margin-bottom: clamp(1rem, 2.5vh, 2rem);
    }
    .hero-name-block .xl-reveal {
      display: block;
      overflow: hidden;
      line-height: 0.92;
    }
    .hero-name-block .xl-reveal span {
      display: block;
      font-family: var(--font-display);
      font-size: clamp(3.8rem, 11vw, 10rem);
      font-weight: 800;
      letter-spacing: -0.04em;
      line-height: 0.92;
      color: var(--text);
      transform: translateY(110%);
      opacity: 0;
      transition: transform 0.9s cubic-bezier(0.16,1,0.3,1), opacity 0.6s ease;
    }
    .hero-name-block .xl-reveal.is-visible span {
      transform: translateY(0);
      opacity: 1;
    }
    .hero-social-icons {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      margin-bottom: clamp(1.5rem, 3.5vh, 3rem);
      opacity: 0;
      transform: translateY(8px);
      transition: opacity 0.6s ease 0.35s, transform 0.6s cubic-bezier(0.16,1,0.3,1) 0.35s;
    }
    .hero-social-icons.is-visible {
      opacity: 1;
      transform: none;
    }
    .hero-social-link {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 40px; height: 40px;
      border-radius: 8px;
      border: 1px solid var(--border);
      color: var(--text-faint);
      background: rgba(255,255,255,0.03);
      transition: color 0.2s, border-color 0.2s, background 0.2s, transform 0.2s;
      text-decoration: none;
    }
    .hero-social-link:hover {
      color: var(--text);
      border-color: rgba(255,255,255,0.3);
      background: rgba(255,255,255,0.07);
      transform: translateY(-2px);
    }
    .hero-bottom {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 2rem 4rem;
      width: 100%;
      align-items: end;
    }
    .hero-tagline {
      font-size: clamp(1rem, 2vw, 1.25rem);
      line-height: 1.7;
      color: var(--text-muted);
      margin-bottom: 1.75rem;
    }
    .hero-tagline em {
      font-style: italic;
      color: var(--text);
      font-family: var(--font-serif);
    }
    .hero-subtitle {
      font-size: 0.9rem;
      color: var(--text-faint);
      line-height: 1.75;
      margin-bottom: 1.25rem;
    }
    .hero-status-badge {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      font-size: 0.68rem;
      font-weight: 600;
      letter-spacing: 0.1em;
      text-transform: uppercase;
      color: var(--text-faint);
      border: 1px solid var(--border);
      border-radius: 99px;
      padding: 0.3rem 0.85rem 0.3rem 0.6rem;
    }
    .hero-status-pulse {
      width: 7px; height: 7px;
      background: #3ddb74;
      border-radius: 50%;
      animation: pulse-green 2s ease infinite;
    }
    @keyframes pulse-green {
      0%, 100% { box-shadow: 0 0 0 0 rgba(61,219,116,0.5); }
      50% { box-shadow: 0 0 0 5px rgba(61,219,116,0); }
    }

    /* ── Stats bar ──────────────────────────────────────── */
    .stats-bar {
      display: flex;
      align-items: center;
      gap: 0;
      border-top: 1px solid var(--border);
      border-bottom: 1px solid var(--border);
      overflow-x: auto;
    }
    .stats-bar-item {
      flex: 1;
      display: flex;
      flex-direction: column;
      gap: 0.2rem;
      padding: 1.5rem 2rem;
      min-width: 130px;
    }
    .stats-bar-divider {
      width: 1px;
      align-self: stretch;
      background: var(--border);
    }
    .stats-bar-num {
      font-family: var(--font-display);
      font-size: clamp(1.8rem, 3.5vw, 2.5rem);
      font-weight: 800;
      letter-spacing: -0.04em;
      color: var(--text);
    }
    .stats-bar-label {
      font-size: 0.72rem;
      letter-spacing: 0.1em;
      text-transform: uppercase;
      color: var(--text-faint);
    }

    /* ── Bold ticker ────────────────────────────────────── */
    .bold-ticker {
      overflow: hidden;
      border-bottom: 1px solid var(--border);
      padding: 1.1rem 0;
      background: var(--bg);
    }
    .bold-ticker-track {
      display: flex;
      align-items: center;
      gap: 2rem;
      white-space: nowrap;
      animation: ticker-track 30s linear infinite;
    }
    .bold-ticker-track span {
      font-family: var(--font-display);
      font-size: 0.78rem;
      font-weight: 700;
      letter-spacing: 0.16em;
      text-transform: uppercase;
      color: var(--text-faint);
      flex-shrink: 0;
    }
    .bold-ticker-track .bull { color: var(--text-faint); opacity: 0.4; }
    @keyframes ticker-track {
      0% { transform: translateX(0); }
      100% { transform: translateX(-50%); }
    }

    /* ── Work section ───────────────────────────────────── */
    /* ══════════════════════════════════════════════════════
       SELECTED WORK — sticky accordion scroll
    ══════════════════════════════════════════════════════ */
    .work-section { padding: 0; }
    .container-wide {
      max-width: 1300px;
      margin: 0 auto;
      padding: 0 var(--spacing-md);
    }
    /* Label row above the sticky block */
    .work-label-row {
      display: flex;
      align-items: baseline;
      gap: 1.25rem;
      padding: clamp(3rem,6vw,5rem) var(--spacing-md) 1.5rem;
      max-width: 1300px;
      margin: 0 auto;
      border-bottom: 1px solid var(--border);
    }
    .work-label-num {
      font-family: var(--font-display);
      font-size: 0.72rem; font-weight: 800;
      letter-spacing: 0.12em; color: var(--text-faint);
    }
    .work-label-text {
      font-family: var(--font-display);
      font-size: 0.72rem; font-weight: 700;
      letter-spacing: 0.18em; text-transform: uppercase;
      color: var(--text-faint); flex: 1;
    }
    .work-label-link {
      font-size: 0.78rem; color: var(--text-faint);
      text-decoration: none; transition: color 0.2s;
    }
    .work-label-link:hover { color: var(--text); }

    /* Scroll tunnel: tall container so sticky panel has room to scroll */
    .work-scroll-tunnel {
      position: relative;
      /* 7 cards × 100vh each = total scroll distance */
      height: calc(7 * 100vh);
    }
    /* Sticky viewport — stays in view while tunnel scrolls past */
    .work-sticky {
      position: sticky;
      top: 0;
      height: 100vh;
      overflow: hidden;
      display: flex;
      flex-direction: column;
    }
    /* Stack of accordion cards */
    .wa-stack {
      flex: 1;
      display: flex;
      flex-direction: column;
      overflow: hidden;
    }
    /* Individual card */
    .wa-card {
      position: relative;
      overflow: hidden;
      flex-shrink: 0;
      /* height is driven by JS via --h CSS var */
      height: var(--h, 72px);
      transition: height 0.55s cubic-bezier(0.16,1,0.3,1);
      text-decoration: none;
      color: var(--text);
      display: block;
      border-bottom: 1px solid rgba(255,255,255,0.07);
    }
    /* Media layer — fills the card */
    .wa-media {
      position: absolute; inset: 0;
      width: 100%; height: 100%;
      object-fit: cover;
      transition: transform 0.7s cubic-bezier(0.16,1,0.3,1), opacity 0.4s;
      opacity: 0;
    }
    .wa-card.is-active .wa-media { opacity: 0.82; }
    .wa-card.is-active .wa-media { transform: scale(1.0); }
    /* Gradient over media */
    .wa-grad {
      position: absolute; inset: 0;
      background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.25) 50%, transparent 100%);
      opacity: 0; transition: opacity 0.4s;
    }
    .wa-card.is-active .wa-grad { opacity: 1; }
    /* Collapsed row bar */
    .wa-bar {
      position: absolute; inset: 0;
      display: flex; align-items: center;
      padding: 0 clamp(1rem,3vw,2.5rem);
      gap: 1.5rem;
      opacity: 1; transition: opacity 0.3s;
      background: rgba(10,10,10,0.95);
      border-top: 1px solid rgba(255,255,255,0.07);
    }
    .wa-card.is-active .wa-bar { opacity: 0; pointer-events: none; }
    .wa-bar-num {
      font-family: var(--font-display); font-size: 0.65rem;
      font-weight: 800; letter-spacing: 0.1em; color: var(--text-faint);
      flex-shrink: 0; min-width: 22px;
    }
    .wa-bar-thumb {
      width: 52px; height: 36px; border-radius: 4px;
      overflow: hidden; flex-shrink: 0;
    }
    .wa-bar-thumb img, .wa-bar-thumb video {
      width: 100%; height: 100%; object-fit: cover; display: block;
    }
    .wa-bar-title {
      font-family: var(--font-display); font-size: clamp(0.85rem,1.4vw,1rem);
      font-weight: 700; letter-spacing: -0.015em; flex: 1;
      white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .wa-bar-tags { display: flex; gap: 0.3rem; flex-shrink: 0; }
    .wa-bar-arrow {
      font-size: 1rem; color: var(--text-faint);
      transition: transform 0.25s, color 0.25s; flex-shrink: 0;
    }
    .wa-card:hover .wa-bar-arrow { transform: translate(2px,-2px); color: var(--text); }
    /* Expanded content */
    .wa-content {
      position: absolute; bottom: 0; left: 0; right: 0;
      padding: clamp(1.5rem,4vw,3rem);
      display: flex; flex-direction: column; gap: 0.6rem;
      opacity: 0; transform: translateY(12px);
      transition: opacity 0.35s 0.1s, transform 0.35s 0.1s;
      pointer-events: none;
    }
    .wa-card.is-active .wa-content {
      opacity: 1; transform: none; pointer-events: auto;
    }
    .wa-tags { display: flex; gap: 0.5rem; flex-wrap: wrap; }
    .wa-title {
      font-family: var(--font-display);
      font-size: clamp(2rem,5vw,4.5rem);
      font-weight: 800; letter-spacing: -0.03em;
      color: var(--text); line-height: 1; margin: 0;
    }
    .wa-desc {
      font-size: 0.95rem; color: rgba(255,255,255,0.7);
      line-height: 1.65; max-width: 60ch; margin: 0;
    }
    .wa-cta {
      font-size: 0.8rem; font-weight: 700;
      letter-spacing: 0.1em; text-transform: uppercase;
      color: var(--text); margin-top: 0.5rem;
      transition: letter-spacing 0.3s;
    }
    .wa-card.is-active:hover .wa-cta { letter-spacing: 0.18em; }
    .wa-badge {
      position: absolute; top: 1.5rem; right: 1.5rem;
      font-size: 0.65rem; font-weight: 700;
      letter-spacing: 0.18em; text-transform: uppercase;
      color: var(--text); border: 1px solid rgba(255,255,255,0.3);
      padding: 0.3rem 0.75rem; border-radius: 99px;
      backdrop-filter: blur(8px);
      opacity: 0; transition: opacity 0.3s;
    }
    .wa-card.is-active .wa-badge { opacity: 1; }

    /* Footer CTA */
    .work-footer {
      padding: 2.5rem var(--spacing-md);
      max-width: 1300px; margin: 0 auto;
    }

    /* ── SilkTricky-style scroll reveals ───────────────── */
    /* Base: everything starts invisible and shifted down  */
    .reveal-up,
    .reveal-left,
    .reveal-right,
    .reveal-row,
    .reveal-fade {
      opacity: 0;
      transition: opacity 0.7s cubic-bezier(0.16,1,0.3,1),
                  transform 0.7s cubic-bezier(0.16,1,0.3,1);
    }
    .reveal-up    { transform: translateY(48px); }
    .reveal-left  { transform: translateX(-48px); }
    .reveal-right { transform: translateX(48px); }
    .reveal-fade  { transform: translateY(20px); }

    /* Work rows: slide up + very slight x-shift */
    .reveal-row {
      transform: translateY(40px);
      border-bottom: 1px solid transparent;
      transition: opacity 0.65s cubic-bezier(0.16,1,0.3,1),
                  transform 0.65s cubic-bezier(0.16,1,0.3,1),
                  border-color 0.65s ease;
    }
    /* Visible state */
    .reveal-up.is-visible,
    .reveal-left.is-visible,
    .reveal-right.is-visible,
    .reveal-fade.is-visible,
    .reveal-row.is-visible {
      opacity: 1;
      transform: none;
      border-color: var(--border);
    }
    /* Stagger children — each child gets a small delay */
    .stagger-reveal > * {
      opacity: 0;
      transform: translateY(32px);
      transition: opacity 0.6s cubic-bezier(0.16,1,0.3,1),
                  transform 0.6s cubic-bezier(0.16,1,0.3,1);
    }
    .stagger-reveal.is-visible > *:nth-child(1) { opacity:1; transform:none; transition-delay: 0.05s; }
    .stagger-reveal.is-visible > *:nth-child(2) { opacity:1; transform:none; transition-delay: 0.12s; }
    .stagger-reveal.is-visible > *:nth-child(3) { opacity:1; transform:none; transition-delay: 0.19s; }
    .stagger-reveal.is-visible > *:nth-child(4) { opacity:1; transform:none; transition-delay: 0.26s; }
    .stagger-reveal.is-visible > *:nth-child(5) { opacity:1; transform:none; transition-delay: 0.33s; }

    /* ── Section label row ──────────────────────────────── */
    .section-label-row {
      display: flex;
      align-items: baseline;
      gap: 1rem;
      margin-bottom: 1.25rem;
      padding-bottom: 1.25rem;
      border-bottom: 1px solid var(--border);
    }

    /* ── Section big heading ────────────────────────────── */
    .section-big-heading {
      font-family: var(--font-display);
      font-size: clamp(2.5rem, 6vw, 5.5rem);
      font-weight: 800;
      letter-spacing: -0.04em;
      margin-bottom: 3rem;
    }

    /* ── About editorial layout ─────────────────────────── */
    .about-section { padding: clamp(4rem, 8vw, 8rem) 0; }
    .about-editorial {
      display: grid;
      grid-template-columns: 1fr 380px;
      gap: 4rem;
      max-width: 1300px;
      margin: 0 auto;
      padding: 0 var(--spacing-md);
      align-items: start;
    }
    .about-big-heading {
      font-family: var(--font-display);
      font-size: clamp(2.2rem, 5vw, 4.5rem);
      font-weight: 800;
      letter-spacing: -0.03em;
      line-height: 1.1;
      margin-bottom: 2rem;
    }
    .about-para {
      font-size: 1.05rem;
      line-height: 1.85;
      color: var(--text-muted);
      margin-bottom: 1.25rem;
    }
    .about-actions { display: flex; gap: 0.75rem; flex-wrap: wrap; margin-top: 2rem; }
    .cred-card { display: flex; flex-direction: column; gap: 0; }
    .cred-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0.85rem 0;
      border-bottom: 1px solid var(--border);
      gap: 1rem;
    }
    .cred-label {
      font-size: 0.7rem;
      letter-spacing: 0.12em;
      text-transform: uppercase;
      color: var(--text-faint);
      flex-shrink: 0;
    }
    .cred-value { font-size: 0.88rem; color: var(--text-muted); text-align: right; }

    /* ── Experience list ────────────────────────────────── */
    .exp-list {
      display: flex;
      flex-direction: column;
      margin-top: 2rem;
      border-top: 1px solid var(--border);
    }
    .exp-item {
      display: grid;
      grid-template-columns: 130px 1fr auto;
      gap: 1rem 2.5rem;
      padding: 2rem 0;
      border-bottom: 1px solid var(--border);
      align-items: start;
    }
    .exp-dates {
      font-size: 0.72rem;
      letter-spacing: 0.1em;
      text-transform: uppercase;
      color: var(--text-faint);
      padding-top: 0.3em;
    }
    .exp-role {
      font-family: var(--font-display);
      font-size: 1.1rem;
      font-weight: 800;
      letter-spacing: -0.02em;
      margin-bottom: 0.3rem;
    }
    .exp-org { font-size: 0.8rem; color: var(--text-muted); }
    .exp-bullets { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.5rem; }
    .exp-bullets li {
      font-size: 0.88rem;
      color: var(--text-muted);
      line-height: 1.55;
      padding-left: 1rem;
      position: relative;
    }
    .exp-bullets li::before { content: '▸'; position: absolute; left: 0; color: var(--text-faint); }

    /* ── Contact section ────────────────────────────────── */
    .contact-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 2rem;
      align-items: start;
      margin-top: 2.5rem;
    }

    /* ── CTA banner ─────────────────────────────────────── */
    .cta-full {
      padding: clamp(5rem, 10vw, 10rem) var(--spacing-md);
      text-align: center;
      border-top: 1px solid var(--border);
    }
    .cta-full-eyebrow {
      display: block;
      font-size: 0.7rem;
      font-weight: 700;
      letter-spacing: 0.2em;
      text-transform: uppercase;
      color: var(--text-faint);
      margin-bottom: 1.5rem;
    }
    .cta-full-heading {
      font-family: var(--font-display);
      font-size: clamp(3rem, 9vw, 9rem);
      font-weight: 800;
      letter-spacing: -0.04em;
      line-height: 1;
      margin-bottom: 1.5rem;
    }
    .cta-full-sub {
      font-size: 1.05rem;
      color: var(--text-muted);
      margin-bottom: 2.5rem;
      max-width: 50ch;
      margin-left: auto;
      margin-right: auto;
      line-height: 1.7;
    }
    .cta-full-actions { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }

    /* ── Footer ─────────────────────────────────────────── */
    .site-footer {
      border-top: 1px solid var(--border);
      padding: 2rem 0;
    }
    .footer-inner {
      display: flex;
      align-items: center;
      gap: 2rem;
      flex-wrap: wrap;
    }
    .footer-logo { flex-shrink: 0; }
    .footer-nav { display: flex; gap: 2rem; flex: 1; }
    .footer-nav a {
      font-size: 0.78rem;
      font-weight: 600;
      letter-spacing: 0.1em;
      text-transform: uppercase;
      color: var(--text-faint);
      text-decoration: none;
      transition: color 0.2s;
    }
    .footer-nav a:hover { color: var(--text); }
    .footer-socials { display: flex; gap: 0.75rem; align-items: center; }
    .footer-copy { font-size: 0.72rem; color: var(--text-faint); letter-spacing: 0.06em; }

    /* ── Tags ───────────────────────────────────────────── */
    .tag {
      font-size: 0.65rem;
      font-weight: 700;
      letter-spacing: 0.12em;
      text-transform: uppercase;
      padding: 0.25rem 0.65rem;
      border-radius: 99px;
      border: 1px solid rgba(255,255,255,0.2);
      color: rgba(255,255,255,0.8);
    }
    .tag-muted { color: rgba(255,255,255,0.45); border-color: rgba(255,255,255,0.1); }

    /* ── Responsive ─────────────────────────────────────── */
    @media (max-width: 900px) {
      .hero-bottom { grid-template-columns: 1fr; }
      .about-editorial { grid-template-columns: 1fr; }
      .exp-item { grid-template-columns: 1fr; gap: 0.5rem; }
      .contact-grid { grid-template-columns: 1fr; }
      .footer-inner { flex-direction: column; align-items: flex-start; gap: 1.5rem; }
    }
    @media (max-width: 640px) {
      .hero { padding-bottom: 5rem; }
      .work-list-item { grid-template-columns: 70px 1fr auto; gap: 0.75rem 1rem; }
      .work-list-media { width: 70px; height: 50px; }
      .stats-bar-item { padding: 1rem 1.25rem; }
    }
  </style>

  <script>
    /* ── XL Hero reveal (name lines) ─────────────────── */
    (function() {
      var els = document.querySelectorAll('.hero-name-block .xl-reveal');
      if (!els.length) return;
      setTimeout(function() {
        els.forEach(function(el) { el.classList.add('is-visible'); });
        // Social icons fade in slightly after the name finishes
        var icons = document.querySelector('.hero-social-icons');
        if (icons) icons.classList.add('is-visible');
      }, 80);
    })();

    /* ── Scroll reveal — IntersectionObserver ─────────── */
    (function() {
      // Reveal single elements
      var singles = document.querySelectorAll('.reveal-up, .reveal-left, .reveal-right, .reveal-fade');
      var singleObs = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
            singleObs.unobserve(entry.target);
          }
        });
      }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
      singles.forEach(function(el) { singleObs.observe(el); });

      // Reveal work rows with staggered delay
      var rows = document.querySelectorAll('.reveal-row');
      var rowObs = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
          if (entry.isIntersecting) {
            // Find index among siblings for stagger
            var siblings = entry.target.parentElement.querySelectorAll('.reveal-row');
            var idx = 0;
            siblings.forEach(function(s, i) { if (s === entry.target) idx = i; });
            entry.target.style.transitionDelay = (idx * 0.06) + 's';
            entry.target.classList.add('is-visible');
            rowObs.unobserve(entry.target);
          }
        });
      }, { threshold: 0.08, rootMargin: '0px 0px -20px 0px' });
      rows.forEach(function(el) { rowObs.observe(el); });

      // Stagger-reveal containers
      var staggerGroups = document.querySelectorAll('.stagger-reveal');
      var groupObs = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
            groupObs.unobserve(entry.target);
          }
        });
      }, { threshold: 0.1, rootMargin: '0px 0px -30px 0px' });
      staggerGroups.forEach(function(el) { groupObs.observe(el); });
    })();

    /* ── Scroll progress bar ──────────────────────────── */
    (function() {
      var bar = document.getElementById('scroll-progress');
      if (!bar) return;
      window.addEventListener('scroll', function() {
        var scrolled = window.scrollY;
        var total = document.documentElement.scrollHeight - window.innerHeight;
        bar.style.width = (total > 0 ? (scrolled / total) * 100 : 0) + '%';
      }, { passive: true });
    })();

    /* ── Work accordion — scroll-driven ─────────────────
       One card is "active" (expands to fill viewport).
       Scrolling through the tunnel advances which card is active.
    ─────────────────────────────────────────────────── */
    (function() {
      var tunnel  = document.getElementById('workTunnel');
      var sticky  = document.getElementById('workSticky');
      var cards   = Array.from(document.querySelectorAll('.wa-card'));
      if (!tunnel || !cards.length) return;

      var N           = cards.length;   // 7
      var COLLAPSED_H = 68;             // px — collapsed bar height
      var raf         = null;

      /* Set initial state: card 0 active */
      function setActive(idx) {
        var stickyH = sticky.getBoundingClientRect().height || window.innerHeight;
        var expandedH = stickyH - COLLAPSED_H * (N - 1);
        expandedH = Math.max(expandedH, stickyH * 0.5); // floor at 50vh

        cards.forEach(function(card, i) {
          var h = (i === idx) ? expandedH : COLLAPSED_H;
          card.style.setProperty('--h', h + 'px');
          card.classList.toggle('is-active', i === idx);
        });
      }

      function onScroll() {
        var rect    = tunnel.getBoundingClientRect();
        var total   = tunnel.offsetHeight - window.innerHeight;
        /* progress: 0 when tunnel top just hits viewport top,
                     1 when tunnel bottom hits viewport bottom */
        var progress = Math.max(0, Math.min(1, -rect.top / total));
        var idx = Math.min(N - 1, Math.floor(progress * N));
        setActive(idx);
      }

      /* Run once on load so card 0 starts expanded */
      setActive(0);

      window.addEventListener('scroll', function() {
        if (raf) return;
        raf = requestAnimationFrame(function() {
          raf = null;
          onScroll();
        });
      }, { passive: true });

      window.addEventListener('resize', function() { setActive(
        parseInt(cards.findIndex(function(c){ return c.classList.contains('is-active'); }))
      ); }, { passive: true });
    })();

    /* ── Ambient cursor glow ──────────────────────────── */
    (function() {
      var glow = document.getElementById('cursor-glow');
      if (!glow || window.matchMedia('(pointer:coarse)').matches) return;
      var cx = 0, cy = 0, tx = 0, ty = 0;
      document.addEventListener('mousemove', function(e) {
        tx = e.clientX; ty = e.clientY;
        glow.style.opacity = '1';
      });
      document.addEventListener('mouseleave', function() { glow.style.opacity = '0'; });
      function lerp(a, b, t) { return a + (b - a) * t; }
      function loop() {
        cx = lerp(cx, tx, 0.07);
        cy = lerp(cy, ty, 0.07);
        glow.style.left = cx + 'px';
        glow.style.top  = cy + 'px';
        requestAnimationFrame(loop);
      }
      loop();
    })();

    /* ── Contact form ─────────────────────────────────── */
    var contactForm = document.getElementById('contactForm');
    if (contactForm) {
      contactForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        var submitBtn = document.getElementById('submitBtn');
        var formMessage = document.getElementById('formMessage');
        var formData = new FormData(this);
        submitBtn.disabled = true;
        submitBtn.textContent = 'Sending...';
        formMessage.style.display = 'none';
        try {
          var response = await fetch('contact-handler.php', { method: 'POST', body: formData });
          var data = await response.json();
          if (data.success) {
            formMessage.textContent = data.message;
            formMessage.style.cssText = 'display:block;background:rgba(61,219,116,0.1);border:1px solid rgba(61,219,116,0.4);color:#3ddb74;padding:0.75rem;border-radius:8px';
            this.reset();
          } else {
            formMessage.textContent = data.errors.join(', ');
            formMessage.style.cssText = 'display:block;background:rgba(255,0,107,0.08);border:1px solid rgba(255,0,107,0.4);color:#ff006b;padding:0.75rem;border-radius:8px';
          }
        } catch(err) {
          formMessage.textContent = 'Error sending message. Please email directly.';
          formMessage.style.cssText = 'display:block;background:rgba(255,0,107,0.08);border:1px solid rgba(255,0,107,0.4);color:#ff006b;padding:0.75rem;border-radius:8px';
        }
        submitBtn.disabled = false;
        submitBtn.textContent = 'Send Message →';
      });
    }
  </script>

  <!-- ── Style Kit JS ───────────────────────────────────── -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
  <script src="assets/js/beams-bg.js"></script>
  <script src="assets/js/cursor-ribbons.js"></script>
  <script src="assets/js/fuzzy-text.js"></script>
  <script src="assets/js/staggered-menu.js"></script>
  <script src="assets/js/effects-stylekit.js"></script>

</body>
</html>
