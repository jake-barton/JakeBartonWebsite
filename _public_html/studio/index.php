<?php
require_once __DIR__ . '/../includes/cms.php';
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: no-referrer');

function studio_redirect(string $url): never { header('Location: ' . $url, true, 303); exit; }
function studio_upload(string $key): ?string {
    if (empty($_FILES[$key]['tmp_name']) || !is_uploaded_file($_FILES[$key]['tmp_name'])) return null;
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($_FILES[$key]['tmp_name']);
    $extensions = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp','image/gif'=>'gif','image/svg+xml'=>'svg','application/xml'=>'svg','text/xml'=>'svg','video/mp4'=>'mp4','video/webm'=>'webm','video/ogg'=>'ogv','video/quicktime'=>'mov'];
    if (!isset($extensions[$mime])) return null;
    $isVideo = str_starts_with((string) $mime, 'video/');
    if ((int) $_FILES[$key]['size'] > ($isVideo ? 100 : 12) * 1024 * 1024) return null;
    if ($extensions[$mime] === 'svg') {
        $svg = (string) file_get_contents($_FILES[$key]['tmp_name']);
        if ($svg === '' || !str_contains($svg, '<svg') || preg_match('/<(script|foreignObject|iframe|object|embed)\b|\son\w+\s*=|javascript:|(?:href|src)\s*=\s*["\'](?:https?:)?\/\//i', $svg)) return null;
    }
    $directory = dirname(__DIR__) . '/assets/uploads';
    if (!is_dir($directory)) @mkdir($directory, 0755, true);
    $name = gmdate('Ymd-His') . '-' . bin2hex(random_bytes(5)) . '.' . $extensions[$mime];
    if (!move_uploaded_file($_FILES[$key]['tmp_name'], $directory . '/' . $name)) return null;
    return '/assets/uploads/' . $name;
}

function studio_component_preview(string $type): string {
    return match ($type) {
        'hero' => '<span class="studio-real-preview is-hero"><img src="/assets/project-covers/80181D04-2E88-4BFD-8A70-A8841E0A647A.png" alt=""><i></i><span><small>IMAGE OR LOOPING VIDEO</small><strong>Hero media<br>+ title.</strong></span></span>',
        'intro' => '<span class="studio-real-preview is-intro"><span class="preview-title">Project title</span><span class="preview-meta"><b>ROLE</b><small>Designer + Developer</small><b>DISCIPLINES</b><small>AI, Product Design, Web</small></span><span class="preview-story">A focused project story sits here, paired with the essential credits and details.</span></span>',
        'artwork' => '<span class="studio-real-preview is-artwork"><img class="preview-decoration one" src="/assets/work/tech-birmingham-ai/brand-shape-1.svg" alt=""><img class="preview-logo" src="/assets/work/tech-birmingham-ai/tech-birmingham-logo.jpeg" alt=""><img class="preview-decoration two" src="/assets/work/tech-birmingham-ai/brand-shape-2.svg" alt=""></span>',
        'product' => '<span class="studio-real-preview is-product"><strong>One workspace for<br>research and export.</strong><span class="preview-window"><img src="/assets/work/tech-birmingham-ai/tech-birmingham-ai-redacted.png" alt=""></span><small>Supporting caption</small></span>',
        'quote' => '<span class="studio-real-preview is-quote"><strong>“A bold idea deserves room to breathe.”</strong><small>Read the full story ↗</small></span>',
        default => '<span class="studio-real-preview is-custom"><strong>Custom component</strong><small>Your reusable site component</small></span>',
    };
}

$error = '';
$notice = (string) ($_GET['notice'] ?? '');
$action = (string) ($_POST['action'] ?? '');

if ($action === 'setup' && jb_cms_can_initialize()) {
    if (!jb_cms_valid_csrf($_POST['csrf'] ?? null)) $error = 'Your session expired. Refresh and try again.';
    elseif ((string) ($_POST['password'] ?? '') !== (string) ($_POST['confirm_password'] ?? '')) $error = 'The passwords do not match.';
    elseif (!jb_cms_set_password((string) $_POST['password'])) $error = 'Use at least 14 characters.';
    else { jb_cms_login((string) $_POST['password']); studio_redirect('/studio/?notice=ready'); }
}

if ($action === 'login' && !jb_cms_authenticated()) {
    $lockedUntil = (int) ($_SESSION['jb_cms_locked_until'] ?? 0);
    if ($lockedUntil > time()) $error = 'Too many attempts. Try again in a few minutes.';
    elseif (!jb_cms_valid_csrf($_POST['csrf'] ?? null)) $error = 'Your session expired. Refresh and try again.';
    elseif (jb_cms_login((string) ($_POST['password'] ?? ''))) { unset($_SESSION['jb_cms_attempts'], $_SESSION['jb_cms_locked_until']); studio_redirect('/studio/'); }
    else {
        $_SESSION['jb_cms_attempts'] = (int) ($_SESSION['jb_cms_attempts'] ?? 0) + 1;
        if ($_SESSION['jb_cms_attempts'] >= 5) $_SESSION['jb_cms_locked_until'] = time() + 900;
        $error = 'Incorrect password.';
    }
}

if ($action === 'logout' && jb_cms_authenticated() && jb_cms_valid_csrf($_POST['csrf'] ?? null)) { jb_cms_logout(); studio_redirect('/studio/'); }

if (jb_cms_authenticated() && $_SERVER['REQUEST_METHOD'] === 'POST' && !in_array($action, ['logout'], true)) {
    if (!jb_cms_valid_csrf($_POST['csrf'] ?? null)) { http_response_code(403); exit('Invalid request token.'); }
    if ($action === 'create') {
        $title = trim((string) ($_POST['title'] ?? 'New project'));
        $slug = jb_cms_slug((string) ($_POST['slug'] ?? $title));
        if ($slug === '' || jb_cms_load_page($slug)) $error = 'Choose a unique page URL.';
        else {
            $page = json_decode((string) file_get_contents(__DIR__ . '/../includes/project-blueprint.json'), true);
            $page['title'] = $title; $page['slug'] = $slug;
            $page['components'][0]['title'] = $title;
            $page['components'][1]['heading'] = $title;
            jb_cms_save_page($page); studio_redirect('/studio/?edit=' . rawurlencode($slug) . '&notice=created');
        }
    }
    if ($action === 'toggle_visibility') {
        $slug = jb_cms_slug((string) ($_POST['slug'] ?? ''));
        $page = jb_cms_load_page($slug);
        if ($page) {
            $page['published'] = empty($page['published']);
            jb_cms_save_page($page);
        }
        $return = !empty($_POST['return_edit']) ? '?edit=' . rawurlencode($slug) . '&notice=visibility-updated' : '?notice=visibility-updated';
        studio_redirect('/studio/' . $return);
    }
    if ($action === 'delete') {
        $slug = jb_cms_slug((string) ($_POST['slug'] ?? ''));
        jb_cms_delete_page($slug); studio_redirect('/studio/?notice=deleted');
    }
    if ($action === 'save') {
        $original = jb_cms_slug((string) ($_POST['original_slug'] ?? ''));
        $slug = jb_cms_slug((string) ($_POST['slug'] ?? ''));
        $cover = studio_upload('cover_upload') ?: trim((string) ($_POST['cover'] ?? ''));
        $blocks = [];
        foreach (($_POST['components'] ?? []) as $index => $raw) {
            if (!is_array($raw) || empty($raw['type'])) continue;
            $block = [];
            foreach ($raw as $key => $value) {
                if (!preg_match('/^[a-z_][a-z0-9_]*$/', (string) $key)) continue;
                if (str_ends_with((string) $key, '_label') && trim((string) $value) === '') continue;
                if ($key === 'paragraphs') $block[$key] = array_values(array_filter(array_map('trim', preg_split('/\R{2,}/', (string) $value) ?: [])));
                else $block[$key] = trim((string) $value);
            }
            $upload = studio_upload('component_image_' . $index);
            if ($upload) $block['image'] = $upload;
            foreach (['decoration_1', 'decoration_2', 'video'] as $assetField) {
                $assetUpload = studio_upload('component_asset_' . $index . '_' . $assetField);
                if ($assetUpload) $block[$assetField] = $assetUpload;
            }
            $blocks[] = $block;
        }
        $page = [
            'slug'=>$slug, 'title'=>trim((string) ($_POST['title'] ?? '')), 'subtitle'=>trim((string) ($_POST['subtitle'] ?? '')),
            'description'=>trim((string) ($_POST['description'] ?? '')), 'cover'=>$cover,
            'tags'=>array_values(array_filter(array_map('trim', explode(',', (string) ($_POST['tags'] ?? ''))))),
            'filters'=>array_values(array_filter(array_map('trim', explode(',', strtolower((string) ($_POST['filters'] ?? '')))))),
            'published'=>isset($_POST['published']), 'sort_order'=>(int) ($_POST['sort_order'] ?? 100),
            'card'=>[
                'video_preview'=>isset($_POST['card_video_preview']),
                'image_position'=>trim((string) ($_POST['card_image_position'] ?? 'center')),
                'image_fit'=>in_array(($_POST['card_image_fit'] ?? 'cover'), ['cover', 'contain'], true) ? $_POST['card_image_fit'] : 'cover',
                'image_scale'=>jb_cms_number($_POST['card_image_scale'] ?? 100, 100, 20, 100),
                'background'=>jb_cms_color($_POST['card_background'] ?? '', '#111111'),
                'overlay_opacity'=>jb_cms_opacity($_POST['card_overlay_opacity'] ?? 0.34),
                'overlay_color'=>jb_cms_color($_POST['card_overlay_color'] ?? '', '#000000'),
                'corner_radius'=>jb_cms_number($_POST['card_corner_radius'] ?? 28, 28, 0, 80),
                'text_color'=>jb_cms_color($_POST['card_text_color'] ?? '', '#ffffff'),
                'tag_background'=>jb_cms_color($_POST['card_tag_background'] ?? '', '#c6c5bc'),
                'tag_text'=>jb_cms_color($_POST['card_tag_text'] ?? '', '#ffffff'),
            ],
            'theme'=>[
                'canvas'=>jb_cms_color($_POST['theme']['canvas'] ?? '', '#e7e6df'),
                'ink'=>jb_cms_color($_POST['theme']['ink'] ?? '', '#132b4f'),
                'accent'=>jb_cms_color($_POST['theme']['accent'] ?? '', '#23883c'),
                'rail'=>jb_cms_color($_POST['theme']['rail'] ?? '', '#23883c'),
                'footer_background'=>jb_cms_color($_POST['theme']['footer_background'] ?? '', '#e7e6df'),
                'footer_text'=>jb_cms_color($_POST['theme']['footer_text'] ?? '', '#111111'),
                'footer_word'=>jb_cms_color($_POST['theme']['footer_word'] ?? '', '#23883c'),
            ],
            'components'=>$blocks,
        ];
        if ($slug === '' || ($slug !== $original && jb_cms_load_page($slug))) $error = 'Choose a unique page URL.';
        elseif (jb_cms_save_page($page)) { if ($original !== $slug) jb_cms_delete_page($original); studio_redirect('/studio/?edit=' . rawurlencode($slug) . '&notice=saved'); }
        else $error = 'The page could not be saved. Check storage permissions.';
    }
}

$authenticated = jb_cms_authenticated();
$editing = $authenticated && !empty($_GET['edit']) ? jb_cms_load_page((string) $_GET['edit']) : null;
$definitions = jb_cms_components();
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow"><title>Portfolio Studio</title><link rel="stylesheet" href="/assets/css/studio.css?v=9"></head>
<body>
<?php if (!$authenticated): ?>
<main class="studio-login"><div class="studio-login-card"><img src="/assets/brand/jb-logo.png" alt=""><p class="studio-kicker">PRIVATE PORTFOLIO STUDIO</p>
<h1><?= jb_cms_can_initialize() ? 'Create your secure password.' : 'Welcome back.' ?></h1>
<?php if ($error): ?><p class="studio-alert"><?= jb_cms_h($error) ?></p><?php endif; ?>
<?php if (jb_cms_can_initialize()): ?><p>This one-time setup is only available from localhost. Use at least 14 characters; only a one-way password hash is stored.</p><form method="post"><input type="hidden" name="csrf" value="<?= jb_cms_h(jb_cms_csrf()) ?>"><input type="hidden" name="action" value="setup"><label>Password<input type="password" name="password" minlength="14" required autocomplete="new-password"></label><label>Confirm password<input type="password" name="confirm_password" minlength="14" required autocomplete="new-password"></label><button>Secure the Studio</button></form>
<?php elseif (jb_cms_auth_hash() === null): ?><p>The Studio has not been configured. Set <code>JB_CMS_PASSWORD_HASH</code> on the server or initialize it locally first.</p>
<?php else: ?><form method="post"><input type="hidden" name="csrf" value="<?= jb_cms_h(jb_cms_csrf()) ?>"><input type="hidden" name="action" value="login"><label>Password<input type="password" name="password" required autocomplete="current-password" autofocus></label><button>Enter Studio</button></form><?php endif; ?>
</div></main>
<?php else: ?>
<header class="studio-topbar"><div class="studio-topbar-left"><button type="button" class="studio-sidebar-toggle" data-sidebar-toggle aria-label="Collapse page sidebar">☰</button><a href="/studio/" class="studio-brand"><img src="/assets/brand/jb-logo.png" alt="">Portfolio Studio</a></div><nav><a href="/work/" target="_blank">View website ↗</a><form method="post"><input type="hidden" name="csrf" value="<?= jb_cms_h(jb_cms_csrf()) ?>"><input type="hidden" name="action" value="logout"><button>Log out</button></form></nav></header>
<?php if ($notice): ?><div class="studio-notice"><?= jb_cms_h(ucfirst($notice)) ?>.</div><?php endif; ?><?php if ($error): ?><div class="studio-notice is-error"><?= jb_cms_h($error) ?></div><?php endif; ?>
<div class="studio-shell"><aside class="studio-sidebar"><h2>Work pages</h2><a class="studio-new" href="/studio/?new=1">+ New page</a><ul><?php foreach (jb_cms_pages() as $item): ?><li><a class="<?= $editing && $editing['slug'] === $item['slug'] ? 'is-active' : '' ?>" href="/studio/?edit=<?= rawurlencode($item['slug']) ?>"><span><?= jb_cms_h($item['title']) ?></span><small><?= !empty($item['published']) ? 'Live' : 'Hidden' ?></small></a><form method="post" class="studio-visibility-form"><input type="hidden" name="csrf" value="<?= jb_cms_h(jb_cms_csrf()) ?>"><input type="hidden" name="action" value="toggle_visibility"><input type="hidden" name="slug" value="<?= jb_cms_h($item['slug']) ?>"><button type="submit" class="studio-visibility <?= !empty($item['published']) ? 'is-live' : '' ?>" aria-label="<?= !empty($item['published']) ? 'Hide' : 'Publish' ?> <?= jb_cms_h($item['title']) ?>" title="<?= !empty($item['published']) ? 'Hide card and page' : 'Show card and page' ?>"><span></span></button></form></li><?php endforeach; ?></ul></aside>
<main class="studio-main">
<?php if (isset($_GET['new'])): ?><section class="studio-panel studio-create"><p class="studio-kicker">NEW WORK PAGE</p><h1>New portfolio page.</h1><p>Start with a header, project information, and media. New pages stay hidden until published.</p><form method="post"><input type="hidden" name="csrf" value="<?= jb_cms_h(jb_cms_csrf()) ?>"><input type="hidden" name="action" value="create"><label>Project title<input name="title" required data-title-slug></label><label>Page URL<input name="slug" required pattern="[a-z0-9-]+" data-slug></label><button>Create page</button></form></section>
<?php elseif ($editing): ?><form class="studio-editor" method="post" enctype="multipart/form-data" novalidate><input type="hidden" name="csrf" value="<?= jb_cms_h(jb_cms_csrf()) ?>"><input type="hidden" name="action" value="save"><input type="hidden" name="original_slug" value="<?= jb_cms_h($editing['slug']) ?>"><input type="hidden" name="card_overlay_color" value="<?= jb_cms_h($editing['card']['overlay_color']??'#000000') ?>"><input type="hidden" name="card_background" value="<?= jb_cms_h($editing['card']['background']??'#111111') ?>"><input type="hidden" name="card_image_fit" value="<?= jb_cms_h($editing['card']['image_fit']??'cover') ?>"><input type="hidden" name="card_image_scale" value="<?= jb_cms_h($editing['card']['image_scale']??100) ?>">
<div class="studio-editor-head"><div><p class="studio-kicker">EDITING · <?= !empty($editing['published']) ? 'LIVE' : 'HIDDEN' ?></p><h1><?= jb_cms_h($editing['title']) ?></h1></div><div class="studio-actions"><a href="/work/<?= jb_cms_h($editing['slug']) ?>/" target="_blank">Open preview ↗</a><button type="button" class="studio-undo" data-studio-undo disabled aria-label="Undo last change"><span>↶</span> Undo</button><button type="submit" data-studio-save formnovalidate>Save changes</button></div></div>
<section class="studio-engine" data-studio-engine>
  <button type="button" class="studio-engine-reopen" data-engine-expand aria-label="Reopen page sections" title="Reopen page sections">›</button>
  <aside class="studio-engine-hierarchy"><header><div><small>SCENE</small><h2>Page sections</h2></div><button type="button" data-engine-collapse title="Collapse hierarchy">‹</button></header><div class="studio-scene-list"><button type="button" class="studio-scene-page" data-page-inspector><span>◆</span>Page + card settings</button><?php foreach (($editing['components'] ?? []) as $sceneIndex=>$sceneBlock): $sceneType=$sceneBlock['type']??''; ?><button type="button" draggable="true" data-scene-component="<?= (int)$sceneIndex ?>"><span><?= str_pad((string)($sceneIndex+1),2,'0',STR_PAD_LEFT) ?></span><?= jb_cms_h($definitions[$sceneType]['label']??$sceneType) ?></button><?php endforeach; ?></div><button type="button" class="studio-scene-add" data-scroll-add>+ Add section</button></aside>
  <div class="studio-engine-viewport"><header><div><span class="studio-engine-dot"></span> PHP PREVIEW</div><div><button type="button" data-viewport-size="desktop">Desktop</button><button type="button" data-viewport-size="tablet">Tablet</button><button type="button" data-viewport-size="mobile">Mobile</button></div></header><div class="studio-viewport-frame"><iframe id="studioVisualPreview" src="/work/<?= jb_cms_h($editing['slug']) ?>/?studio-preview=1" title="Visual editor for <?= jb_cms_h($editing['title']) ?>"></iframe></div></div>
  <aside class="studio-inspector"><header><small>INSPECTOR</small><h2 data-inspector-title>Select something</h2><p data-inspector-help>Click text, an image, or a section inside the preview.</p></header><div class="studio-inspector-properties" data-inspector-properties></div><div class="studio-inspector-text" data-inspector-text hidden><label>Selected text<textarea data-visual-text rows="6"></textarea></label><small>Changes appear immediately and save with the page.</small></div><div class="studio-inspector-image" data-inspector-image hidden><label>Replace selected image<input type="file" data-visual-image accept="image/png,image/jpeg,image/webp,image/gif"></label><small>The replacement previews immediately and uploads when you save.</small></div><div class="studio-inspector-colors"><h3>Color anything</h3><div class="studio-color-swatches"><?php foreach(['#23883c','#132b4f','#79add9','#e7e6df','#111111','#ffffff','#ef2b25','#ffc928'] as $swatch): ?><button type="button" draggable="true" data-drag-color="<?= $swatch ?>" style="--swatch:<?= $swatch ?>" aria-label="Paint with <?= $swatch ?>"></button><?php endforeach; ?></div><label>Custom color<input type="color" value="#23883c" data-custom-swatch></label><button type="button" data-apply-custom-color>Apply custom color</button><p data-color-help>Click a color to recolor the selection, or drag a color directly onto text, a background, the rail, canvas, or footer.</p></div><div class="studio-inspector-actions" data-component-actions hidden><button type="button" data-duplicate-component>Duplicate section</button><button type="button" class="is-danger" data-delete-component>Delete section</button></div></aside>
</section>
<?php $cardSettings=is_array($editing['card']??null)?$editing['card']:[]; ?><section class="studio-panel studio-fields"><h2>Page settings</h2><div class="studio-grid"><label>Project title<input name="title" value="<?= jb_cms_h($editing['title']) ?>" required></label><label>Page URL<input name="slug" value="<?= jb_cms_h($editing['slug']) ?>" pattern="[a-z0-9-]+" required></label><label>Card subtitle<input name="subtitle" value="<?= jb_cms_h($editing['subtitle'] ?? '') ?>"></label><label>Sort order<input name="sort_order" type="number" value="<?= (int) ($editing['sort_order'] ?? 100) ?>"></label><label class="is-wide studio-text-editor">SEO description<textarea name="description" data-autogrow><?= jb_cms_h($editing['description'] ?? '') ?></textarea></label><label>Card tags <small>comma separated</small><input name="tags" value="<?= jb_cms_h(implode(', ', $editing['tags'] ?? [])) ?>"></label><label>Filters <small>games, programming, web, design, 3d</small><input name="filters" value="<?= jb_cms_h(implode(', ', $editing['filters'] ?? [])) ?>"></label><input name="card_image_position" value="<?= jb_cms_h($cardSettings['image_position']??'center') ?>"><input name="card_overlay_opacity" value="<?= jb_cms_h($cardSettings['overlay_opacity']??.34) ?>"><input name="card_corner_radius" value="<?= jb_cms_h($cardSettings['corner_radius']??28) ?>"><input name="card_text_color" value="<?= jb_cms_h($cardSettings['text_color']??'#ffffff') ?>"><input name="card_tag_background" value="<?= jb_cms_h($cardSettings['tag_background']??'#c6c5bc') ?>"><input name="card_tag_text" value="<?= jb_cms_h($cardSettings['tag_text']??'#ffffff') ?>"><label class="is-wide">Card cover<div class="studio-image-editor" data-image-editor><img src="<?= jb_cms_h(jb_cms_asset($editing['cover'] ?? '')) ?>" alt="Current card cover" data-image-preview><div><strong>Drop a new image here</strong><span>or click to choose one</span></div><input type="file" name="cover_upload" accept="image/png,image/jpeg,image/webp,image/gif" data-image-file></div><details><summary>Image path</summary><input name="cover" value="<?= jb_cms_h($editing['cover'] ?? '') ?>" data-image-path></details></label><label class="studio-check"><input type="checkbox" name="card_video_preview" <?= filter_var($cardSettings['video_preview'] ?? true, FILTER_VALIDATE_BOOLEAN) ? 'checked' : '' ?>> Play header video on card hover</label><label class="studio-check"><input type="checkbox" name="published" <?= !empty($editing['published']) ? 'checked' : '' ?>> Show this card and page publicly</label></div></section>
<?php $editingTheme=is_array($editing['theme']??null)?$editing['theme']:[]; $themeFields=['canvas'=>'Page canvas','ink'=>'Page text','accent'=>'Accent','rail'=>'Left rail','footer_background'=>'Footer background','footer_text'=>'Footer text','footer_word'=>'Footer wordmark']; ?><section class="studio-panel studio-fields"><h2>Page colors</h2><p>These colors control the full page. Individual visual sections can override their own background and text colors below.</p><div class="studio-color-grid"><?php foreach($themeFields as $themeKey=>$themeLabel): $themeFallback=['canvas'=>'#e7e6df','ink'=>'#132b4f','accent'=>'#23883c','rail'=>'#23883c','footer_background'=>'#e7e6df','footer_text'=>'#111111','footer_word'=>'#23883c'][$themeKey]; ?><label><?= jb_cms_h($themeLabel) ?><span class="studio-color-control"><input type="color" value="<?= jb_cms_h(jb_cms_color($editingTheme[$themeKey]??'', $themeFallback)) ?>" data-color-picker><input name="theme[<?= jb_cms_h($themeKey) ?>]" value="<?= jb_cms_h(jb_cms_color($editingTheme[$themeKey]??'', $themeFallback)) ?>" pattern="#[0-9a-fA-F]{6}" data-color-value></span></label><?php endforeach; ?></div></section>
<div id="studioComponents"><?php foreach (($editing['components'] ?? []) as $index => $block): ?><?php $type=$block['type']; $definition=$definitions[$type] ?? ['label'=>$type,'fields'=>array_keys($block)]; ?><section class="studio-panel studio-component" data-component draggable="true"><div class="studio-component-head"><div class="studio-drag-handle" title="Drag to reorder"><b>⋮⋮</b><span>SECTION <?= $index + 1 ?></span><h2><?= jb_cms_h($definition['label'] ?? $type) ?></h2></div><div><button type="button" data-move="up" aria-label="Move up">↑</button><button type="button" data-move="down" aria-label="Move down">↓</button><button type="button" class="is-danger" data-remove>Remove</button></div></div><input type="hidden" data-field="type" value="<?= jb_cms_h($type) ?>"><?php foreach (($definition['fields'] ?? []) as $field): ?><?php $value=$block[$field] ?? ''; $isColor=in_array($field,['background','text_color','overlay_color'],true); ?><label class="<?= in_array($field,['paragraphs','title','quote','caption'],true)?'studio-text-editor':'' ?>"><?= jb_cms_h(ucwords(str_replace('_',' ',$field))) ?><?php if ($field==='paragraphs'): ?><textarea data-field="<?= jb_cms_h($field) ?>" rows="9" data-autogrow><?= jb_cms_h(implode("\n\n", is_array($value)?$value:[$value])) ?></textarea><?php elseif($field==='image'): ?><div class="studio-image-editor" data-image-editor><img src="<?= jb_cms_h(jb_cms_asset($value)) ?>" alt="Current section image" data-image-preview><div><strong>Drop a replacement image</strong><span>or click to choose one</span></div><input type="file" data-image-upload data-image-file accept="image/png,image/jpeg,image/webp,image/gif"></div><details><summary>Image path</summary><input data-field="image" value="<?= jb_cms_h($value) ?>" data-image-path></details><?php else: ?><input data-field="<?= jb_cms_h($field) ?>" value="<?= jb_cms_h($value) ?>"<?= $isColor?' type="color"':'' ?><?= $field==='overlay_opacity'?' type="number" min="0" max="1" step="0.05"':'' ?>><?php endif; ?></label><?php endforeach; ?></section><?php endforeach; ?></div>
<section class="studio-add"><div class="studio-add-dialog"><header><div><small>COMPONENT LIBRARY</small><h2>Add a section</h2></div><button type="button" data-close-add aria-label="Close component library">×</button></header><div class="studio-add-grid"><?php foreach ($definitions as $type=>$definition): ?><button type="button" data-add-component="<?= jb_cms_h($type) ?>" data-label="<?= jb_cms_h($definition['label'] ?? $type) ?>" data-fields="<?= jb_cms_h(json_encode($definition['fields'] ?? [])) ?>"><?= studio_component_preview((string)$type) ?><span class="studio-component-description"><strong><?= jb_cms_h($definition['label'] ?? $type) ?></strong><small><?= jb_cms_h(['hero'=>'Full-width image with bold project title','intro'=>'Project facts paired with long-form story','artwork'=>'Flexible branded artwork or logo stage','product'=>'Headline, product image, and caption','quote'=>'Oversized quote with an optional link'][$type] ?? 'Custom portfolio section') ?></small></span></button><?php endforeach; ?></div></div></section></form>
<form class="studio-delete" method="post" onsubmit="return confirm('Delete this page permanently?')"><input type="hidden" name="csrf" value="<?= jb_cms_h(jb_cms_csrf()) ?>"><input type="hidden" name="action" value="delete"><input type="hidden" name="slug" value="<?= jb_cms_h($editing['slug']) ?>"><button>Delete page</button></form>
<?php else: ?><section class="studio-panel studio-welcome"><p class="studio-kicker">PORTFOLIO CMS</p><h1>Portfolio pages</h1><p>Select a page to edit, or create a new portfolio page.</p></section><?php endif; ?>
</main></div><script src="/assets/js/studio.js?v=19"></script>
<?php endif; ?></body></html>
