# Portfolio Studio

Follow [the portfolio style guide](PORTFOLIO_STYLE_GUIDE.md) for page order, project colors, media, and video behavior. Barton Design Solutions and Everybody Golf are the reference pages. New pages use the same simple structure by default.

Open `/studio/` to manage work detail pages. On first local use, the Studio asks for a password of at least 14 characters. The password is processed by PHP and is never stored; only PHP's one-way password hash is saved.

Every page editor includes an embedded PHP preview. It can render unpublished drafts for the authenticated Studio session. Save the page to refresh the preview with the newest content.

Page-level design controls cover the canvas, text, accent, rail, and footer palette. Components expose their relevant image, positioning, overlay, opacity, background, and text controls. More specialized layout or behavior belongs in a registered custom component so it remains reusable rather than becoming page-specific markup.

## Production configuration

For production, set these server environment variables:

- `JB_CMS_PASSWORD_HASH`: a PHP `password_hash()` result. Do not put the plain-text password in source code.
- `JB_CMS_DATA_DIR`: an absolute path to persistent, non-public storage for page JSON and authentication data.

The default data directory is `storage/cms`, outside `_public_html`. Ensure the PHP process can read and write it and that deployment does not replace it. Back up this directory with the rest of the site content.

Example for generating a hash locally without printing or storing the password in the repository:

```sh
php -r '$password = readline("Password: "); fwrite(STDOUT, password_hash($password, PASSWORD_DEFAULT) . PHP_EOL);'
```

## Security behavior

- Password verification and content writes happen only on the server.
- Authentication cookies are HTTP-only, SameSite Strict, and Secure when HTTPS is active.
- Sessions expire after two hours of inactivity and rotate after login.
- All content-changing requests require a CSRF token.
- Login attempts are throttled.
- Studio responses are not cached and cannot be framed.
- Uploads are MIME-checked, size-limited, randomly named raster images.
- Credential storage is ignored by Git.

Always serve the production site over HTTPS. No authentication system can guarantee availability by itself; persistent storage permissions, PHP sessions, HTTPS, deployment configuration, and backups must remain correctly configured.

## Custom components

Built-in page sections are registered in `_public_html/includes/cms.php`. Add custom components under `_public_html/includes/cms-components/`; see that directory's README for the registration format. Registered components appear automatically in Studio's “Add a section” controls.
