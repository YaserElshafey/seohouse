<?php
/**
 * 404 Not Found
 */
get_header();
?>

<section class="err-section">
  <div class="wrap">
    <div class="err-page">
      <div>
        <div class="err-code">404</div>
        <h1 class="err-title">الصفحة غير موجودة</h1>
        <p class="err-p">الصفحة التي تبحث عنها ربما نُقلت أو حُذفت أو لم تكن موجودة أصلاً.</p>
        <div class="err-btns">
          <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-p lg">العودة للرئيسية</a>
          <a href="<?php echo esc_url( sh_page_url( 'contact' ) ); ?>" class="btn btn-g lg">تواصل معنا</a>
        </div>
      </div>
    </div>
  </div>
</section>

<?php get_footer(); ?>
