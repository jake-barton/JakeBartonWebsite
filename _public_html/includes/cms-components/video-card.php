<?php
declare(strict_types=1);

return [
    'type' => 'video_card',
    'label' => 'Looping video card',
    'fields' => ['video', 'zoom', 'padding_top', 'padding_right', 'padding_bottom', 'padding_left'],
    'render' => static function (array $block, int $index = 0): void {
        $source = trim((string) ($block['video'] ?? ''));
        $zoom = (float) jb_cms_number($block['zoom'] ?? 100, 100, 50, 200) / 100;
        $paddingTop = jb_cms_number($block['padding_top'] ?? 0, 0, 0, 240);
        $paddingRight = jb_cms_number($block['padding_right'] ?? 0, 0, 0, 240);
        $paddingBottom = jb_cms_number($block['padding_bottom'] ?? 0, 0, 0, 240);
        $paddingLeft = jb_cms_number($block['padding_left'] ?? 0, 0, 0, 240);
        ?>
        <section class="jb-video-card" data-studio-component="<?= $index ?>" style="--block-padding-top:<?= jb_cms_h($paddingTop) ?>px;--block-padding-right:<?= jb_cms_h($paddingRight) ?>px;--block-padding-bottom:<?= jb_cms_h($paddingBottom) ?>px;--block-padding-left:<?= jb_cms_h($paddingLeft) ?>px;--video-zoom:<?= jb_cms_h((string) $zoom) ?>">
            <?php if ($source !== ''): ?>
                <video src="<?= jb_cms_h(jb_cms_asset($source)) ?>" muted autoplay loop playsinline preload="metadata" disablepictureinpicture disableremoteplayback tabindex="-1" data-studio-video-field="video"></video>
            <?php else: ?>
                <div class="jb-video-card-empty" data-studio-video-field="video">Drop a video in Portfolio Studio</div>
            <?php endif; ?>
        </section>
        <?php
    },
];
