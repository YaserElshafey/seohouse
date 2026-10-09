#!/usr/bin/env python3
"""Content before vs after the upgrade: every post, meta value, term and option must be unchanged."""
import json
import sys

a, b = json.load(open(sys.argv[1])), json.load(open(sys.argv[2]))
problems = 0
for part in ('posts', 'meta', 'terms', 'options'):
    x, y = a[part], b[part]
    gone = sorted(set(x) - set(y))
    new = sorted(set(y) - set(x))
    changed = sorted(k for k in set(x) & set(y) if x[k] != y[k])
    print(f'{part:8} before {len(x):6}  after {len(y):6}  removed {len(gone):3}  added {len(new):3}  changed {len(changed):3}')
    for label, keys in (('removed', gone), ('added', new), ('changed', changed)):
        for k in keys[:15]:
            print(f'    {label}: {k}  {str(x.get(k))[:90]!r} -> {str(y.get(k))[:90]!r}')
    problems += len(gone) + len(new) + len(changed)
print('CONTENT UNCHANGED' if problems == 0 else f'CONTENT DIFFERS: {problems} item(s) — review above')
