<?php
/**
 * Template Name: Team Page
 */
get_header();

// ── Hero fields ────────────────────────────────────────────
$team_hero_tag  = sh_field( 'team_hero_tag',   null, 'الفريق' );
$team_hero_em   = sh_field( 'team_hero_em',    null, 'متخصصون' );
$team_hero_raw  = sh_field( 'team_hero_title', null, "خبراء متخصصون\nلا موظفون عموميون" );
$team_hero_desc = sh_field( 'team_hero_desc',  null, 'كل عضو في فريقنا متخصص في مجال محدد من السيو — لأن التخصص هو الفرق بين النتائج والوعود.' );
$team_hero_title = $team_hero_em
    ? str_replace( $team_hero_em, '<em>' . esc_html( $team_hero_em ) . '</em>', esc_html( $team_hero_raw ) )
    : esc_html( $team_hero_raw );
$team_hero_title = nl2br( $team_hero_title );

// ── Join card fields ───────────────────────────────────────
$team_join_title = sh_field( 'team_join_title', null, 'نبحث عن موهبة' );
$team_join_desc  = sh_field( 'team_join_desc',  null, 'هل أنت متخصص في السيو وتريد العمل في بيئة محترفة؟' );
$team_join_btn   = sh_field( 'team_join_btn',   null, 'تواصل معنا' );

$team = new WP_Query( [
    'post_type'      => 'team_member',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
    'meta_query'     => [
        'relation' => 'OR',
        [ 'key' => 'is_visible', 'value' => '1', 'compare' => '=' ],
        [ 'key' => 'is_visible', 'compare' => 'NOT EXISTS' ],
    ],
] );
?>

<?php
get_template_part( 'template-parts/layout/page-hero', null, [
    'tag'         => $team_hero_tag,
    'title'       => $team_hero_title,
    'description' => $team_hero_desc,
    'breadcrumb'  => [ 'فريق العمل' => '' ],
] );
?>

<section class="sec sec-surface">
  <div class="wrap">
    <div class="team-grid">
      <?php if ( $team->have_posts() ) :
          $delay_classes = [ '', 'd1', 'd2', 'd1', 'd2', 'd3' ];
          $i = 0;
          while ( $team->have_posts() ) : $team->the_post();
              $dc = $delay_classes[ $i % count( $delay_classes ) ];
              get_template_part( 'template-parts/cards/team-card', null, [ 'delay_class' => $dc ] );
              $i++;
          endwhile;
          wp_reset_postdata();
      else :
          // Fallback placeholder cards
          $fallbacks = [
              [ 'initials' => 'م.ع', 'badge' => 'المؤسس',  'name' => 'اسم المؤسس',                 'role' => 'المؤسس ومدير الاستراتيجية',         'bio' => 'خبرة في تحسين محركات البحث للأسواق العربية والسعودية.',   'skills' => 'سيو تقني,استراتيجية,تحليل البيانات', 'bg' => '' ],
              [ 'initials' => 'س.م', 'badge' => 'محتوى',   'name' => 'اسم مسؤول المحتوى',           'role' => 'مدير المحتوى والكتابة',              'bio' => 'متخصص في كتابة المحتوى العربي المحسّن لمحركات البحث.',    'skills' => 'كتابة المحتوى,SEO Copywriting',       'bg' => 'background:linear-gradient(140deg,#0b1240,rgba(16,185,129,.2))' ],
              [ 'initials' => 'ت.ق', 'badge' => 'تقني',    'name' => 'اسم متخصص السيو التقني',      'role' => 'متخصص السيو التقني',                'bio' => 'خبرة في Core Web Vitals وهيكل البيانات المنظمة وزحف جوجل.', 'skills' => 'Technical SEO,Core Web Vitals',       'bg' => 'background:linear-gradient(140deg,#0b1240,rgba(250,204,21,.15))' ],
              [ 'initials' => 'ر.ب', 'badge' => 'روابط',   'name' => 'اسم متخصص الروابط',           'role' => 'متخصص بناء الباك لينك',             'bio' => 'خبرة في بناء الروابط الطبيعية للسوق العربي.',              'skills' => 'Link Building,Outreach',              'bg' => 'background:linear-gradient(140deg,#0b1240,rgba(239,68,68,.15))' ],
              [ 'initials' => 'ن.م', 'badge' => 'متاجر',   'name' => 'اسم متخصص المتاجر الإلكترونية', 'role' => 'متخصص سيو المتاجر الإلكترونية',  'bio' => 'خبرة مباشرة في سلة وزد وشوبيفاي.',                       'skills' => 'سيو سلة,سيو زد,Shopify SEO',         'bg' => 'background:linear-gradient(140deg,#0b1240,rgba(99,102,241,.25))' ],
          ];
          $fb_delays = [ 'sr', 'sr d1', 'sr d2', 'sr d1', 'sr d2' ];
          foreach ( $fallbacks as $idx => $fb ) :
      ?>
          <div class="tm-card <?php echo esc_attr( $fb_delays[ $idx ] ); ?>">
            <div class="tm-avatar" <?php if ( $fb['bg'] ) echo 'style="' . esc_attr( $fb['bg'] ) . '"'; ?>>
              <span class="tm-initials"><?php echo esc_html( $fb['initials'] ); ?></span>
              <span class="tm-role-badge"><?php echo esc_html( $fb['badge'] ); ?></span>
            </div>
            <div class="tm-body">
              <div class="tm-name"><?php echo esc_html( $fb['name'] ); ?></div>
              <div class="tm-role"><?php echo esc_html( $fb['role'] ); ?></div>
              <p class="tm-bio"><?php echo esc_html( $fb['bio'] ); ?></p>
              <div class="tm-tags">
                <?php foreach ( explode( ',', $fb['skills'] ) as $skill ) : ?>
                  <span class="chip"><?php echo esc_html( trim( $skill ) ); ?></span>
                <?php endforeach; ?>
              </div>
              <div class="tm-socials">
                <a href="#" class="tm-soc"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg></a>
              </div>
            </div>
          </div>
      <?php endforeach;
      endif; ?>

      <!-- Join us card -->
      <div class="tm-card tm-join-card sr d3">
        <div class="tm-join-inner">
          <div class="tm-join-ico">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--blue)" stroke-width="1.8"><path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
          </div>
          <h3 class="tm-join-title"><?php echo esc_html( $team_join_title ); ?></h3>
          <p class="tm-join-desc"><?php echo esc_html( $team_join_desc ); ?></p>
          <a href="<?php echo esc_url( sh_page_url( 'contact' ) ); ?>" class="btn btn-p sm"><?php echo esc_html( $team_join_btn ); ?></a>
        </div>
      </div>
    </div>
  </div>
</section>

<?php get_footer(); ?>
