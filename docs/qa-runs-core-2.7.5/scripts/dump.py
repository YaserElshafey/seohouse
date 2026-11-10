# Dump the JSON-LD of local pages. Usage: python3 dump.py <out.json> [paths...]
import json, re, sys, urllib.request
BASE = 'http://127.0.0.1:8090'
PATHS = sys.argv[2:] or ['/', '/about/', '/contact/', '/services/', '/services/seo/', '/services/seo/ksa/', '/services/seo/egypt/',
  '/services/seo/uae/', '/services/seo/technical/', '/services/web-design/', '/services/web-design/wordpress/', '/services/stores/',
  '/services/stores/salla/', '/services/products/', '/sectors/', '/sectors/health/', '/sectors/legal/', '/results/',
  '/results/%D9%82%D8%B7%D8%A7%D8%B9-%D8%A7%D9%84%D8%B5%D8%AD%D8%A9-%D9%88%D8%A7%D9%84%D8%B7%D8%A8/', '/blog/',
  '/blog/%D9%87%D9%84-%D9%8A%D8%B8%D9%87%D8%B1-%D9%85%D9%88%D9%82%D8%B9%D9%83-%D8%AF%D8%A7%D8%AE%D9%84-%D8%A5%D8%AC%D8%A7%D8%A8%D8%A7%D8%AA-%D8%AC%D9%88%D8%AC%D9%84-%D8%A8%D8%A7%D9%84%D8%B0%D9%83%D8%A7%D8%A1/',
  '/team/', '/team/yasser-youssef/', '/pricing/', '/terms/', '/privacy-policy/', '/category/seo/']
out = {}
for p in PATHS:
    try:
        r = urllib.request.urlopen(BASE + p); html = r.read().decode(); st = r.status
    except urllib.error.HTTPError as e:
        html = e.read().decode(); st = e.code
    blocks = re.findall(r'<script[^>]*type="application/ld\+json"[^>]*>(.*?)</script>', html, re.S)
    parsed = []
    for b in blocks:
        try: parsed.append(json.loads(b))
        except Exception as e: parsed.append({'_invalid': str(e), '_raw': b[:300]})
    h1 = re.findall(r'<h1[^>]*>(.*?)</h1>', html, re.S)
    meta = {k: (re.search(pat, html) or [None, None])[1] for k, pat in {
        'title': r'<title>(.*?)</title>', 'desc': r'<meta name="description" content="([^"]*)"',
        'canonical': r'<link rel="canonical" href="([^"]*)"'}.items()}
    meta['hreflang'] = re.findall(r'<link rel="alternate" hreflang="([^"]+)" href="([^"]+)"', html)
    meta['h1'] = [re.sub(r'<[^>]+>', '', x).strip() for x in h1]
    meta['robots'] = (re.search(r'<meta name="robots" content="([^"]*)"', html) or [None, None])[1]
    out[p] = {'status': st, 'blocks': parsed, 'meta': meta}
json.dump(out, open(sys.argv[1], 'w'), ensure_ascii=False, indent=1)
for p, d in out.items():
    print(f"== {p} [{d['status']}] blocks={len(d['blocks'])}")
    for b in d['blocks']:
        for n in (b.get('@graph') or [b]):
            t = n.get('@type'); i = n.get('@id', '')
            keys = ','.join(k for k in n if not k.startswith('@'))
            print(f"   {str(t):28} {i.replace('http://127.0.0.1:8090',''):60} {keys}")
