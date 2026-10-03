#!/usr/bin/env python3
"""
Builds dist/seohouse-core.zip: the SEO House Core plugin with the content pack bundled at
seohouse-core/content-pack/.

Robustness rules (a server's unzip or file copy must never leave an active plugin with a
half-copied pack):
  * every name in the archive is ASCII — asset files with other characters get an ASCII name
    and their references in the bundled pack's JSON are rewritten (images and texts unchanged);
  * order: content pack first (manifest, data, pages, then images), plugin code next, and the main
    plugin file seohouse-core.php last;
  * content-pack/pack-files.json lists every pack file with its size and SHA-1, so the setup
    screen can report exactly what is missing;
  * the finished zip is re-opened and verified.

Usage: package-core.py <repo> <out.zip>
"""
import hashlib, json, os, re, shutil, sys, tempfile, zipfile

REPO, OUT = sys.argv[1], sys.argv[2]
PLUGIN = os.path.join(REPO, 'wordpress/plugins/seohouse-core')
PACK = os.path.join(REPO, 'content-pack')
stage = tempfile.mkdtemp()
root = os.path.join(stage, 'seohouse-core')
shutil.copytree(PLUGIN, root, symlinks=False)
dst_pack = os.path.join(root, 'content-pack')
shutil.copytree(PACK, dst_pack, symlinks=False)
for junk in ('build-log.txt',):
    p = os.path.join(dst_pack, junk)
    if os.path.exists(p):
        os.remove(p)

# 1. ASCII-only asset names (references rewritten in the bundled copy only)
# Names already used by installed sites: media is keyed by its pack path (asset:<path>), so these
# must stay as Core 2.2.5 shipped them or a re-import would add a second copy of the image.
FIXED = {
    'ChatGPT-Image-3-يونيو-2026--05_07_09-م-800x450.webp': 'chatgpt-image-2026-06-03-800x450.webp',
}
renames = {}
assets = os.path.join(dst_pack, 'assets')
for d, _, files in os.walk(assets):
    for f in files:
        if f.isascii():
            continue
        base, ext = os.path.splitext(f)
        ascii_base = re.sub(r'-{2,}', '-', re.sub(r'[^A-Za-z0-9._-]+', '-', base)).strip('-')
        new = FIXED.get(f) or f"{ascii_base}-{hashlib.sha1(f.encode()).hexdigest()[:8]}{ext}"
        os.rename(os.path.join(d, f), os.path.join(d, new))
        rel_old = os.path.relpath(os.path.join(d, f), assets).replace(os.sep, '/')
        renames[rel_old] = os.path.relpath(os.path.join(d, new), assets).replace(os.sep, '/')

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

if renames:
    for d, _, files in os.walk(dst_pack):
        for f in files:
            if f.endswith('.json'):
                p = os.path.join(d, f)
                data = json.load(open(p, encoding='utf-8'))
                new = fix(data)
                if new != data:
                    json.dump(new, open(p, 'w', encoding='utf-8'), ensure_ascii=False, indent=2)

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
for d, _, files in os.walk(dst_pack):
    for f in files:
        if f.endswith('.json'):
            refs(json.load(open(os.path.join(d, f), encoding='utf-8')), all_refs)
for t in json.load(open(os.path.join(dst_pack, 'data/team.json'), encoding='utf-8')):
    if t.get('photo'):
        all_refs.append(t['photo'])
missing = sorted({r for r in all_refs if not os.path.exists(os.path.join(assets, r))})
if missing:
    sys.exit('unresolved asset references: ' + ', '.join(missing))

# 2. integrity list of the pack
pack_files = []
for d, _, files in os.walk(dst_pack):
    for f in files:
        p = os.path.join(d, f)
        rel = os.path.relpath(p, dst_pack).replace(os.sep, '/')
        if rel == 'pack-files.json':
            continue
        pack_files.append({'path': rel, 'size': os.path.getsize(p), 'sha1': hashlib.sha1(open(p, 'rb').read()).hexdigest()})
pack_files.sort(key=lambda x: x['path'])
manifest = json.load(open(os.path.join(dst_pack, 'manifest.json'), encoding='utf-8'))
json.dump({'version': manifest.get('version'), 'count': len(pack_files), 'files': pack_files},
          open(os.path.join(dst_pack, 'pack-files.json'), 'w'), indent=1)

# 3. ordered entry list
def walk(base):
    out = []
    for d, dirs, files in os.walk(base):
        dirs.sort()
        for f in sorted(files):
            out.append(os.path.relpath(os.path.join(d, f), stage).replace(os.sep, '/'))
    return out
pfx = 'seohouse-core/content-pack/'
pack_entries = walk(dst_pack)
rank = lambda e: (0 if e == pfx + 'manifest.json' else 1 if e == pfx + 'pack-files.json' else
                  2 if e.startswith(pfx + 'data/') else 3 if e.startswith(pfx + 'pages/') else 4, e)
pack_entries.sort(key=rank)
code_entries = [e for e in walk(root) if not e.startswith(pfx) and e != 'seohouse-core/seohouse-core.php']
entries = pack_entries + code_entries + ['seohouse-core/seohouse-core.php']

bad = [e for e in entries if not e.isascii()]
if bad:
    sys.exit('non-ASCII names: ' + ', '.join(bad))

if os.path.exists(OUT):
    os.remove(OUT)
with zipfile.ZipFile(OUT, 'w', zipfile.ZIP_DEFLATED, compresslevel=9) as z:
    dirs_done = set()
    for e in entries:
        parts = e.split('/')[:-1]
        for i in range(1, len(parts) + 1):
            dname = '/'.join(parts[:i]) + '/'
            if dname not in dirs_done:
                z.writestr(zipfile.ZipInfo(dname), '')
                dirs_done.add(dname)
        z.write(os.path.join(stage, e), e)

# 4. verify
with zipfile.ZipFile(OUT) as z:
    assert z.testzip() is None
    names = z.namelist()
    files = [n for n in names if not n.endswith('/')]
    assert files[0] == pfx + 'manifest.json' and files[-1] == 'seohouse-core/seohouse-core.php'
    assert all(n.isascii() for n in names)
    listed = json.loads(z.read(pfx + 'pack-files.json'))
    for f in listed['files']:
        data = z.read(pfx + f['path'])
        assert len(data) == f['size'] and hashlib.sha1(data).hexdigest() == f['sha1'], f['path']
shutil.rmtree(stage)
print(f"seohouse-core.zip: {len(files)} files, pack {listed['count']} files (version {listed['version']}), "
      f"renamed to ASCII: {renames or 'none'}")
