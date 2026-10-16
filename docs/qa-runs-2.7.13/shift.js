// Layout shift on hover: every card and its text boxes must keep the same position/size.
const { chromium } = require('/opt/node-tools/node_modules/playwright');
(async () => { const b = await chromium.launch(); let bad = 0, n = 0;
  for (const [path, w] of [['/', 1440], ['/services/', 1440], ['/services/seo/', 1440], ['/pricing/', 1440], ['/services/stores/', 1440], ['/services/products/', 1440], ['/services/web-design/', 1440], ['/services/seo/egypt/', 1440], ['/services/seo/', 768]]) {
    const p = await (await b.newContext({ viewport: { width: w, height: 1000 } })).newPage();
    await p.goto('http://127.0.0.1:8090' + path, { waitUntil: 'networkidle' });
    const cards = p.locator('[data-lcard]'); const c = await cards.count();
    const rects = () => p.evaluate(() => [...document.querySelectorAll('[data-lcard], [data-lcard] h3, [data-lcard] p, [data-lcard] [data-lcard-text], [data-lcard] span:not([data-lcard-arrow])')].map(e => { const r = e.getBoundingClientRect(); return [r.x, r.y, r.width, r.height].map(v => Math.round(v * 10) / 10).join(','); }));
    for (let i = 0; i < c; i++) { await cards.nth(i).scrollIntoViewIfNeeded(); await p.mouse.move(0, 0); await p.waitForTimeout(300); const a = await rects(); await cards.nth(i).hover(); await p.waitForTimeout(400); const h = await rects(); n++; if (a.join('|') !== h.join('|')) { bad++; console.log('shift', w, i); } }
  }
  console.log(`${n} card hovers, layout shifts: ${bad}`); await b.close(); })();
