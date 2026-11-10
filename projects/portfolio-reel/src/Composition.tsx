import {AbsoluteFill, Composition, Series, staticFile, useVideoConfig} from 'remotion';
import {Video} from '@remotion/media';

const PortfolioReel = () => {
  const {fps} = useVideoConfig();
  return <AbsoluteFill><Series>
    <Series.Sequence name="Ding Dong — hands and character art" durationInFrames={42} premountFor={fps}>
      <Video src={staticFile('shots/01-ding-hands.mp4')} muted objectFit="cover" premountFor={fps} style={{width: '100%', height: '100%'}} />
    </Series.Sequence>
    <Series.Sequence name="Ding Dong — phone interface" durationInFrames={42} premountFor={fps}>
      <Video src={staticFile('shots/02-ding-phone.mp4')} muted objectFit="cover" premountFor={fps} style={{width: '100%', height: '100%'}} />
    </Series.Sequence>
    <Series.Sequence name="Everybody Golf — controller and ball flight" durationInFrames={60} premountFor={fps}>
      <Video src={staticFile('shots/03-everybody-golf.mp4')} muted objectFit="cover" premountFor={fps} style={{width: '100%', height: '100%'}} />
    </Series.Sequence>
    <Series.Sequence name="Volleyball — first-person play" durationInFrames={42} premountFor={fps}>
      <Video src={staticFile('shots/04-volleyball.mp4')} muted objectFit="cover" premountFor={fps} style={{width: '100%', height: '100%'}} />
    </Series.Sequence>
    <Series.Sequence name="Barton Design Solutions — animated website" durationInFrames={42} premountFor={fps}>
      <Video src={staticFile('shots/06-bds.mp4')} muted objectFit="cover" premountFor={fps} style={{width: '100%', height: '100%'}} />
    </Series.Sequence>
    <Series.Sequence name="Bayside — lyric animation and editing" durationInFrames={36} premountFor={fps}>
      <Video src={staticFile('shots/08-bayside.mp4')} muted objectFit="cover" premountFor={fps} style={{width: '100%', height: '100%'}} />
    </Series.Sequence>
    <Series.Sequence name="Mode 7 — racing engine" durationInFrames={36} premountFor={fps}>
      <Video src={staticFile('shots/09-mode7.mp4')} muted objectFit="cover" premountFor={fps} style={{width: '100%', height: '100%'}} />
    </Series.Sequence>
    <Series.Sequence name="Phase Runner — platforming" durationInFrames={30} premountFor={fps}>
      <Video src={staticFile('shots/09b-phase.mp4')} muted objectFit="cover" premountFor={fps} style={{width: '100%', height: '100%'}} />
    </Series.Sequence>
    <Series.Sequence name="VR Rhythm Game — riding the dragon" durationInFrames={60} premountFor={fps}>
      <Video src={staticFile('shots/09c-vr.mp4')} muted objectFit="cover" premountFor={fps} style={{width: '100%', height: '100%'}} />
    </Series.Sequence>
    <Series.Sequence name="3D environment — waterfront" durationInFrames={42} premountFor={fps}>
      <Video src={staticFile('shots/10-environment.mp4')} muted objectFit="cover" premountFor={fps} style={{width: '100%', height: '100%'}} />
    </Series.Sequence>
    <Series.Sequence name="Ding Dong — return to opening" durationInFrames={30} premountFor={fps}>
      <Video src={staticFile('shots/11-ding-loop.mp4')} muted objectFit="cover" premountFor={fps} style={{width: '100%', height: '100%'}} />
    </Series.Sequence>
  </Series></AbsoluteFill>;
};

export const MyComposition = () => <Composition id="PortfolioReel" component={PortfolioReel} durationInFrames={462} fps={30} width={1920} height={1080} />;
