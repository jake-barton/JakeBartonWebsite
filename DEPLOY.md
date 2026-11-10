# Deploying the PHP portfolio

The site runs directly from `_public_html/`. Project pages are regular `work/<project-name>/index.php` files with shared PHP header/footer includes. `EDITING.md` maps the files to edit.

## Local preparation

```sh
./sync_to_hostinger.sh
```

This builds the existing Mario Kart runtime and mirrors `_public_html/` into `hostinger_upload/`. It does not deploy anything live. There is no separate content upload, database, editor authentication file, or JSON export.

For a manual deployment, upload the contents of `hostinger_upload/` to the site's `public_html/`, including `.htaccess`. Hidden project source in `projects/portfolio-drafts/` stays outside the public folder.

## GitHub deployment

A push to `main` runs `.github/workflows/deploy.yml`. The workflow builds Mario Kart and uploads the PHP pages, shared files, media, and game runtime to Hostinger. It uses the existing `FTP_PASSWORD` GitHub secret and the FTP host/user configured in the workflow. Check the Actions result to confirm deployment; a local sync is not a live deployment.

The migration also removes the retired editor directory, editor assets, old page-renderer files, and former private page store from their known server paths. `.htaccess` returns 404 for the retired editor URLs even if stale files remain. It also uploads the self-hosted source editor and exact source excerpts, removes the known withdrawn project directories, and removes all Minnie’s media/source files. `.htaccess` blocks these withdrawn URLs as soon as it is uploaded. Other server folders are preserved. FTP command failures fail the deployment job.

The three former motion-video pages redirect to `/work/motion-graphics/`. Hidden projects have no public page directories and are absent from the Work-card list.

## What stays local

- `projects/`, including private drafts and original project sources
- Git history and dependencies
- Local deployment mirrors
- Temporary files, environment files, and credentials

Publishing does not require a PHP session or a writable content directory.
