<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Jake Barton — Digital Card</title>
  <meta name="description" content="Jake Barton · Gameplay Programmer & Technical Designer · Birmingham, AL">

  <!-- Open Graph / iMessage preview -->
  <meta property="og:title" content="Jake Barton — Digital Card">
  <meta property="og:description" content="Gameplay Programmer · Game Design & 3D Animation · Samford University">
  <meta property="og:image" content="https://jakebartoncreative.com/assets/images/jb-logo.png">
  <meta property="og:url" content="https://jakebartoncreative.com/card">
  <meta property="og:type" content="profile">
  <meta name="twitter:card" content="summary">

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="assets/images/jb-logo.png">
  <link rel="apple-touch-icon" href="assets/images/jb-logo.png">

  <!-- Apple Wallet / vCard meta hint -->
  <meta name="format-detection" content="telephone=yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <meta name="apple-mobile-web-app-title" content="Jake Barton">
  <meta name="theme-color" content="#0a0a0a">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

  <style>
    /* ── Reset ─────────────────────────────────────── */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html, body {
      height: 100%; min-height: 100dvh;
      background: #0a0a0a;
      color: #f5f5f5;
      font-family: 'Inter', system-ui, -apple-system, sans-serif;
      -webkit-font-smoothing: antialiased;
      overscroll-behavior: none;
    }
    a { color: inherit; text-decoration: none; }

    /* ── Page Shell ─────────────────────────────────── */
    .bc-page {
      min-height: 100dvh;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 0 0 2.5rem;
      position: relative;
      overflow-x: hidden;
    }

    /* ── Hero Reel (looping video strip) ─────────────── */
    .bc-reel {
      width: 100%;
      height: 52vw;
      max-height: 340px;
      min-height: 180px;
      position: relative;
      overflow: hidden;
      background: #111;
      flex-shrink: 0;
    }
    .bc-reel-track {
      display: flex;
      height: 100%;
      animation: reel-slide 14s ease-in-out infinite;
    }
    .bc-reel-clip {
      flex: 0 0 100%;
      width: 100%;
      height: 100%;
      position: relative;
      overflow: hidden;
    }
    .bc-reel-clip video,
    .bc-reel-clip img {
      position: absolute;
      inset: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
      object-position: center;
    }
    .bc-reel-clip::after {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(to bottom, rgba(10,10,10,0) 40%, rgba(10,10,10,0.92) 100%);
      pointer-events: none;
    }
    @keyframes reel-slide {
      0%        { transform: translateX(0%); }
      18%       { transform: translateX(0%); }
      25%       { transform: translateX(-100%); }
      43%       { transform: translateX(-100%); }
      50%       { transform: translateX(-200%); }
      68%       { transform: translateX(-200%); }
      75%       { transform: translateX(-300%); }
      93%       { transform: translateX(-300%); }
      100%      { transform: translateX(-400%); }
    }

    /* Reel label chips */
    .bc-reel-label {
      position: absolute;
      bottom: 0.75rem;
      left: 1rem;
      z-index: 2;
      background: rgba(255,255,255,0.1);
      backdrop-filter: blur(8px);
      -webkit-backdrop-filter: blur(8px);
      border: 1px solid rgba(255,255,255,0.15);
      padding: 0.25rem 0.65rem;
      border-radius: 999px;
      font-size: 0.65rem;
      font-family: 'Syne', sans-serif;
      font-weight: 600;
      letter-spacing: 0.06em;
      text-transform: uppercase;
      color: rgba(255,255,255,0.85);
    }

    /* Reel dots */
    .bc-reel-dots {
      position: absolute;
      bottom: 0.75rem;
      right: 1rem;
      z-index: 2;
      display: flex;
      gap: 5px;
    }
    .bc-reel-dot {
      width: 5px; height: 5px;
      border-radius: 50%;
      background: rgba(255,255,255,0.3);
      transition: background 0.3s;
    }
    .bc-reel-dot.active { background: #fff; }

    /* ── Identity Block ──────────────────────────────── */
    .bc-identity {
      width: 100%;
      max-width: 480px;
      padding: 1.5rem 1.5rem 0;
      position: relative;
    }

    /* Avatar */
    .bc-avatar {
      width: 68px; height: 68px;
      border-radius: 50%;
      border: 2px solid rgba(255,255,255,0.18);
      overflow: hidden;
      margin-bottom: 1rem;
      background: #1a1a1a;
      flex-shrink: 0;
    }
    .bc-avatar img {
      width: 100%; height: 100%;
      object-fit: contain;
      padding: 6px;
      filter: brightness(1.1);
    }

    .bc-name {
      font-family: 'Syne', sans-serif;
      font-size: clamp(2rem, 7vw, 2.6rem);
      font-weight: 800;
      line-height: 1;
      letter-spacing: -0.03em;
      color: #fff;
      margin-bottom: 0.35rem;
    }
    .bc-role {
      font-size: 0.82rem;
      color: rgba(255,255,255,0.5);
      font-weight: 500;
      letter-spacing: 0.04em;
      text-transform: uppercase;
      margin-bottom: 0.2rem;
    }
    .bc-university {
      font-size: 0.78rem;
      color: rgba(255,255,255,0.35);
      font-weight: 400;
    }

    /* Tags row */
    .bc-tags {
      display: flex;
      flex-wrap: wrap;
      gap: 0.4rem;
      margin: 1rem 0 0;
    }
    .bc-tag {
      font-size: 0.65rem;
      font-family: 'Syne', sans-serif;
      font-weight: 600;
      letter-spacing: 0.07em;
      text-transform: uppercase;
      color: rgba(255,255,255,0.6);
      border: 1px solid rgba(255,255,255,0.12);
      border-radius: 999px;
      padding: 0.22rem 0.7rem;
      background: rgba(255,255,255,0.04);
    }

    /* ── Divider ─────────────────────────────────────── */
    .bc-divider {
      width: 100%;
      max-width: 480px;
      height: 1px;
      background: rgba(255,255,255,0.07);
      margin: 1.4rem 1.5rem;
      width: calc(100% - 3rem);
    }

    /* ── Contact Actions ─────────────────────────────── */
    .bc-actions {
      width: 100%;
      max-width: 480px;
      padding: 0 1.5rem;
      display: flex;
      flex-direction: column;
      gap: 0.55rem;
    }

    .bc-action-row {
      display: flex;
      align-items: center;
      gap: 0.85rem;
      padding: 0.9rem 1.1rem;
      background: rgba(255,255,255,0.04);
      border: 1px solid rgba(255,255,255,0.07);
      border-radius: 14px;
      transition: background 0.18s, border-color 0.18s, transform 0.15s;
      -webkit-tap-highlight-color: transparent;
      cursor: pointer;
      text-decoration: none;
      color: inherit;
    }
    .bc-action-row:active {
      background: rgba(255,255,255,0.09);
      transform: scale(0.98);
    }
    .bc-action-icon {
      width: 36px; height: 36px;
      border-radius: 9px;
      background: rgba(255,255,255,0.07);
      border: 1px solid rgba(255,255,255,0.1);
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
      color: rgba(255,255,255,0.7);
    }
    .bc-action-icon svg { width: 18px; height: 18px; }
    .bc-action-text { flex: 1; min-width: 0; }
    .bc-action-label {
      font-size: 0.7rem;
      color: rgba(255,255,255,0.35);
      text-transform: uppercase;
      letter-spacing: 0.06em;
      font-weight: 600;
      margin-bottom: 0.1rem;
    }
    .bc-action-value {
      font-size: 0.9rem;
      font-weight: 500;
      color: rgba(255,255,255,0.88);
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .bc-action-arrow {
      color: rgba(255,255,255,0.2);
      font-size: 0.9rem;
      flex-shrink: 0;
    }

    /* ── Save Contact CTA ────────────────────────────── */
    .bc-save-wrap {
      width: 100%;
      max-width: 480px;
      padding: 0 1.5rem;
      margin-top: 1.1rem;
    }
    .bc-save-btn {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.55rem;
      width: 100%;
      padding: 1rem 1.5rem;
      background: #fff;
      color: #0a0a0a;
      font-family: 'Syne', sans-serif;
      font-weight: 700;
      font-size: 0.92rem;
      letter-spacing: 0.02em;
      border-radius: 14px;
      border: none;
      cursor: pointer;
      -webkit-tap-highlight-color: transparent;
      transition: opacity 0.15s, transform 0.15s;
      text-decoration: none;
    }
    .bc-save-btn:active { opacity: 0.85; transform: scale(0.98); }
    .bc-save-btn svg { width: 18px; height: 18px; }

    /* ── Mini Portfolio Strip ────────────────────────── */
    .bc-portfolio-label {
      width: 100%;
      max-width: 480px;
      padding: 0 1.5rem;
      margin-top: 1.6rem;
      margin-bottom: 0.75rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .bc-portfolio-label span {
      font-size: 0.68rem;
      text-transform: uppercase;
      letter-spacing: 0.1em;
      font-weight: 700;
      font-family: 'Syne', sans-serif;
      color: rgba(255,255,255,0.35);
    }
    .bc-portfolio-label a {
      font-size: 0.7rem;
      color: rgba(255,255,255,0.45);
      font-weight: 500;
      text-decoration: none;
    }

    .bc-strip {
      width: 100%;
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
      scrollbar-width: none;
      padding: 0 1.5rem;
      scroll-snap-type: x mandatory;
    }
    .bc-strip::-webkit-scrollbar { display: none; }
    .bc-strip-inner {
      display: flex;
      gap: 0.6rem;
      width: max-content;
    }

    .bc-proj {
      scroll-snap-align: start;
      width: 140px;
      height: 110px;
      border-radius: 12px;
      overflow: hidden;
      position: relative;
      flex-shrink: 0;
      background: #161616;
      border: 1px solid rgba(255,255,255,0.07);
      -webkit-tap-highlight-color: transparent;
      cursor: pointer;
      transition: transform 0.15s;
      text-decoration: none;
      display: block;
    }
    .bc-proj:active { transform: scale(0.96); }
    .bc-proj video,
    .bc-proj img {
      position: absolute;
      inset: 0;
      width: 100%; height: 100%;
      object-fit: cover;
    }
    .bc-proj::after {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(to top, rgba(0,0,0,0.82) 0%, rgba(0,0,0,0) 55%);
    }
    .bc-proj-info {
      position: absolute;
      bottom: 0; left: 0; right: 0;
      padding: 0.4rem 0.55rem;
      z-index: 1;
    }
    .bc-proj-title {
      font-family: 'Syne', sans-serif;
      font-size: 0.7rem;
      font-weight: 700;
      color: #fff;
      line-height: 1.2;
      display: block;
    }
    .bc-proj-tag {
      font-size: 0.58rem;
      color: rgba(255,255,255,0.5);
      font-weight: 500;
    }

    /* ── Footer ──────────────────────────────────────── */
    .bc-footer {
      width: 100%;
      max-width: 480px;
      padding: 0 1.5rem;
      margin-top: 1.8rem;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 0.4rem;
    }
    .bc-footer-logo {
      width: 28px; height: 28px;
      opacity: 0.25;
    }
    .bc-footer-url {
      font-size: 0.68rem;
      color: rgba(255,255,255,0.2);
      letter-spacing: 0.04em;
    }

    /* ── Entrance Animations ─────────────────────────── */
    .bc-reel      { animation: bc-fade-in 0.6s ease both; }
    .bc-identity  { animation: bc-slide-up 0.55s 0.1s cubic-bezier(0.16,1,0.3,1) both; }
    .bc-actions   { animation: bc-slide-up 0.55s 0.18s cubic-bezier(0.16,1,0.3,1) both; }
    .bc-save-wrap { animation: bc-slide-up 0.55s 0.24s cubic-bezier(0.16,1,0.3,1) both; }
    .bc-strip     { animation: bc-slide-up 0.55s 0.3s cubic-bezier(0.16,1,0.3,1) both; }

    @keyframes bc-fade-in  { from { opacity:0 } to { opacity:1 } }
    @keyframes bc-slide-up { from { opacity:0; transform:translateY(14px) } to { opacity:1; transform:translateY(0) } }

    /* ── Safe area (iPhone notch) ────────────────────── */
    @supports (padding-bottom: env(safe-area-inset-bottom)) {
      .bc-page { padding-bottom: calc(2.5rem + env(safe-area-inset-bottom)); }
    }
  </style>
</head>
<body>
<?php
require_once __DIR__ . '/includes/content.php';
?>
<div class="bc-page">

  <!-- ── Hero Reel ──────────────────────────────────── -->
  <div class="bc-reel" id="bcReel">
    <div class="bc-reel-track" id="bcTrack">

      <div class="bc-reel-clip">
        <video data-src="assets/images/phase-runner-screen.mp4" muted loop playsinline preload="none" poster="assets/images/phaserunnercover.png"></video>
        <span class="bc-reel-label">Phase Runner</span>
      </div>

      <div class="bc-reel-clip">
        <video data-src="assets/images/mariokart.mp4" muted loop playsinline preload="none" poster="assets/images/mariokart.png"></video>
        <span class="bc-reel-label">Mario Kart Recreation</span>
      </div>

      <div class="bc-reel-clip">
        <video data-src="assets/images/environment-scene.mp4" muted loop playsinline preload="none" poster="assets/images/shelcover.png"></video>
        <span class="bc-reel-label">3D Environment</span>
      </div>

      <div class="bc-reel-clip">
        <video data-src="assets/images/vr-gameplay.mp4" muted loop playsinline preload="none" poster="assets/images/phaserunnercover.png"></video>
        <span class="bc-reel-label">VR Rhythm Game</span>
      </div>

      <div class="bc-reel-clip">
        <img src="assets/images/venice-art.jpg" alt="Venice Fine Art" loading="lazy">
        <span class="bc-reel-label">Venice · Fine Art</span>
      </div>

    </div>

    <!-- Dots indicator -->
    <div class="bc-reel-dots" id="bcDots">
      <div class="bc-reel-dot active"></div>
      <div class="bc-reel-dot"></div>
      <div class="bc-reel-dot"></div>
      <div class="bc-reel-dot"></div>
      <div class="bc-reel-dot"></div>
    </div>
  </div>

  <!-- ── Identity ───────────────────────────────────── -->
  <div class="bc-identity">
    <div class="bc-avatar">
      <img src="assets/images/jb-logo.png" alt="JB">
    </div>
    <div class="bc-name"><?php echo $content['name']; ?></div>
    <div class="bc-role">Gameplay Programmer · Technical Designer</div>
    <div class="bc-university"><?php echo $content['university']; ?> · Game Design &amp; 3D Animation · <?php echo $content['grad_year']; ?></div>
    <div class="bc-tags">
      <span class="bc-tag">Unreal Engine 5</span>
      <span class="bc-tag">Godot 4</span>
      <span class="bc-tag">C++</span>
      <span class="bc-tag">JavaScript</span>
      <span class="bc-tag">Web Dev</span>
      <span class="bc-tag">3D Art</span>
    </div>
  </div>

  <div class="bc-divider"></div>

  <!-- ── Contact Actions ────────────────────────────── -->
  <div class="bc-actions">

    <a href="mailto:<?php echo $content['email']; ?>" class="bc-action-row">
      <div class="bc-action-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <rect x="2" y="4" width="20" height="16" rx="2"/>
          <polyline points="2,4 12,14 22,4"/>
        </svg>
      </div>
      <div class="bc-action-text">
        <div class="bc-action-label">Email</div>
        <div class="bc-action-value"><?php echo $content['email']; ?></div>
      </div>
      <span class="bc-action-arrow">›</span>
    </a>

    <a href="tel:<?php echo preg_replace('/[^0-9+]/', '', $content['phone']); ?>" class="bc-action-row">
      <div class="bc-action-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.4 2 2 0 0 1 3.6 1.22h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.79a16 16 0 0 0 6.29 6.29l.96-.96a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
        </svg>
      </div>
      <div class="bc-action-text">
        <div class="bc-action-label">Phone</div>
        <div class="bc-action-value"><?php echo $content['phone']; ?></div>
      </div>
      <span class="bc-action-arrow">›</span>
    </a>

    <a href="https://www.linkedin.com/in/<?php echo $content['linkedin']; ?>" target="_blank" rel="noopener" class="bc-action-row">
      <div class="bc-action-icon">
        <svg viewBox="0 0 24 24" fill="currentColor">
          <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/>
          <rect x="2" y="9" width="4" height="12"/>
          <circle cx="4" cy="4" r="2"/>
        </svg>
      </div>
      <div class="bc-action-text">
        <div class="bc-action-label">LinkedIn</div>
        <div class="bc-action-value">in/<?php echo $content['linkedin']; ?></div>
      </div>
      <span class="bc-action-arrow">›</span>
    </a>

    <a href="https://github.com/<?php echo $content['github']; ?>" target="_blank" rel="noopener" class="bc-action-row">
      <div class="bc-action-icon">
        <svg viewBox="0 0 24 24" fill="currentColor">
          <path d="M12 0C5.37 0 0 5.37 0 12c0 5.3 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61-.546-1.385-1.335-1.755-1.335-1.755-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23A11.52 11.52 0 0 1 12 5.803c1.02.005 2.047.138 3.006.404 2.29-1.552 3.297-1.23 3.297-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.807 5.625-5.479 5.92.43.372.823 1.102.823 2.222 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 21.795 24 17.295 24 12c0-6.63-5.37-12-12-12z"/>
        </svg>
      </div>
      <div class="bc-action-text">
        <div class="bc-action-label">GitHub</div>
        <div class="bc-action-value">github.com/<?php echo $content['github']; ?></div>
      </div>
      <span class="bc-action-arrow">›</span>
    </a>

    <a href="<?php echo $content['website']; ?>" target="_blank" rel="noopener" class="bc-action-row">
      <div class="bc-action-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="10"/>
          <path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
        </svg>
      </div>
      <div class="bc-action-text">
        <div class="bc-action-label">Portfolio</div>
        <div class="bc-action-value">jakebartoncreative.com</div>
      </div>
      <span class="bc-action-arrow">›</span>
    </a>

  </div>

  <!-- ── Save to Contacts ────────────────────────────── -->
  <div class="bc-save-wrap">
    <a href="/vcard" class="bc-save-btn" download="Jake-Barton.vcf">
      <!-- Person+ icon -->
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
        <circle cx="12" cy="7" r="4"/>
        <line x1="19" y1="8" x2="19" y2="14"/>
        <line x1="16" y1="11" x2="22" y2="11"/>
      </svg>
      Add to Contacts
    </a>
  </div>

  <!-- ── Mini Portfolio Strip ──────────────────────── -->
  <div class="bc-portfolio-label">
    <span>Selected Work</span>
    <a href="<?php echo $content['website']; ?>/portfolio/">See All →</a>
  </div>

  <div class="bc-strip">
    <div class="bc-strip-inner">

      <a href="https://clervercarpet99.itch.io/phase-runner" target="_blank" rel="noopener" class="bc-proj">
        <video data-src="assets/images/phase-runner-screen.mp4" muted loop playsinline preload="none" poster="assets/images/phaserunnercover.png"></video>
        <div class="bc-proj-info">
          <span class="bc-proj-title">Phase Runner</span>
          <span class="bc-proj-tag">Godot 4 · itch.io</span>
        </div>
      </a>

      <a href="<?php echo $content['website']; ?>/portfolio/game-programming/" target="_blank" rel="noopener" class="bc-proj">
        <video data-src="assets/images/vr-gameplay.mp4" muted loop playsinline preload="none" poster="assets/images/phaserunnercover.png"></video>
        <div class="bc-proj-info">
          <span class="bc-proj-title">VR Rhythm Game</span>
          <span class="bc-proj-tag">Unreal Engine 5</span>
        </div>
      </a>

      <a href="<?php echo $content['website']; ?>/portfolio/game-programming/" target="_blank" rel="noopener" class="bc-proj">
        <video data-src="assets/images/mariokart.mp4" muted loop playsinline preload="none" poster="assets/images/mariokart.png"></video>
        <div class="bc-proj-info">
          <span class="bc-proj-title">Mario Kart JS</span>
          <span class="bc-proj-tag">JavaScript · Mode-7</span>
        </div>
      </a>

      <a href="<?php echo $content['website']; ?>/portfolio/art/" target="_blank" rel="noopener" class="bc-proj">
        <img src="assets/images/venice-art.jpg" alt="Venice" loading="lazy">
        <div class="bc-proj-info">
          <span class="bc-proj-title">Venice</span>
          <span class="bc-proj-tag">Fine Art · 2025</span>
        </div>
      </a>

      <a href="<?php echo $content['website']; ?>/portfolio/professional-works/" target="_blank" rel="noopener" class="bc-proj">
        <img src="assets/images/33miles-cover.png" alt="33 Miles" loading="lazy">
        <div class="bc-proj-info">
          <span class="bc-proj-title">33 Miles</span>
          <span class="bc-proj-tag">Client · Illustrator</span>
        </div>
      </a>

    </div>
  </div>

  <!-- ── Footer ─────────────────────────────────────── -->
  <div class="bc-footer">
    <img src="assets/images/jb-logo.png" alt="JB" class="bc-footer-logo">
    <span class="bc-footer-url">jakebartoncreative.com</span>
  </div>

</div><!-- .bc-page -->

<script>
// ── Reel dot syncing ────────────────────────────────────────
(function(){
  var CLIPS = 5;
  var INTERVAL = 14000 / CLIPS; // ms per clip (matches CSS animation)
  var dots = document.querySelectorAll('.bc-reel-dot');
  var current = 0;

  function tick() {
    dots[current].classList.remove('active');
    current = (current + 1) % CLIPS;
    dots[current].classList.add('active');
  }

  setInterval(tick, INTERVAL);
})();

/* Lazy-load all card.php videos on page load */
(function(){
  function loadAndPlay(v) {
    if (v.dataset.src && !v.getAttribute('src')) {
      v.src = v.dataset.src;
      v.load();
    }
    v.play().catch(function(){});
  }
  var vids = document.querySelectorAll('video[data-src]');
  if (!vids.length) return;
  // Load first clip immediately, stagger the rest to avoid saturating bandwidth
  if (vids[0]) loadAndPlay(vids[0]);
  vids.forEach(function(v, i){
    if (i === 0) return;
    setTimeout(function(){ loadAndPlay(v); }, i * 800);
  });
})();
</script>
</body>
</html>
