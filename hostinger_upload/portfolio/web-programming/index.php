<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Programming - Jake Barton</title>
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
                <p class="eyebrow hero-eyebrow">Portfolio → Web Programming</p>
                <h1 class="reveal" style="font-family:var(--font-display);font-size:clamp(2.5rem,6vw,4.5rem);font-weight:700;letter-spacing:-0.02em;line-height:1.1;color:var(--text);margin-bottom:1rem">Web Programming</h1>
                <div class="divider reveal" style="margin:1.5rem auto;max-width:80px"></div>
                <p class="reveal" style="color: var(--text-muted); font-size: 1.1rem; margin-top: 0.5rem; line-height:1.75;transition-delay:0.12s">
                    Full-stack web applications and interactive JavaScript projects
                </p>
            </div>
        </section>

        <section class="section-sm">
            <div style="max-width: 1200px; margin: 0 auto; padding: 0 var(--container-pad);">
                <div class="section-header reveal" style="margin-bottom:2rem">
                    <span class="eyebrow">Projects</span>
                    <h2>Web Projects</h2>
                </div>
                <div class="grid-2 stagger-children">

                    <!-- TechBirmingham AI -->
                    <div class="work-card tilt-card reveal">
                        <div class="work-card-img">
                            <img src="../../assets/images/tb-logo.jpg" alt="TechBirmingham" style="width: 100%; height: 100%; object-fit: contain; padding: 30px; background: var(--bg);">
                        </div>
                        <div class="work-card-info">
                            <p class="eyebrow">Next.js · AI Agent · Prisma · Google Sheets</p>
                            <h3>TechBirmingham Sponsor Research AI</h3>
                            <p style="color: var(--text-muted); font-size: 0.95rem; margin: 8px 0 20px;">
                                AI-powered sponsor research platform for TechBirmingham. Conversational agent that researches companies, scores sponsorship fit, finds contact emails, and auto-exports to Google Sheets.
                            </p>
                            <a href="https://techbirmingham-sponsor-ai.vercel.app" class="btn-primary" target="_blank">Launch App →</a>
                        </div>
                    </div>

                    <!-- Pi Kappa Phi T-Shirt Store -->
                    <div class="work-card tilt-card reveal">
                        <div class="work-card-img">
                            <img src="PiKappaPhiTshirtWeb2.0/vectors/CardImg.png" alt="Pi Kappa Phi T-Shirt Store" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <div class="work-card-info">
                            <p class="eyebrow">PHP · MySQL · JavaScript · E-Commerce</p>
                            <h3>Pi Kappa Phi T-Shirt Store</h3>
                            <p style="color: var(--text-muted); font-size: 0.95rem; margin: 8px 0 20px;">
                                Full-stack e-commerce platform for Pi Kappa Phi fraternity apparel. Admin dashboard, customer accounts, order management, email notifications, and secure auth.
                            </p>
                            <a href="PiKappaPhiTshirtWeb2.0/index.php" class="btn-primary" target="_blank">View Site →</a>
                        </div>
                    </div>

                    <!-- Mario Kart -->
                    <div class="work-card tilt-card reveal">
                        <div class="work-card-img">
                            <img src="../../assets/images/mariokart.png" alt="Mario Kart" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <div class="work-card-info">
                            <p class="eyebrow">JavaScript · HTML5 Canvas · Mode 7</p>
                            <h3>Mario Kart Reverse Engineered</h3>
                            <p style="color: var(--text-muted); font-size: 0.95rem; margin: 8px 0 20px;">
                                Recreation of SNES Super Mario Kart using JavaScript and HTML5 Canvas. Still in development. Controls: Arrow Keys to steer and accelerate, Shift to use items, Enter to start.
                            </p>
                            <a href="../../MarioKartLatest/index.html" class="btn-primary" target="_blank">Play Game →</a>
                        </div>
                    </div>

                    <!-- Shel Silverstein -->
                    <div class="work-card tilt-card reveal">
                        <div class="work-card-img">
                            <img src="../../assets/images/shelcover.png" alt="Shel Silverstein" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <div class="work-card-info">
                            <p class="eyebrow">HTML · CSS · Responsive Design</p>
                            <h3>Shel Silverstein Tribute</h3>
                            <p style="color: var(--text-muted); font-size: 0.95rem; margin: 8px 0 20px;">
                                Interactive tribute website celebrating the work of poet and author Shel Silverstein. 2025 in-class Figma to Web project.
                            </p>
                            <a href="../../Shel Website/shel.html" class="btn-primary" target="_blank">View Site →</a>
                        </div>
                    </div>

                    <!-- Forge -->
                    <div class="work-card tilt-card reveal">
                        <div class="work-card-img">
                            <img src="../../assets/images/forge-cover.svg" alt="Forge" style="width:100%;height:100%;object-fit:cover;">
                        </div>
                        <div class="work-card-info">
                            <p class="eyebrow">Next.js 14 · TypeScript · PostgreSQL · Prisma · Three.js</p>
                            <h3>Forge — Developer Social Platform</h3>
                            <p style="color: var(--text-muted); font-size: 0.95rem; margin: 8px 0 12px;">
                                A social platform where projects are posts. Built for junior developers to build in public, remix others' work, get constructive feedback, and grow with an AI mentor — not just empty likes.
                            </p>
                            <span class="tag tag-muted" style="display:inline-block;margin-bottom:16px">In Development</span>
                            <br>
                            <a href="https://forge-jakebarton.vercel.app" class="btn-primary" target="_blank">View Live →</a>
                            &nbsp;
                            <a href="https://github.com/jake-barton/Forge" class="btn-secondary" target="_blank">GitHub →</a>
                        </div>
                    </div>

                    <!-- ClearDesk -->
                    <div class="work-card tilt-card reveal">
                        <div class="work-card-img">
                            <img src="../../assets/images/cleardesk-cover.svg" alt="ClearDesk" style="width:100%;height:100%;object-fit:cover;">
                        </div>
                        <div class="work-card-info">
                            <p class="eyebrow">Electron · React · TypeScript · Prisma · OpenAI · Stripe</p>
                            <h3>ClearDesk — AI Task Manager</h3>
                            <p style="color: var(--text-muted); font-size: 0.95rem; margin: 8px 0 12px;">
                                macOS productivity app that converts inbox chaos into a clear daily action plan. AI parses tasks from natural language and email, OCR reads invoices, and SMS delivers your morning digest.
                            </p>
                            <span class="tag tag-muted" style="display:inline-block;margin-bottom:16px">In Development</span>
                            <br>
                            <a href="https://github.com/jake-barton/ClearDesk" class="btn-secondary" target="_blank">View on GitHub →</a>
                        </div>
                    </div>

                    <!-- October (JARVIS) -->
                    <div class="work-card tilt-card reveal">
                        <div class="work-card-img">
                            <img src="../../assets/images/october-cover.svg" alt="October AI" style="width:100%;height:100%;object-fit:cover;">
                        </div>
                        <div class="work-card-info">
                            <p class="eyebrow">Python · Ollama · Whisper STT · Local LLM · macOS</p>
                            <h3>October — Personal AI Assistant</h3>
                            <p style="color: var(--text-muted); font-size: 0.95rem; margin: 8px 0 12px;">
                                Voice-controlled macOS AI assistant that runs 100% locally. Push-to-talk with Whisper STT, local Llama 3 LLM via Ollama, persistent memory, tool execution, and a permission gate for safety.
                            </p>
                            <span class="tag tag-muted" style="display:inline-block;margin-bottom:16px">In Development</span>
                            <br>
                            <a href="https://github.com/jake-barton/October" class="btn-secondary" target="_blank">View on GitHub →</a>
                        </div>
                    </div>

                    <!-- This Portfolio -->
                    <div class="work-card tilt-card reveal">
                        <div class="work-card-img" style="display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, var(--bg) 0%, var(--bg-card) 100%);">
                            <span style="font-family: var(--font-display); font-size: 7rem; color: rgba(255,255,255,0.1); letter-spacing: 0.1em;">JB</span>
                        </div>
                        <div class="work-card-info">
                            <p class="eyebrow">PHP · CSS · JavaScript · UX Design</p>
                            <h3>Jake Barton Creative Portfolio</h3>
                            <p style="color: var(--text-muted); font-size: 0.95rem; margin: 8px 0 20px;">
                                This website! Custom-built portfolio with style kit, canvas effects, responsive design, and optimised performance.
                            </p>
                            <a href="../../index.php" class="btn-secondary">You're here! →</a>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <section class="section-sm" style="text-align: center;">
            <p class="reveal" style="color: var(--text-muted); margin-bottom: 24px;">Explore my other work</p>
            <a href="../art/" class="btn-primary magnetic reveal" style="margin-right: 16px;">View Art &amp; Design →</a>
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
