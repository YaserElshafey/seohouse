<?php
/**
 * Sector archive — matches Claude Design sectors.html reference exactly.
 * Shows 8-card grid with per-sector icons and links.
 */
get_header();

$contact_url = sh_page_url( 'contact' );

$sec_em    = sh_option( 'sectors_hero_em',    'لقطاعك' );
$sec_raw   = sh_option( 'sectors_hero_title', "سيو مخصص\nلقطاعك" );
$sec_desc  = sh_option( 'sectors_hero_desc',  'لكل قطاع كلماته المفتاحية، جمهوره، ومنافسوه — استراتيجيتنا مبنية على هذا الفهم، لا على قوالب جاهزة.' );
$sec_em_style = 'font-style:normal;background:linear-gradient(110deg,#7b90ff,#aab8ff 50%,#7b90ff);background-size:200% auto;-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent;animation:sh 5s linear infinite';
$sec_title = $sec_em
    ? str_replace( $sec_em, '<em style="' . $sec_em_style . '">' . esc_html( $sec_em ) . '</em>', esc_html( $sec_raw ) )
    : esc_html( $sec_raw );
$sec_title = nl2br( $sec_title );

$sectors_grid_title = sh_option( 'sectors_grid_title', 'اختر قطاعك لمعرفة كيف نخدمه' );
$sectors_grid_desc  = sh_option( 'sectors_grid_desc',  'نعمل مع شركات من قطاعات متنوعة — لكل واحد منها منهجية وفهم خاص.' );
$sectors_why_raw    = sh_option( 'sectors_why_title',  "استراتيجية قطاعية\nلا قالب جاهز" );
$sectors_why_title  = nl2br( esc_html( $sectors_why_raw ) );
$sectors_why_body1  = sh_option( 'sectors_why_body1',  'كل قطاع يملك كلماته المفتاحية الخاصة، نية بحث مختلفة، ومنافسين محددين. الاستراتيجية الناجحة للعيادة تختلف عن استراتيجية المتجر الإلكتروني تماماً.' );
$sectors_why_body2  = sh_option( 'sectors_why_body2',  'نبني استراتيجية السيو لكل عميل بناءً على فهم عميق لقطاعه — وليس نسخاً من قالب جاهز.' );
$sectors_cta_title  = sh_option( 'sectors_cta_title',  'احجز استشارة مجانية ولنبدأ معاً' );

// 8 sectors in Claude Design order
$all_sectors = [
    [
        'label' => 'التجارة الإلكترونية',
        'desc'  => 'متاجر سلة، زد، شوبيفاي وغيرها',
        'slug'  => 'ecommerce',
        'dc'    => '',
        'icon'  => '<path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/>',
    ],
    [
        'label' => 'الصحة والطب',
        'desc'  => 'عيادات، مستشفيات، مراكز صحية',
        'slug'  => 'health',
        'dc'    => ' d1',
        'icon'  => '<path d="M22 12h-4l-3 9L9 3l-3 9H2"/>',
    ],
    [
        'label' => 'العقارات والبناء',
        'desc'  => 'مطورون عقاريون وشركات تطوير',
        'slug'  => 'realestate',
        'dc'    => ' d2',
        'icon'  => '<path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
    ],
    [
        'label' => 'التعليم والتدريب',
        'desc'  => 'مدارس، جامعات، ومراكز تدريب',
        'slug'  => 'education',
        'dc'    => ' d3',
        'icon'  => '<path d="M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2z"/><path d="M22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z"/>',
    ],
    [
        'label' => 'الأغذية والمطاعم',
        'desc'  => 'مطاعم وسلاسل أغذية',
        'slug'  => 'food',
        'dc'    => ' d1',
        'icon'  => '<path d="M18 8h1a4 4 0 010 8h-1"/><path d="M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8z"/>',
    ],
    [
        'label' => 'القانون والاستشارات',
        'desc'  => 'مكاتب محاماة وشركات استشارية',
        'slug'  => 'legal',
        'dc'    => ' d2',
        'icon'  => '<path d="M3 6l9 6 9-6"/><path d="M3 6v12l9 6 9-6V6"/>',
    ],
    [
        'label' => 'التقنية والبرمجيات',
        'desc'  => 'شركات تقنية وSaaS',
        'slug'  => 'tech',
        'dc'    => ' d3',
        'icon'  => '<polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/>',
    ],
    [
        'label' => 'السياحة والسفر',
        'desc'  => 'وكالات سفر وشركات سياحة',
        'slug'  => 'tourism',
        'dc'    => ' d4',
        'icon'  => '<circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/>',
    ],
];

// Build a map from CPT posts if they exist
$cpt_links = [];
$sectors_q = new WP_Query( [ 'post_type' => 'sector', 'posts_per_page' => -1, 'orderby' => 'menu_order', 'order' => 'ASC' ] );
if ( $sectors_q->have_posts() ) {
    while ( $sectors_q->have_posts() ) {
        $sectors_q->the_post();
        $cpt_links[ get_post_field( 'post_name' ) ] = get_permalink();
    }
    wp_reset_postdata();
}
?>

<!-- ─── HERO ─────────────────────────────────────────────────────────────── -->
<section style="padding-block:clamp(110px,13vh,148px) clamp(52px,6vh,68px);background:var(--navy);position:relative;overflow:hidden">
  <div style="position:absolute;inset:0;background-image:radial-gradient(rgba(255,255,255,.04) 1px,transparent 1px);background-size:36px 36px;mask-image:radial-gradient(ellipse 90% 80% at 50% 50%,#000 10%,transparent 75%);pointer-events:none"></div>
  <div style="position:absolute;border-radius:50%;filter:blur(60px);pointer-events:none;inset-inline-start:-150px;bottom:-80px;width:500px;height:500px;background:radial-gradient(circle,rgba(30,46,245,.2),transparent 65%)"></div>
  <div class="wrap" style="position:relative;z-index:2">
    <div style="max-width:700px">
      <div class="breadcrumb">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">الرئيسية</a>
        <svg class="bc-sep" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
        <span style="color:rgba(255,255,255,.6)">القطاعات</span>
      </div>
      <span class="tag d" style="margin-bottom:18px">تخصص قطاعي</span>
      <h1 style="font-size:clamp(30px,4.5vw,56px);font-weight:900;line-height:1.08;letter-spacing:-.03em;color:#fff;margin-bottom:16px;animation:fu .75s .08s var(--ease) both"><?php echo wp_kses_post( $sec_title ); ?></h1>
      <p style="font-size:clamp(14px,1.5vw,17px);line-height:1.9;color:rgba(255,255,255,.48);max-width:600px;margin-bottom:28px;animation:fu .75s .16s var(--ease) both"><?php echo esc_html( $sec_desc ); ?></p>
    </div>
  </div>
</section>

<!-- ─── 8-CARD SECTORS GRID ───────────────────────────────────────────────── -->
<section class="sec sec-surface">
  <div class="wrap">
    <div class="sh c sr"><span class="tag">القطاعات</span><h2 class="h2"><?php echo esc_html( $sectors_grid_title ); ?></h2><p class="bod"><?php echo esc_html( $sectors_grid_desc ); ?></p></div>
    <div class="sec-grid">
      <?php foreach ( $all_sectors as $s ) :
          // Prefer CPT permalink; fall back to path-based URL
          $url = $cpt_links[ $s['slug'] ] ?? sh_page_url( 'sectors/' . $s['slug'] );
      ?>
      <a href="<?php echo esc_url( $url ); ?>" class="sec-card sr<?php echo esc_attr( $s['dc'] ); ?>">
        <div class="sec-ico">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><?php echo $s['icon']; // phpcs:ignore ?></svg>
        </div>
        <h3><?php echo esc_html( $s['label'] ); ?></h3>
        <p><?php echo esc_html( $s['desc'] ); ?></p>
        <div class="sec-arr">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ─── WHY SECTOR SPECIALISATION ────────────────────────────────────────── -->
<section class="sec sec-white">
  <div class="wrap">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:52px;align-items:center">
      <div class="sr">
        <span class="tag" style="margin-bottom:12px">لماذا التخصص مهم</span>
        <h2 class="h2" style="margin-bottom:16px"><?php echo wp_kses_post( $sectors_why_title ); ?></h2>
        <p class="bod" style="margin-bottom:18px"><?php echo esc_html( $sectors_why_body1 ); ?></p>
        <p class="bod" style="margin-bottom:24px"><?php echo esc_html( $sectors_why_body2 ); ?></p>
        <a href="<?php echo esc_url( $contact_url ); ?>" class="btn btn-p">احجز استشارة مجانية</a>
      </div>
      <div class="sr d1">
        <div style="background:var(--navy-2);border-radius:var(--r4);padding:28px;position:relative;overflow:hidden">
          <div style="position:absolute;inset-inline-end:-40px;top:-40px;width:180px;height:180px;border-radius:50%;background:radial-gradient(circle,rgba(30,46,245,.28),transparent 70%)"></div>
          <div style="font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.22);margin-bottom:18px;position:relative;z-index:1">مثال: نية البحث تختلف حسب القطاع</div>
          <div style="display:flex;flex-direction:column;gap:10px;position:relative;z-index:1">
            <div style="display:flex;align-items:center;gap:10px;padding:12px 13px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);border-radius:var(--r2)"><div style="width:8px;height:8px;border-radius:50%;background:var(--blue);flex-shrink:0"></div><div style="font-size:13px;color:rgba(255,255,255,.7)">متجر إلكتروني → كلمات شرائية</div></div>
            <div style="display:flex;align-items:center;gap:10px;padding:12px 13px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);border-radius:var(--r2)"><div style="width:8px;height:8px;border-radius:50%;background:var(--green);flex-shrink:0"></div><div style="font-size:13px;color:rgba(255,255,255,.7)">عيادة طبية → كلمات استفسارية</div></div>
            <div style="display:flex;align-items:center;gap:10px;padding:12px 13px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);border-radius:var(--r2)"><div style="width:8px;height:8px;border-radius:50%;background:#f59e0b;flex-shrink:0"></div><div style="font-size:13px;color:rgba(255,255,255,.7)">شركة عقارية → كلمات موقعية</div></div>
            <div style="display:flex;align-items:center;gap:10px;padding:12px 13px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);border-radius:var(--r2)"><div style="width:8px;height:8px;border-radius:50%;background:#8b5cf6;flex-shrink:0"></div><div style="font-size:13px;color:rgba(255,255,255,.7)">مركز تدريبي → كلمات معلوماتية</div></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php
get_template_part( 'template-parts/layout/cta-banner', null, [
    'tag'   => 'ابدأ الآن',
    'title' => $sectors_cta_title,
] );

get_footer();
