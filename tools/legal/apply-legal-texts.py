#!/usr/bin/env python3
"""Privacy policy and terms: docs/legal/<key>-ar.md → content-pack/pages/<key>.json (stay drafts).

The Markdown file is the reviewed source: front matter (updated, seo_description), an intro paragraph
(the page's hero text) and one "## heading" per section. Each section fills one item of the page's
document section (heading + body) and its line in «المحتويات». Usage: apply-legal-texts.py [--check]
"""
import json, re, sys, pathlib
ROOT = pathlib.Path(__file__).resolve().parents[2]
PACK = ROOT / 'wordpress/plugins/seohouse-core/content-pack/pages'
changed = []
for key, fname in (('privacy-policy', 'privacy-policy-ar.md'), ('terms', 'terms-ar.md')):
    md = (ROOT / 'docs/legal' / fname).read_text(encoding='utf-8')
    fm, body = re.match(r'^---\n(.*?)\n---\n(.*)$', md, re.S).groups()
    meta = dict(l.split(': ', 1) for l in fm.splitlines() if ': ' in l)
    parts = re.split(r'^## (.+)$', body, flags=re.M)
    intro, secs = parts[0].strip(), [(parts[i].strip(), parts[i + 1].strip()) for i in range(1, len(parts), 2)]
    page_file = PACK / f'{key}.json'
    page = json.loads(page_file.read_text(encoding='utf-8'))
    before = json.dumps(page, ensure_ascii=False, sort_keys=True)
    assert page['status'] == 'draft', 'legal pages stay drafts until approved'
    hero = next(s for s in page['sections'] if s['acf_fc_layout'] == 'hero')
    doc = next(s for s in page['sections'] if s['acf_fc_layout'] == 'document')
    group = json.loads((ROOT / f'wordpress/plugins/seohouse-core/acf-json/group_sh_page_{key.replace("-", "_")}.json').read_text(encoding='utf-8'))
    rows = next(f for f in next(f for f in group['fields'] if f.get('name') == 's_document')['sub_fields'] if f['name'] == 'items_2')
    variants = len(next(f for f in rows['sub_fields'] if f['name'] == 'variant')['choices'])
    assert len(secs) <= variants, f'{key}: {len(secs)} sections, the design has {variants} anchors'
    hero['text'] = intro
    doc['label'] = 'آخر تحديث:'
    doc['updated'] = meta['updated']
    doc['items'] = [{'label': re.sub(r'<[^>]+>', '', t).strip().lstrip('0123456789. ').strip(), 'variant': f'v{i + 1}'} for i, (t, _) in enumerate(secs)]
    doc['items_2'] = [{'title': t, 'body': b, 'label': '', 'variant': f'v{i + 1}'} for i, (t, b) in enumerate(secs)]
    page['seo']['description'] = meta['seo_description']
    if json.dumps(page, ensure_ascii=False, sort_keys=True) != before:
        changed.append(key)
        if '--check' not in sys.argv:
            page_file.write_text(json.dumps(page, ensure_ascii=False, indent=1) + '\n', encoding='utf-8')
    print(key, len(secs), 'sections', 'changed' if key in changed else 'unchanged')
if '--check' in sys.argv and changed:
    sys.exit('out of date: ' + ', '.join(changed))
