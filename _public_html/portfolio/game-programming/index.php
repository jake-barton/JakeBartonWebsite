<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Programming - Jake Barton</title>
    <link rel="icon" type="image/svg+xml" href="../../assets/images/favicon.svg?v=20260325">
    <link rel="stylesheet" href="../../assets/css/base.css">
    <link rel="stylesheet" href="../../assets/css/animations.css">
    <link rel="stylesheet" href="../../assets/css/components.css">
</head>
<body>

  <!-- Scroll progress line -->
  <div id="scroll-progress" style="position:fixed;top:0;left:0;height:2px;width:0%;background:var(--accent);z-index:100001;transition:width 0.1s linear;pointer-events:none"></div>

  <!-- Ambient cursor glow -->
  <div id="cursor-glow" style="position:fixed;top:0;left:0;width:420px;height:420px;border-radius:50%;background:radial-gradient(circle,rgba(255,255,255,0.05) 0%,transparent 70%);pointer-events:none;z-index:0;transform:translate(-50%,-50%);transition:opacity 0.3s ease;opacity:0"></div>  </div>

    <header class="site-nav" id="site-nav">
        <a href="../../index.php" class="nav-logo">JB</a>
        <nav class="nav-links">
            <a href="../../index.php#about">About</a>
            <a href="../../index.php#skills">Skills</a>
            <a href="../">Portfolio</a>
            <a href="../../assets/Jake%20Barton%20-%20Resume.pdf" class="btn btn-secondary btn-sm" download>Resume</a>
            <a href="../../index.php#contact">Contact</a>
        </nav>
        <button class="nav-toggle" aria-label="Open menu">
            <span></span><span></span><span></span>
        </button>
    </header>

    <div class="stagger-menu-overlay" id="stagger-menu">
        <nav>
            <a href="../../index.php">Home</a>
            <a href="../">Portfolio</a>
            <a href="../../index.php#about">About</a>
            <a href="../../index.php#contact">Contact</a>
            <a href="../../assets/Jake%20Barton%20-%20Resume.pdf" download>Resume</a>
        </nav>
    </div>

    <main class="site-content">

        <section class="section" style="padding-top: 140px; padding-bottom: 60px; text-align: center;">
            <div style="max-width: 800px; margin: 0 auto; padding: 0 var(--container-pad);">
                <p class="eyebrow hero-eyebrow">Portfolio → Game Programming</p>
                <h1 class="reveal" style="font-family:var(--font-display);font-size:clamp(2.5rem,6vw,4.5rem);font-weight:700;letter-spacing:-0.02em;line-height:1.1;color:var(--text);margin-bottom:1rem">Game Programming</h1>
                <div class="divider reveal" style="margin:1.5rem auto;max-width:80px"></div>
                <p class="reveal" style="color: var(--text-muted); font-size: 1.1rem; margin-top: 0.5rem; line-height:1.75;transition-delay:0.12s">
                    Interactive game projects built with custom engines and Godot
                </p>
            </div>
        </section>

        <section class="section-sm">
            <div style="max-width: 1200px; margin: 0 auto; padding: 0 var(--container-pad);">
                <div class="section-header reveal" style="margin-bottom:2rem">
                    <span class="eyebrow">Projects</span>
                    <h2>Game Projects</h2>
                </div>
                <div class="grid-2 stagger-children">

                    <!-- Phase Runner -->
                    <div class="work-card tilt-card reveal">
                        <div class="work-card-img">
                            <img src="../../assets/images/phaserunnercover.png" alt="Phase Runner" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <div class="work-card-info">
                            <p class="eyebrow">Godot Engine · Platformer</p>
                            <h3>Phase Runner</h3>
                            <p style="color: var(--text-muted); font-size: 0.95rem; margin: 8px 0 20px;">
                                A fast-paced platformer built in Godot featuring custom physics, responsive controls, and challenging level design.
                            </p>
                            <a href="https://clervercarpet99.itch.io/phase-runner" target="_blank" class="btn-primary">Play on itch.io →</a>
                        </div>
                    </div>

                    <!-- DeskPet -->
                    <div class="work-card tilt-card reveal">
                        <div class="work-card-img" style="display: flex; align-items: center; justify-content: center; background: #111;">
                            <img src="DeskPet/AppIcon.png" alt="DeskPet" style="width: 180px; height: 180px; object-fit: contain; image-rendering: pixelated;">
                        </div>
                        <div class="work-card-info">
                            <p class="eyebrow">macOS App · Desktop Companion</p>
                            <h3>DeskPet</h3>
                            <p style="color: var(--text-muted); font-size: 0.95rem; margin: 8px 0 20px;">
                                A delightful desktop companion app for macOS. Download and install to bring a friendly pet to your screen!
                            </p>
                            <a href="DeskPet/DeskPet_Simple.dmg" download class="btn-primary">Download for Mac →</a>
                        </div>
                    </div>

                    <!-- Captain's Log -->
                    <div class="work-card tilt-card reveal">
                        <div class="work-card-img" style="display: flex; align-items: center; justify-content: center; background: #111;">
                            <img src="../captainslogtruetiles.png" alt="Captain's Log Sprites" style="max-width: 90%; max-height: 90%; object-fit: contain;">
                        </div>
                        <div class="work-card-info">
                            <p class="eyebrow">Pixel Art · Game Sprites</p>
                            <h3>Captain's Log</h3>
                            <p style="color: var(--text-muted); font-size: 0.95rem; margin: 8px 0 20px;">
                                Custom pixel art tileset and sprite collection for a nautical-themed adventure game with hand-crafted assets and cohesive visual style.
                            </p>
                            <span class="btn-secondary" style="opacity: 0.5; cursor: not-allowed;">View Project</span>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <section class="section-sm" style="text-align: center;">
            <p class="reveal" style="color: var(--text-muted); margin-bottom: 24px;">Want to see more?</p>
            <a href="../web-programming/" class="btn-primary magnetic reveal" style="margin-right: 16px;">Explore Web Programming →</a>
            <a href="../" class="btn-secondary reveal" style="transition-delay:0.08s">← Back to Portfolio</a>
        </section>

    </main>

    <footer class="site-footer">
        <div class="container">
            <div class="footer-inner">
                <span class="footer-copy">&copy; <?php echo date('Y'); ?> Jake Barton. All rights reserved.</span>
                <div class="footer-socials">
                    <a href="https://www.linkedin.com/in/jakebartoncreative" target="_blank" class="btn-icon" aria-label="LinkedIn">in</a>
                    <a href="https://instagram.com/jakebarton13" target="_blank" class="btn-icon" aria-label="Instagram">IG</a>
                </div>
            </div>
        </div>
    </footer>

    <canvas id="beams-canvas"></canvas>
    <script src="../../assets/js/beams-bg.js"></script>
    <script src="../../assets/js/cursor-ribbons.js"></script>
    <script src="../../assets/js/fuzzy-text.js"></script>
    <script src="../../assets/js/effects-stylekit.js"></script>
    <script>
      (function(){var b=document.getElementById('scroll-progress');if(!b)return;window.addEventListener('scroll',function(){var s=window.scrollY,t=document.documentElement.scrollHeight-window.innerHeight;b.style.width=(t>0?(s/t)*100:0)+'%';},{passive:true});})();
      (function(){var g=document.getElementById('cursor-glow');if(!g||window.matchMedia('(pointer:coarse)').matches)return;var cx=0,cy=0,tx=0,ty=0;document.addEventListener('mousemove',function(e){tx=e.clientX;ty=e.clientY;g.style.opacity='1';});document.addEventListener('mouseleave',function(){g.style.opacity='0';});function lerp(a,b,t){return a+(b-a)*t;}(function loop(){cx=lerp(cx,tx,0.07);cy=lerp(cy,ty,0.07);g.style.left=cx+'px';g.style.top=cy+'px';requestAnimationFrame(loop);})();})();
    </script>

</body>
</html>
