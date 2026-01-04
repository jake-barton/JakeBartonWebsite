<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Programming - Jake Barton</title>
    <link rel="icon" type="image/svg+xml" href="../../assets/images/favicon.svg">
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Oswald:wght@400;700&display=swap" rel="stylesheet">
    <script src="../../assets/js/effects.js" defer></script>
</head>
<body>
    <div class="animated-bg"></div>

    <header>
        <nav>
            <a href="../../index.php" class="nav-logo" style="text-decoration: none; color: inherit;">JB</a>
            <ul>
                <li><a href="../../index.php">Home</a></li>
                <li><a href="../../index.php#about">About</a></li>
                <li><a href="../">Portfolio</a></li>
                <li><a href="../../assets/Jake%20Barton%20-%20Resume.pdf" download>Resume</a></li>
                <li><a href="../../index.php#contact">Contact</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <!-- Breadcrumb -->
        <div style="padding: 30px 20px 10px; font-size: 0.9rem;">
            <a href="../" style="color: var(--text-muted); text-decoration: none;">← Back to Portfolio</a>
        </div>

        <div class="content-section" style="text-align: center; padding: 60px 40px 40px;">
            <div style="font-size: 4rem; color: var(--accent-cyan); margin-bottom: 20px;">◆</div>
            <h1 style="font-size: 4rem; margin-bottom: 15px;">GAME PROGRAMMING</h1>
            <p style="font-size: 1.2rem; color: var(--text-muted); font-family: 'Oswald', sans-serif; letter-spacing: 1px;">
                Interactive game projects built with custom engines and Godot
            </p>
        </div>

        <!-- Game Projects Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 40px; margin: 60px 20px;">
            
            <!-- Phase Runner -->
            <div class="content-section portfolio-card" style="padding: 0; overflow: hidden; transition: all 0.3s ease;">
                <div style="height: 250px; background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%); display: flex; align-items: center; justify-content: center; border-bottom: 2px solid var(--border-gray); position: relative; overflow: hidden;">
                    <img src="../../assets/images/phaserunnercover.png" alt="Phase Runner" class="portfolio-image" style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0;">
                </div>
                <div style="padding: 30px;">
                    <h3 style="color: var(--accent-white); margin-bottom: 10px; font-size: 1.8rem;">Phase Runner</h3>
                    <p style="color: var(--accent-cyan); font-size: 0.9rem; margin-bottom: 15px; font-family: 'Oswald', sans-serif; letter-spacing: 1px;">GODOT ENGINE • PLATFORMER</p>
                    <p style="color: var(--text-muted); line-height: 1.6; margin-bottom: 25px;">
                        A fast-paced platformer built in Godot featuring custom physics, responsive controls, and challenging level design.
                    </p>
                    <a href="../games/phase-runner/" class="btn portfolio-btn" style="display: inline-block;">PLAY GAME →</a>
                </div>
            </div>

            <!-- DeskPet -->
            <div class="content-section portfolio-card" style="padding: 0; overflow: hidden; transition: all 0.3s ease;">
                <div style="height: 250px; background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%); display: flex; align-items: center; justify-content: center; border-bottom: 2px solid var(--border-gray); position: relative; overflow: hidden;">
                    <img src="DeskPet/AppIcon.png" alt="DeskPet" class="portfolio-image" style="width: 200px; height: 200px; object-fit: contain; image-rendering: pixelated; image-rendering: -moz-crisp-edges; image-rendering: crisp-edges;">
                </div>
                <div style="padding: 30px;">
                    <h3 style="color: var(--accent-white); margin-bottom: 10px; font-size: 1.8rem;">DeskPet</h3>
                    <p style="color: var(--accent-cyan); font-size: 0.9rem; margin-bottom: 15px; font-family: 'Oswald', sans-serif; letter-spacing: 1px;">MACOS APP • DESKTOP COMPANION</p>
                    <p style="color: var(--text-muted); line-height: 1.6; margin-bottom: 25px;">
                        A delightful desktop companion app for macOS. Download and install to bring a friendly pet to your screen!
                    </p>
                    <a href="DeskPet/DeskPet_Simple.dmg" download class="btn portfolio-btn" style="display: inline-block;">DOWNLOAD FOR MAC →</a>
                </div>
            </div>

            <!-- Captain's Log -->
            <div class="content-section portfolio-card" style="padding: 0; overflow: hidden; transition: all 0.3s ease;">
                <div style="height: 250px; background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%); display: flex; align-items: center; justify-content: center; border-bottom: 2px solid var(--border-gray); position: relative; overflow: hidden;">
                    <img src="../captainslogtruetiles.png" alt="Captain's Log Sprites" class="portfolio-image" style="max-width: 90%; max-height: 90%; object-fit: contain; position: relative; z-index: 1;">
                </div>
                <div style="padding: 30px;">
                    <h3 style="color: var(--accent-white); margin-bottom: 10px; font-size: 1.8rem;">Captain's Log</h3>
                    <p style="color: var(--accent-cyan); font-size: 0.9rem; margin-bottom: 15px; font-family: 'Oswald', sans-serif; letter-spacing: 1px;">PIXEL ART • GAME SPRITES</p>
                    <p style="color: var(--text-muted); line-height: 1.6; margin-bottom: 25px;">
                        Custom pixel art tileset and sprite collection for a nautical-themed adventure game. Features hand-crafted assets and cohesive visual style.
                    </p>
                    <span class="btn" style="display: inline-block; opacity: 0.5; cursor: not-allowed;">VIEW PROJECT</span>
                </div>
            </div>

        </div>

        <!-- Call to Action -->
        <div style="text-align: center; padding: 60px 20px; margin-top: 40px;">
            <p style="color: var(--text-muted); font-size: 1.1rem; margin-bottom: 20px;">Want to see more?</p>
            <a href="../web-programming/" class="btn">EXPLORE WEB PROGRAMMING →</a>
        </div>
    </div>
</body>
</html>
