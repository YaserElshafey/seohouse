#!/usr/bin/env node
/**
 * Builds the handover manifests in docs/ from the pipeline's own data:
 *   page-inventory.csv, routes-manifest.json, acf-field-map.md, assets-manifest.csv, schema-map.csv
 * With --wp <url> it also crawls the local build to record the HTTP status and the JSON-LD
 * node types actually printed on every route.
 *
 * Usage: node tools/docs/build-docs.js [--wp http://127.0.0.1:8080]
 */
const fs = require('fs');
const path = require('path');
const crypto = require('crypto');
const http = require('http');

const ROOT = path.resolve(__dirname, '../..');
const DOCS = path.join(ROOT, 'docs');
const argv = process.argv.slice(2);
const wpIdx = argv.indexOf('--wp');
const WP = wpIdx >= 0 ? argv[wpIdx + 1].replace(/\/$/, '') : '';
fs.mkdirSync(DOCS, { recursive: true });

const readJson = p => JSON.parse(fs.readFileSync(path.join(ROOT, p), 'utf8'));
const csv = rows => rows.map(r => r.map(v => {
  const s = v === null || v === undefined ? '' : String(v);
  return /[",\n]/.test(s) ? '"' + s.replace(/"/g, '""') + '"' : s;
}).join(',')).join('\n') + '\n';

const cfg = readJson('tools/design-import/pages.config.json').pages;
const manifest = readJson('content-pack/manifest.json');
const cases = readJson('content-pack/data/cases.json');
const team = readJson('content-pack/data/team.json');
const posts = readJson('content-pack/data/posts.json');
const cats = readJson('content-pack/data/categories.json');
const seeds = Object.fromEntries(fs.readdirSync(path.join(ROOT, 'content-pack/pages')).map(f => {
  const j = readJson('content-pack/pages/' + f);
  return [j.key, j];
}));

function fetchText(url) {
  return new Promise(resolve => {
    http.get(url, res => {
      let b = '';
      res.setEncoding('utf8');
      res.on('data', c => (b += c));
      res.on('end', () => resolve({ status: res.statusCode, body: b, location: res.headers.location || '' }));
    }).on('error', () => resolve({ status: 0, body: '' }));
  });
}
const enc = route => route.split('/').map(s => (s ? encodeURIComponent(decodeURIComponent(s)) : s)).join('/');

(async () => {
  // ---------------------------------------------------------------- routes
  const routes = [];
  for (const p of cfg) {
    const seed = seeds[p.key] || {};
    let route = p.route, source = 'page';
    if (p.kind === 'cpt' || p.kind === 'system') source = 'template';
    const TEMPLATES = { 'case-template': 'single-case_study.php', 'single-post': 'single.php', 'blog-category': 'archive.php', 'team-member': 'single-team_member.php' };
    let status = seed.status || 'publish';
    if (TEMPLATES[p.key]) { source = 'template'; status = 'template'; route = 'قالب: ' + p.route; }
    else if (p.key.startsWith('case-')) {
      const c = cases.find(x => '/results/' + x.slug + '/' === decodeURIComponent(p.route));
      source = 'case_study';
      if (c) status = c.status;
    }
    routes.push({
      key: p.key,
      route,
      admin_name: p.admin,
      kind: p.kind,
      source,
      design_file: p.file,
      template: seed.template || TEMPLATES[p.key] || (p.key.startsWith('case-') ? 'single-case_study.php' : p.key === 'search' ? 'search.php' : p.key === '404' ? '404.php' : ''),
      status,
      layouts: (manifest.pages.find(m => m.key === p.key) || {}).layouts || []
    });
  }
  for (const m of team) routes.push({ key: 'team:' + m.slug, route: `/team/${m.slug}/`, admin_name: m.name, kind: 'cpt', source: 'team_member', design_file: 'SEO House - Team Member.dc.html', template: 'single-team_member.php', status: 'publish', layouts: [] });
  for (const p of posts) routes.push({ key: 'post:' + p.slug, route: `/blog/${p.slug}/`, admin_name: p.title, kind: 'post', source: 'post', design_file: 'SEO House - Blog Post.dc.html', template: 'single.php', status: 'publish', layouts: [] });
  for (const c of cats) routes.push({ key: 'category:' + c.slug, route: `/blog/category/${c.slug}/`, admin_name: c.name, kind: 'taxonomy', source: 'category', design_file: 'SEO House - Blog Category.dc.html', template: 'archive.php', status: 'publish', layouts: [] });
  const seenRoute = new Set();
  const uniq = routes.filter(r => !seenRoute.has(r.key) && seenRoute.add(r.key));

  // ---------------------------------------------------------------- crawl
  const crawl = {};
  if (WP) {
    for (const r of uniq) {
      const url = r.key === 'search' ? '/?s=' + encodeURIComponent('سيو') : r.key === '404' ? '/__missing-page__/' : r.route.startsWith('/') ? enc(r.route) : '';
      if (!url) continue;
      const res = await fetchText(WP + url);
      const ld = [...res.body.matchAll(/<script type="application\/ld\+json">([\s\S]*?)<\/script>/g)].map(m => { try { return JSON.parse(m[1]); } catch (e) { return null; } }).filter(Boolean);
      const nodes = ld.flatMap(j => j['@graph'] || [j]);
      const robots = (res.body.match(/<meta name='robots' content='([^']*)'/) || res.body.match(/<meta name="robots" content="([^"]*)"/) || [])[1] || '';
      const title = ((res.body.match(/<title>([\s\S]*?)<\/title>/) || [])[1] || '').trim();
      crawl[r.key] = { status: res.status, nodes, robots, title };
    }
  }

  // ---------------------------------------------------------------- page-inventory.csv
  fs.writeFileSync(path.join(DOCS, 'page-inventory.csv'), csv([
    ['key', 'route', 'admin_name', 'kind', 'wp_object', 'status', 'template', 'design_file', 'sections', 'http_status', 'robots', 'title'],
    ...uniq.map(r => [r.key, r.route, r.admin_name, r.kind, r.source, r.status, r.template, r.design_file, r.layouts.join(' '), crawl[r.key]?.status ?? '', crawl[r.key]?.robots ?? '', crawl[r.key]?.title ?? ''])
  ]));

  // ---------------------------------------------------------------- routes-manifest.json
  fs.writeFileSync(path.join(DOCS, 'routes-manifest.json'), JSON.stringify({
    generated: new Date().toISOString().slice(0, 10),
    permalinks: { posts: '/blog/%postname%/', category_base: 'blog/category', case_study: '/results/{slug}/', team_member: '/team/{slug}/', search: '/?s=', search_path_blocked: '/search/ → 404' },
    routes: uniq.map(r => ({ route: r.route, key: r.key, object: r.source, status: r.status, template: r.template, ...(crawl[r.key] ? { http: crawl[r.key].status } : {}) })),
    pending_decision: [
      { route: '/services/seo/stores-seo/', note: 'موجود في الموقع الحالي ولا يوجد له تصميم في الإصدار المعتمد — لم يُنشأ.' },
      { route: '/blog/how-to-build-backlinks-correctly/', note: 'مقال في الموقع الحالي بلا محتوى في التصميم — لم يُنشأ.' }
    ]
  }, null, 2) + '\n');

  // ---------------------------------------------------------------- acf-field-map.md
  const fm = readJson('tools/design-import/extract/field-map.json');
  const md = ['# خريطة حقول ACF', '', 'مولَّدة من `tools/design-import` (مجموعات الصفحات) و`core-groups.js` (المجموعات الثابتة). المفاتيح ثابتة ومشتقة من مسار الحقل، فلا تتغير بإعادة التوليد.', ''];
  const walk = (fields, depth) => fields.flatMap(f => [
    `${'  '.repeat(depth)}- \`${f.name}\` — ${String(f.label || '').replace(/\|/g, '/')} (${f.type})`,
    ...(f.sub_fields ? walk(f.sub_fields, depth + 1) : []),
    ...(f.layouts ? Object.values(f.layouts).flatMap(l => [`${'  '.repeat(depth + 1)}- تخطيط \`${l.name}\` — ${l.label}`, ...walk(l.sub_fields || [], depth + 2)]) : [])
  ]);
  md.push('## المجموعات الثابتة (SEO House Core)', '');
  for (const f of fs.readdirSync(path.join(ROOT, 'wordpress/plugins/seohouse-core/acf-json')).filter(f => !f.startsWith('group_sh_page_')).sort()) {
    const g = readJson('wordpress/plugins/seohouse-core/acf-json/' + f);
    const loc = (g.location || []).map(or => or.map(a => `${a.param} ${a.operator} ${a.value}`).join(' و ')).join(' أو ');
    md.push(`### ${g.title}`, '', `الملف: \`acf-json/${f}\` — يظهر عند: ${loc}`, '', ...walk(g.fields.filter(x => x.type !== 'tab'), 0), '');
  }
  md.push('## أقسام الصفحات (Flexible Content: `sh_sections`)', '', 'كل صفحة تصميم لها مجموعة `group_sh_page_<key>.json` مرتبطة بقالبها. كل تخطيط يحمل أيضًا `sh_hide` (إخفاء القسم) و`sh_anchor` (معرّف القسم).', '');
  const byPage = {};
  for (const l of fm) (byPage[l.page] = byPage[l.page] || []).push(l);
  for (const [page, layouts] of Object.entries(byPage)) {
    const r = cfg.find(c => c.key === page);
    md.push(`### ${r ? r.admin : page} — \`${page}\` (${r ? r.route : ''})`, '');
    for (const l of layouts) md.push(`- **${l.layout}** (${l.label})${l.shared ? ' — مكوّن مشترك' : ''}`, ...walk(l.fields || [], 1));
    md.push('');
  }
  fs.writeFileSync(path.join(DOCS, 'acf-field-map.md'), md.join('\n') + '\n');

  // ---------------------------------------------------------------- assets-manifest.csv
  const rows = [['path', 'package', 'bytes', 'sha1', 'type', 'used_by']];
  const usage = {};
  const scan = (obj, where) => {
    if (Array.isArray(obj)) return obj.forEach(v => scan(v, where));
    if (obj && typeof obj === 'object') {
      if (obj.__asset) (usage[obj.__asset] = usage[obj.__asset] || new Set()).add(where);
      Object.values(obj).forEach(v => scan(v, where));
    } else if (typeof obj === 'string' && /^(assets|uploads|remote)\//.test(obj)) (usage[obj] = usage[obj] || new Set()).add(where);
  };
  for (const [k, s] of Object.entries(seeds)) scan(s, 'page:' + k);
  for (const f of ['cases', 'team', 'posts', 'options', 'menus', 'extra']) scan(readJson(`content-pack/data/${f}.json`), f);
  const listFiles = dir => fs.readdirSync(dir, { withFileTypes: true }).flatMap(e => e.isDirectory() ? listFiles(path.join(dir, e.name)) : [path.join(dir, e.name)]);
  for (const f of listFiles(path.join(ROOT, 'content-pack/assets')).sort()) {
    const rel = path.relative(path.join(ROOT, 'content-pack/assets'), f);
    const buf = fs.readFileSync(f);
    rows.push([rel, 'content', buf.length, crypto.createHash('sha1').update(buf).digest('hex'), path.extname(f).slice(1), [...(usage[rel] || [])].join(' ')]);
  }
  const themeAssets = path.join(ROOT, 'wordpress/themes/seohouse/assets');
  for (const f of listFiles(themeAssets).sort()) {
    const rel = path.relative(themeAssets, f);
    if (/^(css|js)\//.test(rel)) continue;
    const buf = fs.readFileSync(f);
    rows.push(['theme/assets/' + rel, 'theme', buf.length, crypto.createHash('sha1').update(buf).digest('hex'), path.extname(f).slice(1), rel.startsWith('fonts/') ? 'fonts.css' : rel.startsWith('platforms/') ? 'platform logos (sh_svg_img)' : 'header/footer']);
  }
  fs.writeFileSync(path.join(DOCS, 'assets-manifest.csv'), csv(rows));

  // ---------------------------------------------------------------- schema-map.csv
  const designTypes = key => {
    const f = path.join(ROOT, 'tools/design-import/extract', key + '.json');
    if (!fs.existsSync(f)) return '';
    const j = JSON.parse(fs.readFileSync(f, 'utf8'));
    return (j.head.jsonld || []).flatMap(x => { try { const o = typeof x === 'string' ? JSON.parse(x) : x; return (o['@graph'] || [o]).map(n => n['@type']); } catch (e) { return []; } }).join(' + ');
  };
  const sources = {
    Organization: 'إعدادات سيو هاوس ← بيانات الشركة', WebSite: 'إعدادات سيو هاوس', WebPage: 'عنوان SEO + الوصف', AboutPage: 'نوع الصفحة في Schema', ContactPage: 'نوع الصفحة في Schema',
    CollectionPage: 'نوع الصفحة / قالب القائمة', ItemList: 'السجلات المنشورة (دراسات/فريق/مقالات)', Service: 'اسم الخدمة في Schema + الوصف', FAQPage: 'قسم الأسئلة الشائعة الظاهر (نفس الأسئلة)',
    BreadcrumbList: 'sh_breadcrumb_trail() — نفس مسار التنقل الظاهر', BlogPosting: 'المقال + الكاتب من فريق العمل', Article: 'دراسة الحالة', Person: 'عضو الفريق', ProfilePage: 'عضو الفريق', SearchResultsPage: 'البحث'
  };
  const srows = [['route', 'key', 'design_jsonld', 'output_nodes', 'field_sources']];
  for (const r of uniq) {
    const out = crawl[r.key] ? crawl[r.key].nodes.map(n => n['@type']) : [];
    const designKey = r.key.startsWith('team:') ? 'team-member' : r.key.startsWith('post:') ? 'single-post' : r.key.startsWith('category:') ? 'blog-category' : r.key;
    srows.push([r.route, r.key, designTypes(designKey), out.join(' + '), [...new Set(out)].map(t => `${t}: ${sources[t] || ''}`).join(' | ')]);
  }
  fs.writeFileSync(path.join(DOCS, 'schema-map.csv'), csv(srows));

  console.log(`docs: ${uniq.length} routes, ${rows.length - 1} assets${WP ? ', crawled ' + Object.keys(crawl).length : ''}`);
})();
