import csv, json, re, html, urllib.request, urllib.parse, concurrent.futures as cf
rows=[r for r in csv.DictReader(open(__import__('os').path.join(__import__('os').path.dirname(__file__), '../../../../docs/legacy-url-map.csv'))) if 'feed' not in r['path']]
class NoRedir(urllib.request.HTTPRedirectHandler):
    def redirect_request(self,*a,**k): return None
op=urllib.request.build_opener(NoRedir)
def norm(s): return re.sub(r'\s+',' ',html.unescape(s).replace('\xa0',' ')).strip()
def get(p):
    u='https://seohouse.agency'+'/'.join(urllib.parse.quote(x) for x in p.split('/'))
    try:
        r=op.open(urllib.request.Request(u,headers={'User-Agent':'Mozilla/5.0 SEOHouse-check'}),timeout=40); code=r.status; h=r.read().decode('utf-8','replace')
    except urllib.error.HTTPError as e: code=e.code; h=''
    head=h.split('</head>')[0]
    t=re.search(r'<title[^>]*>(.*?)</title>',head,re.S|re.I)
    d=None
    for m in re.finditer(r'<meta\s[^>]*>',head,re.I):
        a=dict((k.lower(),v1 or v2) for k,v1,v2 in re.findall(r'([a-zA-Z:-]+)\s*=\s*(?:"([^"]*)"|\'([^\']*)\')',m.group(0)))
        if a.get('name','').lower()=='description' and d is None: d=a.get('content','')
    rob=re.search(r'<meta name="robots" content="([^"]*)"',head); can=re.search(r'<link rel="canonical" href="([^"]*)"',head)
    return {'path':p,'code':code,'title':norm(t.group(1)) if t else '','description':norm(d or ''),'robots':rob.group(1) if rob else '','canonical':can.group(1) if can else ''}
with cf.ThreadPoolExecutor(6) as ex: out=list(ex.map(get,[r['path'] for r in rows]))
json.dump(out,open(__import__('os').path.join(__import__('os').path.dirname(__file__), 'live.json'),'w'),ensure_ascii=False,indent=1)
for o in out: print(o['code'],o['path'],'|',o['title'][:60],'|',o['description'][:50])
