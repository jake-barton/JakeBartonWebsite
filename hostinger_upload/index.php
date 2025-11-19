<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jake Barton - Game Designer & 3D Artist</title>
    <link rel="icon" type="image/svg+xml" href="assets/images/favicon.svg">
    <link rel="stylesheet" href="assets/css/styles.css?v=20251119-frost">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Oswald:wght@400;700&display=swap" rel="stylesheet">
    <script src="assets/js/effects.js?v=20251104-2" defer></script>
</head>
<body>
    <!-- Animated Background -->
    <div class="animated-bg"></div>

    <?php
    $name = "Jake Barton";
    $university = "Samford University";
    $location = "Birmingham, AL";
    $major = "Game Design & 3D Animation";
    $minor = "Computer Science";
    $year = "Junior"; // Class standing
    $gradYear = 2027; // Graduation year per resume
    $gpa = 3.5; // University GPA
    
    $skills = [
        "C++", "Python", "JavaScript", "HTML", "CSS",
        "Unreal Blueprint", "Unreal Engine", "Godot Engine",
        "Autodesk Maya", "Blender", "Substance Painter",
        "Adobe Photoshop", "Adobe Illustrator", "Figma",
        "Web Development", "Game Programming", "Level Design",
        "Project Management", "UX Design"
    ];
    
    $leadership = [
        "Social Chair - Pi Kappa Phi (2025–Current)",
        "Executive Council - Philanthropy Chair (2025–Current)",
        "T-Shirt Chair - Pi Kappa Phi (2023–2025)"
    ];
    
    $contact = [
        'email' => 'jbarton4@samford.edu',
        'phone' => '(615) 943 9722',
        'website' => 'https://jakebartoncreative.com',
        'address' => 'Birmingham, AL',
        'instagram' => 'jakebarton13',
        'github' => '',
        'youtube' => ''
    ];
    ?>

    <header>
        <nav>
            <a href="#home" class="nav-logo" style="text-decoration: none; color: inherit;">JB</a>
            <button class="nav-toggle" aria-controls="primary-menu" aria-expanded="false" aria-label="Open menu">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </button>
            <ul id="primary-menu">
                <li class="mobile-visible"><a href="#home">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#skills">Skills</a></li>
                <li class="mobile-visible"><a href="portfolio/">Portfolio</a></li>
                <li><a href="assets/Jake%20Barton%20-%20Resume.pdf" download>Resume</a></li>
                <li class="mobile-visible"><a href="#contact">Contact</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <!-- Hero Section -->
        <div class="content-section" id="home" style="text-align: center; padding: 120px 60px; margin-top: 60px;">
            <h1 style="font-size: 6rem; margin-bottom: 20px; animation: fadeInUp 0.8s ease-out;">
                <?php echo $name; ?>
            </h1>
            <p style="font-size: 1.8rem; color: var(--accent-white); margin-bottom: 15px; animation: fadeInUp 1s ease-out; font-family: 'Bebas Neue', sans-serif; letter-spacing: 3px;">
                GAME DESIGNER | 3D ARTIST | DEVELOPER
            </p>
            <p style="font-size: 1.3rem; color: var(--text-muted); margin-bottom: 50px; animation: fadeInUp 1.2s ease-out;">
                <?php echo $year; ?> at <?php echo $university; ?>, <?php echo $location; ?>
            </p>
            <div style="display:flex; gap:20px; justify-content:center; flex-wrap:wrap; animation: fadeInUp 1.4s ease-out;">
                <a href="portfolio/" class="btn">VIEW MY WORK</a>
                <a href="assets/Jake%20Barton%20-%20Resume.pdf" download class="btn" style="background: var(--primary-black); border:3px solid var(--accent-white); color: var(--accent-white);">DOWNLOAD RESUME (PDF)</a>
            </div>
        </div>

        <!-- Auto-Rotating Showcase Gallery -->
        <div class="content-section" style="padding: 60px 40px; overflow: visible;">
            <h2 style="text-align: center; margin-bottom: 50px;">FEATURED WORK</h2>
            
            <div class="carousel-wrapper">
                <div class="carousel-track">
                    <!-- T-Shirt Designs -->
                    <div class="carousel-slide">
                        <div class="carousel-card">
                            <img src="portfolio/tshirt-designs/images/thumbnails/Fall Recruitment '25-01.svg" alt="Fall Recruitment 2025">
                            <div class="carousel-info">
                                <h3>Fall Recruitment 2025</h3>
                                <p>T-Shirt Design</p>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-slide">
                        <div class="carousel-card">
                            <img src="portfolio/games/phase-runner/PhaseRunnerWeb.png" alt="Phase Runner Game">
                            <div class="carousel-info">
                                <h3>Phase Runner</h3>
                                <p>Godot Game Project</p>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-slide">
                        <div class="carousel-card">
                            <img src="portfolio/professional-works/33-miles-graphics/images/thumbnails/33-miles-01-grain-striped.png" alt="33Miles Graphics">
                            <div class="carousel-info">
                                <h3>33Miles Band Graphics</h3>
                                <p>Professional Client Work</p>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-slide">
                        <div class="carousel-card">
                            <img src="portfolio/tshirt-designs/images/thumbnails/PGA Polo.svg" alt="PGA Polo Design">
                            <div class="carousel-info">
                                <h3>PGA Polo</h3>
                                <p>Polo Shirt Design</p>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-slide">
                        <div class="carousel-card">
                            <img src="portfolio/tshirt-designs/images/thumbnails/SouthernGents-01.svg" alt="Southern Gents Design">
                            <div class="carousel-info">
                                <h3>Southern Gents</h3>
                                <p>Album-Inspired T-Shirt</p>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-slide">
                        <div class="carousel-card">
                            <img src="portfolio/professional-works/33-miles-graphics/images/thumbnails/33-miles-regular-01.png" alt="33Miles Clean Design">
                            <div class="carousel-info">
                                <h3>33Miles Social Media</h3>
                                <p>Event Advertisement</p>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-slide">
                        <div class="carousel-card">
                            <img src="portfolio/tshirt-designs/images/thumbnails/Fall Recruitment '25-02.svg" alt="Fall Recruitment 2025 Alt">
                            <div class="carousel-info">
                                <h3>Fall Recruitment 2025 Alt</h3>
                                <p>T-Shirt Design Variation</p>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-slide">
                        <div class="carousel-card">
                            <img src="portfolio/tshirt-designs/images/thumbnails/Barn Bash 2025.svg" alt="Barn Bash 2025">
                            <div class="carousel-info">
                                <h3>Barn Bash 2025</h3>
                                <p>Event T-Shirt Design</p>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-slide">
                        <div class="carousel-card">
                            <img src="portfolio/professional-works/33-miles-graphics/images/thumbnails/33-miles-05-grain-regular.png" alt="33Miles Grainy Design">
                            <div class="carousel-info">
                                <h3>33Miles Grainy Style</h3>
                                <p>Textured Graphics</p>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-slide">
                        <div class="carousel-card">
                            <img src="portfolio/tshirt-designs/images/thumbnails/Caribbean Party.svg" alt="Caribbean Party Design">
                            <div class="carousel-info">
                                <h3>Caribbean Party</h3>
                                <p>Themed Event Design</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Navigation Dots -->
                <div class="carousel-dots"></div>
            </div>

            <div style="text-align: center; margin-top: 50px;">
                <a href="portfolio/" class="btn">EXPLORE FULL PORTFOLIO</a>
            </div>
        </div>

        <!-- Skills Section -->
        <div class="content-section" id="skills">
            <h2>TECHNICAL SKILLS & TOOLS</h2>
            <div class="skills-container">
                <?php foreach ($skills as $skill): ?>
                    <span class="skill-tag"><?php echo $skill; ?></span>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- About Section -->
        <div class="content-section" id="about">
            <h2>ABOUT ME</h2>
            <p style="font-size: 1.15rem; line-height: 1.9;">
                I'm a <?php echo $year; ?> at <strong style="color: var(--accent-white);"><?php echo $university; ?></strong> 
                in <?php echo $location; ?>, majoring in <strong style="color: var(--accent-white);"><?php echo $major; ?></strong> 
                with a minor in <strong style="color: var(--accent-white);"><?php echo $minor; ?></strong>. Expected graduation: <strong style="color: var(--accent-white);"><?php echo $gradYear; ?></strong>. Current GPA: <strong style="color: var(--accent-white);"><?php echo number_format($gpa,2); ?></strong>.
            </p>
            
            <p style="font-size: 1.15rem; line-height: 1.9; margin-top: 20px;">
                I specialize in creating immersive gaming experiences and stunning 3D visuals, combining technical expertise 
                with creative design. From coding in <strong style="color: var(--accent-white);">Python</strong> and <strong style="color: var(--accent-white);">C++</strong> to bringing worlds to life in 
                <strong style="color: var(--accent-white);">Unreal Engine 5</strong>, <strong style="color: var(--accent-white);">Godot</strong>, and <strong style="color: var(--accent-white);">Unity</strong>, I'm passionate about every 
                aspect of game development.
            </p>

            <p style="font-size: 1.15rem; line-height: 1.9; margin-top: 20px;">
                My creative toolkit includes <strong style="color: var(--accent-white);">Autodesk Maya</strong>, <strong style="color: var(--accent-white);">Blender</strong>, <strong style="color: var(--accent-white);">Adobe Creative Suite</strong>, 
                and <strong style="color: var(--accent-white);">Figma</strong>, allowing me to craft everything from 3D models to user interfaces and graphic designs.
            </p>
        </div>

        <!-- Leadership Section -->
        <div class="content-section">
            <h2>LEADERSHIP & EXPERIENCE (PI KAPPA PHI)</h2>
            <p style="font-size: 1.15rem; line-height: 1.9;">
                As a committed member of <strong style="color: var(--accent-white);">Pi Kappa Phi Fraternity - Alpha Eta Chapter</strong>, 
                I serve in multiple leadership roles driving event planning, apparel production, and philanthropy impact:
            </p>
            <ul style="margin-top: 25px; font-size: 1.1rem; line-height: 2;">
                <?php foreach ($leadership as $position): ?>
                    <li style="color: var(--text-muted); margin-bottom: 10px;">
                        <span style="color: var(--accent-white);">▸</span> <?php echo $position; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            
            <div style="margin-top: 35px; padding: 30px; background: var(--secondary-black); border: 3px solid var(--border-gray); border-radius: 0px;">
                <h3 style="color: var(--accent-white); font-size: 2rem; margin-bottom: 20px; font-family: 'Bebas Neue', sans-serif; letter-spacing: 2px;">
                    SOCIAL CHAIR
                </h3>
                <p style="font-size: 1.15rem; line-height: 1.9; margin-bottom: 20px;">
                    As <strong style="color: var(--accent-white);">Social Chair</strong>, I specialize in 
                    <strong style="color: var(--accent-white);">event planning and organization</strong>, coordinating 
                    all aspects of our chapter's formal events. My responsibilities include:
                </p>
                <ul style="font-size: 1.1rem; line-height: 2; margin-left: 20px;">
                    <li style="color: var(--text-muted); margin-bottom: 12px;">
                        <span style="color: var(--accent-white);">▸</span> Leading a committee and coordinating team efforts for large-scale events
                    </li>
                    <li style="color: var(--text-muted); margin-bottom: 12px;">
                        <span style="color: var(--accent-white);">▸</span> Organizing and securing venues for chapter formal events
                    </li>
                    <li style="color: var(--text-muted); margin-bottom: 12px;">
                        <span style="color: var(--accent-white);">▸</span> Booking and coordinating with live bands and entertainment
                    </li>
                    <li style="color: var(--text-muted); margin-bottom: 12px;">
                        <span style="color: var(--accent-white);">▸</span> Creating custom banner artworks and event branding materials
                    </li>
                    <li style="color: var(--text-muted); margin-bottom: 12px;">
                        <span style="color: var(--accent-white);">▸</span> Managing event logistics, budgets, and timelines
                    </li>
                </ul>
            </div>

            <div style="margin-top: 35px; padding: 30px; background: var(--secondary-black); border: 3px solid var(--border-gray); border-radius: 0px;">
                <h3 style="color: var(--accent-white); font-size: 2rem; margin-bottom: 20px; font-family: 'Bebas Neue', sans-serif; letter-spacing: 2px;">
                    T-SHIRT CHAIR
                </h3>
                <p style="font-size: 1.15rem; line-height: 1.9; margin-bottom: 20px;">
                    For two years as <strong style="color: var(--accent-white);">T-Shirt Chair</strong>, I've designed and produced 
                    custom apparel for our chapter, collaborating with vendors to ensure quality and timely delivery. My responsibilities include:
                </p>
                <ul style="font-size: 1.1rem; line-height: 2; margin-left: 20px;">
                    <li style="color: var(--text-muted); margin-bottom: 12px;">
                        <span style="color: var(--accent-white);">▸</span> Designing custom t-shirts and apparel for chapter events and recruitment
                    </li>
                    <li style="color: var(--text-muted); margin-bottom: 12px;">
                        <span style="color: var(--accent-white);">▸</span> Managing vendor relationships and coordinating production timelines
                    </li>
                    <li style="color: var(--text-muted); margin-bottom: 12px;">
                        <span style="color: var(--accent-white);">▸</span> Creating designs that reflect chapter identity and event themes
                    </li>
                    <li style="color: var(--text-muted); margin-bottom: 12px;">
                        <span style="color: var(--accent-white);">▸</span> Ensuring quality control and timely delivery for all orders
                    </li>
                    <li style="color: var(--text-muted); margin-bottom: 12px;">
                        <span style="color: var(--accent-white);">▸</span> View the <a href="portfolio/tshirt-designs/" style="color: var(--accent-white); text-decoration: none; border-bottom: 3px solid var(--accent-white); font-weight: bold;">t-shirt design portfolio</a> to see selected work
                    </li>
                </ul>
            </div>
            <div style="margin-top: 35px; padding: 30px; background: var(--secondary-black); border: 3px solid var(--border-gray); border-radius: 0px;">
                <h3 style="color: var(--accent-white); font-size: 2rem; margin-bottom: 20px; font-family: 'Bebas Neue', sans-serif; letter-spacing: 2px;">
                    PHILANTHROPY CHAIR (EXECUTIVE COUNCIL)
                </h3>
                <p style="font-size: 1.15rem; line-height: 1.9; margin-bottom: 20px;">
                    As <strong style="color: var(--accent-white);">Philanthropy Chair</strong>, I coordinate initiatives supporting disability inclusion, 
                    partnering with organizations to raise funds and awareness. My responsibilities include:
                </p>
                <ul style="font-size: 1.1rem; line-height: 2; margin-left: 20px;">
                    <li style="color: var(--text-muted); margin-bottom: 12px;">
                        <span style="color: var(--accent-white);">▸</span> Leading partnerships with <strong style="color: var(--accent-white);">Unless U</strong> and <strong style="color: var(--accent-white);">The Ability Experience</strong>
                    </li>
                    <li style="color: var(--text-muted); margin-bottom: 12px;">
                        <span style="color: var(--accent-white);">▸</span> Planning and executing chapter-wide service and fundraising events
                    </li>
                    <li style="color: var(--text-muted); margin-bottom: 12px;">
                        <span style="color: var(--accent-white);">▸</span> Promoting disability inclusion initiatives within the campus community
                    </li>
                    <li style="color: var(--text-muted); margin-bottom: 12px;">
                        <span style="color: var(--accent-white);">▸</span> Coordinating volunteer efforts and tracking impact metrics
                    </li>
                    <li style="color: var(--text-muted); margin-bottom: 12px;">
                        <span style="color: var(--accent-white);">▸</span> Managing event logistics, volunteer scheduling, and donor relations
                    </li>
                </ul>
            </div>
        </div>
        </div>

        <!-- Contact Section -->
        <div class="content-section" id="contact">
            <h2>GET IN TOUCH</h2>
            <p style="font-size: 1.15rem; line-height: 1.9; margin-bottom: 40px;">
                Interested in collaborating on a project or want to learn more about my work? 
                Feel free to reach out!
            </p>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-bottom: 50px;">
                <!-- Contact Form -->
                <div style="background: var(--secondary-black); padding: 40px; border: 3px solid var(--border-gray); border-radius: 0px;">
                    <h3 style="color: var(--accent-white); font-size: 2rem; margin-bottom: 25px; font-family: 'Bebas Neue', sans-serif; letter-spacing: 2px;">
                        SEND ME A MESSAGE
                    </h3>
                    <form id="contactForm" method="post">
                        <div style="margin-bottom: 20px;">
                            <label style="display: block; color: var(--text-light); margin-bottom: 8px; font-weight: bold; letter-spacing: 1px;">YOUR NAME</label>
                            <input type="text" name="name" id="contactName" required 
                                   style="width: 100%; padding: 15px; background: var(--primary-black); border: 2px solid var(--border-gray); 
                                   color: var(--text-light); font-size: 1rem; border-radius: 0px; transition: border-color 0.3s;">
                        </div>
                        <div style="margin-bottom: 20px;">
                            <label style="display: block; color: var(--text-light); margin-bottom: 8px; font-weight: bold; letter-spacing: 1px;">YOUR EMAIL</label>
                            <input type="email" name="email" id="contactEmail" required 
                                   style="width: 100%; padding: 15px; background: var(--primary-black); border: 2px solid var(--border-gray); 
                                   color: var(--text-light); font-size: 1rem; border-radius: 0px; transition: border-color 0.3s;">
                        </div>
                        <div style="margin-bottom: 20px;">
                            <label style="display: block; color: var(--text-light); margin-bottom: 8px; font-weight: bold; letter-spacing: 1px;">MESSAGE</label>
                            <textarea name="message" id="contactMessage" rows="6" required 
                                      style="width: 100%; padding: 15px; background: var(--primary-black); border: 2px solid var(--border-gray); 
                                      color: var(--text-light); font-size: 1rem; border-radius: 0px; resize: vertical; transition: border-color 0.3s;"></textarea>
                        </div>
                        <div id="formMessage" style="margin-bottom: 15px; padding: 10px; display: none; border-radius: 0px; font-weight: bold; letter-spacing: 1px;"></div>
                        <button type="submit" id="submitBtn" class="btn" style="width: 100%; margin: 0;">SEND MESSAGE</button>
                    </form>
                </div>

                <!-- Contact Information -->
                <div>
                    <div style="background: var(--secondary-black); padding: 40px; border: 3px solid var(--border-gray); border-radius: 0px; margin-bottom: 30px;">
                        <h3 style="color: var(--accent-white); font-size: 2rem; margin-bottom: 25px; font-family: 'Bebas Neue', sans-serif; letter-spacing: 2px;">
                            CONTACT INFO
                        </h3>
                        <div style="margin-bottom: 20px;">
                            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 5px; letter-spacing: 1px;">EMAIL</p>
                            <a href="mailto:<?php echo $contact['email']; ?>" 
                               style="color: var(--accent-white); font-size: 1.1rem; text-decoration: none; border-bottom: 2px solid var(--accent-white);">
                                <?php echo $contact['email']; ?>
                            </a>
                        </div>
                        <div style="margin-bottom: 20px;">
                            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 5px; letter-spacing: 1px;">PHONE</p>
                            <a href="tel:+16159439722" 
                               style="color: var(--accent-white); font-size: 1.1rem; text-decoration: none; border-bottom: 2px solid var(--accent-white);">
                                <?php echo $contact['phone']; ?>
                            </a>
                        </div>
                        <div style="margin-bottom: 20px;">
                            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 5px; letter-spacing: 1px;">WEBSITE</p>
                            <a href="<?php echo $contact['website']; ?>" target="_blank" style="color: var(--accent-white); font-size: 1.05rem; text-decoration: none; border-bottom:2px solid var(--accent-white);">
                                jakebartoncreative.com
                            </a>
                        </div>
                        <div style="margin-bottom: 20px;">
                            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 5px; letter-spacing: 1px;">ADDRESS</p>
                            <p style="color: var(--accent-white); font-size: 1.1rem;">
                                <?php echo $contact['address']; ?>
                            </p>
                        </div>
                    </div>

                    <div style="background: var(--secondary-black); padding: 40px; border: 3px solid var(--border-gray); border-radius: 0px;">
                        <h3 style="color: var(--accent-white); font-size: 2rem; margin-bottom: 25px; font-family: 'Bebas Neue', sans-serif; letter-spacing: 2px;">
                            SOCIAL MEDIA
                        </h3>
                        <div style="margin-bottom: 20px;">
                            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 5px; letter-spacing: 1px;">LINKEDIN</p>
                            <a href="https://www.linkedin.com/in/jakebartoncreative" target="_blank" 
                               style="color: var(--accent-white); font-size: 1.1rem; text-decoration: none; border-bottom: 2px solid var(--accent-white);">
                                linkedin.com/in/jakebartoncreative
                            </a>
                        </div>
                        <?php if (!empty($contact['instagram'])): ?>
                        <div style="margin-bottom: 20px;">
                            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 5px; letter-spacing: 1px;">INSTAGRAM</p>
                            <a href="https://instagram.com/<?php echo $contact['instagram']; ?>" target="_blank" 
                               style="color: var(--accent-white); font-size: 1.1rem; text-decoration: none; border-bottom: 2px solid var(--accent-white);">
                                @<?php echo $contact['instagram']; ?>
                            </a>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($contact['github'])): ?>
                        <div style="margin-bottom: 20px;">
                            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 5px; letter-spacing: 1px;">GITHUB</p>
                            <a href="https://github.com/<?php echo $contact['github']; ?>" target="_blank" 
                               style="color: var(--accent-white); font-size: 1.1rem; text-decoration: none; border-bottom: 2px solid var(--accent-white);">
                                github.com/<?php echo $contact['github']; ?>
                            </a>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($contact['youtube'])): ?>
                        <div style="margin-bottom: 20px;">
                            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 5px; letter-spacing: 1px;">YOUTUBE</p>
                            <a href="https://youtube.com/@<?php echo $contact['youtube']; ?>" target="_blank" 
                               style="color: var(--accent-white); font-size: 1.1rem; text-decoration: none; border-bottom: 2px solid var(--accent-white);">
                                youtube.com/@<?php echo $contact['youtube']; ?>
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="content-section" style="text-align: center; padding: 80px;">
            <h2 style="margin-bottom: 25px;">READY TO SEE WHAT I'VE CREATED?</h2>
            <p style="font-size: 1.2rem; margin-bottom: 40px;">
                Explore my portfolio featuring game projects, 3D artwork, and design work.
            </p>
            <a href="portfolio/" class="btn">VIEW PORTFOLIO</a>
        </div>
    </div>

    <style>
        /* Additional Hero Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Card Stack Carousel - Silky Smooth */
        .carousel-wrapper {
            position: relative;
            width: 100%;
            max-width: 700px;
            height: 500px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .carousel-track {
            position: relative;
            width: 100%;
            height: 100%;
            transform: translateZ(0);
            perspective: 1000px;
        }

        .carousel-slide {
            position: absolute;
            width: 100%;
            max-width: 600px;
            height: 450px;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            transition: transform 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94), 
                        opacity 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            will-change: transform;
            backface-visibility: hidden;
            transform-style: preserve-3d;
            -webkit-font-smoothing: subpixel-antialiased;
        }

        /* Active card - front and center */
        .carousel-slide.position-0 {
            transform: translate(-50%, -50%) translate3d(0, 0, 0) scale(1) rotateY(0deg);
            opacity: 1;
            z-index: 30;
        }

        /* First card behind - stacked right */
        .carousel-slide.position-1 {
            transform: translate(-50%, -50%) translate3d(30px, 15px, -50px) scale(0.94) rotateY(-5deg);
            opacity: 0.7;
            z-index: 20;
            pointer-events: none;
        }

        /* Second card behind - more stacked */
        .carousel-slide.position-2 {
            transform: translate(-50%, -50%) translate3d(60px, 30px, -100px) scale(0.88) rotateY(-10deg);
            opacity: 0.4;
            z-index: 10;
            pointer-events: none;
        }

        /* Cards exiting to the left */
        .carousel-slide.position-exit {
            transform: translate(-50%, -50%) translate3d(-120%, -20px, -150px) scale(0.85) rotateY(15deg);
            opacity: 0;
            z-index: 5;
            pointer-events: none;
        }

        /* Cards entering from the right */
        .carousel-slide.position-enter {
            transform: translate(-50%, -50%) translate3d(120%, 40px, -150px) scale(0.82) rotateY(-15deg);
            opacity: 0;
            z-index: 1;
            pointer-events: none;
        }

        .carousel-card {
            background: var(--secondary-black);
            border: 2px solid var(--border-gray);
            border-radius: 12px;
            padding: 40px;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.6);
            transition: border-color 0.4s ease, box-shadow 0.4s ease;
            transform: translateZ(0);
        }

        .carousel-slide.position-0 .carousel-card {
            border-color: var(--accent-white);
            box-shadow: 0 12px 40px rgba(255, 255, 255, 0.15);
        }

        .carousel-card img {
            width: 100%;
            max-width: 450px;
            height: 280px;
            object-fit: contain;
            margin-bottom: 30px;
            border-radius: 8px;
            transform: translateZ(0);
        }

        .carousel-info {
            text-align: center;
            width: 100%;
        }

        .carousel-info h3 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2.2rem;
            letter-spacing: 2px;
            color: var(--accent-white);
            margin-bottom: 10px;
        }

        .carousel-info p {
            color: var(--text-muted);
            font-size: 1.15rem;
            letter-spacing: 1px;
        }

        /* Hide dots */
        .carousel-dots {
            display: none;
        }

        /* Responsive adjustments */
        @media (max-width: 1024px) {
            .carousel-wrapper {
                max-width: 600px;
                height: 450px;
            }

            .carousel-slide {
                max-width: 520px;
                height: 400px;
            }
            
            .carousel-card {
                padding: 30px;
            }
            
            .carousel-card img {
                height: 240px;
                max-width: 380px;
            }
            
            .carousel-info h3 {
                font-size: 1.9rem;
            }

            .carousel-slide.position-1 {
                transform: translate(-50%, -50%) translate3d(25px, 12px, -50px) scale(0.95) rotateY(-4deg);
            }

            .carousel-slide.position-2 {
                transform: translate(-50%, -50%) translate3d(50px, 24px, -100px) scale(0.90) rotateY(-8deg);
            }
        }

        @media (max-width: 768px) {
            .carousel-wrapper {
                max-width: 100%;
                height: 420px;
                padding: 0 20px;
            }

            .carousel-slide {
                max-width: 100%;
                height: 380px;
                transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94), 
                            opacity 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            }

            .carousel-card {
                padding: 25px;
            }

            .carousel-card img {
                height: 220px;
                max-width: 320px;
            }

            .carousel-info h3 {
                font-size: 1.6rem;
            }

            .carousel-info p {
                font-size: 1rem;
            }

            .carousel-slide.position-1 {
                transform: translate(-50%, -50%) translate3d(20px, 10px, -30px) scale(0.94) rotateY(-3deg);
            }

            .carousel-slide.position-2 {
                transform: translate(-50%, -50%) translate3d(40px, 20px, -60px) scale(0.88) rotateY(-6deg);
            }

            .carousel-slide.position-exit {
                transform: translate(-50%, -50%) translate3d(-100%, -15px, -90px) scale(0.85) rotateY(10deg);
            }

            .carousel-slide.position-enter {
                transform: translate(-50%, -50%) translate3d(100%, 30px, -90px) scale(0.82) rotateY(-10deg);
            }
        }

        /* Contact Form Styles */
        input:focus, textarea:focus {
            outline: none;
            border-color: var(--accent-white) !important;
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.2);
        }

        input:hover, textarea:hover {
            border-color: var(--text-muted) !important;
        }

        /* Contact Links Hover Effects */
        #contact a {
            transition: all 0.3s ease;
        }

        #contact a:hover {
            color: var(--text-light) !important;
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
            border-bottom-color: var(--text-light) !important;
        }

        /* Responsive Contact Grid */
        @media (max-width: 768px) {
            #contact > div:nth-child(3) {
                grid-template-columns: 1fr !important;
            }
        }
    </style>

    <script>
        // Fallback mobile nav (in case effects.js fails to load)
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.querySelector('.nav-toggle');
            const menu = document.getElementById('primary-menu');
            if (!btn || !menu) return;
            if (!menu.classList.contains('open')) menu.classList.remove('open');
            btn.addEventListener('click', function(e){
                const expanded = btn.getAttribute('aria-expanded') === 'true';
                btn.setAttribute('aria-expanded', expanded ? 'false' : 'true');
                menu.classList.toggle('open');
            });
            // Handle link clicks through event delegation
            menu.addEventListener('click', function(e) {
                const link = e.target.closest('a');
                if (link && link.href) {
                    e.preventDefault();
                    menu.classList.remove('open');
                    btn.setAttribute('aria-expanded', 'false');
                    setTimeout(() => {
                        if (link.hasAttribute('download')) {
                            const tempLink = document.createElement('a');
                            tempLink.href = link.href;
                            tempLink.download = link.getAttribute('download') || '';
                            document.body.appendChild(tempLink);
                            tempLink.click();
                            document.body.removeChild(tempLink);
                        } else {
                            window.location.href = link.href;
                        }
                    }, 150);
                }
            });
        });
        // Card Stack Carousel - Enhanced Shifting
        class Carousel {
            constructor() {
                this.slides = Array.from(document.querySelectorAll('.carousel-slide'));
                this.currentIndex = 0;
                this.totalSlides = this.slides.length;
                this.autoplayInterval = null;
                
                if (this.totalSlides === 0) return;
                
                this.init();
            }

            init() {
                // Set initial positions
                this.updatePositions();
                
                // Start autoplay
                this.startAutoplay();
            }

            updatePositions() {
                this.slides.forEach((slide, index) => {
                    // Calculate relative position
                    let relativePos = (index - this.currentIndex + this.totalSlides) % this.totalSlides;
                    
                    // Remove all position classes
                    slide.className = 'carousel-slide';
                    
                    // Assign position class with more states
                    if (relativePos === 0) {
                        slide.classList.add('position-0'); // Active center
                    } else if (relativePos === 1) {
                        slide.classList.add('position-1'); // First behind
                    } else if (relativePos === 2) {
                        slide.classList.add('position-2'); // Second behind
                    } else if (relativePos === this.totalSlides - 1) {
                        slide.classList.add('position-exit'); // Exiting to left
                    } else {
                        slide.classList.add('position-enter'); // Entering from right
                    }
                });
            }

            next() {
                this.currentIndex = (this.currentIndex + 1) % this.totalSlides;
                this.updatePositions();
            }

            startAutoplay() {
                this.autoplayInterval = setInterval(() => this.next(), 3500);
            }

            stopAutoplay() {
                if (this.autoplayInterval) {
                    clearInterval(this.autoplayInterval);
                    this.autoplayInterval = null;
                }
            }
        }

                // Initialize
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => new Carousel());
        } else {
            new Carousel();
        }

        // Contact Form Handler
        document.getElementById('contactForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submitBtn');
            const formMessage = document.getElementById('formMessage');
            const formData = new FormData(this);
            
            // Disable button and show loading
            submitBtn.disabled = true;
            submitBtn.textContent = 'SENDING...';
            formMessage.style.display = 'none';
            
            try {
                const response = await fetch('contact-handler.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Success message
                    formMessage.textContent = data.message;
                    formMessage.style.background = 'rgba(0, 255, 0, 0.1)';
                    formMessage.style.border = '2px solid #00ff00';
                    formMessage.style.color = '#00ff00';
                    formMessage.style.display = 'block';
                    
                    // Reset form
                    document.getElementById('contactForm').reset();
                } else {
                    // Error message
                    formMessage.textContent = data.errors.join(', ');
                    formMessage.style.background = 'rgba(255, 0, 0, 0.1)';
                    formMessage.style.border = '2px solid #ff0000';
                    formMessage.style.color = '#ff0000';
                    formMessage.style.display = 'block';
                }
            } catch (error) {
                // Network error
                formMessage.textContent = 'Error sending message. Please try emailing directly.';
                formMessage.style.background = 'rgba(255, 0, 0, 0.1)';
                formMessage.style.border = '2px solid #ff0000';
                formMessage.style.color = '#ff0000';
                formMessage.style.display = 'block';
            }
            
            // Re-enable button
            submitBtn.disabled = false;
            submitBtn.textContent = 'SEND MESSAGE';
        });
    </script>
</body>
</html>
```