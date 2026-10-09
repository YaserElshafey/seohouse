#!/usr/bin/env bash
# leave the old site's sitemap in Rank Math's file cache, as the move did: an address this site does not have
set -e
curl -s -o /dev/null http://127.0.0.1:8094/sitemap_index.xml; curl -s -o /dev/null http://127.0.0.1:8094/page-sitemap.xml
f=$(grep -l "/about/" /srv/shwplive/wp-content/uploads/rank-math/*.xml | head -1)
python3 - "$f" <<'PY'
import sys
p=sys.argv[1]; s=open(p).read()
s=s.replace('<url>', '<url>\n\t\t<loc>http://127.0.0.1:8094/services/seo/stores-seo/</loc>\n\t\t<lastmod>2026-05-26T10:00:00+00:00</lastmod>\n\t</url>\n\t<url>', 1)
open(p,'w').write(s)
PY
curl -s http://127.0.0.1:8094/page-sitemap.xml | grep -c "stores-seo"
