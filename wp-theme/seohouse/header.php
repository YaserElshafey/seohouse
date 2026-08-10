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
$is_seo      = in_array( $current_template, [ 'templates/template-service-seo.php', 'templates/template-service-seo-sub.php', 'templates/template-service-seo-country.php' ], true );
$is_web      = $current_template === 'templates/template-service-web.php';
$is_stores   = $current_template === 'templates/template-service-stores.php';
$is_products = $current_template === 'templates/template-service-products.php';
$is_platform = $current_template === 'templates/template-service-platform.php';
// Determine current service parent for sub-pages (platform pages)
$parent_slug = '';
if ( $is_platform ) {
    $parent = wp_get_post_parent_id( get_the_ID() );
    if ( $parent ) {
        $parent_slug = get_post_field( 'post_name', $parent );
    }
}
$is_web_platform   = $is_platform && in_array( $parent_slug, [ 'web-design', 'website-design' ], true );
$is_store_platform = $is_platform && in_array( $parent_slug, [ 'stores', 'ecommerce-stores' ], true );

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
          <div class="mega mega-svc">
            <div class="mega-list">
              <a href="<?php echo esc_url( sh_page_url( 'services/seo' ) ); ?>" class="mi<?php echo ( $is_seo || ( $is_platform && ! $is_web_platform && ! $is_store_platform ) ) ? ' act' : ''; ?>">
                <div class="mi-l">
                  <div class="mi-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg></div>
                  <span>تحسين محركات البحث</span>
                </div>
              </a>
              <a href="<?php echo esc_url( sh_page_url( 'services/web-design' ) ); ?>" class="mi<?php echo ( $is_web || ( $is_platform && $is_web_platform ) ) ? ' act' : ''; ?>">
                <div class="mi-l">
                  <div class="mi-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8m-4-4v4"/></svg></div>
                  <span>إنشاء وتصميم مواقع</span>
                </div>
              </a>
              <a href="<?php echo esc_url( sh_page_url( 'services/stores' ) ); ?>" class="mi<?php echo ( $is_stores || ( $is_platform && $is_store_platform ) ) ? ' act' : ''; ?>">
                <div class="mi-l">
                  <div class="mi-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg></div>
                  <span>إنشاء وتصميم متاجر</span>
                </div>
              </a>
              <a href="<?php echo esc_url( sh_page_url( 'services/products' ) ); ?>" class="mi<?php echo $is_products ? ' act' : ''; ?>">
                <div class="mi-l">
                  <div class="mi-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg></div>
                  <span>رفع المنتجات للمتاجر</span>
                </div>
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
        <li class="ni"><a href="<?php echo esc_url( sh_page_url( 'pricing' ) ); ?>" <?php echo is_page( 'pricing' ) ? 'class="active"' : ''; ?>>أسعارنا</a></li>
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
      <div style="border-block-start:1px solid var(--line);display:flex;flex-direction:column">
        <a href="<?php echo esc_url( sh_page_url( 'services/seo' ) ); ?>">تحسين محركات البحث</a>
        <a href="<?php echo esc_url( sh_page_url( 'services/web-design' ) ); ?>">إنشاء وتصميم مواقع</a>
        <a href="<?php echo esc_url( sh_page_url( 'services/stores' ) ); ?>">إنشاء وتصميم متاجر</a>
        <a href="<?php echo esc_url( sh_page_url( 'services/products' ) ); ?>">رفع المنتجات للمتاجر</a>
      </div>
      <a href="<?php echo esc_url( sh_page_url( 'results' ) ); ?>">نتائج الأعمال</a>
      <a href="<?php echo esc_url( sh_page_url( 'about' ) ); ?>">عن الشركة</a>
      <a href="<?php echo esc_url( sh_page_url( 'team' ) ); ?>">فريق العمل</a>
      <a href="<?php echo esc_url( get_post_type_archive_link( 'sector' ) ); ?>">القطاعات</a>
      <a href="<?php echo esc_url( $_blog_url ); ?>">المدونة</a>
      <a href="<?php echo esc_url( sh_page_url( 'pricing' ) ); ?>">أسعارنا</a>
      <a href="<?php echo esc_url( sh_page_url( 'contact' ) ); ?>" class="mob-cta">احجز استشارة مجانية</a>
    </div>

  </div>
</header>
