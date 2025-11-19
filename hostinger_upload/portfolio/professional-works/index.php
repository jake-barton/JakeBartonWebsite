<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professional Works - Jake Barton</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Oswald:wght@400;700&display=swap" rel="stylesheet">
    <script src="../../assets/js/effects.js" defer></script>
</head>
<body>
    <div class="animated-bg"></div>

    <header>
        <nav>
            <a href="../../index.php" class="nav-logo" style="text-decoration: none; color: inherit;">JB</a>
            <button class="nav-toggle" aria-controls="primary-menu" aria-expanded="false" aria-label="Open menu">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </button>
            <ul id="primary-menu">
                <li class="mobile-visible"><a href="../../index.php">Home</a></li>
                <li class="mobile-visible"><a href="../">Portfolio</a></li>
                <li><a href="./">Professional Works</a></li>
                <li><a href="../games/">Games</a></li>
                <li><a href="../tshirt-designs/">T-Shirt Designs</a></li>
                <li><a href="../../assets/Jake%20Barton%20-%20Resume.pdf" download>Resume</a></li>
                <li class="mobile-visible"><a href="../../index.php#contact">Contact</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <div class="content-section" style="text-align: center; padding: 100px 20px;">
            <h1 style="font-size: clamp(2rem, 8vw, 5rem); line-height: 1.1;">PROFESSIONAL<br>WORKS</h1>
            <p style="font-size: clamp(1rem, 3vw, 1.4rem); color: var(--text-muted); margin-top: 20px; font-family: 'Bebas Neue', sans-serif; letter-spacing: 2px;">
                CLIENT PROJECTS & PROFESSIONAL GRAPHIC DESIGN WORK
            </p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; margin-top: 40px;">
            
            <!-- 33Miles Graphics Card -->
            <div class="content-section" style="text-align: center; padding: 60px 40px; transition: all 0.4s ease; cursor: pointer;"
                 onclick="window.location.href='33-miles-graphics/';"
                 onmouseover="this.style.transform='translateY(-10px) scale(1.02)'; this.style.boxShadow='0 20px 60px rgba(255, 255, 255, 0.15)'; this.style.borderColor='var(--accent-white)';"
                 onmouseout="this.style.transform=''; this.style.boxShadow=''; this.style.borderColor='var(--border-gray)';">
                <div style="font-size: 5rem; margin-bottom: 25px; color: var(--accent-white); font-weight: 300;">◇</div>
                <h2 style="color: var(--accent-white); margin-bottom: 20px; word-wrap: break-word;">33MILES GRAPHICS</h2>
                <p style="color: var(--text-muted); font-size: 1.1rem; margin-bottom: 30px;">
                    Social media & event graphics for Christian band 33Miles - 8 designs
                </p>
                <span class="btn" style="color: var(--accent-white); font-family: 'Bebas Neue', sans-serif; letter-spacing: 2px; display: inline-block; max-width: 100%;">
                    CLIENT: 33MILES BAND
                </span>
            </div>

            <!-- College Guys Pressure Washing Card -->
            <div class="content-section" style="text-align: center; padding: 60px 40px; transition: all 0.4s ease; cursor: pointer;"
                 onclick="window.location.href='College Guys Pressure Washing/';"
                 onmouseover="this.style.transform='translateY(-10px) scale(1.02)'; this.style.boxShadow='0 20px 60px rgba(255, 255, 255, 0.15)'; this.style.borderColor='var(--accent-white)';"
                 onmouseout="this.style.transform=''; this.style.boxShadow=''; this.style.borderColor='var(--border-gray)';">
                <div style="font-size: 5rem; margin-bottom: 25px; color: var(--accent-white); font-weight: 300;">◈</div>
                <h2 style="color: var(--accent-white); margin-bottom: 20px; word-wrap: break-word;">COLLEGE GUYS PRESSURE WASHING</h2>
                <p style="color: var(--text-muted); font-size: 1.1rem; margin-bottom: 30px;">
                    Branding & marketing graphics for local pressure washing business
                </p>
                <span class="btn" style="color: var(--accent-white); font-family: 'Bebas Neue', sans-serif; letter-spacing: 2px; display: inline-block; max-width: 100%;">
                    CLIENT: COLLEGE GUYS PRESSURE WASHING
                </span>
            </div>

            <!-- More Professional Projects Coming Soon -->
            <div class="content-section" style="text-align: center; padding: 60px 40px; opacity: 0.5;">
                <div style="font-size: 5rem; margin-bottom: 25px; color: var(--text-muted); font-weight: 300;">+</div>
                <h2 style="color: var(--text-muted); margin-bottom: 20px;">MORE PROJECTS</h2>
                <p style="color: var(--text-muted); font-size: 1.1rem; margin-bottom: 30px;">
                    Additional professional client work and freelance projects
                </p>
                <span class="btn" style="opacity: 0.5; cursor: not-allowed; pointer-events: none;">COMING SOON</span>
            </div>
            
        </div>

        <div class="content-section" style="text-align: center; margin-top: 60px;">
            <p style="color: var(--text-muted); font-size: 1.1rem;">
                Professional client projects showcasing graphic design, branding, and marketing materials.
            </p>
            <a href="../" class="btn" style="margin-top: 30px; display: inline-block;">← BACK TO PORTFOLIO</a>
        </div>
    </div>
</body>
</html>
