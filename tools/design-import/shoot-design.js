#!/usr/bin/env node
// Full-page screenshots of design pages (reference for visual comparison).
// node shoot-design.js <design-dir> <out-dir> <width> [key ...]
const fs = require('fs'), path = require('path'), http = require('http');
const { chromium } = require('playwright');
const [designDir, outDir, width, ...only] = process.argv.slice(2);
const cfg = JSON.parse(fs.readFileSync(path.join(__dirname, 'pages.config.json'), 'utf8'));
fs.mkdirSync(outDir, { recursive: true });
const srv = http.createServer((q, r) => { const p = path.join(designDir, decodeURIComponent(q.url.split('?')[0])); if (!fs.existsSync(p) || fs.statSync(p).isDirectory()) { r.writeHead(404); return r.end(); } r.end(fs.readFileSync(p)); });
srv.listen(0, async () => {
  const b = await chromium.launch();
  const ctx = await b.newContext({ viewport: { width: +width, height: 900 }, ignoreHTTPSErrors: true, reducedMotion: 'reduce' });
  await ctx.route('https://unpkg.com/**', r => { const u = r.request().url(); r.fulfill({ status: 200, contentType: 'application/javascript', body: fs.readFileSync(path.join(__dirname, 'vendor', u.includes('react-dom') ? 'react-dom.js' : u.includes('babel') ? 'babel.js' : 'react.js')) }); });
  for (const pg of cfg.pages) {
    if (only.length && !only.includes(pg.key)) continue;
    const p = await ctx.newPage();
    await p.goto(`http://127.0.0.1:${srv.address().port}/` + encodeURIComponent(pg.file), { waitUntil: 'networkidle' });
    await p.waitForTimeout(1200);
    await p.addStyleTag({ content: '#sh-preview-badge{display:none!important} *{animation-play-state:paused!important}' });
    await p.screenshot({ path: path.join(outDir, `${pg.key}-${width}.jpg`), fullPage: true, quality: 70, type: 'jpeg' });
    await p.close(); console.log(pg.key);
  }
  await b.close(); srv.close();
});
