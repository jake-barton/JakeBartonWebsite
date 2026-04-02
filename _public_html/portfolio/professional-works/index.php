<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professional Works - Jake Barton</title>
    <link rel="icon" type="image/png" href="../../assets/images/jb-logo.png">
    <link rel="stylesheet" href="../../assets/css/base.css">
    <link rel="stylesheet" href="../../assets/css/animations.css">
    <link rel="stylesheet" href="../../assets/css/components.css">
</head>
<body>
  <!-- Scroll progress line -->
  <div id="scroll-progress" style="position:fixed;top:0;left:0;height:2px;width:0%;background:var(--accent);z-index:100001;transition:width 0.1s linear;pointer-events:none"></div>

  <!-- Ambient cursor glow -->
  <div id="cursor-glow" style="position:fixed;top:0;left:0;width:420px;height:420px;border-radius:50%;background:radial-gradient(circle,rgba(255,255,255,0.05) 0%,transparent 70%);pointer-events:none;z-index:0;transform:translate(-50%,-50%);transition:opacity 0.3s ease;opacity:0"></div>


    <header class="site-nav" id="site-nav">
        <a href="../../index.php" class="nav-logo"><img src="../../assets/images/jb-logo.png" alt="JB" class="nav-logo-img"><span class="nav-logo-text">JB</span></a>
        <nav class="nav-links">
            <a href="../../index.php#about">About</a>
            <a href="../../index.php#skills">Skills</a>
            <a href="../">Portfolio</a>
            <a href="../../assets/Jake%20Barton%20-%20Resume.pdf" class="btn btn-secondary btn-sm" download>Resume</a>
            <a href="../../index.php#contact">Contact</a>
        </nav>
    </header>


    <main class="site-content">

        <section class="section" style="padding-top: 140px; padding-bottom: 60px; text-align: center;">
            <div style="max-width: 800px; margin: 0 auto; padding: 0 var(--container-pad);">
                <p class="eyebrow hero-eyebrow">Portfolio → Professional Works</p>
                <h1 class="reveal" style="font-family:var(--font-display);font-size:clamp(2.5rem,6vw,4.5rem);font-weight:700;letter-spacing:-0.02em;line-height:1.1;color:var(--text);margin-bottom:1rem">Professional Works</h1>
                <div class="divider reveal" style="margin:1.5rem auto;max-width:80px"></div>
                <p class="reveal" style="color: var(--text-muted); font-size: 1.2rem; margin-top: 1.5rem;transition-delay:0.12s">
                    Client projects &amp; professional graphic design work
                </p>
            </div>
        </section>

        <section class="section-sm">
            <div style="max-width: 1100px; margin: 0 auto; padding: 0 var(--container-pad);">
                <div class="section-header reveal" style="margin-bottom:2rem">
                    <span class="eyebrow">Projects</span>
                    <h2>Client Work</h2>
                </div>
                <div class="grid-2 stagger-children">

                    <!-- 33Miles Graphics -->
                    <div class="work-card tilt-card reveal" style="cursor: pointer;" onclick="window.location.href='33-miles-graphics/';">
                        <div class="work-card-img">
                            <img src="33-miles-graphics/images/full/33-miles-01-grain-regular.png" alt="33Miles Graphics" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <div class="work-card-info">
                            <p class="eyebrow">Client Work</p>
                            <h3>33MILES GRAPHICS</h3>
                            <p style="color: var(--text-muted); font-size: 0.95rem; margin: 8px 0 20px;">
                                Social media &amp; event graphics for Christian band 33Miles — 8 designs
                            </p>
                            <a href="33-miles-graphics/" class="btn-primary" onclick="event.stopPropagation();">View Work →</a>
                        </div>
                    </div>

                    <!-- College Guys Pressure Washing -->
                    <div class="work-card tilt-card reveal" style="cursor: pointer;" onclick="window.location.href='College%20Guys%20Pressure%20Washing/';">
                        <div class="work-card-img">
                            <img src="College Guys Pressure Washing/College Guys Pressure Washing Banner.svg" alt="College Guys Pressure Washing" style="width: 100%; height: 100%; object-fit: contain; padding: 20px; background: #111;">
                        </div>
                        <div class="work-card-info">
                            <p class="eyebrow">Client Work</p>
                            <h3>COLLEGE GUYS PRESSURE WASHING</h3>
                            <p style="color: var(--text-muted); font-size: 0.95rem; margin: 8px 0 20px;">
                                Branding &amp; marketing graphics for local pressure washing business
                            </p>
                            <a href="College%20Guys%20Pressure%20Washing/" class="btn-primary" onclick="event.stopPropagation();">View Work →</a>
                        </div>
                    </div>

                    <!-- Coming Soon -->
                    <div class="work-card reveal" style="opacity: 0.45;">
                        <div class="work-card-img" style="display: flex; align-items: center; justify-content: center; font-size: 5rem; color: rgba(255,255,255,0.15);">+</div>
                        <div class="work-card-info">
                            <p class="eyebrow">Coming Soon</p>
                            <h3>MORE PROJECTS</h3>
                            <p style="color: var(--text-muted); font-size: 0.95rem; margin: 8px 0 20px;">
                                Additional professional client work and freelance projects
                            </p>
                            <span class="btn-secondary" style="opacity: 0.4; cursor: not-allowed;">Coming Soon</span>
                        </div>
                    </div>

                </div>

                <div class="reveal" style="text-align: center; margin-top: 60px; color: var(--text-muted);">
                    Professional client projects showcasing graphic design, branding, and marketing materials.
                </div>
            </div>
        </section>

        <section class="section-sm" style="text-align: center;">
            <a href="../" class="btn-secondary magnetic reveal">← Back to Portfolio</a>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="../../assets/js/staggered-menu.js"></script>
    <script>
      (function(){var b=document.getElementById('scroll-progress');if(!b)return;window.addEventListener('scroll',function(){var s=window.scrollY,t=document.documentElement.scrollHeight-window.innerHeight;b.style.width=(t>0?(s/t)*100:0)+'%';},{passive:true});})();
      (function(){var g=document.getElementById('cursor-glow');if(!g||window.matchMedia('(pointer:coarse)').matches)return;var cx=0,cy=0,tx=0,ty=0;document.addEventListener('mousemove',function(e){tx=e.clientX;ty=e.clientY;g.style.opacity='1';});document.addEventListener('mouseleave',function(){g.style.opacity='0';});function lerp(a,b,t){return a+(b-a)*t;}(function loop(){cx=lerp(cx,tx,0.07);cy=lerp(cy,ty,0.07);g.style.left=cx+'px';g.style.top=cy+'px';requestAnimationFrame(loop);})();})();
    </script>

</body>
</html>
