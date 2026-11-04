# Auto-Deploy: VS Code → GitHub → Hostinger

Every time you `git push` to `main`, GitHub Actions will automatically
upload changed files to Hostinger via FTP. Your site is live ~30 seconds
after you push.

---

## One-Time Setup (do this once)

### Step 1 — Get your Hostinger FTP credentials

1. Log into [hpanel.hostinger.com](https://hpanel.hostinger.com)
2. Go to **Hosting → Manage → Files → FTP Accounts**
3. Note down:
   - **FTP Host** (looks like `ftp.jakebartoncreative.com` or an IP)
   - **FTP Username** (looks like `u123456789`)
   - **FTP Password** (set/reset it here if you don't know it)

### Step 2 — Add secrets to GitHub

1. Go to [github.com/CleverCarpetGames/JakeBartonWebsite/settings/secrets/actions](https://github.com/CleverCarpetGames/JakeBartonWebsite/settings/secrets/actions)
2. Click **New repository secret** for each of these:

| Secret Name    | Value                              |
|----------------|------------------------------------|
| `FTP_HOST`     | Your FTP host from Hostinger       |
| `FTP_USERNAME` | Your FTP username from Hostinger   |
| `FTP_PASSWORD` | Your FTP password from Hostinger   |

### Step 3 — Push to trigger your first deploy

```bash
git add .
git commit -m "Set up auto-deploy"
git push
```

Then go to [github.com/CleverCarpetGames/JakeBartonWebsite/actions](https://github.com/CleverCarpetGames/JakeBartonWebsite/actions)
to watch it run live. First deploy uploads everything (~1-2 min).
Subsequent deploys only upload **changed files** (usually 5-10 seconds).

---

## Daily Workflow (after setup)

```bash
# 1. Make your changes in VS Code
# 2. Save files
# 3. Push:
git add .
git commit -m "describe what you changed"
git push
# → Site is live on Hostinger in ~30 seconds
```

That's it. No FTP client, no Hostinger file manager, no manual uploads.

---

## What Gets Deployed

Portfolio pages are stored outside the public web root. `scripts/export-portfolio-content.py` prepares **published pages only** in `hostinger_content/storage/cms/pages/`. Drafts and Studio credentials are excluded. The workflow uploads those page files separately without deleting other server content.

The default remote page directory is `/domains/jakebartoncreative.com/storage/cms/pages/`. If the server uses `JB_CMS_DATA_DIR`, set the GitHub Actions repository variable `CMS_REMOTE_DIR` to that directory's `pages/` child. Existing page files with the same slug receive the source-controlled content update. Use Studio to manage server-only pages and to unpublish existing live pages; omitting a page from the export does not delete it remotely.

For manual uploads, `./sync_to_hostinger.sh` prepares both folders. Upload `hostinger_upload/` contents to `public_html/` and `hostinger_content/storage/cms/pages/` files to the sibling persistent `storage/cms/pages/` directory. Do not put `hostinger_content/` inside `public_html/`.

The workflow preserves server-only project files and Studio authentication. It builds Mario Kart, exports published page data, and uploads the curated project videos, homepage reel, shared site files, and game runtime without deleting remote folders.

- Source: `_public_html/` folder in this repo
- Destination: `public_html/` on Hostinger
- Only **changed files** are uploaded on repeat deploys (uses a sync state file)

## What Does NOT Get Deployed

- `.git/` folder
- `node_modules/`
- `.DS_Store` / `Thumbs.db`
- Any `*.log` / `*.tmp` files

---

## Troubleshooting

**Action fails with "Login failed"**
→ Double-check your FTP credentials in Hostinger. Try resetting the FTP password.

**Action fails with "Connection refused"**
→ Some Hostinger plans use port 21 by default. If it fails, edit `.github/workflows/deploy.yml`
and add `port: 21` under the FTP Deploy Action `with:` block.

**Files not updating on live site**
→ Check the Actions tab on GitHub to see if the deploy ran successfully.
→ Hard refresh your browser (`Cmd+Shift+R`).
