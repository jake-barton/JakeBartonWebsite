# Portfolio layout and media

Barton Design Solutions and Everybody Golf are the reference pages. Apply these rules to every published portfolio piece and new page.

## Page order

1. Full-page header with the actual project name and a short category label.
2. Project information: role, disciplines, tools, client, and a short factual description.
3. Actual project videos and images in simple dark frames. Keep the full image visible; preserve its proportions. Pair related landscape footage with its phone recording at the same height on desktop, and stack them on small screens. Put supporting images below.
4. Relevant external links using the shared `jb-fold-underline` style from the email link. Use basic labels such as “Visit website.”
5. The shared footer, with the large name in a color from the project’s own branding or artwork. Keep text and logo readable against the footer background.

Use basic titles and direct descriptions. Avoid hook titles, vague calls to action, quotes, and repetitive paragraphs. Do not put small titles, labels, or captions above or below videos or images. Keep project names and context in the main project heading and introduction. Put necessary credits, team attribution, sample-data notes, or redaction notes in the project description instead.

## Video behavior

- Use real project footage. Trim home screens, unrelated desktop content, and blank lead-ins before publishing.
- Use the first decoded frame of the final video for video-preview cards and header posters. Keep their crop and fit consistent so hovering is seamless. If portrait footage does not suit a wide card, keep its selected artwork cover and turn off the card video preview. Unreleased projects, including Minnie’s Golf, stay entirely outside the web root and all public reels.
- Where a project has footage, use it for the card hover preview and automatic header playback. Static projects use their actual artwork.
- Use lightweight, silent H.264/30fps preview copies for cards, category backgrounds, and project headers. Card metadata can provide `preview_video` and `preview_image`; retain the original `video` and gallery footage. The card and header share the derived clip and its first-frame WebP poster. Warm nearby previews before hover without playing them, preserve the cover until a decoded frame is ready, and cancel pending playback/frame callbacks on exit. Pause filtered-out, offscreen, and hidden-tab previews. Skip speculative hover loading for data saver, touch-only devices, and reduced motion.
- The homepage has a separate, visitor-controlled demo reel directly below the short introduction, followed by the résumé link. At Jake’s request, this player has a prominent “Demo Reel” heading in the same bold italic Helvetica style and size as Resume; this is the exception to the no-small-media-label rule. Use a separate 16:9 clipping frame for its rounded corners so the native player and poster cannot leave square edges. It has normal playback controls, starts on request, and is excluded from decorative autoplay-video handling.
- Detail videos play silently and loop automatically, with no player controls, pause buttons, speed menus, picture-in-picture controls, or video context menus. They are not keyboard focus targets. The shared `project-media.js` manages playback; do not add separate player interfaces.
- Pause media outside the viewport or in a hidden tab, and resume automatically when visible.
- Recordings of the same events must show those events at the same time. Trim them to matching starts and durations, then put them in a `.jb-media-pair` section with the `data-synced-videos` attribute. Its shared clock handles buffering, drift, tab changes, and loop resets. Separate gameplay demos omit `data-synced-videos` and loop independently.
- Preserve interactive games and their controls. These presentation rules apply to recorded footage, not playable game input.

Use H.264 MP4, `yuv420p`, and fast-start metadata for browser playback. Optimize the resolution and frame rate for the footage. Use WebP for posters and supporting images; do not invent replacement project imagery.

## Site reel

Use the edited portfolio reel for the homepage, the menu preview, and the decorative footer video. The homepage uses the Work page’s warm gray canvas and black text, with off-white sections for organizations and the footer. Match the Work page’s horizontal gutters, grid gaps, and responsive card radius across the reel and experience cards. Organizations use the connected rectangular grid with thin shared borders, in three columns on desktop and two on mobile. Balance their visible logo weight with per-asset widths and scale the whole set together, preserving each logo’s proportions. Use the current Wrappt branding and name in the organization grid and experience credit; its project page remains hidden. Keep green for the rail and accents. Category links rest on a neutral background, with no default reel or playing clip. Show the matching category video only on pointer hover or keyboard focus, and hide/pause it when that interaction ends or the section leaves view. Fade category previews in over roughly 750ms, wait for a decoded frame, and crossfade between clips over roughly 850ms while retaining the outgoing frame underneath. Bridge small pointer gaps between links without flashing the neutral surface. Keep category labels dark at rest and white above a dark overlay when a preview is visible. Use large, unboxed category type with no arrows or row dividers. Give the labels a short staggered entrance. On hover/focus, crossfade to the same bold italic Helvetica styling as the name and Resume link, with a small horizontal shift and no rolling duplicate text. Reserve space for both styles so rows do not jump; respect reduced motion. Retain the original category-panel scroll zoom from 0.8 to 1 for the neutral surface, preview video, and type together. Disable it for reduced motion. The introduction’s letters turn the site green on pointer hover and fade back to black over three seconds, retaining the original per-letter return delay of 12ms per letter (capped at 840ms), with no movement. Keep this effect scoped to the introduction and respect reduced motion. Avoid large content-reveal wipes. Right-align the homepage footer name and its matching hover mask. Keep the pacing energetic with cuts of roughly 1–2 seconds. Select moving footage and actual gameplay actions; exclude still-image sections, freeze frames, and Still Sleepless from the reel. The VR selection shows dragon riding. Bayside uses the colorful opening edit rather than the black-background lyric segment. Arrange portrait footage in three panels across, with every panel filled edge to edge. Crop source letterboxing and use cover framing without padding or black borders. The reel fills its video areas on the site. Page loading and page changes use the original green curtain and name wipe; do not place video inside the animated loading curtain. These reel framing rules do not change the full-image treatment on individual project pages. Playback continues from the current reel position as visitors move through the site, pauses when hidden, and respects reduced motion. The edit decision list and editable timeline live in `projects/portfolio-reel/`.

## Editing and validation

Published projects are ordinary PHP/HTML pages under `_public_html/work/<project-name>/index.php`. Work-card metadata is a PHP array in `_public_html/includes/projects.php`. Shared project navigation and footer live in `includes/project-header.php` and `includes/project-footer.php`. There is no visual editor or JSON page store. Use an existing project as a starting point and select project-specific accent/footer colors before publishing. See `EDITING.md` for the file map.

Check desktop and mobile layouts, hover previews, automatic playback, linked recordings, missing assets, external links, and footer contrast. Run `./sync_to_hostinger.sh` after changes to prepare the local deployment folders. Syncing these folders does not publish the live site.

Motion design videos are grouped under `/work/motion-graphics/`: Bayside, Still Sleepless, and Tennis. Their former project URLs redirect to this collection. Wrappt is temporarily hidden. Private projects are preserved as PHP source in `projects/portfolio-drafts/`, outside the web root. To hide a public project, remove its Work-card entry and move its physical page out of `_public_html/work/`.

## Subheading typography

The homepage introduction is one left-aligned column: a separate greeting followed closely by a smaller paragraph with a readable line length. Align both with the demo reel and section gutter. Preserve continuous accessible text alongside the per-letter hover presentation. Do not restore the oversized paragraph or distant two-column greeting/body layout.

The sidebar résumé icon sits directly above LinkedIn, with the same 38px box and 12px vertical gap as the other desktop links. Show no visible Resume label and no gray hover background. Preserve its accessible name, keyboard focus outline, and PDF link opening in a new tab. Mobile retains an icon with a 44px tap target. The text Resume link below the homepage reel remains separate.

Use the same Helvetica Neue stack and regular weight (400) as the homepage introduction for subheadings and small labels throughout the site. The shared rules are in `assets/css/typography.css`; the style-kit typography is aligned with them. Do not substitute condensed display fonts or alternate body fonts. Keep the existing main wordmarks and animated category display styling.

In the homepage experience cards, use a shared logo slot and per-asset widths to balance visible artwork. Preserve each logo’s proportions; do not size all marks by the same height, which makes long wordmarks overpower square icons. Scale the set together on mobile.

Keep social links in the same order as the sidebar: LinkedIn, GitHub, Instagram. Keep footer contact headings larger than their links, with clear spacing between them. Keep menu contact headings separated from their links as well.

The shared footer JB logo opens along its three existing faces on hover or keyboard focus: the cap lifts, the letter faces move apart, and green construction lines draw underneath. Use the authentic logo mask; do not spin or scale the mark. Reassemble on exit and keep it static for reduced motion. The logo links home.

Keep easter eggs rare and local to deliberate interactions. In addition to the footer cube, the toolkit code icon types `<JB/>` once, and both Games controls on Work let a 32px Yoshi kart from the actual Mode 7 sprite sheet pass just outside the pill, clear of its label. The kart starts after 180ms and makes one 2.4-second pass: brake at the center, turn toward the visitor using the actual sheet frames, do a small celebration hop, steer back, and accelerate away. Keep each sprite centered and bottom-aligned in its viewport; do not spin or stretch the sheet. It can replay on a fresh hover or click. The toolkit waits 650ms and retains its eight-second cooldown. Both work with keyboard focus or a click/tap. Stop on exit, offscreen, or hidden tabs. No sound, global keyboard sequences, ambient loops, or animation for reduced motion. Keep filtering and navigation unchanged.

## Process through the scroll

Game case studies follow a simple sequence: what Jake wanted to make, the problem he met, the decision or implementation, and the result he can actually support. Use his answers and verified project source. Keep the writing in his plain voice. Do not fabricate performance claims, exact historical fixes, or code authorship.

Use short `.jb-case-chapter` sections. Put the relevant footage, real code excerpt, or actual exported Blueprint beside the explanation on desktop and directly after it on mobile. Keep the entire story readable by scrolling; chapter links are optional shortcuts. Avoid a single catch-all code gallery, large blank editor areas, dashboards, staged scroll locks, and unrelated source tabs. The homepage has no code gallery.

The shared source viewer embeds actual read-only Monaco for desktop text code and interactive views of saved Blueprint nodes and connections. Mobile retains selectable source text. Preserve original line numbers, filenames, downloadable source, and provenance outside the web root. Viewer toolbars and filenames are functional source controls, not decorative media captions. Large graphs can expand, pan, and zoom without interrupting the normal page flow. Keep source viewer controls local to each example and retain keyboard access and a no-JavaScript fallback.

Minnie’s Golf is unreleased and private at Jake’s request. Remove its card, page, media, source downloads, and appearances in the site reel. Preserve editable originals outside `_public_html/`; do not republish until Jake approves.
