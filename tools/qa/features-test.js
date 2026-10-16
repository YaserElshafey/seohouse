#!/usr/bin/env node
/**
 * Round 2.3.0 features on a test site (never the live site):
 *  - platforms and tools library: replace a logo, link one, add one and choose it on a page,
 *    reorder (choice order), remove one (the page falls back to the design file, logo kept);
 *  - client logos: order and removal from settings reach the logo track;
 *  - reviews: no section without a shortcode, shared shortcode, page override, invalid code,
 *    one widget per page, design arrows/dots hidden, no overflow at 390 px;
 *  - Rank Math: one title/description/canonical per page, no duplicate schema nodes, an editor's
 *    Rank Math description wins over Core's field.
 * Needs the test shortcode [fake_reviews] (mu-plugin, test only) and two PNG files.
 *
 * Usage: node features-test.js --wp-path /srv/shwpnew/new --url http://127.0.0.1:8097/new --logo-a a.png --logo-b b.png --out dir
 */
const path = require('path');
const fs = require('fs');
const { execFileSync } = require('child_process');
const { chromium } = require(require.resolve('playwright', { paths: [path.join(__dirname, '../design-import/node_modules')] }));
const argv = process.argv.slice(2), opt = (n, d) => { const i = argv.indexOf('--' + n); return i >= 0 ? argv[i + 1] : d; };
const WPP = opt('wp-path'), URL = opt('url').replace(/\/$/, ''), OUT = opt('out');
fs.mkdirSync(OUT, { recursive: true });
const wp = (...a) => execFileSync('wp', ['--allow-root', `--path=${WPP}`, ...a], { encoding: 'utf8' }).trim();
const php = code => wp('eval', code);
const get = async u => (await fetch(URL + u)).text();
const results = [];
const check = (name, ok, detail = '') => { results.push({ name, ok: !!ok, detail }); console.log(`${ok ? 'PASS' : 'FAIL'}  ${name}${detail ? '  — ' + detail : ''}`); };
const LIB = `$rows = get_field( "sh_platforms", "option", false );`;
const SAVE = `update_field( "field_sh_opt_platforms", $rows, "option" );`;
const idx = name => `$i = array_search( "${name}", array_column( $rows, "field_sh_opt_platforms__name" ), true );`;

(async () => {
  // ---------------------------------------------------------------- platforms library
  const a = wp('media', 'import', opt('logo-a'), '--porcelain');
  const bLogo = wp('media', 'import', opt('logo-b'), '--porcelain');
  php(`${LIB} ${idx('سلة')} $rows[$i]["field_sh_opt_platforms__logo"] = ${a}; ${SAVE}`);
  let h = await get('/services/stores/salla/');
  const aFile = path.basename(opt('logo-a'), '.png');
  check('replace a logo in the library → store hero shows it', h.includes(aFile), (h.match(/<img src="[^"]*(test-logo-salla|salla)[^"]*"/) || [''])[0]);
  h = await get('/');
  check('… and the homepage platforms chips show it too', h.includes(aFile));

  php(`${LIB} ${idx('شوبيفاي')} $rows[$i]["field_sh_opt_platforms__url"] = "https://www.shopify.com/"; ${SAVE}`);
  h = await get('/services/stores/shopify/');
  check('optional link on a library entry → logo becomes a link', /<a href="https:\/\/www\.shopify\.com\/" target="_blank" rel="noopener" aria-label="[^"]+" style="display:contents"><img/.test(h));

  php(`${LIB} $rows[] = array( "field_sh_opt_platforms__name" => "زد", "field_sh_opt_platforms__logo" => ${bLogo}, "field_sh_opt_platforms__url" => "", "field_sh_opt_platforms__ref" => "" ); ${SAVE}`);
  const zid = php(`foreach ( sh_core_platforms() as $p ) { if ( "زد" === $p["name"] ) echo $p["value"]; }`);
  check('add a new entry → it gets its own reference', /^sh_row:\d+$/.test(zid), zid);
  // choose it on a page (seo-technical, tools list, first item) the way the editor saves it
  php(`$id = (int) get_page_by_path( "services/seo/technical" )->ID; $f = acf_get_field( "s_tools", $id ); $items = null; foreach ( $f["sub_fields"] as $s ) { if ( "items" === $s["name"] ) $items = $s; }
    $logo = null; foreach ( $items["sub_fields"] as $s ) { if ( "logo" === $s["name"] ) $logo = $s; }
    $g = get_field( $f["key"], $id, false ); $rows = $g[ $items["key"] ]; $rows[0][ $logo["key"] ] = "${zid}"; update_field( $f["key"], array( $items["key"] => $rows ), $id );`);
  h = await get('/services/seo/technical/');
  check('choose the new entry in a page section → its logo shows on that page', h.includes(path.basename(opt('logo-b'), '.png')));

  // reorder: زد first → first choice in the page editors
  php(`${LIB} ${idx('زد')} $z = $rows[$i]; unset( $rows[$i] ); array_unshift( $rows, $z ); ${SAVE}`);
  const choices = php(`define( "WP_ADMIN", true ); set_current_screen( "post" ); $id = (int) get_page_by_path( "services/seo/technical" )->ID; $f = acf_get_field( "s_tools", $id ); foreach ( $f["sub_fields"] as $s ) { if ( "items" === $s["name"] ) { foreach ( $s["sub_fields"] as $x ) { if ( "logo" === $x["name"] ) { $x = acf_get_field( $x["key"] ); echo wp_json_encode( array_values( $x["choices"] ), JSON_UNESCAPED_UNICODE ); } } } }`);
  check('reorder the library → order of the logo choices in page editors', choices.startsWith('["زد"') && choices.includes('سلة'), choices.slice(0, 120));

  // remove an entry still used by pages: the page keeps the design file, never an empty logo
  php(`${LIB} ${idx('ووكومرس')} unset( $rows[$i] ); $rows = array_values( $rows ); ${SAVE}`);
  h = await get('/services/stores/woocommerce/');
  check('remove a library entry used by a page → page falls back to the design logo', /assets\/platforms\/official\/woocommerce\.svg/.test(h));

  // ---------------------------------------------------------------- client logos
  php(`$rows = get_field( "sh_client_logos", "option", false ); $rows = array_reverse( $rows ); array_pop( $rows ); update_field( "field_sh_opt_client_logos", $rows, "option" );`);
  const expected = JSON.parse(php(`echo wp_json_encode( array_column( get_field( "sh_client_logos", "option" ), "name" ), JSON_UNESCAPED_UNICODE );`));
  h = await get('/');
  const track = [...h.matchAll(/<div aria-hidden="false" data-logo-cell[^>]*>(?:<a [^>]*>)?<img[^>]*alt="([^"]*)"/g)].map(m => m[1]);
  check('client logos: reorder and remove in settings → same list and order in the logo track', JSON.stringify(track) === JSON.stringify(expected), `${track.length} logos: ${track.slice(0, 3).join('، ')}…`);

  // ---------------------------------------------------------------- reviews
  php(`update_field( "field_sh_opt_reviews_shortcode", "", "option" );`);
  for (const u of ['/', '/services/seo/', '/services/seo/egypt/', '/services/seo/ksa/', '/services/seo/uae/']) {
    h = await get(u);
    check(`no shortcode → no reviews section, no example testimonials: ${u}`, !/<section data-screen-label="Reviews"/.test(h));
  }
  php(`update_field( "sh_reviews_shortcode_page", "[fake_reviews]", (int) get_page_by_path( "services/seo/egypt" )->ID );`);
  h = await get('/services/seo/egypt/');
  check('page override → reviews widget on that page only', /<section data-screen-label="Reviews"/.test(h) && (h.match(/class="fake-reviews"/g) || []).length === 1 && !/<section data-screen-label="Reviews"/.test(await get('/services/seo/ksa/')));
  php(`update_field( "field_sh_opt_reviews_shortcode", "[fake_reviews]", "option" );`);
  for (const u of ['/', '/services/seo/', '/services/seo/ksa/', '/services/seo/uae/']) {
    h = await get(u);
    check(`shared shortcode → widget in the reviews section, once: ${u}`, (h.match(/class="fake-reviews"/g) || []).length === 1 && (h.match(/<div class="sh-reviews-live" data-reviews-live>/g) || []).length === 1);
  }
  php(`update_field( "field_sh_opt_reviews_shortcode", "<script>alert(1)</script>", "option" ); update_field( "sh_reviews_shortcode_page", "[no_such_plugin]", (int) get_page_by_path( "services/seo/egypt" )->ID );`);
  h = (await get('/')) + (await get('/services/seo/egypt/'));
  check('invalid code (HTML, unregistered shortcode) → treated as empty, nothing printed', !/alert\(1\)|no_such_plugin|<section data-screen-label="Reviews"/.test(h));
  php(`update_field( "field_sh_opt_reviews_shortcode", "[fake_reviews]", "option" ); update_field( "sh_reviews_shortcode_page", "", (int) get_page_by_path( "services/seo/egypt" )->ID );`);

  const b = await chromium.launch();
  for (const w of [1440, 390]) {
    const p = await b.newPage({ viewport: { width: w, height: 900 } });
    for (const u of ['/', '/services/seo/ksa/']) {
      await p.goto(URL + u, { waitUntil: 'networkidle' });
      const r = await p.evaluate(() => {
        const s = document.querySelector('section[data-screen-label="Reviews"]');
        const btns = [...s.querySelectorAll('button[aria-label]')].filter(x => !x.closest('[data-reviews-live]'));
        return { visibleDesignButtons: btns.filter(x => getComputedStyle(x).display !== 'none').length, overflow: document.documentElement.scrollWidth - innerWidth, title: (s.querySelector('h2') || {}).textContent };
      });
      check(`${w}px ${u}: design arrows/dots hidden with the widget, title kept, no overflow`, r.visibleDesignButtons === 0 && r.overflow <= 0 && r.title, JSON.stringify(r));
      await p.locator('section[data-screen-label="Reviews"]').screenshot({ path: path.join(OUT, `reviews-${u.replace(/\//g, '_')}-${w}.png`) });
    }
    await p.close();
  }
  await b.close();

  // ---------------------------------------------------------------- Rank Math: one source
  const ksa = php(`echo (int) get_page_by_path( "services/seo/ksa" )->ID;`);
  php(`update_post_meta( ${ksa}, "rank_math_description", "وصف كتبه المحرر في Rank Math" );`);
  h = await get('/services/seo/ksa/');
  check('editor writes the description in Rank Math → that is the description on the page', (h.match(/<meta name="description" content="([^"]*)"/) || [])[1] === 'وصف كتبه المحرر في Rank Math');
  check('… and the Rank Math title the editor wrote is kept', /<title>عنوان Rank Math كتبه المحرر للسعودية<\/title>/.test(h));

  const pages = ['/', '/services/seo/technical/', '/sectors/ecommerce/', '/services/seo/egypt/', '/services/web-design/react-next/', '/results/', '/team/', '/about/', '/contact/'];
  pages.push(new globalThis.URL(wp('post', 'list', '--post_type=post', '--post_status=publish', '--field=url', '--posts_per_page=1')).pathname.replace(/^\/new/, ''));
  pages.push(new globalThis.URL(wp('post', 'list', '--post_type=case_study', '--post_status=publish', '--field=url', '--posts_per_page=1')).pathname.replace(/^\/new/, ''));
  pages.push(new globalThis.URL(wp('post', 'list', '--post_type=team_member', '--post_status=publish', '--field=url', '--posts_per_page=1')).pathname.replace(/^\/new/, ''));
  const table = [];
  for (const u of pages) {
    h = await get(u);
    const head = h.split('</head>')[0];
    const n = re => (head.match(re) || []).length;
    const graph = [];
    for (const m of h.matchAll(/<script type="application\/ld\+json"[^>]*>([\s\S]*?)<\/script>/g)) {
      const j = JSON.parse(m[1]); for (const x of (j['@graph'] || [j])) graph.push([].concat(x['@type']).join('/'));
    }
    const dup = graph.filter((t, i) => graph.indexOf(t) !== i);
    const counts = { title: n(/<title>/g), description: n(/<meta name="description"/g), canonical: n(/rel="canonical"/g), ogTitle: n(/property="og:title"/g), robots: n(/<meta name=['"]robots['"]/g), ldScripts: (h.match(/application\/ld\+json/g) || []).length };
    // never two of anything; a missing description is reported (home: proposal awaiting approval)
    const ok = Object.entries(counts).every(([k, v]) => v === 1 || (k === 'description' && v === 0)) && !dup.length;
    table.push({ url: decodeURIComponent(u), ...counts, graph: graph.join(', ') });
    check(`one source: ${decodeURIComponent(u)}${counts.description ? '' : ' (no description yet)'}`, ok, `${JSON.stringify(counts)} graph: ${graph.join(', ')}${dup.length ? ' DUP ' + dup : ''}`);
  }
  fs.writeFileSync(path.join(OUT, 'seo-sources.json'), JSON.stringify(table, null, 1));

  fs.writeFileSync(path.join(OUT, 'features.json'), JSON.stringify({ date: new Date().toISOString(), url: URL, results }, null, 2));
  const failed = results.filter(r => !r.ok).length;
  console.log(`\n${results.length - failed}/${results.length} passed`);
  process.exit(failed ? 1 : 0);
})().catch(e => { console.error(e); process.exit(2); });
