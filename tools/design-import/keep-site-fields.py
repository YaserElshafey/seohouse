#!/usr/bin/env python3
"""
Step 3 — after `node convert.js` on a NEW design round for a site that is already live.

convert.js derives field names from the design's DOM, so a new design can rename fields
(text → text_2, two cards → one list…). On a live site that would hide saved content or show it
in the wrong place. This step keeps the site's data model and adapts the new markup to it:

  1. acf-json and the content pack are restored from <base-ref> (the release installed on the
     site): field names, keys and choices stay exactly as saved values expect them.
  2. Icon IDs: the new icons.php keeps every old ID as an alias that draws the icon in the same
     position of the new design (same choice index of the same field). Aliases are not offered
     as new choices (sh_icon_choices()).
  3. Variant selects: where the new design renumbers the row styles, the section's style table
     also answers the old values (row-wise mapping old pack → new pack).
  4. Check: every field a generated section reads must exist in the kept definitions. A section
     that fails is reported and must be bound by hand (tag it @sh-manual).

Usage: keep-site-fields.py <repo> <base-ref>      (e.g. keep-site-fields.py . v2.6.0-commit)
Run it right after convert.js, before anything is committed. Re-running is safe.
"""
import glob
import json
import os
import re
import subprocess
import sys
from collections import defaultdict

REPO, BASE = os.path.abspath(sys.argv[1]), sys.argv[2]
THEME = f'{REPO}/wordpress/themes/seohouse'
CORE = f'{REPO}/wordpress/plugins/seohouse-core'
git = lambda *a: subprocess.run(['git', '-C', REPO, *a], capture_output=True, text=True, check=True).stdout


def base_file(rel):
    return git('show', f'{BASE}:{rel}')


# ---------------------------------------------------------------- read the new generation first
# (kept in extract/newgen.json: on a re-run the files below are already the restored ones)
CACHE = f'{REPO}/tools/design-import/extract/newgen.json'
restored = not git('status', '--porcelain', '--', 'wordpress/plugins/seohouse-core/acf-json', 'wordpress/plugins/seohouse-core/content-pack').strip()
if restored and os.path.exists(CACHE):
    cached = json.load(open(CACHE))
    new_groups, new_pack = cached['groups'], cached['pack']
elif restored:
    sys.exit('acf-json and the content pack equal the base already and no cached generation exists: run convert.js first')
else:
    new_groups = {os.path.basename(p): json.load(open(p)) for p in glob.glob(f'{CORE}/acf-json/group_sh_page_*.json')}
    new_pack = {os.path.basename(p): json.load(open(p)) for p in glob.glob(f'{CORE}/content-pack/pages/*.json')}
    json.dump({'groups': new_groups, 'pack': new_pack}, open(CACHE, 'w'), ensure_ascii=False)
ICON_RE = re.compile(r"\t'(i[0-9a-f]{8})' => array\( 'label' => '([^']*)', 'svg' => '((?:[^'\\]|\\.)*)'(, 'alias_of' => '[^']*')? \),\n")
new_icons_src = open(f'{THEME}/inc/generated/icons.php').read()
new_icons = {k: (l, s) for k, l, s, al in ICON_RE.findall(new_icons_src) if not al}  # aliases of a previous run are rebuilt

# ---------------------------------------------------------------- 1. restore the site's data model
git('checkout', BASE, '--', 'wordpress/plugins/seohouse-core/acf-json', 'wordpress/plugins/seohouse-core/content-pack')
old_groups = {os.path.basename(p): json.load(open(p)) for p in glob.glob(f'{CORE}/acf-json/group_sh_page_*.json')}
old_pack = {os.path.basename(p): json.load(open(p)) for p in glob.glob(f'{CORE}/content-pack/pages/*.json')}
old_icons = {k: (l, s) for k, l, s, al in ICON_RE.findall(base_file('wordpress/themes/seohouse/inc/generated/icons.php')) if not al}
print(f'1. acf-json and content pack restored from {BASE}')


# ---------------------------------------------------------------- 2. icons: old IDs → same position in the new design
def icon_fields(fields, path, acc):
    for f in fields:
        if f['name'].startswith('icon') and f['type'] == 'select':
            acc[path + '/' + f['name']] = list(f.get('choices') or {})
        icon_fields(f.get('sub_fields', []), path + '/' + f['name'], acc)


alias, conflicts = defaultdict(set), []
for name, og in old_groups.items():
    a, b = {}, {}
    icon_fields(og['fields'], '', a)
    icon_fields(new_groups.get(name, {'fields': []})['fields'], '', b)
    for path, old_choices in a.items():
        for i, old_id in enumerate(old_choices):
            nl = b.get(path) or []
            if nl:
                alias[old_id].add(nl[min(i, len(nl) - 1)])
for old_id, targets in alias.items():
    if len(targets) > 1:
        conflicts.append((old_id, sorted(targets)))
lines = [f"\t'{k}' => array( 'label' => '{l}', 'svg' => '{s}' ),\n" for k, (l, s) in new_icons.items()]
aliases = []
for k in old_icons:
    if k in new_icons:
        continue
    t = sorted(alias.get(k, []))
    if not t:  # not offered by any field: keep its own drawing
        aliases.append(f"\t'{k}' => array( 'label' => '{old_icons[k][0]}', 'svg' => '{old_icons[k][1]}', 'alias_of' => '{k}' ),\n")
        continue
    l, s = new_icons[t[0]]
    aliases.append(f"\t'{k}' => array( 'label' => '{l}', 'svg' => '{s}', 'alias_of' => '{t[0]}' ),\n")
head = new_icons_src[:new_icons_src.index('return array(\n')]
open(f'{THEME}/inc/generated/icons.php', 'w').write(
    head + 'return array(\n' + ''.join(lines)
    + '\t// Icon IDs of the previous design, kept so values saved on the site keep working: each one\n'
    + '\t// draws the icon in the same position of the current design (same choice of the same field).\n'
    + ''.join(aliases) + ');\n')
print(f'2. icons: {len(new_icons)} current, {len(aliases)} aliases of previous IDs, conflicts: {conflicts or "none"}')


# ---------------------------------------------------------------- 3. variant renumbering
def walk(a, b, path, acc):
    if isinstance(a, dict) and isinstance(b, dict):
        for k in a:
            if k in b:
                walk(a[k], b[k], path + '/' + k, acc)
    elif isinstance(a, list) and isinstance(b, list):
        for x, y in zip(a, b):
            if isinstance(x, dict) and isinstance(y, dict) and 'variant' in x and 'variant' in y:
                acc[path].append((x['variant'], y['variant']))
            walk(x, y, path + '[]', acc)


remaps = []
for name, op in old_pack.items():
    np_ = new_pack.get(name)
    if not np_:
        continue
    sa = {s['acf_fc_layout']: s for s in op.get('sections', [])}
    sb = {s['acf_fc_layout']: s for s in np_.get('sections', [])}
    for lay in sa.keys() & sb.keys():
        acc = defaultdict(list)
        walk(sa[lay], sb[lay], '', acc)
        for path, rows in acc.items():
            if all(o == n for o, n in rows):
                continue
            m = {}
            for o, n in rows:
                m.setdefault(o, n)
            remaps.append((op['key'], lay, path, m))
for key, lay, path, m in remaps:
    f = f'{THEME}/sections/{key}/{lay}.php'
    s = open(f).read()
    if '@sh-manual' in s or '/* previous design values:' in s:  # hand-kept, or already answered (re-run)
        continue
    found = re.findall(r"\$(vt_[0-9a-f]{8}) = (\[(?:'v\d+' => \[[^\]]*\](?:, )?)+\]);", s)
    if len(found) != 1:
        print(f'   ! {key}/{lay}{path}: {len(found)} style tables, bind by hand: {m}')
        continue
    var, table = found[0]
    entries = dict(re.findall(r"'(v\d+)' => (\[[^\]]*\])", table))
    merged = {**entries, **{o: entries[n] for o, n in m.items() if n in entries}}
    order = sorted(merged, key=lambda v: int(v[1:]))
    new_table = '[' + ', '.join(f"'{v}' => {merged[v]}" for v in order) + ']'
    s = s.replace(f'${var} = {table};', f'${var} = {new_table}; /* previous design values: {json.dumps(m)} */', 1)
    open(f, 'w').write(s)
    print(f'3. {key}/{lay}{path}: previous values answered {m}')


# ---------------------------------------------------------------- 4. every field read must exist
def names(fields, acc, prefix=''):
    for f in fields:
        if f['type'] == 'accordion':
            continue
        acc.add(prefix + f['name'])
        names(f.get('sub_fields', []), acc, prefix + f['name'] + '/')


bad = 0
for name, og in old_groups.items():
    key = og['key'].replace('group_sh_page_', '').replace('_', '-')
    for sec in [f for f in og['fields'] if f['type'] == 'group' and f['name'].startswith('s_')]:
        lay = sec['name'][2:]
        f = f'{THEME}/sections/{key}/{lay}.php'
        if not os.path.exists(f):
            continue
        src = open(f).read()
        have = set()
        names(sec.get('sub_fields', []), have)
        top = {n for n in have if '/' not in n} | {'sh_anchor', 'sh_hide'}
        sub = {n.split('/')[-1] for n in have}
        missing = sorted(set(re.findall(r"\$f\['([a-z0-9_]+)'\]", src)) - top) + \
            sorted(set(re.findall(r"\$r\d+\['([a-z0-9_]+)'\]", src)) - sub)
        if missing:
            bad += 1
            print(f"   ! sections/{key}/{lay}.php reads fields the site does not have: {missing}"
                  f"{'' if '@sh-manual' in src else '  → bind by hand'}")
print(f'4. field check: {bad} section(s) reading unknown fields')
