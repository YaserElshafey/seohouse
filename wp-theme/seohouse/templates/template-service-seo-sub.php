<?php
/**
 * Template Name: Service — SEO Sub Page
 * Used for: backlinks, content, consulting
 * Note: seo-stores is now at /sectors/ecommerce/ — this template redirects it.
 */

$slug = get_post_field( 'post_name', get_the_ID() );

// ── Redirect seo-stores to its canonical sector URL ──────────
if ( in_array( $slug, [ 'seo-stores', 'stores-seo' ], true ) ) {
	wp_redirect( sh_page_url( 'sectors/ecommerce' ), 301 );
	exit;
}

get_header();

// ── Per-service hardcoded defaults ────────────────────────────
switch ( $slug ) {

	case 'content':
		$def = [
			'hero_tag'   => 'كتابة المحتوى',
			'hero_h1'    => 'المحتوى الذكي',
			'hero_em'    => 'يبيع قبل أن تتحدث',
			'hero_desc'  => 'المحتوى الجيد لا يُكتب — يُخطَّط له. كل مقال نكتبه يبدأ من تحليل بحث، يفهم نية القارئ، ويُجيب بطريقة تُقنع جوجل والقارئ معاً.',
			'why_tag'    => 'لماذا يهم المحتوى',
			'why_h2'     => "المحتوى الجيّد\nهو قناة عملاء دائمة",
			'why_p'      => 'الإعلانات تنتهي عندما تنتهي الميزانية. أمّا المقال الذي يحتل المرتبة الأولى في جوجل، فيستمر في جلب العملاء أشهر وسنوات — مجاناً. هذا ما يجعل المحتوى الاستثمار التسويقي الأكثر ربحية على المدى البعيد.',
			'why_checks' => [
				[ 'b' => 'تجلب زيارات بدون تكلفة إعلانية',  't' => '— أشهراً وسنوات' ],
				[ 'b' => 'تبني الثقة قبل أن يتّصل العميل',   't' => '— هو يعرفك مسبقاً' ],
				[ 'b' => 'تُقصّر دورة البيع',                 't' => '— العميل يصل وقد قرّر فعلاً' ],
				[ 'b' => 'تُرسّخ خبرتك في القطاع',           't' => '— في عيون العميل وجوجل' ],
			],
			'why_card_lbl' => 'قيمة مقال يحتل المرتبة الأولى',
			'why_stats'    => [
				[ 'l' => 'الزيارات الشهرية المجانية',   'v' => '~2,400' ],
				[ 'l' => 'قيمة هذه الزيارات إعلانياً', 'v' => '$1,200+/شهر' ],
				[ 'l' => 'عمر افتراضي للمقال',          'v' => '3-5 سنوات', 'g' => true ],
			],
			'has_ai_vs'    => true,
			'has_types'    => true,
			'use_inc_grid' => true,
			'tactics_tag'  => 'ماذا تشمل الخدمة',
			'tactics_h2'   => 'أكثر من مجرد كتابة',
			'tactics_desc' => 'المحتوى الذي ينجح هو نتيجة منظومة كاملة — استراتيجية، تخطيط، بحث، كتابة، وتحسين.',
			'tactics'      => [
				[ 'n' => '01', 'h' => 'استراتيجية محتوى',      'd' => 'خطة 3-12 شهر تُحدّد المواضيع، الكلمات المستهدفة، تكرار النشر، وأولويات التنفيذ.',                                          'svg' => '<path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/>' ],
				[ 'n' => '02', 'h' => 'بحث الكلمات والنية',    'd' => 'تحليل ما يبحث عنه جمهورك فعلاً، مع تحديد نيّة كل كلمة (بحث، مقارنة، شراء).',                                            'svg' => '<circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>' ],
				[ 'n' => '03', 'h' => 'تحليل المنافسين',        'd' => 'ندرس محتوى منافسيك في الصفحة الأولى، ونكتشف الفجوات لنكتب محتوى أفضل وأشمل.',                                           'svg' => '<path d="M3 3v18h18"/><path d="M18 17V9m-4 8V5m-4 12v-2"/>' ],
				[ 'n' => '04', 'h' => 'كتابة احترافية',         'd' => 'كتّاب بشريون متخصّصون بقطاعك، يكتبون بلغة عربية فصيحة وتفاعلية مع جمهورك.',                                             'svg' => '<path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>' ],
				[ 'n' => '05', 'h' => 'تحسين السيو الكامل',     'd' => 'عناوين، Meta Description، Schema، Headings، روابط داخلية — كل ما يحتاجه جوجل.',                                        'svg' => '<path d="M9 12l2 2 4-4M22 12c0 5.523-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2s10 4.477 10 10z"/>' ],
				[ 'n' => '06', 'h' => 'تحديث المحتوى الموجود',  'd' => 'نُطوّر صفحاتك الحالية لتحقّق أداءً أفضل — أحياناً تحديث مقال أهم من كتابة 5 مقالات جديدة.',                            'svg' => '<path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>' ],
			],
			'proc_tag'  => 'منهجية العمل',
			'proc_h2'   => 'من فكرة إلى مقال يحتل المراتب',
			'proc_desc' => '5 مراحل تضمن أن كل مقال نكتبه له فرصة حقيقية في الظهور.',
			'proc'      => [
				[ 'n' => '1', 'h' => 'بحث وتحليل',    'p' => 'كلمات، نية، منافسون' ],
				[ 'n' => '2', 'h' => 'تخطيط الهيكل',  'p' => 'عنوان، عناصر، أسئلة' ],
				[ 'n' => '3', 'h' => 'كتابة المسوّدة', 'p' => 'كاتب متخصّص بقطاعك' ],
				[ 'n' => '4', 'h' => 'مراجعة وتحسين', 'p' => 'سيو، تحرير، تدقيق' ],
				[ 'n' => '5', 'h' => 'نشر ومتابعة',   'p' => 'نشر وتتبّع الأداء' ],
			],
			'faq_tag' => 'الأسئلة الشائعة',
			'faq_h2'  => 'أسئلة عن كتابة المحتوى',
			'faqs'    => [
				[ 'q' => 'هل المحتوى الذي تكتبونه أصلي؟',          'a' => 'نعم، 100%. كل محتوى نكتبه أصيل وفريد، ونتحقّق منه بأدوات الكشف عن الانتحال (Copyscape) قبل التسليم.' ],
				[ 'q' => 'هل تستخدمون الذكاء الاصطناعي؟',           'a' => 'نستخدم AI كمساعد للبحث والتخطيط — لا للكتابة النهائية. كل مقال يُكتب ويُراجع بواسطة كتّاب بشريين متخصّصين.' ],
				[ 'q' => 'كم مقالاً أحتاج شهرياً؟',                 'a' => 'يعتمد على قطاعك ومنافستك. للأعمال الجديدة 4-8 مقالات شهرياً مناسب. للقطاعات التنافسية قد تحتاج 12+. نحدّد العدد الأنسب في الاستراتيجية.' ],
				[ 'q' => 'هل تكتبون في كل القطاعات؟',               'a' => 'لدينا كتّاب متخصّصون في القطاعات الرئيسية: طبي، قانوني، تقني، تجارة إلكترونية، تعليم، عقارات، وأكثر.' ],
				[ 'q' => 'متى أرى أثر المحتوى على الترتيب؟',        'a' => 'المقالات تبدأ بالظهور في 2-4 أسابيع، لكن تحقيق المراكز الأولى يستغرق 3-6 أشهر للقطاعات المتوسطة، وأكثر للقطاعات التنافسية.' ],
				[ 'q' => 'هل التعديلات على المقال مشمولة؟',          'a' => 'نعم — جولة مراجعة وتعديلات مشمولة بكل مقال. نريدك راضياً عن المحتوى قبل النشر.' ],
			],
			'cta_h3'     => "جاهز لمحتوى\nيجلب عملاء؟",
			'cta_desc'   => 'احجز استشارة مجانية ولنضع معاً استراتيجية المحتوى المناسبة لك.',
			'cta_checks' => [ 'تحليل احتياجاتك المحتوائية', 'اقتراح مواضيع وكلمات', 'بدون أي التزام' ],
			'cta_btn'    => 'احجز استشارة مجانية',
			'bottom_h2'  => 'حوّل المحتوى إلى قناة عملاء',
			'bottom_p'   => 'احجز استشارة مجانية ولنبدأ معاً.',
			'btn2_txt'   => 'جميع خدمات السيو',
		];
		break;

	case 'consulting':
		$def = [
			'hero_badge' => 'للمدراء وأصحاب الأعمال',
			'hero_tag'   => 'استشارة سيو',
			'hero_h1'    => 'استشارة سيو',
			'hero_em'    => 'قبل أن تستثمر فيها',
			'hero_desc'  => 'قبل أن تتعاقد مع شركة سيو، أو توظّف فريقاً، أو تستمر مع شركتك الحالية — احصل على رأي محايد من خبير لا يبيعك خدمات تنفيذ. تعرف بالضبط أين تقف، وأين يجب أن تستثمر.',
			'why_tag'    => 'لماذا تحتاج استشارة',
			'why_h2'     => "قرار سيو خاطئ\nيكلّفك سنة كاملة",
			'why_p'      => 'السيو ليس "إعلان تجرّبه شهراً وتُلغيه". قرار التعاقد مع الشركة الخطأ، أو الاستثمار في الاستراتيجية الخطأ، يكلّفك 6-12 شهراً قبل أن تكتشف أنك في الطريق الخطأ — مع خسارة الميزانية والفرصة معاً.',
			'why_checks' => [
				[ 'b' => 'رأي محايد',                         't' => '— لا نبيعك خدمات تنفيذ في الاستشارة' ],
				[ 'b' => 'توفير الوقت والميزانية',             't' => '— استثمر في الصحيح من البداية' ],
				[ 'b' => 'تقييم شركتك الحالية بموضوعية',      't' => '— هل تستحق الاستمرار؟' ],
				[ 'b' => 'تفهم بنفسك',                        't' => '— لا تعتمد كلياً على ما يقوله المنفّذ' ],
			],
			'why_card_type'  => 'consulting',
			'has_who'        => true,
			'has_who_light'  => true,
			'has_del'        => true,
			'has_cons_types' => true,
			'tactics_tag'    => 'أنواع الاستشارات',
			'tactics_h2'     => '3 أنواع من الاستشارات',
			'tactics_desc'   => 'لكل سيناريو نوع استشارة مناسب — اختر ما يخدم وضعك.',
			'cons_types'     => [
				[
					'featured' => false,
					'tag'      => 'تدقيق وتشخيص',
					'name'     => 'تدقيق سيو شامل',
					'dur'      => 'جلسة + تقرير مفصّل',
					'feats'    => [ 'تقرير مكتوب بكل المشاكل والأولويات', 'تحليل المنافسين والفجوات', 'قائمة بأهم 10 مهام بترتيب الأولوية', 'جلسة شرح ومناقشة عبر زووم' ],
					'cta'      => 'اطلب استشارة تدقيق',
				],
				[
					'featured' => true,
					'tag'      => 'استشارة استراتيجية',
					'name'     => 'خارطة طريق سيو',
					'dur'      => '3-4 جلسات + خطة سنوية',
					'feats'    => [ 'كل ما في باقة التدقيق الشامل', 'خطة محتوى وكلمات لـ 12 شهر', 'استراتيجية روابط وتوزيع الميزانية', 'توصية بفريق التنفيذ المناسب' ],
					'cta'      => 'اطلب خارطة طريق',
				],
				[
					'featured' => false,
					'tag'      => 'رأي ثانٍ محايد',
					'name'     => 'تقييم شركتك الحالية',
					'dur'      => 'جلسة + تقرير محايد',
					'feats'    => [ 'مراجعة تقارير شركتك الـ 6 أشهر الماضية', 'تقييم جودة الروابط والمحتوى', 'توصية واضحة: استمر / غيّر / فاوض', 'أسئلة لطرحها على شركتك في الاجتماع القادم' ],
					'cta'      => 'اطلب رأي ثانٍ',
				],
			],
			'faq_bg'         => 'sec-white',
			'proc_tag'  => 'منهجية العمل',
			'proc_h2'   => 'كيف تجري الاستشارة؟',
			'proc_desc' => 'شفافية كاملة — تعرف بالضبط ما نفعله في كل مرحلة.',
			'proc'      => [
				[ 'n' => '1', 'h' => 'استكشاف',      'p' => 'جلسة لفهم وضعك وأهدافك' ],
				[ 'n' => '2', 'h' => 'تدقيق',        'p' => 'تحليل موقعك ومنافسيك' ],
				[ 'n' => '3', 'h' => 'تقرير أولي',   'p' => 'أولويات التنفيذ مكتوبة' ],
				[ 'n' => '4', 'h' => 'جلسة مناقشة', 'p' => 'شرح مفصّل عبر زووم' ],
				[ 'n' => '5', 'h' => 'خارطة طريق',  'p' => 'توصيات نهائية موثّقة' ],
			],
			'faq_tag' => 'الأسئلة الشائعة',
			'faq_h2'  => 'أسئلة عن الاستشارات',
			'faqs'    => [
				[ 'q' => 'هل الاستشارة محايدة فعلاً أم محاولة بيع؟',        'a' => 'محايدة 100%. الاستشارة خدمة مدفوعة منفصلة عن خدمات التنفيذ. توصياتنا قد تشمل عدم العمل معنا إذا رأينا أن شركة أخرى أنسب لقطاعك.' ],
				[ 'q' => 'هل يمكن أن أستخدم خارطة الطريق مع شركة أخرى؟',   'a' => 'طبعاً. الخطة ملكك بالكامل ولك حرية التنفيذ مع أي شركة أو فريق داخلي.' ],
				[ 'q' => 'كم تستغرق الاستشارة؟',                             'a' => 'تدقيق شامل: 5-7 أيام عمل. خارطة طريق 12 شهر: 10-14 يوم عمل. الرأي الثاني: 3-5 أيام عمل. تشمل التحليل والجلسات والتقرير النهائي.' ],
				[ 'q' => 'ماذا تحتاجون مني للبدء؟',                          'a' => 'صلاحية قراءة على Google Analytics وSearch Console، وجلسة استكشافية لفهم نشاطك وأهدافك. نتولّى الباقي.' ],
				[ 'q' => 'هل تُقدّمون جلسات شهرية مستمرة؟',                 'a' => 'نعم — للشركات التي تُنفّذ السيو داخلياً، نُقدّم باقة استشارة شهرية لمراجعة الأداء وتحديث الاستراتيجية.' ],
				[ 'q' => 'هل أحتاج خبرة في السيو لأستفيد من الاستشارة؟',    'a' => 'إطلاقاً. نُقدّم الاستشارة بلغة تجارية يفهمها صاحب العمل — هدفنا أن تخرج بقرارات واضحة.' ],
			],
			'cta_h3'     => "احصل على رأي محايد\nقبل أن تقرّر",
			'cta_desc'   => 'احجز جلسة استكشافية مجانية لنتعرّف على وضعك ونوصي بنوع الاستشارة الأنسب.',
			'cta_checks' => [ 'جلسة استكشافية 30 دقيقة', 'توصية بنوع الاستشارة المناسب', 'بدون أي التزام' ],
			'cta_btn'    => 'احجز جلسة مجانية',
			'bottom_h2'  => 'استثمر في القرار الصحيح',
			'bottom_p'   => 'احجز استشارة مجانية ولنبدأ معاً.',
			'btn2_txt'   => 'أنواع الاستشارات',
		];
		break;

	case 'seo-stores': // redirected above — this branch is never reached
	case 'stores-seo':
		$def = [
			'hero_badge'    => 'قطاع التجارة الإلكترونية',
			'hero_tag'      => 'سيو المتاجر',
			'hero_pre_em'   => 'سيو ',
			'hero_em_inline'=> 'المتاجر الإلكترونية',
			'hero_line2'    => 'الذي يُترجم إلى مبيعات',
			'hero_desc'     => 'رفع ترتيب منتجاتك وفئاتك في جوجل ليجدك العملاء قبل أن يجدوا منافسيك — بمنهجية مخصصة لكل منصة تجارة إلكترونية وكل قطاع.',
			'has_plat_badges'=> true,
			'why_tag'       => 'لماذا سيو المتاجر مختلف',
			'why_h2'        => "موقع الخدمات يختلف\nعن المتجر الإلكتروني",
			'why_p'         => 'المتاجر الإلكترونية تملك عشرات أو مئات الصفحات — كل منتج وكل فئة فرصة للترتيب على كلمة مفتاحية شرائية. هذا يعني أن السيو للمتاجر يحتاج منهجية مختلفة تماماً.',
			'why_stores'    => true,
			'why_checks'    => [
				[ 'b' => 'تحسين مئات صفحات المنتجات والفئات',           't' => '' ],
				[ 'b' => 'كلمات شرائية تجلب عملاء جاهزين للدفع',       't' => '' ],
				[ 'b' => 'تجنّب المحتوى المتكرر الذي يُضعف المتاجر',    't' => '' ],
				[ 'b' => 'تحسين سرعة التحميل التي تُقلل الارتداد',      't' => '' ],
				[ 'b' => 'تقارير مبيعات عضوية مرتبطة بكلمات محددة',     't' => '' ],
			],
			'why_card_type' => 'seo-stores',
			'has_pillars'   => true,
			'tactics_tag'   => 'ماذا نفعل',
			'tactics_h2'    => 'ستة محاور لسيو المتاجر',
			'tactics_desc'  => 'كل محور يعالج جانباً مختلفاً من ظهور متجرك في البحث.',
			'tactics'       => [
				[ 'n' => '01', 'h' => 'بحث الكلمات الشرائية',   'd' => 'الكلمات التي يستخدمها المشترون الجادون — لا المتصفحون فقط. الفرق يصنع المبيعات.',                                                                           'svg' => '<circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>' ],
				[ 'n' => '02', 'h' => 'تحسين صفحات المنتجات',   'd' => 'عناوين، أوصاف فريدة، بيانات هيكلية، وصور محسّنة — كل منتج يُعامَل كصفحة مستقلة.',                                                                         'svg' => '<path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/>' ],
				[ 'n' => '03', 'h' => 'تحسين بنية الفئات',      'd' => 'هيكل تنقل منطقي يساعد جوجل على فهم كتالوجك وفهرسة كل صفحة بالشكل الصحيح.',                                                                                'svg' => '<path d="M3 7h18M3 12h18M3 17h18"/>' ],
				[ 'n' => '04', 'h' => 'تحسين سرعة المتجر',      'd' => 'Core Web Vitals محسّنة — المتجر البطيء يفقد الزوار قبل أن يُكملوا الشراء.',                                                                                  'svg' => '<path d="M13 10V3L4 14h7v7l9-11h-7z"/>' ],
				[ 'n' => '05', 'h' => 'بناء روابط للمتجر',      'd' => 'روابط موثوقة تُعزز سلطة متجرك وتسرّع صعود صفحات منتجاتك في نتائج البحث.',                                                                                   'svg' => '<path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/>' ],
				[ 'n' => '06', 'h' => 'تقارير المبيعات العضوية', 'd' => 'تقرير شهري يُظهر نمو الزيارات العضوية والمبيعات المنسوبة لتحسين محركات البحث.',                                                                             'svg' => '<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>' ],
			],
			'has_plat_cards' => true,
			'plat_cards'     => [
				[ 'lbl' => 'سلة',  'h' => 'سيو متاجر سلة',     'p' => 'تحسين مخصص لقيود وإمكانيات منصة سلة — الأكثر انتشاراً في السوق السعودي.' ],
				[ 'lbl' => 'زد',   'h' => 'سيو متاجر زد',      'p' => 'استغلال كامل لإمكانيات زد التقنية في بناء هيكل سيو متين من البداية.' ],
				[ 'lbl' => 'SF',   'h' => 'سيو متاجر شوبيفاي', 'p' => 'تحسين تقني عميق لشوبيفاي مع إدارة التطبيقات المؤثرة على الأداء.' ],
				[ 'lbl' => 'WC',   'h' => 'سيو ووكومرس',       'p' => 'تحسين ووردبريس وووكومرس معاً — مرونة كاملة في التحسين التقني والمحتوى.' ],
			],
			'skip_proc' => true,
			'faq_tag'   => 'الأسئلة الشائعة',
			'faq_h2'    => 'أسئلة عن سيو المتاجر',
			'faq_bg'    => 'sec-off',
			'faqs'      => [
				[ 'q' => 'هل يختلف سيو المتجر عن سيو الموقع العادي؟',  'a' => 'نعم بشكل كبير. المتاجر تحتوي عشرات أو مئات الصفحات المتكررة وتحتاج معالجة خاصة لتفادي المحتوى المتكرر، بنية الفئات، وسرعة التحميل على كميات كبيرة من المنتجات.' ],
				[ 'q' => 'كم من الوقت حتى أرى مبيعات من السيو؟',         'a' => 'في الغالب 3–5 أشهر لبدء ظهور نتائج ملموسة في المبيعات العضوية. الكلمات الطويلة (long-tail) تظهر أسرع من الكلمات التنافسية العامة.' ],
				[ 'q' => 'هل يمكنكم تحسين آلاف المنتجات؟',               'a' => 'نعم. نضع استراتيجية قابلة للتوسع تُعالج المنتجات في مجموعات حسب الأولوية والأثر المتوقع — بدءاً بالأكثر مبيعاً.' ],
				[ 'q' => 'هل تعملون مع متاجر سلة المحدودة تقنياً؟',       'a' => 'نعم، نفهم القيود التقنية لسلة ونعمل ضمنها لتحقيق أقصى تحسين ممكن — المحتوى والهيكل وليس فقط الجانب التقني.' ],
				[ 'q' => 'ما المنصات التي تدعمونها؟',                     'a' => 'سلة، زد، شوبيفاي، ووكومرس، ماجنتو، وأي منصة أخرى. لكل منصة خصائصها التقنية ونفهمها جميعاً بعمق.' ],
				[ 'q' => 'هل تشمل الخدمة كتابة أوصاف المنتجات؟',         'a' => 'نعم — يمكن إضافة كتابة أوصاف فريدة لكل منتج كجزء من الباقة، خاصة للمنتجات الأكثر تأثيراً على المبيعات.' ],
			],
			'cta_h3'     => "هل متجرك يستحق\nالمرتبة الأولى؟",
			'cta_desc'   => 'نحلل متجرك مجاناً خلال 30 دقيقة ونضع معاً خطة السيو.',
			'cta_checks' => [ 'تحليل مجاني لمتجرك', 'توصيات عملية فورية', 'بدون أي التزام' ],
			'cta_btn'    => 'احجز استشارة مجانية',
			'bottom_h2'  => 'هل متجرك يستحق مبيعات أكثر؟',
			'bottom_p'   => 'احجز استشارة مجانية ونحلل معاً فرص نموّه في جوجل.',
			'btn2_txt'   => 'جميع خدمات السيو',
		];
		break;

	default: // backlinks
		$def = [
			'hero_tag'   => 'بناء الروابط',
			'hero_h1'    => 'روابط خلفية',
			'hero_em'    => 'تبني سلطة موقعك',
			'hero_desc'  => 'الروابط الخلفية تُصوّت لموقعك في عيون جوجل — وكلما كان الصوت من جهة موثوقة، رفع موقعك أعلى. نبني روابط حقيقية بمعايير صارمة، لا روابط رخيصة تُعرّضك للعقوبة.',
			'why_tag'    => 'لماذا تهم الروابط',
			'why_h2'     => "روابط خلفية = ثقة محسوبة\nفي عيون جوجل",
			'why_p'      => 'يستخدم جوجل الروابط كإشارة ثقة بين المواقع. عندما يربطك موقع موثوق، فهو يقول لجوجل "هذا موقع أثق به". الروابط لا تزال أحد أقوى عوامل الترتيب الثلاثة — لكن جودتها أهم من عددها بكثير.',
			'why_checks' => [
				[ 'b' => 'ترفع سلطة موقعك (DR/DA)',          't' => '— والسلطة تُترجم إلى ترتيب' ],
				[ 'b' => 'تُسرّع ترتيب صفحاتك',              't' => '— حتى صفحات السيو الجديدة' ],
				[ 'b' => 'تجلب زيارات إحالة (Referral)',       't' => '— مباشرة من المواقع المُحيلة' ],
				[ 'b' => 'تبني علامتك التجارية',               't' => '— حضور في مواقع موثوقة في قطاعك' ],
			],
			'why_card_lbl' => 'ما تأثير رابط واحد عالي الجودة',
			'why_stats'    => [
				[ 'l' => 'قيمته مقارنة بـ 100 رابط ضعيف',    'v' => 'أقوى بكثير' ],
				[ 'l' => 'تأثير رابط من DR70+ على الترتيب',   'v' => 'ملحوظ جداً' ],
				[ 'l' => 'عمر افتراضي للرابط الجيد',           'v' => 'سنوات', 'g' => true ],
			],
			'has_qv'       => true,
			'has_criteria' => true,
			'tactics_tag'  => 'منهجياتنا',
			'tactics_h2'   => 'كيف نبني روابط بشكل طبيعي؟',
			'tactics_desc' => 'منهجيات راسخة عالمياً، نُكيّفها للسوق العربي والسعودي.',
			'tactics'      => [
				[ 'n' => '01', 'h' => 'Guest Posting احترافي',   'd' => 'مقالات ضيف عالية الجودة في مدوّنات ومواقع موثوقة في قطاعك — بمحتوى أصيل يُفيد جمهور الموقع المُضيف.',             'svg' => '<path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/>' ],
				[ 'n' => '02', 'h' => 'الإشارات الإعلامية (PR)', 'd' => 'ظهور علامتك في مواقع إخبارية ومتخصّصة — روابط تحريرية حقيقية يصعب الحصول عليها بطرق أخرى.',                   'svg' => '<path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"/>' ],
				[ 'n' => '03', 'h' => 'تحليل روابط المنافسين',   'd' => 'نكشف الروابط التي يحصل عليها منافسوك، ونبني استراتيجية للوصول إلى نفس المواقع — أو أفضل منها.',              'svg' => '<path d="M3 3v18h18"/><path d="M18 17V9m-4 8V5m-4 12v-2"/>' ],
				[ 'n' => '04', 'h' => 'روابط الأدلّة الموثوقة',  'd' => 'إدراج موقعك في الأدلّة المتخصّصة الحقيقية — روابط أساسية لكل موقع، خاصة في القطاعات المحلية.',               'svg' => '<circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>' ],
				[ 'n' => '05', 'h' => 'محتوى يُحفّز الروابط',    'd' => 'إنشاء محتوى مرجعي (دراسات، إنفوجرافيك، أبحاث) يجلب الروابط بشكل طبيعي من مواقع تنقل عنه.',                  'svg' => '<path d="M13 10V3L4 14h7v7l9-11h-7z"/>' ],
				[ 'n' => '06', 'h' => 'إصلاح الروابط المكسورة',  'd' => 'كشف الروابط المكسورة في مواقع ذات صلة ونعرض محتواك كبديل — منهجية فعّالة وآمنة 100%.',                        'svg' => '<path d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>' ],
			],
			'proc_tag'  => 'منهجية العمل',
			'proc_h2'   => 'كيف نُنفّذ حملة الروابط؟',
			'proc_desc' => 'شفافية كاملة — تعرف بالضبط ما نفعل في كل مرحلة.',
			'proc'      => [
				[ 'n' => '1', 'h' => 'تدقيق',        'p' => 'تحليل بروفايل روابطك الحالي' ],
				[ 'n' => '2', 'h' => 'استراتيجية',   'p' => 'تحديد الصفحات المستهدفة' ],
				[ 'n' => '3', 'h' => 'بحث المواقع',  'p' => 'تصفية حسب المعايير الـ 6' ],
				[ 'n' => '4', 'h' => 'تنفيذ',         'p' => 'تواصل، محتوى، نشر' ],
				[ 'n' => '5', 'h' => 'تقرير شفاف',   'p' => 'كل رابط بالتفصيل والأثر' ],
			],
			'faq_tag' => 'الأسئلة الشائعة',
			'faq_h2'  => 'أسئلة عن بناء الروابط',
			'faqs'    => [
				[ 'q' => 'هل بناء الروابط آمن أم خطر على موقعي؟',    'a' => 'بناء الروابط الطبيعي والتحريري آمن 100% — وهو ما نفعله. ما يُعرّض المواقع للعقوبة هو شراء الروابط الرخيصة من شبكات PBN، وهذا ما لا نفعله أبداً.' ],
				[ 'q' => 'كم رابطاً أحتاج شهرياً؟',                  'a' => 'يعتمد على تنافسية قطاعك وسلطة موقعك الحالية. للأعمال الجديدة 4-8 روابط شهرياً مناسب. للقطاعات شديدة التنافسية قد تحتاج 12-20. الجودة قبل العدد دائماً.' ],
				[ 'q' => 'متى أرى أثر الروابط على الترتيب؟',          'a' => 'عادة 6-12 أسبوع لتُفهرس الروابط، ثم تبدأ بالتأثير على الترتيب. الأثر الكامل يظهر بعد 3-6 أشهر من بناء الروابط بانتظام.' ],
				[ 'q' => 'هل تضمنون قبول مقالات الضيف؟',             'a' => 'لا نضمن قبول موقع محدّد — لأن الضمان يعني شراء الروابط (محظور). لكننا نضمن العدد المتفق عليه شهرياً من خلال شبكتنا الواسعة.' ],
				[ 'q' => 'ماذا يحدث لو فقدت رابطاً بعد أشهر؟',      'a' => 'نراقب الروابط شهرياً. إذا فُقد رابط لأي سبب، نستبدله مجاناً ضمن فترة الضمان (عادة 6-12 شهراً حسب الباقة).' ],
				[ 'q' => 'هل تتعاملون مع روابط بالعربية فقط؟',        'a' => 'عربي وإنجليزي. لمشاريع تستهدف السعودية والخليج نُركّز على المواقع العربية. للمشاريع الدولية نبني روابط بالإنجليزية أيضاً.' ],
			],
			'cta_h3'     => "جاهز لبناء\nسلطة موقعك؟",
			'cta_desc'   => 'احجز استشارة مجانية ولنُحلّل بروفايل روابطك الحالي.',
			'cta_checks' => [ 'تحليل بروفايل الروابط الحالي', 'اقتراح استراتيجية مناسبة', 'بدون أي التزام' ],
			'cta_btn'    => 'احجز استشارة مجانية',
			'bottom_h2'  => 'ابنِ سلطة تستحق المراتب الأولى',
			'bottom_p'   => 'احجز استشارة مجانية ولنبدأ معاً.',
			'btn2_txt'   => 'جميع خدمات السيو',
		];
}

// ── ACF field overrides (only when explicitly set) ─────────────
$tag  = sh_field( 'sub_hero_tag' )   ?: $def['hero_tag'];
$h1       = sh_field( 'sub_hero_title' ) ?: ( $def['hero_h1'] ?? '' );
$em       = sh_field( 'sub_hero_em' )    ?: ( $def['hero_em'] ?? '' );
$desc     = sh_field( 'sub_hero_desc' )  ?: $def['hero_desc'];
$pre_em   = $def['hero_pre_em']    ?? '';
$em_inline= $def['hero_em_inline'] ?? '';
$line2    = $def['hero_line2']     ?? '';

$acf_faqs = sh_field( 'sub_faqs' );
$faqs     = ! empty( $acf_faqs ) ? $acf_faqs : null;

$contact_url = sh_page_url( 'contact' );
$seo_url     = sh_page_url( 'services/seo' );
?>

<!-- ═══════════════════════════════════════════════════════════
     HERO
     ═══════════════════════════════════════════════════════════ -->
<section class="svc-hero">
  <div style="position:absolute;inset:0;background-image:radial-gradient(rgba(255,255,255,.04) 1px,transparent 1px);background-size:36px 36px;mask-image:radial-gradient(ellipse 90% 80% at 50% 50%,#000 10%,transparent 75%);pointer-events:none"></div>
  <div style="position:absolute;border-radius:50%;filter:blur(60px);pointer-events:none;inset-inline-start:-200px;bottom:-100px;width:600px;height:600px;background:radial-gradient(circle,rgba(30,46,245,.22),transparent 65%)"></div>
  <div style="position:absolute;border-radius:50%;filter:blur(60px);pointer-events:none;inset-inline-end:-100px;top:0;width:480px;height:480px;background:radial-gradient(circle,rgba(30,46,245,.12),transparent 65%)"></div>
  <div class="wrap">
    <div class="svc-hero-inner">
      <div class="breadcrumb" style="justify-content:center;margin-bottom:22px">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">الرئيسية</a>
        <svg class="bc-sep" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
        <a href="<?php echo esc_url( $seo_url ); ?>">تحسين محركات البحث</a>
        <svg class="bc-sep" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
        <span style="color:rgba(255,255,255,.55)"><?php echo esc_html( $tag ); ?></span>
      </div>
      <span class="tag d" style="justify-content:center;margin-bottom:20px"><?php echo esc_html( $def['hero_badge'] ?? 'خدمة فرعية' ); ?></span>
      <?php if ( $em_inline ) : ?>
      <h1 class="page-h1"><?php echo esc_html( $pre_em ); ?><em><?php echo esc_html( $em_inline ); ?></em><br><?php echo esc_html( $line2 ); ?></h1>
      <?php else : ?>
      <h1 class="page-h1"><?php echo esc_html( $h1 ); ?><br><em><?php echo esc_html( $em ); ?></em></h1>
      <?php endif; ?>
      <p style="font-size:clamp(15px,1.55vw,17.5px);line-height:1.9;color:rgba(255,255,255,.55);max-width:660px;margin-inline:auto;margin-bottom:30px"><?php echo esc_html( $desc ); ?></p>
      <div class="pbtns">
        <a href="<?php echo esc_url( $contact_url ); ?>" class="btn btn-p lg">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
          احجز استشارة مجانية
        </a>
        <a href="<?php echo esc_url( $seo_url ); ?>" class="btn btn-g lg"><?php echo esc_html( $def['btn2_txt'] ?? 'جميع خدمات السيو' ); ?></a>
      </div>
      <?php if ( ! empty( $def['has_plat_badges'] ) ) : ?>
      <div class="plat-badges">
        <div class="plat-badge"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/></svg>سلة</div>
        <div class="plat-badge"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>زد</div>
        <div class="plat-badge"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="3" width="20" height="14" rx="2"/></svg>شوبيفاي</div>
        <div class="plat-badge"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>ووكومرس</div>
      </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════════
     WHY section — 2-col: checklist + stats card
     ═══════════════════════════════════════════════════════════ -->
<section class="sec sec-white">
  <div class="wrap">
    <div class="<?php echo ! empty( $def['why_stores'] ) ? 'why-stores-grid' : 'why-grid'; ?>">

      <!-- Left: text + checklist -->
      <div class="sr">
        <span class="tag" style="margin-bottom:12px"><?php echo esc_html( $def['why_tag'] ); ?></span>
        <h2 class="h2" style="margin-bottom:16px"><?php echo nl2br( esc_html( $def['why_h2'] ) ); ?></h2>
        <p class="bod" style="margin-bottom:18px"><?php echo esc_html( $def['why_p'] ); ?></p>
        <div class="chklist sr d1" style="margin-bottom:24px">
          <?php foreach ( $def['why_checks'] as $ck ) : ?>
          <div class="chk-item">
            <div class="chk-ico"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></div>
            <span><strong><?php echo esc_html( $ck['b'] ); ?></strong><?php echo $ck['t'] ? ' ' . esc_html( $ck['t'] ) : ''; ?></span>
          </div>
          <?php endforeach; ?>
        </div>
        <a href="<?php echo esc_url( $contact_url ); ?>" class="btn btn-p sr d2">احجز استشارة مجانية</a>
      </div>

      <!-- Right: stats/visual card -->
      <div class="sr d1">
        <div style="background:var(--navy-2);border-radius:var(--r4);padding:30px;position:relative;overflow:hidden">
          <div style="position:absolute;inset-inline-end:-50px;top:-50px;width:220px;height:220px;border-radius:50%;background:radial-gradient(circle,rgba(30,46,245,.28),transparent 70%)"></div>

          <?php if ( ( $def['why_card_type'] ?? 'default' ) === 'consulting' ) : ?>
            <!-- Consulting: 3 big-number stats -->
            <div style="font-size:9.5px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.22);margin-bottom:18px;position:relative;z-index:1">3 أرقام تُلخّص لماذا</div>
            <div style="display:flex;flex-direction:column;gap:12px;position:relative;z-index:1">
              <div style="padding:16px 18px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);border-radius:var(--r2)">
                <div style="font-size:24px;font-weight:900;color:#7b90ff;letter-spacing:-.02em;margin-bottom:4px">68%</div>
                <div style="font-size:12.5px;color:rgba(255,255,255,.55);line-height:1.6">من المعلنين على شركات سيو يصرّحون بعدم تحقيق نتائج مرضية</div>
              </div>
              <div style="padding:16px 18px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);border-radius:var(--r2)">
                <div style="font-size:24px;font-weight:900;color:#7b90ff;letter-spacing:-.02em;margin-bottom:4px">12 شهر</div>
                <div style="font-size:12.5px;color:rgba(255,255,255,.55);line-height:1.6">متوسط الوقت الضائع قبل اكتشاف أن الاستراتيجية خاطئة</div>
              </div>
              <div style="padding:16px 18px;background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.2);border-radius:var(--r2)">
                <div style="font-size:24px;font-weight:900;color:var(--green);letter-spacing:-.02em;margin-bottom:4px">3-5×</div>
                <div style="font-size:12.5px;color:rgba(255,255,255,.85);line-height:1.6">عائد الاستثمار من قرار سيو صحيح مقارنة بقرار خاطئ</div>
              </div>
            </div>

          <?php elseif ( ( $def['why_card_type'] ?? '' ) === 'seo-stores' ) : ?>
            <!-- Stores: journey steps -->
            <div style="font-size:9.5px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.22);margin-bottom:18px;position:relative;z-index:1">رحلة عميل المتجر في جوجل</div>
            <div style="display:flex;flex-direction:column;gap:10px;position:relative;z-index:1">
              <div style="display:flex;align-items:center;gap:10px;padding:12px 14px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);border-radius:var(--r2)">
                <div style="width:26px;height:26px;border-radius:6px;background:rgba(30,46,245,.2);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:900;color:#7b90ff;flex-shrink:0">1</div>
                <div style="font-size:13px;color:rgba(255,255,255,.72)">يبحث: "شراء منتج في الرياض"</div>
              </div>
              <div style="display:flex;align-items:center;gap:10px;padding:12px 14px;background:rgba(30,46,245,.14);border:1px solid rgba(30,46,245,.28);border-radius:var(--r2)">
                <div style="width:26px;height:26px;border-radius:6px;background:var(--blue);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:900;color:#fff;flex-shrink:0">2</div>
                <div style="font-size:13px;color:rgba(255,255,255,.85)">يجد متجرك في المرتبة الأولى</div>
              </div>
              <div style="display:flex;align-items:center;gap:10px;padding:12px 14px;background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.2);border-radius:var(--r2)">
                <div style="width:26px;height:26px;border-radius:6px;background:rgba(16,185,129,.3);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:900;color:var(--green);flex-shrink:0">3</div>
                <div style="font-size:13px;color:rgba(255,255,255,.72)">يشتري — بدون أي تكلفة إعلانية</div>
              </div>
            </div>

          <?php else : ?>
            <!-- Default: label + value rows -->
            <div style="font-size:9.5px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.22);margin-bottom:20px;position:relative;z-index:1"><?php echo esc_html( $def['why_card_lbl'] ?? '' ); ?></div>
            <div style="display:flex;flex-direction:column;gap:12px;position:relative;z-index:1">
              <?php foreach ( $def['why_stats'] as $st ) :
                  $is_green = ! empty( $st['g'] );
                  $bg  = $is_green ? 'rgba(16,185,129,.1)' : 'rgba(255,255,255,.04)';
                  $brd = $is_green ? 'rgba(16,185,129,.2)' : 'rgba(255,255,255,.07)';
                  $vc  = $is_green ? 'var(--green)' : '#7b90ff';
              ?>
              <div style="display:flex;justify-content:space-between;align-items:center;padding:14px 16px;background:<?php echo $bg; ?>;border:1px solid <?php echo $brd; ?>;border-radius:var(--r2)">
                <div style="font-size:13px;color:<?php echo $is_green ? 'rgba(255,255,255,.85)' : 'rgba(255,255,255,.72)'; ?>;<?php echo $is_green ? 'font-weight:700' : ''; ?>"><?php echo esc_html( $st['l'] ); ?></div>
                <div style="font-size:<?php echo strlen( $st['v'] ) > 6 ? '14.5px' : '18px'; ?>;font-weight:900;color:<?php echo $vc; ?>;letter-spacing:-.02em"><?php echo esc_html( $st['v'] ); ?></div>
              </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════════
     WHO NEEDS THIS — consulting only
     ═══════════════════════════════════════════════════════════ -->
<?php if ( ! empty( $def['has_who'] ) ) :
  $who_sec = ! empty( $def['has_who_light'] ) ? 'sec-surface' : 'sec-navy';
  $who_tag_cls = ! empty( $def['has_who_light'] ) ? 'tag' : 'tag d';
  $who_h2_cls  = ! empty( $def['has_who_light'] ) ? 'h2' : 'h2 wh';
  $who_bod_cls = ! empty( $def['has_who_light'] ) ? 'bod' : 'bod d';
?>
<section class="sec <?php echo $who_sec; ?>">
  <div class="wrap">
    <div class="sh c sr">
      <span class="<?php echo $who_tag_cls; ?>">من يحتاج استشارة سيو</span>
      <h2 class="<?php echo $who_h2_cls; ?>">هل تجد نفسك في إحدى هذه الحالات؟</h2>
      <p class="<?php echo $who_bod_cls; ?>">3 سيناريوهات شائعة لأصحاب الأعمال — في كل واحدة منها، ساعة استشارة توفّر عليك ميزانية أشهر.</p>
    </div>
    <div class="who-grid sr d1">
      <div class="who-card">
        <div class="who-q">السيناريو الأول</div>
        <h3>"أفكّر في التعاقد مع شركة سيو ولا أعرف كيف أختار"</h3>
        <p>تتلقّى عروضاً من 3-4 شركات بأرقام مختلفة ووعود متشابهة. كيف تعرف أيّها يستحق فعلاً؟ نُساعدك على فهم الفروقات الحقيقية، الأسئلة التي يجب أن تطرحها، والأرقام التي يجب أن تطلبها.</p>
      </div>
      <div class="who-card">
        <div class="who-q">السيناريو الثاني</div>
        <h3>"أعمل مع شركة سيو منذ أشهر ولا أرى نتائج واضحة"</h3>
        <p>تدفع شهرياً، وتستلم تقارير، لكن الزيارات والمبيعات لا ترتفع كما تتوقع. هل المشكلة من الشركة، أم من توقّعاتك، أم من قطاعك؟ نُقدّم لك تقييماً محايداً للوضع.</p>
      </div>
      <div class="who-card">
        <div class="who-q">السيناريو الثالث</div>
        <h3>"أملك موقعاً وأريد فهم وضعه قبل أي قرار"</h3>
        <p>قبل أن تستثمر في السيو، تريد أن تعرف بالضبط أين تقف، ما المشاكل، ما الفرص، وأين يجب أن تبدأ. تدقيق احترافي + خارطة طريق = قرارات مدروسة.</p>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ═══════════════════════════════════════════════════════════
     AI VS STRATEGY — content only
     ═══════════════════════════════════════════════════════════ -->
<?php if ( ! empty( $def['has_ai_vs'] ) ) : ?>
<section class="sec sec-surface">
  <div class="wrap">
    <div class="sh c sr">
      <span class="tag">لماذا نحن وليس الذكاء الاصطناعي</span>
      <h2 class="h2">المحتوى الذي يحتلّ المراتب الأولى<br>لا يخرج من ChatGPT</h2>
      <p class="bod">الذكاء الاصطناعي أداة ممتازة كمساعد. لكنه يفتقد لما يجعل المحتوى يبيع: الفهم، الخبرة، والاستراتيجية.</p>
    </div>
    <div class="vs-grid">
      <div class="vs-card vs-bad sr">
        <span class="vs-tag">المحتوى المُولَّد عشوائياً</span>
        <h3>لماذا لا يعمل وحده؟</h3>
        <div class="vs-list">
          <div class="vs-item"><svg viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>لا يفهم نيّة البحث الفعلية للقارئ السعودي/العربي</div>
          <div class="vs-item"><svg viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>محتوى متشابه يُكشف بسهولة من خوارزميات جوجل (E-E-A-T)</div>
          <div class="vs-item"><svg viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>يُكرّر معلومات عامة بلا خبرة قطاعية حقيقية</div>
          <div class="vs-item"><svg viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>لا يفهم سياق منتجك أو خدمتك أو فروقات منافسيك</div>
          <div class="vs-item"><svg viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>لغته رسمية مترجمة، لا تتفاعل مع جمهورك العربي</div>
          <div class="vs-item"><svg viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>لا يُحوّل القارئ إلى عميل — يُعطي معلومات بلا دعوة لإجراء</div>
        </div>
      </div>
      <div class="vs-card vs-good sr d1">
        <span class="vs-tag">منهجيتنا في كتابة المحتوى</span>
        <h3>كيف نُقدّم محتوى يعمل فعلاً؟</h3>
        <div class="vs-list">
          <div class="vs-item"><svg viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>تحليل بحث ونيّة قبل كل مقال — لا نكتب عشوائياً</div>
          <div class="vs-item"><svg viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>دراسة منافسيك في الصفحة الأولى ثم نكتب أفضل منهم</div>
          <div class="vs-item"><svg viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>كتّاب متخصّصون بقطاعك — طب، قانون، تقنية، تجارة</div>
          <div class="vs-item"><svg viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>لغة عربية أصيلة تُخاطب جمهورك السعودي بطريقته</div>
          <div class="vs-item"><svg viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>هيكل سيو متكامل: عناوين، Schema، روابط داخلية</div>
          <div class="vs-item"><svg viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>كل مقال له هدف تحويل واضح — قارئ يصبح عميلاً</div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ═══════════════════════════════════════════════════════════
     QUALITY vs QUANTITY — backlinks only
     ═══════════════════════════════════════════════════════════ -->
<?php if ( ! empty( $def['has_qv'] ) ) : ?>
<section class="sec sec-surface">
  <div class="wrap">
    <div class="sh c sr">
      <span class="tag">الجودة قبل العدد</span>
      <h2 class="h2">لماذا 10 روابط جيدة<br>أفضل من 1000 رابط ضعيف؟</h2>
      <p class="bod">في 2026، لا يقاس النجاح بعدد الروابط — بل بمصدرها وسياقها. هذا ما يفصل بين شركات السيو الجيدة والممارسات الخطرة.</p>
    </div>
    <div class="qv-grid">
      <div class="qv-card qv-bad sr">
        <span class="qv-tag">روابط خطرة</span>
        <h3>ما يفعله غيرنا (وما لا نفعله أبداً)</h3>
        <div class="qv-list">
          <div class="qv-item"><svg viewBox="0 0 24 24" fill="none" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>شراء آلاف الروابط من شبكات PBN رخيصة</div>
          <div class="qv-item"><svg viewBox="0 0 24 24" fill="none" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>روابط من مواقع spam وتعليقات مدوّنات عشوائية</div>
          <div class="qv-item"><svg viewBox="0 0 24 24" fill="none" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>تبادل روابط مفرط (Link Exchange) ممنهج</div>
          <div class="qv-item"><svg viewBox="0 0 24 24" fill="none" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>روابط بنصوص متطابقة مكرّرة (Anchor Spam)</div>
          <div class="qv-item"><svg viewBox="0 0 24 24" fill="none" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg><strong>النتيجة:</strong> عقوبات جوجل وفقدان الترتيب الحالي</div>
        </div>
      </div>
      <div class="qv-card qv-good sr d1">
        <span class="qv-tag">منهجيتنا</span>
        <h3>كيف نبني روابط آمنة وفعّالة</h3>
        <div class="qv-list">
          <div class="qv-item"><svg viewBox="0 0 24 24" fill="none" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>روابط من مواقع ذات سلطة حقيقية (DR/DA 40+)</div>
          <div class="qv-item"><svg viewBox="0 0 24 24" fill="none" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>مواقع ذات صلة بقطاعك ومحتوى عربي أصيل</div>
          <div class="qv-item"><svg viewBox="0 0 24 24" fill="none" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>روابط تحريرية طبيعية في سياق ذي معنى</div>
          <div class="qv-item"><svg viewBox="0 0 24 24" fill="none" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>تنوّع طبيعي في نصوص الروابط (Anchor Diversity)</div>
          <div class="qv-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg><strong>النتيجة:</strong> صعود ثابت في الترتيب بأمان كامل</div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ═══════════════════════════════════════════════════════════
     TACTICS — 6-card grid
     ═══════════════════════════════════════════════════════════ -->
<?php
$tactics_sec = ! empty( $def['has_cons_types'] ) ? 'sec-white' : ( ! empty( $def['has_qv'] ) ? 'sec-white' : 'sec-surface' );
?>
<section class="sec <?php echo $tactics_sec; ?>">
  <div class="wrap">
    <div class="sh c sr">
      <span class="tag"><?php echo esc_html( $def['tactics_tag'] ); ?></span>
      <h2 class="h2"><?php echo esc_html( $def['tactics_h2'] ); ?></h2>
      <p class="bod"><?php echo esc_html( $def['tactics_desc'] ); ?></p>
    </div>

    <?php if ( ! empty( $def['has_pillars'] ) ) : ?>
    <div class="pillars-grid">
      <?php
      $delays = [ '', 'd1', 'd2', 'd1', 'd2', 'd3' ];
      foreach ( $def['tactics'] as $i => $tc ) :
          $dc = $delays[ $i % 6 ];
      ?>
      <div class="pillar sr<?php echo $dc ? " $dc" : ''; ?>">
        <div class="pillar-n"><?php echo esc_html( $tc['n'] ); ?></div>
        <div class="pillar-ico">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><?php echo $tc['svg']; ?></svg>
        </div>
        <h3><?php echo esc_html( $tc['h'] ); ?></h3>
        <p><?php echo esc_html( $tc['d'] ); ?></p>
      </div>
      <?php endforeach; ?>
    </div>

    <?php elseif ( ! empty( $def['has_cons_types'] ) ) : ?>
    <div class="cons-grid">
      <?php foreach ( $def['cons_types'] as $i => $ct ) :
        $delays2 = [ '', 'd1', 'd2' ];
        $dc = $delays2[ $i % 3 ];
        $featured = ! empty( $ct['featured'] );
      ?>
      <div class="cons-card<?php echo $featured ? ' featured' : ''; ?> sr<?php echo $dc ? " $dc" : ''; ?>">
        <div class="cons-head">
          <div class="cons-tag"><?php echo esc_html( $ct['tag'] ); ?></div>
          <div class="cons-name"><?php echo esc_html( $ct['name'] ); ?></div>
          <div class="cons-dur"><?php echo esc_html( $ct['dur'] ); ?></div>
        </div>
        <div class="cons-body">
          <div class="cons-feats">
            <?php foreach ( $ct['feats'] as $feat ) : ?>
            <div class="cons-feat"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg><?php echo esc_html( $feat ); ?></div>
            <?php endforeach; ?>
          </div>
          <a href="<?php echo esc_url( $contact_url ); ?>" class="cons-cta"><?php echo esc_html( $ct['cta'] ); ?></a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <?php elseif ( ! empty( $def['use_inc_grid'] ) ) : ?>
    <div class="inc-grid">
      <?php
      $delays = [ '', 'd1', 'd2', '', 'd1', 'd2' ];
      foreach ( $def['tactics'] as $i => $tc ) :
          $dc = $delays[ $i % 6 ];
      ?>
      <div class="inc-card sr<?php echo $dc ? " $dc" : ''; ?>">
        <div class="inc-ico">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><?php echo $tc['svg']; ?></svg>
        </div>
        <h3><?php echo esc_html( $tc['h'] ); ?></h3>
        <p><?php echo esc_html( $tc['d'] ); ?></p>
      </div>
      <?php endforeach; ?>
    </div>

    <?php else : ?>
    <div class="wwd-grid">
      <?php
      $delays = [ '', 'd1', 'd2', 'd1', 'd2', 'd3' ];
      foreach ( $def['tactics'] as $i => $tc ) :
          $dc = $delays[ $i % 6 ];
      ?>
      <div class="wwd-card sr<?php echo $dc ? " $dc" : ''; ?>">
        <div class="wwd-n"><?php echo esc_html( $tc['n'] ); ?></div>
        <div class="wwd-ico">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><?php echo $tc['svg']; ?></svg>
        </div>
        <h3><?php echo esc_html( $tc['h'] ); ?></h3>
        <p><?php echo esc_html( $tc['d'] ); ?></p>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

  </div>
</section>

<!-- ═══════════════════════════════════════════════════════════
     PLATFORM CARDS — seo-stores only
     ═══════════════════════════════════════════════════════════ -->
<?php if ( ! empty( $def['has_plat_cards'] ) ) : ?>
<section class="sec sec-white">
  <div class="wrap">
    <div class="sh c sr">
      <span class="tag">المنصات المدعومة</span>
      <h2 class="h2">نعمل مع جميع منصات التجارة الإلكترونية</h2>
      <p class="bod">لكل منصة خصائصها التقنية — ونفهمها جميعاً بعمق.</p>
    </div>
    <div class="plat-cards sr d1">
      <?php foreach ( $def['plat_cards'] as $pc ) : ?>
      <div class="plat-card-big">
        <div class="plat-logo-box"><?php echo esc_html( $pc['lbl'] ); ?></div>
        <h3><?php echo esc_html( $pc['h'] ); ?></h3>
        <p><?php echo esc_html( $pc['p'] ); ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ═══════════════════════════════════════════════════════════
     6 CRITERIA — backlinks only
     ═══════════════════════════════════════════════════════════ -->
<?php if ( ! empty( $def['has_criteria'] ) ) : ?>
<section class="sec sec-navy">
  <div class="wrap">
    <div class="sh c sr">
      <span class="tag d">معايير القبول</span>
      <h2 class="h2 wh">قبل أن نقبل أي رابط، نتحقّق من 6 معايير</h2>
      <p class="bod d">إذا فشل الموقع في معيار واحد، نرفضه — حتى لو كانت تكلفته منخفضة.</p>
    </div>
    <div class="crit-grid sr d1">
      <div class="crit-card"><div class="crit-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div><div class="crit-body"><h3>سلطة الموقع (DR/DA)</h3><p>نستهدف مواقع DR 40+ كقاعدة — ولا نعمل مع مواقع منخفضة السلطة مهما كانت رخيصة.</p></div></div>
      <div class="crit-card"><div class="crit-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/></svg></div><div class="crit-body"><h3>ترافيك عضوي حقيقي</h3><p>الموقع يجب أن يكون له زيارات عضوية فعلية شهرية — لا فقط أرقام DR وهمية.</p></div></div>
      <div class="crit-card"><div class="crit-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4"/></svg></div><div class="crit-body"><h3>ذو صلة بقطاعك</h3><p>رابط من موقع تقني لا يفيد متجر عقارات — نختار مواقع لها علاقة فعلية بمجال نشاطك.</p></div></div>
      <div class="crit-card"><div class="crit-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></div><div class="crit-body"><h3>محتوى أصيل ومحدّث</h3><p>الموقع ينشر محتوى أصيل بانتظام — لا مواقع مهملة أو محتوى مسروق ومُترجم.</p></div></div>
      <div class="crit-card"><div class="crit-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div><div class="crit-body"><h3>بروفايل روابط نظيف</h3><p>نتحقّق من أن المُحيل ليس له تاريخ في spam أو عقوبات سابقة قد تنتقل عدواها لموقعك.</p></div></div>
      <div class="crit-card"><div class="crit-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5M2 12l10 5 10-5"/></svg></div><div class="crit-body"><h3>وضع الرابط في المحتوى</h3><p>نُفضّل الروابط داخل المقال (Contextual) على الروابط الجانبية — أكثر طبيعية وفعالية.</p></div></div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ═══════════════════════════════════════════════════════════
     TYPES OF CONTENT — content only
     ═══════════════════════════════════════════════════════════ -->
<?php if ( ! empty( $def['has_types'] ) ) : ?>
<section class="sec sec-surface ctype-sec">
  <div class="wrap">
    <div class="sh c sr">
      <span class="tag">أنواع المحتوى</span>
      <h2 class="h2">نكتب لكل سياق ولكل قطاع</h2>
      <p class="bod">لكل صفحة في موقعك دور مختلف — والمحتوى يجب أن يفهم هذا الدور.</p>
    </div>
    <div class="types-grid">
      <div class="type-card sr"><div class="type-card-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></div><div class="type-body"><h3>مقالات المدوّنة المعمّقة</h3><p>مقالات Pillar شاملة تُغطّي موضوعاً بالكامل وتُرتّب لكلمات تنافسية — العمود الفقري لاستراتيجية المحتوى.</p></div></div>
      <div class="type-card sr d1"><div class="type-card-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="18" height="18" rx="2"/></svg></div><div class="type-body"><h3>صفحات الخدمات</h3><p>صفحات تُحوّل الزائر إلى عميل — تُجيب على اعتراضاته، تُبرز فوائدك، وتدفعه للتواصل.</p></div></div>
      <div class="type-card sr d2"><div class="type-card-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4"/></svg></div><div class="type-body"><h3>أوصاف منتجات</h3><p>أوصاف فريدة لكل منتج — تتجنّب المحتوى المتكرر وتُبرز قيمة المنتج للمشتري.</p></div></div>
      <div class="type-card sr d3"><div class="type-card-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div><div class="type-body"><h3>أدلّة ومحتوى تعليمي</h3><p>محتوى يُجيب على أسئلة جمهورك، يبني خبرتك، ويجلب زيارات طويلة الأمد لموقعك.</p></div></div>
      <div class="type-card sr"><div class="type-card-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"/></svg></div><div class="type-body"><h3>صفحات هبوط (Landing Pages)</h3><p>محتوى مبني بمنهجية CRO — كل كلمة لها هدف تحويل واضح من زائر الإعلان إلى ليد.</p></div></div>
      <div class="type-card sr d1"><div class="type-card-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg></div><div class="type-body"><h3>محتوى محلي / GMB</h3><p>محتوى لصفحات Google Business، أوصاف فروع، ومحتوى محلي يستهدف "خدمة + مدينة".</p></div></div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ═══════════════════════════════════════════════════════════
     DELIVERABLES — consulting only
     ═══════════════════════════════════════════════════════════ -->
<?php if ( ! empty( $def['has_del'] ) ) : ?>
<section class="sec sec-navy">
  <div class="wrap">
    <div class="sh c sr">
      <span class="tag d">المخرجات</span>
      <h2 class="h2 wh">ماذا تستلم بعد الاستشارة؟</h2>
      <p class="bod d">الاستشارة ليست محادثة شفهية تنتهي بانتهاء الجلسة — بل مخرجات موثّقة تستخدمها في كل قرار سيو قادم.</p>
    </div>
    <div class="del-grid sr d1">
      <div class="del-item"><div class="del-num">1</div><div class="del-body"><h3>تقرير تدقيق مكتوب</h3><p>وثيقة بكل المشاكل، الفجوات، والفرص — مرتّبة حسب التأثير على عملك. تستخدمه لاحقاً مع أي فريق تنفيذ.</p></div></div>
      <div class="del-item"><div class="del-num">2</div><div class="del-body"><h3>خطة أولويات تنفيذية</h3><p>قائمة محدّدة بأهم المهام التي يجب البدء بها، مرتّبة حسب الأهمية وعائد الاستثمار المتوقّع لكل مهمة.</p></div></div>
      <div class="del-item"><div class="del-num">3</div><div class="del-body"><h3>تحليل المنافسين</h3><p>كيف يتفوّق عليك منافسوك بالضبط؟ ما الكلمات التي يحتلون بها المراتب وأنت لا؟ ما حجم استثمارهم في السيو تقريباً؟</p></div></div>
      <div class="del-item"><div class="del-num">4</div><div class="del-body"><h3>توصية بالميزانية المناسبة</h3><p>كم تحتاج فعلياً للوصول للنتيجة المرجوة؟ هل ميزانيتك الحالية مبالغ بها أم ناقصة؟ توصية محايدة بدون تحيّز.</p></div></div>
      <div class="del-item"><div class="del-num">5</div><div class="del-body"><h3>قائمة أسئلة لشركتك / فريقك</h3><p>الأسئلة التي يجب طرحها على شركة السيو في الاجتماع القادم — تكشف بسهولة جودة فهمها لقطاعك واحترافيتها.</p></div></div>
      <div class="del-item"><div class="del-num">6</div><div class="del-body"><h3>جلسة عملية مباشرة</h3><p>جلسة عبر زووم لمناقشة التقرير، الإجابة على كل أسئلتك، ومساعدتك على اتّخاذ قرار واثق وواضح.</p></div></div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ═══════════════════════════════════════════════════════════
     PROCESS — 5-step horizontal
     ═══════════════════════════════════════════════════════════ -->
<?php if ( empty( $def['skip_proc'] ) ) : ?>
<section class="sec <?php echo ! empty( $def['has_qv'] ) ? 'sec-surface' : 'sec-white'; ?>">
  <div class="wrap">
    <div class="sh c sr">
      <span class="tag"><?php echo esc_html( $def['proc_tag'] ); ?></span>
      <h2 class="h2"><?php echo esc_html( $def['proc_h2'] ); ?></h2>
      <p class="bod"><?php echo esc_html( $def['proc_desc'] ); ?></p>
    </div>
    <div class="proc-list sr d1">
      <?php foreach ( $def['proc'] as $step ) : ?>
      <div class="proc-step">
        <div class="proc-num"><?php echo esc_html( $step['n'] ); ?></div>
        <h3><?php echo esc_html( $step['h'] ); ?></h3>
        <p><?php echo esc_html( $step['p'] ); ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ═══════════════════════════════════════════════════════════
     FAQ + sticky CTA card
     ═══════════════════════════════════════════════════════════ -->
<section class="sec <?php echo esc_attr( $def['faq_bg'] ?? 'sec-off' ); ?>">
  <div class="wrap">
    <div class="faq-cta-layout">

      <!-- FAQs -->
      <div>
        <div class="sh sr">
          <span class="tag"><?php echo esc_html( $def['faq_tag'] ); ?></span>
          <h2 class="h2"><?php echo esc_html( $def['faq_h2'] ); ?></h2>
        </div>
        <div class="faq-list sr d1">
          <?php
          $faq_items = $faqs ?? $def['faqs'];
          foreach ( $faq_items as $faq ) :
              $q = $faq['question'] ?? ( $faq['q'] ?? '' );
              $a = $faq['answer']   ?? ( $faq['a'] ?? '' );
          ?>
          <div class="faq-item">
            <div class="faq-q">
              <span><?php echo esc_html( $q ); ?></span>
              <div class="faq-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></div>
            </div>
            <div class="faq-a"><div class="faq-a-inner"><?php echo esc_html( $a ); ?></div></div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Sticky CTA card -->
      <div class="cta-sticky">
        <div class="cta-side-card sr d1">
          <span class="tag d" style="position:relative;z-index:1;margin-bottom:10px">ابدأ الآن</span>
          <h3 style="font-size:clamp(20px,2.5vw,26px);font-weight:900;color:#fff;margin-bottom:12px;line-height:1.25;position:relative;z-index:1"><?php echo nl2br( esc_html( $def['cta_h3'] ) ); ?></h3>
          <p style="font-size:13.5px;color:rgba(255,255,255,.44);line-height:1.8;margin-bottom:22px;position:relative;z-index:1"><?php echo esc_html( $def['cta_desc'] ); ?></p>
          <div class="chklist" style="margin-bottom:22px">
            <?php foreach ( $def['cta_checks'] as $ck ) : ?>
            <div class="chk-item d">
              <div class="chk-ico"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></div>
              <?php echo esc_html( $ck ); ?>
            </div>
            <?php endforeach; ?>
          </div>
          <a href="<?php echo esc_url( $contact_url ); ?>" class="btn btn-p" style="width:100%;justify-content:center;position:relative;z-index:1"><?php echo esc_html( $def['cta_btn'] ); ?></a>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════════
     CTA BANNER
     ═══════════════════════════════════════════════════════════ -->
<?php
get_template_part( 'template-parts/layout/cta-banner', null, [
	'tag'         => 'ابدأ الآن',
	'title'       => $def['bottom_h2'],
	'description' => $def['bottom_p'],
	'buttons'     => [
		[ 'text' => 'احجز استشارة مجانية', 'url' => $contact_url, 'class' => 'btn-w lg' ],
		[ 'text' => $def['btn2_txt'] ?? 'جميع خدمات السيو', 'url' => $seo_url, 'class' => 'btn-g lg' ],
	],
] );
?>

<?php get_footer(); ?>
