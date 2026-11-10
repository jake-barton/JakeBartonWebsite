# Portfolio project library

This directory contains preserved source, working assets, and editorial notes for published case studies and private drafts. It is outside `_public_html`, so these source directories are not website routes. The complete current publication list is [PORTFOLIO_INVENTORY.md](PORTFOLIO_INVENTORY.md).

The old portfolio subpages have been removed. New pages should be built from these source packages instead of modifying or duplicating the originals.

## Preserved source packages

This table locates the original library packages; it is not the current Work-card list. Some preparation notes record the original review. For current pages and confirmed credits, use the inventory, each PHP page, and `portfolio-process/interview-notes.md`.

| Project | Category | Source package | Material to prepare for the new site |
| --- | --- | --- | --- |
| DeskPet | Game development | `game-development/deskpet/` | Confirm role, write summary, select gameplay media and results |
| Mode 7 Engine | Game development | `game-development/mode7-engine/` | Explain the renderer, select code excerpts and gameplay media |
| Phase Runner | Game development | `game-development/phase-runner/` | Confirm build details, role, tools, gameplay media and link |
| 3D coursework / Kame House | 3D art | `3d-art/coursework/` | Select final renders, process images and software details |
| 33 Miles | Visual design | `visual-design/33-miles/` | Curate the original assets and web-gallery work into one story |
| College Guys Pressure Washing | Visual design | `visual-design/college-guys-pressure-washing/` | Select strongest deliverables and document the design brief |
| T-shirt designs | Visual design | `visual-design/tshirt-designs/` | Curate final designs and add context for each collection |
| Veritas Social | Visual design | `visual-design/veritas-social/` | Select campaign work, define responsibilities and outcomes |
| IronSpark website | Web development | `web-development/ironspark-website/` | Document technical ownership, features and live/project media |
| Pi Kappa Phi store | Web development | `web-development/pi-kappa-phi-store/` | Compare current and legacy versions; document improvements |
| Shel Silverstein site | Web development | `web-development/shel-silverstein-site/` | Add project context, implementation notes and screenshots |
| Tech Birmingham AI | Web development | `web-development/tech-birmingham-ai/` | Explain the AI tools, contribution, stack and impact |

`visual-design/captains-log-tiles.png` is preserved as an unassigned design asset. It should be attached to the appropriate project before that case study is built.

The [current inventory](PORTFOLIO_INVENTORY.md) records the new additions, review decisions, and original source locations. New `PORTFOLIO.md` files link to the Desktop, Downloads, or Unreal project sources without duplicating those working folders.

## Portfolio copy and presentation

- Use the project name as the title. Avoid hook titles, slogans, invented quotes, and vague calls to action.
- Keep descriptions short and factual: what the project is, Jake's role, the tools used, and what was built.
- Use actual exports, artwork, or screenshots. Keep screenshots uncropped in project galleries and credit team artwork separately from programming.
- Unfinished prototypes need Jake's approval before publication. Keep their PHP pages in `portfolio-drafts/` outside the web root until approved. Studio is retired.
- New source locations and review notes are recorded in `PORTFOLIO_INVENTORY.md`. Original Desktop and Downloads projects stay in their original locations.

## Source organization

- Keep editable files, source code, project-specific media and documentation inside the project's folder.
- Keep shared live-site assets in `_public_html/assets/`; do not make a second source-code copy there.
- Use lowercase kebab-case for new project directories and future page slugs.
- Create a `PORTFOLIO.md` in a project folder from `_template/PORTFOLIO.md` before building its page.
- Curate optimized copies for the website; preserve original working files in this library.
- The playable Mode 7 demo is built with `python3 scripts/build-games.py` into `_public_html/games/mario-kart/`. This generated runtime is rebuilt for deployment; keep edits in its source package here.
- Do not place new portfolio routes in `_public_html` until their content and media checklist is complete.

## Website structure

- `_public_html/` — the live landing page and shared website runtime.
- `_public_html/assets/brand/` — Jake Barton identity assets.
- `_public_html/assets/organizations/` — company and organization logos.
- `_public_html/assets/home/` — media used directly by the landing page.
- `_public_html/assets/project-covers/` — reusable cover images for future project cards.
- `_public_html/assets/documents/` — public resume documents.
- `projects/` — preserved project source and original portfolio material.

See [portfolio-process/README.md](portfolio-process/README.md) for source-backed chapters and [portfolio-reel/README.md](portfolio-reel/README.md) for the public reel. Minnie’s Golf is unreleased: its page, media, organization mark, source download, and reel appearances remain private. Wrappt’s page is hidden; its approved homepage organization and experience credit remain visible.

