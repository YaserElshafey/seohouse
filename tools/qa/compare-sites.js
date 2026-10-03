#!/usr/bin/env node
/**
 * URL-by-URL comparison of the live site and the new build (GET only, read-only on both).
 * Paths of the new build are compared after removing its prefix (/new).
 *
 * URLs: the live sitemap (all child sitemaps), the published-URL map
 * (content-pack/data/legacy-urls.json), links found on those live pages, and the new build's own
 * sitemap. For each path, on both sides: HTTP status (redirects not followed), title, meta
 * description, canonical, robots (meta + X-Robots-Tag), H1, Schema types, images (and without alt),
 * internal links, words of the main text, presence in the sitemap; plus text similarity and
 * whether the new build's internal links answer.
 *
 * Every difference is classified: same, intended (with the reason, tools/qa/compare-exceptions.json)
 * or to fix.
 *
 * Usage: node compare-sites.js --live https://seohouse.agency --new https://seohouse.agency/new --out dir
 *        [--mode review|production] [--label "..."]
 *   review: the new build is the hidden review copy (noindex expected, canonical may be absent).
 *   production: tags must be production-ready (indexable, canonical present and equal to live).
 */
const fs = require('fs');
const path = require('path');
const cheerio = require(require.resolve('cheerio', { paths: [path.join(__dirname, '../design-import/node_modules')] }));
const argv = process.argv.slice(2), opt = (n, d) => { const i = argv.indexOf('--' + n); return i >= 0 ? argv[i + 1] : d; };
const LIVE = opt('live').replace(/\/$/, ''), NEW = opt('new').replace(/\/$/, ''), OUT = opt('out'), MODE = opt('mode', 'review'), LABEL = opt('label', NEW);
const REPO = path.join(__dirname, '..', '..');
const EXC = JSON.parse(fs.readFileSync(path.join(__dirname, 'compare-exceptions.json'), 'utf8'));
fs.mkdirSync(OUT, { recursive: true });
const NEW_PREFIX = new URL(NEW + '/').pathname.replace(/\/$/, ''); // "/new" or ""

const norm = p => { try { p = decodeURIComponent(p); } catch (e) { /* keep */ } p = p.split('#')[0].split('?')[0]; return p.endsWith('/') || /\.\w{2,4}$/.test(p) ? p : p + '/'; };
const enc = p => p.split('/').map(s => encodeURIComponent(s)).join('/');
async function get(url, follow = false) {
  for (let i = 0; i < 3; i++) {
    try {
      const r = await fetch(url, { redirect: follow ? 'follow' : 'manual', headers: { 'User-Agent': 'SEOHouse-compare/1.0 (+review)' } });
      const body = (r.headers.get('content-type') || '').includes('html') || (r.headers.get('content-type') || '').includes('xml') ? await r.text() : '';
      return { status: r.status, location: r.headers.get('location') || '', xrobots: r.headers.get('x-robots-tag') || '', body };
    } catch (e) { if (i === 2) return { status: 0, error: e.message, body: '' }; }
  }
}
async function sitemap(base) {
  const out = new Set();
  for (const idx of ['/sitemap_index.xml', '/wp-sitemap.xml']) {
    const r = await get(base + idx, true);
    if (r.status !== 200 || !/<(sitemapindex|urlset)/.test(r.body)) continue;
    const locs = [...r.body.matchAll(/<loc>([^<]+)<\/loc>/g)].map(m => m[1].trim());
    for (const l of locs) {
      if (/\.xml$/.test(l)) {
        const c = await get(l, true);
        for (const m of c.body.matchAll(/<loc>([^<]+)<\/loc>/g)) out.add(m[1].trim());
      } else out.add(l);
    }
    if (out.size) return { found: idx, urls: out };
  }
  return { found: '', urls: out };
}
const pathOf = (u, base) => { const x = new URL(u, base + '/'); const pfx = new URL(base + '/').pathname.replace(/\/$/, ''); return norm(x.pathname.startsWith(pfx) ? x.pathname.slice(pfx.length) || '/' : x.pathname); };

function extract(html, base, res) {
  const $ = cheerio.load(html || '');
  const host = new URL(base + '/').host;
  const ld = [];
  $('script[type="application/ld+json"]').each((_, e) => { try { const j = JSON.parse($(e).text()); for (const n of (j['@graph'] || [].concat(j))) ld.push([].concat(n['@type'] || '?').join('/')); } catch (err) { ld.push('INVALID'); } });
  const canon = $('link[rel="canonical"]').attr('href') || '';
  // the main text block: the element with the most paragraphs/headings/lists as direct children
  // (live theme: .post-content; new theme: [data-art-body] or the page sections)
  let best = null, bestN = 0;
  $('body div, body article, body section, body main').each((_, e) => { const n = $(e).children('p,h2,h3,h4,ul,ol,table,figure,blockquote').length; if (n > bestN) { bestN = n; best = e; } });
  const main = $('main').length ? $('main') : $('body');
  const region = bestN >= 5 ? $(best) : main;
  const text = region.clone().find('script,style,nav,header,footer').remove().end().text().replace(/\s+/g, ' ').trim();
  // uploaded images on the page outside header/footer/navigation, by file name without size suffix
  const files = new Set();
  const fileOf = src => { const m = String(src || '').match(/\/uploads\/(?:\d{4}\/\d{2}\/)?([^/?#]+)$/); if (!m) return ''; let f = m[1]; try { f = decodeURIComponent(f); } catch (err) { /* keep */ } return f.replace(/-(?:\d+x\d+|scaled)(?=\.\w+$)/, '').replace(/\.\w+$/, ''); };
  // the page's own images: inside its main text block, plus its featured/social image
  const og = fileOf($('meta[property="og:image"]').attr('content'));
  if (og) files.add(og);
  (bestN >= 5 ? $(best) : $('main').length ? $('main') : $('body')).find('img').each((_, e) => {
    if ($(e).closest('header,footer,nav,[aria-hidden="true"]').length) return;
    const src = $(e).attr('src') || '';
    const m = src.match(/\/uploads\/(?:\d{4}\/\d{2}\/)?([^/?#]+)$/);
    if (m) { let f = m[1]; try { f = decodeURIComponent(f); } catch (err) { /* keep */ } files.add(f.replace(/-(?:\d+x\d+|scaled)(?=\.\w+$)/, '').replace(/\.\w+$/, '')); }
  });
  const links = new Set();
  $('body a[href]').each((_, a) => {
    const h = $(a).attr('href');
    if (!h || /^(mailto|tel|javascript|#)/.test(h)) return;
    try { const u = new URL(h, base + '/'); if (u.host === host && !/\/wp-(admin|json|content|login)/.test(u.pathname)) links.add(pathOf(u.href, base)); } catch (e) { /* skip */ }
  });
  const imgs = region.find('img');
  return {
    status: res.status, location: res.location ? pathOf(res.location, base) : '',
    title: $('title').first().text().trim(),
    description: $('meta[name="description"]').attr('content') || '',
    canonical: canon ? pathOf(canon, base) : '',
    canonicalAbs: canon,
    robots: [$('meta[name="robots"]').attr('content') || '', res.xrobots].filter(Boolean).join(' | '),
    h1: $('h1').map((_, e) => $(e).text().replace(/\s+/g, ' ').trim()).get(),
    schema: [...new Set(ld)].sort(),
    images: files.size, imageFiles: [...files].sort(),
    // missing alt: no alt attribute at all, or an empty alt on an image that is the only content of
    // a link (a decorative image next to its visible name correctly has alt="")
    imagesNoAlt: imgs.filter((_, e) => { const a = $(e).attr('alt'); if (a === undefined) return true; if (a.trim()) return false; const link = $(e).closest('a'); return link.length && !link.text().trim() && !link.attr('aria-label') && !link.closest('[aria-hidden="true"]').length; }).length,
    links: [...links].sort(), words: text ? text.split(' ').length : 0, text
  };
}
const words = t => new Set(t.toLowerCase().replace(/[^\p{L}\p{N}\s]/gu, ' ').split(/\s+/).filter(w => w.length > 2));
const similarity = (a, b) => { const A = words(a), B = words(b); if (!A.size && !B.size) return 1; let i = 0; for (const w of A) if (B.has(w)) i++; return +(i / Math.max(A.size, B.size)).toFixed(2); };
const exceptionFor = p => EXC.paths.find(e => new RegExp(e.match).test(p));
const isDesignPage = p => EXC.designPages.includes(p);

(async () => {
  console.log(`live ${LIVE}  ↔  new ${NEW}  (mode ${MODE})`);
  const liveMap = await sitemap(LIVE);
  const newMap = await sitemap(NEW);
  const livePaths = new Set([...liveMap.urls].map(u => pathOf(u, LIVE)));
  const newSitemapPaths = new Set([...newMap.urls].map(u => pathOf(u, NEW)));
  const legacy = JSON.parse(fs.readFileSync(path.join(REPO, 'wordpress/plugins/seohouse-core/content-pack/data/legacy-urls.json'), 'utf8'));
  const all = new Set([...livePaths, ...legacy.map(l => norm(l.path)), ...newSitemapPaths]);
  const live = {}, nw = {};
  // first pass: pages from sitemaps and the URL map; links found on live pages are added
  const queue = [...all];
  for (let i = 0; i < queue.length; i++) {
    const p = queue[i];
    if (EXC.skip.some(s => new RegExp(s).test(p))) continue;
    const r = await get(LIVE + enc(p));
    live[p] = extract(r.body, LIVE, r);
    if (r.status === 200) for (const l of live[p].links) if (!all.has(l) && !EXC.skip.some(s => new RegExp(s).test(l)) && !/\.\w{2,4}$/.test(l)) { all.add(l); queue.push(l); }
  }
  for (const p of all) {
    if (EXC.skip.some(s => new RegExp(s).test(p))) continue;
    const r = await get(NEW + enc(p));
    nw[p] = extract(r.body, NEW, r);
  }
  // internal links of the new build: do they answer?
  const newLinks = new Set(); for (const p in nw) if (nw[p].status === 200) for (const l of nw[p].links) newLinks.add(l);
  const linkStatus = {};
  for (const l of newLinks) { if (nw[l]) { linkStatus[l] = nw[l].status; continue; } if (/\.\w{2,4}$/.test(l) || EXC.skip.some(s => new RegExp(s).test(l))) continue; linkStatus[l] = (await get(NEW + enc(l))).status; }

  const rows = [];
  for (const p of [...Object.keys(live)].sort()) {
    const L = live[p], N = nw[p] || { status: 0 };
    const exc = exceptionFor(p);
    const design = isDesignPage(p);
    const issues = [], intended = [];
    const add = (ok, field, why) => { if (!ok) (why ? intended : issues).push(why ? `${field}: ${why}` : field); };
    // status
    if (L.status !== N.status || (L.location && L.location !== N.location)) {
      const newPage = L.status === 404 && N.status === 200 && design ? EXC.reasons.newPage : '';
      add(false, `الحالة ${L.status}${L.location ? '→' + L.location : ''} / ${N.status}${N.location ? '→' + N.location : ''}`, (exc && exc.status) || newPage);
    }
    if (L.status === 200 && N.status === 200) {
      const indexableLive = !/noindex/.test(L.robots);
      // robots
      if (MODE === 'review') add(/noindex/.test(N.robots), 'robots', /noindex/.test(N.robots) ? '' : '');
      if (MODE === 'review' && /noindex/.test(N.robots) && indexableLive) intended.push('robots: نسخة المراجعة محجوبة عن الفهرسة (noindex) حتى الإطلاق');
      if (MODE === 'production') add(/noindex/.test(N.robots) === !indexableLive, `robots (${N.robots || '—'} مقابل ${L.robots || '—'})`, exc && exc.robots ? exc.robots : '');
      // canonical
      const canonOk = N.canonical === L.canonical || (!L.canonical && !N.canonical);
      if (!canonOk) {
        if (MODE === 'review' && !N.canonical && /noindex/.test(N.robots)) intended.push('canonical: Rank Math لا يطبع canonical لصفحة noindex (يعود بعد رفع الحجب)');
        else add(false, `canonical ${L.canonical || '—'} / ${N.canonical || '—'}`, exc && exc.canonical ? exc.canonical : '');
      }
      if (MODE === 'production' && N.canonicalAbs && !N.canonicalAbs.startsWith(NEW + '/')) issues.push('canonical خارج الموقع: ' + N.canonicalAbs);
      // texts
      const why = design ? EXC.reasons.design : (exc && exc.content) || '';
      if (L.title !== N.title) add(false, 'العنوان', why || '');
      if (L.description !== N.description) add(false, 'الوصف', why || (N.description ? '' : ''));
      if (JSON.stringify(L.h1) !== JSON.stringify(N.h1)) add(false, 'H1', why);
      const sim = similarity(L.text, N.text);
      L.sim = sim;
      if (sim < (design ? 0 : EXC.minSimilarity)) add(false, `المحتوى (تشابه ${sim})`, why);
      if (JSON.stringify(L.schema) !== JSON.stringify(N.schema)) {
        // a migrated page (article) must keep every Schema type it has live (e.g. its FAQPage)
        const types = list => new Set(list.flatMap(t => t.split('/')));
        const lost = [...types(L.schema)].filter(t => !types(N.schema).has(t) && !design);
        if (lost.length) issues.push('Schema ناقصة: ' + lost.join(', '));
        else add(false, 'Schema', EXC.reasons.schema);
      }
      const missingImgs = (L.imageFiles || []).filter(f => !(N.imageFiles || []).includes(f));
      if (missingImgs.length) add(false, `صور الموقع الحالي غير موجودة في الجديد: ${missingImgs.join('، ')}`, why);
      if (N.imagesNoAlt > L.imagesNoAlt) add(false, `صور بلا نص بديل ${L.imagesNoAlt}/${N.imagesNoAlt}`, '');
      const broken = N.links.filter(l => linkStatus[l] && linkStatus[l] >= 400);
      if (broken.length) issues.push('روابط داخلية لا تعمل: ' + broken.join(' '));
      if (L.links.length !== N.links.length) add(false, `الروابط الداخلية ${L.links.length}/${N.links.length}`, why || EXC.reasons.links);
    }
    // sitemap
    const inLive = livePaths.has(p), inNew = newSitemapPaths.has(p);
    if (inLive !== inNew) {
      let why = exc && exc.sitemap ? exc.sitemap : '';
      if (MODE === 'review' && !newMap.found) why = 'نسخة المراجعة بلا خريطة (محجوبة) حتى الإطلاق';
      else if (inLive && !inNew && N.status >= 300 && N.status < 400) why = 'رابط محوّل لا يدخل الخريطة (الموقع الحالي يدرجه رغم أنه يحوّل)';
      else if (!inLive && inNew && N.status === 200) why = /\/blog\/category\//.test(p) ? 'قرار: Rank Math في البناء الجديد يدرج أرشيف التصنيفات (الموقع الحالي لا يدرجها)؛ يمكن إطفاؤه من Rank Math ← خريطة الموقع' : 'صفحة منشورة قابلة للفهرسة تُدرج في الخريطة (غير مدرجة في خريطة الموقع الحالي)';
      add(false, `خريطة الموقع ${inLive ? 'نعم' : 'لا'}/${inNew ? 'نعم' : 'لا'}`, why);
    }
    const verdict = issues.length ? 'يحتاج معالجة' : intended.length ? 'مختلف مقصود' : 'مطابق';
    rows.push({ path: p, verdict, issues, intended, live: { ...L, text: undefined, links: L.links.length }, new: { ...N, text: undefined, links: (N.links || []).length }, sitemap: { live: inLive, new: inNew }, similarity: L.sim ?? null, exception: exc ? exc.note : '' });
  }
  // URLs only on the new build
  for (const p of Object.keys(nw)) if (!live[p] && nw[p].status === 200) rows.push({ path: p, verdict: livePaths.has(p) ? 'مطابق' : 'جديد فقط', issues: [], intended: ['صفحة جديدة في التصميم (لا تقابلها صفحة في الموقع الحالي)'], live: { status: 0 }, new: { ...nw[p], text: undefined, links: nw[p].links.length }, sitemap: { live: false, new: newSitemapPaths.has(p) } });

  const count = v => rows.filter(r => r.verdict === v).length;
  const summary = { date: new Date().toISOString(), live: LIVE, new: NEW, mode: MODE, label: LABEL, liveSitemap: liveMap.found, liveSitemapUrls: livePaths.size, newSitemap: newMap.found || 'none', newSitemapUrls: newSitemapPaths.size, urls: rows.length, same: count('مطابق'), intended: count('مختلف مقصود'), toFix: count('يحتاج معالجة'), newOnly: count('جديد فقط'), brokenInternal: Object.entries(linkStatus).filter(([, s]) => s >= 400).map(([l, s]) => `${l} ${s}`) };
  fs.writeFileSync(path.join(OUT, 'compare.json'), JSON.stringify({ summary, rows }, null, 1));
  const csvq = v => '"' + String(v ?? '').replace(/"/g, '""') + '"';
  const head = ['path', 'verdict', 'live_status', 'new_status', 'live_title', 'new_title', 'live_description', 'new_description', 'live_canonical', 'new_canonical', 'live_robots', 'new_robots', 'live_h1', 'new_h1', 'live_schema', 'new_schema', 'live_images', 'new_images', 'live_links', 'new_links', 'live_words', 'new_words', 'similarity', 'live_sitemap', 'new_sitemap', 'to_fix', 'intended'];
  const csv = [head.join(',')].concat(rows.map(r => [r.path, r.verdict, r.live.status, r.new.status, r.live.title, r.new.title, r.live.description, r.new.description, r.live.canonical, r.new.canonical, r.live.robots, r.new.robots, (r.live.h1 || []).join(' / '), (r.new.h1 || []).join(' / '), (r.live.schema || []).join(' '), (r.new.schema || []).join(' '), r.live.images, r.new.images, r.live.links, r.new.links, r.live.words, r.new.words, r.similarity, r.sitemap.live, r.sitemap.new, r.issues.join(' ؛ '), r.intended.join(' ؛ ')].map(csvq).join(',')));
  fs.writeFileSync(path.join(OUT, 'compare.csv'), '﻿' + csv.join('\n') + '\n');
  let md = `# مقارنة الروابط: ${LIVE} ↔ ${LABEL}\n\nالتاريخ: ${summary.date.slice(0, 16).replace('T', ' ')} UTC — الوضع: ${MODE === 'review' ? 'نسخة مراجعة (محجوبة عن الفهرسة)' : 'وسوم الإنتاج'}\n\n`;
  md += `| | العدد |\n|---|---|\n| روابط مقارنة | ${summary.urls} |\n| مطابق | ${summary.same} |\n| مختلف مقصود | ${summary.intended} |\n| **يحتاج معالجة** | **${summary.toFix}** |\n| جديد في التصميم فقط | ${summary.newOnly} |\n| خريطة الموقع الحالي | ${summary.liveSitemap} (${summary.liveSitemapUrls}) |\n| خريطة الموقع الجديد | ${summary.newSitemap} (${summary.newSitemapUrls}) |\n| روابط داخلية لا تعمل في الجديد | ${summary.brokenInternal.length} |\n\n`;
  const fix = rows.filter(r => r.verdict === 'يحتاج معالجة');
  md += `## يحتاج معالجة (${fix.length})\n\n` + (fix.length ? '| الرابط | الحالي | الجديد | المشكلة |\n|---|---|---|---|\n' + fix.map(r => `| \`${r.path}\` | ${r.live.status} | ${r.new.status} | ${r.issues.join('؛ ')} |`).join('\n') : 'لا شيء.') + '\n\n';
  md += `## الاختلافات المقصودة\n\n| الرابط | السبب |\n|---|---|\n` + rows.filter(r => r.intended.length).map(r => `| \`${r.path}\` | ${[...new Set(r.intended)].join('؛ ')} |`).join('\n') + '\n\n';
  md += `## كل الروابط\n\n| الرابط | النتيجة | الحالة | العنوان مطابق | الوصف مطابق | canonical | robots (الجديد) | H1 مطابق | Schema (الجديد) | صور | روابط | تشابه النص | خريطة |\n|---|---|---|---|---|---|---|---|---|---|---|---|---|\n`;
  md += rows.map(r => `| \`${r.path}\` | ${r.verdict} | ${r.live.status}/${r.new.status} | ${r.live.title === r.new.title ? 'نعم' : 'لا'} | ${r.live.description === r.new.description ? 'نعم' : 'لا'} | ${r.live.canonical === r.new.canonical ? 'مطابق' : (r.new.canonical || '—')} | ${r.new.robots || '—'} | ${JSON.stringify(r.live.h1) === JSON.stringify(r.new.h1) ? 'نعم' : 'لا'} | ${(r.new.schema || []).join(' ')} | ${r.live.images ?? '—'}/${r.new.images ?? '—'} | ${r.live.links ?? '—'}/${r.new.links ?? '—'} | ${r.similarity ?? '—'} | ${r.sitemap.live ? '✓' : '—'}/${r.sitemap.new ? '✓' : '—'} |`).join('\n') + '\n';
  fs.writeFileSync(path.join(OUT, 'compare.md'), md);
  console.log(JSON.stringify(summary, null, 1));
})().catch(e => { console.error(e); process.exit(2); });
