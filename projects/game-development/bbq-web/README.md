# BBQ browser export recipe

Approved for the portfolio on November 17, 2026. The current source is `Documents/Codex/2026-11-16/this-looks-so-ai-generated-the/work/game/The_Special_Godot/` (matching the updated `BBQ_Godot.zip` supplied November 17); the local editable copy is `projects/game-development/bbq/`. The full editable project is not included in this repository. This folder retains the deployment recipe; the compiled runtime is committed in `_public_html/games/bbq/`.

Use Godot **4.5.1** with its matching **web_nothreads_release.zip** export template and Compatibility renderer. The desktop Godot 4.3 installation is not the correct engine for this project.

To rebuild from the original source:

1. Copy the current supplied project into `projects/game-development/bbq/`, excluding `.godot/`, `.git/`, and `.DS_Store`.
2. Copy this folder’s `export_presets.cfg` and `web-shell.html` into that project.
3. Apply `browser-input.patch` once with `patch -p1 -d projects/game-development/bbq < projects/game-development/bbq-web/browser-input.patch`. The current local copy already has this patch. The patch is based on the source recorded in `source-version.json` and also removes the desktop-only icon setting from the web copy.
4. Run Godot 4.5.1 with `--headless --editor --path projects/game-development/bbq --import`, then `--headless --path projects/game-development/bbq --export-release Web`.
5. Run `python3 projects/game-development/bbq-web/prepare-export.py` after each export. It gives the PCK a content-hash filename and updates Godot’s `mainPack` configuration, preventing browsers from reusing the previous game. Update the game iframe and new-tab URL query in the project PHP page to the printed revision.
6. Verify the browser title screen, intro, movement/look, Esc pause, click resume, notes, retry, and fullscreen. Run `tests/gameplay_checks.gd` `tests/capture_checks.gd`, `tests/dread_checks.gd`, and `tests/win_playthrough.gd` through the headless Godot executable as well.
7. Run `./sync_to_hostinger.sh`; publish the final exported files together through the normal deployment workflow.

Export **all resources**. A scene-only export omitted runtime-preloaded GDScript, shaders, audio, configuration, and fonts, causing a black canvas. Presentation drafts, reflection notes, tests, and tooling are excluded from the pack. Keep the credited assets and licenses. Do not replace the screenshots or add a new BBQ badge/icon.

The shell starts directly without a launch gate or branded loading overlay. It uses a fixed 1280×720 canvas, fitted proportionally to the browser, rather than a Retina-sized backing buffer. Web play is capped at 60 fps. Browser pointer-lock loss pauses the game; a click resumes/retries capture from a user input event. The native project behavior is retained. Single-threaded output requires WebGL 2 and WebAssembly, with no cross-origin isolation headers. The runtime is prebuilt; `scripts/build-games.py` only regenerates Mario Kart and leaves BBQ intact.

Artwork: authentic `presentation_images/title.png`, `meal.png`, and `kitchen_entrance.png` converted to WebP in `_public_html/assets/work/bbq/`. `credits.txt` and `licenses/` retain source attribution.

The deployment uploads runtime assets before `index.html`. Older remote PCK filenames are retained for visitors with an older open page. The current repository contains only the latest PCK; publishing must not delete old remote runtime files during the update.
