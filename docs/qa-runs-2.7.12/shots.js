// Section screenshots with card states. Usage: node shots.js <outdir> <label> [path]
// For each width: Services + Markets sections at rest, first/second card hovered, card keyboard-focused.
const { chromium } = require('/opt/node-tools/node_modules/playwright');
const [out, label, path = '/'] = process.argv.slice(2);
const secs = { services: 'section[data-screen-label="Services"]', markets: 'section[data-screen-label="Markets"]' };
(async () => {
  const b = await chromium.launch();
  for (const w of [1440, 768, 390]) {
    const ctx = await b.newContext({ viewport: { width: w, height: 1000 }, reducedMotion: 'reduce', hasTouch: false });
    const p = await ctx.newPage();
    await p.goto('http://127.0.0.1:8090' + path, { waitUntil: 'networkidle' });
    for (const [name, sel] of Object.entries(secs)) {
      const s = p.locator(sel).first();
      await s.scrollIntoViewIfNeeded(); await p.waitForTimeout(250);
      await p.mouse.move(0, 0); await p.waitForTimeout(300);
      await s.screenshot({ path: `${out}/${label}-${name}-${w}.png` });
      if (w === 390) continue;
      const cards = s.locator('[data-hcard], [data-lcard]');
      const idx = name === 'services' ? [0, 1] : [1];
      for (const i of idx) { await cards.nth(i).hover(); await p.waitForTimeout(400); await s.screenshot({ path: `${out}/${label}-${name}-${w}-hover${i}.png` }); }
      await p.mouse.move(0, 0);
      await cards.nth(idx[idx.length - 1]).focus(); await p.keyboard.press('Shift+Tab'); await p.keyboard.press('Tab'); await p.waitForTimeout(400);
      await s.screenshot({ path: `${out}/${label}-${name}-${w}-focus.png` });
      await p.evaluate(() => document.activeElement && document.activeElement.blur());
    }
    await ctx.close();
  }
  await b.close();
})();
