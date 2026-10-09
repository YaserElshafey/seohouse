// Upload through the real admin UIs: media library (drag/drop uploader) and Customizer › Site Identity › Site Icon.
const { chromium } = require('/opt/node-tools/node_modules/playwright');
const fs = require('fs');
const base = 'http://127.0.0.1:8090', out = process.argv[2], dir = '/home/claude/wptest-fixture/media-in2/';
(async () => {
  const b = await chromium.launch();
  const ctx = await b.newContext({ viewport: { width: 1440, height: 1000 }, storageState: '/home/claude/wptest-fixture/admin-state.json' });
  const page = await ctx.newPage();
  const errs = []; page.on('response', r => { if (r.status() >= 400 && /wp-admin|uploads|wp-json/.test(r.url())) errs.push(r.status() + ' ' + r.url()); });
  // 1. media library page uploader (async-upload.php, same path as drag & drop)
  await page.goto(base + '/wp-admin/media-new.php', { waitUntil: 'networkidle' });
  const inp = page.locator('input[type=file]').first();
  await inp.setInputFiles([dir + 'SEO House Icon.png', dir + 'SEO-House-Icon-blue.png']);
  await page.waitForTimeout(8000);
  console.log('media-new items:', await page.locator('#media-items .media-item').count(), '| errors:', await page.locator('#media-items .error-div, .upload-error').count());
  // 2. Customizer → site icon → upload webp → crop → publish
  await page.goto(base + '/wp-admin/customize.php', { waitUntil: 'networkidle' }); await page.waitForTimeout(2000);
  await page.click('#accordion-section-title_tagline'); await page.waitForTimeout(800);
  await page.click('#customize-control-site_icon button.upload-button, #customize-control-site_icon .button.upload-button');
  await page.waitForTimeout(1200);
  const modal = page.locator('.media-modal:visible');
  await modal.locator('.media-menu-item, .media-router button', { hasText: /Upload files|رفع/ }).first().click().catch(() => {});
  await modal.locator('input[type=file]').first().setInputFiles(dir + 'SEO-House-Icon-2.webp');
  await page.waitForTimeout(6000);
  await page.screenshot({ path: `${out}/customizer-modal-after-upload.png` });
  const grid = await page.evaluate(() => [...document.querySelectorAll('.media-modal .attachments .attachment')].slice(0, 6).map(a => { const i = a.querySelector('img'); return { label: a.getAttribute('aria-label'), src: i && i.src.split('/').pop(), loaded: !!(i && i.complete && i.naturalWidth), bg: i && getComputedStyle(i.closest('.thumbnail')).backgroundColor }; }));
  console.log('modal grid:', JSON.stringify(grid));
  await modal.locator('.media-button-select, .media-toolbar-primary button.media-button').first().click(); await page.waitForTimeout(2500);
  await page.screenshot({ path: `${out}/customizer-crop.png` });
  const crop = page.locator('.media-modal:visible .media-button-insert, .media-modal:visible button.media-button', { hasText: /Crop|قص/ });
  if (await crop.count()) { await crop.first().click(); await page.waitForTimeout(4000); }
  else { const skip = page.locator('.media-modal:visible button', { hasText: /Skip|تخطي/ }); if (await skip.count()) { await skip.first().click(); await page.waitForTimeout(2000); } }
  await page.screenshot({ path: `${out}/customizer-after-crop.png` });
  await page.click('#save'); await page.waitForTimeout(4000);
  // 3. reopen
  await page.goto(base + '/wp-admin/customize.php', { waitUntil: 'networkidle' }); await page.waitForTimeout(2000);
  await page.click('#accordion-section-title_tagline'); await page.waitForTimeout(800);
  const icon = await page.evaluate(() => { const i = document.querySelector('#customize-control-site_icon img'); return i ? { src: i.src.split('/').pop(), loaded: i.complete && i.naturalWidth > 0, w: i.naturalWidth } : null; });
  console.log('site icon after reopen:', JSON.stringify(icon));
  await page.locator('#customize-control-site_icon').screenshot({ path: `${out}/customizer-site-icon-control.png` });
  // 4. media library grid
  await page.goto(base + '/wp-admin/upload.php?mode=grid', { waitUntil: 'networkidle' }); await page.waitForTimeout(2000);
  await page.screenshot({ path: `${out}/media-grid.png` });
  const lib = await page.evaluate(() => [...document.querySelectorAll('.attachments .attachment')].slice(0, 6).map(a => { const i = a.querySelector('img'); return { label: a.getAttribute('aria-label'), src: i && i.src, loaded: !!(i && i.complete && i.naturalWidth) }; }));
  console.log('library:', JSON.stringify(lib));
  fs.writeFileSync(out + '/lib.json', JSON.stringify(lib));
  console.log('http errors:', JSON.stringify(errs));
  await b.close();
})();
