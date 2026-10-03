#!/usr/bin/env node
/**
 * «نقل عناوين وأوصاف SEO» in a real browser, on a local pair of sites laid out like production:
 * the main site at the root (its paths and the <title>/description it shows copied from
 * seohouse.agency) and the review copy in /new/.
 *
 * Preview (nothing written) → run → report → run again (nothing written) → an editor edit after
 * the run and a changed main-site title → preview → restore. Also checks what must never change:
 * H1/title, content, slug and status of every record, robots and canonical, the main site's database.
 *
 * Usage: node seo-transfer-test.js --wp http://127.0.0.1:8096/new --new-path /srv/shwpseo/new --src-path /srv/shwpseo --out dir
 */
const path = require('path');
const fs = require('fs');
const { execFileSync } = require('child_process');
const { chromium } = require(require.resolve('playwright', { paths: [path.join(__dirname, '../design-import/node_modules')] }));
const argv = process.argv.slice(2), opt = (n, d) => { const i = argv.indexOf('--' + n); return i >= 0 ? argv[i + 1] : d; };
const WP = opt('wp').replace(/\/$/, ''), OUT = opt('out'), NEWP = opt('new-path'), SRCP = opt('src-path');
fs.mkdirSync(OUT, { recursive: true });
const results = [];
const check = (name, ok, detail = '') => { results.push({ name, ok: !!ok, detail: String(detail).slice(0, 400) }); console.log(`${ok ? 'PASS' : 'FAIL'}  ${name}${detail ? '  — ' + String(detail).slice(0, 220) : ''}`); };
const flat = s => String(s || '').replace(/\s+/g, ' ').trim();
const wp = (dir, code) => execFileSync('wp', ['--allow-root', 'eval', code], { cwd: dir, encoding: 'utf8' }).trim();

// everything that must not change: title (H1), content, slug, status, robots/canonical fields
const FROZEN = `
$o = array();
foreach ( get_posts( array( 'post_type' => array_values( get_post_types( array( 'public' => true ) ) ), 'post_status' => 'any', 'posts_per_page' => -1, 'orderby' => 'ID', 'order' => 'ASC' ) ) as $p ) {
  $o[] = array( $p->ID, $p->post_title, md5( $p->post_content ), $p->post_name, $p->post_status, $p->post_parent, get_post_meta( $p->ID, 'rank_math_robots', true ), get_post_meta( $p->ID, 'rank_math_canonical_url', true ), get_post_meta( $p->ID, 'rank_math_advanced_robots', true ) );
}
foreach ( get_terms( array( 'taxonomy' => array( 'category', 'post_tag' ), 'hide_empty' => false ) ) as $t ) {
  $o[] = array( $t->term_id, $t->name, $t->slug, md5( $t->description ), get_term_meta( $t->term_id, 'rank_math_robots', true ), get_term_meta( $t->term_id, 'rank_math_canonical_url', true ) );
}
$ti = get_option( 'rank-math-options-titles' );
$o[] = array( $ti['homepage_robots'] ?? '', $ti['homepage_custom_robots'] ?? '', get_option( 'blog_public' ), get_option( 'permalink_structure' ) );
echo md5( wp_json_encode( $o ) );`;
const RM_VALUES = `
$o = array();
foreach ( get_posts( array( 'post_type' => array_values( get_post_types( array( 'public' => true ) ) ), 'post_status' => 'any', 'posts_per_page' => -1, 'orderby' => 'ID' ) ) as $p ) { $o[] = array( $p->ID, get_post_meta( $p->ID, 'rank_math_title', true ), get_post_meta( $p->ID, 'rank_math_description', true ) ); }
foreach ( get_terms( array( 'taxonomy' => 'category', 'hide_empty' => false ) ) as $t ) { $o[] = array( $t->term_id, get_term_meta( $t->term_id, 'rank_math_title', true ), get_term_meta( $t->term_id, 'rank_math_description', true ) ); }
echo md5( wp_json_encode( $o ) );`;
const SRC_DB = `global $wpdb; echo md5( wp_json_encode( array( $wpdb->get_results( "SELECT ID, post_title, post_name, post_modified FROM {$wpdb->posts} ORDER BY ID" ), $wpdb->get_results( "SELECT * FROM {$wpdb->postmeta} WHERE meta_key LIKE 'rank_math%' ORDER BY meta_id" ), $wpdb->get_results( "SELECT * FROM {$wpdb->termmeta} ORDER BY meta_id" ) ) ) );`;

(async () => {
  const frozen0 = wp(NEWP, FROZEN), rm0 = wp(NEWP, RM_VALUES), src0 = wp(SRCP, SRC_DB);
  const b = await chromium.launch();
  const p = await b.newPage({ viewport: { width: 1500, height: 1000 } });
  const errors = [];
  p.on('pageerror', e => errors.push(e.message));
  p.on('dialog', d => d.accept());
  const shot = n => p.screenshot({ path: path.join(OUT, n + '.png'), fullPage: true });
  await p.goto(WP + '/wp-login.php');
  await p.fill('#user_login', 'admin'); await p.fill('#user_pass', 'admin');
  await Promise.all([p.waitForNavigation(), p.click('#wp-submit')]);

  const open = async () => { await p.goto(WP + '/wp-admin/admin.php?page=seohouse-seo-transfer'); await p.waitForSelector('#sh-seo-preview'); };
  const press = async (id) => { await Promise.all([p.waitForNavigation({ timeout: 600000 }), p.click(id)]); };
  const row = async (sel, pth) => {
    const r = p.locator(`${sel} tr[data-path="${pth}"]`);
    if (!(await r.count())) return null;
    return {
      title: await r.locator('.sh-act[data-field="title"]').getAttribute('class').catch(() => ''),
      desc: await r.locator('.sh-act[data-field="description"]').getAttribute('class').catch(() => ''),
      text: flat(await r.textContent()),
      cells: await r.locator('td').allTextContents(),
    };
  };
  const act = (r, f) => r ? (r[f].match(/sh-act--(\w+)/) || [])[1] : 'missing';

  // menu + screen
  await p.goto(WP + '/wp-admin/admin.php?page=seohouse-settings');
  check('menu: «نقل عناوين وأوصاف SEO» under سيو هاوس', await p.locator('#adminmenu a[href="admin.php?page=seohouse-seo-transfer"]').count() > 0);
  await open();
  const intro = flat(await p.textContent('#sh-seo-transfer'));
  check('screen names the main site read-only and what is never changed', /قراءة فقط/.test(intro) && /H1/.test(intro) && /robots أو canonical/.test(intro), intro.slice(0, 200));

  // ---- preview
  await press('#sh-seo-preview');
  const summary = flat(await p.textContent('#sh-seo-transfer .notice-info'));
  check('preview: «معاينة — لم يُكتب شيء» with counts', /معاينة — لم يُكتب شيء/.test(summary), summary);
  const heads = (await p.locator('.sh-seo-plan thead th').allTextContents()).map(flat);
  check('preview columns: الرابط، العنوان القديم والجديد، الوصف القديم والجديد، الإجراء المقترح', JSON.stringify(heads) === JSON.stringify(['الرابط', 'عنوان SEO القديم', 'عنوان SEO الجديد', 'الوصف القديم', 'الوصف الجديد', 'الإجراء المقترح']), heads.join(' | '));
  await shot('1-preview');
  const home = await row('.sh-seo-plan', '/');
  check('home (static front page): old title from the main site\'s HTML, action write', home && /سيو هاوس \| نساعد الشركات والمتاجر على النمو/.test(home.cells[1]) && act(home, 'title') === 'write', home && home.cells.slice(1, 3).join(' ‖ '));
  const blog = await row('.sh-seo-plan', '/blog/');
  check('blog archive (posts page): row present, old title «مدونة سيو هاوس | …»', blog && /مدونة سيو هاوس/.test(blog.cells[1]) && /أرشيف المدونة/.test(blog.cells[0]), blog && blog.cells[0]);
  const cat = await row('.sh-seo-plan', '/blog/category/off-page-seo/');
  check('category: template title «سيو خارجي - سيو هاوس» (no field on the main site) → write; empty description → kept', cat && cat.cells[1] === 'سيو خارجي - سيو هاوس' && act(cat, 'title') === 'write' && act(cat, 'desc') === 'empty', cat && cat.text.slice(0, 160));
  const res = await row('.sh-seo-plan', '/results/');
  check('results archive on the main site (custom type archive) matched to the /results/ page here', res && /نتائج الأعمال - سيو هاوس/.test(res.cells[1]) && act(res, 'title') === 'write', res && res.cells.slice(1, 4).join(' ‖ '));
  const sect = await row('.sh-seo-plan', '/sectors/ecommerce/');
  check('sector (custom type on the main site, page here): matched by full path', sect && /تحسين محركات البحث للمتاجر الإلكترونية - سيو هاوس/.test(sect.cells[1]), sect && sect.cells[1]);
  const cs = await row('.sh-seo-plan', '/results/قطاع-الصحة-والطب/');
  check('result: description shown through the %excerpt% template on the main site is read from HTML', cs && /نمو في الزيارات العضوية لعيادة طبية/.test(cs.cells[3]), cs && cs.cells[3]);
  const ksa = await row('.sh-seo-plan', '/services/seo/ksa/');
  check('a title edited here by hand → «معدّل يدويًا هنا — لا يُكتب فوقه»', act(ksa, 'title') === 'manual', ksa && ksa.text.slice(-120));
  const pricing = await row('.sh-seo-plan', '/pricing/');
  check('old description empty → «الوصف القديم فارغ — يبقى الحالي»', act(pricing, 'desc') === 'empty', pricing && pricing.cells[5]);
  const about = await row('.sh-seo-plan', '/about/');
  check('identical title → «متطابق — تخطٍّ»', act(about, 'title') === 'same', about && about.cells[5]);
  const priv = await row('.sh-seo-plan', '/privacy-policy/');
  check('draft here (privacy policy): included, new values computed by Rank Math', priv && /draft/.test(priv.cells[0]) && /غير منشور هنا/.test(priv.text), priv && priv.cells[0]);
  const noSrc = flat(await p.textContent('.sh-nosource').catch(() => ''));
  const srcOnly = flat(await p.textContent('.sh-srconly').catch(() => ''));
  check('full path, not last slug: /thank-you/ here is NOT matched to the main site\'s /contact/thank-you/', /\/thank-you\//.test(noSrc) && /\/contact\/thank-you\//.test(srcOnly) && !(await row('.sh-seo-plan', '/thank-you/')), `here-only: ${noSrc.slice(0, 80)} … main-only: ${srcOnly}`);
  const planWrites = await p.locator('.sh-seo-plan .sh-act--write').count();
  check('preview wrote nothing (Rank Math values unchanged)', wp(NEWP, RM_VALUES) === rm0, `${planWrites} values proposed`);

  // ---- run
  await press('#sh-seo-run');
  const done = flat(await p.textContent('#sh-seo-transfer .notice-success'));
  const written = +((done.match(/كُتبت (\d+) قيمة/) || [])[1] || -1);
  check('run: «اكتمل النقل» — values written = values proposed in the preview', /اكتمل النقل/.test(done) && written === planWrites, done);
  const bak = (done.match(/(wp-content\/uploads\/seohouse-backups\/seo-meta-before-transfer-[\w-]+\.json)/) || [])[1];
  const bakJson = bak ? JSON.parse(fs.readFileSync(path.join(NEWP, bak), 'utf8')) : null;
  check('backup of the current meta values written before the change', bakJson && bakJson.values.length > 50 && bakJson.values.some(v => v.path === '/services/seo/ksa/' && v.title === 'عنوان كتبه المحرر لصفحة السعودية'), bak);
  const rep = flat(await p.textContent('.sh-report-summary'));
  const nums = (rep.match(/مطابق: (\d+) — غير مطابق عن قصد: (\d+) — غير مطابق يحتاج مراجعة: (\d+)/) || []).slice(1).map(Number);
  check('report after the run: no mismatch that needs review', nums.length === 3 && nums[2] === 0, rep);
  const repRows = await p.$$eval('.sh-seo-report tbody tr', trs => trs.map(t => ({ path: t.dataset.path, state: t.dataset.state, text: t.textContent.replace(/\s+/g, ' ') })));
  fs.writeFileSync(path.join(OUT, 'report-rows.json'), JSON.stringify(repRows, null, 1));
  const expected = repRows.filter(r => r.state === 'expected');
  check('«غير مطابق عن قصد» only for: the hand-edited title, or an empty old description', expected.every(r => /معدّل يدويًا|الوصف في الأساسي فارغ/.test(r.text)), expected.map(r => r.path).join(' '));
  check('report row: /services/seo/ksa/ title kept (manual)', /معدّل يدويًا هنا — تُرك/.test((repRows.find(r => r.path === '/services/seo/ksa/') || {}).text || ''));
  await shot('2-after-run');

  // what must not change
  check('H1/title, content, slug, status, robots and canonical of every record unchanged', wp(NEWP, FROZEN) === frozen0);
  check('main site database unchanged (read-only connection)', wp(SRCP, SRC_DB) === src0);
  const svc = await (await p.context().request.get(WP + '/services/')).text();
  check('/new/services/: canonical stays this site\'s (main site has a custom canonical), robots stays noindex', !/example\.com\/canonical-main/.test(svc) && /<meta name="robots" content="noindex/.test(svc));
  const pr = await (await p.context().request.get(WP + '/pricing/')).text();
  check('/new/pricing/: main site\'s noindex,nofollow field not copied (robots = this site\'s staging noindex only)', /<meta name="robots" content="noindex, nofollow"\/>/.test(pr) && wp(NEWP, `echo json_encode(get_post_meta(get_page_by_path('pricing')->ID,'rank_math_robots',true));`) === '""');
  check('privacy policy still a draft', wp(NEWP, `echo get_page_by_path('privacy-policy')->post_status;`) === 'draft');

  // ---- run again
  await open();
  await press('#sh-seo-run');
  const again = flat(await p.textContent('#sh-seo-transfer .notice-success'));
  check('second run writes nothing', /كُتبت 0 قيمة في 0 صفحة/.test(again), again.slice(0, 80));

  // ---- an edit after the transfer + a changed main-site title
  wp(NEWP, `update_post_meta( get_page_by_path('contact')->ID, 'rank_math_title', 'عنوان عدّله المحرر بعد النقل' );`);
  wp(SRCP, `update_post_meta( get_page_by_path('services/seo/technical')->ID, 'rank_math_title', 'السيو التقني لمواقع الشركات | سيو هاوس' );`);
  await open();
  await press('#sh-seo-preview');
  const c2 = await row('.sh-seo-plan', '/contact/');
  check('after the run, an editor edit is protected → manual', act(c2, 'title') === 'manual', c2 && c2.cells.slice(1, 3).join(' ‖ '));
  const t2 = await row('.sh-seo-plan', '/services/seo/technical/');
  check('a value this screen wrote follows a later change on the main site → write', act(t2, 'title') === 'write' && /السيو التقني لمواقع الشركات/.test(t2.cells[1]), t2 && t2.cells.slice(1, 3).join(' ‖ '));
  const w2 = await p.locator('.sh-seo-plan .sh-act--write').count();
  check('nothing else proposed (no duplicates on a repeated run)', w2 === 1, `${w2} write`);
  await shot('3-preview-after-edits');
  wp(SRCP, `delete_post_meta( get_page_by_path('services/seo/technical')->ID, 'rank_math_title' );`); // back to the live value

  // ---- restore
  await press('#sh-seo-restore');
  const rs = flat(await p.textContent('#sh-seo-transfer .notice-success'));
  check('restore: values from before the transfer put back', /أُعيدت \d+ قيمة/.test(rs), rs);
  check('restore keeps the editor\'s later edit', wp(NEWP, `echo get_post_meta( get_page_by_path('contact')->ID, 'rank_math_title', true );`) === 'عنوان عدّله المحرر بعد النقل');
  const egypt = JSON.parse(wp(NEWP, `echo json_encode( get_post_meta( get_page_by_path('services/seo/egypt')->ID, 'rank_math_title', true ) );`));
  const egyptBefore = bakJson.values.find(v => v.path === '/services/seo/egypt/').title;
  check('restore: /services/seo/egypt/ title back to its value before the transfer', egypt === egyptBefore, egypt);
  await shot('4-restored');

  check('no JavaScript errors', errors.length === 0, errors.slice(0, 2).join(' | '));
  await b.close();
  fs.writeFileSync(path.join(OUT, 'seo-transfer.json'), JSON.stringify({ date: new Date().toISOString(), wp: WP, results }, null, 2));
  const failed = results.filter(r => !r.ok).length;
  console.log(`\n${results.length - failed}/${results.length} passed`);
  process.exit(failed ? 1 : 0);
})().catch(e => { console.error(e); process.exit(2); });
