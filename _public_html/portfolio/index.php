<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio - Jake Barton</title>
    <link rel="icon" type="image/svg+xml" href="../assets/images/favicon.svg">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Oswald:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Three.js Library -->
    <script type="importmap">
    {
        "imports": {
            "three": "https://cdn.jsdelivr.net/npm/three@0.160.0/build/three.module.js",
            "three/addons/": "https://cdn.jsdelivr.net/npm/three@0.160.0/examples/jsm/"
        }
    }
    </script>
    
    <script src="../assets/js/effects.js" defer></script>
    <script type="module" src="../assets/js/three-effects.js" defer></script>
</head>
<body>
    <div class="animated-bg"></div>

    <header>
        <nav>
            <a href="../index.php" class="nav-logo" style="text-decoration: none; color: inherit;">JB</a>
            <ul>
                <li><a href="../index.php">Home</a></li>
                <li><a href="../index.php#about">About</a></li>
                <li><a href="./">Portfolio</a></li>
                <li><a href="../assets/Jake%20Barton%20-%20Resume.pdf" download>Resume</a></li>
                <li><a href="../index.php#contact">Contact</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <div class="content-section" style="text-align: center; padding: 100px 60px 60px;">
            <h1 style="font-size: 5rem; margin-bottom: 20px;">MY PORTFOLIO</h1>
            <p style="font-size: 1.4rem; color: var(--text-muted); margin-top: 20px; font-family: 'Oswald', sans-serif; letter-spacing: 2px; text-transform: uppercase;">
                Explore my work across three main disciplines
            </p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 40px; margin: 60px 0; padding: 0 20px;">
            
            <!-- Game Programming Card -->
            <div class="content-section portfolio-card" style="padding: 0; overflow: hidden; transition: all 0.4s ease; cursor: pointer; position: relative;"
                 onclick="window.location.href='game-programming/';">
                <div style="height: 250px; background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%); display: flex; align-items: center; justify-content: center; border-bottom: 2px solid var(--border-gray); position: relative; overflow: hidden;">
                    <img src="../assets/images/phaserunnercover.png" alt="Game Programming" class="portfolio-image" style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0;">
                </div>
                <div style="padding: 30px;">
                    <h2 style="color: var(--accent-white); margin-bottom: 15px; font-size: 2rem;">GAME PROGRAMMING</h2>
                    <p style="color: var(--text-muted); font-size: 1.1rem; margin-bottom: 10px; font-family: 'Oswald', sans-serif;">
                        2 Projects
                    </p>
                    <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 30px; line-height: 1.6;">
                        Interactive game projects built with Godot and custom game engines
                    </p>
                    <a href="game-programming/" class="btn portfolio-btn" style="display: inline-block;" onclick="event.stopPropagation();">VIEW PROJECTS →</a>
                </div>
            </div>

            <!-- Web Programming Card -->
            <div class="content-section portfolio-card" style="padding: 0; overflow: hidden; transition: all 0.4s ease; cursor: pointer; position: relative;"
                 onclick="window.location.href='web-programming/';">
                <div style="height: 250px; background: linear-gradient(135deg, #0066cc 0%, #0099ff 100%); display: flex; align-items: center; justify-content: center; border-bottom: 2px solid var(--border-gray); position: relative; overflow: hidden;">
                    <img src="../assets/images/mariokart.png" alt="Web Programming" class="portfolio-image" style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0;">
                </div>
                <div style="padding: 30px;">
                    <h2 style="color: var(--accent-white); margin-bottom: 15px; font-size: 2rem;">WEB PROGRAMMING</h2>
                    <p style="color: var(--text-muted); font-size: 1.1rem; margin-bottom: 10px; font-family: 'Oswald', sans-serif;">
                        4 Projects
                    </p>
                    <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 30px; line-height: 1.6;">
                        Full-stack web applications and interactive JavaScript projects
                    </p>
                    <a href="web-programming/" class="btn portfolio-btn" style="display: inline-block;" onclick="event.stopPropagation();">VIEW PROJECTS →</a>
                </div>
            </div>

            <!-- Art Card -->
            <div class="content-section portfolio-card" style="padding: 0; overflow: hidden; transition: all 0.4s ease; cursor: pointer; position: relative;"
                 onclick="window.location.href='art/';">
                <div style="height: 250px; background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%); display: flex; align-items: center; justify-content: center; border-bottom: 2px solid var(--border-gray); position: relative; overflow: hidden;">
                    <img src="professional-works/33-miles-graphics/images/full/33-miles-01-grain-regular.png" alt="Art & Design" class="portfolio-image" style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0;">
                </div>
                <div style="padding: 30px;">
                    <h2 style="color: var(--accent-white); margin-bottom: 15px; font-size: 2rem;">ART</h2>
                    <p style="color: var(--text-muted); font-size: 1.1rem; margin-bottom: 10px; font-family: 'Oswald', sans-serif;">
                        Professional Graphics + 15 T-Shirt Designs
                    </p>
                    <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 30px; line-height: 1.6;">
                        Professional work and custom apparel designs
                    </p>
                    <a href="art/" class="btn portfolio-btn" style="display: inline-block;" onclick="event.stopPropagation();">VIEW WORK →</a>
                </div>
            </div>

        </div>

        <!-- Quick Stats -->
        <div style="text-align: center; padding: 40px 20px; margin-top: 40px;">
            <p style="color: var(--text-muted); font-size: 1.1rem; font-family: 'Oswald', sans-serif; letter-spacing: 1px;">
                TOTAL PROJECTS: <span style="color: var(--accent-cyan); font-weight: bold;">20+</span>
            </p>
        </div>
    </div>
</body>
</html>
