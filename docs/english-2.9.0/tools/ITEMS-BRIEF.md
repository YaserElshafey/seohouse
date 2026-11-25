# English versions of articles, case studies and team members — writer brief

Same client and style as ../BRIEF.md (read it: tone, US spelling, "SEO House", keep every fact and
number, no invented claims). The English website already exists under https://seohouse.agency/en/ —
read one or two files in ../en/ (e.g. ../en/seo.json) for terminology.

## Files (this folder)
- `ar/<type>-<id>.json` — the live Arabic item. Translate into `en/<type>-<id>.json` with this shape:
  ```json
  {"title": "...", "slug": "...", "content": "...", "excerpt": "...", "fields": {...},
   "seo": {"rank_math_title": "...", "rank_math_description": "...", "rank_math_focus_keyword": "..."}}
  ```
- Check with `python3 -I validate_item.py <type>-<id>` until it prints `OK`.

## slug
- `post` and `team_member`: the Arabic slug + `-en` (e.g. `fix-404-not-found-en`, `yasser-youssef-en`).
  (The public English address mirrors the Arabic one; this slug is internal.)
- `case_study`: a short English slug describing the case, e.g. `education-training-seo-case-study`.

## content (articles: WordPress block markup)
- Keep every block comment `<!-- wp:... -->` / `<!-- /wp:... -->` exactly, in the same order, and every HTML
  tag and attribute, line breaks included. Translate only human text: text between tags, `alt=""` and
  `title=""` attributes, and text inside block JSON attributes only when it is visible text (e.g. a
  caption or button text) — never keys, IDs, class names, anchors, sizes or URLs.
- Internal links to pages of the site: `https://seohouse.agency/<path>` → `https://seohouse.agency/en/<path>`
  (every page, article and team profile has an English version at the same path under /en/). Links to
  `/wp-content/…` files, other websites, `#anchors` and `mailto:` stay unchanged.
- Headings and lists keep their structure. Arabic-specific examples (e.g. an Arabic search query) stay
  meaningful: keep the Arabic query in quotes and add its English meaning, e.g. «شركة سيو» (“SEO company”).

## fields
Translate text values (textarea/text); keep IDs, numbers, booleans, URLs, select keys, image IDs,
`author`, `author_member`, `related_pages`, `service_page`, `reading`, `filter`, `client_logo`, `screenshot`,
`linkedin`. Team: `role`, `experience`, `specialties[].label`, `bio`, `links[].label` in English.
Case studies: every visible text (summary, labels, steps, metrics labels/units, gallery alt/caption,
CTA…). `review_notes` is an internal note: translate it too.

## title
- Team members: the person's name in Latin letters as in their slug, e.g. `yasser-youssef` → "Yasser Youssef",
  `mennatallah-said` → "Mennatallah Said", `heba-geneedy` → "Heba Geneedy".
- Articles and case studies: a natural English headline.

## seo
`rank_math_title` ≤ 60 characters ending " | SEO House"; `rank_math_description` 140–155 characters;
`rank_math_focus_keyword` lowercase. (Team members: "<Name> – <Role> | SEO House" if it fits.)

Reply with the files written, the validate result for each, and ≤ 6 bullets of uncertainties.
