#!/usr/bin/env python3
"""Check the PHP portfolio against a running local preview (no extra packages)."""
import argparse
import json
from pathlib import Path
import re
import subprocess
from urllib.error import HTTPError
from urllib.request import Request, build_opener, HTTPRedirectHandler

ROOT = Path(__file__).resolve().parent.parent


class NoRedirects(HTTPRedirectHandler):
    def redirect_request(self, req, fp, code, msg, headers, newurl):
        return None


def php_list(path):
    return json.loads(subprocess.check_output(
        ['php', '-r', 'echo json_encode(require $argv[1], JSON_THROW_ON_ERROR);', str(path)],
        cwd=ROOT, text=True,
    ))


def main():
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument('--base-url', default='http://localhost:8000')
    args = parser.parse_args()
    opener = build_opener(NoRedirects())

    def request(path, method='GET'):
        req = Request(args.base_url.rstrip('/') + path, method=method)
        try:
            response = opener.open(req, timeout=15)
        except HTTPError as response:
            return response.code, response.headers, response.read().decode()
        with response:
            return response.status, response.headers, response.read().decode()

    projects = php_list(ROOT / '_public_html/includes/projects.php')
    # Check the deployed blocklist without requiring private draft metadata.
    rules = (ROOT / '_public_html/.htaccess').read_text()
    withdrawn = re.search(r'(?m)^RewriteRule \^work/\(\?:([a-z0-9|_-]+)\)', rules)
    assert withdrawn, 'Missing withdrawn-project rule in .htaccess'
    drafts = withdrawn.group(1).split('|')
    status, _, work = request('/work/')
    assert status == 200, 'Work grid is unavailable'
    assert set(re.findall(r'data-project-url="([^"]+)"', work)) == {p['url'] for p in projects.values()}, 'Work cards and PHP list differ'
    for slug, project in projects.items():
        assert (ROOT / '_public_html/work' / slug / 'index.php').is_file(), f'Missing page: {slug}'
        status, headers, body = request(project['url'])
        assert status == 200 and 'id="project-title"' in body, f'Broken project: {slug}'
        assert not headers.get('Set-Cookie'), f'Unexpected session: {slug}'
        assert not re.search(r'data-studio-|studio-preview-bridge|jb_cms_', body), f'Editor remains: {slug}'
    for slug in drafts:
        assert slug not in projects, f'Draft appears in public list: {slug}'
        assert not (ROOT / '_public_html/work' / slug).exists(), f'Draft has a public directory: {slug}'
        for suffix in ['/', '/?studio-preview=1']:
            assert request('/work/' + slug + suffix)[0] == 404, f'Draft is accessible: {slug}'
    for path in ['/studio/', '/studio/index.php', '/review/', '/includes/cms.php', '/assets/js/studio.js', '/assets/js/studio-preview-bridge.js', '/assets/css/studio.css']:
        assert request(path)[0] == 404, f'Retired route is accessible: {path}'
    assert request('/studio/', method='POST')[0] == 404, 'Editor still accepts requests'
    for slug in ['bayside-lyric-video', 'still-sleepless', 'tennis-video']:
        status, headers, _ = request('/work/' + slug + '/')
        assert status == 301 and headers.get('Location') == '/work/motion-graphics/', f'Broken redirect: {slug}'
    print(f'PASS: {len(projects)} public PHP pages, {len(drafts)} private drafts, redirects, retired editor routes, and no content sessions.')


if __name__ == '__main__':
    main()
