<?php
/** One curated reel for the homepage and shared site presentation. */
function jb_site_reel(string $class, string $context): void {
    $escape = static fn($value) => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    $preload = $context === 'hero' ? 'auto' : (in_array($context, ['menu', 'transition'], true) ? 'metadata' : 'none');
    $attributes = $context === 'action' ? ' data-video-index="reel"' : ($context === 'footer' ? ' data-footer-video' : '');
    echo '<video class="' . $escape($class) . '" src="/assets/reel/portfolio-reel.mp4?v=20261104-r3" poster="/assets/reel/portfolio-reel-poster.webp?v=20261104-r3" muted loop playsinline preload="' . $preload . '" disablepictureinpicture disableremoteplayback tabindex="-1" aria-hidden="true" data-site-reel="' . $escape($context) . '"' . $attributes . '></video>';
}
