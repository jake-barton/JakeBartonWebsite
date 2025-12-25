<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Art - Jake Barton</title>
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
            <div style="font-size: 4rem; color: var(--accent-cyan); margin-bottom: 20px;">▲</div>
            <h1 style="font-size: 4rem; margin-bottom: 15px;">ART</h1>
            <p style="font-size: 1.2rem; color: var(--text-muted); font-family: 'Oswald', sans-serif; letter-spacing: 1px;">
                Professional graphics and custom apparel designs
            </p>
        </div>

        <!-- Art Categories Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 40px; margin: 60px 20px;">
            
            <!-- Professional Graphics -->
            <div class="content-section portfolio-card" style="padding: 0; overflow: hidden; transition: all 0.3s ease; cursor: pointer;"
                 onclick="window.location.href='../professional-works/';">
                <div style="height: 250px; background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%); display: flex; align-items: center; justify-content: center; border-bottom: 2px solid var(--border-gray); position: relative; overflow: hidden;">
                    <img src="../professional-works/33-miles-graphics/images/full/33-miles-01-grain-regular.png" alt="Professional Graphics" class="portfolio-image" style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0;">
                </div>
                <div style="padding: 30px;">
                    <h3 style="color: var(--accent-white); margin-bottom: 10px; font-size: 1.8rem;">Professional Graphics</h3>
                    <p style="color: var(--accent-cyan); font-size: 0.9rem; margin-bottom: 15px; font-family: 'Oswald', sans-serif; letter-spacing: 1px;">CLIENT WORK • ADOBE ILLUSTRATOR</p>
                    <p style="color: var(--text-muted); line-height: 1.6; margin-bottom: 10px;">
                        Professional graphic design projects for bands, businesses, and organizations.
                    </p>
                    <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 25px; font-style: italic;">
                        • 33Miles Band Graphics<br>
                        • College Guys Pressure Washing
                    </p>
                    <a href="../professional-works/" class="btn portfolio-btn" onclick="event.stopPropagation();" style="display: inline-block;">VIEW WORK →</a>
                </div>
            </div>

            <!-- T-Shirt Designs -->
            <div class="content-section portfolio-card" style="padding: 0; overflow: hidden; transition: all 0.3s ease; cursor: pointer;"
                 onclick="window.location.href='../tshirt-designs/';">
                <div style="height: 250px; background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%); display: flex; align-items: center; justify-content: center; border-bottom: 2px solid var(--border-gray); position: relative; overflow: hidden;">
                    <img src="../tshirt-designs/images/full/Barn Bash 2024.svg" alt="T-Shirt Designs" class="portfolio-image" style="width: 100%; height: 100%; object-fit: contain; position: absolute; top: 0; left: 0; padding: 20px;">
                </div>
                <div style="padding: 30px;">
                    <h3 style="color: var(--accent-white); margin-bottom: 10px; font-size: 1.8rem;">T-Shirt Designs</h3>
                    <p style="color: var(--accent-cyan); font-size: 0.9rem; margin-bottom: 15px; font-family: 'Oswald', sans-serif; letter-spacing: 1px;">15+ DESIGNS • T-SHIRT CHAIR</p>
                    <p style="color: var(--text-muted); line-height: 1.6; margin-bottom: 10px;">
                        Custom apparel designs created as T-Shirt Chair for Pi Kappa Phi Alpha Eta chapter.
                    </p>
                    <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 25px; font-style: italic;">
                        • Fraternity Events<br>
                        • Recruitment Campaigns<br>
                        • Philanthropy Initiatives
                    </p>
                    <a href="../tshirt-designs/" class="btn portfolio-btn" onclick="event.stopPropagation();" style="display: inline-block;">VIEW GALLERY →</a>
                </div>
            </div>

        </div>

        <!-- Stats Section -->
        <div style="text-align: center; padding: 60px 20px; margin-top: 40px;">
            <div style="display: flex; justify-content: center; gap: 60px; flex-wrap: wrap;">
                <div>
                    <div style="font-size: 3rem; color: var(--accent-cyan); font-family: 'Bebas Neue', sans-serif;">15+</div>
                    <p style="color: var(--text-muted); font-size: 0.9rem; font-family: 'Oswald', sans-serif; letter-spacing: 1px;">T-SHIRT DESIGNS</p>
                </div>
                <div>
                    <div style="font-size: 3rem; color: var(--accent-cyan); font-family: 'Bebas Neue', sans-serif;">2</div>
                    <p style="color: var(--text-muted); font-size: 0.9rem; font-family: 'Oswald', sans-serif; letter-spacing: 1px;">PROFESSIONAL CLIENTS</p>
                </div>
                <div>
                    <div style="font-size: 3rem; color: var(--accent-cyan); font-family: 'Bebas Neue', sans-serif;">2</div>
                    <p style="color: var(--text-muted); font-size: 0.9rem; font-family: 'Oswald', sans-serif; letter-spacing: 1px;">YEARS AS T-SHIRT CHAIR</p>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div style="text-align: center; padding: 40px 20px;">
            <p style="color: var(--text-muted); font-size: 1.1rem; margin-bottom: 20px;">Check out my programming work</p>
            <a href="../game-programming/" class="btn">VIEW GAME PROJECTS →</a>
        </div>
    </div>
</body>
</html>
