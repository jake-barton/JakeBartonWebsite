# Jake Barton portfolio

The live site is [jakebartoncreative.com](https://jakebartoncreative.com). The portfolio is ordinary PHP, HTML, CSS, and JavaScript under `_public_html/`. There is no CMS, database, visual editor, or content-export step for the portfolio.

## Start here

- [AGENTS.md](AGENTS.md): repository instructions.
- [PORTFOLIO_STYLE_GUIDE.md](PORTFOLIO_STYLE_GUIDE.md): current visual, writing, media, attribution, and privacy rules. Barton Design Solutions and Everybody Golf are the reference pages.
- [EDITING.md](EDITING.md): page, component, and interaction ownership.
- [WORKFLOW.md](WORKFLOW.md): local editing and verification.
- [DEPLOY.md](DEPLOY.md): GitHub Actions and Hostinger publishing.
- [Project inventory](projects/PORTFOLIO_INVENTORY.md): current public projects and private drafts.
- [Source provenance](projects/portfolio-process/README.md): exact excerpts, exported Blueprints, and the Monaco build.
- [Portfolio reel](projects/portfolio-reel/README.md): edit decisions, export, and editable timeline.

## Structure

`_public_html/index.php` is the homepage. `_public_html/work/index.php` lists 18 projects from `includes/projects.php`; each has an ordinary PHP page and shared project header/footer. Three former motion-video URLs redirect to Motion Graphics. Eleven drafts, including unreleased Minnie’s Golf and temporarily hidden Wrappt, have no public page routes.

`projects/` preserves source, editorial notes, media work, and private drafts outside the web root. `hostinger_upload/` is a generated local deployment mirror. `style-kit/` preserves an older demonstration; its cyan palette and effects are not the current design system.

## Preview

Run from this repository’s root:

```sh
python3 scripts/build-games.py
PHP_CLI_SERVER_WORKERS=4 php -S 127.0.0.1:8000 -t _public_html _public_html/router.php
```

Open http://localhost:8000/. In a second terminal, run `python3 scripts/check-portfolio.py`. After site changes, run `./sync_to_hostinger.sh`. This prepares local files; pushing to GitHub `main` triggers live deployment. Read [DEPLOY.md](DEPLOY.md) before publishing.

Reviewed November 10, 2026. Application-specific READMEs under `projects/` document those source projects, not the portfolio runtime. Current publication decisions are in the inventory and style guide.
