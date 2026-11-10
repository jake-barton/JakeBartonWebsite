<?php
/** Read-only views of real project excerpts. The source projects are never executed. */
function jb_source_viewer(array $ids, string $label = 'Project source', bool $projectTabs = false, bool $showContext = true): void {
    static $count = 0;
    $catalogue = require __DIR__ . '/source-catalogue.php';
    $items = [];
    foreach ($ids as $id) {
        if (!isset($catalogue[$id])) continue;
        $item = $catalogue[$id];
        $file = dirname(__DIR__) . $item['source'];
        if (!is_file($file)) continue;
        $item['id'] = $id;
        if ($item['kind'] === 'code') $item['code'] = file_get_contents($file);
        else $item['data'] = json_decode(file_get_contents($file), true, 512, JSON_THROW_ON_ERROR);
        $items[] = $item;
    }
    if (!$items) return;
    $uid = 'source-viewer-' . ++$count;
    $escape = static fn($value) => htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    ?>
    <div class="jb-source-workbench" data-source-viewer aria-label="<?= $escape($label) ?>">
        <div class="jb-source-tabs"<?= count($items) === 1 ? ' hidden' : '' ?> role="tablist" aria-label="<?= $escape($label) ?>">
            <?php foreach ($items as $i => $item): ?>
                <button type="button" role="tab" id="<?= $uid ?>-tab-<?= $i ?>" aria-selected="<?= $i === 0 ? 'true' : 'false' ?>" aria-controls="<?= $uid ?>-panel-<?= $i ?>" tabindex="<?= $i === 0 ? '0' : '-1' ?>" data-source-tab="<?= $i ?>"><?= $escape($projectTabs ? $item['project'] : $item['label']) ?></button>
            <?php endforeach; ?>
        </div>
        <?php foreach ($items as $i => $item): ?>
            <div class="jb-source-panel" id="<?= $uid ?>-panel-<?= $i ?>" role="tabpanel" aria-labelledby="<?= $uid ?>-tab-<?= $i ?>" data-source-panel="<?= $i ?>" style="--source-height:<?= $item['kind'] === 'code' ? min(480, max(160, (substr_count($item['code'], "\n") + 2) * 22 + 32)) : 520 ?>px"<?= $i ? ' hidden' : '' ?>>
                <div class="jb-source-toolbar">
                    <span class="jb-source-filename"><?= $escape($item['file']) ?><?= isset($item['graph']) ? ' / ' . $escape($item['graph']) : '' ?></span>
                    <div class="jb-source-actions"><button type="button" data-source-expand>Expand</button><button type="button" data-source-close hidden>Close viewer</button><button type="button" data-source-copy><?= $item['kind'] === 'code' ? 'Copy code' : 'Copy graph' ?></button><a href="<?= $escape($item['source']) ?>" download>Download</a></div>
                </div>
                <div class="jb-source-stage" data-source-stage>
                    <?php if ($item['kind'] === 'code'): ?>
                        <pre class="jb-source-fallback" tabindex="0" aria-label="<?= $escape($item['file']) ?> source code"><code><?php foreach (explode("\n", rtrim($item['code'], "\n")) as $line => $text): ?><span data-line="<?= $item['start'] + $line ?>"><?= $escape($text) ?>
</span><?php endforeach; ?></code></pre>
                    <?php else: ?>
                        <div class="jb-blueprint-fallback"><p><?= $escape($item['graph']) ?></p><ul><?php foreach ($item['data']['nodes'] as $node): ?><li><?= $escape($node['title']) ?></li><?php endforeach; ?></ul></div>
                    <?php endif; ?>
                </div>
                <div class="jb-source-status"><span><?= $escape($item['language']) ?> · Read only<?= isset($item['start']) ? ' · Lines ' . $item['start'] . '–' . $item['end'] : '' ?></span><span data-source-feedback aria-live="polite"></span></div>
            </div>
        <?php endforeach; ?>
        <?php if ($showContext): ?><div class="jb-source-context"><p data-source-summary><?= $escape($items[0]['summary']) ?></p><?php if ($projectTabs): ?><a class="jb-fold-underline" data-source-project href="<?= $escape($items[0]['url']) ?>"><?= $escape($items[0]['project']) ?></a><?php endif; ?></div>
        <?php endif; ?><script type="application/json" data-source-payload><?= json_encode($items, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR) ?></script>
    </div>
    <?php
}
