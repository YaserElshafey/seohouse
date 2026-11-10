# Verify Rank Math sitemaps (test site). Usage: python3 verify.py
import re, urllib.request, urllib.error, xml.etree.ElementTree as ET
B = 'http://127.0.0.1:8090'
NS = {'s': 'http://www.sitemaps.org/schemas/sitemap/0.9'}
def get(u):
    class NoRedirect(urllib.request.HTTPRedirectHandler):
        def redirect_request(self, *a, **k): return None
    o = urllib.request.build_opener(NoRedirect)
    try:
        r = o.open(u); return r.status, r.headers.get('content-type', ''), r.read().decode()
    except urllib.error.HTTPError as e:
        return e.code, e.headers.get('content-type', ''), e.read().decode(errors='replace')
ok = True
st, ct, body = get(B + '/sitemap_index.xml')
idx = [l.text for l in ET.fromstring(body).findall('s:sitemap/s:loc', NS)]
print('sitemap_index.xml', st, ct, '|', [u.replace(B + '/', '') for u in idx])
for t in ('team_member', 'case_study', 'page', 'post'):
    for u in (f'{B}/{t}-sitemap.xml', f'{B}/?sitemap={t}'):
        st, ct, body = get(u)
        try:
            locs = [l.text for l in ET.fromstring(body).findall('s:url/s:loc', NS)]; valid = True
        except Exception as e:
            locs, valid = [], False
        print(f'{u.replace(B, ""):26} {st} {ct.split(";")[0]:9} valid_xml={valid} urls={len(locs)} in_index={any(t + "-sitemap" in x for x in idx)}')
        ok &= st == 200 and valid
        if u.endswith('-sitemap.xml') and t in ('team_member', 'case_study'):
            bad = []
            for loc in locs:
                s2, _, h = get(loc)
                canon = (re.search(r'<link rel="canonical" href="([^"]+)"', h) or [None, None])[1]
                robots = (re.search(r'<meta name="robots" content="([^"]+)"', h) or [None, None])[1] or ''
                if s2 != 200 or canon != loc or 'noindex' in robots: bad.append((loc, s2, canon, robots))
            print(f'   every listed URL: 200, self-canonical, indexable → {"yes" if not bad else bad}')
            ok &= not bad
print('RESULT', 'PASS' if ok else 'FAIL')
