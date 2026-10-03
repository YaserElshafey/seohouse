#!/usr/bin/env node
/**
 * The owner's 2.5.0 upgrade on an initialised /new/ (Core 2.4.1, theme 2.4.0, articles migrated):
 * upload the two release zips over the installed ones («استبدال الحالي بالمرفوع»), open
 * «تهيئة الموقع», preview, update. Only the privacy policy and terms change; both stay drafts.
 *
 * Usage: node upgrade-2.5-test.js --wp http://127.0.0.1:8096/new --theme seohouse-theme.zip --core seohouse-core.zip --out dir
 */
const path = require('path');
const fs = require('fs');
const { chromium } = require(require.resolve('playwright', { paths: [path.join(__dirname, '../design-import/node_modules')] }));
const argv = process.argv.slice(2), opt = (n, d) => { const i = argv.indexOf('--' + n); return i >= 0 ? argv[i + 1] : d; };
const WP = opt('wp').replace(/\/$/, ''), OUT = opt('out');
fs.mkdirSync(OUT, { recursive: true });
const results = [];
const check = (name, ok, detail = '') => { results.push({ name, ok: !!ok, detail: String(detail).slice(0, 300) }); console.log(`${ok ? 'PASS' : 'FAIL'}  ${name}${detail ? '  — ' + String(detail).slice(0, 200) : ''}`); };
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

  for (const [kind, file] of [['theme', opt('theme')], ['plugin', opt('core')]]) {
    await p.goto(`${WP}/wp-admin/${kind === 'theme' ? 'theme-install.php?upload' : 'plugin-install.php?tab=upload'}`);
    if (kind === 'theme' && !(await p.locator('#install-theme-submit').isVisible())) await p.click('.upload-view-toggle');
    await p.setInputFiles(kind === 'theme' ? '#themezip' : '#pluginzip', file);
    await Promise.all([p.waitForNavigation({ timeout: 120000 }), p.click(kind === 'theme' ? '#install-theme-submit' : '#install-plugin-submit')]);
    const cmp = flat(await p.textContent('.update-from-upload-comparison').catch(() => ''));
    const rep = p.locator('a.update-from-upload-overwrite');
    check(`upload ${path.basename(file)}: WordPress offers «استبدال الحالي بالمرفوع» (2.4 → 2.5.0)`, await rep.count() > 0 && /2\.5\.0/.test(cmp), cmp.slice(0, 160));
    await Promise.all([p.waitForNavigation({ timeout: 120000 }), rep.click()]);
    check(`${kind} replaced`, /تم تحديث|updated successfully|بنجاح/.test(await p.textContent('body')));
  }
  await p.goto(WP + '/wp-admin/plugins.php');
  const row = flat(await p.textContent('tr[data-plugin="seohouse-core/seohouse-core.php"]'));
  check('SEO House Core 2.5.0 active', /2\.5\.0/.test(row) && await p.locator('tr[data-plugin="seohouse-core/seohouse-core.php"].active').count() > 0);
  const css = await (await p.context().request.get(WP + '/wp-content/themes/seohouse/style.css')).text();
  check('theme 2.5.0 active files', /Version: 2\.5\.0/.test(css));

  await p.goto(WP + '/wp-admin/admin.php?page=seohouse-content-setup');
  const info = flat(await p.textContent('#sh-setup'));
  check('setup screen: pack 2.5.0, 125/125 complete', /2\.5\.0/.test(info) && /125 ملفًا \/ 125 متوقعة — كاملة وسليمة/.test(info), (info.match(/حزمة المحتوى.{0,160}/) || [''])[0]);
  check('setup screen: newer pack notice', /حزمة المحتوى 2\.5\.0 أحدث مما هُيّئ به الموقع/.test(info));
  await shot('1-setup');
  await p.click('#sh-preview');
  await p.waitForSelector('#sh-preview-out .notice', { timeout: 300000 });
  const pv = flat(await p.textContent('#sh-preview-out'));
  check('preview: no failures', /فشل:\s*0/.test(pv), pv.slice(0, 200));
  await p.click('#sh-run');
  await p.waitForSelector('#sh-run-out .notice', { timeout: 900000 });
  const done = flat(await p.textContent('#sh-run-out .notice'));
  check('update run: no failures', /فشل:\s*0/.test(done), done);
  const log = await p.$$eval('#sh-run-out table tr', trs => trs.map(t => [...t.children].map(c => c.textContent.trim()).join(' | ')));
  fs.writeFileSync(path.join(OUT, 'run-log.txt'), log.join('\n') + '\n');
  const updatedPages = log.filter(l => /^(updated|حُدّث) \| page:/.test(l)).map(l => l.split(' | ')[1]);
  check('only the privacy policy and terms pages updated', JSON.stringify(updatedPages.sort()) === JSON.stringify(['page:privacy-policy', 'page:terms']), updatedPages.join(' '));
  check('no page reported as protected', !log.some(l => /^(protected|محمي) \| page:/.test(l)), log.filter(l => /^(protected|محمي)/.test(l)).join(' ; '));
  await p.waitForSelector('#sh-check-out table', { timeout: 300000 });
  const rows = await p.$$eval('#sh-check-out tr', trs => trs.map(t => [...t.children].map(c => c.textContent.trim())));
  const bad = rows.filter(r => r[0] !== 'سليم' && r[1] !== 'الروابط الدائمة');
  check('post-update checks all «سليم»', bad.length === 0, bad.map(r => r[1] + ': ' + r[2]).join(' ; '));
  await shot('2-updated');
  check('no JavaScript errors', errors.length === 0, errors.slice(0, 2).join(' | '));
  await b.close();
  fs.writeFileSync(path.join(OUT, 'upgrade.json'), JSON.stringify({ date: new Date().toISOString(), results }, null, 2));
  const failed = results.filter(r => !r.ok).length;
  console.log(`\n${results.length - failed}/${results.length} passed`);
  process.exit(failed ? 1 : 0);
})().catch(e => { console.error(e); process.exit(2); });
