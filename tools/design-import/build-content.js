#!/usr/bin/env node
/**
 * Step 3 — assemble the content pack consumed by the SEO House Core importer:
 *   content-pack/data/{team,cases,posts,categories,menus,options}.json
 *   content-pack/assets/**   (every design asset the pages, records and options reference)
 * Page section values are written by convert.js (content-pack/pages/*.json).
 *
 * Usage: node build-content.js <design-dir> [repo-root]
 * Remote images referenced by the approved design (hot-linked in the preview) are downloaded
 * once into the pack so the importer never depends on the designer's machine or on the old site.
 */
const fs = require('fs');
const path = require('path');
const { execFileSync } = require('child_process');
const cheerio = require('cheerio');

/** Plain article HTML (p, h2–h4 with id, ul/ol) → Gutenberg block markup. */
function htmlToBlocks(html) {
  const $ = cheerio.load(html, null, false);
  const out = [];
  $.root().children().each((_, e) => {
    const tag = e.tagName.toLowerCase(), $e = $(e);
    if (tag === 'p') out.push(`<!-- wp:paragraph -->\n<p>${$e.html().trim()}</p>\n<!-- /wp:paragraph -->`);
    else if (/^h[2-4]$/.test(tag)) {
      const lvl = +tag[1], id = $e.attr('id');
      const attrs = Object.assign(lvl === 2 ? {} : { level: lvl }, id ? { anchor: id } : {});
      out.push(`<!-- wp:heading${Object.keys(attrs).length ? ' ' + JSON.stringify(attrs) : ''} -->\n<${tag} class="wp-block-heading"${id ? ` id="${id}"` : ''}>${$e.html().trim()}</${tag}>\n<!-- /wp:heading -->`);
    } else if (tag === 'ul' || tag === 'ol') {
      const items = $e.children('li').toArray().map(li => `<!-- wp:list-item -->\n<li>${$(li).html().trim()}</li>\n<!-- /wp:list-item -->`).join('\n');
      out.push(`<!-- wp:list${tag === 'ol' ? ' {"ordered":true}' : ''} -->\n<${tag} class="wp-block-list">${items}</${tag}>\n<!-- /wp:list -->`);
    } else throw new Error('unsupported tag in draft: ' + tag);
  });
  return out.join('\n\n');
}

const designDir = path.resolve(process.argv[2] || '');
const repo = path.resolve(process.argv[3] || path.join(__dirname, '..', '..'));
const PACK = path.join(repo, 'content-pack');
const DATA = path.join(PACK, 'data');
const ASSETS = path.join(PACK, 'assets');
fs.mkdirSync(DATA, { recursive: true });
const write = (f, o) => fs.writeFileSync(path.join(DATA, f), JSON.stringify(o, null, 1) + '\n');
const log = [];

// ------------------------------------------------------------------ assets
function addAsset(rel) {
  if (!rel) return null;
  if (/^https?:\/\//.test(rel)) {
    const name = decodeURIComponent(rel.split('/').pop()).replace(/[^\p{L}\p{N}._-]+/gu, '-');
    const out = `remote/${name}`;
    const dest = path.join(ASSETS, out);
    if (!fs.existsSync(dest)) {
      fs.mkdirSync(path.dirname(dest), { recursive: true });
      try { execFileSync('curl', ['-sSfL', '-m', '60', '-o', dest, encodeURI(decodeURI(rel))]); log.push(`downloaded ${rel}`); }
      catch (e) { log.push(`MISSING remote asset ${rel}`); return null; }
    }
    return out;
  }
  const src = path.join(designDir, rel);
  if (!fs.existsSync(src)) { log.push(`MISSING design asset ${rel}`); return null; }
  const dest = path.join(ASSETS, rel);
  fs.mkdirSync(path.dirname(dest), { recursive: true });
  fs.copyFileSync(src, dest);
  return rel;
}
const manifest = JSON.parse(fs.readFileSync(path.join(PACK, 'manifest.json'), 'utf8'));
for (const a of manifest.assets) if (!/\.svg$/i.test(a)) addAsset(a);

// ------------------------------------------------------------------ team
const teamSrc = fs.readFileSync(path.join(designDir, 'team-data.js'), 'utf8');
const w = {}; new Function('window', teamSrc)(w);
const photos = JSON.parse(fs.readFileSync(path.join(__dirname, 'team-photos.json'), 'utf8'));
const team = w.SH_TEAM.map((m, i) => ({
  key: `team:${m.slug}`, slug: m.slug, name: m.name, role: m.title, experience: m.exp || '',
  specialties: (m.tags || []).map(t => ({ label: t })), linkedin: m.linkedin || '',
  photo: addAsset(photos[m.slug]) , order: i
}));
write('team.json', team);

// ------------------------------------------------------------------ cases
async function cases() {
  const mod = await import('file://' + path.join(designDir, 'case-studies-data.js'));
  const out = mod.CASES.map((c, i) => {
    let slug = decodeURIComponent(c.href.replace(/^\/results\//, '').replace(/\/$/, ''));
    return {
      key: `case:${c.slug}`, slug, title: c.title, status: c.draft ? 'draft' : 'publish', order: i,
      noindex: !!c.draft,
      fields: {
        summary: c.summary || '', crumb: c.crumb || '', result: c.result || '', result_label: c.resultLabel || '',
        sector: c.sector || '', market: c.market || '', filter: (c.tags || []).filter(t => ['ecommerce', 'health', 'education', 'real-estate', 'tourism', 'tech', 'legal', 'food'].includes(t)),
        client: c.client || '', client_public: c.client ? 1 : 0, client_url: c.clientHref || '',
        duration: c.duration || '', source: c.source || '', period: c.period || '',
        challenge_title: c.challenge?.title || '', challenge_text: c.challenge?.text || '',
        solution_title: c.solution?.title || '', solution_intro: c.solution?.intro || '',
        steps: (c.solution?.steps || []).map(s => ({ title: s.title, text: s.text || '' })),
        results_text: c.resultsText || '',
        metrics: (c.metrics || []).map(m => ({ label: m.label, unit: '', before: m.before || '', after: m.after || '', change: m.change || '', source: '', screenshot: null })),
        period_before: c.periodBefore || '', period_after: c.periodAfter || '',
        gallery: (c.gallery || []).map(g => ({ image: { __asset: addAsset(g.src), alt: g.alt || '' }, alt: g.alt || '', caption: g.caption || '', source: g.source || '', verified: g.verified === false ? 0 : 1 })),
        service_label: c.service?.label || '', service_page: c.service?.href ? { __route: c.service.href } : null,
        review_notes: (c.reviewNotes || []).join('\n')
      }
    };
  });
  write('cases.json', out);
}

// ------------------------------------------------------------------ article(s)
function posts() {
  const x = JSON.parse(fs.readFileSync(path.join(__dirname, 'extract', 'single-post.json'), 'utf8'));
  const $ = cheerio.load(x.html, null, false);
  $('span.sc-interp').each((_, e) => { $(e).replaceWith($(e).contents()); });
  $('[data-dc-tpl]').removeAttr('data-dc-tpl');
  const sec = $('section[data-screen-label="Article"]');
  const body = sec.find('article[data-art-body]');
  const blocks = [];
  body.children().each((_, el) => {
    const $e = $(el); const tag = el.name;
    const html = $.html($e).replace(/\sclass="scp\d+"/g, '');
    if (tag === 'p') blocks.push(`<!-- wp:paragraph -->\n${html}\n<!-- /wp:paragraph -->`);
    else if (/^h[2-4]$/.test(tag)) {
      const lvl = +tag[1]; const id = $e.attr('id');
      blocks.push(`<!-- wp:heading ${JSON.stringify(lvl === 2 ? { anchor: id } : { level: lvl, anchor: id }).replace(/"anchor":undefined,?/, '')} -->\n<${tag} class="wp-block-heading"${id ? ` id="${id}"` : ''}>${$e.html()}</${tag}>\n<!-- /wp:heading -->`);
    } else if (tag === 'ul' || tag === 'ol') {
      const items = $e.children('li').toArray().map(li => `<!-- wp:list-item -->\n<li>${$(li).html()}</li>\n<!-- /wp:list-item -->`).join('\n');
      blocks.push(`<!-- wp:list${tag === 'ol' ? ' {"ordered":true}' : ''} -->\n<${tag} class="wp-block-list">${items}</${tag}>\n<!-- /wp:list -->`);
    } else if (tag === 'blockquote') {
      blocks.push(`<!-- wp:quote -->\n<blockquote class="wp-block-quote"><!-- wp:paragraph -->\n<p>${$e.html()}</p>\n<!-- /wp:paragraph --></blockquote>\n<!-- /wp:quote -->`);
    } else if ($e.is('[data-art-table]')) {
      blocks.push(`<!-- wp:table -->\n<figure class="wp-block-table">${$.html($e.find('table'))}</figure>\n<!-- /wp:table -->`);
    } else {
      // editorial callouts designed inside the article (note / inline CTA)
      blocks.push(`<!-- wp:html -->\n${html}\n<!-- /wp:html -->`);
    }
  });
  const meta = sec.find('header');
  const catA = meta.find('a').first();
  const img = sec.find('figure img').first();
  const intro = body.children('p').first().text().trim();
  const post = {
    key: 'post:ai-search-console-reports',
    slug: 'هل-يظهر-موقعك-داخل-إجابات-جوجل-بالذكاء',
    slug_note: 'المسار العام المنشور حاليًا لهذا المقال (تُحقق منه من خريطة الموقع العامة للمسار فقط).',
    title: $('h1').first().text().trim(),
    date: '2026-06-03 17:00:00',
    author_member: 'team:mona-ali',
    category: { name: catA.text().trim(), slug: (catA.attr('href') || '').split('/').filter(Boolean).pop() },
    featured: img.length ? { __asset: addAsset(img.attr('src')), alt: img.attr('alt') || '' } : null,
    excerpt: intro,
    content: blocks.join('\n\n'),
    fields: { intro: '', toc: 1, sidebar_cta: 1, updated_label: 1 }
  };
  // Draft articles for published URLs whose body is not in the design (new text, for review — see drafts/*.html)
  const drafts = fs.readdirSync(path.join(__dirname, 'drafts')).filter(f => f.endsWith('.html')).sort().map(f => {
    const src = fs.readFileSync(path.join(__dirname, 'drafts', f), 'utf8');
    const head = Object.fromEntries((src.match(/<!--([\s\S]*?)-->/)[1]).split('\n').map(l => l.match(/^\s*([a-z]+):\s*(.*)$/)).filter(Boolean).map(m => [m[1], m[2].trim()]));
    const [catSlug, catName] = head.category.split('|').map(x => x.trim());
    return {
      key: 'post:' + head.slug, slug: head.slug, status: 'draft', review: head.status,
      title: head.title, date: '', author_member: '', category: { name: catName, slug: catSlug }, featured: null,
      excerpt: head.excerpt, content: htmlToBlocks(src.replace(/<!--[\s\S]*?-->/, '')),
      fields: { intro: '', toc: 1, sidebar_cta: 1, updated_label: 0 }
    };
  });
  write('posts.json', [post, ...drafts]);
  const catX = JSON.parse(fs.readFileSync(path.join(__dirname, 'extract', 'blog-category.json'), 'utf8'));
  const $c = cheerio.load(catX.html, null, false);
  const catH1 = $c('h1').first().text().trim();
  const catP = $c('h1').first().nextAll('p').first().text().trim();
  // only categories that hold an article; the category template's chips and description are preview text
  const cats = new Map([[post.category.slug, post.category.name], ...drafts.map(d => [d.category.slug, d.category.name])]);
  write('categories.json', [...cats].map(([slug, name]) => ({ key: 'cat:' + slug, name, slug, description: '' })));
  void catH1; void catP;
  const blogX = JSON.parse(fs.readFileSync(path.join(__dirname, 'extract', 'blog.json'), 'utf8'));
  const $b = cheerio.load(blogX.html, null, false);
  write('extra.json', { 'page:blog': { sh_blog_intro: $b('h1').first().nextAll('p').first().text().trim() } });
  log.push(`posts: 1 article from the design + ${drafts.length} draft articles for published URLs (new text for review).`);
}

// ------------------------------------------------------------------ menus
function menus() {
  const S = (label, href, extra = {}) => ({ label, route: href, ...extra });
  const primary = [
    S('الرئيسية', '/'),
    S('الخدمات', '/services/', { all_label: 'جميع الخدمات', layout: 'list', children: [
      S('تحسين محركات البحث', '/services/seo/', { desc: 'ظهور صفحات موقعك في نتائج البحث', icon: 'search' }),
      S('تصميم المواقع', '/services/web-design/', { desc: 'مواقع شركات سريعة وسهلة الإدارة', icon: 'window' }),
      S('تصميم المتاجر', '/services/stores/', { desc: 'متاجر على سلة وزد وشوبيفاي وووكومرس', icon: 'bag' }),
      S('رفع المنتجات', '/services/products/', { desc: 'إدخال المنتجات وتنظيم بياناتها', icon: 'upload' })
    ] }),
    S('نتائج الأعمال', '/results/'), S('عن الشركة', '/about/'), S('فريق العمل', '/team/'),
    S('القطاعات', '/sectors/', { all_label: 'جميع القطاعات', layout: 'grid2', children: [
      ['التجارة الإلكترونية', '/sectors/ecommerce/'], ['الصحة والطب', '/sectors/health/'], ['العقارات والبناء', '/sectors/real-estate/'], ['التعليم والتدريب', '/sectors/education/'],
      ['السياحة والسفر', '/sectors/tourism/'], ['التقنية والبرمجيات', '/sectors/tech/'], ['القانون والاستشارات', '/sectors/legal/'], ['الأغذية والمطاعم', '/sectors/food/']
    ].map(([l, h]) => S(l, h)) }),
    S('المدونة', '/blog/'), S('الأسعار', '/pricing/'), S('اتصل بنا', '/contact/')
  ];
  const col = (label, items) => ({ label, route: null, children: items.map(([l, h]) => S(l, h)) });
  const footer = [
    col('السيو', [['تحسين محركات البحث', '/services/seo/'], ['السيو التقني', '/services/seo/technical/'], ['السيو الداخلي', '/services/seo/on-page/'], ['كتابة المحتوى', '/services/seo/content/'], ['السيو الخارجي', '/services/seo/backlinks/'], ['الاستشارات وتحليل الأداء', '/services/seo/consulting/']]),
    col('المواقع والمتاجر', [['تصميم المواقع', '/services/web-design/'], ['تصميم المتاجر', '/services/stores/'], ['رفع وإدارة المنتجات', '/services/products/'], ['كل الخدمات', '/services/']]),
    col('الأسواق والقطاعات', [['السعودية', '/services/seo/ksa/'], ['مصر', '/services/seo/egypt/'], ['الإمارات', '/services/seo/uae/'], ['القطاعات', '/sectors/']]),
    col('الشركة', [['عن سيو هاوس', '/about/'], ['فريق العمل', '/team/'], ['النتائج', '/results/'], ['المدونة', '/blog/'], ['الأسعار', '/pricing/'], ['اتصل بنا', '/contact/']])
  ];
  const legal = [S('سياسة الخصوصية', '/privacy-policy/'), S('الشروط والأحكام', '/terms/')];
  write('menus.json', { primary: { name: 'القائمة الرئيسية', items: primary }, footer: { name: 'أعمدة الفوتر', items: footer }, legal: { name: 'روابط أسفل الفوتر', items: legal } });
}

// ------------------------------------------------------------------ options
function options() {
  const home = JSON.parse(fs.readFileSync(path.join(__dirname, 'extract', 'about.json'), 'utf8'));
  const $ = cheerio.load(home.html, null, false);
  const footerText = $('footer p').first().text().trim();
  const logosSrc = fs.readFileSync(path.join(designDir, 'SEO House - Homepage.dc.html'), 'utf8').match(/const CLIENT_LOGOS = (\[[\s\S]*?\]);/)[1];
  const logos = Function("window", `return ${logosSrc}`)({}).map(l => ({ name: l.label, logo: { __asset: addAsset(l.src), alt: l.label }, url: '' }));
  const booking = JSON.parse(fs.readFileSync(path.join(PACK, 'booking-defaults.json'), 'utf8'));
  write('options.json', {
    sh_company_name: 'سيو هاوس', sh_company_name_en: 'SEO House',
    sh_footer_text: footerText, sh_copyright: '© {year} سيو هاوس',
    sh_cta_label: 'احجز استشارة مجانية', sh_cta_fallback: { __route: '/contact/' },
    sh_founded: 2017, sh_areas: 'السعودية، مصر، الإمارات',
    sh_booking: {
      eyebrow: booking.eyebrow, title: booking.title, text: booking.text, points: booking.points,
      services: [['seo', 'تحسين محركات البحث'], ['web', 'تصميم وتطوير موقع'], ['stores', 'تصميم متجر إلكتروني'], ['products', 'إضافة المنتجات'], ['unsure', 'لم أحدد بعد']].map(([key, label]) => ({ key, label })),
      step2: { title: 'وصلنا طلبك', text: 'سجّلنا طلب الاستشارة. نراجع موقعك ونتواصل معك لتحديد موعد المكالمة.' }
    },
    sh_booking_provider: 'none',
    sh_article_cta: { title: 'تريد معرفة وضع موقعك؟', text: 'مكالمة مجانية مدتها 30 دقيقة نراجع فيها موقعك ونحدد الأولوية.', label: 'احجز استشارة', link: { __route: '/contact/' } },
    sh_client_logos: logos,
    sh_reviews_show_examples: 1,
    sh_redirects: REDIRECTS.map(r => ({ from: r.from, to: { __route: r.to }, note: r.note }))
  });
}

// 301 only to an equivalent page of the new design (same topic and purpose as the published URL)
const REDIRECTS = [
  {
    from: '/services/seo/stores-seo/', to: '/sectors/ecommerce/',
    note: 'نفس السلوك الحالي: الموقع المنشور يحوّل هذا الرابط 301 إلى /sectors/ecommerce/ (فُحص 2026-10-02). والوجهة الجديدة بنفس النية: سيو المتاجر الإلكترونية (الفئات والمنتجات، الجانب التقني، سلة/زد/شوبيفاي/ووكومرس، القياس بالطلبات والمبيعات). — للمراجعة'
  }
];

/** Every published URL of the current site → what answers it in the new build (content-pack/data/legacy-urls.json). */
function legacy() {
  const cfg = JSON.parse(fs.readFileSync(path.join(__dirname, 'pages.config.json'), 'utf8')).pages;
  const read = f => JSON.parse(fs.readFileSync(path.join(PACK, 'data', f), 'utf8'));
  const posts = read('posts.json'), cases = read('cases.json'), cats = read('categories.json');
  const norm = u => decodeURIComponent(u).replace(/\/?$/, '/');
  const pageRoutes = new Map(cfg.filter(p => p.kind === 'page' || p.key === 'blog').map(p => [norm(p.route), p]));
  const rows = fs.readFileSync(path.join(__dirname, 'legacy', 'live-urls.tsv'), 'utf8').split('\n').filter(l => l && !l.startsWith('#')).map(l => l.split('\t'));
  const out = rows.map(([url, source]) => {
    const u = norm(url);
    const r = { path: u, source };
    const page = pageRoutes.get(u);
    const post = posts.find(p => u === `/blog/${p.slug}/`);
    const cs = cases.find(c => u === `/results/${c.slug}/`);
    const cat = cats.find(c => u === `/blog/category/${c.slug}/`);
    const red = REDIRECTS.find(x => x.from === u);
    if (page) Object.assign(r, { resolution: 'same-path', object: 'page:' + page.key, status: page.key === 'privacy-policy' || page.key === 'terms' ? 'draft' : 'publish' });
    else if (post) Object.assign(r, { resolution: 'same-path', object: post.key, status: post.status || 'publish' });
    else if (cs) Object.assign(r, { resolution: 'same-path', object: cs.key, status: cs.status });
    else if (cat) Object.assign(r, { resolution: 'same-path', object: 'category:' + cat.slug, status: 'publish' });
    else if (red) Object.assign(r, { resolution: 'redirect-301', target: red.to, note: red.note, status: 'publish' });
    else if (/\/feed\/$/.test(u)) Object.assign(r, { resolution: 'wordpress-feed', status: 'publish' });
    else Object.assign(r, { resolution: 'MISSING', status: '' });
    return r;
  });
  write('legacy-urls.json', out);
  const by = out.reduce((a, r) => (a[r.resolution] = (a[r.resolution] || 0) + 1, a), {});
  log.push(`legacy URLs: ${out.length} — ` + Object.entries(by).map(([k, v]) => `${k} ${v}`).join(', '));
}

(async () => {
  menus(); options(); posts(); await cases(); legacy();
  fs.writeFileSync(path.join(PACK, 'build-log.txt'), log.join('\n') + '\n');
  console.log(log.join('\n'));
  console.log('content pack:', PACK);
})();
