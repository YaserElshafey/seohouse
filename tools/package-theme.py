#!/usr/bin/env python3
"""
Builds seohouse-theme.zip from the files listed in wordpress/themes/seohouse/theme-files.json
(tools/theme-manifest.py), and checks it: one top folder "seohouse", every listed file present
with the same sha1, nothing unlisted, theme-files.json inside, functions.php and style.css last
(an interrupted extraction never leaves a theme whose bootstrap is newer than its files).

Usage: package-theme.py <repo> <out.zip>
"""
import hashlib, json, os, subprocess, sys, zipfile
REPO, OUT = sys.argv[1], sys.argv[2]
THEME = os.path.join(REPO, 'wordpress/themes/seohouse')
subprocess.run([sys.executable, os.path.join(REPO, 'tools/theme-manifest.py'), '--check'], check=True)
man = json.load(open(os.path.join(THEME, 'theme-files.json'), encoding='utf-8'))
paths = [f['path'] for f in man['files']]
last = ['functions.php', 'style.css']
paths = sorted(p for p in paths if p not in last) + ['theme-files.json'] + [p for p in last if p in paths]
bad = [p for p in paths if not p.isascii()]
if bad:
    sys.exit('non-ASCII names: ' + ', '.join(bad))
if os.path.exists(OUT):
    os.remove(OUT)
with zipfile.ZipFile(OUT, 'w', zipfile.ZIP_DEFLATED, compresslevel=9) as z:
    done = set()
    for p in paths:
        parts = ('seohouse/' + p).split('/')[:-1]
        for i in range(1, len(parts) + 1):
            d = '/'.join(parts[:i]) + '/'
            if d not in done:
                z.writestr(zipfile.ZipInfo(d), '')
                done.add(d)
        z.write(os.path.join(THEME, p), 'seohouse/' + p)
# verify
z = zipfile.ZipFile(OUT)
names = [n for n in z.namelist() if not n.endswith('/')]
assert all(n.startswith('seohouse/') for n in z.namelist()), 'one top folder: seohouse/'
want = {f['path']: f['sha1'] for f in man['files']}
got = {n[len('seohouse/'):]: hashlib.sha1(z.read(n)).hexdigest() for n in names}
extra = set(got) - set(want) - {'theme-files.json'}
missing = set(want) - set(got)
wrong = [p for p in want if p in got and got[p] != want[p]]
if extra or missing or wrong or 'theme-files.json' not in got:
    sys.exit(f'theme zip check failed: extra {sorted(extra)} missing {sorted(missing)} changed {wrong}')
if any(n in got for n in ('front-page.php',)) and 'front-page.php' not in want:
    sys.exit('unexpected front-page.php')
print(f"OK seohouse-theme.zip: SEO House theme {man['version']}, {len(names)} files ({man['count']} listed + theme-files.json), all present and intact; no front-page.php: {'front-page.php' not in got}")
