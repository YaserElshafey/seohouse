// Choose case studies in «دراسة الحالة لهذه البطاقة» on the SEO page editor (test site only).
const { chromium } = require('/opt/node-tools/node_modules/playwright');
(async () => {
  const b = await chromium.launch();
  const ctx = await b.newContext({ storageState: '/home/claude/wptest-fixture/admin-state.json', viewport: { width: 1440, height: 1000 } });
  const p = await ctx.newPage();
  await p.goto('http://127.0.0.1:8090/wp-admin/post.php?post=18&action=edit', { waitUntil: 'domcontentloaded' });
  await p.waitForTimeout(3000);
  const KEY = 'field_sh_seo_results_case_study';
  const fields = p.locator(`.acf-field[data-key="${KEY}"]:not(.acf-clone .acf-field)`);
  console.log('picker fields:', await fields.count(), '| old link fields visible:', await p.locator('.acf-field[data-key="field_8ca00a58b2e1c"]').count());
  // open the «نتائج موثقة» accordion, then the three card rows
  await p.locator('.acf-field[data-key="field_92bb39d9ff1df"] .acf-accordion-title').click();
  await p.waitForTimeout(500);
  const rows = p.locator('.acf-field[data-key="field_68ac615c4ba65"] .sh-rows__list > .sh-rows__row:not(.acf-clone)');
  for (let i = 0; i < await rows.count(); i++) { await rows.nth(i).locator('.sh-rows__toggle').first().click(); await p.waitForTimeout(200); }
  const visible = [];
  for (let i = 0; i < await fields.count(); i++) if (await fields.nth(i).isVisible()) visible.push(i);
  console.log('visible pickers:', visible.length);
  // open the first picker and list the offered options
  const f0 = fields.nth(visible[0]);
  await f0.scrollIntoViewIfNeeded();
  await f0.locator('.select2-selection').click();
  await p.waitForSelector('.select2-results__option:not(.loading-results)', { timeout: 15000 });
  await p.waitForTimeout(800);
  console.log('offered:', await p.locator('.select2-results__option').allInnerTexts());
  await p.keyboard.press('Escape');
  const pick = async (idx, text) => {
    const f = fields.nth(visible[idx]);
    await f.scrollIntoViewIfNeeded();
    await f.locator('.select2-selection').click();
    await p.locator('.select2-search__field').fill(text.slice(0, 6));
    await p.waitForTimeout(1500);
    await p.locator('.select2-results__option', { hasText: text }).first().click();
  };
  await pick(0, 'قطاع التجارة الإلكترونية');
  await pick(1, 'قطاع الصحة والطب');
  await f0.screenshot({ path: '/home/claude/wptest-fixture/caselinks/admin-picker.png' });
  await rows.nth(0).screenshot({ path: '/home/claude/wptest-fixture/caselinks/admin-card.png' });
  await Promise.all([p.waitForNavigation({ timeout: 120000 }), p.locator('#publish').click()]);
  console.log('saved:', p.url());
  await b.close();
})();
