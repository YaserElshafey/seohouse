#!/usr/bin/env node
/**
 * The site owner's path, in a real browser: upload ACF, the theme and SEO House Core through
 * the admin upload screens, activate, follow «تهيئة الموقع», run the preview (dry run), run the
 * initialisation, read the result check. Screenshots of each screen go to --out.
 *
 * Usage: node setup-ui-test.js --wp http://127.0.0.1:8097/new --acf acf.zip --theme seohouse-theme.zip --core seohouse-core.zip --out dir
 */
const path = require('path');
const fs = require('fs');
const { chromium } = require(require.resolve('playwright', { paths: [path.join(__dirname, '../design-import/node_modules')] }));
const argv = process.argv.slice(2), opt = (n, d) => { const i = argv.indexOf('--' + n); return i >= 0 ? argv[i + 1] : d; };
const WP = opt('wp').replace(/\/$/, ''), OUT = opt('out');
fs.mkdirSync(OUT, { recursive: true });
const results = [];
const check = (name, ok, detail = '') => { results.push({ name, ok: !!ok, detail }); console.log(`${ok ? 'PASS' : 'FAIL'}  ${name}${detail ? '  — ' + detail : ''}`); };

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

  // home before setup
  const before = await (await p.context().request.get(WP + '/')).text();
  check('before setup: home shows the WordPress default post', /أهلاً بالعالم|أهلًا بالعالم|Hello world/.test(before));

  const upload = async (kind, file) => {
    await p.goto(`${WP}/wp-admin/${kind === 'theme' ? 'theme-install.php?upload' : 'plugin-install.php?tab=upload'}`);
    if (kind === 'theme' && !(await p.locator('#install-theme-submit').isVisible())) await p.click('.upload-view-toggle');
    await p.setInputFiles(kind === 'theme' ? '#themezip' : '#pluginzip', file);
    await Promise.all([p.waitForNavigation({ timeout: 120000 }), p.click(kind === 'theme' ? '#install-theme-submit' : '#install-plugin-submit')]);
    const txt = await p.textContent('body');
    const ok = /تم تثبيت|installed successfully|بنجاح/.test(txt);
    check(`upload ${path.basename(file)} through the admin`, ok);
    const act = p.locator('.wrap a:has-text("تفعيل"), .wrap a:has-text("Activate")').first();
    await Promise.all([p.waitForNavigation({ timeout: 120000 }), act.click()]);
  };
  if (opt('acf')) await upload('plugin', opt('acf'));
  else {
    // ACF already installed from Plugins › Add New (WordPress.org): activate it
    await p.goto(WP + '/wp-admin/plugins.php');
    const a = p.locator('tr[data-slug="advanced-custom-fields"] .activate a');
    if (await a.count()) await Promise.all([p.waitForNavigation(), a.click()]);
    check('ACF (from WordPress.org) active', await p.locator('tr[data-slug="advanced-custom-fields"].active').count() > 0);
  }
  await upload('theme', opt('theme'));
  if (opt('core-old')) {
    // the owner's starting point: an older Core already installed and active, site never initialised
    await upload('plugin', opt('core-old'));
    const h = await (await p.context().request.get(WP + '/')).text();
    check('with the old Core: home still empty (WordPress default post)', /أهلاً بالعالم|أهلًا بالعالم|Hello world/.test(h));
    // upload the new Core over it: WordPress offers "replace current with uploaded"
    await p.goto(`${WP}/wp-admin/plugin-install.php?tab=upload`);
    await p.setInputFiles('#pluginzip', opt('core'));
    await Promise.all([p.waitForNavigation({ timeout: 120000 }), p.click('#install-plugin-submit')]);
    await shot('00-replace-offer');
    const rep = p.locator('a.update-from-upload-overwrite');
    check('WordPress offers to replace the installed Core with the uploaded one', await rep.count() > 0);
    await Promise.all([p.waitForNavigation({ timeout: 120000 }), rep.click()]);
    check('Core replaced', /تم تحديث|updated successfully|بنجاح/.test(await p.textContent('body')));
    await p.goto(WP + '/wp-admin/plugins.php');
    check('Core 2.2.0 active after replace', /2\.2\.0/.test(await p.textContent('tr[data-plugin="seohouse-core/seohouse-core.php"]')) && await p.locator('tr[data-plugin="seohouse-core/seohouse-core.php"].active').count() > 0);
    await p.goto(WP + '/wp-admin/admin.php?page=seohouse-content-setup');
  } else {
    await upload('plugin', opt('core'));
    check('activating Core opens «تهيئة الموقع»', p.url().includes('page=seohouse-content-setup'), p.url().replace(WP, ''));
  }

  await shot('01-setup-screen');
  const info = await p.textContent('#sh-setup table');
  const want = opt('pack-files', '125');
  check(`setup screen: bundled content pack found, ${want} files / ${want} expected, complete`, /مضمّنة/.test(info) && new RegExp(`\\b${want} ملفًا / ${want} متوقعة — كاملة وسليمة`).test(info.replace(/\s+/g, ' ')), info.replace(/\s+/g, ' ').trim());

  // the notice on other screens
  await p.goto(WP + '/wp-admin/');
  check('dashboard shows the «تهيئة الموقع الآن» notice', await p.locator('a:has-text("تهيئة الموقع الآن")').count() > 0);
  await shot('00-dashboard-notice');
  await p.click('a:has-text("تهيئة الموقع الآن")');
  await p.waitForSelector('#sh-preview');

  // 1. preview
  await p.click('#sh-preview');
  await p.waitForSelector('#sh-preview-out .notice', { timeout: 120000 });
  const prev = await p.textContent('#sh-preview-out .notice');
  check('preview (dry run) completes', /تشغيل تجريبي/.test(prev), prev.replace(/\s+/g, ' ').trim());
  const pagesAfterPreview = await (await p.context().request.get(WP + '/wp-json/wp/v2/pages?per_page=100')).json();
  check('preview wrote nothing', Array.isArray(pagesAfterPreview) && pagesAfterPreview.length <= 1, `${pagesAfterPreview.length} published page(s)`);
  await shot('02-preview');

  // 2. run
  const t0 = Date.now();
  await p.click('#sh-run');
  await p.waitForSelector('#sh-run-out .notice', { timeout: 900000 });
  const done = await p.textContent('#sh-run-out .notice');
  check('initialisation completes without failures', /اكتملت التهيئة\./.test(done) && /فشل:\s*0/.test(done.replace(/\s+/g, ' ')), done.replace(/\s+/g, ' ').trim() + ` (${Math.round((Date.now() - t0) / 1000)} s)`);
  await p.waitForSelector('#sh-check-out table', { timeout: 300000 });
  const rows = await p.$$eval('#sh-check-out tr', trs => trs.map(t => [...t.children].map(c => c.textContent.trim())));
  for (const r of rows) check('check: ' + r[1], r[0] === 'سليم', r[2]);
  await shot('03-done-and-checks');

  // 3. run again: nothing duplicated
  await p.reload(); await p.waitForSelector('#sh-run');
  await p.click('#sh-run');
  await p.waitForSelector('#sh-run-out .notice', { timeout: 900000 });
  const again = (await p.textContent('#sh-run-out .notice')).replace(/\s+/g, ' ');
  check('second run creates nothing', /أُنشئ:\s*0/.test(again) && /فشل:\s*0/.test(again), again.trim());

  check('no JavaScript errors in the admin', errors.length === 0, errors.slice(0, 2).join(' | '));
  await b.close();
  fs.writeFileSync(path.join(OUT, 'setup-ui.json'), JSON.stringify({ date: new Date().toISOString(), wp: WP, results }, null, 2));
  const failed = results.filter(r => !r.ok).length;
  console.log(`\n${results.length - failed}/${results.length} passed`);
  process.exit(failed ? 1 : 0);
})().catch(e => { console.error(e); process.exit(2); });
