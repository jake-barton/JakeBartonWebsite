<?php
declare(strict_types=1);

function jb_escape(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function jb_asset(string $path): string
{
    if ($path === '') return '/assets/project-covers/tb-logo.jpg';
    return preg_match('#^(https?://|/)#', $path) ? $path : '/assets/' . ltrim($path, '/');
}

function jb_color(mixed $value, string $fallback): string
{
    return preg_match('/^#[0-9a-fA-F]{6}$/', (string) $value) ? (string) $value : $fallback;
}
