#!/usr/bin/env python3
"""wordpress/themes/seohouse/theme-files.json: every file the theme release ships (path, size, sha1).

The theme reads it to tell its own files from leftovers of an older theme in the same folder
(inc/release-files.php). Usage: theme-manifest.py [--check]  (--check fails if it is out of date)
"""
import hashlib, json, os, sys
THEME = os.path.join(os.path.dirname(os.path.abspath(__file__)), '..', 'wordpress', 'themes', 'seohouse')
THEME = os.path.normpath(THEME)
SKIP_DIRS = {'node_modules', '.git'}
files = []
for root, dirs, names in os.walk(THEME):
    dirs[:] = sorted(d for d in dirs if d not in SKIP_DIRS and not d.startswith('.'))
    for n in sorted(names):
        if n.startswith('.') or n == 'theme-files.json' or n == '.DS_Store':
            continue
        p = os.path.join(root, n)
        rel = os.path.relpath(p, THEME).replace(os.sep, '/')
        data = open(p, 'rb').read()
        files.append({'path': rel, 'size': len(data), 'sha1': hashlib.sha1(data).hexdigest()})
version = next((l.split(':', 1)[1].strip() for l in open(os.path.join(THEME, 'style.css'), encoding='utf-8') if l.startswith('Version:')), '')
out = json.dumps({'theme': 'seohouse', 'version': version, 'count': len(files), 'files': files}, indent=1) + '\n'
target = os.path.join(THEME, 'theme-files.json')
current = open(target, encoding='utf-8').read() if os.path.exists(target) else ''
if '--check' in sys.argv:
    if current != out:
        sys.exit('theme-files.json out of date — run tools/theme-manifest.py')
    print(f'theme-files.json ok: {version}, {len(files)} files')
else:
    open(target, 'w', encoding='utf-8').write(out)
    print(f'theme-files.json: {version}, {len(files)} files')
