"""menus_en.py <export.json> <out.json>: English menu payloads from the exported Arabic menus.

Labels and descriptions are translated from the table below (an unknown Arabic text stops the script,
so a menu changed on the site is noticed). Items whose page has no English version are dropped by
Core on import; a column (custom "#" item) that ends up empty is dropped here when all its children
point at pages listed as untranslated.
"""
import json
import sys

T = {
    "الرئيسية": "Home", "الخدمات": "Services", "جميع الخدمات": "All services",
    "تحسين محركات البحث": "SEO", "ظهور صفحات موقعك في نتائج البحث": "Get your pages seen in search results",
    "تصميم المواقع": "Website design", "مواقع شركات سريعة وسهلة الإدارة": "Fast, easy-to-manage company websites",
    "تصميم المتاجر": "Online store design", "متاجر على سلة وزد وشوبيفاي وووكومرس": "Stores on Salla, Zid, Shopify and WooCommerce",
    "رفع المنتجات": "Product upload", "إدخال المنتجات وتنظيم بياناتها": "Product entry and data organization",
    "نتائج الأعمال": "Results", "عن الشركة": "About us", "فريق العمل": "Team", "القطاعات": "Sectors",
    "جميع القطاعات": "All sectors", "التجارة الإلكترونية": "E-commerce", "الصحة والطب": "Health & medical",
    "العقارات والبناء": "Real estate & construction", "التعليم والتدريب": "Education & training",
    "السياحة والسفر": "Tourism & travel", "التقنية والبرمجيات": "Technology & software",
    "القانون والاستشارات": "Legal & consulting", "الأغذية والمطاعم": "Food & restaurants",
    "المدونة": "Blog", "الأسعار": "Pricing", "اتصل بنا": "Contact us",
    "السيو": "SEO", "السيو التقني": "Technical SEO", "السيو الداخلي": "On-page SEO", "كتابة المحتوى": "Content writing",
    "السيو الخارجي": "Off-page SEO", "الاستشارات وتحليل الأداء": "Consulting & analytics",
    "المواقع والمتاجر": "Websites & stores", "رفع وإدارة المنتجات": "Product upload & management", "كل الخدمات": "All services",
    "الأسواق والقطاعات": "Markets", "السعودية": "Saudi Arabia", "مصر": "Egypt", "الإمارات": "UAE",
    "الشركة": "Company", "عن سيو هاوس": "About SEO House", "النتائج": "Results",
    "سياسة الخصوصية": "Privacy policy", "الشروط والأحكام": "Terms and conditions", "الشروط": "Terms",
    "": "",
}
NAMES = {"primary": "Primary (English)", "footer": "Footer (English)", "legal": "Legal (English)"}


def tr(s):
    s = (s or "").strip()
    if s not in T:
        sys.exit("no English for menu text: %r" % s)
    return T[s]


def conv(items):
    out = []
    for it in items:
        row = {"label": tr(it["label"]), "desc": tr(it.get("desc", "")), "icon": it.get("icon", ""),
               "all_label": tr(it.get("all_label", "")), "layout": it.get("layout", ""),
               "children": conv(it.get("children", []))}
        url = it.get("url", "#")
        path = url.replace("https://seohouse.agency", "") if url.startswith("https://seohouse.agency") else None
        if it.get("page"):
            row["page"] = it["page"]
        elif path is not None and path in IDS:
            row["page"] = IDS[path]  # Core links the English version of that page
        elif path is not None:
            continue  # a site page without an English version: left out of the English menu
        else:
            row["url"] = url
        if "url" in row and row["url"] == "#" and not row["children"]:
            continue  # an empty column
        out.append(row)
    return out


IDS = json.load(open("ids-live.json"))
exp = json.load(open(sys.argv[1], encoding="utf-8"))
payloads = [{"location": loc, "name": NAMES.get(loc, loc + " (English)"), "items": conv(m["items"])}
            for loc, m in exp.items() if m["items"]]
json.dump(payloads, open(sys.argv[2], "w", encoding="utf-8"), ensure_ascii=False)
print(len(payloads), "menus:", [p["location"] for p in payloads])
