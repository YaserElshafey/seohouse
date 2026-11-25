const { chromium } = require('/opt/node-tools/node_modules/playwright');
(async () => {
  const b = await chromium.launch();
  const ctx = await b.newContext({ viewport: { width: 1440, height: 900 }, storageState: '/home/claude/wptest-fixture/admin-state.json' });
  const page = await ctx.newPage();
  await page.goto('http://127.0.0.1:8090/wp-admin/post.php?post=72&action=edit', { waitUntil: 'networkidle' });
  await page.waitForTimeout(3000);
  const close = page.locator('button[aria-label="Close"], .components-modal__header button'); if (await close.count()) await close.first().click().catch(() => {});
  const fr = page.frameLocator('iframe[name="editor-canvas"]');
  const inFrame = await page.locator('iframe[name="editor-canvas"]').count();
  const target = inFrame ? fr.locator('body') : page.locator('.editor-styles-wrapper');
  // add a heading and a link paragraph for the check (not saved)
  const r = await target.evaluate(el => {
    const root = el.matches('body') ? el : el; const doc = root.ownerDocument;
    const w = doc.querySelector('.editor-styles-wrapper') || root;
    const cs = x => x ? getComputedStyle(x) : null;
    const title = doc.querySelector('.editor-post-title, h1.wp-block-post-title');
    const p = w.querySelector('p'); const h = w.querySelector('h2,h3'); const a = w.querySelector('a');
    return { bg: cs(w).backgroundColor, color: cs(w).color, title: title && cs(title).color, p: p && cs(p).color, h: h && cs(h).color, a: a && cs(a).color, font: cs(w).fontFamily.slice(0, 40) };
  });
  console.log('iframe', inFrame, JSON.stringify(r));
  await page.screenshot({ path: '/home/claude/wptest-fixture/runs/r271-ui/editor.png' });
  // admin chrome untouched
  console.log('toolbar', await page.locator('.editor-header, .edit-post-header').first().evaluate(e => getComputedStyle(e).backgroundColor));
  await b.close();
})();
