#!/usr/bin/env node
/**
 * The owner's upgrade path on /new/, in a real browser: upload the new theme and SEO House Core
 * over the installed ones («استبدال الحالي بالمرفوع»), open «تهيئة الموقع», read the version
 * notice, preview, run, read the checks, run again; then look at the editor screens that changed
 * (Rank Math box vs Core fields, reviews shortcode, platforms library). Screenshots go to --out.
 *
 * Usage: node upgrade-ui-test.js --wp http://127.0.0.1:8097/new --theme seohouse-theme.zip --core seohouse-core.zip --out dir
 */
const path = require('path');
const fs = require('fs');
const { chromium } = require(require.resolve('playwright', { paths: [path.join(__dirname, '../design-import/node_modules')] }));
const argv = process.argv.slice(2), opt = (n, d) => { const i = argv.indexOf('--' + n); return i >= 0 ? argv[i + 1] : d; };
const WP = opt('wp').replace(/\/$/, ''), OUT = opt('out');
fs.mkdirSync(OUT, { recursive: true });
const results = [];
const check = (name, ok, detail = '') => { results.push({ name, ok: !!ok, detail }); console.log(`${ok ? 'PASS' : 'FAIL'}  ${name}${detail ? '  — ' + detail : ''}`); };
const flat = s => String(s || '').replace(/\s+/g, ' ').trim();

(async () => {
  const b = await chromium.launch();
  const p = await b.newPage({ viewport: { width: 1400, height: 1000 } });
  const errors = [];
  p.on('pageerror', e => errors.push(e.message));
  p.on('dialog', d => d.accept());
  const shot = n => p.screenshot({ path: path.join(OUT, n + '.png'), fullPage: true });

  await p.goto(WP + '/wp-login.php');
  await p.fill('#user_login', 'admin'); await p.fill('#user_pass', 'admin');
  await Promise.all([p.waitForNavigation(), p.click('#wp-submit')]);

  const replace = async (kind, file) => {
    await p.goto(`${WP}/wp-admin/${kind === 'theme' ? 'theme-install.php?upload' : 'plugin-install.php?tab=upload'}`);
    if (kind === 'theme' && !(await p.locator('#install-theme-submit').isVisible())) await p.click('.upload-view-toggle');
    await p.setInputFiles(kind === 'theme' ? '#themezip' : '#pluginzip', file);
    await Promise.all([p.waitForNavigation({ timeout: 120000 }), p.click(kind === 'theme' ? '#install-theme-submit' : '#install-plugin-submit')]);
    await shot(`00-replace-${kind}`);
    const rep = p.locator(kind === 'theme' ? 'a.update-from-upload-overwrite' : 'a.update-from-upload-overwrite');
    const offered = await rep.count() > 0;
    check(`WordPress offers to replace the installed ${kind} with ${path.basename(file)}`, offered, flat(await p.textContent('.update-from-upload-comparison').catch(() => '')).slice(0, 160));
    if (offered) await Promise.all([p.waitForNavigation({ timeout: 120000 }), rep.click()]);
    check(`${kind} replaced`, /تم تحديث|updated successfully|بنجاح/.test(await p.textContent('body')));
  };
  await replace('theme', opt('theme'));
  await replace('plugin', opt('core'));

  await p.goto(WP + '/wp-admin/plugins.php');
  const coreRow = await p.textContent('tr[data-plugin="seohouse-core/seohouse-core.php"]');
  check('Plugins screen: SEO House Core 2.3.0 active', /2\.3\.0/.test(coreRow) && await p.locator('tr[data-plugin="seohouse-core/seohouse-core.php"].active').count() > 0);
  await shot('01-plugins');
  await p.goto(WP + '/wp-admin/themes.php');
  check('Themes screen: SEO House 2.3.0 active', /2\.3\.0/.test(await (await p.context().request.get(WP + '/wp-content/themes/seohouse/style.css')).text()));

  // dashboard notice: newer design content
  await p.goto(WP + '/wp-admin/');
  check('dashboard: «تحديث محتوى التصميم 2.3.0 متاح» notice', await p.locator('.notice:has-text("تحديث محتوى التصميم 2.3.0 متاح")').count() > 0);

  await p.goto(WP + '/wp-admin/admin.php?page=seohouse-content-setup');
  const info = flat(await p.textContent('#sh-setup'));
  check('setup screen: bundled pack 2.3.0 found and intact', /2\.3\.0 — مضمّنة/.test(info) && /125 ملفًا \/ 125 متوقعة — كاملة وسليمة/.test(info), (info.match(/حزمة المحتوى.{0,160}/) || [''])[0]);
  check('setup screen: newer pack notice (2.3.0 > 2.2.1)', /حزمة المحتوى 2\.3\.0 أحدث مما هُيّئ به الموقع \(2\.2\.1\)/.test(info));
  await shot('02-setup-screen');

  await p.click('#sh-preview');
  await p.waitForSelector('#sh-preview-out .notice', { timeout: 120000 });
  const prev = flat(await p.textContent('#sh-preview-out'));
  check('preview (dry run) completes without failures', /تشغيل تجريبي/.test(prev) && /فشل:\s*0/.test(prev), prev.slice(0, 200));
  await shot('03-preview');

  const t0 = Date.now();
  await p.click('#sh-run');
  await p.waitForSelector('#sh-run-out .notice', { timeout: 900000 });
  const done = flat(await p.textContent('#sh-run-out .notice'));
  check('update run completes without failures', /اكتملت التهيئة\./.test(done) && /فشل:\s*0/.test(done), done + ` (${Math.round((Date.now() - t0) / 1000)} s)`);
  const log = await p.$$eval('#sh-run-out table tr', trs => trs.map(t => [...t.children].map(c => c.textContent.trim()).join(' | ')));
  fs.writeFileSync(path.join(OUT, 'run-log.txt'), log.join('\n') + '\n');
  await p.waitForSelector('#sh-check-out table', { timeout: 300000 });
  const rows = await p.$$eval('#sh-check-out tr', trs => trs.map(t => [...t.children].map(c => c.textContent.trim())));
  for (const r of rows) {
    // this round must not change permalinks (owner decision): the row only reports the state
    if (r[1] === 'الروابط الدائمة') { check('check: الروابط الدائمة (unchanged by the update; reported)', /\/%postname%\//.test(r[2]), r[2]); continue; }
    check('check: ' + r[1], r[0] === 'سليم', r[2]);
  }
  await shot('04-run-and-checks');

  await p.reload(); await p.waitForSelector('#sh-run');
  check('after the update: no newer-pack notice', !/أحدث مما هُيّئ به الموقع/.test(await p.textContent('#sh-setup')));
  await p.click('#sh-run');
  await p.waitForSelector('#sh-run-out .notice', { timeout: 900000 });
  const again = flat(await p.textContent('#sh-run-out .notice'));
  check('second run creates and updates nothing', /أُنشئ:\s*0/.test(again) && /حُدّث:\s*0/.test(again) && /فشل:\s*0/.test(again), again);

  // Rank Math: one place for title/description
  await p.goto(WP + '/wp-admin/admin.php?page=seohouse-rankmath');
  const rm = flat(await p.textContent('.wrap'));
  check('«نقل السيو إلى Rank Math»: nothing left to move, editor value kept', /سيُنقل: 0/.test(rm) && /يبقى: 1/.test(rm), (rm.match(/سيُنقل: \d+ — مطابق: \d+ — يبقى: \d+/) || [''])[0]);
  await shot('05-rankmath-move');

  const ksaId = await p.evaluate(async (wp) => { const r = await fetch(wp + '/wp-json/wp/v2/pages?slug=ksa'); return (await r.json())[0].id; }, WP);
  await p.goto(`${WP}/wp-admin/post.php?post=${ksaId}&action=edit`);
  await p.waitForSelector('#acf-group_sh_seo', { timeout: 60000 });
  const seoBox = flat(await p.textContent('#acf-group_sh_seo'));
  const dupFields = await p.locator('#acf-group_sh_seo .acf-field[data-name="sh_seo_title"], #acf-group_sh_seo .acf-field[data-name="sh_seo_description"], #acf-group_sh_seo .acf-field[data-name="sh_seo_og_image"], #acf-group_sh_seo .acf-field[data-name="sh_seo_noindex"]').count();
  check('page editor: Core SEO box shows no title/description/image/noindex fields with Rank Math', dupFields === 0 && /Rank Math مفعّلة/.test(seoBox), `${dupFields} duplicate field(s)`);
  check('page editor: breadcrumb name field kept', /الاسم في مسار التنقل/.test(seoBox));
  check('page editor: Rank Math box present', await p.locator('#rank_math_metabox, .rank-math-metabox-wrap, [id*="rank-math"]').count() > 0);
  check('page editor: «التقييمات في هذه الصفحة» field', await p.locator('#acf-group_sh_page_reviews').count() > 0);
  const reviewsGroup = flat(await p.locator('.acf-field[data-name="s_reviews"]').first().textContent().catch(() => ''));
  check('page editor: example testimonials hidden in the reviews section', reviewsGroup && !/الاسم|التقييم:/.test(reviewsGroup.replace(/إخفاء القسم|الوسم|العنوان/g, '')) , reviewsGroup.slice(0, 160));
  await shot('06-page-editor-ksa');

  // platforms library
  await p.goto(WP + '/wp-admin/admin.php?page=seohouse-settings');
  await p.click('a.acf-tab-button:has-text("المحتوى المشترك")');
  const lib = p.locator('.acf-field[data-name="sh_platforms"]');
  const libRows = await lib.locator('.sh-rows__list > .sh-rows__row').count();
  check('settings: «المنصات والأدوات» list with 10 entries', libRows === 10, String(libRows));
  check('settings: internal reference field not shown', await lib.locator('.acf-field[data-name="ref"]').count() === 0);
  await shot('07-settings-platforms');

  check('no JavaScript errors in the admin', errors.length === 0, errors.slice(0, 2).join(' | '));
  await b.close();
  fs.writeFileSync(path.join(OUT, 'upgrade-ui.json'), JSON.stringify({ date: new Date().toISOString(), wp: WP, results }, null, 2));
  const failed = results.filter(r => !r.ok).length;
  console.log(`\n${results.length - failed}/${results.length} passed`);
  process.exit(failed ? 1 : 0);
})().catch(e => { console.error(e); process.exit(2); });
