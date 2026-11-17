#!/usr/bin/env python3
"""Give a freshly exported BBQ pack a content version for browser caching."""
import hashlib
import json
from pathlib import Path
import re

ROOT = Path(__file__).resolve().parents[3]
RUNTIME = ROOT / '_public_html/games/bbq'
html_path = RUNTIME / 'index.html'
html = html_path.read_text()
match = re.search(r'const config=(\{[^\n]+\});', html)
if not match:
    raise SystemExit('Expected the Godot export configuration in index.html')
config = json.loads(match[1])
pack = RUNTIME / 'index.pck'
if not pack.is_file():
    raise SystemExit('Export Web first: the fresh index.pck is missing')
revision = hashlib.sha256(pack.read_bytes()).hexdigest()[:16]
name = f'bbq-{revision}.pck'
config['mainPack'] = name
config['fileSizes'].pop('index.pck', None)
config['fileSizes'][name] = pack.stat().st_size
html = html[:match.start(1)] + json.dumps(config, separators=(',', ':')) + html[match.end(1):]
pack.rename(RUNTIME / name)
html_path.write_text(html)
for old in RUNTIME.glob('bbq-*.pck'):
    if old.name != name:
        old.unlink()
# The custom shell uses the site's existing favicon; Godot's splash is unused.
(RUNTIME / 'index.png').unlink(missing_ok=True)
print(revision)
