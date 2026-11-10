<?php
// Edit this project’s page title, description, colors, and HTML below.
$pageTitle = 'Tech Birmingham AI';
$pageDescription = 'I built a sponsor-research assistant for Tech Birmingham’s Sloss Tech committee.';
$theme = [
    'canvas' => '#e7e6df',
    'ink' => '#132b4f',
    'accent' => '#18385d',
    'rail' => '#23883c',
    'footer_background' => '#e7e6df',
    'footer_text' => '#111111',
    'footer_word' => '#18385d',
];

require __DIR__ . '/../../includes/project-header.php';
?>

    <section class="jb-detail-hero" aria-labelledby="project-title" style="--hero-position: center; --hero-fit: cover; --hero-overlay: #132b4f; --hero-overlay-opacity: 0.2; --hero-text: #ffffff">
        <img src="/assets/project-covers/80181D04-2E88-4BFD-8A70-A8841E0A647A.png" alt="Jake Barton speaking with attendees at Sloss Tech">
        <div class="jb-detail-hero-shade" aria-hidden="true"></div>
        <div class="jb-detail-hero-copy">
            <p style="color:#ffffff">Tech Birmingham · AI Research Platform</p>
            <h1 id="project-title" style="color:#ffffff">Tech Birmingham AI</h1>
        </div>
    </section>
    <section class="jb-detail-intro" style="--intro-background: #e7e6df; --intro-text: #000000">
        <div class="jb-detail-info-module">
            <h2 style="color:#000000">Tech Birmingham AI</h2>
            <dl class="jb-detail-meta">
                <div>
                    <dt style="color:#000000">Role</dt>
                    <dd style="color:#000000">Designer + Developer</dd>
                </div>
                <div>
                    <dt style="color:#000000">Disciplines</dt>
                    <dd style="color:#000000">AI, Product Design, Web</dd>
                </div>
                <div>
                    <dt style="color:#000000">Built with</dt>
                    <dd style="color:#000000">Next.js, TypeScript, Local LLMs</dd>
                </div>
                <div>
                    <dt style="color:#000000">Client</dt>
                    <dd style="color:#000000">Tech Birmingham</dd>
                </div>
            </dl>
        </div>
        <div class="jb-detail-story-module">
            <div class="jb-detail-intro-copy">
                <p style="color:#000000">I built a sponsor-research assistant for Tech Birmingham’s Sloss Tech committee.</p>
                <p style="color:#000000">The tool searches for companies, summarizes research, ranks potential sponsors, and exports results to spreadsheets. It uses a local language model and custom research tools.</p>
                <p style="color:#000000">Sponsor information is redacted.</p>
            </div>
        </div>
    </section>
    <figure class="jb-project-media" style="--media-background:#101010">
        <img src="/assets/work/tech-birmingham-ai/tech-birmingham-logo.jpeg" alt="Tech Birmingham identity artwork" width="852" height="252" loading="lazy">
    </figure>
    <figure class="jb-project-media" style="--media-background:#101010">
        <img src="/assets/work/tech-birmingham-ai/tech-birmingham-ai-redacted.png" alt="The Grove AI sponsor research workspace created for Tech Birmingham" width="2998" height="1586" loading="lazy">
    </figure>
    <div class="jb-project-links">
        <a class="jb-fold-underline" href="https://www.samford.edu/arts/news/2026/Game-Design-Student-Builds-AI-Tool-for-Sloss-Tech" target="_blank" rel="noopener">Read the Samford feature</a>
    </div>

<?php require __DIR__ . '/../../includes/project-footer.php'; ?>
