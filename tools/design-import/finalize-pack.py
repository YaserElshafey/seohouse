#!/usr/bin/env python3
"""
Makes the content pack shippable, in place, inside the plugin folder
(wordpress/plugins/seohouse-core/content-pack) — run after build-content.js and optimize-pack.py.

  * every file name is ASCII (a server's unzip must never drop a file): other names get an ASCII
    name and every reference in the pack's JSON is rewritten (images and texts unchanged);
  * pack-files.json lists every pack file with its size and SHA-1, so the setup screen and the
    package check can tell exactly what is present.

The plugin folder is then complete by itself: a zip of it, a copy from GitHub or a deployment
from the repository all carry the pack.

Usage: finalize-pack.py <content-pack-dir> [--check]   (--check: fail if anything would change)
"""
import hashlib, json, os, re, sys

PACK = sys.argv[1]
CHECK = '--check' in sys.argv
ASSETS = os.path.join(PACK, 'assets')
# names already used by installed sites: media is keyed by its pack path (asset:<path>), so these
# keep the name Core 2.2.5 shipped, or a re-import would add a second copy of the image
FIXED = {'ChatGPT-Image-3-يونيو-2026--05_07_09-م-800x450.webp': 'chatgpt-image-2026-06-03-800x450.webp'}
problems = []

renames = {}
for d, _, files in os.walk(ASSETS):
    for f in files:
        if f.isascii():
            continue
        base, ext = os.path.splitext(f)
        ascii_base = re.sub(r'-{2,}', '-', re.sub(r'[^A-Za-z0-9._-]+', '-', base)).strip('-')
        new = FIXED.get(f) or f"{ascii_base}-{hashlib.sha1(f.encode()).hexdigest()[:8]}{ext}"
        old_rel = os.path.relpath(os.path.join(d, f), ASSETS).replace(os.sep, '/')
        renames[old_rel] = os.path.relpath(os.path.join(d, new), ASSETS).replace(os.sep, '/')
        problems.append(f'non-ASCII name {old_rel}')
        if not CHECK:
            dst = os.path.join(d, new)
            if os.path.exists(dst):
                os.remove(dst)
            os.rename(os.path.join(d, f), dst)

def fix(v):
    if isinstance(v, str):
        if v in renames:
            return renames[v]
        if v.startswith('assets/') and v[7:] in renames:
            return 'assets/' + renames[v[7:]]
        return v
    if isinstance(v, list):
        return [fix(x) for x in v]
    if isinstance(v, dict):
        return {k: fix(x) for k, x in v.items()}
    return v

if renames and not CHECK:
    for d, _, files in os.walk(PACK):
        for f in files:
            if f.endswith('.json') and f != 'pack-files.json':
                p = os.path.join(d, f)
                data = json.load(open(p, encoding='utf-8'))
                new = fix(data)
                if new != data:
                    json.dump(new, open(p, 'w', encoding='utf-8'), ensure_ascii=False, indent=1 if f == 'manifest.json' else 2)

# every asset reference must resolve
def refs(v, out):
    if isinstance(v, dict):
        if isinstance(v.get('__asset'), str):
            out.append(v['__asset'])
        for x in v.values():
            refs(x, out)
    elif isinstance(v, list):
        for x in v:
            refs(x, out)
all_refs = []
for d, _, files in os.walk(PACK):
    for f in files:
        if f.endswith('.json') and f != 'pack-files.json':
            refs(json.load(open(os.path.join(d, f), encoding='utf-8')), all_refs)
for t in json.load(open(os.path.join(PACK, 'data/team.json'), encoding='utf-8')):
    if t.get('photo'):
        all_refs.append(t['photo'])
missing = sorted({r for r in all_refs if not os.path.exists(os.path.join(ASSETS, r))})
if missing:
    sys.exit('unresolved asset references: ' + ', '.join(missing))

# integrity list
listing = []
for d, _, files in os.walk(PACK):
    for f in files:
        p = os.path.join(d, f)
        rel = os.path.relpath(p, PACK).replace(os.sep, '/')
        if rel == 'pack-files.json':
            continue
        listing.append({'path': rel, 'size': os.path.getsize(p), 'sha1': hashlib.sha1(open(p, 'rb').read()).hexdigest()})
listing.sort(key=lambda x: x['path'])
manifest = json.load(open(os.path.join(PACK, 'manifest.json'), encoding='utf-8'))
want = json.dumps({'version': manifest.get('version'), 'count': len(listing), 'files': listing}, indent=1) + '\n'
pf = os.path.join(PACK, 'pack-files.json')
have = open(pf).read() if os.path.exists(pf) else ''
if have != want:
    problems.append('pack-files.json out of date')
    if not CHECK:
        open(pf, 'w').write(want)
if CHECK and problems:
    sys.exit('content pack not finalized: ' + '; '.join(problems) + ' — run tools/design-import/finalize-pack.py')
print(f"content pack {manifest.get('version')}: {len(listing)} files listed, renamed to ASCII: {renames or 'none'}")
