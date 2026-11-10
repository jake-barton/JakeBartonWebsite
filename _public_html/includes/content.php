<?php
/**
 * Landing-page content registry.
 * Project source packages live in /projects; public display assets live in /_public_html/assets.
 */

$content = [];
require_once __DIR__ . '/site-reel.php';

$content['name'] = 'Jake Barton';
$content['title'] = 'Jake Barton — Game Developer & Designer';
$content['email'] = 'jbarton4@samford.edu';
$content['website'] = 'https://jakebartoncreative.com';
$content['github'] = 'jake-barton';
$content['instagram'] = 'jakebarton13';
$content['linkedin'] = 'jakebartoncreative';
$content['hero_subtitle'] = 'Gameplay programmer working on player movement, physics, and multiplayer systems.';
$content['home_greeting'] = 'Hey, I’m Jake!';
$content['home_statement'] = 'I’m a designer, developer, and creative problem-solver passionate about turning ambitious ideas into meaningful experiences, always looking for new ways to create, innovate, and bring something exciting to life.';

$content['home_categories'] = [
    ['label' => 'Games', 'url' => '/work/?filter=games', 'video' => 'assets/work/ding-dong/intro-preview.mp4', 'poster' => 'assets/work/ding-dong/intro-preview-poster.webp'],
    ['label' => 'Programming', 'url' => '/work/?filter=programming', 'video' => 'assets/home/media/mode7.mp4', 'poster' => 'assets/home/media/mode7-poster.jpg'],
    ['label' => '3D Art', 'url' => '/work/?filter=3d', 'video' => 'assets/home/media/environment-preview.mp4', 'poster' => 'assets/home/media/environment-preview-poster.webp'],
    ['label' => 'Websites', 'url' => '/work/?filter=web', 'video' => 'assets/work/barton-design-solutions/website-tour.mp4', 'poster' => 'assets/work/barton-design-solutions/website-tour-poster.webp'],
    ['label' => 'Motion Design', 'url' => '/work/?filter=motion', 'video' => 'assets/work/still-sleepless/video.mp4', 'poster' => 'assets/work/still-sleepless/poster.webp'],
];

$content['home_clients'] = [
    ['name' => 'IronSpark', 'image' => 'assets/organizations/ironspark.svg'],
    ['name' => 'Tech Birmingham', 'image' => 'assets/organizations/tech-birmingham.svg'],
    ['name' => 'Veritas Social', 'image' => 'assets/organizations/veritas.svg'],
    ['name' => 'Pi Kappa Phi', 'image' => 'assets/organizations/pi-kappa-phi.svg'],
    ['name' => 'Sloss City Music Festival', 'image' => 'assets/organizations/sloss-city-music-festival.svg'],
    ['name' => 'Wrappt', 'image' => 'assets/organizations/wrappt.svg'],
];

$content['home_accolades'] = [
    ['category' => 'Current', 'logo' => 'assets/organizations/ironspark.svg', 'logo_alt' => 'IronSpark Studios', 'title' => 'Programmer', 'detail' => 'Gameplay programming and technical development for studio projects.'],
    ['category' => 'AI Development', 'logo' => 'assets/organizations/tech-birmingham.svg', 'logo_alt' => 'Tech Birmingham', 'title' => 'AI Tools', 'detail' => 'Built sponsor-research tools for Tech Birmingham and Sloss Tech.'],
    ['category' => 'Game Development', 'logo' => 'assets/organizations/samford-university.svg', 'logo_alt' => 'Samford University', 'title' => 'Lead Programmer', 'detail' => 'Led programming for student game projects.'],
    ['category' => 'Leadership', 'logo' => 'assets/organizations/samford-sga.svg', 'logo_alt' => 'Samford SGA', 'title' => 'Executive Board', 'detail' => 'Serving as Chaplain and helping organize campus programs and legislative decisions.'],
    ['category' => 'Leadership', 'logo' => 'assets/organizations/pi-kappa-phi.svg', 'logo_alt' => 'Pi Kappa Phi', 'title' => 'Philanthropy Chair', 'detail' => 'Organizing fundraising and disability-awareness events.'],
    ['category' => 'Current', 'logo' => 'assets/organizations/sloss-city-music-festival.svg', 'logo_alt' => 'Sloss City Music Festival', 'title' => 'Creative Director', 'detail' => 'Creative direction, promotional graphics, and sponsor materials for the festival.'],
    ['category' => 'Co-Founder', 'logo' => 'assets/organizations/wrappt-mark.svg', 'logo_alt' => 'Wrappt', 'title' => 'Programming Co-Founder', 'detail' => 'Co-founded Wrappt and worked on its app and backend.'],
    ['category' => 'Technical Toolkit', 'mark' => '</>', 'logo_alt' => 'Programming', 'title' => 'C++ / Python / JS', 'detail' => 'Unreal, Godot, React, Git, Maya, Blender, Substance Painter, and Adobe tools.'],
];
