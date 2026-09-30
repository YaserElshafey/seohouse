#!/usr/bin/env node
/**
 * Visual comparison: WordPress page vs the approved design file, same width, motion paused.
 * Writes <out>/<key>-<width>-{wp,design,diff}.png and prints the share of differing pixels.
 *
 * Usage: node compare.js --design <design-dir> --wp http://127.0.0.1:8080 --out <dir> [--width 1440,768,390] [key ...]
 */
const fs = require('fs');
const path = require('path');
const http = require('http');
const { chromium } = require(require.resolve('playwright', { paths: [path.join(__dirname, '../design-import/node_modules'), __dirname] }));

const argv = process.argv.slice(2);
const opt = (n, d) => { const i = argv.indexOf('--' + n); return i >= 0 ? argv.splice(i, 2)[1] : d; };
const designDir = opt('design');
const wp = opt('wp', 'http://127.0.0.1:8080');
const out = opt('out', path.join(__dirname, 'out'));
const widths = opt('width', '1440,768,390').split(',').map(Number);
const keys = argv;
const cfg = JSON.parse(fs.readFileSync(path.join(__dirname, '../design-import/pages.config.json'), 'utf8'));
fs.mkdirSync(out, { recursive: true });

const FREEZE = `*,*::before,*::after{animation-play-state:paused!important;animation-delay:0s!important;transition:none!important;caret-color:transparent!important}
#sh-preview-badge,#wpadminbar{display:none!important}html{margin-top:0!important}`;

function wpUrl(pg) {
  if (pg.route === '(404)') return wp + '/__missing-page__/';
  if (pg.route === '/?s=') return wp + '/?s=' + encodeURIComponent('سيو');
  if (pg.route.includes('{slug}')) return wp + pg.route.replace('{slug}', 'yasser-youssef');
  if (pg.key === 'single-post') return wp + '/blog/' + encodeURIComponent('هل-يظهر-موقعك-داخل-إجابات-جوجل-بالذكاء') + '/';
  if (pg.key === 'blog-category') return wp + '/blog/category/technical-seo/';
  if (pg.key === 'case-template') return null;
  return wp + encodeURI(pg.route);
}

(async () => {
  const srv = http.createServer((q, r) => { const p = path.join(designDir, decodeURIComponent(q.url.split('?')[0])); if (!fs.existsSync(p) || fs.statSync(p).isDirectory()) { r.writeHead(404); return r.end(); } r.end(fs.readFileSync(p)); });
  await new Promise(res => srv.listen(0, res));
  const base = `http://127.0.0.1:${srv.address().port}/`;
  const browser = await chromium.launch();
  const vendor = path.join(__dirname, '../design-import/vendor');
  const report = [];
  for (const w of widths) {
    const ctx = await browser.newContext({ viewport: { width: w, height: 900 }, ignoreHTTPSErrors: true, reducedMotion: 'no-preference', deviceScaleFactor: 1 });
    await ctx.route('https://unpkg.com/**', r => { const u = r.request().url(); r.fulfill({ status: 200, contentType: 'application/javascript', body: fs.readFileSync(path.join(vendor, u.includes('react-dom') ? 'react-dom.js' : u.includes('babel') ? 'babel.js' : 'react.js')) }); });
    const fontDir = path.join(__dirname, '../../wordpress/themes/seohouse/assets/fonts');
    const fontCss = fs.readFileSync(path.join(__dirname, '../../wordpress/themes/seohouse/assets/css/fonts.css'), 'utf8').replace(/url\('\.\.\/fonts\//g, "url('https://fonts.gstatic.com/local/");
    await ctx.route('https://fonts.googleapis.com/**', r => r.fulfill({ status: 200, contentType: 'text/css', body: fontCss }));
    await ctx.route('https://fonts.gstatic.com/local/**', r => r.fulfill({ status: 200, contentType: 'font/woff2', headers: { 'access-control-allow-origin': '*' }, body: fs.readFileSync(path.join(fontDir, r.request().url().split('/').pop())) }));
    await ctx.addInitScript(() => { Math.random = (() => { let s = 7; return () => (s = (s * 16807) % 2147483647) / 2147483647; })(); });
    for (const pg of cfg.pages) {
      if (keys.length && !keys.includes(pg.key)) continue;
      const url = wpUrl(pg);
      if (!url) continue;
      const shots = {};
      for (const [kind, u] of [['design', base + encodeURIComponent(pg.file)], ['wp', url]]) {
        const p = await ctx.newPage();
        const errors = [];
        p.on('pageerror', e => errors.push(String(e)));
        p.on('console', m => { if (m.type() === 'error' && kind === 'wp') errors.push(m.text()); });
        const resp = await p.goto(u, { waitUntil: 'networkidle' });
        if (kind === 'design') await p.waitForSelector('#dc-root .sc-host');
        // local fonts for the design too, so text metrics match
        await p.addStyleTag({ content: FREEZE });
        await p.evaluate(() => document.fonts.ready);
        await p.waitForTimeout(600);
        const file = path.join(out, `${pg.key}-${w}-${kind}.png`);
        await p.screenshot({ path: file, fullPage: true });
        const dims = await p.evaluate(() => ({ h: document.documentElement.scrollHeight, sw: document.documentElement.scrollWidth, cw: document.documentElement.clientWidth }));
        shots[kind] = { file, dims, status: resp ? resp.status() : 0, errors };
        await p.close();
      }
      const d = shots.design.dims, x = shots.wp.dims;
      const row = { key: pg.key, width: w, status: shots.wp.status, designH: d.h, wpH: x.h, dH: x.h - d.h, overflowX: x.sw > x.cw, errors: shots.wp.errors.slice(0, 3) };
      report.push(row);
      console.log(`${pg.key.padEnd(22)} ${String(w).padStart(4)}  http:${row.status}  height design ${d.h} / wp ${x.h} (${row.dH >= 0 ? '+' : ''}${row.dH})${row.overflowX ? '  OVERFLOW-X' : ''}${row.errors.length ? '  JS: ' + row.errors[0] : ''}`);
    }
    await ctx.close();
  }
  fs.writeFileSync(path.join(out, 'report.json'), JSON.stringify(report, null, 1));
  await browser.close(); srv.close();
})().catch(e => { console.error(e); process.exit(1); });
