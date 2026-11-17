# BBQ browser export recipe

Approved for the portfolio on November 17, 2026. The original source is Jake’s `Downloads/BBQ_Godot`; the local editable copy is `projects/game-development/bbq/`. The full editable project is not included in this repository. This folder retains the deployment recipe; the compiled runtime is committed in `_public_html/games/bbq/`.

Use Godot **4.5.1** with its matching **web_nothreads_release.zip** export template and Compatibility renderer. The desktop Godot 4.3 installation is not the correct engine for this project.

To rebuild from the original source:

1. Copy the original project into `projects/game-development/bbq/`, excluding `.godot/`, `.git/`, and `.DS_Store`.
2. Copy this folder’s `export_presets.cfg` and `web-shell.html` into that project.
3. Apply `browser-input.patch` once with `patch -p1 -d projects/game-development/bbq < projects/game-development/bbq-web/browser-input.patch`. The current local copy already has this patch.
4. Run Godot 4.5.1 with `--headless --editor --path projects/game-development/bbq --import`, then `--headless --path projects/game-development/bbq --export-release Web`.
5. Verify the browser title screen, intro, movement/look, Esc pause, click resume, notes, retry, and fullscreen. Run `tests/gameplay_checks.gd` and `tests/win_playthrough.gd` through the headless Godot executable as well.
6. Run `./sync_to_hostinger.sh`; publish the final exported files together through the normal deployment workflow.

Export **all resources**. A scene-only export omitted runtime-preloaded GDScript, shaders, audio, configuration, and fonts, causing a black canvas. Presentation drafts, reflection notes, tests, and tooling are excluded from the pack. Keep the credited assets and licenses. Do not replace the screenshots or add a new BBQ badge/icon.

The shell starts directly without a launch gate or branded loading overlay. It uses a fixed 1280×720 canvas, fitted proportionally to the browser, rather than a Retina-sized backing buffer. Web play is capped at 60 fps. Browser pointer-lock loss pauses the game; a click resumes/retries capture from a user input event. The native project behavior is retained. Single-threaded output requires WebGL 2 and WebAssembly, with no cross-origin isolation headers. The runtime is prebuilt; `scripts/build-games.py` only regenerates Mario Kart and leaves BBQ intact.

Artwork: authentic `presentation_images/title.png`, `meal.png`, and `kitchen_entrance.png` converted to WebP in `_public_html/assets/work/bbq/`. `credits.txt` and `licenses/` retain source attribution.
