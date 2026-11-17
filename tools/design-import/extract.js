#!/usr/bin/env node
/**
 * Step 1 — render every approved design page in Chromium with the design
 * runtime and save what the designer saw:
 *   extract/<key>.json = { key, file, route, head:{title,description,robots,canonical,jsonld[]},
 *                          styles (helmet <style> text, original source), hover (class → rule),
 *                          html (rendered page root), script (design data script) }
 *
 * Usage: node extract.js <design-dir> [key ...]
 * The preview runtime pulls React from unpkg; those requests are served from ./vendor.
 */
const fs = require('fs');
const path = require('path');
const http = require('http');
const cheerio = require('cheerio');
const { chromium } = require('playwright');

const designDir = path.resolve(process.argv[2] || '');
if (!designDir || !fs.existsSync(path.join(designDir, 'support.js'))) {
  console.error('Usage: node extract.js <design-dir-containing-support.js> [key ...]');
  process.exit(1);
}
const only = process.argv.slice(3);
const cfg = JSON.parse(fs.readFileSync(path.join(__dirname, 'pages.config.json'), 'utf8'));
const outDir = path.join(__dirname, 'extract');
fs.mkdirSync(outDir, { recursive: true });

const MIME = { '.html': 'text/html', '.js': 'application/javascript', '.css': 'text/css', '.png': 'image/png', '.jpg': 'image/jpeg', '.svg': 'image/svg+xml', '.json': 'application/json' };
function serve() {
  return new Promise(res => {
    const srv = http.createServer((req, resp) => {
      const p = path.join(designDir, decodeURIComponent(req.url.split('?')[0]));
      if (!p.startsWith(designDir) || !fs.existsSync(p) || fs.statSync(p).isDirectory()) { resp.writeHead(404); return resp.end(); }
      resp.writeHead(200, { 'content-type': MIME[path.extname(p)] || 'application/octet-stream' });
      fs.createReadStream(p).pipe(resp);
    });
    srv.listen(0, '127.0.0.1', () => res(srv));
  });
}

function sourceHead(src) {
  const $ = cheerio.load(src, { decodeEntities: false });
  const h = $('helmet');
  const jsonld = [];
  h.find('script[type="application/ld+json"]').each((_, el) => { try { jsonld.push(JSON.parse($(el).html())); } catch (e) { jsonld.push({ _invalid: $(el).html() }); } });
  const comments = [];
  h.contents().each((_, n) => { if (n.type === 'comment') comments.push(n.data.trim()); });
  return {
    head: {
      title: h.find('title').text().trim(),
      description: h.find('meta[name="description"]').attr('content') || '',
      robots: h.find('meta[name="robots"]').attr('content') || '',
      canonical: h.find('link[rel="canonical"]').attr('href') || '',
      jsonld,
      devComments: comments
    },
    styles: h.find('style').map((_, el) => $(el).html()).get().join('\n'),
    script: $('script[data-dc-script]').html() || ''
  };
}

(async () => {
  const srv = await serve();
  const base = `http://127.0.0.1:${srv.address().port}/`;
  const browser = await chromium.launch();
  const ctx = await browser.newContext({ viewport: { width: 1440, height: 900 }, ignoreHTTPSErrors: true });
  await ctx.route('https://unpkg.com/**', r => {
    const u = r.request().url();
    const f = u.includes('react-dom') ? 'react-dom.js' : u.includes('babel') ? 'babel.js' : 'react.js';
    r.fulfill({ status: 200, contentType: 'application/javascript', body: fs.readFileSync(path.join(__dirname, 'vendor', f)), headers: { 'access-control-allow-origin': '*' } });
  });
  await ctx.route('https://fonts.googleapis.com/**', r => r.fulfill({ status: 200, contentType: 'text/css', body: '' }));
  await ctx.route('https://fonts.gstatic.com/**', r => r.fulfill({ status: 404, body: '' }));

  for (const pg of cfg.pages) {
    if (only.length && !only.includes(pg.key)) continue;
    const src = fs.readFileSync(path.join(designDir, pg.file), 'utf8');
    const meta = sourceHead(src);
    const page = await ctx.newPage();
    const errors = [];
    page.on('pageerror', e => errors.push(String(e)));
    await page.goto(base + encodeURIComponent(pg.file), { waitUntil: 'networkidle' });
    await page.waitForSelector('#dc-root .sc-host', { timeout: 20000 });
    await page.waitForTimeout(800);
    const data = await page.evaluate(() => {
      const host = document.querySelector('#dc-root .sc-host');
      const hover = {};
      for (const s of document.styleSheets) {
        let rules; try { rules = s.cssRules; } catch (e) { continue; }
        for (const r of rules) {
          const m = r.selectorText && r.selectorText.match(/^\.(scp[0-9a-z]+):hover$/);
          if (m) hover[m[1]] = r.style.cssText;
        }
      }
      return { html: host.innerHTML, hover };
    });
    await page.close();
    const out = { ...pg, ...meta, hover: data.hover, html: data.html, errors };
    fs.writeFileSync(path.join(outDir, pg.key + '.json'), JSON.stringify(out, null, 1));
    console.log(`${pg.key.padEnd(24)} ${String(data.html.length).padStart(7)} bytes  hover:${Object.keys(data.hover).length}${errors.length ? '  ERR ' + errors[0] : ''}`);
  }
  await browser.close();
  srv.close();
})().catch(e => { console.error(e); process.exit(1); });
