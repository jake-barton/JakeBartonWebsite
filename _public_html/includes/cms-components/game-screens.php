<?php
declare(strict_types=1);

return [
    'type' => 'game_screens',
    'label' => 'Game screenshots',
    'fields' => ['image', 'alt', 'image_2', 'alt_2'],
    'render' => static function (array $block, int $index = 0): void {
        ?>
        <section class="jb-game-screens" data-studio-component="<?= $index ?>" aria-label="Game screenshots">
            <?php foreach (['image' => 'alt', 'image_2' => 'alt_2'] as $image => $alt): ?>
                <?php if (!empty($block[$image])): ?>
                    <figure><img src="<?= jb_cms_h(jb_cms_asset($block[$image])) ?>" alt="<?= jb_cms_h($block[$alt] ?? '') ?>"<?= jb_cms_image_size_attributes($block[$image]) ?> loading="lazy" data-studio-image-field="<?= $image ?>"></figure>
                <?php endif; ?>
            <?php endforeach; ?>
        </section>
        <?php
    },
];
