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
    <a href="#home" class="nav-logo">JB</a>
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
    <section class="hero section" id="home">

      <span class="hero-eyebrow"><?php echo $content['hero_eyebrow']; ?></span>

      <h1 class="hero-name" data-parallax="0.18"><?php echo $content['name']; ?></h1>

      <p class="hero-tagline" data-parallax="0.10">Game developer building <em class="rotating-text" data-words='<?php echo json_encode($content['hero_rotating_words']); ?>' data-interval="2800"><?php echo $content['hero_rotating_words'][0]; ?></em> — from game engines to the browser.</p>

      <p class="hero-subtitle">
        <?php echo $content['hero_subtitle']; ?>
      </p>

      <div class="hero-cta">
        <a href="/portfolio/" class="btn btn-primary magnetic">See My Work</a>
        <a href="/assets/Jake_Barton_Resume.pdf" download class="btn btn-secondary">Download Resume</a>
        <a href="https://github.com/<?php echo $content['github']; ?>" target="_blank" class="btn btn-secondary">GitHub</a>
      </div>

      <!-- Quick stats bar -->
      <div class="hero-stats">
        <?php foreach ($content['hero_stats'] as $i => $stat): ?>
          <?php if ($i > 0): ?><div class="hero-stat-divider"></div><?php endif; ?>
          <div class="hero-stat">
            <span class="hero-stat-num" data-count="<?php echo $stat['num']; ?>" data-suffix="<?php echo $stat['suffix']; ?>"><?php echo $stat['num'] . $stat['suffix']; ?></span>
            <span class="hero-stat-label"><?php echo $stat['label']; ?></span>
          </div>
        <?php endforeach; ?>
      </div>

    </section>

    <!-- Scroll indicator -->
    <div class="scroll-indicator" style="position:fixed;bottom:2rem;left:50%;transform:translateX(-50%)">Scroll</div>

    <!-- ── Currently Building marquee strip ────────────── -->
    <div class="now-strip">
      <div class="now-marquee-track" aria-hidden="true">
        <div class="now-marquee-inner">
          <?php
          // Duplicated for seamless loop
          for ($pass = 0; $pass < 2; $pass++):
            foreach ($content['marquee_items'] as $item): ?>
              <span class="now-item">
                <?php if ($item['bold']): ?><span class="now-dot"></span><strong><?php echo $item['bold']; ?></strong><?php endif; ?>
                <?php echo $item['text']; ?>
              </span>
              <span class="now-sep">✦</span>
            <?php endforeach;
          endfor; ?>
        </div>
      </div>
    </div>

    <!-- ── Featured Work ─────────────────────────────────── -->
    <section class="section" id="work">
      <div class="container">
        <div class="section-header reveal-up">
          <span class="section-num">01</span>
          <span class="eyebrow">Selected Work</span>
          <h2>What I've Built</h2>
          <p>Games, websites, and client work — each one a different problem to solve.</p>
        </div>

        <div class="showcase-grid stagger-children">

          <!-- Hero card — Phase Runner -->
          <a href="https://clervercarpet99.itch.io/phase-runner" target="_blank" class="showcase-card showcase-card--hero tilt-card">
            <img src="assets/images/phaserunnercover.png" alt="Phase Runner" class="showcase-img">
            <div class="showcase-overlay">
              <div class="showcase-tags">
                <span class="tag">Game Design</span>
                <span class="tag tag-muted">Godot 4</span>
                <span class="tag tag-muted">GDScript</span>
              </div>
              <h3 class="showcase-title">Phase Runner</h3>
              <p class="showcase-desc">2D side-scrolling shooter — custom physics, 10+ weapons, procedural chunks, invincibility dash. Solo-developed, live on itch.io.</p>
              <span class="showcase-cta">Play Now →</span>
            </div>
            <div class="showcase-badge">Featured</div>
          </a>

          <!-- VR Game -->
          <a href="portfolio/game-programming/" class="showcase-card showcase-card--tall">
            <div class="showcase-placeholder">
              <span class="showcase-placeholder-label">VR</span>
            </div>
            <div class="showcase-overlay">
              <div class="showcase-tags">
                <span class="tag">VR Game</span>
                <span class="tag tag-muted">Unreal 5</span>
              </div>
              <h3 class="showcase-title">VR Rhythm Game</h3>
              <p class="showcase-desc">Unreal Engine 5 — your movements control a dragon in a rhythm-based VR experience.</p>
              <span class="showcase-cta">View →</span>
            </div>
          </a>

          <!-- Mario Kart -->
          <a href="portfolio/game-programming/" class="showcase-card showcase-card--wide">
            <img src="assets/images/mariokart.png" alt="Mario Kart Recreation" class="showcase-img">
            <div class="showcase-overlay">
              <div class="showcase-tags">
                <span class="tag">Web Game</span>
                <span class="tag tag-muted">JavaScript</span>
              </div>
              <h3 class="showcase-title">Mario Kart Recreation</h3>
              <p class="showcase-desc">Mode-7 SNES engine in vanilla JS — raycasting, sprite sheets, lap logic.</p>
              <span class="showcase-cta">View →</span>
            </div>
          </a>

          <!-- 33Miles -->
          <a href="portfolio/professional-works/" class="showcase-card showcase-card--wide">
            <img src="assets/images/33miles-cover.png" alt="33Miles Graphics" class="showcase-img">
            <div class="showcase-overlay">
              <div class="showcase-tags">
                <span class="tag">Client Work</span>
                <span class="tag tag-muted">Illustrator</span>
              </div>
              <h3 class="showcase-title">33Miles Band Graphics</h3>
              <p class="showcase-desc">Paid brand &amp; merchandise design for a signed Christian music group.</p>
              <span class="showcase-cta">View →</span>
            </div>
          </a>

        </div>

        <div style="text-align:center;margin-top:3rem">
          <a href="portfolio/" class="btn btn-primary magnetic">Explore Full Portfolio</a>
        </div>
      </div>
    </section>

    <!-- ── Skills Section ────────────────────────────────── -->
    <section class="section" id="skills">
      <div class="container">
        <div class="section-header reveal-up">
          <span class="section-num">02</span>
          <span class="eyebrow">Toolkit</span>
          <h2>Skills &amp; Tools</h2>
          <p>What I reach for — organised by discipline.</p>
        </div>

        <div class="skills-grid stagger-children">

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
    <section class="section" id="about">
      <div class="container">
        <div class="about-grid">

          <!-- Left: text -->
          <div class="about-text reveal-left">
            <span class="eyebrow">About Me</span>
            <h2 style="margin-bottom:1.5rem"><?php echo $content['about_heading']; ?></h2>

            <?php foreach ($content['about_paragraphs'] as $para): ?>
            <p style="font-size:1.05rem;line-height:1.85;color:var(--text-muted);margin-bottom:1.25rem">
              <?php echo $para; ?>
            </p>
            <?php endforeach; ?>

            <div style="display:flex;gap:0.75rem;flex-wrap:wrap;margin-top:2rem">
              <a href="/portfolio/" class="btn btn-primary magnetic">View Portfolio</a>
              <a href="https://github.com/<?php echo $content['github']; ?>" target="_blank" class="btn btn-secondary">GitHub</a>
            </div>
          </div>

          <!-- Right: credential card -->
          <div class="about-creds reveal-right">
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
                <span class="cred-value" style="color:var(--accent-light);font-weight:600"><?php echo $content['gpa']; ?></span>
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
                <span class="cred-value" style="color:var(--accent-light)">● Open to Work</span>
              </div>
            </div>
          </div>

        </div>
      </div>
    </section>

    <!-- ── Leadership Section ────────────────────────────── -->
    <section class="section">
      <div class="container">
        <div class="section-header reveal-up">
          <span class="section-num">03</span>
          <span class="eyebrow">Experience &amp; Leadership</span>
          <h2>Leadership &amp; Experience</h2>
          <p>Building technical and leadership experience across game development and student organisations.</p>
        </div>

        <div class="grid-3 stagger-children" style="margin-top:2rem">
          <?php foreach ($content['experience'] as $i => $exp):
            $delay = $i * 0.08;
            $style = $delay > 0 ? " style=\"transition-delay:{$delay}s\"" : '';
          ?>
          <div class="glass-card reveal-up"<?php echo $style; ?>>
            <span class="eyebrow"><?php echo $exp['dates']; ?></span>
            <h3 style="margin-bottom:1rem"><?php echo $exp['role']; ?></h3>
            <p style="font-size:0.8rem;<?php echo $exp['org_style']; ?>;margin-bottom:0.75rem;font-weight:600"><?php echo $exp['org']; ?></p>
            <ul style="display:flex;flex-direction:column;gap:0.6rem">
              <?php foreach ($exp['bullets'] as $bullet): ?>
              <li style="color:var(--text-muted);font-size:0.9rem"><span style="color:var(--accent);margin-right:0.5rem">▸</span><?php echo $bullet; ?></li>
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
        <div class="section-header reveal-up">
          <span class="eyebrow">Get In Touch</span>
          <h2>Let's Work Together</h2>
          <p>Whether it's a studio role, freelance project, or just a conversation — I'd love to hear from you.</p>
        </div>

        <div class="grid-2 stagger-children" style="align-items:start">
          <!-- Contact Form -->
          <div class="glass-card reveal-left">
            <h3 style="margin-bottom:1.5rem">Send Me a Message</h3>
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
                <textarea name="message" id="contactMessage" rows="6" required class="form-input" placeholder="Tell me about the role or project..."></textarea>
              </div>
              <div id="formMessage" style="margin-bottom:1rem;padding:0.75rem;display:none;border-radius:var(--radius-md);font-size:0.9rem"></div>
              <button type="submit" id="submitBtn" class="btn btn-primary" style="width:100%">Send Message →</button>
            </form>
          </div>

          <!-- Contact Info -->
          <div style="display:flex;flex-direction:column;gap:1.5rem" class="reveal-right">
            <div class="glass-card">
              <h3 style="margin-bottom:1.25rem">Contact Info</h3>
              <div style="display:flex;flex-direction:column;gap:1rem">
                <div>
                  <p class="form-label">Email</p>
                  <a href="mailto:<?php echo $contact['email']; ?>" style="color:var(--accent);font-size:1rem"><?php echo $contact['email']; ?></a>
                </div>
                <div>
                  <p class="form-label">Phone</p>
                  <a href="tel:+16159439722" style="color:var(--accent);font-size:1rem"><?php echo $contact['phone']; ?></a>
                </div>
                <div>
                  <p class="form-label">Website</p>
                  <a href="<?php echo $contact['website']; ?>" target="_blank" style="color:var(--accent);font-size:1rem">jakebartoncreative.com</a>
                </div>
                <div>
                  <p class="form-label">Location</p>
                  <p style="color:var(--text);font-size:1rem"><?php echo $contact['address']; ?></p>
                </div>
              </div>
            </div>

            <div class="glass-card">
              <h3 style="margin-bottom:1.25rem">Find Me Online</h3>
              <div style="display:flex;flex-direction:column;gap:0.75rem">
                <a href="https://www.linkedin.com/in/jakebartoncreative" target="_blank" class="btn btn-secondary" style="justify-content:flex-start">
                  <span style="color:var(--accent)">in</span>&nbsp; linkedin.com/in/jakebartoncreative
                </a>
                <?php if (!empty($contact['github'])): ?>
                <a href="https://github.com/<?php echo $contact['github']; ?>" target="_blank" class="btn btn-secondary" style="justify-content:flex-start">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" style="flex-shrink:0"><path d="M12 2C6.477 2 2 6.477 2 12c0 4.418 2.865 8.166 6.839 9.489.5.092.682-.217.682-.482 0-.237-.009-.868-.013-1.703-2.782.604-3.369-1.341-3.369-1.341-.454-1.155-1.11-1.463-1.11-1.463-.908-.62.069-.608.069-.608 1.003.07 1.531 1.03 1.531 1.03.892 1.529 2.341 1.087 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.11-4.555-4.943 0-1.091.39-1.984 1.029-2.683-.103-.253-.446-1.27.098-2.647 0 0 .84-.269 2.75 1.025A9.564 9.564 0 0112 6.844a9.59 9.59 0 012.504.337c1.909-1.294 2.747-1.025 2.747-1.025.546 1.377.203 2.394.1 2.647.64.699 1.028 1.592 1.028 2.683 0 3.842-2.339 4.687-4.566 4.935.359.309.678.919.678 1.852 0 1.336-.012 2.415-.012 2.744 0 .267.18.578.688.48C19.138 20.163 22 16.418 22 12c0-5.523-4.477-10-10-10z"/></svg>
                  &nbsp;github.com/<?php echo $contact['github']; ?>
                </a>
                <?php endif; ?>
                <?php if (!empty($contact['instagram'])): ?>
                <a href="https://instagram.com/<?php echo $contact['instagram']; ?>" target="_blank" class="btn btn-secondary" style="justify-content:flex-start">
                  <span style="color:var(--pink)">IG</span>&nbsp; @<?php echo $contact['instagram']; ?>
                </a>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ── Let's Talk — full-bleed CTA ────────────────────── -->
    <section class="cta-full">
      <div class="container">
        <span class="cta-full-eyebrow reveal-up"><?php echo $content['cta_eyebrow']; ?></span>
        <h2 class="cta-full-heading reveal-up" style="transition-delay:0.1s">
          <?php echo $content['cta_heading']; ?>
        </h2>
        <p class="cta-full-sub reveal-up" style="transition-delay:0.22s">
          <?php echo $content['cta_sub']; ?>
        </p>
        <div class="cta-full-actions reveal-up" style="transition-delay:0.34s">
          <a href="mailto:<?php echo $content['email']; ?>" class="btn btn-primary magnetic">Email Me →</a>
          <a href="https://www.linkedin.com/in/<?php echo $content['linkedin']; ?>" target="_blank" class="btn btn-secondary">LinkedIn</a>
        </div>
      </div>
    </section>

  </main>

  <!-- ── Footer ────────────────────────────────────────────── -->
  <footer class="site-footer">
    <div class="container">
      <div class="footer-inner">
        <span class="footer-copy">© <?php echo date('Y'); ?> Jake Barton. All rights reserved.</span>
        <div class="footer-socials">
          <a href="https://www.linkedin.com/in/jakebartoncreative" target="_blank" class="btn-icon" aria-label="LinkedIn">in</a>
          <?php if (!empty($contact['github'])): ?>
          <a href="https://github.com/<?php echo $contact['github']; ?>" target="_blank" class="btn-icon" aria-label="GitHub">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.477 2 2 6.477 2 12c0 4.418 2.865 8.166 6.839 9.489.5.092.682-.217.682-.482 0-.237-.009-.868-.013-1.703-2.782.604-3.369-1.341-3.369-1.341-.454-1.155-1.11-1.463-1.11-1.463-.908-.62.069-.608.069-.608 1.003.07 1.531 1.03 1.531 1.03.892 1.529 2.341 1.087 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.11-4.555-4.943 0-1.091.39-1.984 1.029-2.683-.103-.253-.446-1.27.098-2.647 0 0 .84-.269 2.75 1.025A9.564 9.564 0 0112 6.844a9.59 9.59 0 012.504.337c1.909-1.294 2.747-1.025 2.747-1.025.546 1.377.203 2.394.1 2.647.64.699 1.028 1.592 1.028 2.683 0 3.842-2.339 4.687-4.566 4.935.359.309.678.919.678 1.852 0 1.336-.012 2.415-.012 2.744 0 .267.18.578.688.48C19.138 20.163 22 16.418 22 12c0-5.523-4.477-10-10-10z"/></svg>
          </a>
          <?php endif; ?>
          <?php if (!empty($contact['instagram'])): ?>
          <a href="https://instagram.com/<?php echo $contact['instagram']; ?>" target="_blank" class="btn-icon" aria-label="Instagram">IG</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </footer>

  <style>
    /* ── Hero name — clean h1, no canvas ────────────── */
    .hero-name {
      font-family: var(--font-display);
      font-size: clamp(3.5rem, 10vw, 7.5rem);
      font-weight: 700;
      line-height: 1.0;
      letter-spacing: -0.02em;
      color: var(--text);
      margin-bottom: 1rem;
    }
    .hero-tagline {
      font-family: var(--font-display);
      font-size: clamp(1.1rem, 2.5vw, 1.5rem);
      font-weight: 400;
      color: var(--text-muted);
      margin-bottom: 1.25rem;
      font-style: normal;
    }
    /* em inside tagline — only applies before JS replaces it with .rt-wrap */
    .hero-tagline em {
      color: var(--accent-light);
      font-style: italic;
    }

    /* ── Hero Stats Bar ──────────────────────────────── */
    .hero-stats {
      display: flex;
      align-items: center;
      margin-top: 4rem;
      padding: 1.5rem 2.5rem;
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: var(--radius-xl);
      flex-wrap: wrap;
      justify-content: center;
      gap: 0;
    }
    .hero-stat {
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 0 2rem;
    }
    .hero-stat-num {
      font-family: var(--font-display);
      font-size: 1.75rem;
      font-weight: 700;
      color: var(--accent-light);
      line-height: 1;
    }
    .hero-stat-label {
      font-size: 0.72rem;
      color: var(--text-muted);
      margin-top: 0.3rem;
      letter-spacing: 0.04em;
      white-space: nowrap;
    }
    .hero-stat-divider {
      width: 1px;
      height: 2.5rem;
      background: var(--border);
      flex-shrink: 0;
    }
    @media (max-width: 640px) {
      .hero-stats { gap: 1rem; padding: 1.25rem; }
      .hero-stat { padding: 0 1rem; }
      .hero-stat-divider { display: none; }
    }

    /* ── Scroll indicator fade ───────────────────────── */
    .scroll-indicator { transition: opacity 0.4s ease; }

    /* ── Currently Building marquee strip ───────────── */
    .now-strip {
      border-top: 1px solid var(--border);
      border-bottom: 1px solid var(--border);
      padding: 0.6rem 0;
      overflow: hidden;
      background: var(--bg-card2);
      position: relative;
    }
    .now-marquee-track {
      display: flex;
      width: 100%;
      overflow: hidden;
    }
    .now-marquee-inner {
      display: flex;
      align-items: center;
      gap: 2rem;
      white-space: nowrap;
      animation: marquee-scroll 28s linear infinite;
      will-change: transform;
    }
    .now-strip:hover .now-marquee-inner {
      animation-play-state: paused;
    }
    @keyframes marquee-scroll {
      from { transform: translateX(0); }
      to   { transform: translateX(-50%); }
    }
    .now-item {
      display: inline-flex;
      align-items: center;
      gap: 0.6rem;
      font-size: 0.78rem;
      color: var(--text-muted);
      white-space: nowrap;
    }
    .now-item strong { color: var(--text); font-weight: 600; }
    .now-sep {
      font-size: 0.6rem;
      color: var(--accent);
      opacity: 0.6;
      flex-shrink: 0;
    }
    .now-dot {
      width: 6px;
      height: 6px;
      border-radius: 50%;
      background: var(--accent);
      display: inline-block;
      animation: pulse-dot 2s ease-in-out infinite;
      flex-shrink: 0;
    }
    @keyframes pulse-dot {
      0%, 100% { opacity: 1; transform: scale(1); box-shadow: 0 0 0 0 rgba(255,255,255,0.35); }
      50% { opacity: 0.8; transform: scale(0.8); box-shadow: 0 0 0 4px rgba(255,255,255,0); }
    }

    /* ── Showcase Grid ───────────────────────────────── */
    .showcase-grid {
      display: grid;
      grid-template-columns: 1.5fr 1fr 1fr;
      grid-template-rows: auto auto;
      gap: 1rem;
    }

    /* Hero card spans 2 rows on the left */
    .showcase-card--hero {
      grid-row: 1 / 3;
    }

    /* All cards share base styles */
    .showcase-card {
      position: relative;
      border-radius: var(--radius-xl);
      overflow: hidden;
      display: block;
      text-decoration: none;
      cursor: pointer;
      background: var(--bg-card2);
      /* remove visible border — image IS the card */
    }

    /* Hero card is tall */
    .showcase-card--hero { min-height: 520px; }

    /* VR card: tall right-side card */
    .showcase-card--tall { min-height: 250px; }

    /* Wide cards on bottom row */
    .showcase-card--wide { min-height: 210px; }

    /* Full-bleed image */
    .showcase-img {
      position: absolute;
      inset: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.7s cubic-bezier(0.16,1,0.3,1);
      display: block;
    }
    .showcase-card:hover .showcase-img {
      transform: scale(1.06);
    }

    /* Placeholder for cards without an image */
    .showcase-placeholder {
      position: absolute;
      inset: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      background: var(--bg-card2);
    }
    .showcase-placeholder-label {
      font-family: var(--font-display);
      font-size: 4rem;
      font-weight: 700;
      color: rgba(255,255,255,0.08);
      letter-spacing: 0.1em;
    }

    /* Gradient overlay — text lives here */
    .showcase-overlay {
      position: absolute;
      inset: 0;
      background: linear-gradient(to top,
        rgba(0,0,0,0.92) 0%,
        rgba(0,0,0,0.55) 40%,
        rgba(0,0,0,0.1)  70%,
        transparent      100%);
      display: flex;
      flex-direction: column;
      justify-content: flex-end;
      padding: 1.5rem;
      transition: background 0.4s ease;
    }
    .showcase-card:hover .showcase-overlay {
      background: linear-gradient(to top,
        rgba(0,0,0,0.97) 0%,
        rgba(0,0,0,0.65) 50%,
        rgba(0,0,0,0.15) 80%,
        transparent      100%);
    }

    /* Tags */
    .showcase-tags {
      display: flex;
      flex-wrap: wrap;
      gap: 0.35rem;
      margin-bottom: 0.6rem;
    }

    /* Title */
    .showcase-title {
      font-family: var(--font-display);
      font-size: clamp(1.1rem, 2vw, 1.5rem);
      font-weight: 700;
      color: #fff;
      line-height: 1.2;
      margin: 0 0 0.4rem;
    }
    .showcase-card--hero .showcase-title {
      font-size: clamp(1.4rem, 2.5vw, 2rem);
    }

    /* Description — hidden by default, revealed on hover */
    .showcase-desc {
      font-size: 0.82rem;
      color: rgba(255,255,255,0.7);
      line-height: 1.55;
      margin: 0 0 0.75rem;
      max-height: 0;
      overflow: hidden;
      opacity: 0;
      transition: max-height 0.4s ease, opacity 0.4s ease;
    }
    .showcase-card:hover .showcase-desc {
      max-height: 80px;
      opacity: 1;
    }

    /* CTA arrow */
    .showcase-cta {
      font-size: 0.78rem;
      font-weight: 600;
      color: #fff;
      letter-spacing: 0.06em;
      text-transform: uppercase;
      opacity: 0;
      transform: translateY(6px);
      transition: opacity 0.3s ease 0.1s, transform 0.3s ease 0.1s;
      display: inline-block;
    }
    .showcase-card:hover .showcase-cta {
      opacity: 1;
      transform: translateY(0);
    }

    /* Featured badge */
    .showcase-badge {
      position: absolute;
      top: 1rem;
      left: 1rem;
      background: #fff;
      color: #000;
      font-size: 0.6rem;
      font-weight: 700;
      letter-spacing: 0.1em;
      text-transform: uppercase;
      padding: 0.25rem 0.65rem;
      border-radius: 99px;
    }

    /* Subtle border that glows on hover */
    .showcase-card::after {
      content: '';
      position: absolute;
      inset: 0;
      border-radius: var(--radius-xl);
      border: 1px solid rgba(255,255,255,0);
      transition: border-color 0.3s ease;
      pointer-events: none;
    }
    .showcase-card:hover::after {
      border-color: rgba(255,255,255,0.18);
    }

    @media (max-width: 900px) {
      .showcase-grid {
        grid-template-columns: 1fr 1fr;
        grid-template-rows: auto;
      }
      .showcase-card--hero { grid-row: 1; grid-column: 1 / 3; min-height: 340px; }
      .showcase-card--tall, .showcase-card--wide { min-height: 200px; }
    }
    @media (max-width: 600px) {
      .showcase-grid { grid-template-columns: 1fr; }
      .showcase-card--hero { grid-column: 1; min-height: 280px; }
      .showcase-desc { max-height: none; opacity: 1; }
      .showcase-cta { opacity: 1; transform: none; }
    }

    /* ── Skills Grid ─────────────────────────────────── */
    .skills-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 1.25rem;
    }
    .skill-group-header {
      display: flex;
      align-items: center;
      gap: 0.6rem;
      margin-bottom: 1.25rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid var(--border);
    }
    .skill-group-icon { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.06em; line-height: 1; color: var(--accent); font-family: var(--font-mono, monospace); }
    .skill-group-title {
      font-family: var(--font-display);
      font-size: 1.1rem;
      font-weight: 700;
      color: var(--text);
    }
    .skill-group-pills {
      display: flex;
      flex-wrap: wrap;
      gap: 0.5rem;
    }
    .skill-pill.primary {
      border-color: var(--border-cyan);
      color: var(--text);
      background: var(--bg-card2);
    }
    .skill-pill.primary .dot { background: var(--accent); }

    /* Skill pill hover pop */
    .skill-pill {
      transition: transform 0.2s cubic-bezier(0.16,1,0.3,1),
                  border-color 0.2s ease,
                  background 0.2s ease;
    }
    .skill-pill:hover {
      transform: translateY(-2px) scale(1.04);
      border-color: var(--accent);
      background: rgba(255,255,255,0.04);
    }
    @media (max-width: 768px) {
      .skills-grid { grid-template-columns: 1fr; }
    }

    /* ── About 2-col layout ──────────────────────────── */
    .about-grid {
      display: grid;
      grid-template-columns: 1fr 380px;
      gap: 4rem;
      align-items: start;
    }
    .about-text h2 { font-size: clamp(1.8rem, 3.5vw, 2.5rem); line-height: 1.2; }
    .about-text h2 em { color: var(--accent-light); font-style: italic; }
    .cred-card { padding: 0; overflow: hidden; }
    .cred-row {
      display: flex;
      justify-content: space-between;
      align-items: baseline;
      padding: 0.9rem 1.5rem;
      border-bottom: 1px solid var(--border);
      gap: 1rem;
    }
    .cred-label {
      font-size: 0.72rem;
      font-weight: 600;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      color: var(--text-faint);
      white-space: nowrap;
      flex-shrink: 0;
    }
    .cred-value {
      font-size: 0.9rem;
      color: var(--text);
      text-align: right;
    }
    @media (max-width: 900px) {
      .about-grid { grid-template-columns: 1fr; gap: 2rem; }
    }

    /* ── Carousel hidden ─────────────────────────────── */
    .carousel-wrapper, .carousel-track, .carousel-slide,
    .carousel-card, .carousel-card-image, .carousel-info,
    .carousel-dots { display: none; }
  </style>

  <script>
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

    /* ── Scroll indicator fade ────────────────────────── */
    (function() {
      var indicator = document.querySelector('.scroll-indicator');
      if (!indicator) return;
      window.addEventListener('scroll', function() {
        indicator.style.opacity = window.scrollY > 80 ? '0' : '1';
        indicator.style.pointerEvents = window.scrollY > 80 ? 'none' : 'auto';
      }, { passive: true });
    })();

    /* ── Ambient cursor glow ──────────────────────────── */
    (function() {
      var glow = document.getElementById('cursor-glow');
      if (!glow || window.matchMedia('(pointer:coarse)').matches) return;
      var cx = 0, cy = 0, tx = 0, ty = 0, raf;
      document.addEventListener('mousemove', function(e) {
        tx = e.clientX; ty = e.clientY;
        glow.style.opacity = '1';
      });
      document.addEventListener('mouseleave', function() {
        glow.style.opacity = '0';
      });
      function lerp(a, b, t) { return a + (b - a) * t; }
      function loop() {
        cx = lerp(cx, tx, 0.07);
        cy = lerp(cy, ty, 0.07);
        glow.style.left = cx + 'px';
        glow.style.top  = cy + 'px';
        raf = requestAnimationFrame(loop);
      }
      loop();
    })();

    /* ── Hero parallax (name/tagline depth layers) ────── */
    (function() {
      var parallaxEls = document.querySelectorAll('[data-parallax]');
      if (!parallaxEls.length) return;
      function onScroll() {
        var sy = window.scrollY;
        parallaxEls.forEach(function(el) {
          var speed = parseFloat(el.dataset.parallax) || 0.15;
          el.style.transform = 'translateY(' + (sy * speed) + 'px)';
        });
      }
      window.addEventListener('scroll', onScroll, { passive: true });
    })();

    /* ── Contact form ─────────────────────────────────── */
    document.getElementById('contactForm').addEventListener('submit', async function(e) {
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
          formMessage.style.cssText = 'display:block;background:var(--accent-subtle);border:1px solid var(--accent);color:var(--accent-light);padding:0.75rem;border-radius:var(--radius-md)';
          this.reset();
        } else {
          formMessage.textContent = data.errors.join(', ');
          formMessage.style.cssText = 'display:block;background:rgba(255,0,107,0.08);border:1px solid var(--pink);color:var(--pink);padding:0.75rem;border-radius:var(--radius-md)';
        }
      } catch(err) {
        formMessage.textContent = 'Error sending message. Please email directly.';
        formMessage.style.cssText = 'display:block;background:rgba(255,0,107,0.08);border:1px solid var(--pink);color:var(--pink);padding:0.75rem;border-radius:var(--radius-md)';
      }
      submitBtn.disabled = false;
      submitBtn.textContent = 'Send Message →';
    });
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
