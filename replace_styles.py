#!/usr/bin/env python3

with open('_public_html/index.php', 'r') as f:
    content = f.read()

# Find style block boundaries
style_start = content.find('\n  <style>\n')
style_end = content.find('\n  </style>\n', style_start) + len('\n  </style>\n')

print(f"Style block: chars {style_start} to {style_end}")

new_style = """
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
    .hero-clock {
      font-family: var(--font-display);
      font-size: 0.78rem;
      font-weight: 700;
      letter-spacing: 0.12em;
      color: var(--text-faint);
      text-transform: uppercase;
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
      font-size: clamp(5.5rem, 18vw, 16rem);
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
      transition: background 0.25s;
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

    /* ── Environment reel ───────────────────────────────── */
    .env-reel {
      position: relative;
      width: 100%;
      height: clamp(220px, 40vw, 480px);
      overflow: hidden;
    }
    .env-reel-video {
      width: 100%; height: 100%;
      object-fit: cover;
      display: block;
    }
    .env-reel-overlay {
      position: absolute;
      inset: 0;
      background: linear-gradient(to top, rgba(0,0,0,0.6) 0%, transparent 60%);
      display: flex;
      align-items: flex-end;
      padding: 1.5rem 2rem;
    }
    .env-reel-label {
      font-size: 0.7rem;
      font-weight: 700;
      letter-spacing: 0.2em;
      text-transform: uppercase;
      color: rgba(255,255,255,0.5);
    }

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
"""

new_content = content[:style_start] + new_style + content[style_end:]

with open('_public_html/index.php', 'w') as f:
    f.write(new_content)

print(f"Done. File written with {len(new_content.splitlines())} lines.")
