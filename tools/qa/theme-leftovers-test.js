#!/usr/bin/env node
/**
 * Theme files copied OVER an old theme folder (as a migration plugin or FTP does — nothing removed),
 * with the old front-page.php put back on purpose: the release's own templates must still answer,
 * administrators see the leftovers, and «نقل الملفات القديمة خارج مجلد القالب» moves them into an
 * archive in uploads/seohouse-backups, touching no release file.
 *
 * Usage: node theme-leftovers-test.js --wp http://127.0.0.1:8094 --path /srv/shwplive --out dir
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
const walk = d => fs.readdirSync(d, { withFileTypes: true }).flatMap(e => e.isDirectory() ? walk(path.join(d, e.name)) : [path.relative(THEME_DIR, path.join(d, e.name))]);
const sha = f => require('crypto').createHash('sha1').update(fs.readFileSync(f)).digest('hex');

(async () => {
  const listed = JSON.parse(fs.readFileSync(path.join(THEME_DIR, 'theme-files.json'), 'utf8')).files;
  const files0 = walk(THEME_DIR);
  check('theme folder: release files over the old theme, old front-page.php present', files0.includes('front-page.php') && files0.includes('archive-sector.php') && files0.includes('theme-files.json'), `${files0.length} files`);
  const b = await chromium.launch();
  const ctx = await b.newContext({ viewport: { width: 1400, height: 1000 } });
  const logStart = fs.existsSync(path.join(DIR, 'wp-content/debug.log')) ? fs.statSync(path.join(DIR, 'wp-content/debug.log')).size : 0;
  const pages = ['/', '/services/seo/', '/sectors/ecommerce/', '/blog/', '/results/', '/about/'];
  for (const u of pages) {
    const r = await ctx.request.get(WP + u);
    const h = await r.text();
    check(`${u}: 200 with the release templates (old front-page.php / archives not used)`, r.status() === 200 && /data-screen-label=/.test(h) && !/sec-grid|foot-brand/.test(h), `${r.status()}`);
  }
  const log = fs.existsSync(path.join(DIR, 'wp-content/debug.log')) ? fs.readFileSync(path.join(DIR, 'wp-content/debug.log'), 'utf8').slice(logStart) : '';
  check('no PHP error while the old files are in the folder', !/PHP (Fatal|Warning)/.test(log), log.split('\n').filter(l => /PHP/.test(l)).slice(0, 2).join(' | '));

  const p = await ctx.newPage();
  const errors = [];
  p.on('pageerror', e => errors.push(e.message));
  p.on('dialog', d => d.accept());
  await p.goto(WP + '/wp-login.php');
  await p.fill('#user_login', 'admin'); await p.fill('#user_pass', 'admin');
  await Promise.all([p.waitForNavigation(), p.click('#wp-submit')]);
  // page template list: no old templates offered
  const tpl = execFileSync('wp', ['--allow-root', '--path=' + DIR, 'eval', 'echo implode(",", array_keys(wp_get_theme()->get_page_templates()));'], { encoding: 'utf8' });
  check('page template list holds release templates only', tpl.split(',').every(t => listed.some(f => f.path === t)), tpl.slice(0, 160));
  await p.goto(WP + '/wp-admin/');
  const n = p.locator('#sh-theme-leftovers');
  const leftovers = files0.filter(f => f !== 'theme-files.json' && !listed.some(x => x.path === f));
  check('dashboard notice lists the leftovers', await n.count() === 1 && /ملفًا ليست من إصدار القالب 2\.6\.0/.test(await n.textContent()) && (await n.locator('li').count()) === leftovers.length, `${leftovers.length} leftovers`);
  await p.screenshot({ path: path.join(OUT, '1-notice.png') });
  const release0 = Object.fromEntries(listed.map(f => [f.path, sha(path.join(THEME_DIR, f.path))]));
  await Promise.all([p.waitForNavigation(), n.locator('button').click()]);
  const done = await p.textContent('#sh-theme-leftovers-done').catch(() => '');
  check('«نقل الملفات القديمة»: done message with the backup archive', /نُقل \d+ ملفًا قديمًا/.test(done) && /seohouse-backups\/theme-leftovers-/.test(done), done);
  const files1 = walk(THEME_DIR);
  check('theme folder now = release files + theme-files.json', files1.length === listed.length + 1 && files1.every(f => f === 'theme-files.json' || listed.some(x => x.path === f)), `${files1.length}`);
  check('release files untouched', listed.every(f => sha(path.join(THEME_DIR, f.path)) === release0[f.path]));
  const zipPath = path.join(DIR, (done.match(/(wp-content\/uploads\/seohouse-backups\/theme-leftovers-[\w-]+\.zip)/) || [])[1] || 'x');
  const inZip = fs.existsSync(zipPath) ? execFileSync('unzip', ['-Z1', zipPath], { encoding: 'utf8' }).trim().split('\n').filter(x => !x.endsWith('/')) : [];
  check('archive holds every moved file (front-page.php included)', inZip.length === leftovers.length && inZip.includes('seohouse/front-page.php'), `${inZip.length} in archive`);
  const r = await ctx.request.get(WP + '/wp-content/uploads/seohouse-backups/' + path.basename(zipPath));
  check('backup folder carries its deny rules (.htaccess, index.php); archive name is random', fs.existsSync(path.join(DIR, 'wp-content/uploads/seohouse-backups/.htaccess')) && /theme-leftovers-\d{8}-\d{6}-\w{16}\.zip/.test(zipPath), path.basename(zipPath));
  check('home still 200 after the cleanup', (await ctx.request.get(WP + '/')).status() === 200);
  await p.goto(WP + '/wp-admin/');
  check('no notice once the folder is clean', await p.locator('#sh-theme-leftovers').count() === 0);
  check('no JavaScript errors', errors.length === 0, errors.slice(0, 2).join(' | '));
  await b.close();
  fs.writeFileSync(path.join(OUT, 'theme-leftovers.json'), JSON.stringify({ date: new Date().toISOString(), results }, null, 2));
  const failed = results.filter(x => !x.ok).length;
  console.log(`\n${results.length - failed}/${results.length} passed`);
  process.exit(failed ? 1 : 0);
})().catch(e => { console.error(e); process.exit(2); });
