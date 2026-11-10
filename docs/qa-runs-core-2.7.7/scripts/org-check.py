# The company node on every sample page, and every other node unchanged. Usage: python3 org-check.py <before-dir> <after-dir>
import json, re, sys
a, b = sys.argv[1], sys.argv[2]
idx = json.load(open(f'{a}/index.json'))
def graph(d, f):
    h = open(f'{d}/{f}', encoding='utf-8').read()
    m = re.findall(r'<script type="application/ld\+json"[^>]*>(.*?)</script>', h, re.S)
    return [json.loads(x.replace('<\\/', '</')) for x in m]
errs, orgs = [], {}
for p, v in idx.items():
    gb, ga = graph(a, v['file']), graph(b, v['file'])
    if not ga:
        if v['status'] == 200: errs.append(f'{p}: no JSON-LD')
        continue
    if len(ga) != 1: errs.append(f'{p}: {len(ga)} blocks')
    nb = {n.get('@id'): n for n in gb[0]['@graph']} if gb else {}
    na = {n.get('@id'): n for n in ga[0]['@graph']}
    org = [n for n in na.values() if 'Organization' in str(n.get('@type'))]
    if len(org) != 1 or org[0]['@id'] != 'http://127.0.0.1:8090/#organization' or org[0]['@type'] != 'Organization': errs.append(f'{p}: company nodes {[(o["@id"], o["@type"]) for o in org]}')
    o = org[0]
    orgs[p] = json.dumps({k: o.get(k) for k in ('address', 'image', 'logo')}, ensure_ascii=False, sort_keys=True)
    ad = o.get('address') or {}
    if not (isinstance(ad, dict) and ad.get('@type') == 'PostalAddress' and ad.get('addressCountry') == 'EG' and 'name' not in ad and 'postalCode' not in ad and ad.get('streetAddress') and ad.get('addressLocality') and ad.get('addressRegion')):
        errs.append(f'{p}: address {ad}')
    img = o.get('image') or {}
    if not (img.get('@id') == (o.get('logo') or {}).get('@id') or img.get('url')): errs.append(f'{p}: image {img}')
    # every other node identical to before (services, authors, pages, lists…)
    for i, n in na.items():
        if n is o: continue
        if nb.get(i) != n: errs.append(f'{p}: node changed {i}')
    for i in nb:
        if i not in na: errs.append(f'{p}: node removed {i}')
    if nb:
        ob = [n for n in nb.values() if 'Organization' in str(n.get('@type'))][0]
        changed = sorted(k for k in set(ob) | set(o) if ob.get(k) != o.get(k))
        if changed != ['address', 'image']: errs.append(f'{p}: company properties changed: {changed}')
print('pages with schema:', len(orgs), '| distinct company address/image/logo across pages:', len(set(orgs.values())))
print('\n'.join(errs) or 'no errors')
