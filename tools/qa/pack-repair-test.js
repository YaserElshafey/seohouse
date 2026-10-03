#!/usr/bin/env node
/**
 * The owner's situation: SEO House Core active on an initialised /new/, but installed from a copy
 * without its content pack (setup screen: pack not found, 0 files). One step — upload the release
 * zip over it («استبدال الحالي بالمرفوع») — must bring the pack: the screen shows every file, the
 * preview (dry run) works, and nothing already on the site changes.
 *
 * Usage: node pack-repair-test.js --wp http://127.0.0.1:8097/new --core dist/seohouse-core.zip --out dir [--files 125]
 */
const path = require('path');
const fs = require('fs');
const { chromium } = require(require.resolve('playwright', { paths: [path.join(__dirname, '../design-import/node_modules')] }));
const argv = process.argv.slice(2), opt = (n, d) => { const i = argv.indexOf('--' + n); return i >= 0 ? argv[i + 1] : d; };
const WP = opt('wp').replace(/\/$/, ''), OUT = opt('out'), N = opt('files', '125');
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

  await p.goto(WP + '/wp-admin/admin.php?page=seohouse-content-setup');
  let t = flat(await p.textContent('.wrap'));
  check('before: the setup screen reports the pack missing (the reported state)', /لم يُعثر على حزمة المحتوى/.test(t) && /عدد الملفات فيه\s*0/.test(t) && /manifest\.json\s*غير موجود/.test(t) && /pack-files\.json غير موجود/.test(t), (t.match(/لم يُعثر[^.]*\./) || [''])[0]);
  check('before: the screen names the cause and links the complete release file', /لا يحتوي مجلد content-pack/.test(t) && await p.locator('a[href*="releases/latest/download/seohouse-core.zip"]').count() > 0);
  await shot('1-before');

  // the one step
  await p.goto(WP + '/wp-admin/plugin-install.php?tab=upload');
  await p.setInputFiles('#pluginzip', opt('core'));
  await Promise.all([p.waitForNavigation({ timeout: 120000 }), p.click('#install-plugin-submit')]);
  const cmp = flat(await p.textContent('.update-from-upload-comparison').catch(() => ''));
  const rep = p.locator('a.update-from-upload-overwrite');
  check('upload the zip: WordPress offers «استبدال الحالي بالمرفوع»', await rep.count() > 0, cmp.slice(0, 140));
  await shot('2-replace-offer');
  await Promise.all([p.waitForNavigation({ timeout: 120000 }), rep.click()]);
  check('replaced', /تم تحديث|updated successfully|بنجاح/.test(await p.textContent('body')));

  await p.goto(WP + '/wp-admin/plugins.php');
  const row = flat(await p.textContent('tr[data-plugin="seohouse-core/seohouse-core.php"]'));
  check('SEO House Core still active, new version', await p.locator('tr[data-plugin="seohouse-core/seohouse-core.php"].active').count() > 0 && /2\.4\.1/.test(row), (row.match(/(الإصدار|Version) [\d.]+/) || [''])[0]);

  await p.goto(WP + '/wp-admin/admin.php?page=seohouse-content-setup');
  t = flat(await p.textContent('#sh-setup'));
  check(`after: setup screen shows ${N} files / ${N} expected — complete`, new RegExp(`${N} ملفًا / ${N} متوقعة — كاملة وسليمة`).test(t) && !/لم يُعثر على حزمة المحتوى/.test(t), (t.match(/حزمة المحتوى.{0,170}/) || [''])[0]);
  await shot('3-after');
  await p.click('#sh-preview');
  await p.waitForSelector('#sh-preview-out .notice', { timeout: 180000 });
  const pv = flat(await p.textContent('#sh-preview-out'));
  check('preview (dry run) works and writes nothing', /تشغيل تجريبي — لم يُكتب شيء/.test(pv) && /فشل:\s*0/.test(pv), pv.slice(0, 160));
  await shot('4-preview');

  check('no JavaScript errors', errors.length === 0, errors.slice(0, 2).join(' | '));
  await b.close();
  fs.writeFileSync(path.join(OUT, 'pack-repair.json'), JSON.stringify({ date: new Date().toISOString(), wp: WP, results }, null, 2));
  const failed = results.filter(r => !r.ok).length;
  console.log(`\n${results.length - failed}/${results.length} passed`);
  process.exit(failed ? 1 : 0);
})().catch(e => { console.error(e); process.exit(2); });
