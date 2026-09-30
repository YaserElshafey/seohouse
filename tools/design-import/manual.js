/**
 * Sections maintained by hand (theme files marked "@sh-manual"): tab widgets whose inactive
 * panels are not in the rendered DOM. One repeater per widget drives the desktop tabs, the
 * panel and the mobile accordion, so each text exists once.
 * Values are read from the design's own data (the constants the widget renders).
 */
const path = require('path');
const crypto = require('crypto');
const { readConsts } = require('./lib/consts');

const md5 = s => crypto.createHash('md5').update(s).digest('hex');
const clean = s => String(s || '').replace(/\s+/g, ' ').trim();

module.exports = (designDir) => {
  const file = f => path.join(designDir, f);
  const K = (page, layout, p) => 'field_' + md5(`${page}/${layout}/manual/${p}`).slice(0, 13);
  const F = (page, layout, name, label, type, extra = {}, parent = '') => {
    const f = { key: K(page, layout, parent + name), name, label, type, instructions: '', required: 0, ...extra };
    if (f.sub_fields) f.sub_fields = f.sub_fields.map(s => F(page, layout, s.name, s.label, s.type, s.extra || {}, parent + name + '/'));
    if (f.collapsed) f.collapsed = K(page, layout, parent + name + '/' + f.collapsed);
    return f;
  };
  const S = (name, label, type, extra = {}) => ({ name, label, type, extra });
  const head = ($s, $) => ({
    eyebrow: clean($s.find('h2').first().prev().text()),
    title: clean($s.find('h2').first().text())
  });
  const link = (page, layout, name, label) => F(page, layout, name, label, 'page_link', { post_type: ['page', 'case_study', 'post'], allow_archives: 1, allow_null: 1 });

  return {
    seo: {
      Results: {
        fields: (p, l) => [
          F(p, l, 'eyebrow', 'الوسم', 'text'),
          F(p, l, 'title', 'العنوان', 'text'),
          F(p, l, 'all_label', 'نص رابط كل النتائج', 'text'),
          link(p, l, 'all_link', 'رابط كل النتائج'),
          F(p, l, 'cases', 'النتائج المعروضة (تبويبات)', 'repeater', { layout: 'block', button_label: 'إضافة نتيجة', collapsed: 'tab', max: 6, instructions: 'كل صف = تبويب ولوحته. انقل الأرقام كما في التقرير.', sub_fields: [
            S('tab', 'اسم التبويب', 'text'), S('sector', 'القطاع', 'text'), S('market', 'السوق', 'text'),
            S('figure', 'الرقم', 'text'), S('headline', 'العنوان', 'text'), S('desc', 'الوصف', 'textarea', { rows: 2, new_lines: '' }),
            S('period', 'الفترة', 'text'), S('source', 'المصدر', 'text'),
            S('link_label', 'نص الرابط', 'text'), S('link', 'الرابط', 'page_link', { post_type: ['page', 'case_study'], allow_null: 1, allow_archives: 1 }),
            S('proof', 'لقطة التقرير', 'image', { return_format: 'id', preview_size: 'medium' }), S('alt', 'النص البديل للقطة', 'text')
          ] })
        ],
        value: ($s, $) => {
          const { CASES } = readConsts(file('SEO House - SEO Service Page.dc.html'), ['CASES']);
          const a = $s.find('a[href="/results/"]').first();
          return {
            ...head($s, $), all_label: clean(a.text()).replace(/\s*←$/, ''), all_link: { __route: '/results/' },
            cases: CASES.map(c => ({ tab: c.tab, sector: c.sector, market: c.market, figure: c.figure, headline: c.headline, desc: c.desc, period: c.period, source: c.source, link_label: 'دراسة الحالة', link: { __route: '/results/' }, proof: { __asset: c.proof, alt: c.alt }, alt: c.alt }))
          };
        }
      },
      Process: {
        fields: (p, l) => [
          F(p, l, 'eyebrow', 'الوسم', 'text'),
          F(p, l, 'title', 'العنوان', 'text'),
          F(p, l, 'deliverables_label', 'عنوان التسليمات', 'text'),
          F(p, l, 'phases', 'المراحل', 'repeater', { layout: 'block', button_label: 'إضافة مرحلة', collapsed: 'label', max: 8, sub_fields: [
            S('num', 'الرقم', 'text'), S('label', 'اسم المرحلة', 'text'), S('desc', 'الوصف', 'textarea', { rows: 2, new_lines: '' }), S('out', 'النتيجة', 'text'),
            S('items', 'التسليمات', 'repeater', { layout: 'table', button_label: 'إضافة تسليم', sub_fields: [S('text', 'التسليم', 'text')] }),
            S('viz_title', 'عنوان المعاينة التوضيحية', 'text'),
            S('viz_rows', 'أشرطة المعاينة التوضيحية', 'repeater', { layout: 'table', button_label: 'إضافة شريط', max: 5, instructions: 'رسم توضيحي فقط (aria-hidden) وليس نتيجة قياس.', sub_fields: [S('label', 'النص', 'text'), S('pct', 'الطول (مثل 60%)', 'text')] })
          ] }),
          F(p, l, 'team_title', 'عنوان الفريق', 'text'),
          F(p, l, 'team_sub', 'سطر التخصصات', 'text'),
          F(p, l, 'team_link_label', 'نص رابط الفريق', 'text'),
          link(p, l, 'team_link', 'رابط الفريق')
        ],
        value: ($s, $) => {
          const { PHASES } = readConsts(file('SEO House - SEO Service Page.dc.html'), ['PHASES']);
          const src = require('fs').readFileSync(file('SEO House - SEO Service Page.dc.html'), 'utf8');
          const viz = JSON.parse(src.match(/phaseViz: (\[.*?\])\[st\.phase/)[1]);
          const teamA = $s.find('a[href="/team/"]').first();
          const teamHead = teamA.prev();
          return {
            eyebrow: clean($s.find('h2').first().prev().text()), title: clean($s.find('h2').first().text()),
            deliverables_label: 'التسليمات',
            phases: PHASES.map((ph, i) => ({ num: ph.num, label: ph.label, desc: ph.desc, out: ph.out, items: ph.items.map(t => ({ text: t })), viz_title: viz[i]?.t || '', viz_rows: (viz[i]?.rows || []).map(r => ({ label: r.label, pct: r.pct })) })),
            team_title: clean(teamHead.children().first().text()), team_sub: clean(teamHead.children().eq(1).text()),
            team_link_label: clean(teamA.text()).replace(/\s*←$/, ''), team_link: { __route: '/team/' }
          };
        }
      }
    },
    'seo-egypt': {
      Sectors: {
        fields: (p, l) => [
          F(p, l, 'eyebrow', 'الوسم', 'text'),
          F(p, l, 'title', 'العنوان', 'text'),
          F(p, l, 'all_label', 'نص رابط كل القطاعات', 'text'),
          link(p, l, 'all_link', 'رابط كل القطاعات'),
          F(p, l, 'path_label', 'عنوان مسار البحث', 'text'),
          F(p, l, 'link_label', 'نص رابط صفحة القطاع', 'text'),
          F(p, l, 'sectors', 'القطاعات', 'repeater', { layout: 'block', button_label: 'إضافة قطاع', collapsed: 'label', max: 10, sub_fields: [
            S('label', 'القطاع', 'text'), S('desc', 'الوصف', 'textarea', { rows: 2, new_lines: '' }),
            S('link', 'صفحة القطاع', 'page_link', { post_type: ['page'], allow_null: 1 }),
            S('path', 'مسار البحث', 'repeater', { layout: 'table', button_label: 'إضافة خطوة', max: 5, sub_fields: [S('step', 'الخطوة', 'text')] })
          ] })
        ],
        value: ($s, $) => {
          const { SECTORS } = readConsts(file('SEO House - Egypt SEO Page.dc.html'), ['SECTORS']);
          return {
            ...head($s, $), all_label: 'جميع القطاعات', all_link: { __route: '/sectors/' },
            path_label: 'مسار البحث حتى القرار', link_label: 'صفحة القطاع',
            sectors: SECTORS.map(x => ({ label: x.label, desc: x.desc, link: { __route: x.href }, path: (x.path || []).map(step => ({ step })) }))
          };
        }
      }
    },
    'seo-uae': {
      Opportunities: {
        fields: (p, l) => [
          F(p, l, 'eyebrow', 'الوسم', 'text'),
          F(p, l, 'title', 'العنوان', 'text'),
          F(p, l, 'review_label', 'عنوان «ما الذي نراجعه»', 'text'),
          F(p, l, 'improve_label', 'عنوان «ما الذي يمكن تحسينه»', 'text'),
          F(p, l, 'outcome_label', 'عنوان «النتيجة التجارية»', 'text'),
          F(p, l, 'opps', 'الفرص', 'repeater', { layout: 'block', button_label: 'إضافة فرصة', collapsed: 'label', max: 8, sub_fields: [
            S('num', 'الرقم', 'text'), S('label', 'الفرصة', 'text'),
            S('review', 'ما الذي نراجعه', 'textarea', { rows: 2, new_lines: '' }), S('improve', 'ما الذي يمكن تحسينه', 'textarea', { rows: 2, new_lines: '' }), S('outcome', 'النتيجة التجارية', 'textarea', { rows: 2, new_lines: '' })
          ] })
        ],
        value: ($s, $) => {
          const { OPPS } = readConsts(file('SEO House - UAE SEO Page.dc.html'), ['OPPS']);
          return {
            ...head($s, $), review_label: 'ما الذي نراجعه؟', improve_label: 'ما الذي يمكن تحسينه؟', outcome_label: 'ما النتيجة التجارية التي نتابعها؟',
            opps: OPPS.map(o => ({ num: o.num, label: o.label, review: o.review, improve: o.improve, outcome: o.outcome }))
          };
        }
      }
    }
  };
};
