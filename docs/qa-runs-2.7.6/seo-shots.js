const { chromium } = require('/opt/node-tools/node_modules/playwright');
const out = process.argv[2], post = process.argv[3];
(async () => { const b = await chromium.launch();
for (const w of [1440, 390]) { const p = await (await b.newContext({ viewport: { width: w, height: 900 } })).newPage();
  await p.goto('http://127.0.0.1:8090/services/seo/egypt/', { waitUntil: 'networkidle' });
  await p.locator('section[data-screen-label="Hero"]').first().screenshot({ path: `${out}/egypt-hero-${w}.png` });
  const om = p.locator('section[data-screen-label="Other markets"]'); await om.scrollIntoViewIfNeeded(); await om.screenshot({ path: `${out}/egypt-other-markets-${w}.png` });
  await p.locator('footer').screenshot({ path: `${out}/footer-${w}.png` });
  await p.goto('http://127.0.0.1:8090' + post, { waitUntil: 'networkidle' });
  const l = p.locator('[data-art-seo-line]'); await l.scrollIntoViewIfNeeded(); await p.screenshot({ path: `${out}/article-line-${w}.png` });
  console.log(w, 'scrollX', await p.evaluate(() => document.documentElement.scrollWidth > innerWidth));
}
await b.close(); })();
