#!/usr/bin/env python3
"""
Checks a SEO House Core zip before it is handed over (and can be run on any copy of it):

  * it is one plugin folder, seohouse-core/, with seohouse-core.php (Plugin Name + Version);
  * seohouse-core/content-pack/manifest.json and pack-files.json are inside;
  * every file pack-files.json lists is inside with the same size and SHA-1, and the pack holds
    nothing unlisted; every page of the manifest has its pages/<key>.json;
  * all names are ASCII; the main plugin file is the last entry;
  * with --folder: the zip holds exactly the files of that folder, byte for byte.

Usage: verify-core-zip.py <seohouse-core.zip> [--folder <plugin-folder>]
"""
import hashlib, json, os, re, sys, zipfile

path = sys.argv[1]
folder = sys.argv[sys.argv.index('--folder') + 1] if '--folder' in sys.argv else None
errors = []
z = zipfile.ZipFile(path)
if z.testzip() is not None:
    errors.append('corrupt entry: ' + z.testzip())
names = z.namelist()
files = [n for n in names if not n.endswith('/')]
roots = {n.split('/')[0] for n in names}
if roots != {'seohouse-core'}:
    errors.append(f'top-level folders {sorted(roots)} (expected only seohouse-core)')
main = 'seohouse-core/seohouse-core.php'
version = ''
if main not in files:
    errors.append('missing ' + main)
else:
    head = z.read(main).decode('utf-8', 'replace')
    m = re.search(r'^\s*\*\s*Version:\s*(\S+)', head, re.M)
    version = m.group(1) if m else ''
    if 'Plugin Name: SEO House Core' not in head or not version:
        errors.append('plugin header incomplete')
    if files[-1] != main:
        errors.append('main plugin file is not the last entry')
P = 'seohouse-core/content-pack/'
for req in ('manifest.json', 'pack-files.json'):
    if P + req not in files:
        errors.append('missing ' + P + req)
listed = []
if P + 'pack-files.json' in files and P + 'manifest.json' in files:
    lst = json.loads(z.read(P + 'pack-files.json'))
    man = json.loads(z.read(P + 'manifest.json'))
    listed = lst['files']
    if lst.get('count') != len(listed) or lst.get('version') != man.get('version'):
        errors.append('pack-files.json header does not match')
    for f in listed:
        n = P + f['path']
        if n not in files:
            errors.append('missing pack file ' + f['path'])
            continue
        data = z.read(n)
        if len(data) != f['size'] or hashlib.sha1(data).hexdigest() != f['sha1']:
            errors.append('changed pack file ' + f['path'])
    in_zip = {n[len(P):] for n in files if n.startswith(P)} - {'pack-files.json'}
    extra = in_zip - {f['path'] for f in listed}
    if extra:
        errors.append('unlisted pack files: ' + ', '.join(sorted(extra)[:5]))
    for pg in man.get('pages', []):
        if P + 'pages/' + pg['key'] + '.json' not in files:
            errors.append('manifest page without file: ' + pg['key'])
bad = [n for n in names if not n.isascii()]
if bad:
    errors.append('non-ASCII names: ' + ', '.join(bad[:5]))
if folder:
    disk = {}
    for d, dirs, fs in os.walk(folder):
        dirs[:] = [x for x in dirs if not x.startswith('.')]
        for f in fs:
            if f.startswith('.') or f.endswith('~'):
                continue
            p = os.path.join(d, f)
            disk['seohouse-core/' + os.path.relpath(p, folder).replace(os.sep, '/')] = p
    if set(disk) != set(files):
        errors.append(f'zip and folder differ: only in folder {sorted(set(disk) - set(files))[:3]}, only in zip {sorted(set(files) - set(disk))[:3]}')
    else:
        for n, p in disk.items():
            if z.read(n) != open(p, 'rb').read():
                errors.append('differs from folder: ' + n)
if errors:
    print('FAIL ' + path)
    for e in errors:
        print('  - ' + e)
    sys.exit(1)
pack = [n for n in files if n.startswith(P)]
print(f'OK {os.path.basename(path)}: SEO House Core {version}, {len(files)} files, content pack {len(pack)} files '
      f'({len(listed)} listed + pack-files.json, all present and intact), manifest {P}manifest.json')
