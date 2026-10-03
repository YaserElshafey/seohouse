#!/usr/bin/env python3
"""After an update: every address in the XML sitemaps and every internal link on those pages.

Checks per page: HTTP status (no redirect followed), canonical = the page's own address on pages
that are indexed, robots tag, a <title>, no "/new/" left in links or canonical. Internal links:
none answers 4xx/5xx. Sitemaps: no address that redirects or is missing, no address of the old
site that this site does not have. PHP errors: lines added to debug.log while crawling.

Usage: site-crawl.py <base-url> <wp-path> <out.json> [--known-redirect /path/ ...]
"""
import json, os, re, sys, html, urllib.request, urllib.parse
base, wp_path, out = sys.argv[1].rstrip('/'), sys.argv[2], sys.argv[3]
known_redirects = set(a for i, a in enumerate(sys.argv) if i > 0 and sys.argv[i - 1] == '--known-redirect')
class NoRedir(urllib.request.HTTPRedirectHandler):
    def redirect_request(self, *a, **k): return None
op = urllib.request.build_opener(NoRedir)
def get(u):
    try:
        r = op.open(urllib.request.Request(u, headers={'User-Agent': 'SEOHouse-crawl'}), timeout=60)
        return r.status, r.headers.get('Location', ''), r.read().decode('utf-8', 'replace')
    except urllib.error.HTTPError as e:
        return e.code, e.headers.get('Location', ''), ''
log = os.path.join(wp_path, 'wp-content/debug.log')
log0 = os.path.getsize(log) if os.path.exists(log) else 0
res = {'sitemaps': {}, 'pages': [], 'links': {}, 'problems': []}
st, _, idx = get(base + '/sitemap_index.xml')  # Rank Math
if st != 200:
    st, _, idx = get(base + '/wp-sitemap.xml')  # WordPress's own sitemaps
maps = re.findall(r'<loc>([^<]+)</loc>', idx)
urls = []
for m in maps:
    _, _, x = get(html.unescape(m))
    locs = [html.unescape(l) for l in re.findall(r'<loc>([^<]+)</loc>', x)]
    res['sitemaps'][m.replace(base, '')] = [urllib.parse.unquote(l.replace(base, '')) for l in locs]
    urls += locs
if st != 200 or not urls:
    res['problems'].append(f'sitemap_index.xml: HTTP {st}, {len(urls)} addresses')
links = set()
for u in urls:
    code, loc, h = get(u)
    head = h.split('</head>')[0]
    can = (re.search(r'<link rel="canonical" href="([^"]+)"', head) or [None, ''])[1]
    rob = (re.search(r'<meta name="robots" content="([^"]+)"', head) or [None, ''])[1]
    title = (re.search(r'<title>([^<]*)</title>', head) or [None, ''])[1]
    path = urllib.parse.unquote(u.replace(base, ''))
    row = {'path': path, 'status': code, 'canonical': urllib.parse.unquote(can.replace(base, '')), 'robots': rob, 'title': html.unescape(title)}
    res['pages'].append(row)
    if code != 200:
        res['problems'].append(f'sitemap address {path}: HTTP {code} {loc}')
        continue
    if 'noindex' not in rob and urllib.parse.unquote(can) != urllib.parse.unquote(u):
        res['problems'].append(f'{path}: canonical {can}')
    if 'noindex' in rob:
        res['problems'].append(f'{path}: in the sitemap but robots {rob}')
    if not title:
        res['problems'].append(f'{path}: no <title>')
    if '/new/' in h.replace(base + '/new/', 'NEWLINK'):
        pass
    if re.search(re.escape(base) + r'/new/', h) or re.search(r'href="/new/', h):
        res['problems'].append(f'{path}: link to /new/')
    for href in re.findall(r'href="([^"#]+)', h):
        href = html.unescape(href)
        if href.startswith('/') and not href.startswith('//'):
            href = base + href
        if href.startswith(base) and not re.search(r'/wp-(content|includes|json|admin)/|\.(css|js|png|jpe?g|webp|svg|ico|xml|woff2?)(\?|$)|/feed/|xmlrpc|\?', href):
            links.add(href.split('#')[0])
for l in sorted(links):
    code, loc, _ = get(l)
    p = urllib.parse.unquote(l.replace(base, ''))
    res['links'][p] = code
    if code >= 400 or (code in (301, 302) and p not in known_redirects):
        res['problems'].append(f'link {p}: HTTP {code} {loc}')
lines = open(log, encoding='utf-8', errors='replace').read()[log0:].splitlines() if os.path.exists(log) else []
php = [l for l in lines if re.search(r'PHP (Fatal|Warning|Notice|Parse|Deprecated)', l)]
res['php_errors'] = php[:50]
if php:
    res['problems'].append(f'{len(php)} PHP error line(s)')
json.dump(res, open(out, 'w'), ensure_ascii=False, indent=1)
print(f"sitemaps {len(maps)}, addresses {len(urls)}, internal links {len(links)}, PHP error lines {len(php)}, problems {len(res['problems'])}")
for p in res['problems'][:30]:
    print('  -', p)
sys.exit(1 if res['problems'] else 0)
