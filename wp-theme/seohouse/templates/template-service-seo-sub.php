<?php
/**
 * Template Name: Service — SEO Sub Page
 * Used for: backlinks, content writing, consulting, seo-stores
 */
get_header();

$tag       = sh_field( 'sub_hero_tag' );
$title     = sh_field( 'sub_hero_title' );
$hero_em   = sh_field( 'sub_hero_em' );
$desc      = sh_field( 'sub_hero_desc' );
$intro     = sh_field( 'sub_intro' );
$points    = sh_field( 'sub_points' );
$faqs      = sh_field( 'sub_faqs' );

$display_tag   = $tag   ?: get_the_title();
$display_title = $title ?: get_the_title();
$display_desc  = $desc  ?: get_the_excerpt();
?>

<?php
get_template_part( 'template-parts/layout/page-hero', null, [
    'tag'         => $display_tag,
    'title'       => $hero_em ? $display_title . ' <em>' . esc_html( $hero_em ) . '</em>' : $display_title,
    'description' => $display_desc,
    'breadcrumb'  => [
        'خدمات السيو'     => sh_page_url( 'services/seo' ),
        $display_tag      => '',
    ],
    'buttons' => [
        [ 'text' => 'احجز استشارة مجانية', 'url' => sh_page_url( 'contact' ), 'class' => 'btn-p lg' ],
    ],
] );
?>

<?php if ( $intro ) : ?>
<section class="sec sec-white">
  <div class="wrap">
    <div style="max-width:760px">
      <p class="bod" style="font-size:16px;line-height:2"><?php echo wp_kses_post( $intro ); ?></p>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- Points / services -->
<?php if ( ! empty( $points ) ) : ?>
<section class="sec sec-surface">
  <div class="wrap">
    <div class="sh c sr"><span class="tag">ما نُقدّمه</span><h2 class="h2">ماذا يشمل <?php echo esc_html( $display_tag ); ?></h2></div>
    <div class="features-grid">
      <?php foreach ( $points as $idx => $pt ) :
          $dc = [ '', 'd1', 'd2', 'd1', 'd2', 'd3' ][ $idx % 6 ];
      ?>
      <div class="feat-card sr <?php echo esc_attr( $dc ); ?>">
        <div class="ico-box" style="margin-bottom:14px">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="20" height="20"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <h3><?php echo esc_html( $pt['title'] ?? '' ); ?></h3>
        <p><?php echo esc_html( $pt['desc'] ?? '' ); ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- WP editor content -->
<?php
while ( have_posts() ) : the_post();
    $ec = get_the_content();
    if ( trim( wp_strip_all_tags( $ec ) ) ) :
?>
<section class="sec sec-white">
  <div class="wrap">
    <div style="max-width:780px">
      <div class="post-content"><?php the_content(); ?></div>
    </div>
  </div>
</section>
<?php endif; endwhile; ?>

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
            <div style="font-size:13px;font-weight:800;color:#fff;margin-bottom:8px">هل هذه الخدمة تناسبك؟</div>
            <p style="font-size:13px;color:rgba(255,255,255,.5);line-height:1.75;margin-bottom:18px">احجز 30 دقيقة مجانية وسنحدد أفضل نهج لموقعك.</p>
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
    'title' => 'جاهز للبدء؟',
] );
?>

<?php get_footer(); ?>
