# English drafts for seohouse.agency — writer brief

SEO House is an SEO / web design / e-commerce agency (founded 2017, office in Alexandria, Egypt) serving
companies in Saudi Arabia, Egypt and the UAE. The site is Arabic; we are adding English versions of key
pages. Your English is a **draft the client's team will review**, so write publish-ready copy.

## Files (all in this folder)
- `ar/<key>.json` — the live Arabic page, exactly as stored on the site (source of truth).
- `types/<key>.json` — the field type of every value in `sections` (`text`, `textarea`, `true_false`,
  `image`, `page_link`, `url`, `email`, `select:<allowed keys>`, lists shown as one-row arrays).
- Write `en/<key>.json` — **the same JSON with the same structure**, human-readable text translated.
- Check your work: `python3 -I validate.py <key>` (must print `OK`). Fix everything it reports.

## What to change and what to keep
Translate only human-readable text: values of `text` / `textarea` fields and `title`, plus the `seo` block
(see below). Keep **exactly as they are**:
- every key, the order of sections and rows, the number of rows in every list (no added/removed rows);
- `acf_fc_layout`, `id`, `key`, `parent`, `path`, `template`, `status`, `lang`, `slug`;
- `true_false`, `image`, `page_link`, `url`, `email` values, numbers, and `select` values (they are internal keys);
- `sh_anchor` and any value that is an identifier/slug/CSS-like token, URL, email, phone number, icon name,
  hex colour, embed code or Latin brand/product name (Google, Shopify, Salla, Zid, WooCommerce, GA4…);
- empty strings stay empty; `null` stays `null`.
If a `text` value is only numbers/symbols (e.g. "432%", "+12.9K", "30"), keep it, but convert Arabic-Indic
digits to Western digits and "ألف" style units to "K" where it is a number label.

## The `seo` block
- `rank_math_title`: English SEO title, **≤ 60 characters, ending with ` | SEO House`**. Lead with the
  search phrase an English buyer types (e.g. "SEO Company in Saudi Arabia", "SEO Agency in the UAE",
  "Technical SEO Services", "Website Design Company"). Even if the Arabic one is empty, write one.
- `rank_math_description`: 140–155 characters, specific, with the main phrase and a reason to click. Write one even if the Arabic is empty.
- `sh_seo_title` / `sh_seo_description`: copy the two values above.
- `sh_crumb`: short breadcrumb label in English (e.g. "Saudi Arabia"), or "" if the Arabic is "".
- `sh_schema_service`: English service name if not empty (e.g. "Search engine optimization services").
- `rank_math_focus_keyword`: the main English phrase (lowercase).
- Keep `rank_math_robots`, `sh_seo_noindex`, `sh_schema_type` unchanged.
- Also translate the top-level `title` (the page name used in menus/breadcrumbs, short, e.g. "Saudi Arabia SEO").

## Style
- Natural, confident, plain B2B English — not a literal translation. Same meaning, same promises, same
  structure; **do not invent** services, numbers, client names, guarantees or claims. Keep every figure
  and fact exactly (percentages, counts, durations, years, client results).
- **US spelling** ("optimization", "analyze") — it matches what buyers search.
- Brand: **SEO House** (never "Seo House" or "SEOHouse"). "سيو" = SEO. "سيو هاوس" = SEO House.
- Country names: Saudi Arabia (KSA), Egypt, the UAE (United Arab Emirates); cities: Riyadh, Jeddah, Dammam, Cairo, Alexandria, Dubai, Abu Dhabi.
- Arabic platforms keep their English names: Salla, Zid, Shopify, WooCommerce, WordPress, Webflow.
- Keep lengths close to the Arabic so the layout holds: headings short, buttons 1–4 words
  ("Book a free consultation", "Learn more").
- Arrows: Arabic "←" after a link label means "forward" → in English use "→" (e.g. "Learn more →").
  Quotes «…» → “…”. Arabic punctuation (، ؛ ؟) → English punctuation.
- FAQ answers: complete, helpful sentences; questions phrased the way people search in English.
- Legal pages (privacy-policy, terms): translate **faithfully and completely**, clause by clause, no
  additions or omissions, formal legal English. (The client asked for a translation of the Arabic text.)
- Where the Arabic text is clearly about Arabic-language search behaviour, keep the meaning (the agency
  optimizes Arabic and English search); don't drop it.

When done, reply with: the files you wrote, the validate.py result for each, and a short list of anything
you were unsure about (≤ 8 bullets). Do not touch any other file.
