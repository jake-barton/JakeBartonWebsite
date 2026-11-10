# Portfolio development workflow

Run commands from the current repository root. Read [PORTFOLIO_STYLE_GUIDE.md](PORTFOLIO_STYLE_GUIDE.md) before changing pages or media; [EDITING.md](EDITING.md) maps the files.

## Edit and preview

1. Edit pages and shared code under `_public_html/`. Homepage content is `includes/content.php`; Work-card metadata is `includes/projects.php`.
2. Keep original project material under `projects/` or its documented original location. Unapproved pages stay in `projects/portfolio-drafts/`, outside the web root.
3. Build the browser game and start PHP:

   ```sh
   python3 scripts/build-games.py
   PHP_CLI_SERVER_WORKERS=4 php -S 127.0.0.1:8000 -t _public_html _public_html/router.php
   ```

4. Open http://localhost:8000/ and refresh after edits. The development router supports extensionless URLs and MP4 byte ranges for seeking and paired playback. Hostinger uses `.htaccess` and its web server.
5. Stop the preview with Ctrl+C in its terminal. Reuse an existing preview when available; do not stop unrelated PHP processes.

There is no portfolio content build or JSON export. Mario Kart is a separate generated runtime at `/games/mario-kart/`, sourced from `projects/game-development/mode7-engine/`.

## Verify

With the preview running:

```sh
python3 scripts/check-portfolio.py
```

The check covers the public project list/routes, eleven private drafts, motion-page redirects, retired editor routes, and unexpected content sessions. Use `--base-url https://jakebartoncreative.com` to check the live site.

Check changed PHP with `php -l path/to/file.php` and JavaScript with `node --check path/to/file.js`. Inspect desktop and phone layouts, sidebar/menu links, the résumé, assets, Work filters/previews, automatic footage, paired timing, and footer contrast where affected. Interaction changes also need keyboard and reduced-motion checks. Syntax and route checks do not establish visual correctness.

## Prepare local deployment files

```sh
./sync_to_hostinger.sh
```

This builds Mario Kart and refreshes `hostinger_upload/` from `_public_html/`. `prepare_for_hostinger.sh` delegates to the same script. Edit source files, never this generated mirror. The mirror’s `test.php` is a manual diagnostic, not the automated portfolio check and not a file uploaded by the GitHub workflow.

## Publish

A push to `main` on `jake-barton/JakeBartonWebsite` triggers Hostinger deployment. Follow [DEPLOY.md](DEPLOY.md), wait for the Actions result, and verify the live page and changed assets.

Before committing, fetch and compare the actual files with `origin/main`, including files shown locally as untracked. This checkout contains a substantial existing reorganization and its local branch can differ from the deployed branch. Preserve unrelated work, avoid blanket staging, and retain remote deployment fixes. When necessary, apply the intended changes in a separate worktree based on `origin/main`; do not force-push or reset the user’s workspace to publish a small change.

Reviewed November 10, 2026.
