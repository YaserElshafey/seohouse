<?php
/**
 * 404 Not Found
 */
get_header();
?>

<div style="background:var(--navy);padding-block:clamp(100px,12vh,140px) 0;position:relative;overflow:hidden">
  <div style="position:absolute;inset:0;background-image:radial-gradient(rgba(255,255,255,.03) 1px,transparent 1px);background-size:36px 36px;pointer-events:none"></div>
  <div style="position:absolute;inset-inline-start:-100px;bottom:-60px;width:400px;height:400px;border-radius:50%;background:radial-gradient(circle,rgba(30,46,245,.2),transparent 65%);filter:blur(55px);pointer-events:none"></div>
  <div class="wrap">
    <div class="err-page">
      <div>
        <div class="err-code">404</div>
        <h1 class="err-title">الصفحة غير موجودة</h1>
        <p class="err-p">الصفحة التي تبحث عنها ربما نُقلت أو حُذفت أو لم تكن موجودة أصلاً.</p>
        <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap">
          <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-p lg">العودة للرئيسية</a>
          <a href="<?php echo esc_url( sh_page_url( 'contact' ) ); ?>" class="btn btn-g lg">تواصل معنا</a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php get_footer(); ?>
