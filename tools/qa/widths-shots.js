#!/usr/bin/env node
/**
 * Screenshots of pages at the review widths (1440, 768, 390) plus a horizontal-overflow check
 * and the H1 box (to catch a long heading cut or running off screen).
 * Usage: node widths-shots.js --base http://127.0.0.1:8097/new --out dir --tag before /services/seo/egypt/ ...
 */
const path = require('path');
const fs = require('fs');
const { chromium } = require(require.resolve('playwright', { paths: [path.join(__dirname, '../design-import/node_modules')] }));
const argv = process.argv.slice(2), opt = (n, d) => { const i = argv.indexOf('--' + n); return i >= 0 ? argv.splice(i, 2)[1] : d; };
const BASE = opt('base').replace(/\/$/, ''), OUT = opt('out'), TAG = opt('tag', 'shot'), FULL = opt('full', '0') === '1';
fs.mkdirSync(OUT, { recursive: true });
(async () => {
  const b = await chromium.launch();
  const report = [];
  for (const w of [1440, 768, 390]) {
    const p = await b.newPage({ viewport: { width: w, height: 900 } });
    for (const u of argv) {
      await p.goto(BASE + u, { waitUntil: 'networkidle' });
      const m = await p.evaluate(() => {
        const h = document.querySelector('h1'); const r = h ? h.getBoundingClientRect() : null;
        return { overflow: document.documentElement.scrollWidth - window.innerWidth, h1: h ? h.textContent.trim().replace(/\s+/g, ' ') : '', h1Box: r ? [Math.round(r.left), Math.round(r.right), Math.round(r.height)] : null,
          h1Clipped: h ? h.scrollWidth > h.clientWidth + 1 : false };
      });
      const name = `${TAG}-${u.replace(/^\/|\/$/g, '').replace(/\//g, '_') || 'home'}-${w}`;
      await p.screenshot({ path: path.join(OUT, name + '.png'), fullPage: FULL });
      report.push({ url: u, width: w, ...m, ok: m.overflow <= 0 && !m.h1Clipped && (!m.h1Box || (m.h1Box[0] >= 0 && m.h1Box[1] <= w)) });
      console.log(`${report.at(-1).ok ? 'PASS' : 'FAIL'}  ${w}px ${u}  overflow=${m.overflow} h1="${m.h1}" box=${JSON.stringify(m.h1Box)}`);
    }
    await p.close();
  }
  await b.close();
  fs.writeFileSync(path.join(OUT, `${TAG}-widths.json`), JSON.stringify(report, null, 1));
  process.exit(report.every(r => r.ok) ? 0 : 1);
})();
