# Save the HTML of the sample pages for a scenario. Usage: python3 capture.py <outdir> <scenario|"">
import os, sys, urllib.parse, urllib.request, json
out, scen = sys.argv[1], sys.argv[2]
q = lambda s: urllib.parse.quote(s, safe='/')
PATHS = ['/', '/about/', '/contact/', '/services/', '/services/seo/', '/services/seo/ksa/', '/services/seo/egypt/', '/services/seo/uae/',
  '/services/seo/technical/', '/services/web-design/', '/services/web-design/wordpress/', '/services/stores/', '/services/stores/salla/',
  '/services/products/', '/sectors/', '/sectors/health/', '/sectors/legal/', '/results/', q('/results/قطاع-الصحة-والطب/'), '/blog/',
  q('/blog/هل-يظهر-موقعك-داخل-إجابات-جوجل-بالذكاء/'), '/team/', '/team/yasser-youssef/', '/team/eman-farid/', '/pricing/', '/terms/',
  '/privacy-policy/', '/blog/category/technical-seo/', '/?s=seo', '/no-such-page/']
os.makedirs(out, exist_ok=True)
idx = {}
for i, p in enumerate(PATHS):
    url = 'http://127.0.0.1:8090' + p
    req = urllib.request.Request(url, headers={'X-SHS': scen} if scen else {})
    try:
        r = urllib.request.urlopen(req); body = r.read(); st = r.status
    except urllib.error.HTTPError as e:
        body = e.read(); st = e.code
    open(f'{out}/{i:02d}.html', 'wb').write(body); idx[p] = {'file': f'{i:02d}.html', 'status': st}
json.dump(idx, open(f'{out}/index.json', 'w'), ensure_ascii=False, indent=1)
print(out, len(idx), 'pages')
