/**
 * Page-specific knowledge the generic compiler cannot infer:
 *   - shared layouts (FAQ, Booking, Related links) with fixed fields,
 *   - dynamic regions that read WordPress data (logos, team, results, posts),
 *   - the ACF field group wrapper for each page template.
 */
const { toFree, assertFree } = require('./lib/acf-free');
const fs = require('fs');
const path = require('path');
const crypto = require('crypto');
const { tokenize } = require('./lib/tokens');
const { textOf } = require('./lib/section-compiler');

const md5 = s => crypto.createHash('md5').update(s).digest('hex');
const key = (...p) => 'field_' + md5(p.join('/')).slice(0, 13);
const phpStr = s => `'${String(s).replace(/\\/g, '\\\\').replace(/'/g, "\\'")}'`;
const clean = s => String(s || '').replace(/\s+/g, ' ').trim();
const attrStyle = n => tokenize((n.attribs && n.attribs.style) || '');

function fingerprint(dir) {
  const h = crypto.createHash('sha256');
  for (const f of fs.readdirSync(dir).filter(f => f.endsWith('.dc.html')).sort()) h.update(f).update(fs.readFileSync(path.join(dir, f)));
  return 'sha256:' + h.digest('hex').slice(0, 16);
}

function loadTeam(dir) {
  const src = fs.readFileSync(path.join(dir, 'team-data.js'), 'utf8');
  const sandbox = {}; // team-data.js assigns window.SH_TEAM or const TEAM
  try {
    const fn = new Function('window', src + '\n;return (typeof TEAM!=="undefined"?TEAM:(window.SH_TEAM||window.TEAM||[]));');
    const w = {}; const r = fn(w); return Array.isArray(r) ? r : (w.SH_TEAM || []);
  } catch (e) { return []; }
}

// ------------------------------------------------------------------ shared layouts
const BOOKING_SERVICES = { 'تحسين محركات البحث': 'seo', 'تصميم وتطوير موقع': 'web', 'تصميم متجر إلكتروني': 'stores', 'إضافة المنتجات': 'products', 'لم أحدد بعد': 'unsure' };

function sharedFor(pg, label, $s, $) {
  const k = pg.key;
  if (label === 'FAQ') {
    const eyebrow = clean($s.find('h2').first().prev().text()) || clean($s.find('div').filter((_, e) => /font-weight: 600/.test($(e).attr('style') || '') && !$(e).children().length).first().text());
    const title = clean($s.find('h2').first().text());
    const items = [];
    $s.find('button[aria-expanded]').each((_, b) => {
      const q = clean($(b).children('span').first().text());
      const a = clean($(b).next('div').find('p').first().html() ? $(b).next('div').find('p').first().text() : $(b).next('div').text());
      if (q) items.push({ question: q, answer: a });
    });
    return {
      type: 'faq',
      fields: [
        { key: key(k, 'faq', 'eyebrow'), name: 'eyebrow', label: 'الوسم', type: 'text' },
        { key: key(k, 'faq', 'title'), name: 'title', label: 'عنوان القسم', type: 'text' },
        { key: key(k, 'faq', 'items'), name: 'items', label: 'الأسئلة', type: 'repeater', layout: 'block', button_label: 'إضافة سؤال', collapsed: key(k, 'faq', 'items', 'q'), instructions: 'نفس الأسئلة تُستخدم في FAQPage Schema. لا تضف سؤالًا غير ظاهر في الصفحة.', sub_fields: [
          { key: key(k, 'faq', 'items', 'q'), name: 'question', label: 'السؤال', type: 'text', required: 1 },
          { key: key(k, 'faq', 'items', 'a'), name: 'answer', label: 'الإجابة', type: 'textarea', rows: 3, new_lines: 'br', required: 1 }
        ] },
        { key: key(k, 'faq', 'schema'), name: 'schema', label: 'إخراج FAQPage Schema', type: 'true_false', ui: 1, default_value: 1, instructions: 'يُخرج من نفس الأسئلة الظاهرة فقط.' }
      ],
      value: { eyebrow, title, items, schema: 1, _variant: /data-sec-head/.test($.html($s)) ? 'head' : 'plain' }
    };
  }
  if (label === 'Booking') {
    const pressed = clean($s.find('button[aria-pressed="true"]').first().text());
    const eyebrow = clean($s.find('h2').first().prev().text());
    const title = clean($s.find('h2').first().text());
    const text = clean($s.find('h2').first().nextAll('p').first().text());
    const points = $s.find('ul li').toArray().map(li => ({ text: clean($(li).clone().children('[aria-hidden]').remove().end().text()) }));
    return {
      type: 'booking',
      fields: [
        { key: key(k, 'booking', 'service'), name: 'service', label: 'الخدمة المختارة مسبقًا', type: 'select', choices: { '': 'بدون اختيار مسبق', seo: 'تحسين محركات البحث', web: 'تصميم وتطوير موقع', stores: 'تصميم متجر إلكتروني', products: 'إضافة المنتجات', unsure: 'لم أحدد بعد' }, default_value: '', allow_null: 1, return_format: 'value' },
        { key: key(k, 'booking', 'eyebrow'), name: 'eyebrow', label: 'الوسم (اتركه فارغًا لاستخدام النص العام)', type: 'text' },
        { key: key(k, 'booking', 'title'), name: 'title', label: 'العنوان (اتركه فارغًا لاستخدام النص العام)', type: 'text' },
        { key: key(k, 'booking', 'text'), name: 'text', label: 'الوصف (اتركه فارغًا لاستخدام النص العام)', type: 'textarea', rows: 2, new_lines: '' },
        { key: key(k, 'booking', 'points'), name: 'points', label: 'نقاط الفائدة (فارغة = النقاط العامة)', type: 'repeater', layout: 'table', button_label: 'إضافة نقطة', max: 4, sub_fields: [
          { key: key(k, 'booking', 'points', 't'), name: 'text', label: 'النقطة', type: 'text' }
        ] }
      ],
      value: { service: pressed ? (BOOKING_SERVICES[pressed] || '') : '', eyebrow, title, text, points }
    };
  }
  if (label === 'Related' && $s.find('a').length && /صفحات مرتبطة/.test($s.text())) {
    const lead = clean($s.find('span').first().text());
    const links = $s.find('a').toArray().map(a => ({ label: clean($(a).text()).replace(/\s*←\s*$/, ''), link: { __route: $(a).attr('href') } }));
    return {
      type: 'related',
      fields: [
        { key: key(k, 'related', 'lead'), name: 'lead', label: 'العنوان التمهيدي', type: 'text' },
        { key: key(k, 'related', 'links'), name: 'links', label: 'الصفحات المرتبطة', type: 'repeater', layout: 'table', button_label: 'إضافة رابط', max: 8, sub_fields: [
          { key: key(k, 'related', 'links', 'label'), name: 'label', label: 'النص', type: 'text', required: 1 },
          { key: key(k, 'related', 'links', 'link'), name: 'link', label: 'الصفحة', type: 'page_link', post_type: ['page', 'case_study', 'post', 'team_member'], allow_archives: 1, allow_null: 0, required: 1 }
        ] }
      ],
      value: { lead, links, _variant: /max-width: 1200px/.test($.html($s)) ? 'wide' : 'narrow' }
    };
  }
  return null;
}

// ------------------------------------------------------------------ dynamic regions
function styleAttr(n) { const s = attrStyle(n); return s ? ` style="${s.replace(/"/g, '&quot;')}"` : ''; }
function openTag(n, extra = '') {
  let a = '';
  for (const [k, v] of Object.entries(n.attribs || {})) {
    if (k === 'data-dc-tpl') continue;
    const val = k === 'style' ? tokenize(v) : v;
    a += val === '' ? ` ${k}` : ` ${k}="${String(val).replace(/"/g, '&quot;')}"`;
  }
  return `<${n.name}${a}${extra}>`;
}
const hasStyle = (n, re) => n.attribs && re.test(n.attribs.style || '');
const firstTag = (n, name) => { if (!n || !n.children) return null; for (const c of n.children) { if (c.type === 'tag' && (!name || c.name === name)) return c; const d = firstTag(c, name); if (d) return d; } return null; };

function dynamicFor(pg, label, $) {
  const out = [];
  if (label === 'Client logos') {
    out.push({
      name: 'logo-track', required: true,
      match: n => n.name === 'div' && hasStyle(n, /shRtl/),
      php: n => `${openTag(n)}<?php sh_logo_track( array( 'cell' => ${phpStr(attrStyle(firstTag(n, 'div')))}, 'img' => ${phpStr(attrStyle(firstTag(n, 'img')))} ) ); ?></div>`
    });
  }
  if (label === 'Reviews') {
    out.push({
      name: 'reviews-slot', required: true,
      match: n => !!(n.attribs && (n.attribs['data-grid'] === 'reviews' || 'data-rev-grid' in n.attribs || 'data-trustindex-mount' in n.attribs)),
      wrap: true
    });
  }
  const page = PAGE_HOOKS[pg.key] && PAGE_HOOKS[pg.key][label];
  if (page) out.push(...page());
  return out;
}

/** Page specific dynamic regions: filled in hooks.pages.js */
let PAGE_HOOKS = {};
try { PAGE_HOOKS = require('./hooks.pages')({ openTag, attrStyle, phpStr, hasStyle, firstTag, textOf, tokenize }); } catch (e) { if (e.code !== 'MODULE_NOT_FOUND') throw e; }

// ------------------------------------------------------------------ field group
function fieldGroup(pg, layouts) {
  // ACF (free): each design section is an ACF Group field "s_<layout>" in the design order,
  // introduced by an Accordion field so the editor can fold sections. (Flexible Content is PRO.)
  const fields = [];
  for (const l of layouts) {
    const common = [
      { key: key(pg.key, l.layout, '__hide'), name: 'sh_hide', label: 'إخفاء القسم', type: 'true_false', ui: 1, default_value: 0, wrapper: { width: '30' } }
    ];
    if (l.anchor) common.push({ key: key(pg.key, l.layout, '__anchor'), name: 'sh_anchor', label: 'معرّف الرابط الداخلي (anchor)', type: 'text', placeholder: l.anchor, instructions: `الافتراضي: #${l.anchor} — الأزرار داخل الصفحة تشير إليه.`, wrapper: { width: '70' } });
    fields.push({ key: key(pg.key, l.layout, '__acc'), label: sectionLabel(l.label, l.shared, l.fields), name: '', type: 'accordion', open: 0, multi_expand: 1, endpoint: 0 });
    fields.push({ key: key(pg.key, l.layout, '__section'), label: '', name: 's_' + l.layout, type: 'group', layout: 'block', instructions: '', wrapper: { class: 'sh-section' }, sub_fields: toFree([...common, ...normalizeFields(l.fields)]) });
  }
  fields.push({ key: key(pg.key, '__acc_end'), label: '', name: '', type: 'accordion', open: 0, multi_expand: 0, endpoint: 1 });
  return assertFree({
    key: `group_sh_page_${pg.key.replace(/-/g, '_')}`,
    title: `أقسام الصفحة — ${pg.admin}`,
    fields,
    location: [[{ param: 'page_template', operator: '==', value: `page-templates/${pg.key}.php` }]],
    menu_order: 0, position: 'normal', style: 'default', label_placement: 'top', instruction_placement: 'label',
    hide_on_screen: ['the_content', 'excerpt', 'discussion', 'comments', 'format', 'send-trackbacks'],
    active: true, description: `حقول قالب ${pg.admin} — مولدة من ${pg.file}. الأقسام بترتيب التصميم؛ يمكن إخفاء أي قسم.`, show_in_rest: 0,
    modified: 1790000000
  });
}

const LABEL_AR = { Hero: 'الواجهة (Hero)', FAQ: 'الأسئلة الشائعة', Booking: 'حجز الاستشارة', Related: 'صفحات مرتبطة', 'Client logos': 'شعارات العملاء', Reviews: 'التقييمات', Results: 'النتائج', Sectors: 'القطاعات', Services: 'الخدمات', Markets: 'الأسواق', Articles: 'المقالات', Platforms: 'المنصات', About: 'عن الشركة', Process: 'خطوات العمل', Scope: 'النطاق', Phases: 'المراحل', Deliverables: 'التسليمات', Tools: 'الأدوات', Flow: 'المسار', Cases: 'الحالات', 'Pricing strip': 'شريط الأسعار', Honest: 'ما نلتزم به', Opportunities: 'الفرص' };
/** Section name in the editor: known Arabic name, else the section's own heading from the design. */
function sectionLabel(l, shared, fields = []) {
  let name = LABEL_AR[l];
  if (!name) {
    const f = (fields || []).find(x => x.name === 'eyebrow') || (fields || []).find(x => x.name === 'title');
    const preview = f && String(f.label || '').split(': ').slice(1).join(': ').trim();
    name = preview ? `قسم: ${preview}` : `قسم: ${l}`;
  }
  return name + (shared ? ' — مكوّن مشترك' : '');
}

function normalizeFields(fields) {
  return (fields || []).map(f => {
    const o = { ...f };
    if (o.sub_fields) o.sub_fields = normalizeFields(o.sub_fields);
    if (o.required === undefined) o.required = 0;
    return o;
  });
}

module.exports = { fingerprint, loadTeam, sharedFor, dynamicFor, fieldGroup };
