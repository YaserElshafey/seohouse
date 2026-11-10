#!/usr/bin/env python3
"""
Shrinks the content pack: PNG/JPEG assets become WebP (max 2000 px on the long side) when that
is smaller, and every reference in the pack's JSON files is rewritten to the new file name.
Run after build-content.js (tools/dev-reset.sh and tools/package.sh do it).

Usage: optimize-pack.py <content-pack-dir>
"""
import json, os, re, sys
from PIL import Image

PACK = sys.argv[1]
ASSETS = os.path.join(PACK, 'assets')
renames = {}
before = after = 0
for root, _, files in os.walk(ASSETS):
    for f in files:
        if not re.search(r'\.(png|jpe?g)$', f, re.I):
            continue
        src = os.path.join(root, f)
        rel = os.path.relpath(src, ASSETS).replace(os.sep, '/')
        size = os.path.getsize(src)
        before += size
        im = Image.open(src)
        im.load()
        if max(im.size) > 2000:
            im.thumbnail((2000, 2000), Image.LANCZOS)
        if im.mode not in ('RGB', 'RGBA'):
            im = im.convert('RGBA' if 'transparency' in im.info or im.mode in ('LA', 'P') else 'RGB')
        dst_name = re.sub(r'(\.(png|jpe?g))+$', '', f, flags=re.I) + '.webp'
        dst = os.path.join(root, dst_name)
        im.save(dst, 'WEBP', quality=86, method=6)
        if os.path.getsize(dst) < size:
            os.remove(src)
            renames[rel] = os.path.relpath(dst, ASSETS).replace(os.sep, '/')
            after += os.path.getsize(dst)
        else:
            os.remove(dst)
            after += size

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

changed = 0
for root, _, files in os.walk(PACK):
    for f in files:
        if not f.endswith('.json'):
            continue
        p = os.path.join(root, f)
        data = json.load(open(p, encoding='utf-8'))
        new = fix(data)
        if new != data:
            json.dump(new, open(p, 'w', encoding='utf-8'), ensure_ascii=False, indent=1 if f == 'manifest.json' else 2)
            changed += 1

# every reference must point to an existing file
missing = []
def check(v):
    if isinstance(v, dict):
        if '__asset' in v and not os.path.exists(os.path.join(ASSETS, v['__asset'])):
            missing.append(v['__asset'])
        for x in v.values():
            check(x)
    elif isinstance(v, list):
        for x in v:
            check(x)
for root, _, files in os.walk(PACK):
    for f in files:
        if f.endswith('.json'):
            check(json.load(open(os.path.join(root, f), encoding='utf-8')))
print(f'optimize-pack: {len(renames)} images → WebP, {before/1048576:.1f} MB → {after/1048576:.1f} MB, {changed} JSON files updated, missing refs: {len(missing)}')
if missing:
    print('\n'.join(missing))
    sys.exit(1)
