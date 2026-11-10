# Project process and source

The project pages are ordinary PHP. Their stories are written directly into each page, with the relevant gameplay, code, or Blueprint alongside each part. Nothing needs a CMS or visual editor.

Current source-backed pages: Ding Dong, Everybody Golf, Volleyball Game, Mode 7 Engine, and Phase Runner. VR Rhythm Game uses Jake’s account alongside the original dragon footage; Jake confirmed its Unreal source is probably not saved locally and asked us to proceed without it. The homepage code gallery was removed at Jake’s request. Minnie’s Golf is unreleased: its page, media, and former source excerpt are private under `projects/portfolio-drafts/`, and it is excluded from the reel.

## Edit the story

Use `.jb-case-chapter` sections in `_public_html/work/<slug>/index.php`. Keep each explanation short and grounded in Jake’s own answers or verified source. A `.jb-case-nav` contains ordinary anchor links to the chapters. The page reads in order without clicking anything.

Use `jb_source_viewer(['source-id'], 'Accessible label', false, false)` alongside the relevant explanation. Several source IDs give related tabs within that chapter. Do not collect unrelated systems into a single gallery. Code is read only; it is never executed by the website.

The shared viewer is in `_public_html/includes/source-viewer.php`, with its CSS and JavaScript in `assets/css/source-viewer.css` and `assets/js/source-viewer.js`. Large Blueprint graphs support pan, zoom, Fit all, and an expanded native dialog. The A* graph also has Start. Raw exports preserve source positions; the web view proportionally spaces those positions to fit readable web node labels. It does not add or infer connections.

## Source provenance

`export-sources.py` copies exact function excerpts from the local projects and writes the plain PHP source catalogue. `provenance.json` records the original path, line range, source hash, and excerpt hash. Absolute local paths stay outside the web root. New selections must be checked against the actual source and attributed accurately.

- Phase Runner: `GodotSummerProject`, confirmed by `project.godot` naming the application Phase Runner and the matching 2D player/game assets. Exact excerpts of the exponential running update and `hurt_player` come from `Scripts/Player/player.gd`. The editor registers GDScript explicitly; it is not relabelled Python.
- Everybody Golf: `Golf-Simulator`, C++ physics and PhoneGolfInput plugin.
- Mode 7: this repository’s `projects/game-development/mode7-engine`, JavaScript renderer and collision manager.
- Volleyball: a read-only Unreal `MovementAuthoring` audit of `VolleyballGame_Desktop55`. `blueprints/volleyball-current.json` retains the selected graphs with real node GUIDs, values, pin types and links. Camera and movement graphs come from the same current audit.
- Ding Dong: saved uncooked assets from the local `UberEatsHorror` project. `GS_Texting` supplies the phone message flow; `BP_RoadGraphManager` supplies A*, lowest-F-score selection, and world-to-map coordinates. The graph snapshots come from read-only UAssetAPI JSON exports. `read-blueprint-pins.py` decodes native serialized pins; `import-ding-blueprints.py` checks target GUIDs and reciprocal links before writing the curated snapshots. The original game assets were not changed.

The .NET UAssetAPI exporter used UAssetAPI 1.1.0, loading the asset with `VER_UE5_6` and writing `SerializeJson()`. Raw extraction output was kept in `/tmp/portfolio-process-20261109/` while the curated, reproducible source snapshots are in this directory. Pin serialization was checked against the installed Unreal source. No Epic engine source is published.

The phone implementation uses both `BPI_Telephony.SendMessage` and the `NewMessageReceived` dispatcher. Messages pass through a reliable server RPC into `MessageLog`; a multicast calls the dispatcher, which the phone widget binds. The multicast itself is not marked reliable. Do not simplify this into an invented BPI-versus-dispatcher fix.

## Build

```sh
python3 projects/portfolio-process/export-sources.py
cd projects/portfolio-process
npm ci
npm run build
```

Monaco is bundled locally under `_public_html/assets/lib/source-editor/`, including its worker, CSS, license, and notices. It is loaded only when a desktop source view comes into view. Mobile uses selectable server-rendered code with original line numbers. Blueprint rendering uses the exported JSON directly. No external editor service is involved.

After changing site files, run `./sync_to_hostinger.sh` from the repository root. This prepares the local mirror; it does not publish anything.
