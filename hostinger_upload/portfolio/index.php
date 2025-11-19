<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio - Jake Barton</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Oswald:wght@400;700&display=swap" rel="stylesheet">
    <script src="../assets/js/effects.js" defer></script>
</head>
<body>
    <div class="animated-bg"></div>

    <header>
        <nav>
            <a href="../index.php" class="nav-logo" style="text-decoration: none; color: inherit;">JB</a>
            <button class="nav-toggle" aria-controls="primary-menu" aria-expanded="false" aria-label="Open menu">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </button>
            <ul id="primary-menu">
                <li class="mobile-visible"><a href="../index.php">Home</a></li>
                <li class="mobile-visible"><a href="./">Portfolio</a></li>
                <li><a href="professional-works/">Professional Works</a></li>
                <li><a href="games/">Games</a></li>
                <li><a href="tshirt-designs/">T-Shirt Designs</a></li>
                <li><a href="../assets/Jake%20Barton%20-%20Resume.pdf" download>Resume</a></li>
                <li class="mobile-visible"><a href="../index.php#contact">Contact</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <div class="content-section" style="text-align: center; padding: 100px 60px;">
            <h1 style="font-size: 5rem;">MY PORTFOLIO</h1>
            <p style="font-size: 1.4rem; color: var(--text-muted); margin-top: 20px; font-family: 'Bebas Neue', sans-serif; letter-spacing: 2px;">
                EXPLORE MY CREATIVE WORK ACROSS GAME DESIGN, 3D ART, AND GRAPHIC DESIGN
            </p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 30px; margin-top: 40px;">
            
            <!-- Professional Works Card -->
            <div class="content-section" style="text-align: center; padding: 60px 40px; transition: all 0.4s ease; cursor: pointer;"
                 onclick="window.location.href='professional-works/';"
                 onmouseover="this.style.transform='translateY(-10px) scale(1.02)'; this.style.boxShadow='0 20px 60px rgba(255, 255, 255, 0.15)'; this.style.borderColor='var(--accent-white)';"
                 onmouseout="this.style.transform=''; this.style.boxShadow=''; this.style.borderColor='var(--border-gray)';">
                <div style="font-size: 5rem; margin-bottom: 25px; color: var(--accent-white); font-weight: 300;">■</div>
                <h2 style="color: var(--accent-white); margin-bottom: 20px;">PROFESSIONAL WORKS</h2>
                <p style="color: var(--text-muted); font-size: 1.1rem; margin-bottom: 30px;">
                    Client projects & professional graphic design work
                </p>
                <a href="professional-works/" class="btn" onclick="event.stopPropagation();">VIEW PROJECTS</a>
            </div>

            <!-- T-Shirt Designs Card -->
            <div class="content-section" style="text-align: center; padding: 60px 40px; transition: all 0.4s ease; cursor: pointer;"
                 onclick="window.location.href='tshirt-designs/';"
                 onmouseover="this.style.transform='translateY(-10px) scale(1.02)'; this.style.boxShadow='0 20px 60px rgba(255, 255, 255, 0.15)'; this.style.borderColor='var(--accent-white)';"
                 onmouseout="this.style.transform=''; this.style.boxShadow=''; this.style.borderColor='var(--border-gray)';">
                <div style="font-size: 5rem; margin-bottom: 25px; color: var(--accent-white); font-weight: 300;">▲</div>
                <h2 style="color: var(--accent-white); margin-bottom: 20px;">T-SHIRT DESIGNS</h2>
                <p style="color: var(--text-muted); font-size: 1.1rem; margin-bottom: 30px;">
                    Custom apparel designs created as T-Shirt Chair for Pi Kappa Phi
                </p>
                <a href="tshirt-designs/" class="btn" onclick="event.stopPropagation();">VIEW GALLERY</a>
            </div>

            <!-- Game Projects Card -->
            <div class="content-section" style="text-align: center; padding: 60px 40px; transition: all 0.4s ease; cursor: pointer;"
                 onclick="window.location.href='games/';"
                 onmouseover="this.style.transform='translateY(-10px) scale(1.02)'; this.style.boxShadow='0 20px 60px rgba(255, 255, 255, 0.15)'; this.style.borderColor='var(--accent-white)';"
                 onmouseout="this.style.transform=''; this.style.boxShadow=''; this.style.borderColor='var(--border-gray)';">
                <div style="font-size: 5rem; margin-bottom: 25px; color: var(--accent-white); font-weight: 300;">◆</div>
                <h2 style="color: var(--accent-white); margin-bottom: 20px;">GAME PROJECTS</h2>
                <p style="color: var(--text-muted); font-size: 1.1rem; margin-bottom: 30px;">
                    Interactive experiences built in Unreal, Unity, and Godot
                </p>
                <a href="games/" class="btn" onclick="event.stopPropagation();">PLAY GAMES</a>
            </div>

            <!-- 3D Art Card (Coming Soon) -->
            <div class="content-section" style="text-align: center; padding: 60px 40px; opacity: 0.5;">
                <div style="font-size: 5rem; margin-bottom: 25px; color: var(--text-muted); font-weight: 300;">●</div>
                <h2 style="color: var(--text-muted); margin-bottom: 20px;">3D ART & MODELS</h2>
                <p style="color: var(--text-muted); font-size: 1.1rem; margin-bottom: 30px;">
                    3D artwork and models created in Maya and Blender
                </p>
                <span class="btn" style="opacity: 0.5; cursor: not-allowed; pointer-events: none;">COMING SOON</span>
            </div>

            <!-- Web & UX Design Card (Coming Soon) -->
            <div class="content-section" style="text-align: center; padding: 60px 40px; opacity: 0.5;">
                <div style="font-size: 5rem; margin-bottom: 25px; color: var(--text-muted); font-weight: 300;">▢</div>
                <h2 style="color: var(--text-muted); margin-bottom: 20px;">WEB & UX DESIGN</h2>
                <p style="color: var(--text-muted); font-size: 1.1rem; margin-bottom: 30px;">
                    User interface and experience design work in Figma
                </p>
                <span class="btn" style="opacity: 0.5; cursor: not-allowed; pointer-events: none;">COMING SOON</span>
            </div>
            
        </div>

        <div class="content-section" style="text-align: center; margin-top: 60px;">
            <p style="color: var(--text-muted); font-size: 1.1rem;">
                More projects and work samples are being added regularly. Check back soon!
            </p>
        </div>
    </div>
    <script>
    // Mobile nav fallback
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.querySelector('.nav-toggle');
        const menu = document.getElementById('primary-menu');
        if (!btn || !menu) return;
        
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const expanded = btn.getAttribute('aria-expanded') === 'true';
            btn.setAttribute('aria-expanded', !expanded);
            menu.classList.toggle('open');
        });
        
        // Close on outside click
        document.addEventListener('click', function(e) {
            if (menu.classList.contains('open') && !menu.contains(e.target) && !btn.contains(e.target)) {
                menu.classList.remove('open');
                btn.setAttribute('aria-expanded', 'false');
            }
        });
    });
    </script>
</body>
</html>
