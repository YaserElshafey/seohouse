#!/usr/bin/env python3
"""
Test fixture only: reads the main site's PUBLIC REST API and article pages (GET, read-only) and
writes posts.json + the image files, so a local copy of the main site can be built for testing the
migration (tools/qa/fixtures/build-source.php). The real migration reads the main site's database.
Usage: fetch-live-posts.py <site> <out-dir> <uploads-dir>
"""
import json, os, re, sys, urllib.request, urllib.parse, html
SITE, OUT, UP = sys.argv[1].rstrip('/'), sys.argv[2], sys.argv[3]
os.makedirs(OUT, exist_ok=True)
def get(path):
    with urllib.request.urlopen(SITE + path if path.startswith('/') else path) as r:
        return r.read()
J = lambda p: json.loads(get(p))
posts = J('/wp-json/wp/v2/posts?per_page=100&status=publish')
media_ids, users, cats, tags = set(), set(), set(), set()
for p in posts:
    if p['featured_media']: media_ids.add(p['featured_media'])
    media_ids.update(int(x) for x in re.findall(r'wp-image-(\d+)', p['content']['rendered']))
    users.add(p['author']); cats.update(p['categories']); tags.update(p['tags'])
    page = get(p['link']).decode()
    head = page.split('</head>')[0]
    m = lambda rx: (re.search(rx, head) or [None, None])[1]
    p['_rank_math'] = {
        'title': html.unescape(m(r'<title>(.*?)</title>') or ''),
        'description': html.unescape(m(r'<meta name="description" content="([^"]*)"') or ''),
        'robots': m(r'<meta name="robots" content="([^"]*)"') or '',
        'og_image': m(r'<meta property="og:image" content="([^"]*)"') or '',
        'schema': [json.loads(x) for x in re.findall(r'<script type="application/ld\+json" class="rank-math-schema">(.*?)</script>', page, re.S)],
    }
media = []
for mid in sorted(media_ids):
    try: media.append(J(f'/wp-json/wp/v2/media/{mid}'))
    except Exception as e: print('media', mid, e)
users_d = []
for u in users:
    try: users_d.append(J(f'/wp-json/wp/v2/users/{u}'))
    except Exception as e: users_d.append({'id': u, 'name': f'author{u}', 'slug': f'author{u}'})
cats_d = [J(f'/wp-json/wp/v2/categories/{c}') for c in cats]
tags_d = [J(f'/wp-json/wp/v2/tags/{t}') for t in tags]
# image files: original and every size, same relative path under uploads
n = 0
for md in media:
    d = md.get('media_details') or {}
    rel = d.get('file') or urllib.parse.urlparse(md['source_url']).path.split('/wp-content/uploads/')[1]
    urls = {rel: md['source_url']}
    base = os.path.dirname(rel)
    for s in (d.get('sizes') or {}).values():
        urls[(base + '/' if base else '') + s['file']] = s['source_url']
    if d.get('original_image'):
        urls[(base + '/' if base else '') + d['original_image']] = md['source_url'].rsplit('/', 1)[0] + '/' + d['original_image']
    for r, u in urls.items():
        dest = os.path.join(UP, urllib.parse.unquote(r))
        if os.path.exists(dest): continue
        os.makedirs(os.path.dirname(dest), exist_ok=True)
        try:
            with urllib.request.urlopen(urllib.parse.quote(u, safe=':/%')) as r2, open(dest, 'wb') as f: f.write(r2.read()); n += 1
        except Exception as e: print('file', u, e)
json.dump({'site': SITE, 'posts': posts, 'media': media, 'users': users_d, 'categories': cats_d, 'tags': tags_d}, open(os.path.join(OUT, 'live-posts.json'), 'w'), ensure_ascii=False, indent=1)
print(f'{len(posts)} posts, {len(media)} media, {n} files, {len(users_d)} authors, {len(cats_d)} categories, {len(tags_d)} tags')
