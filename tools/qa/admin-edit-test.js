#!/usr/bin/env node
/**
 * Editing test through the real WordPress admin (ACF free + SEO House Core):
 *   1. page sections: change a text field, add / reorder / remove list items, edit a nested list,
 *      hide a section → save → reload editor → check values → check the public page;
 *   2. «إعدادات سيو هاوس»: change a text field and a list (socials) → save → reload → public page;
 *   3. case study: add a metrics item → save → public page.
 * No JavaScript errors may occur on the edit screens.
 *
 * Usage: node admin-edit-test.js --wp http://127.0.0.1:8080 --user admin --pass admin
 *        [--page <id>] [--home <id>] [--case <id>] [--out report.json]
 */
const path = require('path');
const fs = require('fs');
const { chromium } = require(require.resolve('playwright', { paths: [path.join(__dirname, '../design-import/node_modules'), __dirname] }));

const argv = process.argv.slice(2);
const opt = (n, d) => { const i = argv.indexOf('--' + n); return i >= 0 ? argv[i + 1] : d; };
const WP = opt('wp', 'http://127.0.0.1:8080').replace(/\/$/, '');
const results = [];
const check = (name, ok, detail = '') => { results.push({ name, ok: !!ok, detail }); console.log(`${ok ? 'PASS' : 'FAIL'}  ${name}${detail ? '  — ' + detail : ''}`); };
const stamp = Date.now().toString(36);

(async () => {
  const browser = await chromium.launch();
  const ctx = await browser.newContext({ viewport: { width: 1400, height: 1000 } });
  const page = await ctx.newPage();
  const jsErrors = [];
  page.on('pageerror', e => jsErrors.push(e.message));
  page.on('dialog', d => d.accept());

  // login
  await page.goto(WP + '/wp-login.php');
  await page.fill('#user_login', opt('user', 'admin'));
  await page.fill('#user_pass', opt('pass', 'admin'));
  await Promise.all([page.waitForNavigation(), page.click('#wp-submit')]);
  check('login', page.url().includes('/wp-admin'));

  const pageId = opt('page', '30');
  const edit = async id => { await page.goto(`${WP}/wp-admin/post.php?post=${id}&action=edit`, { waitUntil: 'domcontentloaded' }); await page.waitForFunction(() => window.acf && document.querySelector('.acf-postbox'), null, { timeout: 30000 }); await page.waitForTimeout(500); };
  const save = async () => { await Promise.all([page.waitForNavigation({ timeout: 60000 }), page.click('#publish')]); await page.waitForFunction(() => window.acf, null, { timeout: 30000 }); };
  const revealTab = async name => page.evaluate(n => {
    const f = document.querySelector(`.acf-field[data-name="${n}"]`);
    let el = f; while (el && !(el.classList && el.classList.contains('acf-field-tab'))) el = el.previousElementSibling;
    if (el) { const b = document.querySelector(`.acf-tab-button[data-key="${el.dataset.key}"]`); if (b) b.click(); }
  }, name);
  const front = async url => (await (await ctx.request.get(url)).text());
  const openSection = async name => {
    const acc = page.locator(`.acf-field-accordion:has(.acf-field[data-name="${name}"]) > .acf-accordion-title`).first();
    if (await acc.count()) { const open = await acc.evaluate(e => e.closest('.acf-field-accordion').classList.contains('-open')); if (!open) await acc.click(); }
    return page.locator(`.acf-field[data-name="${name}"]`).first();
  };

  /* ------------------------------------------------ 1. page sections */
  await edit(pageId);
  if (opt('shot', '')) await page.screenshot({ path: opt('shot'), fullPage: false });
  check('classic edit screen with ACF sections (no block editor)', await page.locator('#post-body .acf-postbox').count() > 0 && !(await page.locator('.block-editor').count()));
  const sectionCount = await page.locator('.acf-field-group[data-name^="s_"]').count();
  check('sections rendered as ACF Group fields', sectionCount > 3, `${sectionCount} sections`);

  const hero = await openSection('s_hero');
  const titleInput = hero.locator('> .acf-input > .acf-fields > .acf-field[data-name="title"] input').first();
  const oldTitle = await titleInput.inputValue();
  const newTitle = `عنوان معدل للاختبار ${stamp}`;
  await titleInput.fill(newTitle);

  // first section that has a top-level list with at least 3 items
  const listInfo = await page.evaluate(() => {
    for (const sec of document.querySelectorAll('.acf-field-group[data-name^="s_"]')) {
      const box = sec.querySelector(':scope > .acf-input > .acf-fields > .acf-field-sh-rows .sh-rows');
      if (box && box.querySelectorAll(':scope > .sh-rows__list > .sh-rows__row').length >= 3) return { section: sec.dataset.name, field: box.closest('.acf-field').dataset.name };
    }
    return null;
  });
  check('a section list (sh_rows) is present', !!listInfo, listInfo ? `${listInfo.section}.${listInfo.field}` : '');
  const sec = await openSection(listInfo.section);
  const box = sec.locator(`.acf-field[data-name="${listInfo.field}"] .sh-rows`).first();
  const rows = () => box.locator(':scope > .sh-rows__list > .sh-rows__row');
  const rowText = async i => rows().nth(i).locator('> .sh-rows__body input[type=text], > .sh-rows__body textarea').first().inputValue();
  const before = await rows().count();
  const firstText = await rowText(0), secondText = await rowText(1);

  await box.locator(':scope > .sh-rows__add').click();
  check('add item: new row appended and open', (await rows().count()) === before + 1 && !(await rows().nth(before).evaluate(e => e.classList.contains('-collapsed'))));
  const addedText = `عنصر جديد للاختبار ${stamp}`;
  await rows().nth(before).locator('> .sh-rows__body input[type=text], > .sh-rows__body textarea').first().fill(addedText);
  await rows().nth(1).locator('> .sh-rows__bar > .sh-rows__up').click(); // second item moves to the top
  check('reorder: ↑ moves the item up', (await rowText(0)) === secondText);
  // remove the item that is now second (the original first)
  await rows().nth(1).locator('> .sh-rows__bar > .sh-rows__remove').click();
  check('remove item: row removed', (await rows().count()) === before);

  // hide the FAQ section if any
  const hasFaq = await page.locator('.acf-field[data-name="s_faq"]').count();
  if (hasFaq) {
    const faq = await openSection('s_faq');
    const hide = faq.locator('> .acf-input > .acf-fields > .acf-field[data-name="sh_hide"]').first();
    if (!(await hide.locator('input[type=checkbox]').isChecked())) await hide.locator('.acf-switch').click();
  }

  await save();
  check('save: no JS errors on the edit screen', jsErrors.length === 0, jsErrors.slice(0, 2).join(' | '));

  // reload editor and check stored values
  await edit(pageId);
  const hero2 = await openSection('s_hero');
  check('saved: section text field', (await hero2.locator('> .acf-input > .acf-fields > .acf-field[data-name="title"] input').first().inputValue()) === newTitle);
  const sec2 = await openSection(listInfo.section);
  const box2 = sec2.locator(`.acf-field[data-name="${listInfo.field}"] .sh-rows`).first();
  const rows2 = box2.locator(':scope > .sh-rows__list > .sh-rows__row');
  const texts2 = await rows2.evaluateAll(rs => rs.map(r => (r.querySelector(':scope > .sh-rows__body input[type=text], :scope > .sh-rows__body textarea') || {}).value));
  check('saved: item count', texts2.length === before, `${texts2.length}/${before}`);
  check('saved: new order (moved item first)', texts2[0] === secondText);
  check('saved: removed item is gone', !texts2.includes(firstText) || firstText === secondText);
  check('saved: added item kept at the end', texts2[texts2.length - 1] === addedText);

  // public page
  const permalink = await page.evaluate(() => (document.querySelector('#sample-permalink a') || {}).href);
  const html = await front(permalink);
  check('public page: edited title shown', html.includes(newTitle));
  check('public page: added item shown', html.includes(addedText));
  if (hasFaq) check('public page: hidden FAQ section not rendered', !html.includes('data-screen-label="FAQ"'));


  /* ------------------------------------------------ 1b. nested list (home page) */
  const homeId = opt('home', '');
  if (homeId) {
    jsErrors.length = 0;
    await edit(homeId);
      // nested list: first item of a list inside another list's item
    const nested = await page.evaluate(() => {
      const inner = document.querySelector('.sh-rows__list > .sh-rows__row .sh-rows__body .acf-field-sh-rows .sh-rows__list > .sh-rows__row');
      if (!inner) return null;
      inner.closest('.sh-rows__list').closest('.sh-rows__row').classList.remove('-collapsed');
      const outerRow = inner.closest('.sh-rows__body').closest('.sh-rows__row');
      outerRow.classList.remove('-collapsed');
      const acc = outerRow.closest('.acf-field-accordion');
      if (acc && !acc.classList.contains('-open')) acc.querySelector(':scope > .acf-accordion-title').click();
      inner.classList.remove('-collapsed');
      const input = inner.querySelector('.sh-rows__body input[type=text]');
      input.setAttribute('data-test-nested', '1');
      return { section: outerRow.closest('.acf-field-group').dataset.name, old: input.value };
    });
    let nestedText = '';
    if (nested) {
      nestedText = `نص متداخل للاختبار ${stamp}`;
      await page.fill('[data-test-nested="1"]', nestedText);
    }
    check('nested list present on this page', !!nested, nested ? nested.section : 'none on this page');

    check('nested list found', !!nested, nested ? nested.section : 'none');
    if (nested) {
      await save();
      const html2 = await front(WP + '/');
      check('nested list: edit saved and shown on the public page', html2.includes(nestedText));
      check('nested list: no JS errors', jsErrors.length === 0, jsErrors.slice(0, 2).join(' | '));
    }
  }

  /* ------------------------------------------------ 2. settings screen */
  jsErrors.length = 0;
  await page.goto(`${WP}/wp-admin/admin.php?page=seohouse-settings`, { waitUntil: 'domcontentloaded' });
  await page.waitForFunction(() => window.acf && document.querySelector('.acf-postbox'), null, { timeout: 30000 });
  check('settings: WordPress admin page renders the ACF group with tabs', (await page.locator('.acf-tab-button').count()) >= 5);
  const tab = async label => page.locator('.acf-tab-button', { hasText: label }).first().click();
  await tab('الهوية');
  const footer = page.locator('.acf-field[data-name="sh_footer_text"] textarea, .acf-field[data-name="sh_footer_text"] input').first();
  const footerText = `نص الفوتر للاختبار ${stamp}`;
  await footer.fill(footerText);
  await tab('بيانات الشركة');
  const socials = page.locator('.acf-field[data-name="sh_socials"] .sh-rows').first();
  const sBefore = await socials.locator(':scope > .sh-rows__list > .sh-rows__row').count();
  await socials.locator(':scope > .sh-rows__add').click();
  const sRow = socials.locator(':scope > .sh-rows__list > .sh-rows__row').last();
  await sRow.locator('.acf-field[data-name="label"] input').fill('LinkedIn');
  await sRow.locator('.acf-field[data-name="url"] input').fill(`https://www.linkedin.com/company/test-${stamp}`);
  await Promise.all([page.waitForNavigation({ timeout: 60000 }), page.click('#sh_settings_save')]);
  check('settings: saved notice', (await page.locator('.notice-success').count()) > 0);
  check('settings: no JS errors', jsErrors.length === 0, jsErrors.slice(0, 2).join(' | '));
  await page.waitForFunction(() => window.acf, null, { timeout: 30000 });
  await tab('الهوية');
  check('settings: text field persisted', (await page.locator('.acf-field[data-name="sh_footer_text"] textarea, .acf-field[data-name="sh_footer_text"] input').first().inputValue()) === footerText);
  await tab('بيانات الشركة');
  check('settings: list item persisted', (await page.locator('.acf-field[data-name="sh_socials"] .sh-rows > .sh-rows__list > .sh-rows__row').count()) === sBefore + 1);
  const home = await front(WP + '/');
  check('public page: footer text from settings', home.includes(footerText));

  /* ------------------------------------------------ 3. case study list */
  const caseId = opt('case', '');
  if (caseId) {
    jsErrors.length = 0;
    await edit(caseId);
    await revealTab('metrics');
    const metrics = page.locator('.acf-field[data-name="metrics"] .sh-rows').first();
    const mBefore = await metrics.locator(':scope > .sh-rows__list > .sh-rows__row').count();
    await metrics.locator(':scope > .sh-rows__add').click();
    const m = metrics.locator(':scope > .sh-rows__list > .sh-rows__row').last();
    const label = `مؤشر للاختبار ${stamp}`;
    await m.locator('.acf-field[data-name="label"] input').fill(label);
    await m.locator('.acf-field[data-name="after"] input').fill('+1'); // the template shows a metric only with a value
    await save();
    await edit(caseId);
    await revealTab('metrics');
    check('case study: metrics item saved', (await page.locator('.acf-field[data-name="metrics"] .sh-rows > .sh-rows__list > .sh-rows__row').count()) === mBefore + 1);
    const link = await page.evaluate(() => (document.querySelector('#sample-permalink a') || {}).href);
    check('case study: public page shows the new metric', (await front(link)).includes(label));
    check('case study: no JS errors', jsErrors.length === 0, jsErrors.slice(0, 2).join(' | '));
  }

  await browser.close();
  const failed = results.filter(r => !r.ok).length;
  const out = opt('out', '');
  if (out) fs.writeFileSync(out, JSON.stringify({ date: new Date().toISOString(), wp: WP, passed: results.length - failed, failed, results }, null, 2));
  console.log(`\n${results.length - failed}/${results.length} passed`);
  process.exit(failed ? 1 : 0);
})().catch(e => { console.error(e); process.exit(2); });
