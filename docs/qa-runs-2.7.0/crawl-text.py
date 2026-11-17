#!/usr/bin/env python3
"""Fetch every published URL of the local test site and save status + visible text per URL.
Usage: crawl-text.py <out.json>"""
import json, re, subprocess, sys, html, urllib.request, urllib.error

WP = ['wp', '--allow-root', '--path=/home/claude/wptest']
urls = []
for t in ('page', 'post', 'case_study', 'team_member'):
    r = subprocess.run(WP + ['post', 'list', f'--post_type={t}', '--post_status=publish', '--field=url'], capture_output=True, text=True)
    urls += [u for u in r.stdout.split() if u.startswith('http')]
urls += ['http://127.0.0.1:8090/?s=%D8%B3%D9%8A%D9%88', 'http://127.0.0.1:8090/no-such-page-xyz/']

def text(doc):
    doc = re.sub(r'(?is)<(script|style|noscript|template)\b.*?</\1>', ' ', doc)
    doc = re.sub(r'(?s)<[^>]+>', '\n', doc)
    lines = [re.sub(r'\s+', ' ', html.unescape(l)).strip() for l in doc.split('\n')]
    return [l for l in lines if l]

out = {}
for u in sorted(set(urls)):
    try:
        with urllib.request.urlopen(u, timeout=60) as r:
            code, body = r.status, r.read().decode('utf-8', 'replace')
    except urllib.error.HTTPError as e:
        code, body = e.code, e.read().decode('utf-8', 'replace')
    out[u] = {'status': code, 'text': text(body), 'php_error': bool(re.search(r'(Fatal error|Warning:|Notice:|Deprecated:)', body))}
json.dump(out, open(sys.argv[1], 'w'), ensure_ascii=False, indent=0)
bad = {u: v['status'] for u, v in out.items() if v['status'] != 200 and 'no-such-page' not in u}
print(f"{len(out)} urls; non-200: {bad}; php errors in page: {[u for u, v in out.items() if v['php_error']]}")
