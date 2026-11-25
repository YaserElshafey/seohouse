# Local pair for «نقل عناوين وأوصاف SEO»

A local copy of the main site at the root and the review copy in `/new/`, served by `router.php`
(`php -S 127.0.0.1:8096 -t /srv/shwpseo router.php`).

- `read-live.py` — reads, with GET only, the `<title>` and meta description that seohouse.agency shows
  for every published path (`docs/legacy-url-map.csv`) → `live.json` (read 3 October 2026).
- `main-site-types.php` — mu-plugin of the main-site copy: its `case_study` (/results/) and `sector`
  (/sectors/) types, with archives, as on seohouse.agency.
- `seed-main.php` — `wp eval-file` on the main-site copy (Rank Math active, permalinks `/blog/%postname%/`):
  pages, results and sectors on the same paths, a value of the form "X - سيو هاوس" produced by a Rank
  Math template and any other value stored in the page's own field, so the copy shows exactly what
  `live.json` holds. Test-only additions: `/contact/thank-you/` (same last slug as `/thank-you/` on
  /new/, other path), a noindex,nofollow robots field on `/pricing/` and a custom canonical on `/services/`.
- `independent-check.py <base> <label>` — compares `live.json` with what another site shows, without the plugin.
