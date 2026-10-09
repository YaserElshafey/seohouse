# Verify the SEO round on fetched HTML. Usage: python3 seo-verify.py <base>
import re, sys, html, urllib.request
base = sys.argv[1].rstrip('/')
T = lambda x: ' '.join(html.unescape(re.sub(r'<[^>]+>', ' ', x)).split())
def get(p):
    r = urllib.request.urlopen(base + p); return r.status, r.read().decode()
blocks = {}
group = ['/services/seo/', '/services/seo/ksa/', '/services/seo/egypt/', '/services/seo/uae/']
first_post = re.search(r'href="(' + re.escape(base) + r'/blog/[^"#?]+/)"', get('/blog/')[1]).group(1).replace(base, '')
for p in group + ['/', '/services/seo/technical/', '/about/', first_post]:
    st, s = get(p)
    head = s.split('</head>')[0]
    hl = re.findall(r'<link rel="alternate" hreflang="[^"]+" href="[^"]+" />', head)
    allhl = len(re.findall(r'hreflang=', s))
    can = re.search(r'<link rel="canonical" href="([^"]+)"', head)
    robots = re.search(r"<meta name=.robots. content=.([^'\"]+)", head)
    h1 = [T(x) for x in re.findall(r'<h1[^>]*>(.*?)</h1>', s, re.S)]
    title = T(re.search(r'<title>(.*?)</title>', s, re.S).group(1))
    desc = re.search(r'<meta name="description" content="([^"]*)"', head)
    print(f'===== {p} [{st}]')
    print(f'  title: {title}\n  desc : {html.unescape(desc.group(1)) if desc else "-"}\n  canonical: {can.group(1) if can else "-"} | robots: {robots.group(1) if robots else "-"}')
    print(f'  H1 x{len(h1)}: {h1}')
    print(f'  hreflang lines: {len(hl)} (all hreflang attrs in page: {allhl})')
    if hl:
        blocks[p] = hl
        hrefs = dict(re.findall(r'hreflang="([^"]+)" href="([^"]+)"', '\n'.join(hl)))
        print('   ', '\n    '.join(hl))
        print(f'  self-reference matches canonical: {can and can.group(1) in hrefs.values()}')
    if p in group[1:]:
        om = re.search(r'data-screen-label="Other markets".*?</section>', s, re.S)
        faq_pos = s.find('data-screen-label="FAQ"') if 'data-screen-label="FAQ"' in s else s.find('أسئلة')
        print(f'  other markets block: {[(T(t), u.replace(base, "")) for u, t in re.findall(r"<a data-hcard href=\"([^\"]+)\".*?<span style=\"font-family[^>]*>(.*?)</span>", om.group(0), re.S)] if om else None} | before FAQ: {bool(om) and om.start() < s.rfind("<h2", 0, s.find("أسئلة", om.start() if om else 0) + 1) + 10}')
    if p in ('/', '/services/seo/'):
        sec = re.search(r'data-screen-label="Markets".*?</section>', s, re.S).group(0)
        print('  market card links:', [ (u.replace(base, ''), [t for t in [T(x) for x in re.findall(r'<span[^>]*>([^<]*)</span>', a)] if 'شركة سيو' in t]) for u, a in re.findall(r'<a data-hcard href="([^"]+)"(.*?)</a>', sec, re.S)])
    foot = re.search(r'<footer.*?</footer>', s, re.S).group(0)
    m = re.search(r'<p[^>]*>(.{0,40}<a href="([^"]+)"[^>]*>شركة سيو</a>.{0,60})', foot, re.S)
    print('  footer about link:', (m.group(2).replace(base, ''), T(m.group(1))[:80]) if m else None)
    print('  footer market links:', [(T(t), u.replace(base, '')) for u, t in re.findall(r'<a href="([^"]+/services/seo/(?:ksa|egypt|uae)/)"[^>]*>(.*?)</a>', foot, re.S)])
    if p == first_post:
        m = re.search(r'<p data-art-seo-line>(.*?)</p>', s, re.S)
        print('  article line:', T(m.group(1)) if m else None, '| link:', re.search(r'href="([^"]+)"', m.group(1)).group(1).replace(base, '') if m else None, '| inside article body:', bool(m) and s.rfind('<article data-art-body', 0, m.start()) > s.rfind('</article>', 0, m.start()))
print('\nidentical block on the 4 pages:', len(set('\n'.join(v) for v in blocks.values())) == 1 and sorted(blocks) == sorted(group))
