<?php
$projects = require __DIR__ . '/../includes/projects.php';
$slug = (string) ($_GET['slug'] ?? '');
$redirects = ['bayside-lyric-video' => 'motion-graphics', 'still-sleepless' => 'motion-graphics', 'tennis-video' => 'motion-graphics'];
$slug = $redirects[$slug] ?? $slug;
if (!isset($projects[$slug])) {
    http_response_code(404);
    exit('404 Not Found');
}
header('Location: /work/' . $slug . '/', true, 301);
exit;
