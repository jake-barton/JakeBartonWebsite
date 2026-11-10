<?php
require_once __DIR__ . '/includes/content.php';

function h($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function plain($value) {
    return html_entity_decode(strip_tags((string) $value), ENT_QUOTES, 'UTF-8');
}

function split_letters($text) {
    $parts = preg_split('/(\s+)/u', $text, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
    $out = '';
    $color = 1;
    $index = 0;

    foreach ($parts as $part) {
        if (preg_match('/^\s+$/u', $part)) {
            $out .= '<span class="jb-word-space">&nbsp;</span>';
            continue;
        }

        $chars = preg_split('//u', $part, -1, PREG_SPLIT_NO_EMPTY);
        $out .= '<span class="jb-word">';
        foreach ($chars as $char) {
            $class = 'tone-' . $color;
            $out .= '<span class="jb-letter ' . $class . '" style="--i: ' . h((string) $index) . '">' . h($char) . '</span>';
            $color = $color === 5 ? 1 : $color + 1;
            $index++;
        }
        $out .= '</span>';
    }

    return $out;
}

$contact = [
    'email'     => $content['email'],
    'website'   => $content['website'],
    'instagram' => $content['instagram'],
    'github'    => $content['github'],
    'linkedin'  => $content['linkedin'],
];

$menu_items = [
    ['label' => 'Home',    'url' => '#home',                        'current' => true],
    ['label' => 'Work',    'url' => '/work/',                       'parent' => true],
    ['label' => 'Games',   'url' => '/work/',                       'child' => true],
    ['label' => 'Art',     'url' => '/work/',                       'child' => true],
    ['label' => 'Web',     'url' => '/work/',                       'child' => true],
    ['label' => 'About',   'url' => '#about',                       'parent' => true],
    ['label' => 'Contact', 'url' => '#contact',                     'parent' => true],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo h(plain($content['hero_subtitle'])); ?>">
    <meta name="author" content="<?php echo h($content['name']); ?>">
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo h($content['title']); ?>">
    <meta property="og:description" content="<?php echo h(plain($content['hero_subtitle'])); ?>">
    <meta property="og:image" content="assets/brand/og-image.jpg">
    <title><?php echo h($content['title']); ?></title>
    <link rel="icon" type="image/svg+xml" href="assets/brand/favicon.svg?v=20260714">
    <link rel="stylesheet" href="assets/css/base.css?v=20261109-helvetica">
    <link rel="stylesheet" href="assets/css/animations.css">
    <link rel="stylesheet" href="assets/css/components.css?v=20261110-sidebar-icon">
    <link rel="stylesheet" href="assets/css/work.css?v=20260714-global-page-loader">
<link rel="stylesheet" href="/assets/css/site-reel.css?v=20261109-player">
    <link rel="stylesheet" href="assets/css/home.css?v=20261110-intro-aligned">
    <link rel="stylesheet" href="/assets/css/typography.css?v=20261109-footer-headings">
</head>
<body class="jb-home jb-home-page">
    <div class="jb-work-loader" id="pageLoader" aria-label="Loading page" aria-live="polite">
        <div class="jb-work-loader-curtains" aria-hidden="true">
            <span></span>
            <span></span>
            <span></span>
            <span class="jb-work-loader-primary">
                <span class="jb-work-loader-name">
                    <b class="jb-work-name-dark">Jake Barton</b>
                    <b class="jb-work-name-white">Jake Barton</b>
                </span>
            </span>
        </div>
    </div>

    <nav class="jb-rail" id="jbRail" aria-label="Primary navigation">
        <button class="jb-menu-button" id="jbMenuButton" type="button" aria-expanded="false" aria-controls="jbMegaMenu">
            <img src="assets/brand/jb-logo.png" alt="Open menu">
            <span class="jb-menu-text">Open/Close</span>
        </button>

        <a href="#home" class="jb-rail-wordmark" aria-label="Jake Barton home">Jake Barton</a>
        <?php require __DIR__ . '/includes/rail-resume.php'; ?>

        <ul class="jb-rail-socials" aria-label="Social links">
            <li>
                <a href="https://www.linkedin.com/in/<?php echo h($contact['linkedin']); ?>" target="_blank" rel="noopener" aria-label="LinkedIn">
                    <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                        <path d="M4.98 3.5C4.98 4.88 3.86 6 2.5 6S0 4.88 0 3.5 1.12 1 2.5 1s2.48 1.12 2.48 2.5ZM.36 8.2h4.28V23H.36V8.2Zm7.08 0h4.1v2.02h.06c.57-1.08 1.97-2.22 4.05-2.22 4.34 0 5.14 2.86 5.14 6.57V23h-4.28v-7.48c0-1.78-.03-4.08-2.49-4.08-2.49 0-2.87 1.94-2.87 3.95V23H7.44V8.2Z"/>
                    </svg>
                </a>
            </li>
            <li>
                <a href="https://github.com/<?php echo h($contact['github']); ?>" target="_blank" rel="noopener" aria-label="GitHub">
                    <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                        <path d="M12 .5C5.65.5.5 5.65.5 12c0 5.08 3.29 9.39 7.86 10.91.58.1.79-.25.79-.56v-2.14c-3.2.7-3.87-1.36-3.87-1.36-.52-1.33-1.28-1.68-1.28-1.68-1.04-.71.08-.7.08-.7 1.15.08 1.76 1.18 1.76 1.18 1.03 1.75 2.69 1.25 3.35.95.1-.74.4-1.25.73-1.54-2.56-.29-5.25-1.28-5.25-5.7 0-1.26.45-2.29 1.18-3.09-.12-.29-.51-1.47.11-3.05 0 0 .96-.31 3.16 1.18.92-.26 1.9-.38 2.88-.39.98.01 1.96.13 2.88.39 2.2-1.49 3.16-1.18 3.16-1.18.62 1.58.23 2.76.11 3.05.74.8 1.18 1.83 1.18 3.09 0 4.43-2.7 5.41-5.27 5.69.42.36.78 1.06.78 2.14v3.17c0 .31.21.67.8.56A11.51 11.51 0 0 0 23.5 12C23.5 5.65 18.35.5 12 .5Z"/>
                    </svg>
                </a>
            </li>
            <li>
                <a href="https://instagram.com/<?php echo h($contact['instagram']); ?>" target="_blank" rel="noopener" aria-label="Instagram">
                    <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                        <path d="M7.5 2h9A5.51 5.51 0 0 1 22 7.5v9a5.51 5.51 0 0 1-5.5 5.5h-9A5.51 5.51 0 0 1 2 16.5v-9A5.51 5.51 0 0 1 7.5 2Zm0 2A3.5 3.5 0 0 0 4 7.5v9A3.5 3.5 0 0 0 7.5 20h9a3.5 3.5 0 0 0 3.5-3.5v-9A3.5 3.5 0 0 0 16.5 4h-9ZM12 7a5 5 0 1 1 0 10 5 5 0 0 1 0-10Zm0 2a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm5.25-2.35a1.1 1.1 0 1 1 0 2.2 1.1 1.1 0 0 1 0-2.2Z"/>
                    </svg>
                </a>
            </li>
        </ul>

        <section class="jb-mega-menu" id="jbMegaMenu" aria-label="Menu">
            <div class="jb-mega-links">
                <?php foreach ($menu_items as $i => $item): ?>
                    <?php
                    $menu_classes = ['jb-mega-link'];
                    if (!empty($item['current'])) $menu_classes[] = 'is-current';
                    if (!empty($item['parent'])) $menu_classes[] = 'is-parent';
                    if (!empty($item['child'])) $menu_classes[] = 'is-child';
                    ?>
                    <a href="<?php echo h($item['url']); ?>"
                       class="<?php echo h(implode(' ', $menu_classes)); ?>"
                       style="--delay: <?php echo h((string) ($i * 0.11)); ?>s"
                       <?php echo !empty($item['current']) ? 'aria-current="page"' : ''; ?>>
                        <span><?php echo h($item['label']); ?></span>
                        <span class="jb-orbit" aria-hidden="true"></span>
                    </a>
                <?php endforeach; ?>
            </div>
<div class="jb-menu-reel-mobile" aria-hidden="true"><?php jb_site_reel('', 'menu'); ?></div>
            <div class="jb-mega-contact">
                <div>
                    <h2>Email</h2>
                    <a href="mailto:<?php echo h($contact['email']); ?>"><?php echo h($contact['email']); ?></a>
                </div>
                <div>
                    <h2>Social</h2>
                    <a href="https://www.linkedin.com/in/<?php echo h($contact['linkedin']); ?>" target="_blank" rel="noopener">LinkedIn</a>
                    <a href="https://github.com/<?php echo h($contact['github']); ?>" target="_blank" rel="noopener">GitHub</a>
                    <a href="https://instagram.com/<?php echo h($contact['instagram']); ?>" target="_blank" rel="noopener">Instagram</a>
                </div>
            </div>
        </section>
    <div class="jb-menu-reel" aria-hidden="true"><?php jb_site_reel('', 'menu'); ?></div>
</nav>

    <main id="home" class="jb-main">
        <section class="jb-hero" aria-label="Jake Barton">
            <div class="jb-hero-media" aria-hidden="true">
                <?php jb_site_reel('jb-hero-video is-active', 'hero'); ?>
            </div>
            <img src="assets/brand/jb-logo.png" alt="" class="jb-hero-mark">
            <div class="jb-loader" id="jbLoader" aria-label="Loading homepage" aria-live="polite">
                <div class="jb-loader-bg"></div>
                <img src="assets/brand/jb-logo.png" alt="" class="jb-loader-mark">
                <h1 class="jb-loader-name"><span class="jb-loader-initial">J</span>ake Barton</h1>
                <div class="jb-loader-percent" id="jbLoaderPercent">0%</div>
            </div>
        </section>

        <section class="jb-statement jb-reveal" id="about" aria-label="About Jake Barton">
            <div class="jb-intro-copy">
                <h2><span class="jb-intro-readable"><?php echo h($content['home_greeting']); ?></span><span aria-hidden="true"><?php echo split_letters($content['home_greeting']); ?></span></h2>
                <p><span class="jb-intro-readable"><?php echo h($content['home_statement']); ?></span><span aria-hidden="true"><?php echo split_letters($content['home_statement']); ?></span></p>
            </div>
            <h3 class="jb-reel-title" id="demo-reel-title">Demo Reel</h3>
            <div class="jb-reel-frame">
                <video class="jb-reel-player" data-reel-player controls playsinline preload="metadata" poster="/assets/reel/portfolio-reel-poster.webp?v=20261109-public-reel" aria-labelledby="demo-reel-title">
                    <source src="/assets/reel/portfolio-reel.mp4?v=20261109-public-reel" type="video/mp4">
                    <a href="/assets/reel/portfolio-reel.mp4">Watch the demo reel</a>
                </video>
            </div>
            <a class="jb-resume-cta" href="/assets/documents/jake-barton-resume.pdf" target="_blank" rel="noopener">
                <span>Resume</span>
            </a>
        </section>

        <section class="jb-actions jb-reveal" id="work" aria-label="Work categories">
            <div class="jb-action-media" aria-hidden="true">
                <?php foreach ($content['home_categories'] as $i => $category): ?>
                    <video
                        class="jb-action-video"
                        data-src="<?php echo h($category['video']); ?>?v=20260813-video-r1"
                        poster="<?php echo h($category['poster']); ?>"
                        muted
                        loop
                        playsinline
                        preload="none"
                        disablepictureinpicture
                        disableremoteplayback
                        tabindex="-1"
                        data-video-index="<?php echo h((string) $i); ?>">
                    </video>
                <?php endforeach; ?>
            </div>
            <ul class="jb-action-links">
                <?php foreach ($content['home_categories'] as $i => $category): ?>
                    <li style="--category-order: <?php echo (int) $i; ?>;">
                        <a href="<?php echo h($category['url']); ?>" aria-label="<?php echo h($category['label']); ?>" data-video-index="<?php echo h((string) $i); ?>">
                            <span class="jb-category-window" aria-hidden="true"><span class="jb-category-label"><?php echo h($category['label']); ?></span><span class="jb-category-active"><?php echo h($category['label']); ?></span></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>

        <section class="jb-grid-section jb-reveal" id="clients" aria-label="Companies Jake Barton has worked with">
            <h2>Companies and organizations</h2>
            <ul class="jb-client-grid" aria-label="Client and organization logos">
                <?php foreach ($content['home_clients'] as $client): ?>
                    <li aria-label="<?php echo h($client['name']); ?>">
                        <img src="<?php echo h($client['image']); ?>" alt="<?php echo h($client['name']); ?>">
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>

        <section class="jb-accolades jb-reveal" aria-label="Toolkit and achievements">
            <h2>Experience and skills</h2>
            <ul class="jb-accolade-grid">
                <?php foreach ($content['home_accolades'] as $item): ?>
                    <li>
                        <span class="jb-accolade-kicker">
                            <span class="jb-accolade-category"><?php echo h($item['category']); ?></span>
                            <span class="jb-accolade-logo-slot">
                                <?php if (!empty($item['logo'])): ?>
                                    <img class="jb-accolade-logo" src="<?php echo h($item['logo']); ?>" alt="<?php echo h($item['logo_alt']); ?>">
                                <?php else: ?>
                                    <button type="button" class="jb-accolade-mark jb-code-egg" data-quiet-egg="code" aria-label="Play JB code animation">
                                        <span class="jb-code-default" aria-hidden="true"><?php echo h($item['mark']); ?></span>
                                        <span class="jb-code-reveal" aria-hidden="true">&lt;JB/&gt;</span>
                                    </button>
                                <?php endif; ?>
                            </span>
                        </span>
                        <strong><?php echo h($item['title']); ?></strong>
                        <p><?php echo h($item['detail']); ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    </main>

    <footer class="jb-footer jb-reveal" id="contact">
        <div class="jb-footer-contact">
            <div>
                <h2>Email</h2>
                <a class="jb-fold-underline" href="mailto:<?php echo h($contact['email']); ?>"><?php echo h($contact['email']); ?></a>
            </div>
            <div>
                <h2>Social</h2>
                <a href="https://www.linkedin.com/in/<?php echo h($contact['linkedin']); ?>" target="_blank" rel="noopener">LinkedIn</a>
                <a href="https://github.com/<?php echo h($contact['github']); ?>" target="_blank" rel="noopener">GitHub</a>
                <a href="https://instagram.com/<?php echo h($contact['instagram']); ?>" target="_blank" rel="noopener">Instagram</a>
            </div>
            <?php include __DIR__ . '/includes/footer-logo.php'; ?>
        </div>
        <a href="#home" class="jb-footer-word" aria-label="Back to top">
            <svg class="jb-footer-word-svg" viewBox="0 0 1720 320" aria-hidden="true" focusable="false">
                <text x="1708" y="258" text-anchor="end">Jake Barton</text>
            </svg>
            <span class="jb-footer-word-mask" aria-hidden="true">
                <?php jb_site_reel('', 'footer'); ?>
            </span>
        </a>
        <div class="jb-footer-bottom">
            <span>&copy; <?php echo date('Y'); ?> Jake Barton. Website inspired by piranhabar.ie</span>
            <nav aria-label="Footer links">
                <a href="#work">Portfolio</a>
                <a href="https://www.linkedin.com/in/<?php echo h($contact['linkedin']); ?>" target="_blank" rel="noopener">LinkedIn</a>
                <a href="https://github.com/<?php echo h($contact['github']); ?>" target="_blank" rel="noopener">GitHub</a>
                <a href="/assets/documents/jake-barton-resume.pdf" download>Resume</a>
            </nav>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/lenis@1.3.25/dist/lenis.min.js" defer></script>
    <script src="assets/js/site-scroll.js?v=20260813-video-r1" defer></script>
    <script src="assets/js/piranha-home.js?v=20261109-preview-fade" defer></script>
    <script src="assets/js/page-loader.js?v=20261104-loader-fix" defer></script>
<script src="/assets/js/site-reel.js?v=20261109-player" defer></script>
    <script src="/assets/js/easter-eggs.js?v=20261109-kart-sprites" defer></script>
</body>
</html>
