<?php
/**
 * qr-paul.php — QR card for Paul Lovejoy
 * Target: paullovejoycreative.com
 * Aesthetic: matches Paul's Wix site — full-bleed cinematic hero, pitch black,
 * stark white ALL-CAPS headline (Bebas Neue), thin Barlow body type,
 * zero rounded corners, wide letter-spacing. "3D ARTIST // ANIMATOR" energy.
 */
$TARGET_URL = 'https://www.paullovejoycreative.com';
$encodedURL = urlencode($TARGET_URL);
$qrSrc      = "https://chart.googleapis.com/chart?cht=qr&chs=300x300&chl={$encodedURL}&choe=UTF-8&chld=H|1";
$qrSrcHD    = "https://chart.googleapis.com/chart?cht=qr&chs=600x600&chl={$encodedURL}&choe=UTF-8&chld=H|1";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Paul Lovejoy — Portfolio</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Barlow:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --bg:    #000000;
      --rule:  rgba(255,255,255,0.12);
      --text:  #ffffff;
      --muted: rgba(255,255,255,0.42);
    }

    html, body {
      background: var(--bg);
      color: var(--text);
      font-family: 'Barlow', -apple-system, sans-serif;
      font-weight: 300;
      -webkit-font-smoothing: antialiased;
      min-height: 100dvh;
    }

    /* ── Full-bleed cinematic hero ── */
    .hero {
      position: relative;
      width: 100%;
      aspect-ratio: 16/9;
      overflow: hidden;
      background: #080808;
    }
    .hero img {
      width: 100%; height: 100%;
      object-fit: cover; display: block;
      filter: brightness(0.72) contrast(1.08) saturate(0.85);
    }
    .hero::after {
      content: '';
      position: absolute; inset: 0;
      background: linear-gradient(to bottom, transparent 40%, rgba(0,0,0,0.6) 72%, #000 100%);
    }
    .hero-name {
      position: absolute; bottom: 1.1rem; left: 1.4rem; z-index: 2;
      font-family: 'Bebas Neue', sans-serif;
      font-size: clamp(2.4rem, 9vw, 3.6rem);
      letter-spacing: 0.06em; line-height: 1; color: #fff;
      text-shadow: 0 2px 20px rgba(0,0,0,0.8);
    }

    /* ── Body ── */
    .body {
      padding: 1.6rem 1.4rem 3.5rem;
      display: flex; flex-direction: column; align-items: center;
      gap: 1.8rem; max-width: 480px; margin: 0 auto;
    }

    /* Tagline — mirrors his "3D ARTIST // ANIMATOR" hero text */
    .tagline { width: 100%; text-align: left; }
    .tagline-label {
      font-size: 0.6rem; text-transform: uppercase;
      letter-spacing: 0.22em; color: var(--muted); margin-bottom: 0.4rem;
    }
    .tagline h1 {
      font-family: 'Bebas Neue', sans-serif;
      font-size: clamp(1.8rem, 7vw, 2.6rem);
      letter-spacing: 0.1em; line-height: 1.05; color: var(--text);
    }

    .rule { width: 100%; height: 1px; background: var(--rule); }

    /* ── QR ── */
    .qr-block {
      display: flex; flex-direction: column;
      align-items: center; gap: 1rem; width: 100%;
    }
    /* Sharp corners — Paul's site has zero border-radius on everything */
    .qr-frame {
      background: #fff; padding: 12px;
      display: inline-flex; line-height: 0;
    }
    .qr-frame img {
      display: block; width: 200px; height: 200px;
      image-rendering: pixelated; image-rendering: crisp-edges;
    }
    .qr-url {
      font-size: 0.65rem; text-transform: uppercase;
      letter-spacing: 0.2em; color: var(--muted);
    }

    /* ── Skills chips — echoes his "Core Skills" section ── */
    .skills { display: flex; flex-wrap: wrap; gap: 0.4rem; width: 100%; }
    .skill {
      font-size: 0.58rem; text-transform: uppercase;
      letter-spacing: 0.14em; font-weight: 500;
      color: var(--muted); border: 1px solid var(--rule);
      padding: 0.28rem 0.6rem;
    }

    /* ── Actions ── */
    .actions { display: flex; flex-direction: column; gap: 0.5rem; width: 100%; }
    .btn {
      display: flex; align-items: center; justify-content: center;
      gap: 0.5rem; padding: 0.85rem 1.2rem;
      font-family: 'Barlow', sans-serif; font-weight: 500;
      font-size: 0.72rem; letter-spacing: 0.16em; text-transform: uppercase;
      cursor: pointer; text-decoration: none;
      transition: opacity 0.15s; -webkit-tap-highlight-color: transparent;
      border: none;
      /* No border-radius — Paul's site is all sharp */
    }
    .btn:active { opacity: 0.7; }
    .btn svg { width: 14px; height: 14px; flex-shrink: 0; }
    .btn-primary { background: #fff; color: #000; }
    .btn-ghost   { background: transparent; color: var(--muted); border: 1px solid var(--rule); }

    .footer {
      text-align: center; font-size: 0.58rem;
      letter-spacing: 0.14em; text-transform: uppercase;
      color: rgba(255,255,255,0.18);
    }

    @media print {
      .actions, .tagline-label, .footer { display: none !important; }
    }

    @keyframes fade-in {
      from { opacity: 0; transform: translateY(8px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    .body > * { animation: fade-in 0.5s cubic-bezier(0.16,1,0.3,1) both; }
    .body > *:nth-child(1) { animation-delay: 0.05s; }
    .body > *:nth-child(2) { animation-delay: 0.10s; }
    .body > *:nth-child(3) { animation-delay: 0.15s; }
    .body > *:nth-child(4) { animation-delay: 0.20s; }
    .body > *:nth-child(5) { animation-delay: 0.25s; }
    .body > *:nth-child(6) { animation-delay: 0.30s; }
  </style>
</head>
<body>

  <!-- Full-bleed hero pulled from Paul's Wix CDN -->
  <div class="hero">
    <img
      src="https://static.wixstatic.com/media/109794_80890666fe7c4fa288683b0ad6e0c86bf000.jpg/v1/fill/w_1200,h_675,fp_0.50_0.50,q_85,usm_0.66_1.00_0.01,enc_auto/109794_80890666fe7c4fa288683b0ad6e0c86bf000.jpg"
      alt="Paul Lovejoy showreel" loading="eager"
    >
    <div class="hero-name">Paul Lovejoy</div>
  </div>

  <div class="body">

    <div class="tagline">
      <p class="tagline-label">Portfolio</p>
      <h1>3D Artist&nbsp;//&nbsp;Animator</h1>
    </div>

    <div class="rule"></div>

    <div class="qr-block">
      <div class="qr-frame">
        <img src="<?= htmlspecialchars($qrSrc) ?>" alt="QR — paullovejoycreative.com" id="qr-img">
      </div>
      <span class="qr-url">paullovejoycreative.com</span>
    </div>

    <div class="rule"></div>

    <div class="skills">
      <span class="skill">3D Animation</span>
      <span class="skill">Modeling</span>
      <span class="skill">Texturing</span>
      <span class="skill">Autodesk Maya</span>
      <span class="skill">Unreal Engine</span>
      <span class="skill">Blender</span>
      <span class="skill">Substance Painter</span>
      <span class="skill">UX / UI</span>
    </div>

    <div class="actions">
      <a href="https://www.paullovejoycreative.com" target="_blank" class="btn btn-primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
          <polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/>
        </svg>
        Visit Portfolio
      </a>
      <a href="<?= htmlspecialchars($qrSrcHD) ?>" target="_blank" download="paul-lovejoy-qr.png" class="btn btn-ghost">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
          <polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
        </svg>
        Save QR Code
      </a>
      <button class="btn btn-ghost" onclick="window.print()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="6 9 6 2 18 2 18 9"/>
          <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
          <rect x="6" y="14" width="12" height="8"/>
        </svg>
        Print
      </button>
    </div>

    <p class="footer">Samford University &nbsp;·&nbsp; Class of 2027</p>

  </div>

</body>
</html>
