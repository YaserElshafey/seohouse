#!/usr/bin/env node
/**
 * Full-site crawl of a local build:
 *  - starts from "/", the WordPress sitemap and (optionally) the legacy URL list;
 *  - follows every internal <a href> (menus, footer, cards, results lists, articles…);
 *  - records status, redirect target, robots, title, and which pages link to each URL;
 *  - checks every referenced image / script / stylesheet;
 *  - checks the sitemap: every entry answers 200 and is indexable; drafts are never listed;
 *  - checks that no link anywhere points to an unpublished record (--drafts file of paths).
 *
 * Usage: node crawl.js --wp http://127.0.0.1:8080 --out <dir> [--legacy content-pack/data/legacy-urls.json] [--drafts drafts.txt]
 */
const fs = require('fs');
const path = require('path');

const argv = process.argv.slice(2);
const opt = (n, d) => { const i = argv.indexOf('--' + n); return i >= 0 ? argv[i + 1] : d; };
const WP = opt('wp', 'http://127.0.0.1:8080').replace(/\/$/, '');
const OUT = opt('out', path.join(__dirname, 'out', 'crawl'));
fs.mkdirSync(OUT, { recursive: true });
const host = new URL(WP).host;

const norm = href => {
  try {
    const u = new URL(href, WP + '/');
    if (u.host !== host) return null;
    u.hash = '';
    u.pathname = encodeURI(decodeURI(u.pathname)); // one canonical percent-encoding (WP writes lowercase %xx)
    return u.toString();
  } catch (e) { return null; }
};
const pathOf = u => decodeURIComponent(new URL(u).pathname) + (new URL(u).search || '');
const skip = u => /\/wp-(admin|login|json)|xmlrpc\.php|[?&](replytocom|p=)|\/feed\/?$|\.(xml|xsl)$/.test(u);

async function get(url, follow = false) {
  const r = await fetch(url, { redirect: follow ? 'follow' : 'manual' });
  const ct = r.headers.get('content-type') || '';
  const body = /html|xml/.test(ct) ? await r.text() : '';
  return { status: r.status, location: r.headers.get('location') || '', ct, body };
}

(async () => {
  const pages = new Map();     // url -> {status, location, robots, title, from:Set}
  const assets = new Map();    // url -> {status, from:Set}
  const queue = [];
  const enqueue = (u, from) => {
    if (!u || skip(u)) return;
    if (!pages.has(u)) { pages.set(u, { from: new Set() }); queue.push(u); }
    if (from) pages.get(u).from.add(from);
  };

  // seeds: home, sitemap, legacy URLs
  enqueue(WP + '/', 'seed');
  const sitemapUrls = new Set();
  const smIndex = await get(WP + '/wp-sitemap.xml', true);
  for (const loc of [...smIndex.body.matchAll(/<loc>([^<]+)<\/loc>/g)].map(m => m[1])) {
    const sm = await get(loc, true);
    for (const u of [...sm.body.matchAll(/<loc>([^<]+)<\/loc>/g)].map(m => m[1])) { const n = norm(u); sitemapUrls.add(n); enqueue(n, 'sitemap'); }
  }
  const legacyFile = opt('legacy', '');
  const legacy = legacyFile ? JSON.parse(fs.readFileSync(legacyFile, 'utf8')) : [];
  for (const l of legacy) if (!/\/feed\/$/.test(l.path)) enqueue(norm(l.path.split('/').map(encodeURIComponent).join('/')), 'legacy');

  while (queue.length) {
    const u = queue.shift();
    const r = await get(u);
    const rec = pages.get(u);
    Object.assign(rec, { status: r.status, location: r.location ? decodeURIComponent(new URL(r.location, WP).pathname) : '' });
    if (r.status >= 300 && r.status < 400 && r.location) { enqueue(norm(r.location), u); continue; }
    if (!/html/.test(r.ct)) continue;
    rec.robots = ((r.body.match(/<meta name=['"]robots['"] content=['"]([^'"]*)['"]/) || [])[1] || '');
    rec.title = ((r.body.match(/<title>([\s\S]*?)<\/title>/) || [])[1] || '').trim();
    rec.canonical = ((r.body.match(/<link rel=['"]canonical['"] href=['"]([^'"]+)['"]/) || [])[1] || '');
    const html = r.body.replace(/<script[\s\S]*?<\/script>/g, m => (/type="application\/ld\+json"/.test(m) ? '' : m));
    for (const m of html.matchAll(/<a\s[^>]*href=["']([^"']+)["']/g)) {
      const href = m[1];
      if (/^(mailto:|tel:|javascript:|#)/.test(href)) continue;
      const n = norm(href);
      if (n && !/\/wp-content\//.test(n)) enqueue(n, u);
    }
    for (const m of html.matchAll(/<(?:img|script|source)\s[^>]*(?:src|srcset)=["']([^"']+)["']|<link\s[^>]*href=["']([^"']+)["'][^>]*>/g)) {
      const raw = (m[1] || m[2] || '').split(',').map(s => s.trim().split(/\s+/)[0]);
      for (const a of raw) {
        const n = norm(a);
        if (!n || !/\/wp-(content|includes)\//.test(n)) continue;
        if (!assets.has(n)) assets.set(n, { from: new Set() });
        assets.get(n).from.add(u);
      }
    }
  }
  for (const [a, rec] of assets) { const r = await fetch(a, { method: 'HEAD' }); rec.status = r.status; }

  // drafts (paths that must never be linked or listed)
  const drafts = (opt('drafts', '') ? fs.readFileSync(opt('drafts'), 'utf8').split('\n') : []).map(s => s.trim()).filter(Boolean);
  const draftHits = [];
  for (const [u, rec] of pages) {
    const p = pathOf(u);
    if (drafts.includes(p)) {
      const linkers = [...rec.from].filter(f => !['seed', 'legacy'].includes(f));
      if (linkers.length) draftHits.push({ draft: p, linkedFrom: linkers.map(f => f === 'sitemap' ? 'sitemap' : pathOf(f)) });
    }
  }

  const rows = [...pages].map(([u, r]) => ({
    path: pathOf(u), status: r.status, redirect: r.location || '', robots: r.robots || '', inSitemap: sitemapUrls.has(u),
    linkedFrom: [...r.from].filter(f => !['seed', 'sitemap', 'legacy'].includes(f)).length, seeds: [...r.from].filter(f => ['seed', 'sitemap', 'legacy'].includes(f)).join('+'),
    sample: [...r.from].filter(f => !['seed', 'sitemap', 'legacy'].includes(f)).slice(0, 2).map(pathOf).join(' | '), title: r.title || ''
  })).sort((a, b) => a.path.localeCompare(b.path));
  const csv = rs => [Object.keys(rs[0]).join(','), ...rs.map(r => Object.values(r).map(v => /[",\n]/.test(String(v)) ? `"${String(v).replace(/"/g, '""')}"` : v).join(','))].join('\n') + '\n';
  fs.writeFileSync(path.join(OUT, 'links.csv'), csv(rows));
  const assetRows = [...assets].map(([u, r]) => ({ asset: pathOf(u).split('?')[0], status: r.status, usedOn: r.from.size }));
  fs.writeFileSync(path.join(OUT, 'assets.csv'), csv(assetRows));

  const broken = rows.filter(r => r.status >= 400 && r.linkedFrom > 0);
  const sitemapBad = rows.filter(r => r.inSitemap && (r.status !== 200 || /noindex/.test(r.robots)));
  const indexableMissing = rows.filter(r => r.status === 200 && !/noindex/.test(r.robots) && !r.inSitemap && !r.path.includes('?'));
  const summary = {
    date: new Date().toISOString(), wp: WP,
    urls: rows.length, ok200: rows.filter(r => r.status === 200).length, redirects: rows.filter(r => r.status >= 300 && r.status < 400).length,
    notFound: rows.filter(r => r.status === 404).length,
    internalLinks: rows.reduce((n, r) => n + r.linkedFrom, 0),
    sitemapEntries: sitemapUrls.size, assets: assetRows.length, assetErrors: assetRows.filter(a => a.status >= 400),
    brokenLinks: broken.map(r => ({ path: r.path, status: r.status, from: r.sample })),
    sitemapProblems: sitemapBad.map(r => ({ path: r.path, status: r.status, robots: r.robots })),
    indexableNotInSitemap: indexableMissing.map(r => r.path),
    draftsLinkedOrListed: draftHits
  };
  fs.writeFileSync(path.join(OUT, 'summary.json'), JSON.stringify(summary, null, 2));
  console.log(JSON.stringify({ ...summary, assetErrors: summary.assetErrors.length }, null, 2));
})().catch(e => { console.error(e); process.exit(2); });
