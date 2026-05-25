<?php
/**
 * Template Name: Service — Platform Page
 * Used for: WordPress, Shopify, Salla, Zid, WooCommerce, Webflow, React/Next, Custom Dev
 */
get_header();

$hero_tag     = sh_field( 'service_hero_tag' );
$hero_title   = sh_field( 'service_hero_title' );
$hero_em      = sh_field( 'service_hero_em' );
$hero_desc    = sh_field( 'service_hero_desc' );
$platform_logo = sh_field( 'service_platform_logo' );
$features     = sh_field( 'service_features' );
$steps        = sh_field( 'service_steps' );
$faqs         = sh_field( 'service_faqs' );

$display_title = $hero_title ?: get_the_title();
$display_tag   = $hero_tag  ?: get_the_title();
$display_desc  = $hero_desc ?: get_the_excerpt();

// Parent breadcrumb — detect if stores or web design based on tag
$is_stores = in_array( strtolower( $display_tag ), [ 'شوبيفاي', 'سلة', 'زد', 'ووكومرس', 'shopify', 'salla', 'zid', 'woocommerce' ], true );
$parent_label = $is_stores ? 'إنشاء وتصميم متاجر' : 'إنشاء وتصميم مواقع';
$parent_url   = $is_stores ? sh_page_url( 'services/stores' ) : sh_page_url( 'services/web-design' );
?>

<!-- Hero -->
<section class="svc-hero">
  <div style="position:absolute;inset:0;background-image:radial-gradient(rgba(255,255,255,.04) 1px,transparent 1px);background-size:36px 36px;pointer-events:none"></div>
  <div style="position:absolute;border-radius:50%;filter:blur(60px);pointer-events:none;inset-inline-start:-200px;bottom:-100px;width:600px;height:600px;background:radial-gradient(circle,rgba(30,46,245,.22),transparent 65%)"></div>
  <div class="wrap">
    <div class="svc-hero-inner">
      <div class="breadcrumb" style="justify-content:center;margin-bottom:22px">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">الرئيسية</a>
        <svg class="bc-sep" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
        <a href="<?php echo esc_url( $parent_url ); ?>"><?php echo esc_html( $parent_label ); ?></a>
        <svg class="bc-sep" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
        <span style="color:rgba(255,255,255,.55)"><?php echo esc_html( $display_tag ); ?></span>
      </div>

      <?php if ( ! empty( $platform_logo['url'] ) ) : ?>
        <div style="margin-bottom:24px;display:flex;justify-content:center">
          <img src="<?php echo esc_url( $platform_logo['url'] ); ?>" alt="<?php echo esc_attr( $display_tag ); ?>" style="height:52px;width:auto;object-fit:contain;filter:brightness(0) invert(1)">
        </div>
      <?php else : ?>
        <div style="display:inline-flex;align-items:center;gap:8px;background:rgba(123,144,255,.14);border:1px solid rgba(123,144,255,.25);border-radius:50px;padding:7px 16px;font-size:11.5px;font-weight:700;color:rgba(255,255,255,.78);margin-bottom:22px">
          <?php echo esc_html( $display_tag ); ?>
        </div>
      <?php endif; ?>

      <h1 style="font-size:clamp(32px,5vw,60px);font-weight:900;line-height:1.08;letter-spacing:-.03em;color:#fff;margin-bottom:16px">
        <?php if ( $hero_em ) : ?>
          <?php echo esc_html( $display_title ); ?> <em style="font-style:normal;background:linear-gradient(110deg,#7b90ff,#aab8ff 50%,#7b90ff);background-size:200% auto;-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent;animation:sh 5s linear infinite"><?php echo esc_html( $hero_em ); ?></em>
        <?php else : ?>
          <?php echo wp_kses_post( $display_title ); ?>
        <?php endif; ?>
      </h1>
      <p style="font-size:clamp(15px,1.55vw,17.5px);line-height:1.9;color:rgba(255,255,255,.6);max-width:680px;margin-inline:auto;margin-bottom:30px"><?php echo esc_html( $display_desc ); ?></p>
      <div style="display:flex;gap:11px;justify-content:center;flex-wrap:wrap">
        <a href="<?php echo esc_url( sh_page_url( 'contact' ) ); ?>" class="btn btn-p lg">احجز استشارة مجانية</a>
        <a href="<?php echo esc_url( $parent_url ); ?>" class="btn btn-g lg"><?php echo esc_html( $parent_label ); ?></a>
      </div>
    </div>
  </div>
</section>

<!-- Why / Page content (WP editor) -->
<?php
while ( have_posts() ) : the_post();
    $editor_content = get_the_content();
    if ( trim( wp_strip_all_tags( $editor_content ) ) ) :
?>
<section class="sec sec-white">
  <div class="wrap">
    <div style="max-width:780px;margin-inline:auto">
      <div class="post-content"><?php the_content(); ?></div>
    </div>
  </div>
</section>
<?php endif; endwhile; ?>

<!-- Features grid -->
<?php if ( ! empty( $features ) ) : ?>
<section class="sec sec-surface">
  <div class="wrap">
    <div class="sh c sr"><span class="tag">ما نُقدّمه</span><h2 class="h2">ما الذي تحصل عليه</h2></div>
    <div class="features-grid">
      <?php foreach ( $features as $idx => $feat ) :
          $dc = [ '', 'd1', 'd2', 'd1', 'd2', 'd3' ][ $idx % 6 ];
      ?>
      <div class="feat-card sr <?php echo esc_attr( $dc ); ?>">
        <div class="ico-box" style="margin-bottom:14px">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="20" height="20"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <h3><?php echo esc_html( $feat['title'] ?? '' ); ?></h3>
        <p><?php echo esc_html( $feat['desc'] ?? '' ); ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- Process steps -->
<?php if ( ! empty( $steps ) ) : ?>
<section class="sec sec-white">
  <div class="wrap">
    <div class="sh sr"><span class="tag">خطوات العمل</span><h2 class="h2">كيف نبني مشروعك</h2></div>
    <div class="steps-list">
      <?php foreach ( $steps as $idx => $step ) : ?>
      <div class="step-item sr <?php echo ( $idx % 3 === 1 ) ? 'd1' : ( ( $idx % 3 === 2 ) ? 'd2' : '' ); ?>">
        <div class="step-num"><?php echo str_pad( $idx + 1, 2, '0', STR_PAD_LEFT ); ?></div>
        <div class="step-body">
          <h3><?php echo esc_html( $step['title'] ?? '' ); ?></h3>
          <p><?php echo esc_html( $step['desc'] ?? '' ); ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- FAQs -->
<?php if ( ! empty( $faqs ) ) : ?>
<section class="sec sec-surface">
  <div class="wrap">
    <div style="display:grid;grid-template-columns:5fr 4fr;gap:48px;align-items:start">
      <div>
        <div class="sh sr"><span class="tag">أسئلة شائعة</span><h2 class="h2">أسئلة حول <?php echo esc_html( $display_tag ); ?></h2></div>
        <div class="faq-list sr d1">
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
      <div class="sr d2" style="position:sticky;top:86px">
        <div style="background:var(--navy-2);border-radius:var(--r4);padding:28px;position:relative;overflow:hidden">
          <div style="position:absolute;inset-inline-end:-30px;top:-30px;width:160px;height:160px;border-radius:50%;background:radial-gradient(circle,rgba(30,46,245,.25),transparent 70%)"></div>
          <div style="position:relative;z-index:1">
            <div style="font-size:13px;font-weight:800;color:#fff;margin-bottom:8px">هل هذا هو اختيارك؟</div>
            <p style="font-size:13px;color:rgba(255,255,255,.5);line-height:1.75;margin-bottom:18px">احجز استشارة مجانية ونحدد معاً أفضل خيار لمشروعك.</p>
            <a href="<?php echo esc_url( sh_page_url( 'contact' ) ); ?>" class="btn btn-p" style="width:100%;justify-content:center">استشارة مجانية</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<?php
get_template_part( 'template-parts/layout/cta-banner', null, [
    'tag'   => 'ابدأ الآن',
    'title' => 'جاهز لبناء مشروعك؟',
] );
?>

<?php get_footer(); ?>
