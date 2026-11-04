#!/usr/bin/env python3
"""Publish only the Mode 7 browser runtime from the preserved project source."""

from pathlib import Path
import shutil

ROOT = Path(__file__).resolve().parent.parent
SOURCE = ROOT / "projects/game-development/mode7-engine"
DESTINATION = ROOT / "_public_html/games/mario-kart"


def build():
    files = [SOURCE / "index.html", SOURCE / "game.js"]
    for folder, extension in (
        ("src", ".js"),
        ("assets", ".png"),
        ("spritesheets", ".png"),
        ("MARIO KART SNES/images", ".png"),
    ):
        matches = sorted((SOURCE / folder).rglob(f"*{extension}"))
        if not matches:
            raise SystemExit(f"Missing game runtime files: {folder}")
        files.extend(matches)
    for path in files:
        if not path.is_file():
            raise SystemExit(f"Missing game runtime file: {path}")

    if DESTINATION.exists():
        shutil.rmtree(DESTINATION)
    for path in files:
        target = DESTINATION / path.relative_to(SOURCE)
        target.parent.mkdir(parents=True, exist_ok=True)
        shutil.copy2(path, target)
    print(f"Built Mario Kart: {len(files)} runtime files → {DESTINATION.relative_to(ROOT)}/")


if __name__ == "__main__":
    build()
