#!/usr/bin/env node
/**
 * Update of a site laid out like seohouse.agency after the move from /new/: a theme folder holding
 * the old main-site theme's files under the new ones (front-page.php renamed front-page.php.old),
 * Core 2.5.0, privacy page published with empty fields (not the page the setup created), terms
 * published with the unapproved 2.5.0 draft. Steps as on the live site: upload the theme zip and
 * the Core zip («استبدال الحالي بالمرفوع»), «إعادة التهيئة», then the legal box.
 *
 * Usage: node live-update-test.js --wp http://127.0.0.1:8094 --path /srv/shwplive --theme seohouse-theme.zip --core seohouse-core.zip --out dir
 */
const path = require('path');
const fs = require('fs');
const { execFileSync } = require('child_process');
const { chromium } = require(require.resolve('playwright', { paths: [path.join(__dirname, '../design-import/node_modules')] }));
const argv = process.argv.slice(2), opt = (n, d) => { const i = argv.indexOf('--' + n); return i >= 0 ? argv[i + 1] : d; };
const WP = opt('wp').replace(/\/$/, ''), OUT = opt('out'), DIR = opt('path');
const THEME_DIR = path.join(DIR, 'wp-content/themes/seohouse');
fs.mkdirSync(OUT, { recursive: true });
const results = [];
const check = (name, ok, detail = '') => { results.push({ name, ok: !!ok, detail: String(detail).slice(0, 400) }); console.log(`${ok ? 'PASS' : 'FAIL'}  ${name}${detail ? '  — ' + String(detail).slice(0, 220) : ''}`); };
const flat = s => String(s || '').replace(/\s+/g, ' ').trim();
const wp = code => execFileSync('wp', ['--allow-root', '--path=' + DIR, 'eval', code], { encoding: 'utf8' }).trim();
const walk = d => fs.readdirSync(d, { withFileTypes: true }).flatMap(e => e.isDirectory() ? walk(path.join(d, e.name)) : [path.relative(THEME_DIR, path.join(d, e.name))]);
const legal = k => JSON.parse(wp(`$s=sh_legal_state('${k}'); echo json_encode(array('state'=>$s['state'],'id'=>$s['page']?$s['page']->ID:0,'status'=>$s['page']?$s['page']->post_status:'','robots'=>$s['robots']));`));
const md = k => fs.readFileSync(path.join(__dirname, '../../docs/legal', k + '.md'), 'utf8');

(async () => {
  const b = await chromium.launch();
  const ctx = await b.newContext({ viewport: { width: 1400, height: 1000 } });
  const p = await ctx.newPage();
  const errors = [];
  p.on('pageerror', e => errors.push(e.message));
  p.on('dialog', d => d.accept());
  const shot = n => p.screenshot({ path: path.join(OUT, n + '.png'), fullPage: true });
  const get = async u => { const r = await ctx.request.get(WP + u, { maxRedirects: 0 }); return { status: r.status(), text: await r.text() }; };
  const logLen = () => { try { return fs.statSync(path.join(DIR, 'wp-content/debug.log')).size; } catch (e) { return 0; } };

  // ---- the state before
  const before = walk(THEME_DIR);
  check('before: theme folder holds old-theme leftovers (front-page.php.old, archive-sector.php, …)', before.includes('front-page.php.old') && before.includes('archive-sector.php'), `${before.length} files`);
  check('before: home answers (front-page.php renamed on the server)', (await get('/')).status === 200);
  const robots0 = {};
  for (const k of ['privacy-policy', 'terms']) {
    const h = (await get(`/${k}/`)).text;
    robots0[k] = (h.match(/<meta name="robots" content="([^"]*)"/) || [])[1];
  }
  const t0 = (await get('/terms/')).text;
  check('before: privacy published, robots index; terms published with [..] fields, robots noindex', /index, follow/.test(robots0['privacy-policy']) && /noindex/.test(robots0.terms) && /\[البريد المعتمد للشركة\]/.test(t0), JSON.stringify(robots0));

  await p.goto(WP + '/wp-login.php');
  await p.fill('#user_login', 'admin'); await p.fill('#user_pass', 'admin');
  await Promise.all([p.waitForNavigation(), p.click('#wp-submit')]);

  // ---- 1. theme zip, «استبدال الحالي بالمرفوع»
  const log1 = logLen();
  for (const [kind, file] of [['theme', opt('theme')], ['plugin', opt('core')]]) {
    await p.goto(`${WP}/wp-admin/${kind === 'theme' ? 'theme-install.php?upload' : 'plugin-install.php?tab=upload'}`);
    if (kind === 'theme' && !(await p.locator('#install-theme-submit').isVisible())) await p.click('.upload-view-toggle');
    await p.setInputFiles(kind === 'theme' ? '#themezip' : '#pluginzip', file);
    await Promise.all([p.waitForNavigation({ timeout: 120000 }), p.click(kind === 'theme' ? '#install-theme-submit' : '#install-plugin-submit')]);
    const cmp = flat(await p.textContent('.update-from-upload-comparison').catch(() => ''));
    const rep = p.locator('a.update-from-upload-overwrite');
    check(`upload ${path.basename(file)}: «استبدال الحالي بالمرفوع» offered (2.5.0 → 2.6.0)`, await rep.count() > 0 && /2\.6\.0/.test(cmp), cmp.slice(0, 140));
    await Promise.all([p.waitForNavigation({ timeout: 120000 }), rep.click()]);
    check(`${kind} replaced`, /تم تحديث|updated successfully|بنجاح/.test(await p.textContent('body')));
    if (kind === 'theme') {
      const after = walk(THEME_DIR);
      const listed = new Set(JSON.parse(fs.readFileSync(path.join(THEME_DIR, 'theme-files.json'), 'utf8')).files.map(f => f.path));
      const extra = after.filter(f => f !== 'theme-files.json' && !listed.has(f));
      check('theme folder after the upload = exactly the release files (old front-page.php.old, archive-sector.php, … gone)', extra.length === 0 && !after.includes('front-page.php.old') && after.length === listed.size + 1, `${after.length} files, extra: ${extra.slice(0, 5).join(' ')}`);
      check('home answers after the theme upload', (await get('/')).status === 200);
    }
  }
  await p.goto(WP + '/wp-admin/plugins.php');
  check('SEO House Core 2.6.0 active', /2\.6\.0/.test(flat(await p.textContent('tr[data-plugin="seohouse-core/seohouse-core.php"]'))));

  // ---- 2. «إعادة التهيئة»
  await p.goto(WP + '/wp-admin/admin.php?page=seohouse-content-setup');
  check('no «ملفات قديمة» notice after the upload', await p.locator('#sh-theme-leftovers').count() === 0);
  await p.click('#sh-preview');
  await p.waitForSelector('#sh-preview-out .notice', { timeout: 300000 });
  check('preview: no failures', /فشل:\s*0/.test(flat(await p.textContent('#sh-preview-out'))));
  await p.click('#sh-run');
  await p.waitForSelector('#sh-run-out .notice', { timeout: 900000 });
  const done = flat(await p.textContent('#sh-run-out .notice'));
  check('update run: no failures', /فشل:\s*0/.test(done), done);
  const log = await p.$$eval('#sh-run-out table tr', trs => trs.map(t => [...t.children].map(c => c.textContent.trim()).join(' | ')));
  fs.writeFileSync(path.join(OUT, 'run-log.txt'), log.join('\n') + '\n');
  check('the update did not write the legal pages', !log.some(l => /^(updated|حُدّث) \| page:(privacy-policy|terms)/.test(l)), log.filter(l => /privacy|terms/.test(l)).join(' ; '));
  await p.waitForSelector('#sh-check-out table', { timeout: 300000 });
  const rows = await p.$$eval('#sh-check-out tr', trs => trs.map(t => [...t.children].map(c => c.textContent.trim())));
  const bad = rows.filter(r => r[0] !== 'سليم' && r[1] !== 'الروابط الدائمة');
  const flagged = bad.map(r => r[1]).sort();
  check('post-update checks flag exactly: legal pages published without approved text, and the missing redirect of the old site', JSON.stringify(flagged) === JSON.stringify(['الصفحات القانونية', 'تحويلات روابط الموقع السابق'].sort()) && bad.every(r => /انظر «/.test(r[2])), bad.map(r => r[1] + ': ' + r[2]).join(' ; '));
  check('before: /services/seo/stores-seo/ answers 404 (the old site redirected it 301)', (await get('/services/seo/stores-seo/')).status === 404);
  check('terms unchanged by the update (still the 2.5.0 draft until approved here)', /\[البريد المعتمد للشركة\]/.test((await get('/terms/')).text));

  // ---- 3. the legal box
  await p.goto(WP + '/wp-admin/admin.php?page=seohouse-content-setup#sh-legal');
  const st = async k => p.locator(`tr[data-legal="${k}"]`).getAttribute('data-state');
  check('box: privacy (published, empty) → automatic, button offered; notes the other page the setup created', await st('privacy-policy') === 'automatic' && /صفحة أخرى أنشأتها التهيئة/.test(flat(await p.textContent('tr[data-legal="privacy-policy"]'))), flat(await p.textContent('tr[data-legal="privacy-policy"]')).slice(0, 200));
  check('box: terms (2.5.0 draft) → automatic', await st('terms') === 'automatic', flat(await p.textContent('tr[data-legal="terms"]')).slice(0, 160));
  await shot('1-legal-box');
  for (const k of ['privacy-policy', 'terms']) {
    await Promise.all([p.waitForNavigation({ timeout: 300000 }), p.click(`tr[data-legal="${k}"] button[name="sh_legal_apply"]`)]);
    const m = flat(await p.textContent('#sh-setup .notice-success').catch(() => ''));
    check(`apply ${k}: approved text written, copy kept, page stays published`, /طُبّق النص المعتمد/.test(m) && /منشورة/.test(m) && await st(k) === 'pack', m.slice(0, 200));
  }
  await shot('2-applied');
  check('«تحويلات الموقع السابق» lists /services/seo/stores-seo/ → /sectors/ecommerce/', /\/services\/seo\/stores-seo\//.test(flat(await p.textContent('[data-redirects="missing"]').catch(() => ''))));
  await Promise.all([p.waitForNavigation({ timeout: 120000 }), p.click('button[name="sh_redirects_add"]')]);
  check('redirect added; existing ones kept', /أُضيف 1 تحويل/.test(flat(await p.textContent('#sh-setup'))) && await p.locator('[data-redirects="ok"]').count() === 1);
  const rd = await get('/services/seo/stores-seo/');
  check('/services/seo/stores-seo/ → 301 /sectors/ecommerce/ again', rd.status === 301);
  await p.click('#sh-check');
  await p.waitForSelector('#sh-check-out table', { timeout: 300000 });
  const rows2 = await p.$$eval('#sh-check-out tr', trs => trs.map(t => [...t.children].map(c => c.textContent.trim())));
  const bad2 = rows2.filter(r => r[0] !== 'سليم' && r[1] !== 'الروابط الدائمة');
  check('after applying: all checks «سليم» (legal pages carry the approved text)', bad2.length === 0, bad2.map(r => r[1] + ': ' + r[2]).join(' ; '));

  // ---- 4. the pages
  for (const k of ['privacy-policy', 'terms']) {
    const r = await get(`/${k}/`);
    const h = r.text;
    const src = md(k);
    const titles = [...src.matchAll(/^## (.+)$/gm)].map(m => m[1].trim());
    const firstPara = src.split('\n').find(l => l && !l.startsWith('#') && !l.startsWith('---') && !/^(title|updated|source):/.test(l)).replace(/<[^>]+>/g, '').slice(0, 60);
    const text = h.replace(/<[^>]+>/g, '').replace(/\s+/g, ' ');
    check(`/${k}/: 200, H1, every approved heading, first paragraph, «آخر تحديث: 30 مايو 2026»`, r.status === 200 && /<h1[^>]*>[^<]+<\/h1>/.test(h) && titles.every(t => text.includes(t)) && text.includes(firstPara.replace(/\s+/g, ' ')) && /آخر تحديث: <time>30 مايو 2026<\/time>/.test(h), `${titles.length} headings`);
    check(`/${k}/: nothing in square brackets, no placeholder`, !/\[(البريد|الدولة|المدينة|الاسم القانوني)/.test(h) && !/يُضاف النص القانوني/.test(h));
    const toc = [...h.matchAll(/<nav data-lg-nav[\s\S]*?<\/nav>/g)].map(m => [...m[0].matchAll(/href="#([pt]\d+)"/g)].map(x => x[1]))[0] || [];
    check(`/${k}/: contents list = section headings, each link has its target`, toc.length === titles.length && toc.every(id => h.includes(`id="${id}"`)), toc.join(' '));
    const indexable = !/noindex/.test(robots0[k]);
    check(`/${k}/: robots as before (${robots0[k]}), canonical this address${indexable ? '' : ' (Rank Math prints none for noindex)'}`, (h.match(/<meta name="robots" content="([^"]*)"/) || [])[1] === robots0[k] && (indexable ? h.includes(`<link rel="canonical" href="${WP}/${k}/"`) : !/rel="canonical"/.test(h)));
    check(`${k}: still published; copy of previous values in _sh_pre_legal_text`, wp(`$p=sh_legal_page('${k}'); $c=get_post_meta($p->ID,'_sh_pre_legal_text',true); echo $p->post_status.'|'.(is_array($c)&&count($c)?'copy':'none');`) === 'publish|copy');
  }
  // editable from the dashboard
  const pid = legal('privacy-policy').id;
  await p.goto(`${WP}/wp-admin/post.php?post=${pid}&action=edit`);
  await p.waitForSelector('.acf-field[data-name="items_2"]', { state: 'attached', timeout: 60000 });
  const rowsN = await p.locator('.acf-field[data-name="items_2"] .sh-rows__row:not(.acf-clone)').count();
  const bodies = await p.locator('.acf-field[data-name="items_2"] .sh-rows__row:not(.acf-clone) .acf-field[data-name="body"] textarea').count();
  check('editor: «الأقسام» list with a title and a text box per section, «تاريخ آخر تحديث» field', rowsN === 7 && bodies === 7 && await p.locator('.acf-field[data-name="updated"] input').count() === 1, `${rowsN} rows`);
  await shot('3-editor');

  // ---- 5. a hand edit is never replaced
  wp(`$p=sh_legal_page('terms'); $d=get_field('s_document',$p->ID); $d['items_2'][1]['body'].="\\n\\nفقرة أضافها المحرر."; update_field('s_document',$d,$p->ID); echo 'ok';`);
  await p.goto(WP + '/wp-admin/admin.php?page=seohouse-content-setup#sh-legal');
  check('after an editor change: terms → «عُدّل يدويًا», no button', await st('terms') === 'manual' && await p.locator('tr[data-legal="terms"] button[name="sh_legal_apply"]').count() === 0);
  const { spawnSync } = require('child_process');
  const cli = spawnSync('wp', ['--allow-root', '--path=' + DIR, 'seohouse', 'legal', '--apply=terms'], { encoding: 'utf8' });
  const refused = String(cli.stdout) + String(cli.stderr);
  check('CLI apply refuses the edited page', /لم يُكتب شيء/.test(refused), refused.slice(0, 160));
  check('editor paragraph still on the page', (await get('/terms/')).text.includes('فقرة أضافها المحرر.'));

  const newLog = fs.existsSync(path.join(DIR, 'wp-content/debug.log')) ? fs.readFileSync(path.join(DIR, 'wp-content/debug.log'), 'utf8').slice(log1) : '';
  const phpErr = newLog.split('\n').filter(l => /PHP (Fatal|Warning|Notice|Parse|Deprecated)/.test(l) && !/plugins\/(seo-by-rank-math|advanced-custom-fields)\//.test(l));
  check('no PHP errors from the theme or Core during the update', phpErr.length === 0, phpErr.slice(0, 3).join(' | '));
  check('no JavaScript errors', errors.length === 0, errors.slice(0, 2).join(' | '));
  await b.close();
  fs.writeFileSync(path.join(OUT, 'live-update.json'), JSON.stringify({ date: new Date().toISOString(), results }, null, 2));
  const failed = results.filter(r => !r.ok).length;
  console.log(`\n${results.length - failed}/${results.length} passed`);
  process.exit(failed ? 1 : 0);
})().catch(e => { console.error(e); process.exit(2); });
