#!/usr/bin/env python3
"""Preview unpublished portfolio candidates on localhost without publishing them."""

import argparse
import html
import json
import os
from pathlib import Path
import subprocess
import tempfile

ROOT = Path(__file__).resolve().parent.parent


def main():
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument("--port", type=int, default=8188)
    args = parser.parse_args()
    with tempfile.TemporaryDirectory(prefix="jb-portfolio-review-") as temporary:
        folder = Path(temporary)
        data = folder / "cms/pages"
        data.mkdir(parents=True)
        candidates = []
        for source in sorted((ROOT / "storage/cms/pages").glob("*.json")):
            page = json.loads(source.read_text())
            if not page.get("published") and page.get("review_note"):
                candidates.append(page.copy())
                page["published"] = True
                page["_review_draft"] = True
            (data / source.name).write_text(json.dumps(page, ensure_ascii=False))
        cards = []
        for page in candidates:
            escape = html.escape
            cards.append(
                f'<article><a href="/work/{escape(page["slug"])}/">'
                f'<img src="{escape(page["cover"])}" alt="" loading="lazy">'
                f'<h2>{escape(page["title"])}</h2></a>'
                f'<p>{escape(page["subtitle"])}</p>'
                f'<p class="note">{escape(page["review_note"])}</p></article>'
            )
        (folder / "review.html").write_text(
            '<!doctype html><html lang="en"><meta charset="utf-8">'
            '<meta name="viewport" content="width=device-width, initial-scale=1">'
            '<title>Portfolio drafts</title><style>'
            '*{box-sizing:border-box}body{margin:0;padding:40px;background:#f0e7d3;color:#141414;font:16px/1.6 system-ui,sans-serif}'
            'main{max-width:1240px;margin:auto}h1{font-size:clamp(2.4rem,5vw,4rem);line-height:1.1;margin:0 0 20px}'
            '.grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:28px;margin-top:40px}'
            'article{background:#fff;padding:20px}article img{width:100%;height:260px;object-fit:contain;background:#101010}'
            'h2{font-size:1.4rem;margin:16px 0 8px}p{margin:8px 0}.note{color:#555}a{color:inherit}'
            '@media(max-width:700px){body{padding:20px}.grid{grid-template-columns:1fr}article img{height:220px}}'
            '</style><main><h1>Portfolio drafts</h1>'
            '<p>These projects are hidden from the portfolio. Open each page to review the images and copy, then tell me which to include.</p>'
            '<p><a href="http://localhost:8000/work/">Current portfolio</a></p>'
            '<div class="grid">' + ''.join(cards) + '</div></main></html>'
        )
        # This router and the temporary page copies are used only by this process.
        runtime_router = str(ROOT / "_public_html/router.php").replace("'", "\\'")
        (folder / "router.php").write_text(
            "<?php\nif (parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) === '/review/') {"
            "readfile(__DIR__ . '/review.html'); return true;}\n"
            f"require '{runtime_router}';\n"
        )
        environment = dict(os.environ, JB_CMS_DATA_DIR=str(folder / "cms"), JB_PORTFOLIO_REVIEW="1")
        print(f"Review {len(candidates)} drafts: http://localhost:{args.port}/review/", flush=True)
        print("Original page files remain unchanged. Stop with Ctrl+C.", flush=True)
        try:
            subprocess.run(
                ["php", "-S", f"127.0.0.1:{args.port}", "-t", str(ROOT / "_public_html"), str(folder / "router.php")],
                cwd=ROOT, env=environment, check=True,
            )
        except KeyboardInterrupt:
            pass


if __name__ == "__main__":
    main()
