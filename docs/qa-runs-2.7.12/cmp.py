# Compare two crawls: visible text, link targets, headings/meta must be identical. Usage: python3 cmp.py a b
import sys, os, re, html
def parse(s):
    u, h = s.split('\n', 1)
    h = re.sub(r'\?ver=[^"\'& ]+', '', h)
    body = re.sub(r'(?s)<(script|style)\b.*?</\1>', '', h)
    text = re.sub(r'\s+', ' ', html.unescape(re.sub(r'<[^>]+>', ' ', body))).strip()
    links = re.findall(r'<a\b[^>]*href="([^"]*)"', h)
    head = re.findall(r'(?s)<(?:title|link rel="(?:canonical|alternate)"|meta name="description")[^>]*>(?:[^<]*</title>)?', h)
    h1 = re.findall(r'(?s)<h1\b.*?</h1>', h)
    return u, text, links, head, [re.sub(r'<[^>]+>', '', x) for x in h1], len(re.findall(r'<section\b', h))
a, b = sys.argv[1:3]; bad = 0; changed = 0
for f in sorted(os.listdir(a)):
    A = parse(open(os.path.join(a, f)).read()); B = parse(open(os.path.join(b, f)).read())
    for i, n in enumerate(['text', 'links', 'head', 'h1', 'sections'], 1):
        if A[i] != B[i]:
            bad += 1; print('DIFF', n, A[0])
            if n == 'text':
                import difflib; print('  ', [x for x in difflib.ndiff(A[1].split(' '), B[1].split(' ')) if x[0] in '+-'][:12])
    if open(os.path.join(a, f)).read() != open(os.path.join(b, f)).read(): changed += 1
print(f'{len(os.listdir(a))} pages; html changed on {changed}; text/links/head/h1/section differences: {bad}')
