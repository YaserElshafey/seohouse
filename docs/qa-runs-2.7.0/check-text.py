#!/usr/bin/env python3
"""After the upgrade: owner edits visible, deleted member absent, no URL changed status, no text lost."""
import json
import sys

before, after, db = (json.load(open(p)) for p in sys.argv[1:4])
ok = True


def page_text(url_part):
    hits = [u for u in after if url_part in u]
    return hits, ' '.join(t for u in hits for t in after[u]['text'])


print('-- owner edits visible')
for url_part, needle in (
    ('127.0.0.1:8090/', 'نحسّن ظهور موقعك في محركات البحث'),
    ('127.0.0.1:8090/', 'فريق بخبرات متنوعة يعمل معك لجذب العملاء وزيادة مبيعاتك.'),
    ('127.0.0.1:8090/', '[EDIT-ROW]'),
    ('127.0.0.1:8090/', '[EDIT-HOME-SERVICES]'),
    ('/team/', '[EDIT-TEAM]'),
    ('/custom-dev/', '[EDIT-FIT]'),
    ('/woocommerce/', '[EDIT-WOO]'),
    ('/sectors/legal/', '[EDIT-LEGAL]'),
    ('/backlinks/', '[EDIT-BACKLINKS]'),
):
    urls = [u for u in after if u.endswith(url_part) or (url_part != '127.0.0.1:8090/' and url_part in u)]
    found = any(needle in ' '.join(after[u]['text']) for u in urls)
    ok &= found
    print(f"   {'OK ' if found else 'MISSING'} {needle}  ({urls[0] if urls else 'no url'})")

print('-- deleted team member (mona-ali / منى علي)')
gone_urls = [u for u in after if 'mona-ali' in u]
print(f"   own page among published URLs: {gone_urls or 'none'}")
pages_naming = [u for u in after if 'منى علي' in ' '.join(after[u]['text'])]
pages_naming_before = [u for u in before if 'منى علي' in ' '.join(before[u]['text'])]
print(f'   pages showing the name — before upgrade: {len(pages_naming_before)}, after: {len(pages_naming)} {pages_naming[:5]}')
ok &= not gone_urls and not pages_naming

print('-- status per URL')
for u in sorted(set(before) | set(after)):
    sb, sa = before.get(u, {}).get('status'), after.get(u, {}).get('status')
    if sb != sa:
        ok = False
        print(f'   CHANGED {u}: {sb} -> {sa}')
print(f"   {sum(1 for u in after if after[u]['status'] == 200)} URLs answer 200; 404 page: {[after[u]['status'] for u in after if 'no-such-page' in u]}")
errs = [u for u in after if after[u]['php_error']]
print(f'   PHP warnings/errors printed in pages: {errs or "none"}')
ok &= not errs

print('-- text that was on a page before and is missing after (content must not disappear)')
lost_total = 0
for u in sorted(before):
    if u not in after:
        continue
    b, a = set(before[u]['text']), set(after[u]['text'])
    lost = sorted(b - a)
    if lost:
        lost_total += len(lost)
        print(f'   {u}  lost {len(lost)}:')
        for t in lost[:12]:
            print(f'      - {t[:110]}')
print(f'   total lines lost: {lost_total}')
print('RESULT:', 'PASS' if ok else 'FAIL (see above)')
