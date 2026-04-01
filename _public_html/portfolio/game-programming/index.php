<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Programming — Jake Barton</title>
    <link rel="icon" type="image/svg+xml" href="../../assets/images/favicon.svg?v=20260325">
    <link rel="stylesheet" href="../../assets/css/base.css">
    <link rel="stylesheet" href="../../assets/css/animations.css">
    <link rel="stylesheet" href="../../assets/css/components.css">
    <style>
    .cs-container { max-width:900px;margin:0 auto;padding:0 var(--container-pad); }
    .cs-hero { padding:140px 0 72px; }
    .cs-hero h1 { font-family:var(--font-display);font-size:clamp(2.8rem,7vw,5rem);font-weight:800;letter-spacing:-0.03em;line-height:1.05;color:var(--text);margin-bottom:1.25rem; }
    .cs-hero p.lead { font-size:clamp(1rem,2vw,1.2rem);color:var(--text-muted);line-height:1.75;max-width:640px; }
    .case-study { padding:72px 0;border-bottom:1px solid rgba(255,255,255,0.06); }
    .case-study:last-child { border-bottom:none; }
    .cs-number { font-family:var(--font-display);font-size:0.7rem;font-weight:800;letter-spacing:0.18em;text-transform:uppercase;color:var(--accent);display:block;margin-bottom:0.6rem; }
    .case-study h2 { font-family:var(--font-display);font-size:clamp(2rem,5vw,3.2rem);font-weight:800;letter-spacing:-0.025em;line-height:1.1;color:var(--text);margin-bottom:1.5rem; }
    .cs-meta { display:flex;flex-wrap:wrap;gap:12px;margin-bottom:2rem; }
    .cs-tag { font-size:0.78rem;font-weight:600;letter-spacing:0.06em;text-transform:uppercase;padding:5px 14px;border-radius:100px;background:rgba(255,255,255,0.06);color:var(--text-muted);border:1px solid rgba(255,255,255,0.08); }
    .cs-tag.highlight { background:rgba(255,255,255,0.1);color:var(--text);border-color:rgba(255,255,255,0.18); }
    .cs-video { width:100%;border-radius:12px;overflow:hidden;margin:2rem 0;background:#111;aspect-ratio:16/9; }
    .cs-video video { width:100%;height:100%;object-fit:cover;display:block; }
    .cs-prose { color:var(--text-muted);font-size:1rem;line-height:1.8;margin-bottom:1.25rem; }
    .cs-prose strong { color:var(--text); }
    .cs-detail-grid { display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px;margin:2rem 0; }
    .cs-detail-card { background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);border-radius:10px;padding:20px 22px; }
    .cs-detail-card .label { font-size:0.7rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:var(--text-muted);margin-bottom:6px; }
    .cs-detail-card .value { font-size:0.95rem;font-weight:600;color:var(--text);line-height:1.5; }
    .cs-outcomes { list-style:none;padding:0;margin:1.5rem 0; }
    .cs-outcomes li { display:flex;gap:12px;align-items:flex-start;color:var(--text-muted);font-size:0.95rem;line-height:1.7;margin-bottom:0.6rem; }
    .cs-outcomes li::before { content:'→';color:var(--accent);flex-shrink:0;margin-top:2px; }
    .cs-outcomes li strong { color:var(--text); }
    .cs-cta-row { display:flex;flex-wrap:wrap;gap:12px;margin-top:2.5rem; }
    .cs-other-grid { display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:24px; }
    .cs-other-card { background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);border-radius:12px;padding:28px 26px;transition:border-color 0.2s,background 0.2s; }
    .cs-other-card:hover { border-color:rgba(255,255,255,0.18);background:rgba(255,255,255,0.05); }
    .cs-other-card h3 { font-family:var(--font-display);font-size:1.25rem;font-weight:800;color:var(--text);margin-bottom:0.5rem; }
    .cs-other-card p { font-size:0.88rem;color:var(--text-muted);line-height:1.65;margin-bottom:1.25rem; }
    .cs-divider { width:48px;height:2px;background:var(--accent);margin-bottom:2rem; }
    .cs-section { padding:80px 0; }
    @media(max-width:640px){ .cs-hero{padding:100px 0 48px;} .case-study{padding:48px 0;} }
    </style>
</head>
<body>

  <div id="scroll-progress" style="position:fixed;top:0;left:0;height:2px;width:0%;background:var(--accent);z-index:100001;transition:width 0.1s linear;pointer-events:none"></div>
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

        <!-- Hero -->
        <div class="cs-container">
            <section class="cs-hero">
                <p class="eyebrow reveal-up" style="margin-bottom:1rem">Portfolio → Game Programming</p>
                <h1 class="reveal-up" style="transition-delay:0.05s">Game<br>Programming</h1>
                <div class="cs-divider reveal-up" style="transition-delay:0.1s;margin-top:1.5rem;"></div>
                <p class="lead reveal-up" style="transition-delay:0.15s">
                    Gameplay systems, engine work, and shipped projects — built in Unreal Engine 5, Godot 4, and vanilla JavaScript. Currently <strong style="color:var(--text)">Lead Programmer at Samford Game Design Studio</strong> coordinating a 5-person team toward a full playable demo.
                </p>
            </section>
        </div>

        <!-- CASE STUDY 01 — Phase Runner -->
        <div class="cs-container">
            <article class="case-study reveal-up">
                <span class="cs-number">Case Study 01</span>
                <h2>Phase Runner</h2>
                <div class="cs-meta">
                    <span class="cs-tag highlight">Solo Developer</span>
                    <span class="cs-tag">Godot 4 · GDScript</span>
                    <span class="cs-tag">2D Shooter / Platformer</span>
                    <span class="cs-tag">Live on itch.io</span>
                </div>
                <div class="cs-video">
                    <video autoplay muted loop playsinline>
                        <source src="../../assets/images/phase-runner-screen.mp4" type="video/mp4">
                    </video>
                </div>
                <div class="cs-detail-grid">
                    <div class="cs-detail-card"><div class="label">Role</div><div class="value">Solo Developer — design, code &amp; art</div></div>
                    <div class="cs-detail-card"><div class="label">Stack</div><div class="value">Godot 4 · GDScript · Aseprite</div></div>
                    <div class="cs-detail-card"><div class="label">Scope</div><div class="value">10+ weapon types · procedural level chunks · full UI system</div></div>
                    <div class="cs-detail-card"><div class="label">Status</div><div class="value">Shipped — playable demo live on itch.io</div></div>
                </div>
                <p class="cs-prose">Phase Runner is a fast-paced 2D side-scrolling shooter where players dash through procedurally assembled level sections, switching between <strong>10+ distinct weapons</strong> on the fly. The entire game — physics, weapons, level generation, and UI — was designed and built solo from scratch.</p>
                <p class="cs-prose"><strong>The hardest technical problem</strong> was the custom physics controller. Godot's built-in CharacterBody2D handles basic collision well, but the game required wall-jumping, coyote-time, variable jump height, and a zero-gravity dash that bypasses normal gravity for a precise window. I rebuilt the movement stack from first principles using move_and_slide with manual velocity management, giving full control over feel and making tuning fast.</p>
                <p class="cs-prose">Level generation uses a chunk-based system — prefab rooms are validated against a ruleset (no two spike corridors back to back, guaranteed weapon pickup cadence) and stitched together at runtime. This kept level design authored rather than fully random, so difficulty can be tuned without sacrificing the handcrafted feel.</p>
                <ul class="cs-outcomes">
                    <li><strong>Shipped and publicly playable</strong> — live on itch.io</li>
                    <li><strong>Custom physics controller</strong> handling wall-jump, coyote-time, and invincibility dash without plugins</li>
                    <li><strong>Chunk-based procedural generation</strong> keeps levels varied while preserving handcrafted difficulty curves</li>
                    <li><strong>Full feature parity</strong> with original design doc — weapons, progression, level gen, leaderboard</li>
                </ul>
                <div class="cs-cta-row">
                    <a href="https://clervercarpet99.itch.io/phase-runner" target="_blank" class="btn-primary magnetic">Play on itch.io →</a>
                    <a href="../../index.php#work" class="btn-secondary">← Back to Work</a>
                </div>
            </article>
        </div>

        <!-- CASE STUDY 02 — VR Rhythm Game -->
        <div class="cs-container">
            <article class="case-study reveal-up">
                <span class="cs-number">Case Study 02</span>
                <h2>VR Rhythm Game</h2>
                <div class="cs-meta">
                    <span class="cs-tag highlight">Lead Programmer · 5-Person Team</span>
                    <span class="cs-tag">Unreal Engine 5 · C++</span>
                    <span class="cs-tag">VR · Meta Quest</span>
                    <span class="cs-tag">Samford Game Design Studio</span>
                </div>
                <div class="cs-video">
                    <video autoplay muted loop playsinline>
                        <source src="../../assets/images/vr-gameplay.mp4" type="video/mp4">
                    </video>
                </div>
                <div class="cs-detail-grid">
                    <div class="cs-detail-card"><div class="label">Role</div><div class="value">Lead Programmer — owned all core systems</div></div>
                    <div class="cs-detail-card"><div class="label">Stack</div><div class="value">Unreal Engine 5 · C++ · Blueprints · OpenXR</div></div>
                    <div class="cs-detail-card"><div class="label">Team</div><div class="value">5 people — 2 programmers, 2 artists, 1 designer</div></div>
                    <div class="cs-detail-card"><div class="label">Key Challenge</div><div class="value">Body-movement dragon controller with VR locomotion</div></div>
                </div>
                <p class="cs-prose">This VR rhythm game puts the player inside a dragon — physical arm movements and body lean control flight direction while staying in sync with the music. Built for Meta Quest using Unreal Engine 5 and OpenXR. As Lead Programmer I was responsible for <strong>all gameplay systems</strong>, VR input mapping, and cross-team technical integration.</p>
                <p class="cs-prose"><strong>The core technical challenge</strong> was the dragon locomotion controller. Standard VR locomotion broke immersion; we needed the player's actual body pose to translate into dragon flight. I built a custom input aggregator in C++ that reads both hand controller orientation and head yaw simultaneously, blends them with a smoothing curve to prevent simulator sickness, and feeds the result into the flight physics. The threshold between "intentional tilt" and "natural head drift" required significant playtesting to tune.</p>
                <p class="cs-prose">Controller logic was implemented in C++ for deterministic per-frame execution at the physics tick rate. Blueprint was used for designer-facing tuning parameters — so the team could adjust sensitivity curves without touching code.</p>
                <ul class="cs-outcomes">
                    <li><strong>Full playable VR demo</strong> delivered within the semester timeline</li>
                    <li><strong>Custom C++ locomotion controller</strong> — body-movement dragon flight with sickness-mitigation smoothing</li>
                    <li><strong>Led 2-programmer sub-team</strong>, reviewed all code submissions and resolved merge conflicts across 4+ contributors</li>
                    <li><strong>C++ / Blueprint split architecture</strong> — production-quality systems with designer-accessible tuning</li>
                </ul>
                <div class="cs-cta-row">
                    <a href="../../index.php#contact" class="btn-primary magnetic">Ask about this project →</a>
                </div>
            </article>
        </div>

        <!-- CASE STUDY 03 — Penguins Creed -->
        <div class="cs-container">
            <article class="case-study reveal-up">
                <span class="cs-number">Case Study 03</span>
                <h2>Penguins Creed</h2>
                <div class="cs-meta">
                    <span class="cs-tag highlight">Gameplay Programmer</span>
                    <span class="cs-tag">Unreal Engine 5 · Blueprints</span>
                    <span class="cs-tag">Third-Person Action</span>
                    <span class="cs-tag">Stealth &amp; AI Systems</span>
                </div>
                <div class="cs-video">
                    <video autoplay muted loop playsinline>
                        <source src="../../assets/images/penguins-creed.mp4" type="video/mp4">
                    </video>
                </div>
                <div class="cs-detail-grid">
                    <div class="cs-detail-card"><div class="label">Stack</div><div class="value">Unreal Engine 5 · Blueprints · Behavior Trees</div></div>
                    <div class="cs-detail-card"><div class="label">Systems Built</div><div class="value">Patrol AI · detection cone · third-person movement · combat</div></div>
                    <div class="cs-detail-card"><div class="label">Genre</div><div class="value">Third-person stealth-action</div></div>
                    <div class="cs-detail-card"><div class="label">Notable</div><div class="value">Enemy AI with patrol routes and line-of-sight detection</div></div>
                </div>
                <p class="cs-prose">Penguins Creed is a third-person stealth-action game built in Unreal Engine 5. The project focused on building the core enemy AI — patrol routes, a field-of-view detection cone with noise sensitivity, and an alert state machine — all implemented using UE5 Behavior Trees and Blueprint.</p>
                <p class="cs-prose">Enemy AI uses a <strong>hierarchical Behavior Tree</strong> with three top-level states: Patrol, Alert, and Chase. Patrol follows waypoints with idle wait times. The detection system uses a cone-shaped sweep with configurable angle and distance, checking line-of-sight via trace before triggering Alert. The Chase state applies NavMesh pathfinding with dynamic obstacle avoidance.</p>
                <ul class="cs-outcomes">
                    <li><strong>Functional stealth loop</strong> — patrol, detect, alert, chase, and lose-sight return-to-patrol all working</li>
                    <li><strong>Configurable AI per enemy type</strong> — detection angle, speed, and aggression tuned per Blueprint instance</li>
                    <li><strong>Smooth third-person movement</strong> with blended animation states for walk, sprint, crouch, and jump</li>
                </ul>
            </article>
        </div>

        <!-- Other Projects -->
        <div class="cs-container">
            <section class="cs-section reveal-up">
                <p class="eyebrow" style="margin-bottom:0.75rem">Also Worth Noting</p>
                <h2 style="font-family:var(--font-display);font-size:clamp(1.6rem,4vw,2.4rem);font-weight:800;letter-spacing:-0.02em;color:var(--text);margin-bottom:2rem">More Projects</h2>
                <div class="cs-other-grid">
                    <div class="cs-other-card">
                        <p class="eyebrow" style="margin-bottom:0.5rem">Web Game · JavaScript</p>
                        <h3>Mario Kart Recreation</h3>
                        <p>SNES-style Mode-7 renderer built from scratch in vanilla JS — raycasting, sprite sheets, lap logic, and multi-character selection. No libraries.</p>
                        <a href="/portfolio/game-programming/mario-kart/" class="btn-secondary" style="font-size:0.85rem">View Project →</a>
                    </div>
                    <div class="cs-other-card">
                        <p class="eyebrow" style="margin-bottom:0.5rem">3D Art · Unreal Engine 5</p>
                        <h3>Mediterranean Environment</h3>
                        <p>Photorealistic environment built in UE5 using Lumen GI, Nanite geometry, and custom material layering for a Mediterranean coastal scene.</p>
                        <a href="/portfolio/art/" class="btn-secondary" style="font-size:0.85rem">View in Art Portfolio →</a>
                    </div>
                    <div class="cs-other-card">
                        <p class="eyebrow" style="margin-bottom:0.5rem">macOS App · Desktop Companion</p>
                        <h3>DeskPet</h3>
                        <p>Pixel-art desktop companion app for macOS. Idle, walking, and interaction animations — packaged as a signed .dmg for direct download.</p>
                        <a href="DeskPet/DeskPet_Simple.dmg" download class="btn-secondary" style="font-size:0.85rem">Download for Mac →</a>
                    </div>
                    <div class="cs-other-card">
                        <p class="eyebrow" style="margin-bottom:0.5rem">Pixel Art · Game Sprites</p>
                        <h3>Captain's Log</h3>
                        <p>Custom pixel art tileset and sprite collection for a nautical-themed adventure game — hand-crafted assets with cohesive visual language and animation sheets.</p>
                        <span class="btn-secondary" style="font-size:0.85rem;opacity:0.4;cursor:not-allowed;">Coming Soon</span>
                    </div>
                </div>
            </section>
        </div>

        <!-- Nav footer -->
        <div class="cs-container">
            <section style="padding:60px 0;text-align:center;" class="reveal-up">
                <p style="color:var(--text-muted);margin-bottom:1.5rem">Explore more work</p>
                <div style="display:flex;flex-wrap:wrap;gap:12px;justify-content:center;">
                    <a href="../web-programming/" class="btn-primary magnetic">Web Programming →</a>
                    <a href="../art/" class="btn-secondary">Art &amp; 3D →</a>
                    <a href="../" class="btn-secondary">← All Portfolio</a>
                </div>
            </section>
        </div>

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
      (function(){var els=document.querySelectorAll('.reveal-up');if(!els.length)return;els.forEach(function(el){el.style.opacity='0';el.style.transform='translateY(32px)';el.style.transition='opacity 0.65s ease, transform 0.65s ease';});var io=new IntersectionObserver(function(entries){entries.forEach(function(e){if(e.isIntersecting){e.target.style.opacity='1';e.target.style.transform='translateY(0)';io.unobserve(e.target);}});},{threshold:0.12});els.forEach(function(el){io.observe(el);});})();
    </script>

</body>
</html>
