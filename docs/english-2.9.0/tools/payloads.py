"""payloads.py <ids.json> <out.json>: English import payloads in parent-first order.

ids.json maps Arabic path ("/services/seo/ksa/") -> Arabic page ID on the target site.
"""
import json
import os
import sys

HERE = "./"  # the folder that holds en/<key>.json
# key, Arabic path, English slug, Arabic path of the English parent ("" = top level)
PLAN = [
    ("home", "/", "home-en", ""),
    ("services", "/services/", "our-services", ""),
    ("seo", "/services/seo/", "seo", "/services/"),
    ("seo-ksa", "/services/seo/ksa/", "saudi-arabia", "/services/seo/"),
    ("seo-uae", "/services/seo/uae/", "uae", "/services/seo/"),
    ("seo-technical", "/services/seo/technical/", "technical-seo", "/services/seo/"),
    ("seo-content", "/services/seo/content/", "content-writing", "/services/seo/"),
    ("seo-egypt", "/services/seo/egypt/", "egypt-en", "/services/seo/"),
    ("seo-on-page", "/services/seo/on-page/", "on-page-en", "/services/seo/"),
    ("seo-backlinks", "/services/seo/backlinks/", "backlinks-en", "/services/seo/"),
    ("seo-consulting", "/services/seo/consulting/", "consulting-en", "/services/seo/"),
    ("web-design", "/services/web-design/", "web-design", "/services/"),
    ("web-wordpress", "/services/web-design/wordpress/", "wordpress-en", "/services/web-design/"),
    ("web-webflow", "/services/web-design/webflow/", "webflow-en", "/services/web-design/"),
    ("web-react-next", "/services/web-design/react-next/", "react-next-en", "/services/web-design/"),
    ("web-custom-dev", "/services/web-design/custom-dev/", "custom-dev-en", "/services/web-design/"),
    ("stores", "/services/stores/", "ecommerce-store-design", "/services/"),
    ("stores-salla", "/services/stores/salla/", "salla-en", "/services/stores/"),
    ("stores-zid", "/services/stores/zid/", "zid-en", "/services/stores/"),
    ("stores-shopify", "/services/stores/shopify/", "shopify-en", "/services/stores/"),
    ("stores-woocommerce", "/services/stores/woocommerce/", "woocommerce-en", "/services/stores/"),
    ("products", "/services/products/", "products-en", "/services/"),
    ("sectors", "/sectors/", "sectors-en", ""),
    ("sector-ecommerce", "/sectors/ecommerce/", "ecommerce-en", "/sectors/"),
    ("sector-health", "/sectors/health/", "health-en", "/sectors/"),
    ("sector-real-estate", "/sectors/real-estate/", "real-estate-en", "/sectors/"),
    ("sector-education", "/sectors/education/", "education-en", "/sectors/"),
    ("sector-tourism", "/sectors/tourism/", "tourism-en", "/sectors/"),
    ("sector-tech", "/sectors/tech/", "tech-en", "/sectors/"),
    ("sector-legal", "/sectors/legal/", "legal-en", "/sectors/"),
    ("sector-food", "/sectors/food/", "food-en", "/sectors/"),
    ("pricing", "/pricing/", "pricing-en", ""),
    ("results", "/results/", "results-en", ""),
    ("team", "/team/", "team-en", ""),
    ("blog", "/blog/", "blog-en", ""),
    ("about", "/about/", "about-us", ""),
    ("contact", "/contact/", "contact-us", ""),
    ("privacy-policy", "/privacy-policy/", "privacy", ""),
    ("terms", "/terms/", "terms-and-conditions", ""),
    ("thank-you", "/thank-you/", "thanks", ""),
]

ids = json.load(open(sys.argv[1]))
INDEX = set(json.load(open(HERE + "index-keys.json"))) if os.path.exists(HERE + "index-keys.json") else set()
out = []
ONLY = set(sys.argv[3].split(",")) if len(sys.argv) > 3 else None
for key, path, slug, parent in PLAN:
    if ONLY is not None and key not in ONLY:
        continue
    if path not in ids or not os.path.exists(HERE + "en/" + key + ".json"):
        print("skip", key)
        continue
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
        "index": key in INDEX,
    })
json.dump(out, open(sys.argv[2], "w", encoding="utf-8"), ensure_ascii=False)
print(len(out), "payloads")
