// Rest + hover shots for the cards of one section. Usage: node hov.js <outdir> <tag> <path> <label> [width] [index]
const { chromium } = require('/opt/node-tools/node_modules/playwright');
const [out, tag, path, label, w = 1440, idx = 0] = process.argv.slice(2);
(async () => { const b = await chromium.launch(); const p = await (await b.newContext({ viewport: { width: +w, height: 1000 }, reducedMotion: 'reduce' })).newPage();
  await p.goto('http://127.0.0.1:8090' + path, { waitUntil: 'networkidle' });
  const s = p.locator(`section[data-screen-label="${label}"]`).first(); await s.scrollIntoViewIfNeeded(); await p.mouse.move(0, 0); await p.waitForTimeout(300);
  await s.screenshot({ path: `${out}/${tag}.png` });
  const c = s.locator('[data-hcard], [data-lcard]').nth(+idx); await c.hover(); await p.waitForTimeout(400);
  await s.screenshot({ path: `${out}/${tag}-hover.png` });
  await p.mouse.move(0, 0); await c.focus(); await p.keyboard.press('Shift+Tab'); await p.keyboard.press('Tab'); await p.waitForTimeout(400);
  await s.screenshot({ path: `${out}/${tag}-focus.png` }); await b.close(); })();
