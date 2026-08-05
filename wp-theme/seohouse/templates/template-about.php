<?php
/**
 * Template Name: About Page
 */
get_header();

// ── Hero fields ────────────────────────────────────────────
$about_hero_tag  = sh_field( 'about_hero_tag',   null, 'قصتنا' );
$about_hero_em   = sh_field( 'about_hero_em',    null, 'الأداء' );
$about_hero_raw  = sh_field( 'about_hero_title', null, "نعمل بمنطق الأداء\nلا بمنطق الوعود" );
$about_hero_desc = sh_field( 'about_hero_desc',  null, 'سيو هاوس متخصصون في تحسين محركات البحث، يخدمون الأسواق السعودية والخليجية. لا نُقدّم وعوداً — نُقدّم خطوات واضحة ونتائج قابلة للقياس.' );
$about_hero_title = $about_hero_em
    ? str_replace( $about_hero_em, '<em>' . esc_html( $about_hero_em ) . '</em>', esc_html( $about_hero_raw ) )
    : esc_html( $about_hero_raw );
$about_hero_title = nl2br( $about_hero_title );

// ── Who section ────────────────────────────────────────────
$about_who_tag     = sh_field( 'about_who_tag',     null, 'من نحن' );
$about_who_heading = sh_field( 'about_who_heading', null, "متخصصون في السيو،\nلا في كل شيء" );
$about_who_body1   = sh_field( 'about_who_body1',   null, 'سيو هاوس تأسست لخدمة الأعمال السعودية والخليجية الباحثة عن حضور رقمي حقيقي في محركات البحث. لم نبنِ عملنا على الوعود — بنيناه على نتائج يمكن قياسها في Search Console.' );
$about_who_body2   = sh_field( 'about_who_body2',   null, 'نؤمن أن أفضل طريقة لإثبات قيمتنا هي أن نعمل بشفافية كاملة — تقرير شهري، أهداف محددة، وتواصل مباشر مع الفريق المنفذ.' );

// ── Principles ─────────────────────────────────────────────
$about_principles_heading = sh_field( 'about_principles_heading', null, 'مبادئنا في العمل' );
$about_principles = sh_field( 'about_principles', null, [] );
if ( empty( $about_principles ) ) {
    $about_principles = [
        [ 'title' => 'البيانات قبل الآراء', 'desc' => 'كل قرار يبدأ من Search Console وGoogle Analytics، لا من التخمين.' ],
        [ 'title' => 'الشفافية الكاملة',     'desc' => 'تقرير شهري مفصّل — كل ما تحقق وكل ما لم يتحقق ولماذا.' ],
        [ 'title' => 'بدون عقود ملزمة',      'desc' => 'نثبت قيمتنا شهراً بعد شهر — أنت دائماً من يقرر الاستمرار.' ],
    ];
}
$principle_icons = [
    '<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>',
    '<path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/>',
    '<path d="M9 12l2 2 4-4M22 12c0 5.523-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2s10 4.477 10 10z"/>',
    '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
    '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
];

// ── Values ─────────────────────────────────────────────────
$about_values_tag     = sh_field( 'about_values_tag',     null, 'قيمنا' );
$about_values_heading = sh_field( 'about_values_heading', null, 'ما يحكم طريقة عملنا' );
$about_values = sh_field( 'about_values', null, [] );
if ( empty( $about_values ) ) {
    $about_values = [
        [ 'title' => 'الأداء أولاً',   'desc' => 'كل قرار نتخذه يُقاس بأثره على الترتيب والزيارات والتحويلات.' ],
        [ 'title' => 'الصدق مع العميل','desc' => 'إذا كانت الكلمة صعبة أو النتيجة ستأخذ وقتاً — نقولها بوضوح.' ],
        [ 'title' => 'معايير جوجل فقط','desc' => 'نعمل وفق الدلائل الرسمية لجوجل — لا اختصارات تُعرّض موقعك للعقوبة.' ],
        [ 'title' => 'فريق متخصص',     'desc' => 'سيو تقني، محتوى، وباك لينك — خبراء في مجالهم تحت سقف واحد.' ],
        [ 'title' => 'التطوير المستمر', 'desc' => 'نتابع تحديثات جوجل ونُطوّر استراتيجياتنا باستمرار لنبقى في المقدمة.' ],
        [ 'title' => 'الصبر والمتابعة', 'desc' => 'السيو يحتاج وقتاً — نبقى إلى جانبك في كل مرحلة دون تخلٍّ.' ],
    ];
}
$value_icons = [
    '<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>',
    '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>',
    '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
    '<path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/>',
    '<path d="M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2z"/><path d="M22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z"/>',
    '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
];
$val_delays = [ '', ' d1', ' d2', ' d1', ' d2', ' d3' ];

// ── CTA ────────────────────────────────────────────────────
$about_cta_tag   = sh_field( 'about_cta_tag',   null, 'تعرّف أكثر' );
$about_cta_title = sh_field( 'about_cta_title', null, 'هل تريد أن تعمل معنا؟' );
$about_cta_desc  = sh_field( 'about_cta_desc',  null, 'ابدأ باستشارة مجانية — وستعرف هل نحن الشريك المناسب لك.' );
?>

<?php
get_template_part( 'template-parts/layout/page-hero', null, [
    'tag'         => $about_hero_tag,
    'title'       => $about_hero_title,
    'description' => $about_hero_desc,
    'breadcrumb'  => [ 'عن الشركة' => '' ],
] );
?>

<section class="sec sec-white">
  <div class="wrap">
    <div class="about-grid">
      <div class="sr about-who-col">
        <span class="tag"><?php echo esc_html( $about_who_tag ); ?></span>
        <h2 class="h2"><?php echo nl2br( esc_html( $about_who_heading ) ); ?></h2>
        <p class="bod"><?php echo esc_html( $about_who_body1 ); ?></p>
        <p class="bod"><?php echo esc_html( $about_who_body2 ); ?></p>
        <a href="<?php echo esc_url( sh_page_url( 'team' ) ); ?>" class="btn btn-o">تعرّف على فريقنا</a>
      </div>
      <div class="sr d1">
        <div class="story-card">
          <div style="position:relative;z-index:1">
            <div class="principles-label"><?php echo esc_html( $about_principles_heading ); ?></div>
            <div class="principle-list">
              <?php foreach ( $about_principles as $i => $principle ) :
                  $icon = $principle_icons[ $i ] ?? $principle_icons[0];
              ?>
              <div class="principle-item">
                <div class="principle-ico"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#7b90ff" stroke-width="1.8"><?php echo $icon; ?></svg></div>
                <div>
                  <div class="principle-head"><?php echo esc_html( $principle['title'] ); ?></div>
                  <div class="principle-desc"><?php echo esc_html( $principle['desc'] ); ?></div>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="sec sec-surface">
  <div class="wrap">
    <div class="sh c sr"><span class="tag"><?php echo esc_html( $about_values_tag ); ?></span><h2 class="h2"><?php echo esc_html( $about_values_heading ); ?></h2></div>
    <div class="values-grid">
      <?php foreach ( $about_values as $j => $value ) :
          $vicon = $value_icons[ $j ] ?? $value_icons[0];
          $vdc   = $val_delays[ $j ] ?? ' d3';
      ?>
      <div class="val-card sr<?php echo esc_attr( $vdc ); ?>">
        <div class="val-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><?php echo $vicon; ?></svg></div>
        <h3><?php echo esc_html( $value['title'] ); ?></h3>
        <p><?php echo esc_html( $value['desc'] ); ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php
get_template_part( 'template-parts/layout/cta-banner', null, [
    'tag'         => $about_cta_tag,
    'title'       => $about_cta_title,
    'description' => $about_cta_desc,
    'buttons'     => [
        [ 'text' => 'احجز استشارة مجانية', 'url' => sh_page_url( 'contact' ), 'class' => 'btn-w lg' ],
        [ 'text' => 'تعرّف على الفريق',    'url' => sh_page_url( 'team' ),    'class' => 'btn-g lg' ],
    ],
] );
?>

<?php get_footer(); ?>
