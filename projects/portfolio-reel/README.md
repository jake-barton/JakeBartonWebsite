# Portfolio reel

A silent 15.4-second loop using moving project footage only. Still-image sections and Still Sleepless are excluded. No added titles, music, or generated imagery. Team credits remain on the project pages.

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
| 6.2–7.6s | Barton Design Solutions — animated website |
| 7.6–8.8s | Bayside — lyric animation and editing |
| 8.8–10s | Mode 7 — racing engine |
| 10–11s | Phase Runner — platforming |
| 11–13s | VR Rhythm Game — riding the dragon |
| 13–14.4s | 3D environment — waterfront |
| 14.4–15.4s | Ding Dong — return to opening |

Minnie’s Golf is unreleased and excluded from the public edit and timeline.

The visitor-controlled homepage player retains the full 1920 × 1080 export. Decorative hero/menu/footer contexts use `assets/reel/portfolio-reel-preview.mp4`, a lighter copy with the same complete 15.4-second timeline, so shared playback time stays valid. After rebuilding the reel, run `python3 scripts/build-previews.py` from the repository root to refresh this copy and its first-frame poster, then sync the site.
