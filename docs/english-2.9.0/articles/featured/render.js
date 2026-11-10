const { chromium } = require('playwright');
(async () => {
  const b = await chromium.launch();
  const p = await b.newPage({ viewport: { width: 1672, height: 941 }, deviceScaleFactor: 1 });
  await p.goto('file://' + __dirname + '/cost.html'); await p.evaluate(() => document.fonts.ready); await p.waitForTimeout(300);
  await p.screenshot({ path: 'cost.png' });
  const o = await p.evaluate(() => [...document.querySelectorAll('.card,.left,.tag,.logo')].map(e => { const r = e.getBoundingClientRect(); return e.className + ' ' + [r.left, r.top, r.right, r.bottom].map(Math.round).join(',') }));
  console.log(o.join('\n')); await b.close();
})();
