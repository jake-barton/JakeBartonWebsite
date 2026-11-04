<?php
declare(strict_types=1);

return [
    'type' => 'media_pair',
    'label' => 'Side-by-side images or videos',
    'fields' => ['image', 'video', 'alt', 'image_2', 'video_2', 'alt_2', 'layout', 'sync_videos'],
    'render' => static function (array $block, int $index = 0): void {
        $layout = in_array($block['layout'] ?? '', ['gameplay_phone', 'portraits'], true)
            ? ' is-' . str_replace('_', '-', $block['layout']) : '';
        $synced = !empty($block['video']) && !empty($block['video_2'])
            && in_array($block['sync_videos'] ?? true, [true, 1, '1', 'true', 'on'], true);
        ?>
        <section class="jb-media-pair<?= jb_cms_h($layout) ?>" data-studio-component="<?= $index ?>" aria-label="Project media"<?= $synced ? ' data-synced-videos' : '' ?>>
            <?php foreach (['', '_2'] as $suffix): ?>
                <?php if (empty($block['image' . $suffix]) && empty($block['video' . $suffix])) continue; ?>
                <figure>
                    <?php if (!empty($block['video' . $suffix])): ?>
                        <video src="<?= jb_cms_h(jb_cms_asset($block['video' . $suffix])) ?>" poster="<?= jb_cms_h(jb_cms_asset($block['image' . $suffix] ?? '')) ?>" aria-label="<?= jb_cms_h($block['alt' . $suffix] ?? 'Project video') ?>" muted playsinline preload="metadata" disablepictureinpicture disableremoteplayback tabindex="-1"<?= $synced ? '' : ' autoplay loop' ?> data-studio-video-field="video<?= $suffix ?>"></video>
                    <?php else: ?>
                        <img src="<?= jb_cms_h(jb_cms_asset($block['image' . $suffix])) ?>" alt="<?= jb_cms_h($block['alt' . $suffix] ?? '') ?>"<?= jb_cms_image_size_attributes($block['image' . $suffix]) ?> loading="lazy" data-studio-image-field="image<?= $suffix ?>">
                    <?php endif; ?>
                </figure>
            <?php endforeach; ?>
        </section>
        <?php
    },
];
