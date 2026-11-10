"""build_items.py <out.json> [keys...]: content/item payloads from en/<type>-<id>.json (all items by default)."""
import glob
import json
import os
import sys

HERE = os.path.dirname(os.path.abspath(__file__)) + "/"
keys = sys.argv[2:] or sorted(os.path.basename(f)[:-5] for f in glob.glob(HERE + "en/*.json"))
order = {"team_member": 0, "case_study": 1, "post": 2}  # team members first: articles point at them
out = []
for k in sorted(keys, key=lambda x: (order[x.split("-")[0]], x)):
    e = json.load(open(HERE + "en/" + k + ".json", encoding="utf-8"))
    if k.startswith("post-"):
        # right-to-left layout details of the Arabic article, mirrored for English
        e["content"] = e["content"].replace("has-text-align-right", "has-text-align-left").replace("border-right:", "border-left:")
        e["content"] = e["content"].replace("?hl=ar", "?hl=en").replace("&amp;hl=ar", "&amp;hl=en").replace("&hl=ar", "&hl=en")  # Google docs in English
    out.append({
        "key": k, "source": int(k.split("-")[-1]), "slug": e["slug"], "title": e["title"],
        "content": e.get("content", ""), "excerpt": e.get("excerpt", ""), "fields": e.get("fields", {}),
        "seo": e.get("seo", {}), "index": True,
    })
json.dump(out, open(sys.argv[1], "w", encoding="utf-8"), ensure_ascii=False)
print(len(out), "items")
