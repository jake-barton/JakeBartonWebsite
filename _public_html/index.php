<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jake Barton - Game Designer & 3D Artist</title>
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
  <div id="cursor-glow" style="position:fixed;top:0;left:0;width:420px;height:420px;border-radius:50%;background:radial-gradient(circle,rgba(255,255,255,0.05) 0%,transparent 70%);pointer-events:none;z-index:0;transform:translate(-50%,-50%);transition:opacity 0.3s ease;opacity:0"></div>  </div>


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

      <!-- Bottom two-col info row -->
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

    <!-- ── Selected Work ─────────────────────────────────── -->
    <section class="work-section" id="work">

      <!-- Section label row -->
      <div class="work-label-row container-wide">
        <span class="work-label-num">01</span>
        <span class="work-label-text">Selected Work</span>
        <a href="/portfolio/" class="work-label-link">View All →</a>
      </div>

      <!-- Featured hero project: Environment scene as cinematic backdrop -->
      <a href="portfolio/game-programming/" class="work-hero-card reveal-up">
        <video class="work-hero-video" autoplay muted loop playsinline preload="auto">
          <source src="assets/images/environment-scene.mp4" type="video/mp4">
        </video>
        <div class="work-hero-gradient"></div>
        <div class="work-hero-content">
          <div class="work-hero-tags">
            <span class="tag">3D Art</span>
            <span class="tag tag-muted">Real-time</span>
            <span class="tag tag-muted">Unreal 5</span>
          </div>
          <h2 class="work-hero-title">Mediterranean Environment</h2>
          <p class="work-hero-desc">Real-time 3D scene built in Unreal Engine 5 — custom lighting, modular architecture, atmospheric FX.</p>
          <span class="work-hero-cta">View All Work ↗</span>
        </div>
        <div class="work-hero-badge">Featured</div>
      </a>

      <!-- Project index list -->
      <div class="work-list container-wide">

        <a href="https://clervercarpet99.itch.io/phase-runner" target="_blank" class="work-list-item reveal-row">
          <div class="work-list-media video-card">
            <video autoplay muted loop playsinline preload="metadata">
              <source src="assets/images/phase-runner-screen.mp4" type="video/mp4">
            </video>
          </div>
          <div class="work-list-info">
            <span class="work-list-num">01</span>
            <div>
              <h3 class="work-list-title">Phase Runner</h3>
              <p class="work-list-desc">2D side-scrolling shooter — custom physics, 10+ weapons, procedural chunks, invincibility dash. Solo-developed, live on itch.io.</p>
              <div class="work-list-tags">
                <span class="tag tag-muted">Game Design</span>
                <span class="tag tag-muted">Godot 4</span>
                <span class="tag tag-muted">Solo</span>
              </div>
            </div>
          </div>
          <span class="work-list-arrow">↗</span>
        </a>

        <a href="portfolio/game-programming/" class="work-list-item reveal-row">
          <div class="work-list-media video-card">
            <video autoplay muted loop playsinline preload="metadata">
              <source src="assets/images/vr-gameplay.mp4" type="video/mp4">
            </video>
          </div>
          <div class="work-list-info">
            <span class="work-list-num">02</span>
            <div>
              <h3 class="work-list-title">VR Rhythm Game</h3>
              <p class="work-list-desc">Body-movement dragon controller in Unreal Engine 5 — C++ gameplay, VR locomotion, rhythm mechanics.</p>
              <div class="work-list-tags">
                <span class="tag tag-muted">VR</span>
                <span class="tag tag-muted">Unreal 5</span>
                <span class="tag tag-muted">C++</span>
              </div>
            </div>
          </div>
          <span class="work-list-arrow">↗</span>
        </a>

        <a href="portfolio/game-programming/" class="work-list-item reveal-row">
          <div class="work-list-media video-card">
            <video autoplay muted loop playsinline preload="metadata">
              <source src="assets/images/penguins-creed.mp4" type="video/mp4">
            </video>
          </div>
          <div class="work-list-info">
            <span class="work-list-num">03</span>
            <div>
              <h3 class="work-list-title">Penguins Creed</h3>
              <p class="work-list-desc">Third-person action game with stealth mechanics, AI patrol systems, and a penguin protagonist.</p>
              <div class="work-list-tags">
                <span class="tag tag-muted">Game Design</span>
                <span class="tag tag-muted">Unreal 5</span>
                <span class="tag tag-muted">Blueprints</span>
              </div>
            </div>
          </div>
          <span class="work-list-arrow">↗</span>
        </a>

        <a href="portfolio/game-programming/" class="work-list-item reveal-row">
          <div class="work-list-media">
            <img src="assets/images/mariokart.png" alt="Mario Kart Recreation">
          </div>
          <div class="work-list-info">
            <span class="work-list-num">04</span>
            <div>
              <h3 class="work-list-title">Mario Kart Recreation</h3>
              <p class="work-list-desc">Mode-7 SNES renderer in vanilla JS — raycasting, sprite sheets, full lap logic.</p>
              <div class="work-list-tags">
                <span class="tag tag-muted">Web Game</span>
                <span class="tag tag-muted">JavaScript</span>
              </div>
            </div>
          </div>
          <span class="work-list-arrow">↗</span>
        </a>

        <a href="portfolio/" class="work-list-item reveal-row">
          <div class="work-list-media">
            <img src="assets/images/venice-art.jpg" alt="Venice — Juried Art Show" style="object-position:center top">
          </div>
          <div class="work-list-info">
            <span class="work-list-num">05</span>
            <div>
              <h3 class="work-list-title">Venice</h3>
              <p class="work-list-desc">Digital art piece accepted into the Samford University Juried Art Show 2025.</p>
              <div class="work-list-tags">
                <span class="tag tag-muted">Fine Art</span>
                <span class="tag tag-muted">Digital</span>
                <span class="tag tag-muted">Juried Show</span>
              </div>
            </div>
          </div>
          <span class="work-list-arrow">↗</span>
        </a>

        <a href="portfolio/professional-works/" class="work-list-item reveal-row">
          <div class="work-list-media">
            <img src="assets/images/33miles-cover.png" alt="33Miles Band Graphics">
          </div>
          <div class="work-list-info">
            <span class="work-list-num">06</span>
            <div>
              <h3 class="work-list-title">33Miles Band Graphics</h3>
              <p class="work-list-desc">Paid brand &amp; merchandise design for a signed Christian music group.</p>
              <div class="work-list-tags">
                <span class="tag tag-muted">Client Work</span>
                <span class="tag tag-muted">Illustrator</span>
              </div>
            </div>
          </div>
          <span class="work-list-arrow">↗</span>
        </a>

      </div>

      <div class="work-footer container-wide">
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
      margin-bottom: clamp(2rem, 5vh, 4rem);
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
    .work-section { padding: clamp(4rem, 8vw, 8rem) 0; }
    .container-wide {
      max-width: 1300px;
      margin: 0 auto;
      padding: 0 var(--spacing-md);
    }
    .work-label-row {
      display: flex;
      align-items: baseline;
      gap: 1.25rem;
      margin-bottom: 3rem;
      padding-bottom: 1.25rem;
      border-bottom: 1px solid var(--border);
    }
    .work-label-num {
      font-family: var(--font-display);
      font-size: 0.72rem;
      font-weight: 800;
      letter-spacing: 0.12em;
      color: var(--text-faint);
    }
    .work-label-text {
      font-family: var(--font-display);
      font-size: 0.72rem;
      font-weight: 700;
      letter-spacing: 0.18em;
      text-transform: uppercase;
      color: var(--text-faint);
      flex: 1;
    }
    .work-label-link {
      font-size: 0.78rem;
      color: var(--text-faint);
      text-decoration: none;
      transition: color 0.2s;
    }
    .work-label-link:hover { color: var(--text); }

    /* Featured hero card */
    .work-hero-card {
      display: block;
      position: relative;
      width: 100%;
      height: clamp(320px, 55vw, 620px);
      overflow: hidden;
      text-decoration: none;
      margin-bottom: 1px;
    }
    .work-hero-video {
      position: absolute;
      inset: 0;
      width: 100%; height: 100%;
      object-fit: cover;
      transition: transform 0.8s cubic-bezier(0.16,1,0.3,1);
    }
    .work-hero-card:hover .work-hero-video { transform: scale(1.04); }
    .work-hero-gradient {
      position: absolute;
      inset: 0;
      background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.3) 50%, transparent 100%);
    }
    .work-hero-content {
      position: absolute;
      bottom: 0; left: 0; right: 0;
      padding: clamp(1.5rem, 4vw, 3rem);
      display: flex;
      flex-direction: column;
      gap: 0.6rem;
    }
    .work-hero-tags { display: flex; gap: 0.5rem; flex-wrap: wrap; }
    .work-hero-title {
      font-family: var(--font-display);
      font-size: clamp(2rem, 5vw, 4.5rem);
      font-weight: 800;
      letter-spacing: -0.03em;
      color: var(--text);
      line-height: 1;
      margin: 0;
    }
    .work-hero-desc {
      font-size: 0.95rem;
      color: rgba(255,255,255,0.7);
      line-height: 1.65;
      max-width: 60ch;
      margin: 0;
    }
    .work-hero-cta {
      font-size: 0.8rem;
      font-weight: 700;
      letter-spacing: 0.1em;
      text-transform: uppercase;
      color: var(--text);
      margin-top: 0.5rem;
      transition: letter-spacing 0.3s;
    }
    .work-hero-card:hover .work-hero-cta { letter-spacing: 0.18em; }
    .work-hero-badge {
      position: absolute;
      top: 1.5rem; right: 1.5rem;
      font-size: 0.65rem;
      font-weight: 700;
      letter-spacing: 0.18em;
      text-transform: uppercase;
      color: var(--text);
      border: 1px solid rgba(255,255,255,0.3);
      padding: 0.3rem 0.75rem;
      border-radius: 99px;
      backdrop-filter: blur(8px);
    }

    /* Work index list */
    .work-list {
      border-top: 1px solid var(--border);
      margin-top: 0;
    }
    .work-list-item {
      display: grid;
      grid-template-columns: 100px 1fr auto;
      align-items: center;
      gap: 1.5rem 2rem;
      padding: 1.75rem 0;
      border-bottom: 1px solid var(--border);
      text-decoration: none;
      color: var(--text);
      transition: background 0.25s, border-color 0.25s;
      cursor: pointer;
    }
    .work-list-item:hover { background: rgba(255,255,255,0.025); }
    .work-list-media {
      width: 100px; height: 68px;
      border-radius: 6px;
      overflow: hidden;
      flex-shrink: 0;
    }
    .work-list-media img,
    .work-list-media video {
      width: 100%; height: 100%;
      object-fit: cover;
      display: block;
    }
    .work-list-info {
      display: flex;
      align-items: flex-start;
      gap: 1.25rem;
    }
    .work-list-num {
      font-family: var(--font-display);
      font-size: 0.68rem;
      font-weight: 800;
      letter-spacing: 0.1em;
      color: var(--text-faint);
      padding-top: 0.2em;
      flex-shrink: 0;
      min-width: 20px;
    }
    .work-list-title {
      font-family: var(--font-display);
      font-size: clamp(1rem, 2vw, 1.35rem);
      font-weight: 800;
      letter-spacing: -0.025em;
      color: var(--text);
      margin: 0 0 0.35rem;
    }
    .work-list-desc {
      font-size: 0.85rem;
      color: var(--text-muted);
      line-height: 1.6;
      margin: 0 0 0.5rem;
    }
    .work-list-tags { display: flex; gap: 0.4rem; flex-wrap: wrap; }
    .work-list-arrow {
      font-size: 1.25rem;
      color: var(--text-faint);
      transition: transform 0.25s, color 0.25s;
      flex-shrink: 0;
      padding-right: 0.5rem;
    }
    .work-list-item:hover .work-list-arrow {
      transform: translate(3px, -3px);
      color: var(--text);
    }
    .work-footer {
      margin-top: 2.5rem;
      display: flex;
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
