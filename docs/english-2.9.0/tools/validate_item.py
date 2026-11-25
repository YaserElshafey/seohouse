"""validate_item.py <type>-<id>: en/<key>.json against ar/<key>.json."""
import json
import re
import sys

AR = re.compile(r"[؀-ۿ]")
key = sys.argv[1]
a = json.load(open(f"ar/{key}.json", encoding="utf-8"))
e = json.load(open(f"en/{key}.json", encoding="utf-8"))
errs = []
for k in ("title", "slug", "content", "excerpt", "fields", "seo"):
    if k not in e:
        errs.append(f"missing {k}")
typ = a["type"]
slug = e.get("slug", "")
if typ in ("post", "team_member") and slug != a["slug"] + "-en":
    errs.append(f"slug must be {a['slug']}-en")
if typ == "case_study" and (not re.fullmatch(r"[a-z0-9-]{8,80}", slug) or slug.endswith("-en")):
    errs.append("case study slug: short lowercase English words with hyphens")
for k in ("title", "excerpt"):
    if AR.search(e.get(k, "") or ""):
        errs.append(f"{k}: Arabic left")
if not e.get("title"):
    errs.append("title empty")

# content: block comments and tag sequence unchanged
def blocks(s):
    return re.findall(r"<!-- /?wp:[a-z0-9/-]+", s or "")


def tags(s):
    return re.findall(r"</?([a-zA-Z0-9]+)", s or "")


ca, ce = a.get("content", ""), e.get("content", "")
if blocks(ca) != blocks(ce):
    errs.append(f"content: block comments differ ({len(blocks(ca))} vs {len(blocks(ce))})")
if tags(ca) != tags(ce):
    errs.append(f"content: HTML tag sequence differs ({len(tags(ca))} vs {len(tags(ce))})")
# Arabic left in content outside quotes «…» (Arabic examples may stay quoted)
body = re.sub(r"«[^»]*»", "", re.sub(r"<!--.*?-->", "", ce, flags=re.S))
left = re.findall(r"[؀-ۿ][؀-ۿ\s]{2,}", re.sub(r"<[^>]+>", " ", body))
if left:
    errs.append(f"content: Arabic left outside «quotes»: {left[:3]}")
for m in re.findall(r'href="(https://seohouse\.agency/[^"]*)"', ce):
    if "/wp-content/" not in m and not m.startswith("https://seohouse.agency/en/"):
        errs.append(f"content: internal link not /en/: {m[:80]}")
        break


def walk(x, y, path):
    if isinstance(x, dict):
        if not isinstance(y, dict) or set(x) != set(y):
            errs.append(f"{path}: keys differ")
            return
        for k in x:
            walk(x[k], y[k], f"{path}.{k}")
    elif isinstance(x, list):
        if not isinstance(y, list) or len(x) != len(y):
            errs.append(f"{path}: list length differs")
            return
        for i, (p, q) in enumerate(zip(x, y)):
            walk(p, q, f"{path}[{i}]")
    elif isinstance(x, str) and AR.search(x):
        if isinstance(y, str) and AR.search(re.sub(r"«[^»]*»", "", y)):
            errs.append(f"{path}: Arabic left")
        if y in ("", None):
            errs.append(f"{path}: text dropped")
    else:
        if x != y and not (isinstance(x, str) and isinstance(y, str) and (re.fullmatch(r"[\x00-\x7F]*", x or "") and x.strip() and not re.match(r"^(https?:|/|#)", x))):
            if x != y:
                errs.append(f"{path}: must stay {x!r}")


walk(a.get("fields", {}), e.get("fields", {}), "fields")
seo = e.get("seo", {})
t, d = seo.get("rank_math_title", ""), seo.get("rank_math_description", "")
if not t.endswith(" | SEO House") or len(t) > 60 or t.count("SEO House") != 1:
    errs.append(f"seo.rank_math_title: <=60 chars, ends ' | SEO House' ({len(t)}): {t!r}")
if not 120 <= len(d) <= 160:
    errs.append(f"seo.rank_math_description length {len(d)}")
for k, v in seo.items():
    if isinstance(v, str) and AR.search(v):
        errs.append(f"seo.{k}: Arabic left")
print("OK" if not errs else "\n".join(errs[:40]))
