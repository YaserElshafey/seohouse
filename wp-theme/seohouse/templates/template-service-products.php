<?php
/**
 * Template Name: Service — Product Upload
 */
get_header();

$features = sh_field( 'service_features' );
$faqs     = sh_field( 'service_faqs' );

$prod_hero_em    = sh_field( 'prod_hero_em',    null, 'باحترافية' );
$prod_hero_raw   = sh_field( 'prod_hero_title', null, "رفع المنتجات للمتاجر\nباحترافية" );
$prod_hero_desc  = sh_field( 'prod_hero_desc',  null, 'ليس مجرّد إدخال بيانات — رفع منظّم واحترافي عبر جميع المنصات مع تحسين لكل منتج يرفع فرصته في الظهور.' );
$prod_hero_title = $prod_hero_em
    ? str_replace( $prod_hero_em, '<em>' . esc_html( $prod_hero_em ) . '</em>', esc_html( $prod_hero_raw ) )
    : esc_html( $prod_hero_raw );
$prod_hero_title = nl2br( $prod_hero_title );
?>

<?php
get_template_part( 'template-parts/layout/page-hero', null, [
    'tag'         => sh_field( 'prod_hero_tag', null, 'رفع المنتجات' ),
    'title'       => $prod_hero_title,
    'description' => $prod_hero_desc,
    'breadcrumb'  => [ 'رفع المنتجات' => '' ],
    'buttons' => [
        [ 'text' => 'احجز استشارة مجانية', 'url' => sh_page_url( 'contact' ), 'class' => 'btn-p lg' ],
    ],
] );
?>

<section class="sec sec-surface">
  <div class="wrap">
    <div class="sh c sr"><span class="tag">ما نُقدّمه</span><h2 class="h2">رفع المنتجات ليس مجرد نسخ ولصق</h2></div>
    <div class="features-grid">
      <?php if ( ! empty( $features ) ) :
          foreach ( $features as $idx => $feat ) :
              $dc = [ '', 'd1', 'd2', 'd1', 'd2', 'd3' ][ $idx % 6 ];
      ?>
          <div class="feat-card sr <?php echo esc_attr( $dc ); ?>">
            <div class="ico-box" style="margin-bottom:14px"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="20" height="20"><polyline points="20 6 9 17 4 12"/></svg></div>
            <h3><?php echo esc_html( $feat['title'] ?? '' ); ?></h3>
            <p><?php echo esc_html( $feat['desc'] ?? '' ); ?></p>
          </div>
      <?php endforeach;
      else :
          $defaults = [
              [ 'title' => 'كتابة أوصاف جاذبة',       'desc' => 'أوصاف منتجات محسّنة لمحركات البحث وجذب المشتري.' ],
              [ 'title' => 'ضغط الصور وتحسينها',       'desc' => 'أحجام صور مثالية تُسرّع المتجر وتُحسّن التجربة.' ],
              [ 'title' => 'تصنيف دقيق للمنتجات',      'desc' => 'هيكل تصنيف يُسهّل التصفح ويُحسّن ترتيب الفئات.' ],
              [ 'title' => 'ربط المتغيرات والخيارات',  'desc' => 'ألوان، أحجام، وحزم — مرتبة ومُعرَّضة بشكل صحيح.' ],
              [ 'title' => 'بيانات هيكلية للمنتجات',   'desc' => 'Schema markup يُحسّن ظهور المنتجات في نتائج البحث.' ],
              [ 'title' => 'تقرير ما بعد الرفع',       'desc' => 'قائمة بكل منتج مُرفَّع مع ملاحظات الجودة.' ],
          ];
          $dcs2 = [ '', 'd1', 'd2', 'd1', 'd2', 'd3' ];
          foreach ( $defaults as $di => $d ) :
      ?>
          <div class="feat-card sr <?php echo esc_attr( $dcs2[ $di ] ); ?>">
            <div class="ico-box" style="margin-bottom:14px"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="20" height="20"><polyline points="20 6 9 17 4 12"/></svg></div>
            <h3><?php echo esc_html( $d['title'] ); ?></h3>
            <p><?php echo esc_html( $d['desc'] ); ?></p>
          </div>
      <?php endforeach;
      endif; ?>
    </div>
  </div>
</section>

<!-- WP editor content -->
<?php
while ( have_posts() ) : the_post();
    $ec = get_the_content();
    if ( trim( wp_strip_all_tags( $ec ) ) ) :
?>
<section class="sec sec-white">
  <div class="wrap"><div class="post-content"><?php the_content(); ?></div></div>
</section>
<?php endif; endwhile; ?>

<!-- FAQs -->
<?php if ( ! empty( $faqs ) ) : ?>
<section class="sec sec-white">
  <div class="wrap">
    <div class="sh sr"><span class="tag">أسئلة شائعة</span><h2 class="h2">أسئلة حول رفع المنتجات</h2></div>
    <div class="faq-list sr d1" style="max-width:680px">
      <?php foreach ( $faqs as $faq ) : ?>
      <div class="faq-item">
        <div class="faq-q">
          <span><?php echo esc_html( $faq['question'] ?? '' ); ?></span>
          <div class="faq-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></div>
        </div>
        <div class="faq-a">
          <div class="faq-a-inner"><?php echo esc_html( $faq['answer'] ?? '' ); ?></div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php
get_template_part( 'template-parts/layout/cta-banner', null, [
    'tag'   => 'ابدأ الآن',
    'title' => sh_field( 'prod_cta_title', null, 'جاهز لرفع منتجاتك؟' ),
] );
?>

<?php get_footer(); ?>
