<?php
/**
 * Template Name: Pricing Page
 */
get_header();

// ── ACF options ──────────────────────────────────────────────────────────
$hero_tag   = sh_option( 'pricing_hero_tag',   'الباقات والأسعار' );
$hero_raw   = sh_option( 'pricing_hero_title', "اختر الباقة\nالمناسبة لنشاطك" );
$hero_em    = sh_option( 'pricing_hero_em',    'المناسبة' );
$hero_desc  = sh_option( 'pricing_hero_desc',  'باقات سيو شاملة مصممة لأهدافك — من البداية إلى الهيمنة الكاملة على نتائج البحث.' );

$monthly_label = sh_option( 'pricing_toggle_monthly', 'شهري' );
$yearly_label  = sh_option( 'pricing_toggle_yearly',  'سنوي' );
$yearly_badge  = sh_option( 'pricing_yearly_badge',   'وفّر 20%' );

$contact_title = sh_option( 'pricing_contact_title', 'اطلب استشارة مجانية' );
$contact_sub   = sh_option( 'pricing_contact_sub',   'غير متأكد من الباقة المناسبة؟ تواصل معنا وسنساعدك في اختيار الخطة الأنسب لأهدافك.' );

// Reuse contact-page text for the form
$contact_email    = sh_option( 'contact_email',    'info@seohouse.sa' );
$contact_phone    = sh_option( 'contact_phone',    '' );
$social_linkedin  = sh_option( 'social_linkedin',  '#' );
$consult_duration = sh_option( 'consult_duration', '30 دقيقة' );

// ── Plans — defaults until ACF data is saved ──────────────────────────────
$plans = sh_option( 'pricing_plans', [] );
if ( empty( $plans ) ) {
    $plans = [
        [
            'plan_name'           => 'البداية الذكية',
            'plan_desc'           => 'مثالية للمشاريع الناشئة التي تحتاج إلى بناء أساس سيو متين.',
            'plan_badge'          => '',
            'plan_featured'       => false,
            'plan_monthly_price'  => '2,500',
            'plan_yearly_price'   => '24,000',
            'plan_currency'       => 'ر.س',
            'plan_monthly_suffix' => 'شهريًا',
            'plan_yearly_suffix'  => 'سنويًا',
            'plan_features'       => [
                [ 'feature_text' => 'تدقيق تقني شامل للموقع',           'feature_included' => true ],
                [ 'feature_text' => 'بحث الكلمات المفتاحية (50 كلمة)', 'feature_included' => true ],
                [ 'feature_text' => 'تحسين صفحات الموقع (On-Page)',     'feature_included' => true ],
                [ 'feature_text' => 'تقرير شهري مفصّل',                 'feature_included' => true ],
                [ 'feature_text' => 'بناء الروابط الخارجية',             'feature_included' => false ],
                [ 'feature_text' => 'كتابة محتوى متخصص',               'feature_included' => false ],
            ],
            'plan_cta_text' => 'ابدأ الآن',
        ],
        [
            'plan_name'           => 'النمو المتسارع',
            'plan_desc'           => 'الاختيار الأمثل للشركات التي تريد نتائج سيو قابلة للقياس.',
            'plan_badge'          => 'الأكثر طلبًا',
            'plan_featured'       => true,
            'plan_monthly_price'  => '4,500',
            'plan_yearly_price'   => '43,200',
            'plan_currency'       => 'ر.س',
            'plan_monthly_suffix' => 'شهريًا',
            'plan_yearly_suffix'  => 'سنويًا',
            'plan_features'       => [
                [ 'feature_text' => 'تدقيق تقني شامل للموقع',                  'feature_included' => true ],
                [ 'feature_text' => 'بحث الكلمات المفتاحية (150 كلمة)',        'feature_included' => true ],
                [ 'feature_text' => 'تحسين صفحات الموقع (On-Page)',            'feature_included' => true ],
                [ 'feature_text' => 'تقرير شهري مفصّل مع توصيات',             'feature_included' => true ],
                [ 'feature_text' => 'بناء الروابط الخارجية (25 رابطًا/شهر)',  'feature_included' => true ],
                [ 'feature_text' => 'كتابة محتوى (مقالتان شهريًا)',           'feature_included' => true ],
            ],
            'plan_cta_text' => 'ابدأ الآن',
        ],
        [
            'plan_name'           => 'الهيمنة الكاملة',
            'plan_desc'           => 'للمؤسسات الكبرى التي تستهدف الصدارة في أسواق تنافسية.',
            'plan_badge'          => '',
            'plan_featured'       => false,
            'plan_monthly_price'  => '7,500',
            'plan_yearly_price'   => '72,000',
            'plan_currency'       => 'ر.س',
            'plan_monthly_suffix' => 'شهريًا',
            'plan_yearly_suffix'  => 'سنويًا',
            'plan_features'       => [
                [ 'feature_text' => 'تدقيق تقني شامل للموقع',                   'feature_included' => true ],
                [ 'feature_text' => 'بحث الكلمات المفتاحية (غير محدود)',        'feature_included' => true ],
                [ 'feature_text' => 'تحسين صفحات الموقع (On-Page)',             'feature_included' => true ],
                [ 'feature_text' => 'تقارير أسبوعية مع KPIs مخصصة',           'feature_included' => true ],
                [ 'feature_text' => 'بناء الروابط الخارجية (50 رابطًا/شهر)', 'feature_included' => true ],
                [ 'feature_text' => 'كتابة محتوى (4 مقالات شهريًا)',          'feature_included' => true ],
            ],
            'plan_cta_text' => 'تواصل معنا',
        ],
    ];
}

// ── Hero title with <em> highlight ────────────────────────────────────────
$hero_title = $hero_em
    ? str_replace( $hero_em, '<em>' . esc_html( $hero_em ) . '</em>', esc_html( $hero_raw ) )
    : esc_html( $hero_raw );
$hero_title = nl2br( $hero_title );

// ── Calendly (same logic as contact page) ────────────────────────────────
$_cal_embed = trim( sh_option( 'calendly_embed', '' ) );
$_show_cal  = $_cal_embed && strpos( $_cal_embed, 'calendly.com' ) !== false;
if ( $_show_cal ) {
    wp_enqueue_script( 'calendly-widget', 'https://assets.calendly.com/assets/external/widget.js', [], null, true );
}

$contact_expect_title = sh_option( 'contact_expect_title', 'ماذا تتوقع بعد التواصل' );
$contact_expect_items = sh_option( 'contact_expect_items', [] );
if ( empty( $contact_expect_items ) ) {
    $contact_expect_items = [
        [ 'exp_text' => 'ردّ خلال 24 ساعة في أيام العمل' ],
        [ 'exp_text' => 'تحليل أولي سريع لموقعك' ],
        [ 'exp_text' => 'اجتماع ' . $consult_duration . ' عبر Google Meet' ],
        [ 'exp_text' => 'توصيات فورية — بدون أي التزام' ],
    ];
}
?>

<?php
get_template_part( 'template-parts/layout/page-hero', null, [
    'tag'         => $hero_tag,
    'title'       => $hero_title,
    'description' => $hero_desc,
    'breadcrumb'  => [ 'الباقات' => '' ],
    'buttons'     => [
        [ 'text' => 'اكتشف الباقات', 'url' => '#pricing-plans', 'class' => 'btn-p' ],
        [ 'text' => 'تواصل معنا',    'url' => '#pricing-contact', 'class' => 'btn-g' ],
    ],
] );
?>

<!-- ── Pricing Plans ──────────────────────────────────────────────────── -->
<section id="pricing-plans" class="sec sec-surface">
  <div class="wrap">

    <div class="sh c">
      <h2 class="h2">اختر الباقة المناسبة لك</h2>
    </div>

    <!-- Monthly / Yearly toggle -->
    <div style="text-align:center;margin-bottom:44px">
      <div class="pricing-toggle" role="group" aria-label="خيار الدفع">
        <button class="ptog-btn act" data-plan="monthly" aria-pressed="true"><?php echo esc_html( $monthly_label ); ?></button>
        <button class="ptog-btn" data-plan="yearly" aria-pressed="false">
          <?php echo esc_html( $yearly_label ); ?>
          <?php if ( $yearly_badge ) : ?>
            <span class="ptog-badge" aria-label="توفير"><?php echo esc_html( $yearly_badge ); ?></span>
          <?php endif; ?>
        </button>
      </div>
    </div>

    <!-- Plan cards -->
    <div class="pricing-grid">
      <?php
      $delay_classes = [ 'd1', 'd2', 'd3', 'd1', 'd2', 'd3' ];
      foreach ( $plans as $idx => $plan ) :
        $featured   = ! empty( $plan['plan_featured'] );
        $badge      = trim( $plan['plan_badge']          ?? '' );
        $plan_name  = trim( $plan['plan_name']           ?? '' );
        $m_price    = trim( $plan['plan_monthly_price']  ?? '' );
        $y_price    = trim( $plan['plan_yearly_price']   ?? '' );
        $currency   = trim( $plan['plan_currency']       ?? 'ريال' );
        $m_suffix   = trim( $plan['plan_monthly_suffix'] ?? 'شهريًا' );
        $y_suffix   = trim( $plan['plan_yearly_suffix']  ?? 'سنويًا' );
        $features   = is_array( $plan['plan_features'] ?? null ) ? array_filter( $plan['plan_features'] ) : [];
        $cta_text   = trim( $plan['plan_cta_text']       ?? 'ابدأ الآن' ) ?: 'ابدأ الآن';
        $delay      = $delay_classes[ $idx % 6 ];
        $card_class = 'plan-card sr ' . $delay . ( $featured ? ' featured' : '' );
      ?>
      <article class="<?php echo esc_attr( $card_class ); ?>" aria-label="<?php echo esc_attr( $plan_name ); ?>">

        <?php if ( $featured ) : ?>
          <div class="plan-ribbon" aria-label="الأنسب للنمو">الأنسب للنمو</div>
        <?php elseif ( $badge ) : ?>
          <div class="plan-badge-pill"><?php echo esc_html( $badge ); ?></div>
        <?php endif; ?>

        <h3 class="plan-name"><?php echo esc_html( $plan_name ); ?></h3>
        <p class="plan-desc"><?php echo esc_html( $plan['plan_desc'] ?? '' ); ?></p>

        <div class="plan-price-row"
             data-monthly="<?php echo esc_attr( $m_price ); ?>"
             data-yearly="<?php echo esc_attr( $y_price ); ?>"
             data-monthly-suffix="<?php echo esc_attr( $m_suffix ); ?>"
             data-yearly-suffix="<?php echo esc_attr( $y_suffix ); ?>">
          <span class="plan-amount"><?php echo esc_html( $m_price ); ?></span>
          <span class="plan-price-sep">/</span>
          <span class="plan-currency"><?php echo esc_html( $currency ); ?></span>
          <span class="plan-suffix"><?php echo esc_html( $m_suffix ); ?></span>
        </div>

        <div class="plan-divider" role="separator"></div>

        <?php if ( ! empty( $features ) ) : ?>
        <div class="plan-features" role="list">
          <?php foreach ( $features as $feat ) :
            $included = ! empty( $feat['feature_included'] );
          ?>
          <div class="plan-feat<?php echo $included ? '' : ' off'; ?>" role="listitem">
            <span class="plan-feat-ico <?php echo $included ? 'yes' : 'no'; ?>" aria-hidden="true">
              <?php if ( $included ) : ?>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
              <?php else : ?>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
              <?php endif; ?>
            </span>
            <span><?php echo esc_html( $feat['feature_text'] ?? '' ); ?></span>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <button class="plan-cta-btn"
                data-plan-name="<?php echo esc_attr( $plan_name ); ?>"
                aria-label="<?php echo esc_attr( $cta_text . ' — ' . $plan_name ); ?>">
          <?php echo esc_html( $cta_text ); ?>
        </button>

      </article>
      <?php endforeach; ?>
    </div>

  </div>
</section>

<!-- ── Contact Form ───────────────────────────────────────────────────── -->
<section id="pricing-contact" class="sec sec-white">
  <div class="wrap">

    <div class="pricing-contact-head">
      <span class="tag"><?php echo esc_html( $contact_title ); ?></span>
      <h2 class="h2"><?php echo esc_html( $contact_title ); ?></h2>
      <?php if ( $contact_sub ) : ?>
        <p><?php echo esc_html( $contact_sub ); ?></p>
      <?php endif; ?>
    </div>

    <div class="con-layout">

      <?php
      get_template_part( 'template-parts/layout/contact-form', null, [
          'form_title'    => sh_option( 'contact_form_title',    'أرسل لنا طلبك' ),
          'form_sub'      => sh_option( 'contact_form_sub',      'سنتواصل معك خلال 24 ساعة لتأكيد موعد الاستشارة.' ),
          'form_note'     => sh_option( 'contact_form_note',     'أو تواصل معنا على واتساب مباشرةً — سنردّ في أقرب وقت' ),
          'success_title' => sh_option( 'contact_success_title', 'تم الإرسال بنجاح!' ),
          'success_desc'  => sh_option( 'contact_success_desc',  'شكراً على تواصلك — سيتصل بك أحد متخصصينا خلال 24 ساعة لتحديد موعد الاستشارة.' ),
          'expect_title'  => $contact_expect_title,
          'expect_items'  => $contact_expect_items,
      ] );
      ?>

      <!-- Info panel — same as contact page -->
      <div class="info-panel">

        <?php if ( $_show_cal ) : ?>
        <div class="info-card sr d1">
          <div class="info-head">
            <div class="info-ico">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
            <div>
              <div class="info-t"><?php echo esc_html( sh_option( 'contact_cal_title', 'احجز وقتك مباشرةً' ) ); ?></div>
              <div class="info-sub"><?php echo esc_html( $consult_duration ); ?> · Google Meet · مجانية</div>
            </div>
          </div>
          <div class="calendly-wrap">
            <?php
            $strip = preg_replace( '/<script\b[^>]*>.*?<\/script>/is', '', $_cal_embed );
            echo wp_kses( $strip, [
                'div' => [
                    'class'            => true, 'style'            => true, 'id'               => true,
                    'data-url'         => true, 'data-resize'      => true, 'data-prefill'     => true,
                    'data-utm-source'  => true, 'data-utm-medium'  => true, 'data-utm-campaign'=> true,
                    'data-utm-content' => true, 'data-utm-term'    => true,
                ],
            ] );
            ?>
          </div>
        </div>
        <?php endif; ?>

        <div class="info-card sr d2">
          <div style="font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.22);margin-bottom:14px;position:relative;z-index:1">وسائل التواصل</div>
          <div class="con-details">
            <?php if ( $contact_email ) : ?>
            <a href="mailto:<?php echo esc_attr( $contact_email ); ?>" class="con-detail">
              <div class="con-d-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></div>
              <div><div class="con-d-t"><?php echo esc_html( $contact_email ); ?></div><div class="con-d-sub">للاستفسارات العامة</div></div>
            </a>
            <?php endif; ?>
            <?php if ( $contact_phone ) : ?>
            <a href="https://wa.me/<?php echo esc_attr( preg_replace( '/\D/', '', $contact_phone ) ); ?>" class="con-detail" target="_blank" rel="noopener">
              <div class="con-d-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg></div>
              <div><div class="con-d-t">واتساب</div><div class="con-d-sub">للتواصل السريع</div></div>
            </a>
            <?php endif; ?>
            <?php if ( $social_linkedin && $social_linkedin !== '#' ) : ?>
            <a href="<?php echo esc_url( $social_linkedin ); ?>" class="con-detail" target="_blank" rel="noopener">
              <div class="con-d-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg></div>
              <div><div class="con-d-t">لينكدإن</div><div class="con-d-sub">تابعنا للمحتوى المتخصص</div></div>
            </a>
            <?php endif; ?>
          </div>
        </div>


      </div>
    </div>
  </div>
</section>

<?php get_template_part( 'template-parts/layout/cta-banner' ); ?>

<script>
/* Pricing page: toggle + CTA scroll */
(function () {
  var toggleBtns = document.querySelectorAll('.ptog-btn');
  if (!toggleBtns.length) return;

  /* Toggle monthly / yearly prices */
  toggleBtns.forEach(function (btn) {
    btn.addEventListener('click', function () {
      toggleBtns.forEach(function (b) {
        b.classList.remove('act');
        b.setAttribute('aria-pressed', 'false');
      });
      btn.classList.add('act');
      btn.setAttribute('aria-pressed', 'true');
      var isYearly = btn.dataset.plan === 'yearly';
      document.querySelectorAll('.plan-price-row').forEach(function (row) {
        var amountEl = row.querySelector('.plan-amount');
        var suffixEl = row.querySelector('.plan-suffix');
        if (!amountEl) return;
        amountEl.textContent = isYearly ? row.dataset.yearly  : row.dataset.monthly;
        if (suffixEl) suffixEl.textContent = isYearly ? row.dataset.yearlySuffix : row.dataset.monthlySuffix;
      });
    });
  });

  /* CTA click: fill hidden plan field + smooth-scroll to form */
  document.querySelectorAll('.plan-cta-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var planName = btn.dataset.planName || '';
      var inp = document.getElementById('planSelected');
      if (inp) inp.value = planName;
      var section = document.getElementById('pricing-contact');
      if (section) section.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  });
}());
</script>

<?php get_footer(); ?>
