<?php
/**
 * qr-henry.php — QR card for Henry Lammons → henrylammons.com
 * Win95 window aesthetic matching his actual site.
 * QR generated server-side via qrserver.com and embedded as base64.
 */
$TARGET_URL = 'https://www.henrylammons.com';
$encodedURL = urlencode($TARGET_URL);
$ctx    = stream_context_create(['http' => ['timeout' => 6]]);
$qrPng  = @file_get_contents(
    'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . $encodedURL . '&ecc=H&margin=4',
    false, $ctx
);
$qrSrc   = $qrPng ? 'data:image/png;base64,' . base64_encode($qrPng)
                  : 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . $encodedURL . '&ecc=H&margin=4';
$qrSrcHD = 'https://api.qrserver.com/v1/create-qr-code/?size=600x600&data=' . $encodedURL . '&ecc=H&margin=4';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Henry Lammons — Portfolio</title>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --desktop:   #1a2744;
      --window-bg: #2c3e6b;
      --chrome:    #3a4f7a;
      --titlebar:  #1a3a6b;
      --border-hi: #6b88c4;
      --border-sh: #0d1d3a;
      --text:      #e8edf8;
      --muted:     rgba(232,237,248,0.55);
    }

    html, body {
      background: var(--desktop);
      color: var(--text);
      font-family: 'Segoe UI', Tahoma, Geneva, sans-serif;
      -webkit-font-smoothing: antialiased;
      min-height: 100dvh;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 1.4rem 1rem 3rem;
      gap: 1rem;
    }

    /* Desktop label */
    .desktop-label {
      display: flex; flex-direction: column; align-items: center; gap: 0.3rem;
      animation: fade-up 0.4s cubic-bezier(0.16,1,0.3,1) both;
    }
    .desktop-icon { font-size: 2rem; line-height: 1; }
    .desktop-label p {
      font-size: 0.6rem; text-transform: uppercase;
      letter-spacing: 0.12em; color: var(--muted);
    }

    /* Win95 window */
    .win-window {
      width: 100%; max-width: 380px;
      background: var(--window-bg);
      border: 2px solid;
      border-color: var(--border-hi) var(--border-sh) var(--border-sh) var(--border-hi);
      box-shadow: 2px 2px 0 var(--border-sh);
      animation: fade-up 0.45s 0.05s cubic-bezier(0.16,1,0.3,1) both;
    }

    /* Title bar */
    .win-titlebar {
      background: var(--titlebar);
      padding: 0.28rem 0.4rem;
      display: flex; align-items: center; justify-content: space-between;
      user-select: none;
    }
    .win-titlebar-left {
      display: flex; align-items: center; gap: 0.35rem;
      font-size: 0.7rem; font-weight: 700; color: #fff; letter-spacing: 0.02em;
    }
    .win-icon {
      background: #4a6fad; color: #fff;
      font-size: 0.55rem; font-weight: 900;
      width: 16px; height: 16px;
      display: flex; align-items: center; justify-content: center;
      border: 1px solid rgba(255,255,255,0.3); flex-shrink: 0;
    }
    .win-titlebar-btns { display: flex; gap: 0.2rem; }
    .win-btn {
      width: 16px; height: 14px;
      background: var(--chrome);
      border: 1px solid;
      border-color: var(--border-hi) var(--border-sh) var(--border-sh) var(--border-hi);
      display: flex; align-items: center; justify-content: center;
      font-size: 0.5rem; color: var(--text); cursor: pointer;
    }

    /* Menu bar */
    .win-menubar {
      background: var(--window-bg);
      border-bottom: 1px solid var(--border-sh);
      padding: 0.15rem 0.5rem;
      display: flex; gap: 1rem;
      font-size: 0.68rem; color: var(--text);
    }
    .win-menubar span { cursor: pointer; padding: 0.1rem 0.3rem; }
    .win-menubar span:hover { background: var(--titlebar); color: #fff; }

    /* Content */
    .win-content {
      padding: 1rem;
      display: flex; flex-direction: column; align-items: center; gap: 0.9rem;
    }

    /* Profile photo */
    .henry-pfp {
      width: 80px; height: 80px; object-fit: cover; display: block;
      border: 2px solid;
      border-color: var(--border-hi) var(--border-sh) var(--border-sh) var(--border-hi);
    }

    /* Identity */
    .identity { text-align: center; }
    .identity-name  { font-size: 1rem; font-weight: 700; letter-spacing: 0.03em; }
    .identity-role  { font-size: 0.65rem; color: var(--muted); margin-top: 0.2rem; }

    /* Win95 sunken rule */
    .win-rule {
      width: 100%; height: 2px;
      border-top: 1px solid var(--border-sh);
      border-bottom: 1px solid var(--border-hi);
    }

    /* QR frame — sunken inset */
    .qr-frame {
      background: #fff; padding: 10px;
      border: 2px solid;
      border-color: var(--border-sh) var(--border-hi) var(--border-hi) var(--border-sh);
      display: inline-flex; line-height: 0;
    }
    .qr-frame img {
      width: 190px; height: 190px; display: block;
      image-rendering: pixelated; image-rendering: crisp-edges;
    }

    .url-chip {
      font-size: 0.62rem; letter-spacing: 0.1em;
      color: var(--muted); text-align: center;
    }

    /* Status bar */
    .win-statusbar {
      background: var(--window-bg);
      border-top: 1px solid var(--border-sh);
      padding: 0.2rem 0.5rem;
      display: flex; justify-content: space-between;
      font-size: 0.6rem; color: var(--muted);
    }
    .status-dot {
      display: inline-block; width: 6px; height: 6px;
      background: #4caf50; border-radius: 50%; margin-right: 0.3rem;
      vertical-align: middle;
    }

    /* Actions */
    .actions {
      width: 100%; max-width: 380px;
      display: flex; flex-direction: column; gap: 0.4rem;
      animation: fade-up 0.45s 0.1s cubic-bezier(0.16,1,0.3,1) both;
    }
    .btn {
      display: flex; align-items: center; justify-content: center;
      gap: 0.45rem; padding: 0.65rem 1rem;
      font-family: 'Segoe UI', Tahoma, sans-serif;
      font-size: 0.7rem; font-weight: 700;
      letter-spacing: 0.08em; text-transform: uppercase;
      cursor: pointer; text-decoration: none;
      border: 2px solid; border-radius: 0;
      transition: opacity 0.15s; -webkit-tap-highlight-color: transparent;
    }
    .btn:active { opacity: 0.75; }
    .btn svg { width: 13px; height: 13px; flex-shrink: 0; }
    .btn-primary {
      background: var(--titlebar); color: #fff;
      border-color: var(--border-hi) var(--border-sh) var(--border-sh) var(--border-hi);
    }
    .btn-ghost {
      background: var(--chrome); color: var(--muted);
      border-color: var(--border-hi) var(--border-sh) var(--border-sh) var(--border-hi);
    }

    .footer {
      font-size: 0.55rem; letter-spacing: 0.12em; text-transform: uppercase;
      color: rgba(232,237,248,0.2); text-align: center;
      animation: fade-up 0.4s 0.15s cubic-bezier(0.16,1,0.3,1) both;
    }

    @keyframes fade-up {
      from { opacity: 0; transform: translateY(10px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    @media print {
      body { background: #fff !important; }
      .desktop-label, .actions, .footer { display: none !important; }
      .win-window { border-color: #ccc !important; box-shadow: none !important; background: #fff !important; }
      .win-titlebar { background: #ddd !important; color: #000 !important; }
      .win-menubar, .win-titlebar-btns, .win-statusbar { display: none !important; }
    }
  </style>
</head>
<body>

  <div class="desktop-label">
    <div class="desktop-icon">&#128421;</div>
    <p>Henry Lammons &mdash; Portfolio.exe</p>
  </div>

  <div class="win-window">

    <div class="win-titlebar">
      <div class="win-titlebar-left">
        <div class="win-icon">HL</div>
        Henry Lammons &mdash; Portfolio
      </div>
      <div class="win-titlebar-btns">
        <div class="win-btn">_</div>
        <div class="win-btn">&#9633;</div>
        <div class="win-btn">&times;</div>
      </div>
    </div>

    <div class="win-menubar">
      <span>File</span>
      <span>View</span>
      <span>Contact</span>
      <span>Help</span>
    </div>

    <div class="win-content">

      <img class="henry-pfp"
           src="https://www.henrylammons.com/media/HenryPfp.jpg"
           alt="Henry Lammons" loading="eager">

      <div class="identity">
        <div class="identity-name">Henry Lammons</div>
        <div class="identity-role">Animation &middot; Video Editing &middot; Game Design</div>
      </div>

      <div class="win-rule"></div>

      <div class="qr-frame">
        <img src="<?= htmlspecialchars($qrSrc) ?>"
             alt="QR code — henrylammons.com" id="qr-img">
      </div>

      <div class="url-chip">henrylammons.com</div>

    </div>

    <div class="win-statusbar">
      <span><span class="status-dot"></span>Ready</span>
      <span>henrylammons.com</span>
    </div>

  </div>

  <div class="actions">
    <a href="https://www.henrylammons.com" target="_blank" class="btn btn-primary">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
        <polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/>
      </svg>
      Visit Portfolio
    </a>
    <a href="<?= htmlspecialchars($qrSrcHD) ?>" target="_blank" download="henry-lammons-qr.png" class="btn btn-ghost">
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

  <p class="footer">Samford University &nbsp;&middot;&nbsp; Class of 2027</p>

</body>
</html>
