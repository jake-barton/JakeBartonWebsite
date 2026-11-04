#!/usr/bin/env python3
"""Build the silent website reel from the reviewed edit decision list.

Only curated, already-public project assets are used. Source clips are retained.
FFmpeg makes exact-frame, edge-to-edge layouts; Remotion previews the same shots.
"""
import concurrent.futures
import json
from pathlib import Path
import subprocess
import argparse

HERE = Path(__file__).resolve().parent
ROOT = HERE.parents[1]
ASSETS = ROOT / '_public_html/assets'
SHOTS = HERE / 'public/shots'
OUTPUT = ASSETS / 'reel'
EDIT = json.loads((HERE / 'edit.json').read_text())


def run(args):
    subprocess.run(args, check=True)


def build(shot):
    target = SHOTS / (shot['id'] + '.mp4')
    cmd = ['ffmpeg', '-hide_banner', '-loglevel', 'error', '-y', '-filter_complex_threads', '1']
    for source in shot['sources']:
        path = ASSETS / source['path']
        if source.get('still'):
            raise ValueError('The site reel uses moving footage only.')
        cmd += ['-ss', str(source.get('start', 0))]
        cmd += ['-t', str(shot['duration']), '-i', str(path)]
    boxes = [(1920, 1080)]
    if shot['layout'] == 'triptych':
        boxes = [(640, 1080)] * 3
    elif shot['layout'] == 'gameplay-phone':
        boxes = [(1440, 1080), (480, 1080)]
    filters = []
    for i, (w, h) in enumerate(boxes):
        crop = shot['sources'][i].get('crop')
        source_crop = f'crop={crop},' if crop else ''
        filters.append(f'[{i}:v]{source_crop}fps=30,scale={w}:{h}:force_original_aspect_ratio=increase:flags=lanczos,crop={w}:{h},setsar=1,setpts=PTS-STARTPTS[v{i}]')
    output = '[v0]'
    if len(boxes) > 1:
        filters.append(''.join(f'[v{i}]' for i in range(len(boxes))) + f'hstack=inputs={len(boxes)}:shortest=1[out]')
        output = '[out]'
    cmd += ['-filter_complex', ';'.join(filters), '-map', output,
            '-an', '-t', str(shot['duration']), '-c:v', 'libx264', '-preset', 'medium',
            '-crf', '19', '-maxrate', '8M', '-bufsize', '16M', '-pix_fmt', 'yuv420p',
            '-r', '30', '-g', '30', '-threads', '2', '-movflags', '+faststart', str(target)]
    run(cmd)
    print('Prepared', shot['name'], flush=True)
    return target


if __name__ == '__main__':
    parser = argparse.ArgumentParser()
    parser.add_argument('--shots', nargs='*', help='Rebuild only these shot IDs, then assemble the complete reel.')
    args = parser.parse_args()
    SHOTS.mkdir(parents=True, exist_ok=True)
    OUTPUT.mkdir(parents=True, exist_ok=True)
    selected = [s for s in EDIT['shots'] if not args.shots or s['id'] in args.shots]
    with concurrent.futures.ThreadPoolExecutor(max_workers=2) as pool:
        list(pool.map(build, selected))
    files = [SHOTS / (s['id'] + '.mp4') for s in EDIT['shots']]
    concat = SHOTS / 'timeline.txt'
    concat.write_text(''.join(f"file '{p.name}'\n" for p in files))
    # Re-encode the assembled delivery file once for a small, consistent web stream.
    run(['ffmpeg', '-hide_banner', '-loglevel', 'error', '-y', '-f', 'concat', '-safe', '0',
         '-i', str(concat), '-an', '-c:v', 'libx264', '-preset', 'slow', '-crf', '24',
         '-maxrate', '4M', '-bufsize', '8M', '-pix_fmt', 'yuv420p', '-r', '30',
         '-g', '30', '-threads', '4', '-movflags', '+faststart', str(OUTPUT / 'portfolio-reel.mp4')])
    run(['ffmpeg', '-hide_banner', '-loglevel', 'error', '-y', '-i', str(OUTPUT / 'portfolio-reel.mp4'),
         '-frames:v', '1', str(SHOTS / 'poster.png')])
    run(['cwebp', '-quiet', '-lossless', str(SHOTS / 'poster.png'), '-o', str(OUTPUT / 'portfolio-reel-poster.webp')])
    print('Built', OUTPUT / 'portfolio-reel.mp4', flush=True)
