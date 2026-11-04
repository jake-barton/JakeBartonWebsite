<?php
declare(strict_types=1);

return [
    'type' => 'project_links',
    'label' => 'Project link',
    'fields' => ['label', 'url'],
    'render' => static function (array $block, int $index = 0): void {
        $url = trim((string) ($block['url'] ?? ''));
        if (!preg_match('#^(?:https://|/(?!/))#i', $url)) return;
        ?>
        <div class="jb-project-links" data-studio-component="<?= $index ?>"><a class="jb-fold-underline" href="<?= jb_cms_h($url) ?>" target="_blank" rel="noopener"><?= jb_cms_h($block['label'] ?? 'Project link') ?></a></div>
        <?php
    },
];
