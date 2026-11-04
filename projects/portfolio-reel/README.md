# Portfolio reel

A silent 17-second loop using moving project footage only. Still-image sections and Still Sleepless are excluded. No added titles, music, or generated imagery. Team credits remain on the project pages.

Bayside uses the colorful opening DJ edit at source seconds 0.8–2. The website shot uses the animated campaign section rather than the gallery containing Still Sleepless. The VR clip shows dragon riding through the rings. The loop closes at the source point where its opening begins.

Each shot fills the 1920 × 1080 frame. Source letterboxing is cropped, portrait footage uses three adjoining panels, and the synchronized golf recording uses adjoining game and phone panels. Every panel uses proportional cover cropping without padding. Mode 7 shows its upper race view without the minimap divider. These choices apply to this reel; original project recordings are retained.

- `edit.json`: source paths, exact in-points, crops, shot durations, and layouts.
- `build.py`: FFmpeg shot preparation, edge-to-edge layouts, H.264 export, and exact first-frame poster.
- `src/Composition.tsx`: independently editable shots in Remotion Studio. Keep durations/order aligned with `edit.json`.
- `public/shots/`: generated preview footage, ignored by Git.
- Website output: `../../_public_html/assets/reel/portfolio-reel.mp4` and its WebP poster.

The full VR recording is `~/Documents/PortfolioOverhaul/Barton_Jake_GameplayVideo.mp4`. The public dragon-flight excerpt uses original seconds 101–119; this reel uses 110–112. Everybody Golf uses the already-aligned game and phone recordings with the same in-point and duration. Footage with phone notifications and home screens is excluded.

## Rebuild and preview

```sh
python3 build.py
npm ci
npm run dev -- --no-open
```

Open the `PortfolioReel` composition. FFmpeg and cwebp are required. `python3 build.py --shots SHOT_ID ...` rebuilds selected shots and reassembles the complete reel. Run `../../sync_to_hostinger.sh` after site changes to prepare the local deployment folders; it does not publish the site.

## Shot order

Cuts run for 1–2 seconds, with the longest shots reserved for the synchronized golf action and VR dragon interaction.

| Reel time | Selection |
| --- | --- |
| 0–1.4s | Ding Dong — hands and character art |
| 1.4–2.8s | Ding Dong — phone interface |
| 2.8–4.8s | Everybody Golf — controller and ball flight |
| 4.8–6.2s | Volleyball — first-person play |
| 6.2–7.8s | Minnie's Golf — three courses |
| 7.8–9.2s | Barton Design Solutions — animated website |
| 9.2–10.4s | Bayside — lyric animation and editing |
| 10.4–11.6s | Mode 7 — racing engine |
| 11.6–12.6s | Phase Runner — platforming |
| 12.6–14.6s | VR Rhythm Game — riding the dragon |
| 14.6–16s | 3D environment — waterfront |
| 16–17s | Ding Dong — return to opening |
