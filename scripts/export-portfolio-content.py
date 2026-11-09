#!/usr/bin/env python3
"""Export published pages and explicit unpublishing updates, excluding drafts."""

import json
from pathlib import Path
import re
import shutil

ROOT = Path(__file__).resolve().parent.parent
DESTINATION = ROOT / "hostinger_content/storage/cms/pages"


def main():
    published = []
    hidden = 0
    for source in sorted((ROOT / "storage/cms/pages").glob("*.json")):
        page = json.loads(source.read_text())
        if not page.get("published") and not page.get("deploy_unpublished"):
            continue
        if not page.get("published"):
            hidden += 1
        slug = page.get("slug", "")
        if not re.fullmatch(r"[a-z0-9]+(?:-[a-z0-9]+)*", slug):
            raise SystemExit(f"Invalid page slug: {source.name}")
        page.pop("review_note", None)
        page.pop("_review_draft", None)
        page.pop("deploy_unpublished", None)
        published.append((slug, page))
    if DESTINATION.exists():
        shutil.rmtree(DESTINATION)
    DESTINATION.mkdir(parents=True)
    for slug, page in published:
        (DESTINATION / f"{slug}.json").write_text(json.dumps(page, indent=4, ensure_ascii=False) + "\n")
    print(f"Prepared {len(published) - hidden} published pages and {hidden} visibility updates in {DESTINATION.relative_to(ROOT)}/")


if __name__ == "__main__":
    main()
