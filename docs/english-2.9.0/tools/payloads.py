"""payloads.py <ids.json> <out.json>: English import payloads in parent-first order.

ids.json maps Arabic path ("/services/seo/ksa/") -> Arabic page ID on the target site.
"""
import json
import sys

HERE = "./"  # the folder that holds en/<key>.json
# key, Arabic path, English slug, Arabic path of the English parent ("" = top level)
PLAN = [
    ("home", "/", "home-en", ""),
    ("services", "/services/", "our-services", ""),
    ("seo", "/services/seo/", "seo", ""),
    ("seo-ksa", "/services/seo/ksa/", "saudi-arabia", "/services/seo/"),
    ("seo-uae", "/services/seo/uae/", "uae", "/services/seo/"),
    ("seo-technical", "/services/seo/technical/", "technical-seo", "/services/seo/"),
    ("seo-content", "/services/seo/content/", "content-writing", "/services/seo/"),
    ("web-design", "/services/web-design/", "web-design", ""),
    ("stores", "/services/stores/", "ecommerce-store-design", ""),
    ("about", "/about/", "about-us", ""),
    ("contact", "/contact/", "contact-us", ""),
    ("privacy-policy", "/privacy-policy/", "privacy", ""),
    ("terms", "/terms/", "terms-and-conditions", ""),
    ("thank-you", "/thank-you/", "thanks", ""),
]

ids = json.load(open(sys.argv[1]))
out = []
for key, path, slug, parent in PLAN:
    e = json.load(open(HERE + "en/" + key + ".json", encoding="utf-8"))
    seo = e["seo"]
    out.append({
        "key": key,
        "source": ids[path],
        "slug": slug,
        "parent_source": ids[parent] if parent else 0,
        "title": e["title"],
        "sections": e["sections"],
        "seo": {k: seo.get(k) for k in ("rank_math_title", "rank_math_description", "rank_math_focus_keyword", "sh_crumb", "sh_schema_service", "sh_schema_type")},
        "index": False,
    })
json.dump(out, open(sys.argv[2], "w", encoding="utf-8"), ensure_ascii=False)
print(len(out), "payloads")
