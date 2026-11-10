// Reviews link through the real admin screens. Usage: node reviews-admin-test.js <outdir>
const { chromium } = require('/opt/node-tools/node_modules/playwright');
const base = 'http://127.0.0.1:8090', out = process.argv[2];
const HOME_URL = 'https://maps.example.test/home-reviews', GLOBAL_URL = 'https://maps.example.test/all-reviews';
(async () => {
  const b = await chromium.launch();
  const ctx = await b.newContext({ viewport: { width: 1440, height: 1000 }, storageState: '/home/claude/wptest-fixture/admin-state.json' });
  const page = await ctx.newPage();
  // 1. «تقييمات جوجل»: shortcode (stand-in plugin) + global link
  await page.goto(base + '/wp-admin/admin.php?page=seohouse-reviews', { waitUntil: 'networkidle' });
  await page.fill('#sh-reviews-global', '[trustindex no-registration=google]');
  await page.fill('#sh-reviews-all-link', GLOBAL_URL);
  await page.screenshot({ path: `${out}/admin-google-reviews-screen.png`, fullPage: true });
  await page.click('#sh_reviews_save'); await page.waitForLoadState('networkidle');
  await page.goto(base + '/wp-admin/admin.php?page=seohouse-reviews', { waitUntil: 'networkidle' });
  console.log('global after reload:', await page.inputValue('#sh-reviews-all-link'), '| label placeholder:', await page.getAttribute('#sh-reviews-all-label', 'placeholder'));
  // 2. home editor → التقييمات section
  await page.goto(base + '/wp-admin/post.php?post=6&action=edit', { waitUntil: 'networkidle' });
  await page.waitForTimeout(2500);
  const close = page.locator('.components-modal__header button[aria-label]'); if (await close.count()) await close.first().click().catch(() => {});
  const acc = page.locator('.acf-accordion-title', { hasText: 'التقييمات' }).first();
  await acc.scrollIntoViewIfNeeded(); await acc.click(); await page.waitForTimeout(600);
  const linkField = page.locator('.acf-field[data-name="all_link"]').filter({ has: page.locator('label', { hasText: 'رابط جميع المراجعات على Google' }) }).first();
  const labelField = page.locator('.acf-field[data-name="all_label"]').first();
  console.log('field visible:', await linkField.isVisible(), '| label field visible:', await labelField.isVisible(), '| label placeholder:', await labelField.locator('input').getAttribute('placeholder'));
  await linkField.locator('input').fill(HOME_URL);
  await linkField.scrollIntoViewIfNeeded();
  await page.screenshot({ path: `${out}/admin-home-reviews-section.png` });
  const save = page.locator('.editor-post-publish-button, .editor-post-publish-button__button, #publish').first();
  await save.click(); await page.waitForTimeout(4000);
  // 3. reopen
  await page.goto(base + '/wp-admin/post.php?post=6&action=edit', { waitUntil: 'networkidle' }); await page.waitForTimeout(2000);
  console.log('home field after reopen:', await page.locator('.acf-field[data-name="all_link"] input').first().inputValue());
  // 4. front end (logged out)
  const v = await (await b.newContext({ viewport: { width: 1440, height: 900 } })).newPage();
  for (const u of ['/', '/services/seo/', '/seo-ksa/', '/seo-egypt/', '/seo-uae/']) {
    const r = await v.goto(base + u, { waitUntil: 'networkidle' });
    const btn = await v.evaluate(() => { const a = [...document.querySelectorAll('[data-reviews-all]')]; const w = document.querySelector('[data-reviews-live]'); return { count: a.length, href: a[0] && a[0].href, target: a[0] && a[0].target, text: a[0] && a[0].innerText.split('\n')[0], belowWidget: !!(w && a[0] && (w.compareDocumentPosition(a[0]) & 4)), widgets: document.querySelectorAll('[data-reviews-live]').length }; });
    console.log(u, r.status(), JSON.stringify(btn));
    if (u === '/') { await v.locator('[data-reviews-all]').scrollIntoViewIfNeeded(); await v.locator('section[data-screen-label="Reviews"]').screenshot({ path: `${out}/front-home-reviews.png` }); }
  }
  // 5. the button opens the link in a new tab
  await v.goto(base + '/', { waitUntil: 'networkidle' });
  const [popup] = await Promise.all([v.context().waitForEvent('page'), v.locator('[data-reviews-all]').click()]);
  console.log('new tab opened:', popup.url());
  await b.close();
})();
