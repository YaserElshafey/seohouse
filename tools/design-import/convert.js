#!/usr/bin/env node
/**
 * Step 2 — convert extracted design pages into WordPress sources:
 *   theme  : sections/<page>/<layout>.php, page-templates/<page>.php, assets/css/{design-base,pages/<page>}.css,
 *            inc/generated/icons.php, inc/generated/hover.css
 *   core   : acf-json/group_sh_page_<page>.json
 *   content: pages/<page>.json (seed values), manifest.json, assets used
 *
 * Usage: node convert.js <design-dir> <repo-root>
 * Files whose header has a line " * @sh-manual" are never overwritten.
 */
const fs = require('fs');
const path = require('path');
const crypto = require('crypto');
const cheerio = require('cheerio');
const { SectionCompiler, textOf } = require('./lib/section-compiler');
const { tokenize, rootCss } = require('./lib/tokens');
const hooks = require('./hooks');
const MANUAL = require('./manual');

const designDir = path.resolve(process.argv[2] || '');
const repo = path.resolve(process.argv[3] || path.join(__dirname, '..', '..'));
const THEME = path.join(repo, 'wordpress/themes/seohouse');
const CORE = path.join(repo, 'wordpress/plugins/seohouse-core');
const CONTENT = path.join(repo, 'content-pack');
const cfg = JSON.parse(fs.readFileSync(path.join(__dirname, 'pages.config.json'), 'utf8'));
const md5 = s => crypto.createHash('md5').update(s).digest('hex');

function writeGen(file, content) {
  if (fs.existsSync(file) && /^\s*\*\s*@sh-manual\b/m.test(fs.readFileSync(file, 'utf8'))) return false;
  fs.mkdirSync(path.dirname(file), { recursive: true });
  fs.writeFileSync(file, content);
  return true;
}
const slug = s => s.toLowerCase().replace(/[^a-z0-9]+/g, '_').replace(/^_|_$/g, '') || 'section';
const phpStr = s => `'${String(s).replace(/\\/g, '\\\\').replace(/'/g, "\\'")}'`;

// ------------------------------------------------------------------ inputs
const pages = cfg.pages.map(p => ({ ...p, x: JSON.parse(fs.readFileSync(path.join(__dirname, 'extract', p.key + '.json'), 'utf8')) }));
const routeSet = new Set(pages.map(p => p.route).filter(r => r.startsWith('/')));
['/blog/', '/team/'].forEach(r => routeSet.add(r));
const teamData = hooks.loadTeam(designDir);
const manual = MANUAL(designDir);
teamData.forEach(m => routeSet.add(`/team/${m.slug}/`));
const routeResolvable = h => {
  let p = h.split('#')[0].split('?')[0];
  try { p = decodeURIComponent(p); } catch (e) { /* keep */ }
  if (!p.endsWith('/')) p += '/';
  return routeSet.has(p);
};

// hover classes → stable global names
const hoverRules = new Map();
const hoverMapFor = pg => {
  const map = {};
  for (const [cls, rule] of Object.entries(pg.x.hover || {})) {
    const norm = tokenize(rule);
    const name = 'hv-' + md5(norm).slice(0, 6);
    hoverRules.set(name, norm);
    map[cls] = name;
  }
  return map;
};

// ------------------------------------------------------------------ CSS split
function cssBlocks(css) {
  const out = []; let i = 0, depth = 0, start = 0;
  css = css.replace(/\/\*[\s\S]*?\*\//g, '');
  for (; i < css.length; i++) {
    if (css[i] === '{') depth++;
    else if (css[i] === '}') { depth--; if (depth === 0) { const b = css.slice(start, i + 1).trim(); if (b) out.push(b.replace(/\s+/g, ' ')); start = i + 1; } }
  }
  return out;
}
const blockCount = new Map();
const pageBlocks = {};
for (const pg of pages) {
  const blocks = [...new Set(cssBlocks(pg.x.styles).map(b => tokenizeCss(b)))];
  pageBlocks[pg.key] = blocks;
  blocks.forEach(b => blockCount.set(b, (blockCount.get(b) || 0) + 1));
}
function tokenizeCss(b) {
  // selectors that match rendered inline style text must follow the tokenised inline styles
  b = b.replace(/\[style\*="([^"]*)"\]/g, (m, v) => `[style*="${tokenize(v)}"]`);
  return tokenize(b);
}
const threshold = Math.ceil(pages.length * 0.9);
const baseBlocks = [...blockCount.entries()].filter(([, c]) => c >= threshold).map(([b]) => b);
const baseSet = new Set(baseBlocks);

// ------------------------------------------------------------------ compile pages
const icons = new Map();
const assets = new Set();
const svgChoices = [];
const report = [];
const manifest = { generated: new Date().toISOString().slice(0, 10), designFingerprint: hooks.fingerprint(designDir), pages: [] };
const fieldMap = [];
const seeds = [];

for (const pg of pages) {
  const $ = cheerio.load(pg.x.html, null, false);
  $('span.sc-interp').each((_, e) => { $(e).replaceWith($(e).contents()); });
  const hoverMap = hoverMapFor(pg);
  const root = $.root().children().first();
  const sections = root.children('section[data-screen-label]').toArray();
  const pageLayouts = [];
  const seedSections = [];
  const usedLayoutNames = new Set();
  for (const el of sections) {
    const label = $(el).attr('data-screen-label');
    let layout = slug(label);
    let k = 2; while (usedLayoutNames.has(layout)) layout = `${slug(label)}_${k++}`;
    usedLayoutNames.add(layout);
    const shared = hooks.sharedFor(pg, label, $(el), $);
    if (shared) {
      pageLayouts.push({ layout, shared: shared.type, label, fields: shared.fields });
      seedSections.push({ acf_fc_layout: layout, ...shared.value });
      continue;
    }
    if (pg.kind !== 'page') continue;
    const man = manual[pg.key] && manual[pg.key][label];
    if (man) {
      const tpl = path.join(THEME, 'sections', pg.key, `${layout}.php`);
      if (!fs.existsSync(tpl)) report.push(`WARN ${pg.key}/${layout}: manual section template missing (${path.relative(repo, tpl)})`);
      pageLayouts.push({ layout, label, fields: man.fields(pg.key, layout), anchor: $(el).attr('id') || null });
      const mv = man.value($(el), $);
      JSON.stringify(mv, (k, v) => { if (k === '__asset' && v) assets.add(v); return v; });
      seedSections.push({ acf_fc_layout: layout, ...mv });
      continue;
    }
    const dyn = hooks.dynamicFor(pg, label, $);
    const comp = new SectionCompiler({ pageKey: pg.key, layout, hoverMap, assets, icons, dynamic: dyn, svgChoices, routes: routeResolvable });
    const res = comp.compile(el);
    for (const d of dyn) if (d.required && !d.used) report.push(`WARN ${pg.key}/${layout}: dynamic hook "${d.name}" did not match`);
    const header = `<?php\n/**\n * Section "${label}" — ${pg.file.replace('.dc.html', '')}.\n * Generated from the approved design by tools/design-import/convert.js.\n * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.\n *\n * @var array $args { f: layout values }\n */\ndefined( 'ABSPATH' ) || exit;\n$f = $args['f'] ?? array();\n?>\n`;
    writeGen(path.join(THEME, 'sections', pg.key, `${layout}.php`), header + res.php.trim() + '\n');
    pageLayouts.push({ layout, label, fields: res.fields, anchor: comp.anchorDefault });
    seedSections.push({ acf_fc_layout: layout, ...res.value });
    fieldMap.push({ page: pg.key, layout, label, fields: res.fields });
  }

  // page template + field group (only for design pages)
  if (pg.kind === 'page') {
    writeGen(path.join(THEME, 'page-templates', `${pg.key}.php`),
`<?php
/**
 * Template Name: سيو هاوس — ${pg.admin}
 * Design source: ${pg.file}
 * Generated by tools/design-import/convert.js.
 *
 * @package SEOHouse
 */
defined( 'ABSPATH' ) || exit;
get_header();
sh_render_sections( '${pg.key}' );
get_footer();
`);
    const group = hooks.fieldGroup(pg, pageLayouts);
    writeGen(path.join(CORE, 'acf-json', `${group.key}.json`), JSON.stringify(group, null, 2) + '\n');
  }

  // page CSS (everything that is not in the shared base)
  const own = pageBlocks[pg.key].filter(b => !baseSet.has(b));
  writeGen(path.join(THEME, 'assets/css/pages', `${pg.key}.css`), `/* ${pg.file} — page-specific rules from the approved design (generated). */\n` + own.join('\n') + '\n');

  const crumbs = $('nav[aria-label="مسار التنقل"]').first().children('a,span[aria-current]').toArray().map(e => ({ label: $(e).text().trim(), href: $(e).attr('href') || null }));
  const seed = {
    crumbs,
    key: pg.key, route: pg.route, kind: pg.kind, title: pg.admin, file: pg.file,
    // pages whose approved text is still a placeholder are created as drafts (never published automatically)
    status: /\[يُضاف النص القانوني المعتمد قبل النشر\]/.test(pg.x.html) ? 'draft' : 'publish',
    seo: { title: pg.x.head.title, description: pg.x.head.description, robots: pg.x.head.robots, ...schemaFromDesign(pg.x.head.jsonld) },
    template: pg.kind === 'page' ? `page-templates/${pg.key}.php` : null,
    sections: seedSections
  };
  seeds.push(seed);
  manifest.pages.push({ key: pg.key, route: pg.route, kind: pg.kind, file: pg.file, layouts: pageLayouts.map(l => l.layout) });
  report.push(`${pg.key.padEnd(22)} ${pageLayouts.map(l => l.layout + (l.shared ? '*' : '') + ':' + countFields(l.fields)).join(' ')}`);
}
// page schema type / service name taken from the design's own JSON-LD (the output itself is built by Core from fields)
function schemaFromDesign(ld) {
  const nodes = (ld || []).flatMap(x => { try { const o = typeof x === 'string' ? JSON.parse(x) : x; return o['@graph'] || [o]; } catch (e) { return []; } });
  const types = nodes.map(n => n['@type']);
  const svc = nodes.find(n => n['@type'] === 'Service');
  const map = { AboutPage: 'about', ContactPage: 'contact', CollectionPage: 'collection', WebPage: 'webpage' };
  const t = svc ? 'service' : (types.map(x => map[x]).find(Boolean) || 'auto');
  return { schema_type: t, schema_service: svc ? String(svc.serviceType || '') : '' };
}
function countFields(fs) { let n = 0; for (const f of fs || []) { n++; if (f.sub_fields) n += countFields(f.sub_fields); } return n; }

// ------------------------------------------------------------------ booking copy: one global default, page overrides only where different
const bk = seeds.flatMap(s => s.sections.filter(x => x.service !== undefined && x.acf_fc_layout.startsWith('booking')));
const mode = arr => { const c = new Map(); arr.forEach(v => c.set(v, (c.get(v) || 0) + 1)); return [...c].sort((a, b) => b[1] - a[1])[0]?.[0]; };
const bookingDefaults = {
  eyebrow: mode(bk.map(b => b.eyebrow)), title: mode(bk.map(b => b.title)), text: mode(bk.map(b => b.text)),
  points: JSON.parse(mode(bk.map(b => JSON.stringify(b.points))) || '[]')
};
for (const b of bk) {
  for (const f of ['eyebrow', 'title', 'text']) if (b[f] === bookingDefaults[f]) b[f] = '';
  if (JSON.stringify(b.points) === JSON.stringify(bookingDefaults.points)) b.points = [];
}
for (const seed of seeds) writeGen(path.join(CONTENT, 'pages', `${seed.key}.json`), JSON.stringify(seed, null, 1) + '\n');
fs.writeFileSync(path.join(CONTENT, 'booking-defaults.json'), JSON.stringify(bookingDefaults, null, 1) + '\n');

// ------------------------------------------------------------------ global outputs
writeGen(path.join(THEME, 'assets/css/design-base.css'),
  `/* Shared rules of the approved design (present in ≥90% of pages) + colour tokens. Generated. */\n${rootCss()}\n${baseBlocks.join('\n')}\n\n/* hover states (style-hover in the design) */\n${[...hoverRules].map(([n, r]) => `.${n}:hover { ${r} }`).join('\n')}\n`);

writeGen(path.join(THEME, 'inc/generated/icons.php'),
  `<?php\n/**\n * Inline SVG icons used by repeaters in the approved design. Generated.\n *\n * @package SEOHouse\n */\ndefined( 'ABSPATH' ) || exit;\nreturn array(\n${[...icons].map(([id, v]) => `\t${phpStr(id)} => array( 'label' => ${phpStr(v.label)}, 'svg' => ${phpStr(v.markup)} ),`).join('\n')}\n);\n`);

fs.mkdirSync(CONTENT, { recursive: true });
manifest.assets = [...assets].sort();
fs.writeFileSync(path.join(CONTENT, 'manifest.json'), JSON.stringify(manifest, null, 1) + '\n');
fs.writeFileSync(path.join(__dirname, 'extract', 'field-map.json'), JSON.stringify(fieldMap, null, 1));
fs.writeFileSync(path.join(__dirname, 'extract', 'report.txt'), report.join('\n') + '\n');
console.log(report.join('\n'));
console.log(`\nicons: ${icons.size}  assets: ${assets.size}  hover classes: ${hoverRules.size}  base css blocks: ${baseBlocks.length}`);
