# Compare HTML before/after with JSON-LD removed. Usage: python3 compare.py <before-dir> <after-dir>
import json, re, sys, difflib
a, b = sys.argv[1], sys.argv[2]
LD = re.compile(r'<script[^>]*application/ld\+json[^>]*>.*?</script>\s*', re.S)
SEO = re.compile(r'<title>.*?</title>|<meta\s[^>]*(?:name|property)="[^"]+"[^>]*>|<link\s[^>]*rel="(?:canonical|alternate|prev|next|shortlink)"[^>]*>', re.S)
idx = json.load(open(f'{a}/index.json'))
bad = 0
for path, v in idx.items():
    h0 = open(f'{a}/{v["file"]}', encoding='utf-8', errors='replace').read()
    h1 = open(f'{b}/{v["file"]}', encoding='utf-8', errors='replace').read()
    # volatile values unrelated to SEO: asset cache-buster (file time) and the forms' anti-spam timestamp
    vol = lambda h: re.sub(r'name="ts" value="\d+"', 'name="ts" value="T"', re.sub(r'(\?ver=[\d.]+?)\.\d{9,}', r'\1.MTIME', h))
    s0, s1 = vol(LD.sub('', h0)), vol(LD.sub('', h1))
    tags0, tags1 = SEO.findall(h0), SEO.findall(h1)
    ld0, ld1 = len(LD.findall(h0)), len(LD.findall(h1))
    cls1 = re.findall(r'<script[^>]*application/ld\+json[^>]*class="([^"]+)"', h1)
    same_tags = tags0 == tags1
    same_rest = s0 == s1
    if not same_rest:
        diff = [l for l in difflib.unified_diff(s0.splitlines(), s1.splitlines(), lineterm='', n=0) if l[:1] in '+-' and l[:3] not in ('+++', '---')]
    else:
        diff = []
    ok = same_tags and same_rest
    bad += not ok
    print(f"{'OK ' if ok else 'DIFF'} {path[:60]:60} SEO tags {len(tags1):3} {'same' if same_tags else 'CHANGED'} | rest of HTML {'identical' if same_rest else str(len(diff)) + ' lines differ'} | JSON-LD {ld0}→{ld1} {cls1}")
    for l in diff[:6]: print('      ', l[:200])
print(f'{len(idx)} pages, {bad} with differences outside JSON-LD')
