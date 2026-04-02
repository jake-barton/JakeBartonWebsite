<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Art & Design - Jake Barton</title>
    <link rel="icon" type="image/svg+xml" href="../../assets/images/favicon.svg?v=20260325">
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
                <p class="eyebrow hero-eyebrow">Portfolio → Art &amp; Design</p>
                <h1 class="reveal" style="font-family:var(--font-display);font-size:clamp(2.5rem,6vw,4.5rem);font-weight:700;letter-spacing:-0.02em;line-height:1.1;color:var(--text);margin-bottom:1rem">Art &amp; Design</h1>
                <div class="divider reveal" style="margin:1.5rem auto;max-width:80px"></div>
                <p class="reveal" style="color: var(--text-muted); font-size: 1.1rem; margin-top: 0.5rem; line-height:1.75;transition-delay:0.12s">
                    Professional graphics, fine art, and custom apparel designs
                </p>
            </div>
        </section>

        <!-- Category Cards -->
        <section class="section-sm">
            <div style="max-width: 1100px; margin: 0 auto; padding: 0 var(--container-pad);">
                <div class="section-header reveal" style="margin-bottom:2rem">
                    <span class="eyebrow">Categories</span>
                    <h2>Art &amp; Design Work</h2>
                </div>
                <div class="grid-2 stagger-children">

                    <!-- Venice — Juried Art Show -->
                    <div class="work-card tilt-card reveal" style="grid-column:1/-1;cursor:zoom-in;" onclick="document.getElementById('venice-lightbox').classList.add('is-open')">
                        <div class="work-card-img" style="max-height:420px;overflow:hidden;position:relative;">
                            <img src="../../assets/images/venice-art.jpg" alt="Venice" style="width:100%;height:100%;object-fit:cover;object-position:center top;transition:transform 0.5s cubic-bezier(0.16,1,0.3,1);">
                            <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;opacity:0;transition:opacity 0.2s;background:rgba(0,0,0,0.35);" class="venice-hover-hint">
                                <span style="font-size:0.75rem;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:#fff;border:1px solid rgba(255,255,255,0.6);padding:0.45rem 1rem;border-radius:99px;backdrop-filter:blur(4px);">View Full Artwork</span>
                            </div>
                        </div>
                        <div class="work-card-info">
                            <p class="eyebrow">Digital Fine Art · Juried Exhibition</p>
                            <h3>Venice</h3>
                            <p style="color: var(--text-muted); font-size: 0.95rem; margin: 8px 0 12px;">
                                Original digital artwork accepted into the <strong style="color:var(--text)">Samford University Juried Art Show 2025</strong>. A stylised depiction of Venice capturing the interplay of light on water, architecture, and atmosphere.
                            </p>
                            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 20px; font-style: italic;">
                                • Digital painting / illustration<br>
                                • Accepted into juried exhibition<br>
                                • Samford University, 2025
                            </p>
                            <span class="btn-secondary" style="font-size:0.85rem;pointer-events:none;">View Full Artwork ↗</span>
                        </div>
                    </div>

                    <!-- Venice lightbox -->
                    <div id="venice-lightbox" style="display:none;position:fixed;inset:0;z-index:99999;background:rgba(0,0,0,0.95);align-items:center;justify-content:center;padding:1.5rem;cursor:zoom-out;" onclick="this.classList.remove('is-open')">
                        <button onclick="event.stopPropagation();document.getElementById('venice-lightbox').classList.remove('is-open')" style="position:absolute;top:1.25rem;right:1.5rem;background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.2);color:#fff;width:40px;height:40px;border-radius:50%;font-size:1.1rem;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:background 0.2s;" aria-label="Close">✕</button>
                        <img src="../../assets/images/venice-art.jpg" alt="Venice — Full Artwork"
                             style="max-width:100%;max-height:100%;object-fit:contain;border-radius:4px;box-shadow:0 24px 80px rgba(0,0,0,0.8);transform:scale(0.96);transition:transform 0.35s cubic-bezier(0.16,1,0.3,1);"
                             id="venice-lightbox-img">
                        <div style="position:absolute;bottom:1.5rem;left:50%;transform:translateX(-50%);text-align:center;pointer-events:none;">
                            <p style="font-size:0.75rem;letter-spacing:0.14em;text-transform:uppercase;color:rgba(255,255,255,0.4);">Venice · Samford University Juried Art Show 2025 · Click anywhere to close</p>
                        </div>
                    </div>

                    <style>
                      #venice-lightbox.is-open { display:flex !important; }
                      #venice-lightbox.is-open #venice-lightbox-img { transform:scale(1); }
                      .work-card:hover .venice-hover-hint { opacity:1 !important; }
                    </style>

                    <!-- Mediterranean Environment — Coming Soon -->
                    <div class="work-card reveal" style="grid-column:1/-1;display:grid;grid-template-columns:1fr 1fr;overflow:hidden;gap:0;position:relative;border:1px solid rgba(255,255,255,0.12);">
                        <div style="position:relative;overflow:hidden;min-height:280px;">
                            <video autoplay muted loop playsinline style="width:100%;height:100%;object-fit:cover;display:block;opacity:0.7;">
                                <source src="../../assets/images/environment-scene.mp4" type="video/mp4">
                            </video>
                            <img src="../../assets/images/venice-art.jpg" alt="Venice Art" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:0;transition:opacity 0.4s">
                        </div>
                        <div class="work-card-info" style="background:#0d0d0d;">
                            <p class="eyebrow">Unreal Engine 5 · 3D Environment</p>
                            <h3>Mediterranean Environment</h3>
                            <p style="color: var(--text-muted); font-size: 0.95rem; margin: 8px 0 12px;">
                                Photorealistic coastal scene built in UE5 — Lumen global illumination, Nanite geometry, custom atmospheric effects, and modular architecture.
                            </p>
                            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 20px; font-style: italic;">
                                • Lumen GI &amp; Reflections<br>
                                • Nanite Meshes<br>
                                • Atmospheric FX &amp; Water
                            </p>
                            <span class="btn-secondary" style="opacity:0.55;cursor:default;font-size:0.85rem">Full Case Study Coming Soon</span>
                        </div>
                    </div>

                    <!-- Professional Graphics -->
                    <div class="work-card tilt-card reveal" style="cursor: pointer;" onclick="window.location.href='../professional-works/';">
                        <div class="work-card-img">
                            <img src="../professional-works/33-miles-graphics/images/full/33-miles-01-grain-regular.png" alt="Professional Graphics" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <div class="work-card-info">
                            <p class="eyebrow">Client Work · Adobe Illustrator</p>
                            <h3>Professional Graphics</h3>
                            <p style="color: var(--text-muted); font-size: 0.95rem; margin: 8px 0 12px;">
                                Professional graphic design projects for bands, businesses, and organisations.
                            </p>
                            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 20px; font-style: italic;">
                                • 33Miles Band Graphics<br>
                                • College Guys Pressure Washing
                            </p>
                            <a href="../professional-works/" class="btn-primary" onclick="event.stopPropagation();">View Work →</a>
                        </div>
                    </div>

                    <!-- T-Shirt Designs -->
                    <div class="work-card tilt-card reveal" style="cursor: pointer;" onclick="window.location.href='../tshirt-designs/';">
                        <div class="work-card-img" style="display: flex; align-items: center; justify-content: center; background: #111;">
                            <img src="../tshirt-designs/images/full/Barn Bash 2024.svg" alt="T-Shirt Designs" style="max-width: 80%; max-height: 80%; object-fit: contain;">
                        </div>
                        <div class="work-card-info">
                            <p class="eyebrow">15+ Designs · T-Shirt Chair</p>
                            <h3>T-Shirt Designs</h3>
                            <p style="color: var(--text-muted); font-size: 0.95rem; margin: 8px 0 12px;">
                                Custom apparel designs as T-Shirt Chair for Pi Kappa Phi Alpha Eta chapter.
                            </p>
                            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 20px; font-style: italic;">
                                • Fraternity Events<br>
                                • Recruitment Campaigns<br>
                                • Philanthropy Initiatives
                            </p>
                            <a href="../tshirt-designs/" class="btn-primary" onclick="event.stopPropagation();">View Gallery →</a>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- Stats -->
        <section class="section-sm" style="text-align: center;">
            <div style="max-width: 700px; margin: 0 auto; padding: 0 var(--container-pad);">
                <div class="hero-stats reveal" style="display:inline-flex;margin-top:0">
                    <div class="hero-stat">
                        <span class="hero-stat-num" data-count="15" data-suffix="+">15+</span>
                        <span class="hero-stat-label">T-Shirt Designs</span>
                    </div>
                    <div class="hero-stat-divider"></div>
                    <div class="hero-stat">
                        <span class="hero-stat-num" data-count="2" data-suffix="">2</span>
                        <span class="hero-stat-label">Professional Clients</span>
                    </div>
                    <div class="hero-stat-divider"></div>
                    <div class="hero-stat">
                        <span class="hero-stat-num" data-count="2" data-suffix="">2</span>
                        <span class="hero-stat-label">Years as T-Shirt Chair</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="section-sm" style="text-align: center;">
            <p class="reveal" style="color: var(--text-muted); margin-bottom: 24px;">Check out my programming work</p>
            <a href="../game-programming/" class="btn-primary magnetic reveal" style="margin-right: 16px;">View Game Projects →</a>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="../../assets/js/staggered-menu.js"></script>
    <script>
      (function(){var b=document.getElementById('scroll-progress');if(!b)return;window.addEventListener('scroll',function(){var s=window.scrollY,t=document.documentElement.scrollHeight-window.innerHeight;b.style.width=(t>0?(s/t)*100:0)+'%';},{passive:true});})();
      (function(){var g=document.getElementById('cursor-glow');if(!g||window.matchMedia('(pointer:coarse)').matches)return;var cx=0,cy=0,tx=0,ty=0;document.addEventListener('mousemove',function(e){tx=e.clientX;ty=e.clientY;g.style.opacity='1';});document.addEventListener('mouseleave',function(){g.style.opacity='0';});function lerp(a,b,t){return a+(b-a)*t;}(function loop(){cx=lerp(cx,tx,0.07);cy=lerp(cy,ty,0.07);g.style.left=cx+'px';g.style.top=cy+'px';requestAnimationFrame(loop);})();})();
    </script>

</body>
</html>
