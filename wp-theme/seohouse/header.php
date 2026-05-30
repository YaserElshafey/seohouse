<!DOCTYPE html>
<html <?php language_attributes(); ?> dir="rtl">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php
// Logo sources
$logo_white = sh_option( 'logo_white' );
$logo_color = sh_option( 'logo_color' );
$logo_white_url = ! empty( $logo_white['url'] ) ? $logo_white['url'] : get_template_directory_uri() . '/assets/images/logo-white.png';
$logo_color_url = ! empty( $logo_color['url'] ) ? $logo_color['url'] : get_template_directory_uri() . '/assets/images/logo-color.png';

// Service page detection helpers
$current_template = get_page_template_slug();
$is_seo      = in_array( $current_template, [ 'templates/template-service-seo.php', 'templates/template-service-seo-sub.php' ], true );
$is_web      = $current_template === 'templates/template-service-web.php';
$is_stores   = $current_template === 'templates/template-service-stores.php';
$is_platform = $current_template === 'templates/template-service-platform.php';
// Determine current service parent for sub-pages
$parent_slug = '';
if ( $is_platform || $is_seo ) {
    $parent = wp_get_post_parent_id( get_the_ID() );
    if ( $parent ) {
        $parent_slug = get_post_field( 'post_name', $parent );
    }
}
$is_web_platform   = $is_platform && in_array( $parent_slug, [ 'web-design', 'website-design' ], true );
$is_store_platform = $is_platform && in_array( $parent_slug, [ 'stores', 'ecommerce-stores' ], true );
$is_seo_group      = $is_seo || $is_web || ( $is_platform && ! $is_web_platform && ! $is_store_platform );

// Default active sub-panel in mega menu: SEO unless on web/stores page
$default_sub = 1;
if ( $is_web || ( $is_platform && $is_web_platform ) ) {
    $default_sub = 2;
} elseif ( $is_stores || ( $is_platform && $is_store_platform ) ) {
    $default_sub = 3;
}

// Blog URL: page_for_posts → blog page slug → fallback path
$blog_page_id = (int) get_option( 'page_for_posts' );
$_blog_url = $blog_page_id ? get_permalink( $blog_page_id ) : '';
if ( ! $_blog_url ) {
    $_blog_candidate = get_page_by_path( 'blog' );
    $_blog_url = $_blog_candidate ? get_permalink( $_blog_candidate->ID ) : home_url( '/blog/' );
}
?>

<header id="nav">
  <div class="wrap">
    <nav class="nav-bar">

      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="nav-logo" aria-label="<?php bloginfo( 'name' ); ?>">
        <img src="<?php echo esc_url( $logo_white_url ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="logo-w" height="30">
        <img src="<?php echo esc_url( $logo_color_url ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="logo-c" height="30">
      </a>

      <ul class="nav-links">
        <li class="ni"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" <?php echo is_front_page() ? 'class="active"' : ''; ?>>الرئيسية</a></li>

        <!-- Services Mega Menu -->
        <li class="ni" id="svcNi">
          <button class="ni-btn" id="svcBtn">الخدمات
            <svg class="chev" viewBox="0 0 24 24" fill="none" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
          </button>
          <div class="mega mega-wide">
            <div class="mega-body mega-body-wide">
              <nav class="mega-main">
                <p class="mega-lbl">تحسين محركات البحث</p>
                <a href="<?php echo esc_url( sh_page_url( 'services/seo' ) ); ?>" class="mi<?php echo $default_sub === 1 ? ' act' : ''; ?>" data-sub="sub1">
                  <div class="mi-l">
                    <div class="mi-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg></div>
                    <span>تحسين محركات البحث</span>
                  </div>
                  <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="var(--muted)" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
                </a>
                <p class="mega-lbl" style="margin-top:10px">إنشاء وتصميم</p>
                <a href="<?php echo esc_url( sh_page_url( 'services/web-design' ) ); ?>" class="mi<?php echo $default_sub === 2 ? ' act' : ''; ?>" data-sub="sub2">
                  <div class="mi-l">
                    <div class="mi-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8m-4-4v4"/></svg></div>
                    <span>إنشاء وتصميم مواقع</span>
                  </div>
                  <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="var(--muted)" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
                </a>
                <a href="<?php echo esc_url( sh_page_url( 'services/stores' ) ); ?>" class="mi<?php echo $default_sub === 3 ? ' act' : ''; ?>" data-sub="sub3">
                  <div class="mi-l">
                    <div class="mi-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg></div>
                    <span>إنشاء وتصميم متاجر</span>
                  </div>
                  <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="var(--muted)" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
                </a>
                <a href="<?php echo esc_url( sh_page_url( 'services/products' ) ); ?>" class="mi<?php echo is_page( 'products' ) ? ' act' : ''; ?>" data-sub="sub4">
                  <div class="mi-l">
                    <div class="mi-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg></div>
                    <span>رفع المنتجات للمتاجر</span>
                  </div>
                </a>
              </nav>

              <!-- Sub: SEO -->
              <div class="mega-sub<?php echo $default_sub === 1 ? ' show' : ''; ?>" id="sub1">
                <p class="mega-sub-lbl">خدمات السيو الفرعية</p>
                <div class="mega-sub-grid">
                  <a href="<?php echo esc_url( sh_page_url( 'services/seo/backlinks' ) ); ?>" class="sl"><span class="sl-dot"></span><div><strong>بناء الباك لينك</strong><p>روابط تقوّي سلطة موقعك</p></div></a>
                  <a href="<?php echo esc_url( sh_page_url( 'services/seo/content' ) ); ?>" class="sl"><span class="sl-dot"></span><div><strong>كتابة المحتوى</strong><p>محتوى يُرضي القارئ وجوجل</p></div></a>
                  <a href="<?php echo esc_url( sh_page_url( 'services/seo/consulting' ) ); ?>" class="sl"><span class="sl-dot"></span><div><strong>استشارات تحليل الأداء</strong><p>قراءة التقارير واتخاذ القرار</p></div></a>
                </div>
              </div>

              <!-- Sub: Web Design -->
              <div class="mega-sub<?php echo $default_sub === 2 ? ' show' : ''; ?>" id="sub2">
                <p class="mega-sub-lbl">تخصّصات تصميم المواقع</p>
                <div class="mega-sub-grid">
                  <a href="<?php echo esc_url( sh_page_url( 'services/web-design/wordpress' ) ); ?>" class="sl"><span class="sl-dot"></span><div><strong>تصميم مواقع ووردبريس</strong><p>إدارة محتوى مرنة لمواقع الشركات والتسويق</p></div></a>
                  <a href="<?php echo esc_url( sh_page_url( 'services/web-design/webflow' ) ); ?>" class="sl"><span class="sl-dot"></span><div><strong>تصميم مواقع ويب فلو</strong><p>تصاميم عالية الجودة بإطلاق سريع</p></div></a>
                  <a href="<?php echo esc_url( sh_page_url( 'services/web-design/react-next' ) ); ?>" class="sl"><span class="sl-dot"></span><div><strong>تصميم مواقع رياكت ونكست</strong><p>أداء متقدّم لمنتجات رقمية قابلة للتوسّع</p></div></a>
                  <a href="<?php echo esc_url( sh_page_url( 'services/web-design/custom-dev' ) ); ?>" class="sl"><span class="sl-dot"></span><div><strong>برمجة مواقع خاصة</strong><p>متطلبات فريدة وأنظمة مخصّصة بالكامل</p></div></a>
                </div>
              </div>

              <!-- Sub: Stores -->
              <div class="mega-sub<?php echo $default_sub === 3 ? ' show' : ''; ?>" id="sub3">
                <p class="mega-sub-lbl">منصّات المتاجر</p>
                <div class="mega-sub-grid">
                  <a href="<?php echo esc_url( sh_page_url( 'services/stores/shopify' ) ); ?>" class="sl"><span class="sl-dot"></span><div><strong>إنشاء متاجر شوبيفاي</strong><p>تجارة إلكترونية مرنة بمعايير دولية</p></div></a>
                  <a href="<?php echo esc_url( sh_page_url( 'services/stores/salla' ) ); ?>" class="sl"><span class="sl-dot"></span><div><strong>إنشاء متاجر سلة</strong><p>منصّة سعودية جاهزة للسوق المحلي</p></div></a>
                  <a href="<?php echo esc_url( sh_page_url( 'services/stores/zid' ) ); ?>" class="sl"><span class="sl-dot"></span><div><strong>إنشاء متاجر زد</strong><p>بنية محلية قوية لانطلاقة احترافية</p></div></a>
                  <a href="<?php echo esc_url( sh_page_url( 'services/stores/woocommerce' ) ); ?>" class="sl"><span class="sl-dot"></span><div><strong>إنشاء متاجر ووكومرس</strong><p>تحكّم كامل على ووردبريس</p></div></a>
                </div>
              </div>

              <!-- Sub: Products -->
              <div class="mega-sub" id="sub4">
                <p class="mega-sub-lbl">رفع المنتجات للمتاجر</p>
                <p style="font-size:13px;color:var(--muted);line-height:1.8;padding-top:6px">رفع منظّم واحترافي عبر جميع المنصات — ليس مجرد إدخال بيانات.</p>
              </div>
            </div>
            <div class="mega-foot">
              <a href="<?php echo esc_url( sh_page_url( 'services/seo' ) ); ?>" class="mega-all">جميع خدمات السيو
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
              </a>
            </div>
          </div>
        </li>

        <li class="ni"><a href="<?php echo esc_url( sh_page_url( 'results' ) ); ?>" <?php echo is_post_type_archive( 'case_study' ) || is_page( 'results' ) ? 'class="active"' : ''; ?>>نتائج الأعمال</a></li>
        <li class="ni"><a href="<?php echo esc_url( sh_page_url( 'about' ) ); ?>" <?php echo is_page( 'about' ) ? 'class="active"' : ''; ?>>عن الشركة</a></li>
        <li class="ni"><a href="<?php echo esc_url( sh_page_url( 'team' ) ); ?>" <?php echo is_page( 'team' ) ? 'class="active"' : ''; ?>>فريق العمل</a></li>

        <!-- Sectors Dropdown -->
        <li class="ni" id="secNi">
          <button class="ni-btn" id="secBtn">القطاعات
            <svg class="chev" viewBox="0 0 24 24" fill="none" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
          </button>
          <div class="sec-drop">
            <div class="sec-drop-grid">
              <?php
              $sectors = new WP_Query( [ 'post_type' => 'sector', 'posts_per_page' => -1, 'orderby' => 'menu_order', 'order' => 'ASC' ] );
              if ( $sectors->have_posts() ) :
                  $nav_icon_paths = [
                      'ecommerce'  => '<path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/>',
                      'health'     => '<path d="M22 12h-4l-3 9L9 3l-3 9H2"/>',
                      'realestate' => '<path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
                      'education'  => '<path d="M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2z"/><path d="M22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z"/>',
                      'food'       => '<path d="M18 8h1a4 4 0 010 8h-1"/><path d="M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8z"/>',
                      'legal'      => '<path d="M3 6l9 6 9-6"/><path d="M3 6v12l9 6 9-6V6"/>',
                      'tech'       => '<polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/>',
                      'tourism'    => '<circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/>',
                  ];
                  while ( $sectors->have_posts() ) : $sectors->the_post();
                      $sector_icon_svg = sh_field( 'sector_icon_svg' );
                      $slug            = get_post_field( 'post_name' );
                      if ( $sector_icon_svg ) {
                          $icon_out = sh_svg( $sector_icon_svg );
                      } else {
                          $ico      = $nav_icon_paths[ $slug ] ?? '<circle cx="12" cy="12" r="10"/>';
                          $icon_out = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">' . $ico . '</svg>';
                      }
                      ?>
                      <a href="<?php the_permalink(); ?>" class="sd-link<?php echo is_singular( 'sector' ) && get_the_ID() === get_queried_object_id() ? ' active' : ''; ?>">
                        <?php echo $icon_out; // phpcs:ignore ?>
                        <?php the_title(); ?>
                      </a>
                      <?php
                  endwhile;
                  wp_reset_postdata();
              else :
                  // Fallback static links if no sectors exist yet
                  $fallback_sectors = [
                      [ 'التجارة الإلكترونية', 'sectors/ecommerce', '<path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/>' ],
                      [ 'الصحة والطب',         'sectors/health',    '<path d="M22 12h-4l-3 9L9 3l-3 9H2"/>' ],
                      [ 'العقارات والبناء',    'sectors/realestate','<path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>' ],
                      [ 'التعليم والتدريب',    'sectors/education', '<path d="M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2z"/><path d="M22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z"/>' ],
                      [ 'الأغذية والمطاعم',   'sectors/food',      '<path d="M18 8h1a4 4 0 010 8h-1"/><path d="M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8z"/>' ],
                      [ 'القانون والاستشارات','sectors/legal',     '<path d="M3 6l9 6 9-6"/><path d="M3 6v12l9 6 9-6V6"/>' ],
                      [ 'التقنية والبرمجيات', 'sectors/tech',      '<polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/>' ],
                      [ 'السياحة والسفر',     'sectors/tourism',   '<circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/>' ],
                  ];
                  foreach ( $fallback_sectors as $s ) :
                      ?>
                      <a href="<?php echo esc_url( sh_page_url( $s[1] ) ); ?>" class="sd-link">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><?php echo $s[2]; // phpcs:ignore ?></svg>
                        <?php echo esc_html( $s[0] ); ?>
                      </a>
                  <?php endforeach;
              endif;
              ?>
            </div>
            <div style="padding:8px 6px 4px;border-top:1px solid var(--line);margin-top:6px">
              <a href="<?php echo esc_url( get_post_type_archive_link( 'sector' ) ); ?>" style="font-size:12px;font-weight:700;color:var(--blue);display:flex;align-items:center;gap:5px;padding:6px 10px;border-radius:6px">
                جميع القطاعات
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
              </a>
            </div>
          </div>
        </li>

        <li class="ni"><a href="<?php echo esc_url( $_blog_url ); ?>" <?php echo is_home() || is_singular( 'post' ) ? 'class="active"' : ''; ?>>المدونة</a></li>
        <li class="ni"><a href="<?php echo esc_url( sh_page_url( 'contact' ) ); ?>" <?php echo is_page( 'contact' ) ? 'class="active"' : ''; ?>>اتصل بنا</a></li>
      </ul>

      <div class="nav-end">
        <a href="<?php echo esc_url( sh_page_url( 'contact' ) ); ?>" class="btn btn-p" style="font-size:13px;padding:9px 18px">استشارة مجانية</a>
        <button class="burger" id="burger" aria-label="قائمة التنقل"><span></span><span></span><span></span></button>
      </div>
    </nav>

    <!-- Mobile Menu -->
    <div class="mob" id="mob">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>">الرئيسية</a>
      <div class="mob-head">تحسين محركات البحث</div>
      <a href="<?php echo esc_url( sh_page_url( 'services/seo' ) ); ?>" class="mob-sub">تحسين محركات البحث</a>
      <a href="<?php echo esc_url( sh_page_url( 'services/seo/backlinks' ) ); ?>" class="mob-sub" style="padding-inline-start:calc(var(--pad) + 16px)">بناء الباك لينك</a>
      <a href="<?php echo esc_url( sh_page_url( 'services/seo/content' ) ); ?>" class="mob-sub" style="padding-inline-start:calc(var(--pad) + 16px)">كتابة المحتوى</a>
      <a href="<?php echo esc_url( sh_page_url( 'services/seo/consulting' ) ); ?>" class="mob-sub" style="padding-inline-start:calc(var(--pad) + 16px)">استشارات الأداء</a>
      <div class="mob-head">إنشاء وتصميم مواقع</div>
      <a href="<?php echo esc_url( sh_page_url( 'services/web-design' ) ); ?>" class="mob-sub">نظرة عامة</a>
      <a href="<?php echo esc_url( sh_page_url( 'services/web-design/wordpress' ) ); ?>" class="mob-sub" style="padding-inline-start:calc(var(--pad) + 16px)">تصميم ووردبريس</a>
      <a href="<?php echo esc_url( sh_page_url( 'services/web-design/webflow' ) ); ?>" class="mob-sub" style="padding-inline-start:calc(var(--pad) + 16px)">تصميم ويب فلو</a>
      <a href="<?php echo esc_url( sh_page_url( 'services/web-design/react-next' ) ); ?>" class="mob-sub" style="padding-inline-start:calc(var(--pad) + 16px)">تصميم رياكت ونكست</a>
      <a href="<?php echo esc_url( sh_page_url( 'services/web-design/custom-dev' ) ); ?>" class="mob-sub" style="padding-inline-start:calc(var(--pad) + 16px)">برمجة مواقع خاصة</a>
      <div class="mob-head">إنشاء وتصميم متاجر</div>
      <a href="<?php echo esc_url( sh_page_url( 'services/stores' ) ); ?>" class="mob-sub">نظرة عامة</a>
      <a href="<?php echo esc_url( sh_page_url( 'services/stores/shopify' ) ); ?>" class="mob-sub" style="padding-inline-start:calc(var(--pad) + 16px)">متاجر شوبيفاي</a>
      <a href="<?php echo esc_url( sh_page_url( 'services/stores/salla' ) ); ?>" class="mob-sub" style="padding-inline-start:calc(var(--pad) + 16px)">متاجر سلة</a>
      <a href="<?php echo esc_url( sh_page_url( 'services/stores/zid' ) ); ?>" class="mob-sub" style="padding-inline-start:calc(var(--pad) + 16px)">متاجر زد</a>
      <a href="<?php echo esc_url( sh_page_url( 'services/stores/woocommerce' ) ); ?>" class="mob-sub" style="padding-inline-start:calc(var(--pad) + 16px)">متاجر ووكومرس</a>
      <a href="<?php echo esc_url( sh_page_url( 'services/products' ) ); ?>" class="mob-sub">رفع المنتجات</a>
      <a href="<?php echo esc_url( sh_page_url( 'results' ) ); ?>">نتائج الأعمال</a>
      <a href="<?php echo esc_url( sh_page_url( 'about' ) ); ?>">عن الشركة</a>
      <a href="<?php echo esc_url( sh_page_url( 'team' ) ); ?>">فريق العمل</a>
      <a href="<?php echo esc_url( get_post_type_archive_link( 'sector' ) ); ?>">القطاعات</a>
      <a href="<?php echo esc_url( $_blog_url ); ?>">المدونة</a>
      <a href="<?php echo esc_url( sh_page_url( 'contact' ) ); ?>" class="mob-cta">احجز استشارة مجانية</a>
    </div>

  </div>
</header>
