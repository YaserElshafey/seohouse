# English SEO round — geo-targeted titles + schema (10 Nov 2026)

## Why
Global head terms ("education seo", "technical seo services") are out of reach for a regional agency. The English
pages now target the same terms **with the market**: Saudi Arabia, the UAE and Egypt (Salla and Zid: Saudi Arabia only;
the SEO country pages keep their single country). Before this round the English page bodies did not mention any market.

## What changed (live)
- 29 English pages: Rank Math title, meta description and focus keyword (`geo_titles.py` → `geo-seo-payload.json`,
  previous values in `geo-seo-before.json`). Titles ≤ 60 characters, descriptions ≤ 160.
- 26 English pages: hero H1 now names the markets, and one sentence about the markets was added to the hero text
  (previous values in `geo-hero-before.json`).
- Core 2.9.23–2.9.24 (schema / canonical):
  - English home: canonical and WebPage `@id` were the Arabic home (`/`), now `/en/`.
  - Organization on English pages: name «SEO House», Arabic name as alternateName.
  - areaServed: «the UAE» was not recognised in the English markets option, so the UAE was missing on every English
    page; country names on English pages are now the standard ones (Saudi Arabia, Egypt, United Arab Emirates).
  - Service: every service and industry page has areaServed (all markets; the country pages their own; Salla/Zid SA).
  - JSON-LD strings are plain text (titles were HTML-escaped: «&amp;»).
  - New route `POST seohouse/v1/content/seo` {items: [{id, title, description, focus}], dry_run}: updates Rank Math
    and the fallback SEO fields of existing pages without touching their content.

## Checked
Indexing (all English pages index,follow, in the sitemap), hreflang (en/ar/x-default on every page), canonicals,
no English links to missing pages, no orphan pages. Visual check of the longer H1s on desktop and mobile.

## Rollback
`content/seo` with the `before` values from `geo-seo-before.json`; `content/section` (section hero) with
`geo-hero-before.json`.
