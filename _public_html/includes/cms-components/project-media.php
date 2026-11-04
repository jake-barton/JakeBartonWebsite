<?php
declare(strict_types=1);

return [
    'type' => 'project_media',
    'label' => 'Project image or video',
    'fields' => ['image', 'alt', 'video', 'format', 'background'],
    'render' => static function (array $block, int $index = 0): void {
        $portrait = ($block['format'] ?? '') === 'portrait';
        ?>
        <figure class="jb-project-media<?= $portrait ? ' is-portrait' : '' ?>" data-studio-component="<?= $index ?>" data-studio-color-field="background" style="--media-background:<?= jb_cms_h(jb_cms_color($block['background'] ?? '', '#101010')) ?>">
            <?php if (!empty($block['video'])): ?>
                <video src="<?= jb_cms_h(jb_cms_asset($block['video'])) ?>" poster="<?= jb_cms_h(jb_cms_asset($block['image'] ?? '')) ?>" aria-label="<?= jb_cms_h($block['alt'] ?? 'Project video') ?>" muted autoplay loop playsinline preload="metadata" disablepictureinpicture disableremoteplayback tabindex="-1" data-studio-video-field="video"></video>
            <?php elseif (!empty($block['image'])): ?>
                <img src="<?= jb_cms_h(jb_cms_asset($block['image'])) ?>" alt="<?= jb_cms_h($block['alt'] ?? '') ?>"<?= jb_cms_image_size_attributes($block['image']) ?> loading="lazy" data-studio-image-field="image">
            <?php endif; ?>
        </figure>
        <?php
    },
];
