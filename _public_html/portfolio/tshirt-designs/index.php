<?php
// T-Shirt Design Portfolio
$pageTitle = "T-Shirt Designs Portfolio";

$designs = [
    ['id'=>1,'title'=>"Fall Recruitment 2025 - Design 1",'description'=>'Custom recruitment t-shirt design for Pi Kappa Phi Fall 2025','year'=>'2025','thumbnail'=>"images/thumbnails/Fall Recruitment '25-01.svg",'full'=>"images/full/Fall Recruitment '25-01.svg"],
    ['id'=>2,'title'=>"Fall Recruitment 2025 - Design 2",'description'=>'Custom recruitment t-shirt design for Pi Kappa Phi Fall 2025','year'=>'2025','thumbnail'=>"images/thumbnails/Fall Recruitment '25-02.svg",'full'=>"images/full/Fall Recruitment '25-02.svg"],
    ['id'=>3,'title'=>'Barn Bash 2025','description'=>'Custom event t-shirt design for Barn Bash 2025','year'=>'2025','thumbnail'=>'images/thumbnails/Barn Bash 2025.svg','full'=>'images/full/Barn Bash 2025.svg'],
    ['id'=>4,'title'=>'Southern Gents - Design 1','description'=>'Custom t-shirt design based on album cover - Southern Gents collection','year'=>'2025','thumbnail'=>'images/thumbnails/SouthernGents-01.svg','full'=>'images/full/SouthernGents/SouthernGents-01.svg'],
    ['id'=>8,'title'=>'Southern Gents - Design 5','description'=>'Custom t-shirt design based on album cover - Southern Gents collection','year'=>'2025','thumbnail'=>'images/thumbnails/SouthernGents_Artboard 1-05.svg','full'=>'images/full/SouthernGents/SouthernGents_Artboard 1-05.svg'],
    ['id'=>9,'title'=>'Southern Gents 2024','description'=>'Custom t-shirt design based on album cover - Southern Gents collection','year'=>'2024','thumbnail'=>'images/thumbnails/Southern Gents 2024.svg','full'=>'images/full/SouthernGents/Southern Gents 2024.svg'],
    ['id'=>10,'title'=>'Barn Bash 2024','description'=>'Custom event t-shirt design for Barn Bash 2024','year'=>'2024','thumbnail'=>'images/thumbnails/Barn Bash 2024.svg','full'=>'images/full/Barn Bash 2024.svg'],
    ['id'=>11,'title'=>'Caribbean Party','description'=>'Custom event t-shirt design for Caribbean themed party','year'=>'2024','thumbnail'=>'images/thumbnails/Caribbean Party.svg','full'=>'images/full/Caribbean Party.svg'],
    ['id'=>12,'title'=>'Rose Ball','description'=>'Custom formal event t-shirt design for Rose Ball','year'=>'2024','thumbnail'=>'images/thumbnails/Rose Ball.svg','full'=>'images/full/RoseBall/Rose Ball.svg'],
    ['id'=>13,'title'=>'PGA Polo','description'=>'Custom polo shirt design for Pi Kappa Phi','year'=>'2024','thumbnail'=>'images/thumbnails/PGA Polo.svg','full'=>'images/full/PGA Polo.svg'],
    ['id'=>14,'title'=>'Samford Film Club','description'=>'Custom t-shirt design for Samford Film Club','year'=>'2024','thumbnail'=>'images/thumbnails/Samford Film.svg','full'=>'images/full/Samford Film.svg'],
];

$years = array_unique(array_column($designs, 'year'));
sort($years);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - Jake Barton</title>
    <link rel="stylesheet" href="../../assets/css/base.css">
    <link rel="stylesheet" href="../../assets/css/animations.css">
    <link rel="stylesheet" href="../../assets/css/components.css">
    <style>
        .filter-controls { display: flex; gap: 12px; flex-wrap: wrap; justify-content: center; margin-bottom: 40px; }
        .filter-btn { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.12); color: var(--text-muted); padding: 10px 24px; border-radius: 100px; cursor: pointer; font-family: var(--font-body); font-size: 0.9rem; transition: all 0.3s ease; }
        .filter-btn:hover, .filter-btn.active { background: var(--accent); border-color: var(--accent); color: #fff; }
        .gallery-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 24px; }
        .gallery-item { background: var(--bg-card); border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; overflow: hidden; cursor: pointer; transition: all 0.35s ease; }
        .gallery-item:hover { transform: translateY(-6px); border-color: var(--accent); box-shadow: 0 12px 40px rgba(255,255,255,0.08); }
        .gallery-item img { width: 100%; aspect-ratio: 1/1; object-fit: contain; background: #111; padding: 16px; display: block; }
        .gallery-item-info { padding: 16px 20px; }
        .gallery-item-info h3 { font-size: 1rem; font-weight: 600; margin-bottom: 4px; }
        .gallery-item-info p { color: var(--text-muted); font-size: 0.85rem; }
        .modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.9); z-index: 1000; align-items: center; justify-content: center; }
        .modal.open { display: flex; }
        .modal-content { background: var(--bg-card); border: 1px solid rgba(255,255,255,0.12); border-radius: 20px; max-width: 90vw; max-height: 90vh; overflow: hidden; display: grid; grid-template-columns: 1fr 340px; }
        .modal-content img { max-height: 80vh; width: 100%; object-fit: contain; background: #111; padding: 24px; }
        .modal-info { padding: 40px; display: flex; flex-direction: column; justify-content: center; }
        .modal-info h2 { font-size: 1.8rem; margin-bottom: 12px; }
        .modal-info p { color: var(--text-muted); margin-bottom: 8px; }
        .close { position: absolute; top: 20px; right: 28px; font-size: 2rem; cursor: pointer; color: var(--text-muted); }
        .close:hover { color: var(--accent); }
        .modal-nav { position: absolute; top: 50%; transform: translateY(-50%); font-size: 2rem; cursor: pointer; color: var(--text-muted); padding: 12px; background: rgba(0,0,0,0.5); border-radius: 50%; transition: color 0.2s; }
        .modal-nav:hover { color: var(--accent); }
        .modal-prev { left: 20px; }
        .modal-next { right: 20px; }
        @media (max-width: 768px) {
            .modal-content { grid-template-columns: 1fr; }
            .modal-content img { max-height: 50vh; }
        }
    </style>
</head>
<body>
  <!-- Scroll progress line -->
  <div id="scroll-progress" style="position:fixed;top:0;left:0;height:2px;width:0%;background:var(--accent);z-index:100001;transition:width 0.1s linear;pointer-events:none"></div>

  <!-- Ambient cursor glow -->
  <div id="cursor-glow" style="position:fixed;top:0;left:0;width:420px;height:420px;border-radius:50%;background:radial-gradient(circle,rgba(255,255,255,0.05) 0%,transparent 70%);pointer-events:none;z-index:0;transform:translate(-50%,-50%);transition:opacity 0.3s ease;opacity:0"></div>  </div>


    <header class="site-nav" id="site-nav">
        <a href="../../index.php" class="nav-logo">JB</a>
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
                <p class="eyebrow hero-eyebrow">Portfolio → T-Shirt Designs</p>
                <h1 class="reveal" style="font-family:var(--font-display);font-size:clamp(2.5rem,6vw,4.5rem);font-weight:700;letter-spacing:-0.02em;line-height:1.1;color:var(--text);margin-bottom:1rem">T-Shirt Designs</h1>
                <div class="divider reveal" style="margin:1.5rem auto;max-width:80px"></div>
                <p class="reveal" style="color: var(--text-muted); font-size: 1.2rem; margin-top: 1.5rem;transition-delay:0.12s">
                    Custom apparel designs created as <strong style="color: var(--accent);">T-Shirt Chair</strong>
                    for Pi Kappa Phi Fraternity — Alpha Eta Chapter.
                </p>
            </div>
        </section>

        <section class="section-sm">
            <div style="max-width: 1200px; margin: 0 auto; padding: 0 var(--container-pad);">
                <div class="section-header reveal" style="margin-bottom:2rem;text-align:center">
                    <span class="eyebrow">Gallery</span>
                    <h2>All Designs</h2>
                </div>
                <div class="filter-controls stagger-pop reveal">
                    <button class="filter-btn active" data-filter="all">All Designs</button>
                    <?php foreach ($years as $year): ?>
                        <button class="filter-btn" data-filter="<?php echo $year; ?>"><?php echo $year; ?></button>
                    <?php endforeach; ?>
                </div>

                <div class="gallery-grid stagger-children">
                    <?php foreach ($designs as $design): ?>
                        <div class="gallery-item"
                             data-title="<?php echo htmlspecialchars($design['title']); ?>"
                             data-description="<?php echo htmlspecialchars($design['description']); ?>"
                             data-year="<?php echo $design['year']; ?>"
                             data-full="<?php echo $design['full']; ?>">
                            <img src="<?php echo $design['thumbnail']; ?>"
                                 alt="<?php echo htmlspecialchars($design['title']); ?>"
                                 loading="lazy">
                            <div class="gallery-item-info">
                                <h3><?php echo htmlspecialchars($design['title']); ?></h3>
                                <p><?php echo $design['year']; ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <p class="reveal" style="text-align: center; color: var(--text-muted); margin-top: 40px;">
                    Click any design to view full size. More designs added as they are created!
                </p>
            </div>
        </section>

        <section class="section-sm" style="text-align: center;">
            <a href="../" class="btn-secondary magnetic reveal">← Back to Portfolio</a>
        </section>

    </main>

    <!-- Modal -->
    <div id="designModal" class="modal" role="dialog" aria-modal="true">
        <span class="close" id="modalClose">&times;</span>
        <span class="modal-nav modal-prev" id="modalPrev">&#10094;</span>
        <span class="modal-nav modal-next" id="modalNext">&#10095;</span>
        <div class="modal-content">
            <img id="modalImage" src="" alt="">
            <div class="modal-info">
                <p class="eyebrow" id="modalYear"></p>
                <h2 id="modalTitle"></h2>
                <p id="modalDescription"></p>
            </div>
        </div>
    </div>

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
    <script src="../../assets/js/gallery.js"></script>
    <script>
      (function(){var b=document.getElementById('scroll-progress');if(!b)return;window.addEventListener('scroll',function(){var s=window.scrollY,t=document.documentElement.scrollHeight-window.innerHeight;b.style.width=(t>0?(s/t)*100:0)+'%';},{passive:true});})();
      (function(){var g=document.getElementById('cursor-glow');if(!g||window.matchMedia('(pointer:coarse)').matches)return;var cx=0,cy=0,tx=0,ty=0;document.addEventListener('mousemove',function(e){tx=e.clientX;ty=e.clientY;g.style.opacity='1';});document.addEventListener('mouseleave',function(){g.style.opacity='0';});function lerp(a,b,t){return a+(b-a)*t;}(function loop(){cx=lerp(cx,tx,0.07);cy=lerp(cy,ty,0.07);g.style.left=cx+'px';g.style.top=cy+'px';requestAnimationFrame(loop);})();})();
    </script>

</body>
</html>
