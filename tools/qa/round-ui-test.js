#!/usr/bin/env node
/**
 * Round 2.4.0 in a real browser on the /new/ test site (never the live site):
 *  A. Rank Math installed but its registration step not done: notice, Core output meanwhile,
 *     «تشغيل بدون حساب», then the Rank Math box on pages, articles, case studies and team members:
 *     a title and a description typed in the box, saved, shown on the page.
 *  B. Platform logos in a page section: choices are the library's names; a new item with a logo
 *     uploaded from the media library becomes a library platform and shows on the page; changing
 *     an item's platform changes the logo on the page; deleting the item removes it.
 *  C. «تقييمات جوجل»: status, shared shortcode, a page's own shortcode, once per page.
 *  D. «نقل المقالات»: preview and run from the screen.
 * Needs mu-plugin test shortcodes [fake_reviews] and [fake_reviews_alt] and a PNG (--logo).
 *
 * Usage: node round-ui-test.js --wp http://127.0.0.1:8097/new --logo logo.png --out dir [--theme t.zip --core c.zip] [--skip-migrate]
 */
const path = require('path');
const fs = require('fs');
const { chromium } = require(require.resolve('playwright', { paths: [path.join(__dirname, '../design-import/node_modules')] }));
const argv = process.argv.slice(2), opt = (n, d) => { const i = argv.indexOf('--' + n); return i >= 0 ? argv[i + 1] : d; };
const WP = opt('wp').replace(/\/$/, ''), OUT = opt('out'), LOGO = opt('logo');
fs.mkdirSync(OUT, { recursive: true });
const results = [];
const check = (name, ok, detail = '') => { results.push({ name, ok: !!ok, detail: String(detail).slice(0, 300) }); console.log(`${ok ? 'PASS' : 'FAIL'}  ${name}${detail ? '  — ' + String(detail).slice(0, 200) : ''}`); };
const flat = s => String(s || '').replace(/\s+/g, ' ').trim();
const stamp = Date.now().toString(36);

(async () => {
  const b = await chromium.launch();
  const p = await b.newPage({ viewport: { width: 1400, height: 1000 } });
  const errors = [];
  p.on('pageerror', e => errors.push(e.message));
  p.on('dialog', d => d.accept());
  const shot = n => p.screenshot({ path: path.join(OUT, n + '.png'), fullPage: true });
  const front = async u => (await (await p.context().request.get(WP + u)).text());
  const head = h => h.split('</head>')[0];
  // the edit screen finishes loading its boxes (Rank Math, autosave heartbeat) before a person clicks «تحديث»
  const save = async () => { await p.waitForTimeout(5000); await Promise.all([p.waitForNavigation({ timeout: 120000 }), p.click('#publish')]); };
  const rest = async (type, q) => p.evaluate(async ([wp, type, q]) => (await (await fetch(`${wp}/wp-json/wp/v2/${type}?${q}`)).json()), [WP, type, q]);

  await p.goto(WP + '/wp-login.php');
  await p.fill('#user_login', 'admin'); await p.fill('#user_pass', 'admin');
  await Promise.all([p.waitForNavigation(), p.click('#wp-submit')]);

  // ------------------------------------------------------------ 0. install the packages over 2.3.0 (admin upload)
  for (const [kind, file] of [['theme', opt('theme')], ['plugin', opt('core')]]) {
    if (!file) continue;
    await p.goto(`${WP}/wp-admin/${kind === 'theme' ? 'theme-install.php?upload' : 'plugin-install.php?tab=upload'}`);
    if (kind === 'theme' && !(await p.locator('#install-theme-submit').isVisible())) await p.click('.upload-view-toggle');
    await p.setInputFiles(kind === 'theme' ? '#themezip' : '#pluginzip', file);
    await Promise.all([p.waitForNavigation({ timeout: 120000 }), p.click(kind === 'theme' ? '#install-theme-submit' : '#install-plugin-submit')]);
    const rep = p.locator('a.update-from-upload-overwrite');
    const cmp = flat(await p.textContent('.update-from-upload-comparison').catch(() => ''));
    check(`0 upload ${path.basename(file)}: WordPress offers to replace the installed ${kind}`, await rep.count() > 0, cmp.slice(0, 120));
    await Promise.all([p.waitForNavigation({ timeout: 120000 }), rep.click()]);
    check(`0 ${kind} replaced`, /تم تحديث|updated successfully|بنجاح/.test(await p.textContent('body')));
  }
  if (opt('core')) {
    await p.goto(WP + '/wp-admin/plugins.php');
    check('0 SEO House Core 2.4.0 active', /2\.4\.0/.test(await p.textContent('tr[data-plugin="seohouse-core/seohouse-core.php"]')) && await p.locator('tr[data-plugin="seohouse-core/seohouse-core.php"].active').count() > 0);
    await shot('0-plugins');
  }

  // ------------------------------------------------------------ D. migration screen
  if (!argv.includes('--skip-migrate')) {
    await p.goto(WP + '/wp-admin/admin.php?page=seohouse-migrate-posts');
    const info = flat(await p.textContent('.wrap'));
    check('D1 «نقل المقالات»: main site detected with its published count', /المقالات المنشورة\s*4/.test(info) && /seohouse\.agency/.test(info), (info.match(/الموقع الأساسي.{0,60}/) || [''])[0]);
    await Promise.all([p.waitForNavigation({ timeout: 120000 }), p.click('button[value="preview"]')]);
    const pv = flat(await p.textContent('.wrap'));
    check('D2 preview writes nothing and lists the four articles', /معاينة — لم يُكتب شيء/.test(pv) && /فشل: 0/.test(pv), (pv.match(/جديد: \d+.{0,90}/) || [''])[0]);
    await shot('D2-migrate-preview');
    await Promise.all([p.waitForNavigation({ timeout: 300000 }), p.click('button[value="run"]')]);
    const rn = flat(await p.textContent('.wrap'));
    check('D3 run completes without failures', /اكتمل النقل/.test(rn) && /فشل: 0/.test(rn), (rn.match(/جديد: \d+.{0,90}/) || [''])[0]);
    await shot('D3-migrate-run');
    for (const u of ['/blog/why-is-my-website-not-showing-in-search-engines/', '/blog/how-to-build-backlinks-correctly/', '/blog/fix-404-not-found/']) {
      const r = await p.context().request.get(WP + u);
      check(`D4 ${u} answers 200 on /new/`, r.status() === 200, r.status());
    }
    const art = await (await p.context().request.get(WP + '/blog/how-to-build-backlinks-correctly/')).text();
    const by = flat((art.match(/بقلم[\s\S]{0,400}?<\/(?:a|span)>/) || [''])[0].replace(/<[^>]+>/g, ' '));
    check('D4 article byline: the author of the main site (not a generic team name)', /بقلم \S/.test(by) && !/فريق/.test(by), by);
    await Promise.all([p.waitForNavigation({ timeout: 300000 }), p.click('button[value="run"]')]);
    check('D5 second run: nothing created or replaced', /جديد: 0 — استبدال مسودة: 0/.test(flat(await p.textContent('.wrap'))));
  }


  // ------------------------------------------------------------ A. Rank Math
  await p.goto(WP + '/wp-admin/');
  const notice = p.locator('.notice:has-text("Rank Math مفعّلة لكنها متوقفة")');
  check('A1 notice: Rank Math installed but waiting for its registration step', await notice.count() > 0);
  let h = await front('/services/seo/egypt/');
  check('A2 meanwhile Core outputs title, description and schema (not an empty head)', /<meta name="description"/.test(head(h)) && /application\/ld\+json/.test(h) && !/<title>[^<]*&#8211;/.test(head(h)), (head(h).match(/<title>[^<]*/) || [''])[0]);
  await shot('A1-notice');
  await Promise.all([p.waitForNavigation(), notice.locator('a:has-text("تشغيل Rank Math بدون حساب")').click()]);
  const rmPage = flat(await p.textContent('.wrap'));
  check('A3 «تشغيل بدون حساب» → Rank Math running, box shown for all four types', /تعمل الآن بدون حساب/.test(rmPage) && !/مطفأ/.test(rmPage), (rmPage.match(/الصفحات.{0,80}/) || [''])[0]);
  await shot('A3-rankmath-screen');

  const types = [
    ['page', 'pages', 'slug=egypt'],
    ['post', 'posts', 'slug=why-is-my-website-not-showing-in-search-engines'],
    ['case_study', 'case_study', 'per_page=1'],
    ['team_member', 'team_member', 'per_page=1']
  ];
  for (const [type, ep, q] of types) {
    const r = await rest(ep, q);
    if (!r[0]) { check(`A4 ${type}: record found`, false); continue; }
    const { id, link } = r[0];
    const title = `عنوان اختبار ${type} ${stamp}`, desc = `وصف اختبار Rank Math لنوع ${type} — ${stamp}`;
    await p.goto(`${WP}/wp-admin/post.php?post=${id}&action=edit`);
    const block = await p.locator('.block-editor, .edit-post-layout').count() > 0;
    if (block) {
      await p.waitForSelector('button[aria-label="إضافة Rank Math"]', { timeout: 60000 });
      const dlg = p.locator('.components-modal__screen-overlay button[aria-label="إغلاق"], .components-modal__screen-overlay button[aria-label="Close"]');
      if (await dlg.count()) await dlg.first().click();
      await p.click('button[aria-label="إضافة Rank Math"]');
    } else {
      await p.waitForSelector('#rank_math_metabox', { timeout: 60000 });
    }
    check(`A4 ${type}: Rank Math box in the edit screen`, true, block ? 'block editor sidebar' : 'classic editor box');
    await p.locator('button:has-text("تحرير مقتطف"), button:has-text("Edit Snippet")').first().click();
    await p.waitForSelector('#rank-math-editor-title', { timeout: 30000 });
    await p.fill('#rank-math-editor-title', title);
    await p.fill('#rank-math-editor-description', desc);
    await p.waitForTimeout(800);
    await shot(`A5-${type}-snippet`);
    await p.locator('.rank-math-modal-overlay .components-modal__header button, .components-modal__screen-overlay .components-modal__header button').first().click();
    await p.waitForSelector('.components-modal__screen-overlay', { state: 'detached', timeout: 15000 }).catch(() => {});
    if (block) {
      await p.click('.editor-post-publish-button, .editor-post-publish-button__button');
      await p.waitForSelector('.components-snackbar, .editor-post-saved-state.is-saved', { timeout: 60000 });
      await p.waitForTimeout(2500);
    } else {
      await save();
    }
    h = await front(new URL(link).pathname.replace(/^\/new/, ''));
    const t = (head(h).match(/<title>([^<]*)<\/title>/) || [])[1] || '';
    const d = (head(h).match(/<meta name="description" content="([^"]*)"/) || [])[1] || '';
    check(`A5 ${type}: title and description saved in Rank Math and shown on the page`, t.includes(title) && d === desc, `${t} | ${d}`);
    check(`A5 ${type}: one title, one description, at most one canonical, one JSON-LD`, (head(h).match(/<title>/g) || []).length === 1 && (head(h).match(/<meta name="description"/g) || []).length === 1 && (head(h).match(/rel="canonical"/g) || []).length <= 1 && (h.match(/application\/ld\+json/g) || []).length === 1, 'canonical: Rank Math leaves it out while the review copy is noindex');
  }
  h = await front('/services/seo/egypt/');
  check('A6 review copy /new/ stays noindex with Rank Math running', /<meta name=["']robots["'] content=["'][^"']*noindex/.test(head(h)), (head(h).match(/<meta name=["']robots["'][^>]*/) || [''])[0]);

  // ------------------------------------------------------------ B. platform logos in a section
  const tech = (await rest('pages', 'slug=technical'))[0];
  const openAll = async () => p.evaluate(() => {
    document.querySelectorAll('.acf-field-accordion').forEach(a => { a.classList.add('-open'); const c = a.querySelector('.acf-accordion-content'); if (c) c.style.display = 'block'; });
    document.querySelectorAll('.sh-rows__row.-collapsed').forEach(r => r.classList.remove('-collapsed'));
  });
  await p.goto(`${WP}/wp-admin/post.php?post=${tech.id}&action=edit`);
  await p.waitForSelector('.acf-field[data-name="s_tools"]', { state: 'attached' });
  await openAll();
  const list = p.locator('.acf-field[data-name="s_tools"] .acf-field[data-name="items"]').first();
  const firstSelect = list.locator('.sh-rows__list > .sh-rows__row select').first();
  const opts = await firstSelect.locator('option').allTextContents();
  check('B1 logo field in the section: choices are the library platforms by name (not SVG file names)', opts.includes('سيرش كونسول') && !opts.some(o => /^(gsc|ga4|wordpress|salla)$/.test(o.trim())), opts.filter(Boolean).join('، '));
  check('B1 «أو شعار من مكتبة الوسائط» field next to it', await list.locator('.sh-rows__list > .sh-rows__row .acf-field[data-name="logo_image"]').count() > 0);
  await shot('B1-section-logo-field');
  const before = await list.locator('.sh-rows__list > .sh-rows__row').count();
  await list.locator('.sh-rows__add').last().click();
  await openAll();
  const row = list.locator('.sh-rows__list > .sh-rows__row').nth(before);
  const label = `منصة اختبار ${stamp}`;
  await row.locator('.acf-field[data-name="label"] input').fill(label);
  await row.locator('.acf-field[data-name="logo_image"] a[data-name="add"], .acf-field[data-name="logo_image"] .acf-button').first().click();
  await p.waitForSelector('.media-modal:visible', { timeout: 30000 });
  const upTab = p.locator('.media-modal:visible .media-menu-item:has-text("رفع"), .media-modal:visible #menu-item-upload');
  if (await upTab.count()) await upTab.first().click();
  await p.locator('.media-modal:visible input[type="file"], body > div[id^="html5_"] input[type="file"]').first().setInputFiles(LOGO);
  await p.waitForSelector('.media-modal:visible .attachment.selected.details, .media-modal:visible .attachment-details', { timeout: 60000 });
  await p.waitForTimeout(1500);
  await p.locator('.media-modal:visible .media-button-select').click();
  await p.waitForTimeout(800);
  check('B2 new item: logo uploaded from the media library into the section', await row.locator('.acf-field[data-name="logo_image"] .acf-image-uploader.has-value, .acf-field[data-name="logo_image"] img[src]').count() > 0);
  await save();
  check('B3 saved: notice that a platform was added to the library', /أُضيفت 1 منصة جديدة/.test(flat(await p.textContent('#wpbody-content'))));
  await shot('B3-saved');
  const logoName = path.basename(LOGO).replace(/\.\w+$/, '');
  h = await front('/services/seo/technical/');
  check('B4 page shows the uploaded logo with the item label', h.includes(logoName) && h.includes(label));
  await p.goto(WP + '/wp-admin/admin.php?page=seohouse-settings');
  const libNames = await p.locator('.acf-field[data-name="sh_platforms"] .sh-rows__list > .sh-rows__row .sh-rows__title').allTextContents();
  check('B5 the new platform is in «المنصات والأدوات» (name + logo)', libNames.some(n => n.includes(label)), libNames.join('، '));
  await p.goto(`${WP}/wp-admin/post.php?post=${tech.id}&action=edit`);
  await openAll();
  const row2 = list.locator('.sh-rows__list > .sh-rows__row').nth(before);
  check('B6 the item now points to the library platform (choice selected)', (await row2.locator('select option:checked').textContent()) === label);
  // edit: first item → شوبيفاي
  const sel0 = list.locator('.sh-rows__list > .sh-rows__row').first().locator('select').first();
  await sel0.selectOption({ label: 'شوبيفاي' });
  await save();
  h = await front('/services/seo/technical/');
  const tools = h.slice(h.indexOf('data-screen-label="Tools"'));
  const chips = [...tools.matchAll(/<img src="([^"]+)"/g)].map(m => m[1]);
  check('B7 change an item\'s platform → the page shows the new logo in that place', chips[0] && /shopify/.test(chips[0]), chips.slice(0, 3).map(s => s.split('/').pop()).join(', '));
  // delete the new item
  await p.goto(`${WP}/wp-admin/post.php?post=${tech.id}&action=edit`);
  await openAll();
  await list.locator('.sh-rows__list > .sh-rows__row').nth(before).locator('.sh-rows__remove').click();
  await save();
  h = await front('/services/seo/technical/');
  check('B8 delete the item → its logo and label are gone from the page', !h.includes(label));
  await p.goto(WP + '/wp-admin/admin.php?page=seohouse-settings');
  check('B8 … the platform stays in the library for other pages', (await p.locator('.acf-field[data-name="sh_platforms"] .sh-rows__title').allTextContents()).some(n => n.includes(label)));

  // ------------------------------------------------------------ C. Google reviews screen
  await p.goto(WP + '/wp-admin/admin.php?page=seohouse-reviews');
  check('C1 «تقييمات جوجل» in the SEO House menu', await p.locator('#adminmenu a[href$="page=seohouse-reviews"]').count() > 0);
  check('C1 status without a shortcode: section hidden everywhere', /لا يوجد شورت كود عام/.test(flat(await p.textContent('.wrap'))));
  await p.fill('#sh-reviews-global', '[fake_reviews]');
  await Promise.all([p.waitForNavigation(), p.click('#sh_reviews_save')]);
  let w = flat(await p.textContent('.wrap'));
  check('C2 shared shortcode saved: status «مسجّل من إضافة مفعّلة», pages «يظهر — الشورت كود العام»', /مسجّل من إضافة مفعّلة/.test(w) && (w.match(/يظهر — الشورت كود العام/g) || []).length >= 5, (w.match(/يظهر — الشورت كود العام/g) || []).length + ' pages');
  const egypt = (await rest('pages', 'slug=egypt'))[0];
  await p.fill(`input[name="sh_reviews_page[${egypt.id}]"]`, '[fake_reviews_alt]');
  await Promise.all([p.waitForNavigation(), p.click('#sh_reviews_save')]);
  w = flat(await p.textContent('.wrap'));
  check('C3 page override saved: Egypt «يظهر — الشورت كود الخاص»', /يظهر — الشورت كود الخاص/.test(w));
  await shot('C3-reviews-screen');
  h = await front('/services/seo/egypt/');
  check('C4 Egypt shows its own widget once and not the shared one', (h.match(/class="fake-reviews-alt"/g) || []).length === 1 && !/class="fake-reviews"/.test(h));
  h = await front('/');
  check('C4 home shows the shared widget once', (h.match(/class="fake-reviews"/g) || []).length === 1);
  await p.fill('#sh-reviews-global', '[not_a_plugin]');
  await Promise.all([p.waitForNavigation(), p.click('#sh_reviews_save')]);
  check('C5 unknown shortcode: status says the plugin is not active; home hides the section', /غير مسجّل/.test(flat(await p.textContent('.wrap'))) && !/<section data-screen-label="Reviews"/.test(await front('/')));
  await p.fill('#sh-reviews-global', '[fake_reviews]');
  await Promise.all([p.waitForNavigation(), p.click('#sh_reviews_save')]);

  check('no JavaScript errors in the admin', errors.length === 0, errors.slice(0, 3).join(' | '));
  await b.close();
  fs.writeFileSync(path.join(OUT, 'round-ui.json'), JSON.stringify({ date: new Date().toISOString(), wp: WP, results }, null, 2));
  const failed = results.filter(r => !r.ok).length;
  console.log(`\n${results.length - failed}/${results.length} passed`);
  process.exit(failed ? 1 : 0);
})().catch(e => { console.error(e); process.exit(2); });
