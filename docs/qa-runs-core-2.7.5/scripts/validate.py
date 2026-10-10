# Local structured data checks (test site only). Usage: python3 validate.py <before.json> [query-suffix]
#   before.json: dump.py output taken before the change (meta comparison)
#   query-suffix: e.g. "?shs=rm-company" to run a scenario (meta comparison is skipped then)
import html as H, json, re, sys, urllib.parse, urllib.request

BASE = 'http://127.0.0.1:8090'
HOME = BASE + '/'
ORG = HOME + '#organization'
before = json.load(open(sys.argv[1]))
SUF = sys.argv[2] if len(sys.argv) > 2 else ''
q = lambda s: urllib.parse.quote(s, safe='/')
CASE = q('/results/قطاع-الصحة-والطب/')
POST = q('/blog/هل-يظهر-موقعك-داخل-إجابات-جوجل-بالذكاء/')
EXPECT = {  # path: (page type, main entity type or None, extra checks)
    '/': ('WebPage', None, {}),
    '/about/': ('AboutPage', 'Organization', {}),
    '/contact/': ('ContactPage', None, {}),
    '/services/': ('CollectionPage', 'ItemList', {}),
    '/sectors/': ('CollectionPage', 'ItemList', {}),
    '/results/': ('CollectionPage', 'ItemList', {}),
    '/blog/': ('CollectionPage', 'ItemList', {}),
    '/team/': ('CollectionPage', 'ItemList', {}),
    '/services/seo/': ('WebPage', 'Service', {'areas': ['السعودية', 'مصر', 'الإمارات']}),
    '/services/seo/ksa/': ('WebPage', 'Service', {'areas': ['السعودية']}),
    '/services/seo/egypt/': ('WebPage', 'Service', {'areas': ['مصر']}),
    '/services/seo/uae/': ('WebPage', 'Service', {'areas': ['الإمارات']}),
    '/services/seo/technical/': ('WebPage', 'Service', {'areas': []}),
    '/services/web-design/': ('WebPage', 'Service', {'areas': []}),
    '/services/web-design/wordpress/': ('WebPage', 'Service', {'areas': []}),
    '/services/stores/': ('WebPage', 'Service', {'areas': []}),
    '/services/stores/salla/': ('WebPage', 'Service', {'areas': []}),
    '/services/products/': ('WebPage', 'Service', {'areas': []}),
    '/sectors/health/': ('WebPage', 'Service', {'areas': [], 'audience': True}),
    '/sectors/legal/': ('WebPage', 'Service', {'areas': [], 'audience': True}),
    CASE: ('WebPage', None, {'article': 'Article'}),
    POST: ('WebPage', None, {'article': 'BlogPosting'}),
    '/team/yasser-youssef/': ('ProfilePage', 'Person', {}),
    '/pricing/': ('WebPage', None, {}),
    '/terms/': ('WebPage', None, {}),
    '/privacy-policy/': ('WebPage', None, {}),
    '/blog/category/technical-seo/': ('CollectionPage', None, {}),
}
errors, notes = [], []
def err(p, m): errors.append(f'{urllib.parse.unquote(p)}: {m}')
def types(n): return n['@type'] if isinstance(n.get('@type'), list) else [n.get('@type')]
def text_of(html):
    body = re.sub(r'<(script|style|noscript)\b.*?</\1>', ' ', html, flags=re.S | re.I)
    return re.sub(r'\s+', ' ', H.unescape(re.sub(r'<[^>]+>', ' ', body))).strip()
def norm(s): return re.sub(r'\s+', ' ', s or '').strip()

for path, (ptype, main, extra) in EXPECT.items():
    r = urllib.request.urlopen(BASE + path + SUF); html = r.read().decode()
    blocks = re.findall(r'<script[^>]*type="application/ld\+json"[^>]*>(.*?)</script>', html, re.S)
    if len(blocks) != 1: err(path, f'{len(blocks)} JSON-LD blocks')
    graph = []
    for b in blocks:
        try:
            d = json.loads(b)
        except Exception as e:
            err(path, f'invalid JSON: {e}'); continue
        graph += d.get('@graph', [d])
    raw = json.dumps(graph, ensure_ascii=False)
    # one node per entity
    ids = [n.get('@id') for n in graph if n.get('@id')]
    for i in set(ids):
        if ids.count(i) > 1: err(path, f'duplicate @id {i}')
    orgs = [n for n in graph if 'Organization' in types(n) or 'ProfessionalService' in types(n)]
    if len(orgs) != 1 or orgs[0].get('@id') != ORG or orgs[0].get('@type') != 'Organization':
        err(path, f'company node: {[(o.get("@id"), o.get("@type")) for o in orgs]}')
    if any('Person' in types(n) and n.get('@id', '') == HOME + '#person' for n in graph):
        err(path, 'site-level /#person node left')
    # references resolve
    known = set(ids)
    def defs(v):  # nodes defined at any depth (e.g. the logo inside the company node)
        if isinstance(v, dict):
            if v.get('@id') and len(v) > 1: known.add(v['@id'])
            for x in v.values(): defs(x)
        elif isinstance(v, list):
            for x in v: defs(x)
    defs(graph)
    def walk(v):
        if isinstance(v, dict):
            if set(v) == {'@id'} and v['@id'] not in known: err(path, f'unresolved reference {v["@id"]}')
            for x in v.values(): walk(x)
        elif isinstance(v, list):
            for x in v: walk(x)
    for n in graph:
        for k, v in n.items():
            if k != '@id': walk(v)
    # forbidden
    for bad in ('SearchAction', 'AggregateRating', '"Review"', 'aggregateRating', 'ProfessionalService'):
        if bad in raw: err(path, f'{bad} present')
    for n in graph:
        for k, v in n.items():
            if v in ('', [], {}, None): err(path, f'empty {k} on {types(n)}')
    # page node
    pages = [n for n in graph if n.get('@id', '').endswith('#webpage')]
    if len(pages) != 1: err(path, f'{len(pages)} page nodes'); continue
    page = pages[0]
    if page.get('@type') != ptype: err(path, f'page type {page.get("@type")} != {ptype}')
    byid = {n.get('@id'): n for n in graph}
    me = byid.get((page.get('mainEntity') or {}).get('@id'))
    if main and (not me or main not in types(me)): err(path, f'mainEntity {me and me.get("@type")} != {main}')
    if not main and page.get('mainEntity') and not path.startswith('/blog/category/'): err(path, 'unexpected mainEntity')
    vis = text_of(html)
    h1 = [norm(H.unescape(re.sub(r'<[^>]+>', '', x))) for x in re.findall(r'<h1[^>]*>(.*?)</h1>', html, re.S)]
    if main == 'Service':
        s = me
        if norm(s.get('name')) != (h1[0] if h1 else None): err(path, f'Service name "{s.get("name")}" != H1 {h1}')
        if not s.get('description') or norm(s['description']) not in vis: err(path, 'Service description not visible on the page')
        if s.get('provider') != {'@id': ORG}: err(path, 'provider')
        got = [a.get('name') for a in s.get('areaServed', [])]
        if got != extra['areas']: err(path, f'areaServed {got} != {extra["areas"]}')
        if extra.get('audience'):
            a = s.get('audience') or {}
            if a.get('@type') != 'BusinessAudience' or not a.get('audienceType') or a['audienceType'] not in vis:
                err(path, f'audience {a}')
        elif s.get('audience'): err(path, 'unexpected audience')
        if not s.get('serviceType'): notes.append(f'{urllib.parse.unquote(path)}: no serviceType (field empty)')
    if main == 'ItemList':
        items = me.get('itemListElement', [])
        hrefs = set(re.findall(r'href="([^"]+)"', html))
        hrefs |= {h.lower() for h in hrefs}
        for it in items:
            if it['url'] not in hrefs and it['url'].lower() not in hrefs: err(path, f'list item not linked on page: {it["url"]}')
            if norm(it['name']) not in vis: err(path, f'list item name not visible: {it["name"]}')
        if not items: err(path, 'empty list')
        notes.append(f'{urllib.parse.unquote(path)}: ItemList {len(items)} items')
    if main == 'Organization' and me.get('@id') != ORG: err(path, 'AboutPage mainEntity')
    if main == 'Person':
        if norm(me.get('name')) != (h1[0] if h1 else None): err(path, 'Person name != H1')
        if me.get('worksFor') != {'@id': ORG}: err(path, 'worksFor')
    if extra.get('article'):
        arts = [n for n in graph if extra['article'] in types(n)]
        if len(arts) != 1: err(path, f'{len(arts)} {extra["article"]} nodes'); continue
        a = arts[0]
        if norm(a.get('headline')) != (h1[0] if h1 else None): err(path, f'headline "{a.get("headline")}" != H1')
        if a.get('publisher') != {'@id': ORG}: err(path, 'publisher')
        if a.get('mainEntityOfPage') != {'@id': page['@id']}: err(path, 'mainEntityOfPage')
        notes.append(f'{urllib.parse.unquote(path)}: author={a.get("author")} image={"yes" if a.get("image") else "no"}')
    # FAQ only from the visible section
    for f in [n for n in graph if 'FAQPage' in types(n)]:
        for qn in f['mainEntity']:
            if norm(qn['name']) not in vis: err(path, f'FAQ question not visible: {qn["name"][:40]}')
    if not [n for n in graph if 'FAQPage' in types(n)]: notes.append(f'{urllib.parse.unquote(path)}: no FAQPage')
    # meta unchanged
    if not SUF and path in before:
        m0 = before[path]['meta']
        m1 = {k: (re.search(p_, html) or [None, None])[1] for k, p_ in {'title': r'<title>(.*?)</title>', 'desc': r'<meta name="description" content="([^"]*)"', 'canonical': r'<link rel="canonical" href="([^"]*)"'}.items()}
        m1['hreflang'] = [list(x) for x in re.findall(r'<link rel="alternate" hreflang="([^"]+)" href="([^"]+)"', html)]
        m1['h1'] = [re.sub(r'<[^>]+>', '', x).strip() for x in re.findall(r'<h1[^>]*>(.*?)</h1>', html, re.S)]
        m1['robots'] = (re.search(r'<meta name="robots" content="([^"]*)"', html) or [None, None])[1]
        for k in m1:
            if json.dumps(m0.get(k)) != json.dumps(m1[k]): err(path, f'meta {k} changed: {m0.get(k)} → {m1[k]}')
print('\n'.join('note  ' + n for n in notes))
print('\n'.join('ERROR ' + e for e in errors) or 'no errors')
print(f'{len(EXPECT)} pages, {len(errors)} errors')
sys.exit(1 if errors else 0)
