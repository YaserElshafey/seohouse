# Local copy of seohouse.agency after the move from /new/

- `make-live.sh` — root site on 127.0.0.1:8094 with the /new/ database; Core 2.5.0 and theme 2.5.0
  extracted OVER the old main-site theme folder (`wp-theme/seohouse`), as All-in-One WP Migration
  does (nothing removed); the 2.5.0 update; privacy page published with empty fields on
  /privacy-policy/ (not the page the setup created), terms published with the 2.5.0 draft.
  The redirect list is emptied after it (`update_field('sh_redirects', [], 'option')`), as on the site.
- `reset-live.sh` — back to that state (front-page.php renamed front-page.php.old, as on the server).
- `stale-cache.sh` — leaves an old-site address in Rank Math's sitemap file cache.

Runs: `tools/qa/live-update-test.js`, `tools/qa/theme-leftovers-test.js`, `tools/qa/site-crawl.py`.
