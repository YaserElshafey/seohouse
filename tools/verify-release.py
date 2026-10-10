#!/usr/bin/env python3
"""
Checks that the committed release/ packages are the packages built and checked from this commit.

  * release/seohouse-core.zip and release/seohouse-theme.zip must match the zips just built by
    package-core.py / package-theme.py entry by entry: same names in the same order, same bytes,
    same time and mode. (The zips are built with a fixed time and mode, so on the same zlib they
    are byte-identical too; a different zlib may compress the same bytes differently, which is
    why the entries are compared and not only the zip files.)
  * release/SHA256SUMS.txt must list exactly those two files with their real sha256.

Usage: verify-release.py <release-dir> <built-core.zip> <built-theme.zip>
"""
import hashlib, os, re, sys, zipfile

REL, BUILT = sys.argv[1], {'seohouse-core.zip': sys.argv[2], 'seohouse-theme.zip': sys.argv[3]}
errors = []


def entries(path):
    with zipfile.ZipFile(path) as z:
        return [(i.filename, i.date_time, i.external_attr, i.CRC, i.file_size, z.read(i)) for i in z.infolist()]


def version(path):
    with zipfile.ZipFile(path) as z:
        for n in z.namelist():
            if n in ('seohouse/style.css', 'seohouse-core/seohouse-core.php'):
                m = re.search(r'^\s*\*?\s*Version:\s*(\S+)', z.read(n).decode('utf-8'), re.M)
                return m.group(1) if m else '?'
    return '?'


def sha256(path):
    return hashlib.sha256(open(path, 'rb').read()).hexdigest()


for name, built in BUILT.items():
    rel = os.path.join(REL, name)
    if not os.path.exists(rel):
        errors.append(f'{rel} missing')
        continue
    a, b = entries(rel), entries(built)
    if a != b:
        an, bn = [e[0] for e in a], [e[0] for e in b]
        diff = sorted(set(an) ^ set(bn)) or [x[0] for x, y in zip(a, b) if x != y] or ['order']
        errors.append(f'{name}: release file differs from the build of this commit: {diff[:10]}')
        continue
    same = sha256(rel) == sha256(built)
    print(f'OK {name}: {version(rel)}, {sum(1 for e in a if not e[0].endswith("/"))} files, same entries as the build'
          f'{" and byte-identical" if same else " (compressed differently by this zlib)"}, sha256 {sha256(rel)}')

sums = os.path.join(REL, 'SHA256SUMS.txt')
listed = {}
for line in open(sums, encoding='utf-8') if os.path.exists(sums) else []:
    if line.strip():
        h, f = line.split()
        listed[f.lstrip('*')] = h
if set(listed) != set(BUILT):
    errors.append(f'SHA256SUMS.txt lists {sorted(listed)}, expected {sorted(BUILT)}')
for f, h in listed.items():
    p = os.path.join(REL, f)
    if os.path.exists(p) and sha256(p) != h:
        errors.append(f'SHA256SUMS.txt: wrong sha256 for {f}')
if errors:
    sys.exit('release check failed:\n  ' + '\n  '.join(errors))
print('OK SHA256SUMS.txt matches release/')
