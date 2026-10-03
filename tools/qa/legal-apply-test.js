#!/usr/bin/env node
/**
 * «تهيئة الموقع» → «سياسة الخصوصية والشروط والأحكام»: a published privacy page whose document
 * section was emptied (counts as edited, so «إعادة التهيئة» leaves it) gets the pack's text from
 * the button; it stays published, a copy of its previous values is kept, terms already carry the text.
 *
 * Usage: node legal-apply-test.js --wp http://127.0.0.1:8096/new --new-path /srv/shwpseo/new --out dir
 */
const path = require('path');
const fs = require('fs');
const { execFileSync } = require('child_process');
const { chromium } = require(require.resolve('playwright', { paths: [path.join(__dirname, '../design-import/node_modules')] }));
const argv = process.argv.slice(2), opt = (n, d) => { const i = argv.indexOf('--' + n); return i >= 0 ? argv[i + 1] : d; };
const WP = opt('wp').replace(/\/$/, ''), OUT = opt('out'), NEWP = opt('new-path');
fs.mkdirSync(OUT, { recursive: true });
const results = [];
const check = (name, ok, detail = '') => { results.push({ name, ok: !!ok, detail: String(detail).slice(0, 300) }); console.log(`${ok ? 'PASS' : 'FAIL'}  ${name}${detail ? '  — ' + String(detail).slice(0, 200) : ''}`); };
const wp = code => execFileSync('wp', ['--allow-root', 'eval', code], { cwd: NEWP, encoding: 'utf8' }).trim();
const flat = s => String(s || '').replace(/\s+/g, ' ').trim();

(async () => {
  const otherBefore = wp(`$o=array(); foreach(get_posts(array('post_type'=>'page','post_status'=>'any','posts_per_page'=>-1,'orderby'=>'ID','exclude'=>array(get_page_by_path('privacy-policy')->ID))) as $p){ $o[]=array($p->ID,$p->post_status,get_post_meta($p->ID,'_sh_import_hash',true)); } echo md5(json_encode($o));`);
  const b = await chromium.launch();
  const p = await b.newPage({ viewport: { width: 1400, height: 1000 } });
  const errors = [];
  p.on('pageerror', e => errors.push(e.message));
  p.on('dialog', d => d.accept());
  await p.goto(WP + '/wp-login.php');
  await p.fill('#user_login', 'admin'); await p.fill('#user_pass', 'admin');
  await Promise.all([p.waitForNavigation(), p.click('#wp-submit')]);
  await p.goto(WP + '/wp-admin/admin.php?page=seohouse-content-setup#sh-legal');
  const st = async k => p.locator(`tr[data-legal="${k}"]`).getAttribute('data-state');
  check('box lists both pages: terms already carry the pack text', await st('terms') === 'pack', flat(await p.textContent('tr[data-legal="terms"]')));
  check('privacy (published, document emptied) → «بلا نص», button offered', await st('privacy-policy') === 'placeholder' && await p.locator('tr[data-legal="privacy-policy"] button').count() === 1, flat(await p.textContent('tr[data-legal="privacy-policy"]')));
  await p.screenshot({ path: path.join(OUT, '1-legal-box.png'), fullPage: true });
  await Promise.all([p.waitForNavigation({ timeout: 300000 }), p.click('tr[data-legal="privacy-policy"] button')]);
  const msg = flat(await p.textContent('#sh-setup .notice-success').catch(() => ''));
  check('apply: success message, page still published, copy kept', /طُبّق النص على «سياسة الخصوصية»/.test(msg) && /منشورة/.test(msg), msg);
  check('privacy now carries the pack text', await st('privacy-policy') === 'pack');
  await p.screenshot({ path: path.join(OUT, '2-applied.png'), fullPage: true });
  const pid = wp(`echo get_page_by_path('privacy-policy')->ID;`);
  check('privacy still published', wp(`echo get_post_status(${pid});`) === 'publish');
  check('copy of previous values in _sh_pre_legal_text', wp(`$c=get_post_meta(${pid},'_sh_pre_legal_text',true); echo is_array($c)&&count($c)>=1&&$c[0]['post_status']==='publish' ? 'ok':'no';`) === 'ok');
  const html = await (await p.context().request.get(WP + '/privacy-policy/')).text();
  check('public page shows the sections and the date', /البيانات التي يجمعها الموقع/.test(html) && /آخر تحديث: <time>3 أكتوبر 2026<\/time>/.test(html) && !/يُضاف النص القانوني/.test(html));
  const otherAfter = wp(`$o=array(); foreach(get_posts(array('post_type'=>'page','post_status'=>'any','posts_per_page'=>-1,'orderby'=>'ID','exclude'=>array(get_page_by_path('privacy-policy')->ID))) as $p){ $o[]=array($p->ID,$p->post_status,get_post_meta($p->ID,'_sh_import_hash',true)); } echo md5(json_encode($o));`);
  check('no other page changed', otherAfter === otherBefore);
  check('no JavaScript errors', errors.length === 0, errors.slice(0, 2).join(' | '));
  await b.close();
  fs.writeFileSync(path.join(OUT, 'legal-apply.json'), JSON.stringify({ date: new Date().toISOString(), results }, null, 2));
  const failed = results.filter(r => !r.ok).length;
  console.log(`\n${results.length - failed}/${results.length} passed`);
  process.exit(failed ? 1 : 0);
})().catch(e => { console.error(e); process.exit(2); });
