<?php
declare(strict_types=1);

return [
    'type' => 'mario_kart',
    'label' => 'Playable Mario Kart',
    'fields' => [],
    'render' => static function (array $block, int $index = 0): void {
        ?>
        <section class="jb-game" data-game-embed data-studio-component="<?= $index ?>" aria-label="Mario Kart racing demo">
            <div class="jb-game-viewport" data-game-viewport data-lenis-prevent>
                <iframe src="/games/mario-kart/" title="Playable Mario Kart racing demo" allow="fullscreen" allowfullscreen></iframe>
            </div>
        </section>
        <?php
    },
];
