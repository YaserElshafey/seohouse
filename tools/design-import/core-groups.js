#!/usr/bin/env node
/**
 * Hand-designed ACF field groups (not derived from a single design section):
 * site options, case studies, team members, articles, page SEO/breadcrumb, menu items.
 * Writes wordpress/plugins/seohouse-core/acf-json/group_sh_*.json (single source; not registered in PHP).
 *
 * Usage: node core-groups.js [repo-root]
 */
const { toFree, assertFree } = require('./lib/acf-free');
const fs = require('fs');
const path = require('path');
const repo = path.resolve(process.argv[2] || path.join(__dirname, '..', '..'));
const OUT = path.join(repo, 'wordpress/plugins/seohouse-core/acf-json');
fs.mkdirSync(OUT, { recursive: true });

let counter = 0;
/** Field helper: key is derived from group + name path so it stays stable. */
function F(group, name, label, type, extra = {}) {
  const f = { key: `field_sh_${group}_${name}`.replace(/-/g, '_'), label, name, type, instructions: '', required: 0, ...extra };
  if (f.sub_fields) f.sub_fields = f.sub_fields.map(s => ({ ...s, key: `${f.key}__${s.name}` }));
  if (f.sub_fields) f.sub_fields = f.sub_fields.map(s => s.sub_fields ? { ...s, sub_fields: s.sub_fields.map(x => ({ ...x, key: `${s.key}__${x.name}` })) } : s);
  if (f.collapsed) f.collapsed = `${f.key}__${f.collapsed}`;
  counter++;
  return f;
}
const S = (name, label, type, extra = {}) => ({ name, label, type, instructions: '', required: 0, ...extra });
const tab = (group, name, label) => F(group, 'tab_' + name, label, 'tab', { placement: 'top', endpoint: 0 });
const group = (key, title, fields, location, extra = {}) => ({
  key: `group_sh_${key}`, title, fields, location,
  menu_order: 0, position: 'normal', style: 'default', label_placement: 'top', instruction_placement: 'label',
  hide_on_screen: '', active: true, description: '', show_in_rest: 0, modified: 1790000000, ...extra
});

const COLOR = (g, n, label, def) => F(g, n, label, 'color_picker', { default_value: def, enable_opacity: 0, return_format: 'string', instructions: `القيمة المعتمدة: ${def}. التغيير يطبّق على كل الصفحات.` });

// ------------------------------------------------------------------ options
const O = 'opt';
const options = group('options', 'إعدادات سيو هاوس', [
  tab(O, 'identity', 'الهوية'),
  F(O, 'company_name', 'اسم الشركة', 'text', { name: 'sh_company_name', default_value: 'سيو هاوس' }),
  F(O, 'company_name_en', 'الاسم اللاتيني', 'text', { name: 'sh_company_name_en', default_value: 'SEO House' }),
  F(O, 'logo', 'الشعار (على الخلفية الداكنة)', 'image', { name: 'sh_logo', return_format: 'id', preview_size: 'medium', instructions: 'PNG أو WebP بخلفية شفافة. عند الإفراغ يُستخدم شعار الثيم.' }),
  F(O, 'logo_light', 'الشعار على الخلفية الفاتحة', 'image', { name: 'sh_logo_light', return_format: 'id', preview_size: 'medium' }),
  F(O, 'footer_text', 'نبذة الفوتر', 'textarea', { name: 'sh_footer_text', rows: 3, new_lines: '' }),
  F(O, 'copyright', 'سطر الحقوق', 'text', { name: 'sh_copyright', instructions: 'استخدم {year} للسنة الحالية. مثال: © {year} سيو هاوس' }),

  tab(O, 'design', 'التصميم'),
  F(O, 'design_msg', 'تنبيه', 'message', { message: 'الألوان مأخوذة من التصميم المعتمد. غيّرها فقط بقرار هوية؛ كل الأقسام تقرأ من هذه القيم.' }),
  COLOR(O, 'c_ink', 'الخلفية الداكنة', '#060B1F'), COLOR(O, 'c_blue', 'الأزرق الحيوي', '#2F5BFF'), COLOR(O, 'c_sky', 'السماوي (الروابط)', '#4CACFF'),
  COLOR(O, 'c_lime', 'الفسفوري (الإجراء البارز)', '#C7FF32'), COLOR(O, 'c_paper', 'السطح الفاتح', '#F7F9FD'), COLOR(O, 'c_text', 'النص على الداكن', '#EDF1FA'), COLOR(O, 'c_muted', 'النص الثانوي', '#B9C4DC'),

  tab(O, 'header', 'الهيدر'),
  F(O, 'cta_label', 'نص زر الاستشارة', 'text', { name: 'sh_cta_label', default_value: 'احجز استشارة مجانية' }),
  F(O, 'cta_fallback', 'وجهة الزر في الصفحات التي لا تحتوي نموذج الحجز', 'page_link', { name: 'sh_cta_fallback', post_type: ['page'], allow_null: 1, instructions: 'في الصفحات التي تحتوي قسم الحجز يتجه الزر إلى #booking تلقائيًا.' }),
  F(O, 'menu_msg', 'القوائم', 'message', { message: 'عناصر القائمة والميجا مينيو وأيقوناتها تُدار من المظهر ← القوائم (القائمة الرئيسية، أعمدة الفوتر، روابط أسفل الفوتر).' }),

  tab(O, 'company', 'بيانات الشركة'),
  F(O, 'email', 'البريد الإلكتروني', 'email', { name: 'sh_email' }),
  F(O, 'phone', 'الهاتف', 'text', { name: 'sh_phone', instructions: 'بالصيغة الدولية، مثال +9665xxxxxxx. يظهر فقط بعد اعتماده.' }),
  F(O, 'whatsapp', 'واتساب', 'text', { name: 'sh_whatsapp' }),
  F(O, 'founded', 'سنة التأسيس', 'number', { name: 'sh_founded', default_value: 2017 }),
  F(O, 'areas', 'الأسواق المخدومة', 'text', { name: 'sh_areas', default_value: 'السعودية، مصر، الإمارات', instructions: 'تُستخدم في areaServed داخل Schema. الخدمة عن بُعد؛ لا تُضاف عناوين مكاتب غير حقيقية.' }),
  F(O, 'socials', 'الروابط الاجتماعية', 'repeater', { name: 'sh_socials', layout: 'table', button_label: 'إضافة رابط', sub_fields: [S('label', 'الاسم', 'text'), S('url', 'الرابط', 'url')] }),

  tab(O, 'booking', 'الاستشارة'),
  F(O, 'booking', 'قسم الحجز (النص العام)', 'group', { name: 'sh_booking', layout: 'block', sub_fields: [
    S('eyebrow', 'الوسم', 'text'), S('title', 'العنوان', 'text'), S('text', 'الوصف', 'textarea', { rows: 2, new_lines: '' }),
    S('points', 'نقاط الفائدة', 'repeater', { layout: 'table', button_label: 'إضافة نقطة', max: 4, sub_fields: [S('text', 'النقطة', 'text')] }),
    S('services', 'الخدمات في النموذج', 'repeater', { layout: 'table', button_label: 'إضافة خدمة', min: 1, max: 8, sub_fields: [S('key', 'المفتاح', 'text'), S('label', 'النص', 'text')] }),
    S('step2', 'رسالة الخطوة الثانية (قبل ربط أداة الحجز)', 'group', { layout: 'block', sub_fields: [S('title', 'العنوان', 'text', { default_value: 'وصلنا طلبك' }), S('text', 'النص', 'textarea', { rows: 2, new_lines: '', default_value: 'سجّلنا طلب الاستشارة. نراجع موقعك ونتواصل معك لتحديد موعد المكالمة.' })] })
  ] }),
  F(O, 'lead_to', 'بريد استلام الطلبات', 'text', { name: 'sh_lead_to', instructions: 'عنوان أو أكثر مفصولة بفاصلة. عند الإفراغ يُستخدم بريد مدير الموقع.' }),
  F(O, 'booking_provider', 'أداة الحجز', 'select', { name: 'sh_booking_provider', choices: { none: 'غير مربوطة — تأكيد استلام الطلب فقط', calendly: 'Calendly', other: 'رابط تضمين آخر' }, default_value: 'none', return_format: 'value' }),
  F(O, 'booking_url', 'رابط التضمين', 'url', { name: 'sh_booking_url', conditional_logic: [[{ field: 'field_sh_opt_booking_provider', operator: '!=', value: 'none' }]], instructions: 'يُعرض داخل الخطوة الثانية بعد حفظ الطلب. يوجَّه الزائر إلى /thank-you/ من إعدادات أداة الحجز بعد تأكيد الموعد فقط.' }),

  tab(O, 'shared', 'المحتوى المشترك'),
  F(O, 'client_logos', 'شعارات العملاء', 'repeater', { name: 'sh_client_logos', layout: 'table', button_label: 'إضافة شعار', collapsed: 'name', instructions: 'الشعارات المعتمدة فقط. تظهر في الشريط المتحرك بنفس الترتيب.', sub_fields: [S('name', 'اسم العميل', 'text', { required: 1 }), S('logo', 'الشعار', 'image', { return_format: 'id', preview_size: 'thumbnail', required: 1 }), S('url', 'رابط (اختياري)', 'url')] }),
  F(O, 'platforms', 'المنصات والأدوات', 'repeater', { name: 'sh_platforms', layout: 'table', button_label: 'إضافة منصة أو أداة', collapsed: 'name', instructions: 'شعارات المنصات والأدوات في أقسام «منصات نعمل عليها» وأدوات القياس. كل قسم في الصفحات يختار منها ما يعرضه وترتيبه، والترتيب هنا ترتيبها في قوائم الاختيار. غيّر الشعار من مكتبة الوسائط (SVG أو PNG أو WebP بخلفية شفافة)؛ يظهر بنفس المقاس في كل الأقسام.', sub_fields: [
    S('name', 'الاسم', 'text', { required: 1, instructions: 'يُستخدم نصًا بديلًا للشعار عند عدم وجود اسم ظاهر بجانبه.' }),
    S('logo', 'الشعار', 'image', { return_format: 'id', preview_size: 'thumbnail', library: 'all' }),
    S('url', 'رابط (اختياري)', 'url', { instructions: 'إن أُدخل صار الشعار رابطًا يفتح في نافذة جديدة.' }),
    S('ref', 'المعرّف الداخلي', 'text', { instructions: 'يربط الشعارات المعتمدة بالأقسام؛ لا يظهر في المحرر.' })
  ] }),
  F(O, 'article_cta', 'دعوة الاستشارة بجانب المقالات', 'group', { name: 'sh_article_cta', layout: 'block', sub_fields: [
    S('title', 'العنوان', 'text', { default_value: 'تريد معرفة وضع موقعك؟' }),
    S('text', 'النص', 'textarea', { rows: 2, new_lines: '', default_value: 'مكالمة مجانية مدتها 30 دقيقة نراجع فيها موقعك ونحدد الأولوية.' }),
    S('label', 'نص الزر', 'text', { default_value: 'احجز استشارة' }),
    S('link', 'وجهة الزر', 'page_link', { post_type: ['page'], allow_null: 1 })
  ] }),
  F(O, 'home_cases', 'دراسات الحالة المختارة للأقسام المشتركة', 'relationship', { name: 'sh_featured_cases', post_type: ['case_study'], filters: ['search'], return_format: 'id', max: 6, instructions: 'فارغة = أحدث الحالات المنشورة.' }),

  tab(O, 'integrations', 'التكاملات'),
  F(O, 'reviews_shortcode', 'شورت كود التقييمات (Trustindex أو غيره)', 'text', { name: 'sh_reviews_shortcode', instructions: 'مثال: [trustindex no-registration=google]. يظهر في مكان قسم التقييمات في كل صفحة فيها هذا القسم، ويمكن تغييره لصفحة بعينها من «التقييمات في هذه الصفحة» في محررها. بلا شورت كود لإضافة مفعّلة لا يظهر قسم التقييمات، ولا تُعرض شهادات تجريبية.' }),
  F(O, 'gtm', 'معرّف Google Tag Manager', 'text', { name: 'sh_gtm_id', placeholder: 'GTM-XXXXXXX', instructions: 'مصدر تتبع واحد. لا يُحمّل إذا كانت إضافة أخرى تضيف GTM.' }),
  F(O, 'ga4', 'معرّف GA4 (إن لم يُستخدم GTM)', 'text', { name: 'sh_ga4_id', placeholder: 'G-XXXXXXX' }),
  F(O, 'search_console', 'رمز التحقق من Search Console', 'text', { name: 'sh_gsc_verification' }),

  tab(O, 'redirects', 'التحويلات'),
  F(O, 'redirects', 'تحويلات الروابط القديمة (301)', 'repeater', { name: 'sh_redirects', layout: 'table', button_label: 'إضافة تحويل', collapsed: 'from', instructions: 'لروابط منشورة في الموقع السابق ولا يوجد لها مسار مطابق. حوّل فقط إلى صفحة مكافئة في الموضوع والغرض؛ لا تحوّل إلى الرئيسية. يعمل التحويل فقط عندما لا توجد صفحة منشورة على الرابط القديم.', sub_fields: [
    S('from', 'الرابط القديم (المسار)', 'text', { placeholder: '/services/seo/stores-seo/', required: 1 }),
    S('to', 'الصفحة المكافئة', 'page_link', { post_type: ['page', 'post', 'case_study', 'team_member'], allow_null: 1, allow_archives: 0 }),
    S('note', 'سبب التكافؤ', 'text')
  ] })
], [[{ param: 'sh_screen', operator: '==', value: 'seohouse-settings' }]]); // Core settings screen (ACF free; Options Pages are PRO)

// fix: options fields whose storage name differs from helper name
options.fields.forEach(f => { if (f.name && !f.name.startsWith('sh_') && f.type !== 'tab' && f.type !== 'message') f.name = 'sh_' + f.name.replace(/^c_/, 'color_'); });

// ------------------------------------------------------------------ case study
const C = 'cs';
const caseStudy = group('case_study', 'بيانات دراسة الحالة', [
  tab(C, 'intro', 'المقدمة والبيانات'),
  F(C, 'summary', 'الملخص', 'textarea', { rows: 3, new_lines: '', required: 1 }),
  F(C, 'crumb', 'اسم مختصر لمسار التنقل', 'text', { instructions: 'فارغ = عنوان الحالة.' }),
  F(C, 'result', 'الرقم الرئيسي', 'text', { required: 1, instructions: 'كما في التقرير، مثل +432.54% أو 12.9 ألف. لا تكتب رقمًا غير موثق.' , wrapper: { width: '40' } }),
  F(C, 'result_label', 'اسم المؤشر الرئيسي', 'text', { required: 1, wrapper: { width: '60' } }),
  F(C, 'sector', 'القطاع', 'text', { wrapper: { width: '50' } }),
  F(C, 'market', 'السوق', 'text', { wrapper: { width: '50' } }),
  F(C, 'filter', 'تصنيف الفهرس', 'select', { choices: { ecommerce: 'التجارة الإلكترونية', health: 'الصحة والطب', education: 'التعليم والتدريب', 'real-estate': 'العقارات', tourism: 'السياحة', tech: 'التقنية', legal: 'القانون', food: 'الأغذية والمطاعم', other: 'أخرى' }, multiple: 1, ui: 1, return_format: 'value' }),
  F(C, 'client', 'اسم العميل', 'text', { wrapper: { width: '40' } }),
  F(C, 'client_public', 'إظهار اسم العميل', 'true_false', { ui: 1, default_value: 0, instructions: 'يظهر الاسم فقط بموافقة العميل. عند الإخفاء لا يظهر في الصفحة أو Schema أو نص الصور البديل.', wrapper: { width: '30' } }),
  F(C, 'client_url', 'موقع العميل', 'url', { wrapper: { width: '30' }, conditional_logic: [[{ field: 'field_sh_cs_client_public', operator: '==', value: '1' }]] }),
  F(C, 'client_logo', 'شعار العميل (إن كان معتمدًا)', 'image', { return_format: 'id', preview_size: 'thumbnail', conditional_logic: [[{ field: 'field_sh_cs_client_public', operator: '==', value: '1' }]] }),
  F(C, 'duration', 'مدة التعاون', 'text'),
  F(C, 'source', 'مصدر البيانات', 'text', { instructions: 'مثل: جوجل أناليتكس 4، سيرش كونسول.' }),
  F(C, 'period', 'فترة القياس', 'text'),

  tab(C, 'challenge', 'التحدي والحل'),
  F(C, 'challenge_title', 'عنوان التحدي', 'text'),
  F(C, 'challenge_text', 'وصف التحدي', 'textarea', { rows: 5, new_lines: 'wpautop' }),
  F(C, 'solution_title', 'عنوان الحل', 'text'),
  F(C, 'solution_intro', 'مقدمة الحل', 'textarea', { rows: 3, new_lines: '' }),
  F(C, 'steps', 'خطوات الحل', 'repeater', { layout: 'block', button_label: 'إضافة خطوة', collapsed: 'title', max: 10, sub_fields: [S('title', 'الخطوة', 'text', { required: 1 }), S('text', 'الشرح', 'textarea', { rows: 2, new_lines: '' })] }),

  tab(C, 'results', 'النتائج'),
  F(C, 'results_text', 'قراءة النتائج', 'textarea', { rows: 4, new_lines: 'wpautop' }),
  F(C, 'metrics', 'مؤشرات القياس', 'repeater', { layout: 'block', button_label: 'إضافة مؤشر', collapsed: 'label', max: 8, instructions: 'انسخ القيم كما في التقرير. أي نسبة مشتقة تُراجع حسابيًا قبل النشر.', sub_fields: [
    S('label', 'اسم المؤشر', 'text', { required: 1, wrapper: { width: '30' } }), S('unit', 'الوحدة', 'text', { wrapper: { width: '14' } }),
    S('before', 'القيمة السابقة', 'text', { wrapper: { width: '18' } }), S('after', 'القيمة اللاحقة', 'text', { wrapper: { width: '18' } }), S('change', 'التغيّر', 'text', { wrapper: { width: '20' } }),
    S('source', 'المصدر', 'text', { wrapper: { width: '50' } }), S('screenshot', 'اللقطة المرجعية', 'image', { return_format: 'id', preview_size: 'thumbnail', wrapper: { width: '50' } })
  ] }),
  F(C, 'period_before', 'الفترة الأولى', 'text', { wrapper: { width: '50' } }),
  F(C, 'period_after', 'الفترة الثانية', 'text', { wrapper: { width: '50' } }),

  tab(C, 'gallery', 'نظرة على النتائج'),
  F(C, 'gallery', 'لقطات التقارير', 'repeater', { layout: 'block', button_label: 'إضافة لقطة', max: 12, sub_fields: [
    S('image', 'الصورة', 'image', { return_format: 'id', preview_size: 'medium', required: 1 }), S('alt', 'نص بديل', 'text'),
    S('caption', 'التعليق', 'text'), S('source', 'المصدر', 'text'),
    S('verified', 'لقطة موثقة لهذه الحالة', 'true_false', { ui: 1, default_value: 0 })
  ] }),

  tab(C, 'links', 'الروابط والتواصل'),
  F(C, 'service_label', 'اسم الخدمة المرتبطة', 'text'),
  F(C, 'service_page', 'صفحة الخدمة المرتبطة', 'page_link', { post_type: ['page'], allow_null: 1 }),
  F(C, 'related_pages', 'صفحات أخرى مرتبطة', 'relationship', { post_type: ['page'], return_format: 'id', max: 4, filters: ['search'] }),
  F(C, 'cta_title', 'عنوان دعوة التواصل', 'text'),
  F(C, 'cta_text', 'نص دعوة التواصل', 'textarea', { rows: 2, new_lines: '' }),
  F(C, 'review_notes', 'ملاحظات مراجعة داخلية', 'textarea', { rows: 4, new_lines: '', instructions: 'لا تظهر للزائر أبدًا.' })
], [[{ param: 'post_type', operator: '==', value: 'case_study' }]], { hide_on_screen: ['the_content', 'excerpt', 'discussion', 'comments', 'format', 'categories', 'tags', 'send-trackbacks'] });

// ------------------------------------------------------------------ team member
const T = 'tm';
const team = group('team_member', 'بيانات عضو الفريق', [
  F(T, 'role', 'المسمى الوظيفي', 'text', { required: 1 }),
  F(T, 'experience', 'الخبرة المعتمدة', 'text', { instructions: 'كما هي معتمدة، مثل +6 سنوات خبرة.' }),
  F(T, 'specialties', 'التخصصات', 'repeater', { layout: 'table', button_label: 'إضافة تخصص', max: 8, sub_fields: [S('label', 'التخصص', 'text')] }),
  F(T, 'bio', 'النبذة', 'textarea', { rows: 4, new_lines: 'wpautop', instructions: 'نبذة حقيقية للشخص. تُخفى إذا كانت فارغة.' }),
  F(T, 'links', 'روابط خارجية', 'repeater', { layout: 'table', button_label: 'إضافة رابط', max: 5, sub_fields: [S('label', 'الاسم', 'text'), S('url', 'الرابط', 'url')] }),
  F(T, 'linkedin', 'لينكدإن', 'url'),
  F(T, 'author', 'حساب الكاتب في ووردبريس', 'user', { role: ['author', 'editor', 'administrator', 'contributor'], allow_null: 1, return_format: 'id', instructions: 'اربطه عندما يكتب العضو مقالات؛ يقود اسم الكاتب في المقال إلى هذا الملف.' }),
  F(T, 'home_photo', 'الظهور في صور الرئيسية', 'true_false', { ui: 1, default_value: 1 })
], [[{ param: 'post_type', operator: '==', value: 'team_member' }]], { position: 'acf_after_title', hide_on_screen: ['excerpt', 'discussion', 'comments', 'format', 'categories', 'tags', 'send-trackbacks'] });

// ------------------------------------------------------------------ post extras
const P = 'post';
const post = group('post', 'خيارات المقال', [
  F(P, 'intro', 'مقدمة المقال', 'textarea', { rows: 3, new_lines: '', instructions: 'تظهر تحت العنوان. فارغة = مقتطف المقال.' }),
  F(P, 'author_member', 'الكاتب من فريق العمل', 'post_object', { post_type: ['team_member'], allow_null: 1, return_format: 'id', instructions: 'فارغ = يُستخدم ملف الفريق المرتبط بحساب كاتب المقال، أو «فريق سيو هاوس».' }),
  F(P, 'reading', 'مدة القراءة (بالدقائق)', 'number', { min: 1, max: 90, instructions: 'فارغة = تُحسب تلقائيًا من طول المقال.' }),
  F(P, 'toc', 'إظهار فهرس المحتوى', 'true_false', { ui: 1, default_value: 1 }),
  F(P, 'sidebar_cta', 'إظهار دعوة الاستشارة الجانبية', 'true_false', { ui: 1, default_value: 1 }),
  F(P, 'updated_label', 'إظهار تاريخ آخر تحديث', 'true_false', { ui: 1, default_value: 1 })
], [[{ param: 'post_type', operator: '==', value: 'post' }]], { position: 'side' });

// ------------------------------------------------------------------ SEO + navigation (all content)
const G = 'seo';
const seo = group('seo', 'السيو ومسار التنقل', [
  F(G, 'rankmath_msg', 'Rank Math', 'message', { message: 'Rank Math مفعّلة: عنوان البحث ووصفه وصورة المشاركة ومنع الفهرسة تُحرَّر من صندوق Rank Math في هذه الصفحة. الحقول هنا لمسار التنقل ونوع الصفحة في البيانات المنظمة.' }),
  F(G, 'title', 'عنوان SEO', 'text', { name: 'sh_seo_title', maxlength: 70, instructions: 'فارغ = عنوان الصفحة + اسم الموقع. منفصل عن H1 ونص القائمة.' }),
  F(G, 'description', 'وصف SEO', 'textarea', { name: 'sh_seo_description', rows: 2, maxlength: 170, new_lines: '' }),
  F(G, 'noindex', 'منع الفهرسة (noindex)', 'true_false', { name: 'sh_seo_noindex', ui: 1, default_value: 0 }),
  F(G, 'og_image', 'صورة المشاركة', 'image', { name: 'sh_seo_og_image', return_format: 'id', preview_size: 'medium' }),
  F(G, 'crumb', 'الاسم في مسار التنقل', 'text', { name: 'sh_crumb', instructions: 'فارغ = عنوان الصفحة.' }),
  F(G, 'crumb_parent', 'الاسم عندما تظهر الصفحة كأب في مسار التنقل', 'text', { name: 'sh_crumb_ancestor', instructions: 'مثال: صفحة «تحسين محركات البحث» تظهر كـ«خدمات السيو» في مسار صفحاتها الفرعية.' }),
  F(G, 'schema_service', 'اسم الخدمة في Schema', 'text', { name: 'sh_schema_service', instructions: 'لصفحات الخدمات والقطاعات والدول: يصف الخدمة المقدمة (serviceType).' }),
  F(G, 'schema_type', 'نوع الصفحة في Schema', 'select', { name: 'sh_schema_type', choices: { auto: 'تلقائي حسب القالب', webpage: 'WebPage', service: 'WebPage + Service', about: 'AboutPage', contact: 'ContactPage', collection: 'CollectionPage', none: 'بدون' }, default_value: 'auto', return_format: 'value' })
], [[{ param: 'post_type', operator: '==', value: 'page' }], [{ param: 'post_type', operator: '==', value: 'post' }], [{ param: 'post_type', operator: '==', value: 'case_study' }], [{ param: 'post_type', operator: '==', value: 'team_member' }]], { position: 'normal', menu_order: 50 });

// ------------------------------------------------------------------ menu items
const M = 'menu';
const menu = group('menu_item', 'خيارات عنصر القائمة', [
  F(M, 'icon', 'أيقونة (الميجا مينيو)', 'select', { name: 'sh_menu_icon', choices: { '': 'بدون', search: 'بحث', window: 'موقع', bag: 'متجر', upload: 'رفع' }, allow_null: 1, return_format: 'value' }),
  F(M, 'all_label', 'نص رابط «الكل» أسفل اللوحة', 'text', { name: 'sh_menu_all_label', instructions: 'للعناصر التي لها قائمة فرعية؛ يشير إلى رابط العنصر نفسه.' }),
  F(M, 'layout', 'شكل اللوحة', 'select', { name: 'sh_menu_layout', choices: { '': 'تلقائي', list: 'قائمة بعمود واحد', grid2: 'عمودان' }, allow_null: 1, return_format: 'value' })
], [[{ param: 'nav_menu_item', operator: '==', value: 'all' }]]);

// ------------------------------------------------------------------ posts page (/blog/)
const B = 'blog';
const blogPage = group('blog_page', 'صفحة المدونة', [
  F(B, 'intro', 'وصف المدونة تحت العنوان', 'textarea', { name: 'sh_blog_intro', rows: 2, new_lines: '' })
], [[{ param: 'page_type', operator: '==', value: 'posts_page' }]], { position: 'acf_after_title', hide_on_screen: ['the_content', 'excerpt', 'discussion', 'comments'] });

// ------------------------------------------------------------------ reviews override (pages with a reviews section)
const RV = 'rv';
const reviewPages = JSON.parse(fs.readFileSync(path.join(repo, 'content-pack/manifest.json'), 'utf8')).pages
  .filter(p => p.kind === 'page' && (p.layouts || []).some(l => /^reviews/.test(l))).map(p => p.key);
const pageReviews = group('page_reviews', 'التقييمات في هذه الصفحة', [
  F(RV, 'shortcode', 'شورت كود التقييمات لهذه الصفحة (اختياري)', 'text', { name: 'sh_reviews_shortcode_page', placeholder: '[trustindex no-registration=google]', instructions: 'فارغ = الشورت كود العام من «إعدادات سيو هاوس ← التكاملات». يُعرض في مكان قسم التقييمات بنفس تصميمه. بلا شورت كود لا يظهر القسم.' })
], reviewPages.map(k => [{ param: 'page_template', operator: '==', value: `page-templates/${k}.php` }]), { position: 'side', menu_order: 40 });

for (const g0 of [options, caseStudy, team, post, seo, menu, blogPage, pageReviews]) {
  const g = assertFree({ ...g0, fields: toFree(g0.fields) }); // repeaters → sh_rows (ACF free)
  fs.writeFileSync(path.join(OUT, `${g.key}.json`), JSON.stringify(g, null, 2) + '\n');
}
console.log('wrote', 8, 'groups,', counter, 'fields');
