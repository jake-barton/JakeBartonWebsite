<?php
// Games Portfolio
$pageTitle = "Game Projects";

// Games data
$games = [
    [
        'id' => 1,
        'title' => 'Phase Runner',
        'description' => 'A fast-paced endless runner where you phase through dimensions to avoid obstacles. Built with Godot Engine.',
        'year' => '2024',
        'tech' => ['Godot', 'GDScript', 'Web Export'],
        'thumbnail' => 'phase-runner/PhaseRunnerWeb.png',
        'playLink' => 'phase-runner/',
        'controls' => 'Keyboard: Arrow Keys or WASD to move'
    ],
    [
        'id' => 2,
        'title' => 'Captain\'s Log',
        'description' => 'Pixel art tileset and sprite design for a retro-style adventure game. Custom hand-crafted tiles and character sprites.',
        'year' => '2024',
        'tech' => ['Pixel Art', 'Game Design', 'Sprites & Tiles'],
        'thumbnail' => '../captainslogtruetiles.png',
        'playLink' => null,
        'isArt' => true
    ],
    // Add more games here as you create them
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - Jake Barton</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Oswald:wght@400;700&display=swap" rel="stylesheet">
    <script src="../../assets/js/effects.js" defer></script>
    <style>
        .game-card {
            background: var(--secondary-black);
            border: 3px solid var(--border-gray);
            padding: 0;
            margin-bottom: 40px;
            transition: all 0.3s ease;
            display: grid;
            grid-template-columns: 400px 1fr;
            gap: 0;
        }

        .game-card:hover {
            border-color: var(--accent-white);
            transform: translateY(-5px);
            box-shadow: 0 10px 40px rgba(255, 255, 255, 0.1);
        }

        .game-thumbnail {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-right: 3px solid var(--border-gray);
            filter: grayscale(100%);
            transition: filter 0.3s ease;
        }

        .game-card:hover .game-thumbnail {
            filter: grayscale(0%);
        }

        .game-info {
            padding: 40px;
        }

        .game-info h3 {
            font-size: 2.5rem;
            margin-bottom: 15px;
            font-family: 'Bebas Neue', sans-serif;
            letter-spacing: 2px;
        }

        .game-meta {
            color: var(--text-muted);
            margin-bottom: 20px;
            font-size: 0.9rem;
        }

        .tech-tags {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin: 20px 0;
        }

        .tech-tag {
            background: var(--primary-black);
            border: 2px solid var(--border-gray);
            padding: 8px 16px;
            font-size: 0.85rem;
            font-weight: bold;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        .tech-tag:hover {
            border-color: var(--accent-white);
            color: var(--accent-white);
        }

        .game-controls {
            background: var(--primary-black);
            padding: 15px;
            margin-top: 20px;
            border: 2px solid var(--border-gray);
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        .game-controls strong {
            color: var(--accent-white);
        }

        @media (max-width: 768px) {
            .game-card {
                grid-template-columns: 1fr;
            }

            .game-thumbnail {
                height: 250px;
                border-right: none;
                border-bottom: 3px solid var(--border-gray);
            }

            .game-info {
                padding: 25px;
            }
        }
    </style>
    
    <script>
        // Mobile nav toggle
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.querySelector('.nav-toggle');
            const menu = document.getElementById('primary-menu');
            if (!btn || !menu) return;
            
            btn.addEventListener('click', function(e){
                e.stopPropagation();
                const expanded = btn.getAttribute('aria-expanded') === 'true';
                btn.setAttribute('aria-expanded', expanded ? 'false' : 'true');
                menu.classList.toggle('open');
            });
            
            // Close menu when clicking outside
            document.addEventListener('click', function(e) {
                if (menu.classList.contains('open') && !menu.contains(e.target) && !btn.contains(e.target)) {
                    menu.classList.remove('open');
                    btn.setAttribute('aria-expanded', 'false');
                }
            });
            
            // Close on Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && menu.classList.contains('open')) {
                    menu.classList.remove('open');
                    btn.setAttribute('aria-expanded', 'false');
                }
            });
        });
    </script>
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
                <li><a href="../professional-works/">Professional Works</a></li>
                <li><a href="./">Games</a></li>
                <li><a href="../tshirt-designs/">T-Shirt Designs</a></li>
                <li><a href="../../assets/Jake%20Barton%20-%20Resume.pdf" download>Resume</a></li>
                <li class="mobile-visible"><a href="../../index.php#contact">Contact</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <div class="content-section" style="text-align: center; padding: 80px;">
            <h1 style="font-size: 4.5rem;">GAME PROJECTS</h1>
            <p style="font-size: 1.2rem; color: var(--text-muted); margin-top: 25px; max-width: 900px; margin-left: auto; margin-right: auto;">
                Interactive experiences built with <strong style="color: var(--accent-white); font-family: 'Bebas Neue', sans-serif; letter-spacing: 2px;">GODOT ENGINE</strong>, 
                <strong style="color: var(--accent-white); font-family: 'Bebas Neue', sans-serif; letter-spacing: 2px;">UNREAL ENGINE 5</strong>, 
                and <strong style="color: var(--accent-white); font-family: 'Bebas Neue', sans-serif; letter-spacing: 2px;">UNITY</strong>. 
                Click to play directly in your browser!
            </p>
        </div>

        <div class="content-section">
            <?php foreach ($games as $game): ?>
                <div class="game-card">
                    <img src="<?php echo $game['thumbnail']; ?>" 
                         alt="<?php echo htmlspecialchars($game['title']); ?>"
                         class="game-thumbnail">
                    <div class="game-info">
                        <h3><?php echo strtoupper(htmlspecialchars($game['title'])); ?></h3>
                        <p class="game-meta">
                            <strong style="color: var(--accent-white);">YEAR:</strong> <?php echo $game['year']; ?>
                        </p>
                        <p style="font-size: 1.1rem; line-height: 1.8; margin-bottom: 20px;">
                            <?php echo htmlspecialchars($game['description']); ?>
                        </p>
                        
                        <div class="tech-tags">
                            <?php foreach ($game['tech'] as $tech): ?>
                                <span class="tech-tag"><?php echo strtoupper($tech); ?></span>
                            <?php endforeach; ?>
                        </div>

                        <?php if (!empty($game['controls'])): ?>
                        <div class="game-controls">
                            <strong>CONTROLS:</strong> <?php echo $game['controls']; ?>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($game['playLink'])): ?>
                        <a href="<?php echo $game['playLink']; ?>" class="btn" style="margin-top: 25px; display: inline-block;">
                            PLAY NOW
                        </a>
                        <?php elseif (isset($game['isArt']) && $game['isArt']): ?>
                        <span class="btn" style="margin-top: 25px; display: inline-block; opacity: 0.8; cursor: default;">
                            GAME ART
                        </span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="content-section" style="text-align: center;">
            <p style="color: var(--text-muted); font-size: 1.1rem;">
                More games coming soon! Check back regularly for new projects.
            </p>
        </div>
    </div>
    
    <script>
        // Mobile nav toggle
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.querySelector('.nav-toggle');
            const menu = document.getElementById('primary-menu');
            if (!btn || !menu) return;
            
            btn.addEventListener('click', function(e){
                e.stopPropagation();
                const expanded = btn.getAttribute('aria-expanded') === 'true';
                btn.setAttribute('aria-expanded', expanded ? 'false' : 'true');
                menu.classList.toggle('open');
            });
            
            // Close menu when clicking outside
            document.addEventListener('click', function(e) {
                if (menu.classList.contains('open') && !menu.contains(e.target) && !btn.contains(e.target)) {
                    menu.classList.remove('open');
                    btn.setAttribute('aria-expanded', 'false');
                }
            });
            
            // Close on Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && menu.classList.contains('open')) {
                    menu.classList.remove('open');
                    btn.setAttribute('aria-expanded', 'false');
                }
            });
        });
    </script>
</body>
</html>
