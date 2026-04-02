<?php
/**
 * ============================================================
 *  CONTENT.PHP  —  Jake Barton Portfolio
 *  ──────────────────────────────────────────────────────────
 *  ALL editable site text lives here.
 *  Edit this file to update copy anywhere on the site.
 *  Then re-save — changes go live immediately on next deploy.
 * ============================================================
 */

// ── Personal / Contact ─────────────────────────────────────
$content['name']        = 'Jake Barton';
$content['title']       = 'Jake Barton — Gameplay Programmer & Technical Designer';
$content['email']       = 'jbarton4@samford.edu';
$content['phone']       = '(615) 943-9722';
$content['location']    = 'Birmingham, AL';
$content['website']     = 'https://jakebartoncreative.com';
$content['github']      = 'jake-barton';
$content['instagram']   = 'jakebarton13';
$content['linkedin']    = 'jakebartoncreative';

// ── Education ──────────────────────────────────────────────
$content['university']  = 'Samford University';
$content['major']       = 'Game Design & 3D Animation';
$content['minor']       = 'Computer Science';
$content['year']        = 'Junior';
$content['gpa']         = '3.50';
$content['grad_year']   = '2027';
$content['grad_date']   = 'May 2027';

// ── Hero Section ───────────────────────────────────────────
$content['hero_eyebrow']  = 'Seeking Internship &amp; Entry-Level Roles · Birmingham, AL';
$content['hero_tagline']  = 'Game developer building — from game engines to the browser.';
$content['hero_rotating_words'] = ['gameplay systems', 'creative tools', '3D worlds', 'UI & web work', 'real-time systems'];
$content['hero_subtitle'] = 'Junior at Samford University studying Game Design &amp; 3D Animation — currently <strong>Lead Programmer at Samford Game Design Studio</strong>, building in Unreal Engine 5, Godot 4, and the web.';

// Hero stats bar
$content['hero_stats'] = [
    ['num' => '3',    'suffix' => '+', 'label' => 'Years Building'],
    ['num' => '20',   'suffix' => '+', 'label' => 'Projects Built'],
    ['num' => '3.5',  'suffix' => '',  'label' => 'GPA · Samford'],
    ['num' => '2027', 'suffix' => '',  'label' => 'Graduation'],
];

// ── Marquee Strip ("Currently Building") ───────────────────
$content['marquee_items'] = [
    ['bold' => 'Lead Programmer', 'text' => '· Samford Game Design Studio'],
    ['bold' => '',                'text' => 'Phase Runner — released on itch.io · Godot 4'],
    ['bold' => '',                'text' => 'Mario Kart Recreation — JavaScript · Mode-7 renderer'],
    ['bold' => '',                'text' => 'VR Rhythm Game — Unreal Engine 5 · C++'],
];

// ── Featured Work (homepage showcase grid) ─────────────────
$content['featured_projects'] = [
    [
        'title'    => 'Phase Runner',
        'url'      => 'https://clervercarpet99.itch.io/phase-runner',
        'external' => true,
        'img'      => 'assets/images/phaserunnercover.png',
        'tags'     => ['Game Design', 'Godot 4', 'GDScript'],
        'desc'     => '2D side-scrolling shooter — custom physics, procedural chunks, invincibility dash. Solo-developed, live on itch.io.',
        'cta'      => 'Play Now →',
        'badge'    => 'Featured',
        'size'     => 'hero',       // hero | tall | wide | square
    ],
    [
        'title'    => 'VR Rhythm Game',
        'url'      => '/portfolio/game-programming/',
        'external' => false,
        'img'      => '',           // leave empty for placeholder
        'placeholder_label' => 'VR',
        'tags'     => ['VR Game', 'Unreal 5'],
        'desc'     => 'Unreal Engine 5 — your movements control a dragon in a rhythm-based VR experience.',
        'cta'      => 'View →',
        'badge'    => '',
        'size'     => 'tall',
    ],
    [
        'title'    => 'Mario Kart Recreation',
        'url'      => '/portfolio/game-programming/',
        'external' => false,
        'img'      => 'assets/images/mariokart.png',
        'tags'     => ['Web Game', 'In Development'],
        'desc'     => 'Mode-7 SNES engine in vanilla JS — raycasting, sprite sheets, lap logic. Currently in development.',
        'cta'      => 'View →',
        'badge'    => '',
        'size'     => 'wide',
    ],
    [
        'title'    => '33Miles Band Graphics',
        'url'      => '/portfolio/professional-works/',
        'external' => false,
        'img'      => 'assets/images/33miles-cover.png',
        'tags'     => ['Client Work', 'Illustrator'],
        'desc'     => 'Paid brand &amp; merchandise design for a signed Christian music group.',
        'cta'      => 'View →',
        'badge'    => '',
        'size'     => 'wide',
    ],
];

// ── Skills ─────────────────────────────────────────────────
$content['skill_groups'] = [
    [
        'icon'    => 'GD',
        'title'   => 'Game Development',
        'primary' => ['Unreal Engine 5', 'Godot 4', 'Unreal Blueprint'],
        'other'   => ['Unity', 'Level Design', 'Game Programming'],
    ],
    [
        'icon'    => '</>',
        'title'   => 'Programming',
        'primary' => ['C++', 'Python', 'JavaScript'],
        'other'   => ['HTML &amp; CSS', 'PHP', 'Web Development'],
    ],
    [
        'icon'    => 'ART',
        'title'   => 'Art &amp; Design',
        'primary' => ['Autodesk Maya', 'Adobe Illustrator'],
        'other'   => ['Blender', 'Substance Painter', 'Photoshop', 'Figma'],
    ],
];

// ── About Section ──────────────────────────────────────────
$content['about_heading']    = 'Building games, tools, and<br><em>interactive experiences.</em>';
$content['about_paragraphs'] = [
    'I\'m a Junior at <strong>Samford University</strong> majoring in Game Design &amp; 3D Animation with a Computer Science minor — currently serving as <strong>Lead Programmer at Samford\'s Game Design Studio</strong>. I work across gameplay programming, systems design, and UI implementation, with a focus on delivering polished, playable results.',
    'My background spans code (C++, GDScript, Python), 3D art (Maya, Blender), and front-end web development. I\'m comfortable working end to end on a project — from core gameplay mechanics to UI layout and visual polish.',
    'Outside of projects I hold leadership roles in <strong>Pi Kappa Phi</strong>, where I\'ve developed practical skills in project coordination, event management, and cross-team communication.',
];

// ── Experience / Leadership Cards ──────────────────────────
$content['experience'] = [
    [
        'dates'    => '2025 – Current',
        'role'     => 'Lead Programmer',
        'org'      => 'Samford Game Design Studio',
        'org_style'=> 'color:var(--accent-light)',
        'bullets'  => [
            'Lead gameplay programming for a 5-person team building a full playable demo in Unreal Engine 5',
            'Own combat, movement, and interaction systems — reviewed &amp; integrated code from 4 contributors',
            'Set technical direction across 3 sub-teams; bridge Blueprint prototypes into C++ production systems',
        ],
    ],
    [
        'dates'    => '2025 – Current',
        'role'     => 'Social Chair',
        'org'      => 'Pi Kappa Phi · Alpha Eta',
        'org_style'=> 'color:var(--text-faint)',
        'bullets'  => [
            'Plan &amp; execute large-scale chapter events',
            'Venue sourcing, budget management &amp; logistics',
            'Custom banner artwork &amp; event branding',
        ],
    ],
    [
        'dates'    => '2023 – 2025',
        'role'     => 'T-Shirt Chair',
        'org'      => 'Pi Kappa Phi · Alpha Eta',
        'org_style'=> 'color:var(--text-faint)',
        'bullets'  => [
            'Designed &amp; produced 15+ chapter apparel runs',
            'Managed vendor relationships &amp; print deadlines',
            '<a href="/portfolio/tshirt-designs/">View the T-Shirt portfolio →</a>',
        ],
    ],
    [
        'dates'    => '2025 – Current',
        'role'     => 'Philanthropy Chair',
        'org'      => 'Pi Kappa Phi · Alpha Eta',
        'org_style'=> 'color:var(--text-faint)',
        'bullets'  => [
            'Partner: Unless U &amp; The Ability Experience',
            'Chapter-wide fundraising &amp; service events',
            'Disability inclusion advocacy on campus',
        ],
    ],
];

// ── Contact Section ────────────────────────────────────────
$content['contact_heading'] = 'Let\'s Work Together';
$content['contact_sub']     = 'Whether it\'s a studio role, freelance project, or just a conversation — I\'d love to hear from you.';

// ── CTA Banner (bottom of homepage) ───────────────────────
$content['cta_eyebrow']  = 'Ready to work together?';
$content['cta_heading']  = 'Let\'s <em>Talk.</em>';
$content['cta_sub']      = 'Studio role, freelance gig, or just a conversation about a project — I\'d love to hear from you.';
