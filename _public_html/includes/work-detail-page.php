<?php
require_once __DIR__ . '/cms.php';
require_once __DIR__ . '/content.php';

$slug = jb_cms_slug((string) ($jb_detail_slug ?? $_GET['slug'] ?? ''));
$page = jb_cms_load_page($slug);
// Keep links to projects that have been combined pointed at their collection.
if ($page && !isset($_GET['studio-preview']) && !empty($page['redirect_to'])) {
    $targetSlug = jb_cms_slug((string) $page['redirect_to']);
    $targetPage = jb_cms_load_page($targetSlug);
    if ($targetSlug !== $slug && !empty($targetPage['published'])) {
        header('Location: /work/' . $targetSlug . '/', true, 301);
        exit;
    }
}
if (!$page || (empty($page['published']) && !jb_cms_authenticated())) {
    http_response_code(404);
    echo '404 Not Found';
    exit;
}
$components = jb_cms_components();
$theme = is_array($page['theme'] ?? null) ? $page['theme'] : [];
$theme['footer_word'] = $theme['footer_word'] ?? $theme['accent'] ?? $theme['ink'] ?? '#141414';
$studioPreview = isset($_GET['studio-preview']) && jb_cms_authenticated();
$heroId = 'project-title';
$menuItems = [
    ['label' => 'Home', 'url' => '/'],
    ['label' => 'Work', 'url' => '/work/', 'current' => true, 'parent' => true],
    ['label' => 'Games', 'url' => '/work/', 'child' => true],
    ['label' => 'Art', 'url' => '/work/', 'child' => true],
    ['label' => 'Web', 'url' => '/work/', 'child' => true],
    ['label' => 'About', 'url' => '/#about', 'parent' => true],
    ['label' => 'Contact', 'url' => '/#contact', 'parent' => true],
];

function jb_text_layout_style(array $block, string $field): string
{
    $styles = [];
    $width = $block[$field . '_width'] ?? '';
    if (is_numeric($width)) $styles[] = 'max-width:' . jb_cms_number($width, 100, 10, 100) . '%';
    foreach (['left', 'right', 'top', 'bottom'] as $side) {
        $value = $block[$field . '_margin_' . $side] ?? '';
        if (is_numeric($value)) $styles[] = 'margin-' . $side . ':' . jb_cms_number($value, 0, -300, 400) . 'px';
    }
    return $styles ? ';' . implode(';', $styles) : '';
}

function jb_render_component(array $block, array $definitions, int $index): void
{
    $type = (string) ($block['type'] ?? '');
    if ($type === 'hero'): ?>
        <section class="jb-detail-hero" aria-labelledby="project-title" data-studio-component="<?= $index ?>" data-studio-color-field="overlay_color" style="--hero-position: <?= jb_cms_h(jb_cms_image_position($block['image_position'] ?? 'center')) ?>; --hero-fit: <?= ($block['media_fit'] ?? '') === 'contain' ? 'contain' : 'cover' ?>; --hero-overlay: <?= jb_cms_h(jb_cms_color($block['overlay_color'] ?? '', '#132b4f')) ?>; --hero-overlay-opacity: <?= jb_cms_h(jb_cms_opacity($block['overlay_opacity'] ?? 0.42)) ?>; --hero-text: <?= jb_cms_h(jb_cms_color($block['text_color'] ?? '', '#ffffff')) ?>">
            <?php if (trim((string) ($block['video'] ?? '')) !== ''): ?>
                <video src="<?= jb_cms_h(jb_cms_asset($block['video'])) ?>" poster="<?= jb_cms_h(jb_cms_asset($block['image'] ?? '')) ?>" muted autoplay loop playsinline preload="metadata" disablepictureinpicture disableremoteplayback tabindex="-1" data-studio-video-field="video"></video>
            <?php else: ?>
                <img src="<?= jb_cms_h(jb_cms_asset($block['image'] ?? '')) ?>" alt="<?= jb_cms_h($block['alt'] ?? '') ?>" data-studio-image-field="image">
            <?php endif; ?>
            <div class="jb-detail-hero-shade" aria-hidden="true"></div>
            <div class="jb-detail-hero-copy"><p data-studio-field="eyebrow" data-studio-color-field="eyebrow_color" style="color:<?= jb_cms_h(jb_cms_color($block['eyebrow_color'] ?? '', $block['text_color'] ?? '#ffffff')) ?><?= jb_cms_h(jb_text_layout_style($block,'eyebrow')) ?>"><?= jb_cms_h($block['eyebrow'] ?? '') ?></p><h1 id="project-title" data-studio-field="title" data-studio-color-field="title_color" style="color:<?= jb_cms_h(jb_cms_color($block['title_color'] ?? '', $block['text_color'] ?? '#ffffff')) ?><?= jb_cms_h(jb_text_layout_style($block,'title')) ?>"><?= jb_cms_h($block['title'] ?? '') ?></h1></div>
        </section>
    <?php elseif ($type === 'intro'): ?>
        <section class="jb-detail-intro" data-studio-component="<?= $index ?>" data-studio-color-field="background" style="--intro-background: <?= jb_cms_h(jb_cms_color($block['background'] ?? '', '#e7e6df')) ?>; --intro-text: <?= jb_cms_h(jb_cms_color($block['text_color'] ?? '', '#111111')) ?>">
            <div class="jb-detail-info-module">
                <h2 data-studio-field="heading" data-studio-color-field="heading_color" style="color:<?= jb_cms_h(jb_cms_color($block['heading_color'] ?? '', $block['text_color'] ?? '#111111')) ?><?= jb_cms_h(jb_text_layout_style($block,'heading')) ?>"><?= jb_cms_h($block['heading'] ?? '') ?></h2>
                <dl class="jb-detail-meta">
                    <div><dt data-studio-field="role_label" data-studio-color-field="role_label_color" style="color:<?= jb_cms_h(jb_cms_color($block['role_label_color'] ?? '', $block['text_color'] ?? '#111111')) ?>"><?= jb_cms_h($block['role_label'] ?? 'Role') ?></dt><dd data-studio-field="role" data-studio-color-field="role_color" style="color:<?= jb_cms_h(jb_cms_color($block['role_color'] ?? '', $block['text_color'] ?? '#111111')) ?>"><?= jb_cms_h($block['role'] ?? '') ?></dd></div>
                    <div><dt data-studio-field="disciplines_label" data-studio-color-field="disciplines_label_color" style="color:<?= jb_cms_h(jb_cms_color($block['disciplines_label_color'] ?? '', $block['text_color'] ?? '#111111')) ?>"><?= jb_cms_h($block['disciplines_label'] ?? 'Disciplines') ?></dt><dd data-studio-field="disciplines" data-studio-color-field="disciplines_color" style="color:<?= jb_cms_h(jb_cms_color($block['disciplines_color'] ?? '', $block['text_color'] ?? '#111111')) ?>"><?= jb_cms_h($block['disciplines'] ?? '') ?></dd></div>
                    <div><dt data-studio-field="built_with_label" data-studio-color-field="built_with_label_color" style="color:<?= jb_cms_h(jb_cms_color($block['built_with_label_color'] ?? '', $block['text_color'] ?? '#111111')) ?>"><?= jb_cms_h($block['built_with_label'] ?? 'Built with') ?></dt><dd data-studio-field="built_with" data-studio-color-field="built_with_color" style="color:<?= jb_cms_h(jb_cms_color($block['built_with_color'] ?? '', $block['text_color'] ?? '#111111')) ?>"><?= jb_cms_h($block['built_with'] ?? '') ?></dd></div>
                    <div><dt data-studio-field="client_label" data-studio-color-field="client_label_color" style="color:<?= jb_cms_h(jb_cms_color($block['client_label_color'] ?? '', $block['text_color'] ?? '#111111')) ?>"><?= jb_cms_h($block['client_label'] ?? 'Client') ?></dt><dd data-studio-field="client" data-studio-color-field="client_color" style="color:<?= jb_cms_h(jb_cms_color($block['client_color'] ?? '', $block['text_color'] ?? '#111111')) ?>"><?= jb_cms_h($block['client'] ?? '') ?></dd></div>
                </dl>
            </div>
            <div class="jb-detail-story-module"><div class="jb-detail-intro-copy">
                <?php foreach (($block['paragraphs'] ?? []) as $paragraphIndex => $paragraph): ?><p data-studio-field="paragraphs" data-studio-color-field="paragraphs_color" data-studio-paragraph="<?= (int) $paragraphIndex ?>" style="color:<?= jb_cms_h(jb_cms_color($block['paragraphs_color'] ?? '', $block['text_color'] ?? '#111111')) ?><?= jb_cms_h(jb_text_layout_style($block,'paragraphs')) ?>"><?= jb_cms_h($paragraph) ?></p><?php endforeach; ?>
            </div></div>
        </section>
    <?php elseif ($type === 'artwork'): ?>
        <section class="jb-detail-brand" data-studio-component="<?= $index ?>" data-studio-color-field="background" aria-label="<?= jb_cms_h($block['alt'] ?? 'Project artwork') ?>" style="--artwork-background: <?= jb_cms_h(jb_cms_color($block['background'] ?? '', '#79add9')) ?>; --artwork-width: <?= jb_cms_h(jb_cms_number($block['image_width'] ?? 66, 66, 10, 100)) ?>%; --block-radius: <?= jb_cms_h(jb_cms_number($block['corner_radius'] ?? 10, 10, 0, 100)) ?>px; --block-left: <?= jb_cms_h(jb_cms_number($block['left_margin'] ?? 100, 100, 0, 300)) ?>px; --block-right: <?= jb_cms_h(jb_cms_number($block['right_margin'] ?? 40, 40, 0, 300)) ?>px; --block-gap: <?= jb_cms_h(jb_cms_number($block['section_gap'] ?? 80, 80, 0, 300)) ?>px">
            <img class="jb-detail-brand-logo" src="<?= jb_cms_h(jb_cms_asset($block['image'] ?? '')) ?>" alt="<?= jb_cms_h($block['alt'] ?? '') ?>" data-studio-image-field="image" style="justify-self:<?= jb_cms_h(['start'=>'start','center'=>'center','end'=>'end'][$block['image_alignment'] ?? 'center'] ?? 'center') ?>">
            <?php if (!empty($block['decoration_1'])): ?><img class="jb-detail-orbit jb-detail-orbit-one" src="<?= jb_cms_h(jb_cms_asset($block['decoration_1'])) ?>" alt=""><?php endif; ?>
            <?php if (!empty($block['decoration_2'])): ?><img class="jb-detail-orbit jb-detail-orbit-two" src="<?= jb_cms_h(jb_cms_asset($block['decoration_2'])) ?>" alt=""><?php endif; ?>
        </section>
    <?php elseif ($type === 'product'): ?>
        <section class="jb-detail-system" data-studio-component="<?= $index ?>" data-studio-color-field="background" style="--section-background: <?= jb_cms_h(jb_cms_color($block['background'] ?? '', '#132b4f')) ?>; --section-text: <?= jb_cms_h(jb_cms_color($block['text_color'] ?? '', '#ffffff')) ?>; --block-radius: <?= jb_cms_h(jb_cms_number($block['corner_radius'] ?? 10, 10, 0, 100)) ?>px; --block-left: <?= jb_cms_h(jb_cms_number($block['left_margin'] ?? 100, 100, 0, 300)) ?>px; --block-right: <?= jb_cms_h(jb_cms_number($block['right_margin'] ?? 40, 40, 0, 300)) ?>px; --block-gap: <?= jb_cms_h(jb_cms_number($block['section_gap'] ?? 80, 80, 0, 300)) ?>px; --block-padding-top: <?= jb_cms_h(jb_cms_number($block['padding_top'] ?? 100, 100, 0, 300)) ?>px; --block-padding-bottom: <?= jb_cms_h(jb_cms_number($block['padding_bottom'] ?? 100, 100, 0, 300)) ?>px"><header><h2 data-studio-field="title" data-studio-color-field="title_color" style="color:<?= jb_cms_h(jb_cms_color($block['title_color'] ?? '', $block['text_color'] ?? '#ffffff')) ?><?= jb_cms_h(jb_text_layout_style($block,'title')) ?>"><?= jb_cms_h($block['title'] ?? '') ?></h2></header>
            <figure class="jb-detail-product-shot" style="--product-width:<?= jb_cms_h(jb_cms_number($block['image_width'] ?? 100, 100, 20, 100)) ?>%;--product-align:<?= jb_cms_h(['start'=>'flex-start','center'=>'center','end'=>'flex-end'][$block['image_alignment'] ?? 'center'] ?? 'center') ?>"><img src="<?= jb_cms_h(jb_cms_asset($block['image'] ?? '')) ?>" alt="<?= jb_cms_h($block['alt'] ?? '') ?>" data-studio-image-field="image"></figure>
        </section>
    <?php elseif ($type === 'quote'): ?>
        <section class="jb-detail-quote" data-studio-component="<?= $index ?>" data-studio-color-field="background" style="--quote-background: <?= jb_cms_h(jb_cms_color($block['background'] ?? '', '#79add9')) ?>; --quote-text: <?= jb_cms_h(jb_cms_color($block['text_color'] ?? '', '#132b4f')) ?>; --block-radius: <?= jb_cms_h(jb_cms_number($block['corner_radius'] ?? 10, 10, 0, 100)) ?>px; --block-left: <?= jb_cms_h(jb_cms_number($block['left_margin'] ?? 100, 100, 0, 300)) ?>px; --block-right: <?= jb_cms_h(jb_cms_number($block['right_margin'] ?? 40, 40, 0, 300)) ?>px; --block-gap: <?= jb_cms_h(jb_cms_number($block['section_gap'] ?? 80, 80, 0, 300)) ?>px; --block-padding-top: <?= jb_cms_h(jb_cms_number($block['padding_top'] ?? 100, 100, 0, 300)) ?>px; --block-padding-bottom: <?= jb_cms_h(jb_cms_number($block['padding_bottom'] ?? 100, 100, 0, 300)) ?>px"><blockquote data-studio-field="quote" data-studio-color-field="quote_color" style="color:<?= jb_cms_h(jb_cms_color($block['quote_color'] ?? '', $block['text_color'] ?? '#132b4f')) ?><?= jb_cms_h(jb_text_layout_style($block,'quote')) ?>"><?= jb_cms_h($block['quote'] ?? '') ?></blockquote>
            <?php if (!empty($block['link_url'])): ?><a href="<?= jb_cms_h($block['link_url']) ?>" target="_blank" rel="noopener" data-studio-field="link_label" data-studio-color-field="link_label_color" style="color:<?= jb_cms_h(jb_cms_color($block['link_label_color'] ?? '', $block['text_color'] ?? '#132b4f')) ?><?= jb_cms_h(jb_text_layout_style($block,'link_label')) ?>"><?= jb_cms_h($block['link_label'] ?? 'Project link') ?> <span>↗</span></a><?php endif; ?>
        </section>
    <?php elseif (isset($definitions[$type]['render']) && is_callable($definitions[$type]['render'])):
        call_user_func($definitions[$type]['render'], $block, $index);
    endif;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= jb_cms_h($page['description'] ?? '') ?>">
    <title><?= jb_cms_h($page['title'] ?? 'Work') ?> — <?= jb_cms_h($content['name']) ?></title>
    <link rel="icon" type="image/svg+xml" href="/assets/brand/favicon.svg">
    <link rel="stylesheet" href="/assets/css/base.css"><link rel="stylesheet" href="/assets/css/components.css?v=20261103-links"><link rel="stylesheet" href="/assets/css/work-detail.css?v=20261109-no-video-labels">
<link rel="stylesheet" href="/assets/css/site-reel.css?v=20261104-loader-fix">
</head>
<body class="jb-home jb-detail-page<?= $studioPreview ? ' is-studio-preview' : '' ?>" data-studio-theme-color="canvas" style="--page-canvas: <?= jb_cms_h(jb_cms_color($theme['canvas'] ?? '', '#e7e6df')) ?>; --page-ink: <?= jb_cms_h(jb_cms_color($theme['ink'] ?? '', '#132b4f')) ?>; --page-accent: <?= jb_cms_h(jb_cms_color($theme['accent'] ?? '', '#23883c')) ?>; --page-rail: <?= jb_cms_h(jb_cms_color($theme['rail'] ?? '', '#23883c')) ?>; --footer-background: <?= jb_cms_h(jb_cms_color($theme['footer_background'] ?? '', '#e7e6df')) ?>; --footer-text: <?= jb_cms_h(jb_cms_color($theme['footer_text'] ?? '', '#111111')) ?>; --footer-word: <?= jb_cms_h(jb_cms_color($theme['footer_word'] ?? '', '#23883c')) ?>;">
<?php if (!$studioPreview): ?><div class="jb-work-loader" id="pageLoader" aria-label="Loading project" aria-live="polite"><div class="jb-work-loader-curtains" aria-hidden="true"><span></span><span></span><span></span><span class="jb-work-loader-primary"><span class="jb-work-loader-name"><b class="jb-work-name-dark">Jake Barton</b><b class="jb-work-name-white">Jake Barton</b></span></span></div></div><?php endif; ?>
<nav class="jb-rail" id="jbRail" aria-label="Primary navigation" data-studio-theme-color="rail">
    <button class="jb-menu-button" id="jbMenuButton" type="button" aria-expanded="false" aria-controls="jbMegaMenu"><img src="/assets/brand/jb-logo.png" alt="Open menu"><span class="jb-menu-text">Open/Close</span></button>
    <a href="/" class="jb-rail-wordmark" aria-label="Jake Barton home">Jake Barton</a>
    <ul class="jb-rail-socials" aria-label="Social links">
        <li><a href="https://www.linkedin.com/in/<?= jb_cms_h($content['linkedin']) ?>" target="_blank" rel="noopener" aria-label="LinkedIn"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4.98 3.5C4.98 4.88 3.86 6 2.5 6S0 4.88 0 3.5 1.12 1 2.5 1s2.48 1.12 2.48 2.5ZM.36 8.2h4.28V23H.36V8.2Zm7.08 0h4.1v2.02h.06c.57-1.08 1.97-2.22 4.05-2.22 4.34 0 5.14 2.86 5.14 6.57V23h-4.28v-7.48c0-1.78-.03-4.08-2.49-4.08-2.49 0-2.87 1.94-2.87 3.95V23H7.44V8.2Z"/></svg></a></li>
        <li><a href="https://github.com/<?= jb_cms_h($content['github']) ?>" target="_blank" rel="noopener" aria-label="GitHub"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 .5C5.65.5.5 5.65.5 12c0 5.08 3.29 9.39 7.86 10.91.58.1.79-.25.79-.56v-2.14c-3.2.7-3.87-1.36-3.87-1.36-.52-1.33-1.28-1.68-1.28-1.68-1.04-.71.08-.7.08-.7 1.15.08 1.76 1.18 1.76 1.18 1.03 1.75 2.69 1.25 3.35.95.1-.74.4-1.25.73-1.54-2.56-.29-5.25-1.28-5.25-5.7 0-1.26.45-2.29 1.18-3.09-.12-.29-.51-1.47.11-3.05 0 0 .96-.31 3.16 1.18.92-.26 1.9-.38 2.88-.39.98.01 1.96.13 2.88.39 2.2-1.49 3.16-1.18 3.16-1.18.62 1.58.23 2.76.11 3.05.74.8 1.18 1.83 1.18 3.09 0 4.43-2.7 5.41-5.27 5.69.42.36.78 1.06.78 2.14v3.17c0 .31.21.67.8.56A11.51 11.51 0 0 0 23.5 12C23.5 5.65 18.35.5 12 .5Z"/></svg></a></li>
        <li><a href="https://instagram.com/<?= jb_cms_h($content['instagram']) ?>" target="_blank" rel="noopener" aria-label="Instagram"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7.5 2h9A5.51 5.51 0 0 1 22 7.5v9a5.51 5.51 0 0 1-5.5 5.5h-9A5.51 5.51 0 0 1 2 16.5v-9A5.51 5.51 0 0 1 7.5 2Zm0 2A3.5 3.5 0 0 0 4 7.5v9A3.5 3.5 0 0 0 7.5 20h9a3.5 3.5 0 0 0 3.5-3.5v-9A3.5 3.5 0 0 0 16.5 4h-9ZM12 7a5 5 0 1 1 0 10 5 5 0 0 1 0-10Zm0 2a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm5.25-2.35a1.1 1.1 0 1 1 0 2.2 1.1 1.1 0 0 1 0-2.2Z"/></svg></a></li>
    </ul>
    <section class="jb-mega-menu" id="jbMegaMenu" aria-label="Menu">
        <div class="jb-mega-links">
            <?php foreach ($menuItems as $menuIndex => $item): ?>
                <?php $classes=['jb-mega-link']; if(!empty($item['current']))$classes[]='is-current'; if(!empty($item['parent']))$classes[]='is-parent'; if(!empty($item['child']))$classes[]='is-child'; ?>
                <a href="<?= jb_cms_h($item['url']) ?>" class="<?= jb_cms_h(implode(' ', $classes)) ?>" style="--delay:<?= jb_cms_h((string)($menuIndex * .11)) ?>s"<?= !empty($item['current'])?' aria-current="page"':'' ?>><span><?= jb_cms_h($item['label']) ?></span><span class="jb-orbit" aria-hidden="true"></span></a>
            <?php endforeach; ?>
        </div>
        <div class="jb-menu-reel-mobile" aria-hidden="true"><?php jb_site_reel('', 'menu'); ?></div>
<div class="jb-mega-contact"><div><h2>Email</h2><a href="mailto:<?= jb_cms_h($content['email']) ?>"><?= jb_cms_h($content['email']) ?></a></div><div><h2>Social</h2><a href="https://instagram.com/<?= jb_cms_h($content['instagram']) ?>" target="_blank" rel="noopener">Instagram</a><a href="https://www.linkedin.com/in/<?= jb_cms_h($content['linkedin']) ?>" target="_blank" rel="noopener">LinkedIn</a><a href="https://github.com/<?= jb_cms_h($content['github']) ?>" target="_blank" rel="noopener">GitHub</a></div></div>
    </section>
<div class="jb-menu-reel" aria-hidden="true"><?php jb_site_reel('', 'menu'); ?></div>
</nav>
<a class="jb-detail-close" href="/work/" aria-label="Close project and return to Work"><span class="jb-detail-close-icon" aria-hidden="true"></span><span>Close</span></a>
<?php if (getenv('JB_PORTFOLIO_REVIEW') === '1' && !empty($page['_review_draft'])): ?><aside class="jb-review-notice">Draft preview · Hidden from the portfolio <a href="/review/">All drafts</a></aside><?php endif; ?>
<main class="jb-detail-main">
    <?php foreach (($page['components'] ?? []) as $blockIndex => $block) jb_render_component($block, $components, (int) $blockIndex); ?>
    <?php if ($studioPreview): ?>
        <?php foreach (array_keys($components) as $templateType): ?>
            <template data-studio-template="<?= jb_cms_h((string) $templateType) ?>">
                <?php jb_render_component(['type' => $templateType], $components, -1); ?>
            </template>
        <?php endforeach; ?>
    <?php endif; ?>
</main>
<footer class="jb-footer jb-work-site-footer" id="contact" data-studio-theme-color="footer_background">
    <div class="jb-footer-contact" data-studio-theme-color="footer_text"><div><h2>Email</h2><a class="jb-fold-underline" href="mailto:<?= jb_cms_h($content['email']) ?>"><?= jb_cms_h($content['email']) ?></a></div><div><h2>Social</h2><a href="https://instagram.com/<?= jb_cms_h($content['instagram']) ?>" target="_blank" rel="noopener">Instagram</a><a href="https://www.linkedin.com/in/<?= jb_cms_h($content['linkedin']) ?>" target="_blank" rel="noopener">LinkedIn</a><a href="https://github.com/<?= jb_cms_h($content['github']) ?>" target="_blank" rel="noopener">GitHub</a></div><span class="jb-footer-mark" aria-hidden="true"></span></div>
    <a href="#project-title" class="jb-footer-word" aria-label="Back to top" data-studio-theme-color="footer_word"><svg class="jb-footer-word-svg" viewBox="0 0 1720 320" aria-hidden="true"><text x="58" y="258">Jake Barton</text></svg><span class="jb-footer-word-mask" aria-hidden="true"><?php jb_site_reel('', 'footer'); ?></span></a>
    <div class="jb-footer-bottom" data-studio-theme-color="footer_text"><span>&copy; <?= date('Y') ?> Jake Barton. Website inspired by piranhabar.ie</span><nav aria-label="Footer links"><a href="/work/">Portfolio</a><a href="https://github.com/<?= jb_cms_h($content['github']) ?>" target="_blank" rel="noopener">GitHub</a><a href="https://www.linkedin.com/in/<?= jb_cms_h($content['linkedin']) ?>" target="_blank" rel="noopener">LinkedIn</a><a href="/assets/documents/jake-barton-resume.pdf" download>Resume</a></nav></div>
</footer>
<script src="/assets/js/project-media.js?v=20261103-project-rules" defer></script>
<script src="https://cdn.jsdelivr.net/npm/lenis@1.3.25/dist/lenis.min.js" defer></script><script src="/assets/js/site-scroll.js?v=20260715-sitewide" defer></script><script src="/assets/js/site-footer.js?v=20260715-detail-footer" defer></script><script src="/assets/js/work-loader.js?v=20260716-detail-menu" defer></script><?php if (!$studioPreview): ?><script src="/assets/js/page-loader.js?v=20261104-loader-fix" defer></script><?php endif; ?>
<?php if ($studioPreview): ?><script src="/assets/js/studio-preview-bridge.js?v=12"></script><?php endif; ?>
<script src="/assets/js/site-reel.js?v=20261109-player" defer></script>
</body></html>
