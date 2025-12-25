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
                <li><a href="../../index.php#about">About</a></li>
                <li><a href="../../index.php#skills">Skills</a></li>
                <li><a href="../">Portfolio</a></li>
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
            <div class="content-section portfolio-card" style="padding: 0; overflow: hidden; transition: all 0.4s ease; cursor: pointer;"
                 onclick="window.location.href='33-miles-graphics/';">
                <div style="height: 250px; background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%); display: flex; align-items: center; justify-content: center; border-bottom: 2px solid var(--border-gray); position: relative; overflow: hidden;">
                    <img src="33-miles-graphics/images/full/33-miles-01-grain-regular.png" alt="33Miles Graphics" class="portfolio-image" style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0;">
                </div>
                <div style="padding: 30px;">
                    <h2 style="color: var(--accent-white); margin-bottom: 20px; word-wrap: break-word;">33MILES GRAPHICS</h2>
                    <p style="color: var(--text-muted); font-size: 1.1rem; margin-bottom: 30px;">
                        Social media & event graphics for Christian band 33Miles - 8 designs
                    </p>
                    <a href="33-miles-graphics/" class="btn portfolio-btn" style="display: inline-block;" onclick="event.stopPropagation();">
                        CLIENT: 33MILES BAND
                    </a>
                </div>
            </div>

            <!-- College Guys Pressure Washing Card -->
            <div class="content-section portfolio-card" style="padding: 0; overflow: hidden; transition: all 0.4s ease; cursor: pointer;"
                 onclick="window.location.href='College Guys Pressure Washing/';">
                <div style="height: 250px; background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%); display: flex; align-items: center; justify-content: center; border-bottom: 2px solid var(--border-gray); position: relative; overflow: hidden;">
                    <img src="College Guys Pressure Washing/College Guys Pressure Washing Banner.svg" alt="College Guys Pressure Washing" class="portfolio-image" style="width: 100%; height: 100%; object-fit: contain; position: absolute; top: 0; left: 0; padding: 20px;">
                </div>
                <div style="padding: 30px;">
                    <h2 style="color: var(--accent-white); margin-bottom: 20px; word-wrap: break-word;">COLLEGE GUYS PRESSURE WASHING</h2>
                    <p style="color: var(--text-muted); font-size: 1.1rem; margin-bottom: 30px;">
                        Branding & marketing graphics for local pressure washing business
                    </p>
                    <a href="College Guys Pressure Washing/" class="btn portfolio-btn" style="display: inline-block;" onclick="event.stopPropagation();">
                        CLIENT: COLLEGE GUYS PRESSURE WASHING
                    </a>
                </div>
            </div>

            <!-- More Professional Projects Coming Soon -->
            <div class="content-section" style="padding: 0; overflow: hidden; opacity: 0.5;">
                <div style="height: 250px; background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%); display: flex; align-items: center; justify-content: center; border-bottom: 2px solid var(--border-gray);">
                    <div style="font-size: 5rem; color: var(--text-muted); font-weight: 300;">+</div>
                </div>
                <div style="padding: 30px;">
                    <h2 style="color: var(--text-muted); margin-bottom: 20px;">MORE PROJECTS</h2>
                    <p style="color: var(--text-muted); font-size: 1.1rem; margin-bottom: 30px;">
                        Additional professional client work and freelance projects
                    </p>
                    <span class="btn" style="opacity: 0.5; cursor: not-allowed; pointer-events: none;">COMING SOON</span>
                </div>
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
