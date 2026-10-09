import json, re, html, urllib.request, urllib.parse, sys
live=json.load(open(__import__('os').path.join(__import__('os').path.dirname(__file__), 'live.json'))); base=sys.argv[1]; label=sys.argv[2]
def norm(s): return re.sub(r'\s+',' ',html.unescape(s).replace('\xa0',' ')).strip()
class NoRedir(urllib.request.HTTPRedirectHandler):
    def redirect_request(self,*a,**k): return None
op=urllib.request.build_opener(NoRedir)
def get(u):
    try: r=op.open(urllib.request.Request(u,headers={'User-Agent':'Mozilla/5.0 SEOHouse-check'}),timeout=40); return r.status, r.read().decode('utf-8','replace')
    except urllib.error.HTTPError as e: return e.code, ''
    except Exception as e: return 0, ''
ok=diff=skip=0
print(f'Independent check (not using the plugin): <title>/meta description on seohouse.agency (read 3 Oct 2026) vs {label}')
for v in live:
    if v['code']!=200: print('SKIP', v['path'], 'live', v['code']); skip+=1; continue
    code,h=get(base+'/'.join(urllib.parse.quote(x) for x in v['path'].split('/')))
    if code!=200: print('NOT PUBLISHED HERE', v['path'], code); skip+=1; continue
    head=h.split('</head>')[0]
    t=re.search(r'<title[^>]*>(.*?)</title>',head,re.S); t=norm(t.group(1)) if t else ''
    d=None
    for m in re.finditer(r'<meta\s[^>]*>',head,re.I):
        a=dict((k.lower(),v1 or v2) for k,v1,v2 in re.findall(r'([a-zA-Z:-]+)\s*=\s*(?:"([^"]*)"|\'([^\']*)\')',m.group(0)))
        if a.get('name','').lower()=='description' and d is None: d=a.get('content','')
    d=norm(d or '')
    rob=re.search(r'<meta name=["\']robots["\'] content=["\']([^"\']*)',head); rob=rob.group(1) if rob else ''
    tm=t==v['title']; dm=d==v['description']
    if tm and dm: ok+=1; print('MATCH', v['path'], '| robots here:', rob)
    else:
        diff+=1; print('DIFF ', v['path'], '| title', 'ok' if tm else f'live «{v["title"]}» / here «{t}»', '| desc', 'ok' if dm else f'live «{v["description"][:70]}» / here «{d[:70]}»')
print(f'match {ok}, differ {diff}, not compared {skip}')
