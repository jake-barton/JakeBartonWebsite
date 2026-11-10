<?php
require_once __DIR__ . '/content.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/source-viewer.php';

$menuItems = [
    ['label' => 'Home', 'url' => '/'],
    ['label' => 'Work', 'url' => '/work/', 'current' => true, 'parent' => true],
    ['label' => 'Games', 'url' => '/work/', 'child' => true],
    ['label' => 'Art', 'url' => '/work/', 'child' => true],
    ['label' => 'Web', 'url' => '/work/', 'child' => true],
    ['label' => 'About', 'url' => '/#about', 'parent' => true],
    ['label' => 'Contact', 'url' => '/#contact', 'parent' => true],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= jb_escape($pageDescription ?? '') ?>">
    <title><?= jb_escape($pageTitle ?? 'Work') ?> — <?= jb_escape($content['name']) ?></title>
    <link rel="icon" type="image/svg+xml" href="/assets/brand/favicon.svg">
    <link rel="stylesheet" href="/assets/css/base.css?v=20261109-helvetica"><link rel="stylesheet" href="/assets/css/components.css?v=20261110-sidebar-icon"><link rel="stylesheet" href="/assets/css/work-detail.css?v=20261109-logo-assembly">
<link rel="stylesheet" href="/assets/css/site-reel.css?v=20261104-loader-fix">
    <link rel="stylesheet" href="/assets/css/typography.css?v=20261109-footer-headings">
    <link rel="stylesheet" href="/assets/css/source-viewer.css?v=20261109-case-flow">
</head>
<body class="jb-home jb-detail-page" style="--page-canvas: <?= jb_escape(jb_color($theme['canvas'] ?? '', '#e7e6df')) ?>; --page-ink: <?= jb_escape(jb_color($theme['ink'] ?? '', '#132b4f')) ?>; --page-accent: <?= jb_escape(jb_color($theme['accent'] ?? '', '#23883c')) ?>; --page-rail: <?= jb_escape(jb_color($theme['rail'] ?? '', '#23883c')) ?>; --footer-background: <?= jb_escape(jb_color($theme['footer_background'] ?? '', '#e7e6df')) ?>; --footer-text: <?= jb_escape(jb_color($theme['footer_text'] ?? '', '#111111')) ?>; --footer-word: <?= jb_escape(jb_color($theme['footer_word'] ?? '', '#23883c')) ?>;">
<div class="jb-work-loader" id="pageLoader" aria-label="Loading project" aria-live="polite"><div class="jb-work-loader-curtains" aria-hidden="true"><span></span><span></span><span></span><span class="jb-work-loader-primary"><span class="jb-work-loader-name"><b class="jb-work-name-dark">Jake Barton</b><b class="jb-work-name-white">Jake Barton</b></span></span></div></div>
<nav class="jb-rail" id="jbRail" aria-label="Primary navigation">
    <button class="jb-menu-button" id="jbMenuButton" type="button" aria-expanded="false" aria-controls="jbMegaMenu"><img src="/assets/brand/jb-logo.png" alt="Open menu"><span class="jb-menu-text">Open/Close</span></button>
    <a href="/" class="jb-rail-wordmark" aria-label="Jake Barton home">Jake Barton</a>
    <?php require __DIR__ . '/rail-resume.php'; ?>
    <ul class="jb-rail-socials" aria-label="Social links">
        <li><a href="https://www.linkedin.com/in/<?= jb_escape($content['linkedin']) ?>" target="_blank" rel="noopener" aria-label="LinkedIn"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4.98 3.5C4.98 4.88 3.86 6 2.5 6S0 4.88 0 3.5 1.12 1 2.5 1s2.48 1.12 2.48 2.5ZM.36 8.2h4.28V23H.36V8.2Zm7.08 0h4.1v2.02h.06c.57-1.08 1.97-2.22 4.05-2.22 4.34 0 5.14 2.86 5.14 6.57V23h-4.28v-7.48c0-1.78-.03-4.08-2.49-4.08-2.49 0-2.87 1.94-2.87 3.95V23H7.44V8.2Z"/></svg></a></li>
        <li><a href="https://github.com/<?= jb_escape($content['github']) ?>" target="_blank" rel="noopener" aria-label="GitHub"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 .5C5.65.5.5 5.65.5 12c0 5.08 3.29 9.39 7.86 10.91.58.1.79-.25.79-.56v-2.14c-3.2.7-3.87-1.36-3.87-1.36-.52-1.33-1.28-1.68-1.28-1.68-1.04-.71.08-.7.08-.7 1.15.08 1.76 1.18 1.76 1.18 1.03 1.75 2.69 1.25 3.35.95.1-.74.4-1.25.73-1.54-2.56-.29-5.25-1.28-5.25-5.7 0-1.26.45-2.29 1.18-3.09-.12-.29-.51-1.47.11-3.05 0 0 .96-.31 3.16 1.18.92-.26 1.9-.38 2.88-.39.98.01 1.96.13 2.88.39 2.2-1.49 3.16-1.18 3.16-1.18.62 1.58.23 2.76.11 3.05.74.8 1.18 1.83 1.18 3.09 0 4.43-2.7 5.41-5.27 5.69.42.36.78 1.06.78 2.14v3.17c0 .31.21.67.8.56A11.51 11.51 0 0 0 23.5 12C23.5 5.65 18.35.5 12 .5Z"/></svg></a></li>
        <li><a href="https://instagram.com/<?= jb_escape($content['instagram']) ?>" target="_blank" rel="noopener" aria-label="Instagram"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7.5 2h9A5.51 5.51 0 0 1 22 7.5v9a5.51 5.51 0 0 1-5.5 5.5h-9A5.51 5.51 0 0 1 2 16.5v-9A5.51 5.51 0 0 1 7.5 2Zm0 2A3.5 3.5 0 0 0 4 7.5v9A3.5 3.5 0 0 0 7.5 20h9a3.5 3.5 0 0 0 3.5-3.5v-9A3.5 3.5 0 0 0 16.5 4h-9ZM12 7a5 5 0 1 1 0 10 5 5 0 0 1 0-10Zm0 2a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm5.25-2.35a1.1 1.1 0 1 1 0 2.2 1.1 1.1 0 0 1 0-2.2Z"/></svg></a></li>
    </ul>
    <section class="jb-mega-menu" id="jbMegaMenu" aria-label="Menu">
        <div class="jb-mega-links">
            <?php foreach ($menuItems as $menuIndex => $item): ?>
                <?php $classes=['jb-mega-link']; if(!empty($item['current']))$classes[]='is-current'; if(!empty($item['parent']))$classes[]='is-parent'; if(!empty($item['child']))$classes[]='is-child'; ?>
                <a href="<?= jb_escape($item['url']) ?>" class="<?= jb_escape(implode(' ', $classes)) ?>" style="--delay:<?= jb_escape((string)($menuIndex * .11)) ?>s"<?= !empty($item['current'])?' aria-current="page"':'' ?>><span><?= jb_escape($item['label']) ?></span><span class="jb-orbit" aria-hidden="true"></span></a>
            <?php endforeach; ?>
        </div>
        <div class="jb-menu-reel-mobile" aria-hidden="true"><?php jb_site_reel('', 'menu'); ?></div>
<div class="jb-mega-contact"><div><h2>Email</h2><a href="mailto:<?= jb_escape($content['email']) ?>"><?= jb_escape($content['email']) ?></a></div><div><h2>Social</h2><a href="https://www.linkedin.com/in/<?= jb_escape($content['linkedin']) ?>" target="_blank" rel="noopener">LinkedIn</a><a href="https://github.com/<?= jb_escape($content['github']) ?>" target="_blank" rel="noopener">GitHub</a><a href="https://instagram.com/<?= jb_escape($content['instagram']) ?>" target="_blank" rel="noopener">Instagram</a></div></div>
    </section>
<div class="jb-menu-reel" aria-hidden="true"><?php jb_site_reel('', 'menu'); ?></div>
</nav>
<a class="jb-detail-close" href="/work/" aria-label="Close project and return to Work"><span class="jb-detail-close-icon" aria-hidden="true"></span><span>Close</span></a>

<main class="jb-detail-main">
