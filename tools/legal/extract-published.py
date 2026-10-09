#!/usr/bin/env python3
"""The legal text that was published on the main site → docs/legal/<key>.md, word for word.

Source: the HTML of https://seohouse.agency/privacy-policy/ and /terms/ saved on 3 October 2026,
before the site was replaced (docs/legal/source/*.html). Only the page's own text is taken: the
paragraphs and headings between its H1 and the footer, in order, inline bold kept; the closing
"آخر تحديث" line becomes the page's update date. Nothing is reworded.
"""
import html, pathlib, re
ROOT = pathlib.Path(__file__).resolve().parents[2]
SRC = ROOT / 'docs/legal/source'
for key, f in (('privacy-policy', 'privacy-policy-2026-10-03.html'), ('terms', 'terms-2026-10-03.html')):
    h = (SRC / f).read_text(encoding='utf-8')
    body = h[h.index('<h1'):h.index('<footer id="footer">')]
    blocks = re.findall(r'<(h1|h2|h3|p)\b[^>]*>(.*?)</\1>', body, re.S)
    title = re.sub(r'<[^>]+>', '', blocks[0][1]).strip()
    updated, sections, cur = '', [], {'title': '', 'paras': []}
    for tag, inner in blocks[1:]:
        inner = inner.strip()
        if tag == 'p' and re.match(r'^<strong>آخر تحديث:</strong>', inner):
            updated = html.unescape(re.sub(r'<[^>]+>', '', inner.split('<br>', 1)[1])).strip()
            continue
        if tag in ('h2', 'h3'):
            if cur['title'] or cur['paras']:
                sections.append(cur)
            cur = {'title': html.unescape(re.sub(r'<[^>]+>', '', inner)).strip(), 'paras': []}
            continue
        para = re.sub(r'<(?!/?strong\b|/?a\b|br\b)[^>]+>', '', inner)  # keep bold, links, line breaks
        cur['paras'].append(html.unescape(para).replace('\n', ' ').strip())
    sections.append(cur)
    out = ['---', f'title: {title}', f'updated: {updated}', f'source: docs/legal/source/{f} (https://seohouse.agency/{key}/ as published until the site move; read 3 October 2026)', '---']
    for s in sections:
        out.append('')
        out.append('## ' + s['title'] if s['title'] else '## ')
        out.append('\n\n'.join(s['paras']))
    (ROOT / 'docs/legal' / f'{key}.md').write_text('\n'.join(out).rstrip() + '\n', encoding='utf-8')
    print(key, title, updated, len(sections), 'sections', [s['title'] for s in sections])
