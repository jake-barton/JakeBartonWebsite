<?php
require_once __DIR__ . '/../includes/content.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/work-easter-eggs.php';

function h($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$menu_items = [
    ['label' => 'Home', 'url' => '/'],
    ['label' => 'Work', 'url' => '/work/', 'current' => true, 'parent' => true],
    ['label' => 'Games', 'url' => '/work/', 'child' => true],
    ['label' => 'Art', 'url' => '/work/', 'child' => true],
    ['label' => 'Web', 'url' => '/work/', 'child' => true],
    ['label' => 'About', 'url' => '/#about', 'parent' => true],
    ['label' => 'Contact', 'url' => '/#contact', 'parent' => true],
];

$work_filters = [
    ['key' => 'all', 'label' => 'All Work'],
    ['key' => 'games', 'label' => 'Games'],
    ['key' => 'apps', 'label' => 'Apps'],
    ['key' => 'programming', 'label' => 'Programming'],
    ['key' => 'web', 'label' => 'Web + AI'],
    ['key' => 'design', 'label' => 'Visual Design'],
    ['key' => 'motion', 'label' => 'Motion'],
    ['key' => 'business', 'label' => 'Business'],
    ['key' => '3d', 'label' => '3D Art'],
];

$work_projects = array_values(require __DIR__ . '/../includes/projects.php');
usort($work_projects, static fn(array $a, array $b): int => ($a['sort_order'] ?? 50) <=> ($b['sort_order'] ?? 50));
$available_filters = array_unique(array_merge([], ...array_column($work_projects, 'filters')));
$work_filters = array_values(array_filter($work_filters, static fn(array $filter): bool => $filter['key'] === 'all' || in_array($filter['key'], $available_filters, true)));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Selected work by Jake Barton.">
    <title>Work — <?php echo h($content['name']); ?></title>
    <link rel="icon" type="image/svg+xml" href="../assets/brand/favicon.svg">
    <link rel="stylesheet" href="../assets/css/base.css?v=20261109-helvetica">
    <link rel="stylesheet" href="../assets/css/components.css?v=20261110-filter-cameos">
    <link rel="stylesheet" href="../assets/css/work.css?v=20261110-filter-cameos">
<link rel="stylesheet" href="/assets/css/site-reel.css?v=20261104-loader-fix">
    <link rel="stylesheet" href="/assets/css/typography.css?v=20261109-footer-headings">
</head>
<body class="jb-home jb-work-page">
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
            <img src="../assets/brand/jb-logo.png" alt="Open menu">
            <span class="jb-menu-text">Open/Close</span>
        </button>
        <a href="/" class="jb-rail-wordmark" aria-label="Jake Barton home">Jake Barton</a>

        <ul class="jb-rail-socials" aria-label="Social links">
            <li><a href="https://www.linkedin.com/in/<?php echo h($content['linkedin']); ?>" target="_blank" rel="noopener" aria-label="LinkedIn"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4.98 3.5C4.98 4.88 3.86 6 2.5 6S0 4.88 0 3.5 1.12 1 2.5 1s2.48 1.12 2.48 2.5ZM.36 8.2h4.28V23H.36V8.2Zm7.08 0h4.1v2.02h.06c.57-1.08 1.97-2.22 4.05-2.22 4.34 0 5.14 2.86 5.14 6.57V23h-4.28v-7.48c0-1.78-.03-4.08-2.49-4.08-2.49 0-2.87 1.94-2.87 3.95V23H7.44V8.2Z"/></svg></a></li>
            <li><a href="https://github.com/<?php echo h($content['github']); ?>" target="_blank" rel="noopener" aria-label="GitHub"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 .5C5.65.5.5 5.65.5 12c0 5.08 3.29 9.39 7.86 10.91.58.1.79-.25.79-.56v-2.14c-3.2.7-3.87-1.36-3.87-1.36-.52-1.33-1.28-1.68-1.28-1.68-1.04-.71.08-.7.08-.7 1.15.08 1.76 1.18 1.76 1.18 1.03 1.75 2.69 1.25 3.35.95.1-.74.4-1.25.73-1.54-2.56-.29-5.25-1.28-5.25-5.7 0-1.26.45-2.29 1.18-3.09-.12-.29-.51-1.47.11-3.05 0 0 .96-.31 3.16 1.18.92-.26 1.9-.38 2.88-.39.98.01 1.96.13 2.88.39 2.2-1.49 3.16-1.18 3.16-1.18.62 1.58.23 2.76.11 3.05.74.8 1.18 1.83 1.18 3.09 0 4.43-2.7 5.41-5.27 5.69.42.36.78 1.06.78 2.14v3.17c0 .31.21.67.8.56A11.51 11.51 0 0 0 23.5 12C23.5 5.65 18.35.5 12 .5Z"/></svg></a></li>
            <li><a href="https://instagram.com/<?php echo h($content['instagram']); ?>" target="_blank" rel="noopener" aria-label="Instagram"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7.5 2h9A5.51 5.51 0 0 1 22 7.5v9a5.51 5.51 0 0 1-5.5 5.5h-9A5.51 5.51 0 0 1 2 16.5v-9A5.51 5.51 0 0 1 7.5 2Zm0 2A3.5 3.5 0 0 0 4 7.5v9A3.5 3.5 0 0 0 7.5 20h9a3.5 3.5 0 0 0 3.5-3.5v-9A3.5 3.5 0 0 0 16.5 4h-9ZM12 7a5 5 0 1 1 0 10 5 5 0 0 1 0-10Zm0 2a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm5.25-2.35a1.1 1.1 0 1 1 0 2.2 1.1 1.1 0 0 1 0-2.2Z"/></svg></a></li>
        </ul>

        <section class="jb-mega-menu" id="jbMegaMenu" aria-label="Menu">
            <div class="jb-mega-links">
                <?php foreach ($menu_items as $i => $item): ?>
                    <?php
                    $classes = ['jb-mega-link'];
                    if (!empty($item['current'])) $classes[] = 'is-current';
                    if (!empty($item['parent'])) $classes[] = 'is-parent';
                    if (!empty($item['child'])) $classes[] = 'is-child';
                    ?>
                    <a href="<?php echo h($item['url']); ?>" class="<?php echo h(implode(' ', $classes)); ?>" style="--delay: <?php echo h((string) ($i * 0.11)); ?>s" <?php echo !empty($item['current']) ? 'aria-current="page"' : ''; ?>>
                        <span><?php echo h($item['label']); ?></span><span class="jb-orbit" aria-hidden="true"></span>
                    </a>
                <?php endforeach; ?>
            </div>
<div class="jb-menu-reel-mobile" aria-hidden="true"><?php jb_site_reel('', 'menu'); ?></div>
            <div class="jb-mega-contact">
                <div><h2>Email</h2><a href="mailto:<?php echo h($content['email']); ?>"><?php echo h($content['email']); ?></a></div>
                <div><h2>Social</h2><a href="https://www.linkedin.com/in/<?php echo h($content['linkedin']); ?>" target="_blank" rel="noopener">LinkedIn</a><a href="https://github.com/<?php echo h($content['github']); ?>" target="_blank" rel="noopener">GitHub</a><a href="https://instagram.com/<?php echo h($content['instagram']); ?>" target="_blank" rel="noopener">Instagram</a></div>
            </div>
        </section>
    <div class="jb-menu-reel" aria-hidden="true"><?php jb_site_reel('', 'menu'); ?></div>
</nav>

    <main class="jb-work-index" id="work" aria-label="Selected work">
        <nav class="jb-work-sections" aria-label="Work sections">
            <button type="button" data-work-section="games" data-quiet-egg="kart">Games
                <?php jb_work_egg('games'); ?>
            </button>
            <button type="button" data-work-section="art" data-quiet-egg="pen">Art<?php jb_work_egg('art'); ?></button>
            <button type="button" data-work-section="web" data-quiet-egg="browser">Web<?php jb_work_egg('web'); ?></button>
        </nav>

        <header class="jb-work-header">
            <h1>WORK</h1>
        </header>

        <nav class="jb-work-filters" aria-label="Filter projects">
            <?php foreach ($work_filters as $i => $filter): ?>
                <button type="button" class="jb-work-filter<?php echo $i === 0 ? ' is-active' : ''; ?>" data-work-filter="<?php echo h($filter['key']); ?>" data-quiet-egg="<?= h(jb_work_egg_type($filter['key'])) ?>" aria-pressed="<?php echo $i === 0 ? 'true' : 'false'; ?>">
                    <?php echo h($filter['label']); ?>
                    <?php jb_work_egg($filter['key']); ?>
                </button>
            <?php endforeach; ?>
        </nav>

        <section class="jb-work-grid" aria-live="polite">
            <?php foreach ($work_projects as $i => $project): ?>
                <?php $card=is_array($project['card']??null)?$project['card']:[]; ?><article class="jb-work-card<?php echo !empty($project['logo_card']) ? ' is-logo-card' : ''; ?>" tabindex="0" data-work-card<?php echo !empty($project['url']) ? ' data-project-url="' . h($project['url']) . '"' : ''; ?> data-filters="<?php echo h(implode(' ', $project['filters'])); ?>" style="--card-index: <?php echo h((string) $i); ?>;--card-radius:<?= h((string)($card['corner_radius']??28)) ?>px;--card-overlay:<?= h((string)($card['overlay_opacity']??.34)) ?>;--card-overlay-color:<?= h($card['overlay_color']??'#000000') ?>;--card-background:<?= h($card['background']??'#111111') ?>;--card-text:<?= h($card['text_color']??'#ffffff') ?>;--card-tag:<?= h($card['tag_background']??'#c6c5bc') ?>;--card-tag-text:<?= h($card['tag_text']??'#ffffff') ?>;--card-position:<?= h($card['image_position']??'center') ?>;--card-fit:<?= h($card['image_fit']??'cover') ?>;--card-scale:<?= h((string)($card['image_scale']??100)) ?>">
                    <div class="jb-work-card-media">
                        <img src="<?php echo h($project['image']); ?>" alt="<?php echo h($project['title']); ?> project cover" loading="<?php echo $i < 4 ? 'eager' : 'lazy'; ?>">
                        <?php if ($project['video'] !== ''): ?><video data-card-video data-src="<?= h(jb_asset($project['video'])) ?>" muted loop playsinline preload="none" aria-hidden="true"></video><?php endif; ?>
                    </div>
                    <div class="jb-work-card-shade" aria-hidden="true"></div>
                    <ul class="jb-work-card-tags" aria-label="Project categories">
                        <?php foreach ($project['tags'] as $tag): ?>
                            <li><?php echo h($tag); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="jb-work-card-copy">
                        <h2><?php echo h($project['title']); ?></h2>
                        <p><?php echo h($project['subtitle']); ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>

    </main>

    <footer class="jb-footer jb-work-site-footer" id="contact">
        <div class="jb-footer-contact">
            <div>
                <h2>Email</h2>
                <a class="jb-fold-underline" href="mailto:<?php echo h($content['email']); ?>"><?php echo h($content['email']); ?></a>
            </div>
            <div>
                <h2>Social</h2>
                <a href="https://www.linkedin.com/in/<?php echo h($content['linkedin']); ?>" target="_blank" rel="noopener">LinkedIn</a>
                <a href="https://github.com/<?php echo h($content['github']); ?>" target="_blank" rel="noopener">GitHub</a>
                <a href="https://instagram.com/<?php echo h($content['instagram']); ?>" target="_blank" rel="noopener">Instagram</a>
            </div>
            <?php include __DIR__ . '/../includes/footer-logo.php'; ?>
        </div>
        <a href="#work" class="jb-footer-word" aria-label="Back to top">
            <svg class="jb-footer-word-svg" viewBox="0 0 1720 320" aria-hidden="true" focusable="false">
                <text x="58" y="258">Jake Barton</text>
            </svg>
            <span class="jb-footer-word-mask" aria-hidden="true">
                <?php jb_site_reel('', 'footer'); ?>
            </span>
        </a>
        <div class="jb-footer-bottom">
            <span>&copy; <?php echo date('Y'); ?> Jake Barton. Website inspired by piranhabar.ie</span>
            <nav aria-label="Footer links">
                <a href="#work">Portfolio</a>
                <a href="https://www.linkedin.com/in/<?php echo h($content['linkedin']); ?>" target="_blank" rel="noopener">LinkedIn</a>
                <a href="https://github.com/<?php echo h($content['github']); ?>" target="_blank" rel="noopener">GitHub</a>
                <a href="../assets/documents/jake-barton-resume.pdf" download>Resume</a>
            </nav>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/lenis@1.3.25/dist/lenis.min.js" defer></script>
    <script src="../assets/js/site-scroll.js?v=20261109-chapter-links" defer></script>
    <script src="../assets/js/work-loader.js?v=20260714-menu-only" defer></script>
    <script src="../assets/js/page-loader.js?v=20261104-loader-fix" defer></script>
    <script src="../assets/js/work-page.js?v=20261103-card-video" defer></script>
<script src="/assets/js/site-reel.js?v=20261109-player" defer></script>
    <script src="/assets/js/easter-eggs.js?v=20261110-filter-cameos" defer></script>
</body>
</html>
