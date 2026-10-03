#!/usr/bin/env python3
"""
Builds the SEO House Core zip from the plugin folder exactly as it is in the repository
(wordpress/plugins/seohouse-core, content pack included at content-pack/). Nothing is added or
transformed here, so the zip, a copy of the folder from GitHub and a deployment from the
repository are the same plugin.

  * refuses to build if the pack is not finalized (tools/design-import/finalize-pack.py --check);
  * order: content pack first (manifest, pack-files.json, data, pages, then images), plugin code
    next, the main plugin file seohouse-core.php last — an interrupted extraction never leaves an
    active plugin without its pack;
  * the finished zip is checked with tools/verify-core-zip.py.

Usage: package-core.py <repo> <out.zip>
"""
import os, subprocess, sys, zipfile

REPO, OUT = sys.argv[1], sys.argv[2]
PLUGIN = os.path.join(REPO, 'wordpress/plugins/seohouse-core')
subprocess.run([sys.executable, os.path.join(REPO, 'tools/design-import/finalize-pack.py'), os.path.join(PLUGIN, 'content-pack'), '--check'], check=True, stdout=subprocess.DEVNULL)

entries = []
for d, dirs, files in os.walk(PLUGIN):
    dirs[:] = sorted(x for x in dirs if not x.startswith('.'))
    for f in sorted(files):
        if f.startswith('.') or f.endswith('~'):
            continue
        entries.append('seohouse-core/' + os.path.relpath(os.path.join(d, f), PLUGIN).replace(os.sep, '/'))
pfx = 'seohouse-core/content-pack/'
rank = lambda e: (0 if e == pfx + 'manifest.json' else 1 if e == pfx + 'pack-files.json' else
                  2 if e.startswith(pfx + 'data/') else 3 if e.startswith(pfx + 'pages/') else
                  4 if e.startswith(pfx) else 6 if e == 'seohouse-core/seohouse-core.php' else 5, e)
entries.sort(key=rank)
bad = [e for e in entries if not e.isascii()]
if bad:
    sys.exit('non-ASCII names: ' + ', '.join(bad))

if os.path.exists(OUT):
    os.remove(OUT)
with zipfile.ZipFile(OUT, 'w', zipfile.ZIP_DEFLATED, compresslevel=9) as z:
    done = set()
    for e in entries:
        parts = e.split('/')[:-1]
        for i in range(1, len(parts) + 1):
            dname = '/'.join(parts[:i]) + '/'
            if dname not in done:
                z.writestr(zipfile.ZipInfo(dname), '')
                done.add(dname)
        z.write(os.path.join(PLUGIN, e[len('seohouse-core/'):]), e)
subprocess.run([sys.executable, os.path.join(REPO, 'tools/verify-core-zip.py'), OUT, '--folder', PLUGIN], check=True)
