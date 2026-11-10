#!/usr/bin/env python3
"""Build lightweight hover/header copies from approved public footage; keep originals."""
from concurrent.futures import ThreadPoolExecutor
from pathlib import Path
import subprocess
import tempfile
import shutil

ROOT = Path(__file__).resolve().parent.parent
PUBLIC = ROOT / '_public_html'
# Keep each clip's framing; its poster is decoded from the final output.
# Golf starts after the opening menu; Mode 7 uses the racing shot from the reel.
JOBS = [
    ('work/ding-dong/intro-preview.mp4', 'work/ding-dong/hover-preview', 0, 12),
    ('work/everybody-golf/gameplay.mp4', 'work/everybody-golf/hover-preview', 13.6, 12),
    ('work/barton-design-solutions/website-tour.mp4', 'work/barton-design-solutions/hover-preview', 0, 12),
    ('work/volleyball-game/gameplay-video.mp4', 'work/volleyball-game/hover-preview', 0, 12),
    ('work/phase-runner/movement.mp4', 'work/phase-runner/hover-preview', 0, 12),
    ('home/media/mode7.mp4', 'work/mode-7-engine/hover-preview', 50, 12),
    ('work/vr-rhythm-game/dragon-flight.mp4', 'work/vr-rhythm-game/hover-preview', 0, 12),
    ('work/bayside-lyric-video/video.mp4', 'work/bayside-lyric-video/hover-preview', 0, 12),
    ('home/media/environment-preview.mp4', 'home/media/environment-hover', 0, 12),
    ('work/still-sleepless/video.mp4', 'work/still-sleepless/hover-preview', 0, 8),
    ('reel/portfolio-reel.mp4', 'reel/portfolio-reel-preview', 0, 15.4),
]

def build(job):
    source, target, start, duration = job
    source = PUBLIC / 'assets' / source
    target = PUBLIC / 'assets' / target
    target.parent.mkdir(parents=True, exist_ok=True)
    subprocess.run([
        'ffmpeg', '-hide_banner', '-loglevel', 'error', '-y', '-ss', str(start),
        '-i', str(source), '-t', str(duration), '-map', '0:v:0', '-an',
        '-vf', 'fps=30,scale=960:720:force_original_aspect_ratio=decrease:force_divisible_by=2:in_range=auto:out_range=tv,format=yuv420p',
        '-c:v', 'libx264', '-preset', 'medium', '-crf', '25', '-maxrate', '1400k',
        '-bufsize', '2800k', '-color_range', 'tv', '-pix_fmt', 'yuv420p', '-g', '30', '-keyint_min', '30',
        '-sc_threshold', '0', '-threads', '2', '-movflags', '+faststart', str(target)+'.mp4',
    ], check=True)
    with tempfile.TemporaryDirectory(prefix='portfolio-preview-') as folder:
        frame = Path(folder) / 'first-frame.png'
        subprocess.run([
            'ffmpeg', '-hide_banner', '-loglevel', 'error', '-y', '-i', str(target)+'.mp4',
            '-frames:v', '1', str(frame),
        ], check=True)
        subprocess.run(['cwebp', '-quiet', '-q', '85', str(frame), '-o', str(target)+'-poster.webp'], check=True)
    return f'{target.relative_to(PUBLIC)}.mp4: {Path(str(target)+".mp4").stat().st_size / 1024:.0f} KiB'

if __name__ == '__main__':
    for tool in ('ffmpeg', 'cwebp'):
        if not shutil.which(tool):
            raise SystemExit(f'Required media tool not installed: {tool}')
    for source, _, _, _ in JOBS:
        if not (PUBLIC / 'assets' / source).is_file():
            raise SystemExit(f'Missing approved source: {source}')
    with ThreadPoolExecutor(max_workers=2) as pool:
        for result in pool.map(build, JOBS):
            print(result, flush=True)
