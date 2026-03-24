<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Programming - Jake Barton</title>
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
            <div style="font-size: 4rem; color: var(--accent-cyan); margin-bottom: 20px;">●</div>
            <h1 style="font-size: 4rem; margin-bottom: 15px;">WEB PROGRAMMING</h1>
            <p style="font-size: 1.2rem; color: var(--text-muted); font-family: 'Oswald', sans-serif; letter-spacing: 1px;">
                Full-stack web applications and interactive JavaScript projects
            </p>
        </div>

        <!-- Web Projects Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 40px; margin: 60px 20px;">
            
            <!-- TechBirmingham AI Sponsor Research Agent -->
            <div class="content-section portfolio-card" style="padding: 0; overflow: hidden; transition: all 0.3s ease;">
                <div style="height: 250px; background: linear-gradient(135deg, #0a0a1a 0%, #0d1b2a 100%); display: flex; align-items: center; justify-content: center; border-bottom: 2px solid var(--border-gray); position: relative; overflow: hidden;">
                    <img src="../../assets/images/tb-logo.jpg" alt="TechBirmingham" style="max-width: 65%; max-height: 65%; object-fit: contain; position: relative; z-index: 1; border-radius: 12px;">
                </div>
                <div style="padding: 30px;">
                    <h3 style="color: var(--accent-white); margin-bottom: 10px; font-size: 1.8rem;">TechBirmingham Sponsor Research AI</h3>
                    <p style="color: var(--accent-cyan); font-size: 0.9rem; margin-bottom: 15px; font-family: 'Oswald', sans-serif; letter-spacing: 1px;">NEXT.JS • AI AGENT • PRISMA • GOOGLE SHEETS</p>
                    <p style="color: var(--text-muted); line-height: 1.6; margin-bottom: 25px;">
                        AI-powered sponsor research platform built for TechBirmingham. Conversational agent that researches companies, scores sponsorship fit, finds contact emails, and auto-exports data to Google Sheets.
                    </p>
                    <a href="https://techbirmingham-sponsor-ai.vercel.app" class="btn portfolio-btn" style="display: inline-block;" target="_blank">LAUNCH APP →</a>
                </div>
            </div>

            <!-- Pi Kappa Phi T-Shirt Website -->
            <div class="content-section portfolio-card" style="padding: 0; overflow: hidden; transition: all 0.3s ease;">
                <div style="height: 250px; background: #1a1a1a; display: flex; align-items: center; justify-content: center; border-bottom: 2px solid var(--border-gray); position: relative; overflow: hidden;">
                    <img src="PiKappaPhiTshirtWeb2.0/vectors/CardImg.png" alt="Pi Kappa Phi T-Shirt Store" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div style="padding: 30px;">
                    <h3 style="color: var(--accent-white); margin-bottom: 10px; font-size: 1.8rem;">Pi Kappa Phi T-Shirt Store</h3>
                    <p style="color: var(--accent-cyan); font-size: 0.9rem; margin-bottom: 15px; font-family: 'Oswald', sans-serif; letter-spacing: 1px;">PHP • MYSQL • JAVASCRIPT • E-COMMERCE</p>
                    <p style="color: var(--text-muted); line-height: 1.6; margin-bottom: 25px;">
                        Full-stack e-commerce platform for Pi Kappa Phi fraternity apparel. Features admin dashboard, customer accounts, order management, email notifications, and secure authentication.
                    </p>
                    <a href="PiKappaPhiTshirtWeb2.0/index.php" class="btn portfolio-btn" style="display: inline-block;" target="_blank">VIEW SITE →</a>
                </div>
            </div>
            
            <!-- Mario Kart -->
            <div class="content-section portfolio-card" style="padding: 0; overflow: hidden; transition: all 0.3s ease;">
                <div style="height: 250px; background: linear-gradient(135deg, #0066cc 0%, #0099ff 100%); display: flex; align-items: center; justify-content: center; border-bottom: 2px solid var(--border-gray); position: relative; overflow: hidden;">
                    <img src="../../assets/images/mariokart.png" alt="Mario Kart" class="portfolio-image" style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0;">
                </div>
                <div style="padding: 30px;">
                    <h3 style="color: var(--accent-white); margin-bottom: 10px; font-size: 1.8rem;">Mario Kart Reverse Engineered</h3>
                    <p style="color: var(--accent-cyan); font-size: 0.9rem; margin-bottom: 15px; font-family: 'Oswald', sans-serif; letter-spacing: 1px;">JAVASCRIPT • HTML5 CANVAS • MODE 7</p>
                    <p style="color: var(--text-muted); line-height: 1.6; margin-bottom: 25px;">
                        Recreation of SNES Super Mario Kart using JavaScript and HTML5 Canvas - Still in development. Controls: Arrow Keys to steer and accelerate, Shift to use items, Enter to start
                    </p>
                    <a href="../../MarioKartLatest/index.html" class="btn portfolio-btn" style="display: inline-block;" target="_blank">PLAY GAME →</a>
                </div>
            </div>

            <!-- Shel Silverstein Website -->
            <div class="content-section portfolio-card" style="padding: 0; overflow: hidden; transition: all 0.3s ease;">
                <div style="height: 250px; background: linear-gradient(135deg, #2a4a2a 0%, #3d6a3d 100%); display: flex; align-items: center; justify-content: center; border-bottom: 2px solid var(--border-gray); position: relative; overflow: hidden;">
                    <img src="../../assets/images/shelcover.png" alt="Shel Silverstein" class="portfolio-image" style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0;">
                </div>
                <div style="padding: 30px;">
                    <h3 style="color: var(--accent-white); margin-bottom: 10px; font-size: 1.8rem;">Shel Silverstein Tribute</h3>
                    <p style="color: var(--accent-cyan); font-size: 0.9rem; margin-bottom: 15px; font-family: 'Oswald', sans-serif; letter-spacing: 1px;">HTML • CSS • RESPONSIVE DESIGN</p>
                    <p style="color: var(--text-muted); line-height: 1.6; margin-bottom: 25px;">
                        Interactive tribute website celebrating the work of poet and author Shel Silverstein. 2025 in class Figma to Web project.
                    </p>
                    <a href="../../Shel Website/shel.html" class="btn portfolio-btn" style="display: inline-block;" target="_blank">VIEW SITE →</a>
                </div>
            </div>

            <!-- Jake Barton Portfolio -->
            <div class="content-section portfolio-card" style="padding: 0; overflow: hidden; transition: all 0.3s ease;">
                <div style="height: 250px; background: linear-gradient(135deg, #1a1a1a 0%, #0f2a2a 100%); display: flex; align-items: center; justify-content: center; border-bottom: 2px solid var(--border-gray); position: relative; overflow: hidden;">
                    <div style="font-size: 6rem; font-family: 'Bebas Neue', sans-serif; color: rgba(255, 255, 255, 0.08); letter-spacing: 0.1em;">JB</div>
                </div>
                <div style="padding: 30px;">
                    <h3 style="color: var(--accent-white); margin-bottom: 10px; font-size: 1.8rem;">Jake Barton Creative Portfolio</h3>
                    <p style="color: var(--accent-cyan); font-size: 0.9rem; margin-bottom: 15px; font-family: 'Oswald', sans-serif; letter-spacing: 1px;">PHP • CSS • JAVASCRIPT • UX DESIGN</p>
                    <p style="color: var(--text-muted); line-height: 1.6; margin-bottom: 25px;">
                        This website! A custom-built portfolio showcasing my work across game design, web development, and graphic design. Features particle effects, responsive design, and optimized performance.
                    </p>
                    <a href="../../index.php" class="btn portfolio-btn" style="display: inline-block;">YOU'RE HERE! →</a>
                </div>
            </div>

        </div>

        <!-- Call to Action -->
        <div style="text-align: center; padding: 60px 20px; margin-top: 40px;">
            <p style="color: var(--text-muted); font-size: 1.1rem; margin-bottom: 20px;">Explore my other work</p>
            <a href="../art/" class="btn">VIEW ART & DESIGN →</a>
        </div>
    </div>
</body>
</html>
