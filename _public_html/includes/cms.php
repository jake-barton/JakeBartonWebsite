<?php
declare(strict_types=1);

const JB_CMS_SESSION_KEY = 'jb_cms_authenticated';
const JB_CMS_CSRF_KEY = 'jb_cms_csrf';

function jb_cms_root(): string
{
    $configured = getenv('JB_CMS_DATA_DIR');
    return $configured !== false && $configured !== ''
        ? rtrim($configured, DIRECTORY_SEPARATOR)
        : dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'cms';
}

function jb_cms_boot(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_name('jb_portfolio_studio');
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
            'httponly' => true,
            'samesite' => 'Strict',
        ]);
        session_start();
    }
    $root = jb_cms_root();
    if (!is_dir($root . '/pages')) {
        @mkdir($root . '/pages', 0750, true);
    }
}

function jb_cms_slug(string $value): string
{
    $value = strtolower(trim($value));
    $value = preg_replace('/[^a-z0-9]+/', '-', $value) ?? '';
    return trim($value, '-');
}

function jb_cms_page_path(string $slug): string
{
    return jb_cms_root() . '/pages/' . jb_cms_slug($slug) . '.json';
}

function jb_cms_load_page(string $slug): ?array
{
    $path = jb_cms_page_path($slug);
    if (!is_file($path)) return null;
    $data = json_decode((string) file_get_contents($path), true);
    return is_array($data) ? $data : null;
}

function jb_cms_pages(): array
{
    $pages = [];
    foreach (glob(jb_cms_root() . '/pages/*.json') ?: [] as $path) {
        $data = json_decode((string) file_get_contents($path), true);
        if (is_array($data) && !empty($data['slug'])) $pages[] = $data;
    }
    usort($pages, fn(array $a, array $b): int => ($a['sort_order'] ?? 999) <=> ($b['sort_order'] ?? 999));
    return $pages;
}

function jb_cms_save_page(array $page): bool
{
    $page['slug'] = jb_cms_slug((string) ($page['slug'] ?? ''));
    if ($page['slug'] === '') return false;
    $page['updated_at'] = gmdate('c');
    $json = json_encode($page, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    if ($json === false) return false;
    $path = jb_cms_page_path($page['slug']);
    $temp = $path . '.' . bin2hex(random_bytes(6)) . '.tmp';
    if (file_put_contents($temp, $json . "\n", LOCK_EX) === false) return false;
    @chmod($temp, 0640);
    return rename($temp, $path);
}

function jb_cms_delete_page(string $slug): bool
{
    $path = jb_cms_page_path($slug);
    return is_file($path) ? unlink($path) : false;
}

function jb_cms_components(): array
{
    $components = [
        'hero' => ['label' => 'Hero media + title', 'fields' => ['image', 'video', 'eyebrow', 'title', 'alt', 'image_position', 'media_fit', 'overlay_color', 'overlay_opacity', 'text_color', 'eyebrow_color', 'title_color', 'eyebrow_width', 'eyebrow_margin_left', 'eyebrow_margin_right', 'eyebrow_margin_top', 'eyebrow_margin_bottom', 'title_width', 'title_margin_left', 'title_margin_right', 'title_margin_top', 'title_margin_bottom']],
        'intro' => ['label' => 'Project information + story', 'fields' => ['heading', 'role_label', 'role', 'disciplines_label', 'disciplines', 'built_with_label', 'built_with', 'client_label', 'client', 'paragraphs', 'background', 'text_color', 'heading_color', 'role_label_color', 'role_color', 'disciplines_label_color', 'disciplines_color', 'built_with_label_color', 'built_with_color', 'client_label_color', 'client_color', 'paragraphs_color', 'heading_width', 'heading_margin_left', 'heading_margin_right', 'heading_margin_top', 'heading_margin_bottom', 'paragraphs_width', 'paragraphs_margin_left', 'paragraphs_margin_right', 'paragraphs_margin_top', 'paragraphs_margin_bottom']],
        'artwork' => ['label' => 'Artwork image', 'fields' => ['image', 'alt', 'background', 'image_width', 'image_alignment', 'corner_radius', 'left_margin', 'right_margin', 'section_gap', 'decoration_1', 'decoration_2']],
        'product' => ['label' => 'Product screenshot', 'fields' => ['title', 'image', 'alt', 'caption', 'background', 'text_color', 'title_color', 'caption_color', 'title_width', 'title_margin_left', 'title_margin_right', 'title_margin_top', 'title_margin_bottom', 'caption_width', 'caption_margin_left', 'caption_margin_right', 'caption_margin_top', 'caption_margin_bottom', 'image_width', 'image_alignment', 'corner_radius', 'left_margin', 'right_margin', 'section_gap', 'padding_top', 'padding_bottom']],
        'quote' => ['label' => 'Large quote', 'fields' => ['quote', 'link_label', 'link_url', 'background', 'text_color', 'quote_color', 'link_label_color', 'quote_width', 'quote_margin_left', 'quote_margin_right', 'quote_margin_top', 'quote_margin_bottom', 'link_label_width', 'link_label_margin_left', 'link_label_margin_right', 'link_label_margin_top', 'link_label_margin_bottom', 'corner_radius', 'left_margin', 'right_margin', 'section_gap', 'padding_top', 'padding_bottom']],
    ];
    $custom = __DIR__ . '/cms-components';
    foreach (glob($custom . '/*.php') ?: [] as $file) {
        $definition = require $file;
        if (is_array($definition) && isset($definition['type'], $definition['render'])) {
            $components[$definition['type']] = $definition;
        }
    }
    return $components;
}

function jb_cms_auth_hash(): ?string
{
    $environment = getenv('JB_CMS_PASSWORD_HASH');
    if ($environment !== false && $environment !== '') return $environment;
    $path = jb_cms_root() . '/auth.json';
    if (!is_file($path)) return null;
    $data = json_decode((string) file_get_contents($path), true);
    return is_array($data) && is_string($data['password_hash'] ?? null) ? $data['password_hash'] : null;
}

function jb_cms_can_initialize(): bool
{
    $address = $_SERVER['REMOTE_ADDR'] ?? '';
    return jb_cms_auth_hash() === null && in_array($address, ['127.0.0.1', '::1'], true);
}

function jb_cms_set_password(string $password): bool
{
    if (!jb_cms_can_initialize() || strlen($password) < 14) return false;
    $payload = json_encode(['password_hash' => password_hash($password, PASSWORD_DEFAULT)], JSON_PRETTY_PRINT);
    $path = jb_cms_root() . '/auth.json';
    $result = file_put_contents($path, $payload . "\n", LOCK_EX) !== false;
    if ($result) @chmod($path, 0600);
    return $result;
}

function jb_cms_login(string $password): bool
{
    $hash = jb_cms_auth_hash();
    if ($hash === null || !password_verify($password, $hash)) return false;
    session_regenerate_id(true);
    $_SESSION[JB_CMS_SESSION_KEY] = true;
    $_SESSION['jb_cms_last_seen'] = time();
    return true;
}

function jb_cms_authenticated(): bool
{
    if (empty($_SESSION[JB_CMS_SESSION_KEY])) return false;
    if (time() - (int) ($_SESSION['jb_cms_last_seen'] ?? 0) > 7200) {
        jb_cms_logout();
        return false;
    }
    $_SESSION['jb_cms_last_seen'] = time();
    return true;
}

function jb_cms_logout(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'] ?? '', (bool) $params['secure'], (bool) $params['httponly']);
    }
    session_destroy();
}

function jb_cms_csrf(): string
{
    if (empty($_SESSION[JB_CMS_CSRF_KEY])) $_SESSION[JB_CMS_CSRF_KEY] = bin2hex(random_bytes(32));
    return $_SESSION[JB_CMS_CSRF_KEY];
}

function jb_cms_valid_csrf(?string $token): bool
{
    return is_string($token) && isset($_SESSION[JB_CMS_CSRF_KEY]) && hash_equals($_SESSION[JB_CMS_CSRF_KEY], $token);
}

function jb_cms_h(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function jb_cms_asset(string $path): string
{
    $path = trim($path);
    if ($path === '') return '/assets/project-covers/tb-logo.jpg';
    if (preg_match('#^(https?://|/)#', $path)) return $path;
    return '/assets/' . ltrim($path, '/');
}

function jb_cms_image_size_attributes(string $asset): string
{
    static $sizes = [];
    if (isset($sizes[$asset])) return $sizes[$asset];
    $publicRoot = dirname(__DIR__);
    $path = realpath($publicRoot . jb_cms_asset($asset));
    if ($path === false || !str_starts_with($path, $publicRoot . DIRECTORY_SEPARATOR)) return '';
    $size = @getimagesize($path);
    return $sizes[$asset] = $size ? ' width="' . $size[0] . '" height="' . $size[1] . '"' : '';
}

function jb_cms_color(mixed $value, string $fallback): string
{
    $value = trim((string) $value);
    return preg_match('/^#[0-9a-fA-F]{6}$/', $value) ? $value : $fallback;
}

function jb_cms_image_position(mixed $value): string
{
    $allowed = ['center', 'top', 'bottom', 'left', 'right', 'center top', 'center bottom', 'left center', 'right center', 'left top', 'right top', 'left bottom', 'right bottom'];
    $value = strtolower(trim((string) $value));
    return in_array($value, $allowed, true) ? $value : 'center';
}

function jb_cms_opacity(mixed $value, float $fallback = 0.42): string
{
    if (!is_numeric($value)) return (string) $fallback;
    return (string) max(0, min(1, (float) $value));
}

function jb_cms_number(mixed $value, float $fallback, float $min, float $max): string
{
    if (!is_numeric($value)) return (string) $fallback;
    return (string) max($min, min($max, (float) $value));
}

jb_cms_boot();
