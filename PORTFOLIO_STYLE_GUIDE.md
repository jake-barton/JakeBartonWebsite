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
- Use the first decoded frame of the final video for video-preview cards and header posters. Keep their crop and fit consistent so hovering is seamless. If portrait footage does not suit a wide card, keep its selected artwork cover and turn off the card video preview. Minnie’s Golf uses this exception at Jake’s request.
- Where a project has footage, use it for the card hover preview and automatic header playback. Static projects use their actual artwork.
- The homepage has a separate, visitor-controlled demo reel directly below the short introduction, followed by the résumé link. It has normal playback controls, starts on request, and is excluded from decorative autoplay-video handling.
- Detail videos play silently and loop automatically, with no player controls, pause buttons, speed menus, picture-in-picture controls, or video context menus. They are not keyboard focus targets. The shared `project-media.js` manages playback; do not add separate player interfaces.
- Pause media outside the viewport or in a hidden tab, and resume automatically when visible.
- Recordings of the same events must show those events at the same time. Trim them to matching starts and durations, then put them in a `media_pair` section with `sync_videos` enabled. Its shared clock handles buffering, drift, tab changes, and loop resets. Separate gameplay demos use `sync_videos: false` and loop independently.
- Preserve interactive games and their controls. These presentation rules apply to recorded footage, not playable game input.

Use H.264 MP4, `yuv420p`, and fast-start metadata for browser playback. Optimize the resolution and frame rate for the footage. Use WebP for posters and supporting images; do not invent replacement project imagery.

## Site reel

Use the edited portfolio reel for the homepage, the menu preview, the default category preview, and the decorative footer video. Keep project-specific category hover previews. Keep the pacing energetic with cuts of roughly 1–2 seconds. Select moving footage and actual gameplay actions; exclude still-image sections, freeze frames, and Still Sleepless from the reel. The VR selection shows dragon riding. Bayside uses the colorful opening edit rather than the black-background lyric segment. Arrange portrait footage in three panels across, with every panel filled edge to edge. Crop source letterboxing and use cover framing without padding or black borders. The reel fills its video areas on the site. Page loading and page changes use the original green curtain and name wipe; do not place video inside the animated loading curtain. These reel framing rules do not change the full-image treatment on individual project pages. Playback continues from the current reel position as visitors move through the site, pauses when hidden, and respects reduced motion. The edit decision list and editable timeline live in `projects/portfolio-reel/`.

## Editing and validation

Published content lives in `storage/cms/pages/`. Reusable components live in `_public_html/includes/cms-components/`. The Studio blueprint in `_public_html/includes/project-blueprint.json` starts new pages with the same header → information → media structure. Select a project-specific accent and footer wordmark color before publishing.

Check desktop and mobile layouts, hover previews, automatic playback, linked recordings, missing assets, external links, and footer contrast. Run `./sync_to_hostinger.sh` after changes to prepare the local deployment folders. Syncing these folders does not publish the live site.

Motion design videos are grouped under `/work/motion-graphics/`: Bayside, Still Sleepless, and Tennis. Their former project URLs redirect to this collection. Wrappt is temporarily hidden. Previously published pages that must be hidden on deployment use `deploy_unpublished: true`; unrelated private drafts stay excluded from export.
