# Editing the portfolio

The website is ordinary PHP, HTML, CSS, and JavaScript. There is no editor, database, JSON page store, or content-export step.

## Where to edit

| Change | File |
| --- | --- |
| Homepage layout and markup | `_public_html/index.php` |
| Homepage copy, category links, experience, and contact details | `_public_html/includes/content.php` |
| Work cards: titles, covers, hover videos, tags, filters, and order | `_public_html/includes/projects.php` |
| A complete project page | `_public_html/work/<project-name>/index.php` |
| Shared project navigation, head tags, and page shell | `_public_html/includes/project-header.php` |
| Shared project footer and scripts | `_public_html/includes/project-footer.php` |
| Quiet toolkit and Work-filter easter eggs | `_public_html/assets/js/easter-eggs.js` (styling in `components.css`) |
| Work-filter cameo SVGs | `_public_html/includes/work-easter-eggs.php` |
| Footer JB logo and its face layers | `_public_html/includes/footer-logo.php` (motion in `components.css`) |
| Main site and homepage styling | `_public_html/assets/css/components.css` |
| Homepage colors and spacing | `_public_html/assets/css/home.css` |
| Shared subheading and label fonts | `_public_html/assets/css/typography.css` |
| Work grid styling | `_public_html/assets/css/work.css` |
| Project page styling | `_public_html/assets/css/work-detail.css` |
| Case-study source renderer | `_public_html/includes/source-viewer.php`, `assets/js/source-viewer.js`, `assets/css/source-viewer.css` |
| Exact project-source selections and provenance | `projects/portfolio-process/` (see its README) |
| Project gallery playback and linked videos | `_public_html/assets/js/project-media.js` |
| Images and project videos | `_public_html/assets/work/<project-name>/` |
| Unpublished project source and notes | `projects/portfolio-drafts/` (outside the web root) |

For example, open `_public_html/work/ding-dong/index.php`. Its title, description, and page colors are at the top. The rest is editable HTML: the header video, role and credits, paragraphs, and gallery videos. Keep shared navigation and footer changes in their includes.

The Work card is edited separately in `includes/projects.php`. Its `video` value controls the hover preview; use an empty string for a static card. `sort_order` controls the grid order. Removing a card does not remove a physical page: move that project's directory out of `_public_html/work/` as well if it must become private.

## Add a project

1. Copy a similar approved project's `work/<project-name>/index.php` into a new directory.
2. Edit the metadata, theme colors, HTML, role, credits, and media paths.
3. Add its card to `includes/projects.php`.
4. Check the page and Work grid on desktop and mobile.

Use Barton Design Solutions and Everybody Golf as layout references. Follow `PORTFOLIO_STYLE_GUIDE.md`, including no small titles or captions around videos. Unapproved work belongs in `projects/portfolio-drafts/`, never under `_public_html/`.

## Preview and deployment

Run from the repository root:

```sh
python3 scripts/build-games.py
PHP_CLI_SERVER_WORKERS=4 php -S 127.0.0.1:8000 -t _public_html _public_html/router.php
```

Open http://localhost:8000/. Edit a PHP file, save it, and refresh the browser. No page-generation command is needed. The game build only prepares the existing Mario Kart runtime.

With the preview running, `python3 scripts/check-portfolio.py` checks public pages, redirects, hidden drafts, and the retired editor routes.

After changes, run `./sync_to_hostinger.sh` to refresh the local `hostinger_upload/` mirror. This does not publish the live site. See `DEPLOY.md` for deployment.

## Process chapters

Ding Dong, Everybody Golf, Volleyball Game, Mode 7, and Phase Runner use short, sequential `.jb-case-chapter` sections. Edit their explanations in the PHP page itself. Each chapter pairs the story with its relevant media or `jb_source_viewer()` call. Chapter navigation is ordinary anchor links; visitors can read everything by scrolling.

See `projects/portfolio-process/README.md` for source selection, Blueprint provenance, and the local Monaco build. Stories must come from Jake’s actual answers and code. Keep code out of the homepage; its former process gallery was removed.

Minnie’s Golf is private because it is unreleased. Its page, media, and former source excerpt are archived in `projects/portfolio-drafts/`. It is also removed from both the delivered reel and the editable reel timeline. Hiding a project must include direct media and code-download URLs when the content is unreleased.
