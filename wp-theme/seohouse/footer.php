<?php
$logo_white        = sh_option( 'logo_white' );
$footer_col1       = sh_option( 'footer_col1_heading', 'تحسين محركات البحث' );
$footer_col2       = sh_option( 'footer_col2_heading', 'تصميم المواقع' );
$footer_col3       = sh_option( 'footer_col3_heading', 'إنشاء المتاجر' );
$footer_col4       = sh_option( 'footer_col4_heading', 'الشركة' );
$logo_white_url = ! empty( $logo_white['url'] ) ? $logo_white['url'] : get_template_directory_uri() . '/assets/images/logo-white.png';
$footer_desc = sh_option( 'footer_desc', 'متخصصون في تحسين محركات البحث وبناء المتاجر الإلكترونية. نخدم الأسواق السعودية والخليجية ونعمل بمنطق الأداء.' );
$copyright   = sh_option( 'footer_copyright', '© ' . date( 'Y' ) . ' سيو هاوس. جميع الحقوق محفوظة.' );
$twitter_url = sh_option( 'social_twitter',   '#' );
$linkedin_url = sh_option( 'social_linkedin',  '#' );
$instagram_url = sh_option( 'social_instagram', '#' );
?>

<footer id="footer">
  <div class="wrap">
    <div class="foot-top">
      <div class="foot-brand">
        <img src="<?php echo esc_url( $logo_white_url ); ?>" alt="<?php bloginfo( 'name' ); ?>" height="28">
        <p><?php echo esc_html( $footer_desc ); ?></p>
        <div class="socials">
          <?php if ( $twitter_url && $twitter_url !== '#' ) : ?>
          <a href="<?php echo esc_url( $twitter_url ); ?>" class="soc" aria-label="X / تويتر" target="_blank" rel="noopener">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/></svg>
          </a>
          <?php endif; ?>
          <?php if ( $linkedin_url && $linkedin_url !== '#' ) : ?>
          <a href="<?php echo esc_url( $linkedin_url ); ?>" class="soc" aria-label="لينكد إن" target="_blank" rel="noopener">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>
          </a>
          <?php endif; ?>
          <?php if ( $instagram_url && $instagram_url !== '#' ) : ?>
          <a href="<?php echo esc_url( $instagram_url ); ?>" class="soc" aria-label="إنستغرام" target="_blank" rel="noopener">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
          </a>
          <?php endif; ?>
          <?php if ( ! $twitter_url && ! $linkedin_url && ! $instagram_url ) : ?>
          <a href="#" class="soc" aria-label="X"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/></svg></a>
          <a href="#" class="soc" aria-label="LinkedIn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg></a>
          <a href="#" class="soc" aria-label="Instagram"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg></a>
          <?php endif; ?>
        </div>
      </div>

      <div class="foot-col">
        <h4><?php echo esc_html( $footer_col1 ); ?></h4>
        <?php
        if ( has_nav_menu( 'footer-menu-seo' ) ) {
            wp_nav_menu( [ 'theme_location' => 'footer-menu-seo', 'container' => false, 'menu_class' => 'foot-links', 'depth' => 1, 'fallback_cb' => false ] );
        } else {
            ?>
            <div class="foot-links">
              <a href="<?php echo esc_url( sh_page_url( 'services/seo' ) ); ?>">خدمة السيو الشاملة</a>
              <a href="<?php echo esc_url( sh_page_url( 'services/seo/backlinks' ) ); ?>">بناء الباك لينك</a>
              <a href="<?php echo esc_url( sh_page_url( 'services/seo/content' ) ); ?>">كتابة المحتوى</a>
              <a href="<?php echo esc_url( sh_page_url( 'services/seo/consulting' ) ); ?>">استشارات الأداء</a>
            </div>
        <?php } ?>
      </div>

      <div class="foot-col">
        <h4><?php echo esc_html( $footer_col2 ); ?></h4>
        <?php
        if ( has_nav_menu( 'footer-menu-web' ) ) {
            wp_nav_menu( [ 'theme_location' => 'footer-menu-web', 'container' => false, 'menu_class' => 'foot-links', 'depth' => 1, 'fallback_cb' => false ] );
        } else {
            ?>
            <div class="foot-links">
              <a href="<?php echo esc_url( sh_page_url( 'services/web-design/wordpress' ) ); ?>">تصميم ووردبريس</a>
              <a href="<?php echo esc_url( sh_page_url( 'services/web-design/webflow' ) ); ?>">تصميم ويب فلو</a>
              <a href="<?php echo esc_url( sh_page_url( 'services/web-design/react-next' ) ); ?>">رياكت ونكست</a>
              <a href="<?php echo esc_url( sh_page_url( 'services/web-design/custom-dev' ) ); ?>">برمجة خاصة</a>
            </div>
        <?php } ?>
      </div>

      <div class="foot-col">
        <h4><?php echo esc_html( $footer_col3 ); ?></h4>
        <?php
        if ( has_nav_menu( 'footer-menu-stores' ) ) {
            wp_nav_menu( [ 'theme_location' => 'footer-menu-stores', 'container' => false, 'menu_class' => 'foot-links', 'depth' => 1, 'fallback_cb' => false ] );
        } else {
            ?>
            <div class="foot-links">
              <a href="<?php echo esc_url( sh_page_url( 'services/stores/shopify' ) ); ?>">متاجر شوبيفاي</a>
              <a href="<?php echo esc_url( sh_page_url( 'services/stores/salla' ) ); ?>">متاجر سلة</a>
              <a href="<?php echo esc_url( sh_page_url( 'services/stores/zid' ) ); ?>">متاجر زد</a>
              <a href="<?php echo esc_url( sh_page_url( 'services/stores/woocommerce' ) ); ?>">متاجر ووكومرس</a>
              <a href="<?php echo esc_url( sh_page_url( 'services/products' ) ); ?>">رفع المنتجات</a>
            </div>
        <?php } ?>
      </div>

      <div class="foot-col">
        <h4><?php echo esc_html( $footer_col4 ); ?></h4>
        <?php
        if ( has_nav_menu( 'footer-menu-company' ) ) {
            wp_nav_menu( [ 'theme_location' => 'footer-menu-company', 'container' => false, 'menu_class' => 'foot-links', 'depth' => 1, 'fallback_cb' => false ] );
        } else {
            ?>
            <div class="foot-links">
              <a href="<?php echo esc_url( sh_page_url( 'about' ) ); ?>">عن سيو هاوس</a>
              <a href="<?php echo esc_url( sh_page_url( 'team' ) ); ?>">فريق العمل</a>
              <a href="<?php echo esc_url( get_post_type_archive_link( 'case_study' ) ?: sh_page_url( 'results' ) ); ?>">نتائج الأعمال</a>
              <a href="<?php echo esc_url( get_post_type_archive_link( 'sector' ) ); ?>">القطاعات</a>
              <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/blog/' ) ); ?>">المدونة</a>
              <a href="<?php echo esc_url( sh_page_url( 'contact' ) ); ?>">اتصل بنا</a>
            </div>
        <?php } ?>
      </div>
    </div>

    <div class="foot-bot">
      <p><?php echo esc_html( $copyright ); ?></p>
      <div class="foot-leg">
        <a href="<?php echo esc_url( sh_page_url( 'privacy-policy' ) ); ?>">سياسة الخصوصية</a>
        <a href="<?php echo esc_url( sh_page_url( 'terms' ) ); ?>">الشروط والأحكام</a>
      </div>
    </div>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
