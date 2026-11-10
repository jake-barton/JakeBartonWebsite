# Everybody Golf

- Status: Published in the portfolio
- Page: `/work/everybody-golf/`
- Role: Sole Creator
- Original source: [Everybody Golf](</Users/jakebarton/Downloads/Everybody Golf>)
- Display media: `/_public_html/assets/work/everybody-golf/`

Sole creator confirmed by Jake. Gameplay and phone-controller recordings supplied by Jake show the Mac beta and companion app. The card and header use the lightweight gameplay selection documented below, with its first decoded frame as the poster. The detail page pairs both recordings and includes the supplied phone screenshots. The phone recording starts three seconds into the original, after the home screen.

## Copy

Everybody Golf is a Mac golf game with an iPhone motion-controller companion. I created the game and companion app.

The available build is a Mac beta. Players connect their phones on the same local network, join with a room code, and use the phone to swing.

## Media sources

- Mac recording: `~/Downloads/Screen Recording 2026-09-23 at 1.03.52 PM 2.mov`
- Phone recording: `~/Downloads/ScreenRecording_09-23-2026 13-04-07_1 2.mp4`
- Phone screenshots: `~/Downloads/IMG_4607.PNG` and `~/Downloads/IMG_4602.PNG`
- Public clips use H.264 MP4 with fast-start playback.

## Paired recording timing

The paired Mac clip begins at 13.6 seconds in the original recording. The phone clip begins at 3 seconds, after the home screen. Both are 65.766667 seconds long at 60 fps. This removes the 13.6-second event offset between the previously displayed clips. The driver and 5-iron launches were used to check alignment.

The detail page uses `gameplay-aligned.mp4` and `phone-controller-aligned.mp4`. `project-media.js` starts them together and keeps them linked through buffering, visibility changes, and looping. The header/card use `hover-preview.mp4`, a lightweight 12-second selection beginning 13.6 seconds into the original, after the menu, with its own first-frame poster. The complete original and paired recordings are preserved. The footer wordmark uses Everybody Golf green (`#007d67`).
