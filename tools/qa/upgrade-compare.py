#!/usr/bin/env python3
"""Compares two upgrade-new.sh snapshots: nothing lost, nothing duplicated, edits kept, design updates applied.
Usage: upgrade-compare.py before.json after.json [out.md]"""
import json, sys
b, a = (json.load(open(f)) for f in sys.argv[1:3])
res = []
def check(name, ok, detail=''):
    res.append((ok, name, detail)); print(('PASS' if ok else 'FAIL') + '  ' + name + (('  — ' + detail) if detail else ''))
bp = {p['id']: p for p in b['pages']}; ap = {p['id']: p for p in a['pages']}
check('versions', a['core'] == '2.3.0' and a['theme'] == '2.3.0' and a['last_import'] == '2.3.0', f"core {b['core']}→{a['core']}, theme {b['theme']}→{a['theme']}, pack {b['last_import']}→{a['last_import']}")
lost = [i for i in bp if i not in ap]
check('no page, article, case study or team record lost', not lost, str(lost))
new = [ap[i]['url'] for i in ap if i not in bp]
check('no new pages or records created (no duplicates)', not new, str(new))
moved = [(i, bp[i]['url'], ap[i]['url']) for i in bp if i in ap and (bp[i]['url'] != ap[i]['url'] or bp[i]['slug'] != ap[i]['slug'] or bp[i]['status'] != ap[i]['status'] or bp[i]['template'] != ap[i]['template'])]
check('IDs, slugs, URLs, status and templates unchanged', not moved, str(moved[:5]))
adopted = [p for p in a['pages'] if p['pre_adoption']]
check('pages adopted under 2.2.5 keep their ID and URL', len(adopted) == len([p for p in b['pages'] if p['pre_adoption']]), ', '.join(f"{p['id']} {p['url']}" for p in adopted))
for k, v in b['edited'].items():
    check(f'editor change kept: {k}', a['edited'][k] == v, str(a['edited'][k]))
want = {'egypt_h1': 'خدمات تحسين محركات البحث في مصر', 'ksa_h1': 'خدمات تحسين محركات البحث في السعودية', 'uae_h1': 'خدمات تحسين محركات البحث في الإمارات', 'react_h1': 'تطوير مواقع باستخدام رياكت ونكست'}
for k, v in want.items():
    check(f'design update applied: {k}', a['design'][k] == v, f"{b['design'][k]} → {a['design'][k]}")
check('media: only the 10 platform logos added', a['counts']['attachments'] - b['counts']['attachments'] == 10, f"{b['counts']['attachments']} → {a['counts']['attachments']}")
check('published / draft pages unchanged', (a['counts']['pages_publish'], a['counts']['pages_draft']) == (b['counts']['pages_publish'], b['counts']['pages_draft']), f"{a['counts']['pages_publish']} published, {a['counts']['pages_draft']} drafts")
check('platforms library added (10 entries)', a['platforms'] == 10 and b['platforms'] == 0)
check('Rank Math: titles moved into empty fields, editor title kept', a['rank_math_titles'] > b['rank_math_titles'] and a['edited']['ksa_rank_math_title'] == b['edited']['ksa_rank_math_title'], f"{b['rank_math_titles']} → {a['rank_math_titles']} pages with a Rank Math title")
if len(sys.argv) > 3:
    with open(sys.argv[3], 'w') as f:
        f.write('| | Check | Detail |\n|---|---|---|\n' + ''.join(f"| {'سليم' if ok else 'فشل'} | {n} | {d.replace('|', '/')} |\n" for ok, n, d in res))
fails = sum(1 for r in res if not r[0]); print(f"\n{len(res)-fails}/{len(res)} passed"); sys.exit(1 if fails else 0)
