"""validate.py <key>: compare en/<key>.json with ar/<key>.json (structure, untouched values, no Arabic left)."""
import json
import re
import sys

AR = re.compile(r"[؀-ۿ]")
KEEP_TOP = ["id", "key", "parent", "path", "template", "status", "lang", "slug"]
TEXT_TYPES = {"text", "textarea"}


def walk(a, e, t, path, errs):
    if isinstance(a, dict):
        if not isinstance(e, dict):
            errs.append(f"{path}: expected object")
            return
        if list(a.keys()) != list(e.keys()):
            errs.append(f"{path}: keys differ {sorted(set(a) ^ set(e))}")
            return
        for k in a:
            sub_t = t.get(k) if isinstance(t, dict) else None
            if k == "acf_fc_layout":
                sub_t = "keep"
            walk(a[k], e[k], sub_t, f"{path}.{k}", errs)
        return
    if isinstance(a, list):
        if not isinstance(e, list) or len(a) != len(e):
            errs.append(f"{path}: list length {len(a)} vs {len(e) if isinstance(e, list) else type(e).__name__}")
            return
        row_t = t[0] if isinstance(t, list) and t else None
        for i, (x, y) in enumerate(zip(a, e)):
            walk(x, y, row_t, f"{path}[{i}]", errs)
        return
    ttype = t if isinstance(t, str) else None
    if ttype in TEXT_TYPES:
        if path.endswith(".sh_anchor") and a != e:
            errs.append(f"{path}: sh_anchor must not change")
        if isinstance(e, str) and AR.search(e):
            errs.append(f"{path}: Arabic left: {e[:60]!r}")
        if a in ("", None) and e not in ("", None):
            errs.append(f"{path}: was empty, now filled")
        if a not in ("", None) and e in ("", None):
            errs.append(f"{path}: text dropped")
        return
    if a != e:
        errs.append(f"{path}: {ttype or 'value'} must stay {a!r}, got {e!r}")


def main():
    key = sys.argv[1]
    a = json.load(open(f"ar/{key}.json", encoding="utf-8"))
    e = json.load(open(f"en/{key}.json", encoding="utf-8"))
    t = json.load(open(f"types/{key}.json", encoding="utf-8"))
    errs = []
    for k in KEEP_TOP:
        if a.get(k) != e.get(k):
            errs.append(f"{k} must stay {a.get(k)!r}")
    if set(a) != set(e):
        errs.append(f"top-level keys differ {sorted(set(a) ^ set(e))}")
    if not e.get("title") or AR.search(e.get("title", "")):
        errs.append("title missing or Arabic")
    seo_a, seo_e = a.get("seo", {}), e.get("seo", {})
    if set(seo_a) != set(seo_e):
        errs.append("seo keys differ")
    for k in ("rank_math_robots", "sh_seo_noindex", "sh_schema_type"):
        if seo_a.get(k) != seo_e.get(k):
            errs.append(f"seo.{k} must stay {seo_a.get(k)!r}")
    title = seo_e.get("rank_math_title") or ""
    desc = seo_e.get("rank_math_description") or ""
    if not title.endswith(" | SEO House") or len(title) > 60:
        errs.append(f"seo.rank_math_title must end with ' | SEO House' and be <= 60 chars ({len(title)}): {title!r}")
    if title.count("SEO House") != 1:
        errs.append("seo.rank_math_title: brand must appear once")
    noindex = "noindex" in json.dumps(seo_a.get("rank_math_robots"))
    if not noindex and not 120 <= len(desc) <= 160:
        errs.append(f"seo.rank_math_description length {len(desc)} (want 140-155)")
    if seo_e.get("sh_seo_title") != title or seo_e.get("sh_seo_description") != desc:
        errs.append("seo.sh_seo_title/description must equal the rank_math values")
    for k, v in seo_e.items():
        if isinstance(v, str) and AR.search(v):
            errs.append(f"seo.{k}: Arabic left")
    sa, se = a.get("sections", []), e.get("sections", [])
    if len(sa) != len(se):
        errs.append("number of sections differs")
    else:
        for i, (x, y) in enumerate(zip(sa, se)):
            lay = x.get("acf_fc_layout")
            walk(x, y, t.get(lay, {}), f"sections[{i}:{lay}]", errs)
    print("OK" if not errs else "\n".join(errs[:60]) + (f"\n... {len(errs)} problems" if len(errs) > 60 else ""))


main()
