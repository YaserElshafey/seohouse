<?php
/**
 * Single case study — one fixed template for every case (design "Case Study Template"):
 * summary & data → challenge → solution steps → results → report screenshots → contact.
 * Empty fields are not rendered; nothing is invented when data is missing.
 *
 * @package SEOHouse
 */

defined( 'ABSPATH' ) || exit;

get_header();
while ( have_posts() ) :
	the_post();
	$id = get_the_ID();
	$f  = function_exists( 'get_fields' ) ? (array) get_fields( $id ) : array();
	$g  = static fn( $k ) => isset( $f[ $k ] ) && '' !== $f[ $k ] && null !== $f[ $k ] ? $f[ $k ] : '';

	$show_client = $g( 'client' ) && ! empty( $f['client_public'] );
	$meta        = array_filter(
		array(
			__( 'العميل', 'seohouse' )       => $show_client ? $g( 'client' ) : '',
			__( 'القطاع', 'seohouse' )       => $g( 'sector' ),
			__( 'السوق', 'seohouse' )        => $g( 'market' ),
			__( 'مدة التعاون', 'seohouse' )  => $g( 'duration' ),
			__( 'مصدر البيانات', 'seohouse' ) => $g( 'source' ),
			__( 'فترة القياس', 'seohouse' )  => $g( 'period' ),
		)
	);
	$steps   = array_values( array_filter( (array) ( $f['steps'] ?? array() ), static fn( $s ) => ! empty( $s['title'] ) ) );
	$metrics = array_values( array_filter( (array) ( $f['metrics'] ?? array() ), static fn( $m ) => ! empty( $m['label'] ) && ( '' !== ( $m['after'] ?? '' ) || '' !== ( $m['change'] ?? '' ) ) ) );
	// only screenshots confirmed for this case are shown (unverified ones stay in the admin for review)
	$gallery = array_values( array_filter( (array) ( $f['gallery'] ?? array() ), static fn( $x ) => ! empty( $x['image'] ) && ! empty( $x['verified'] ) ) );
	$cta     = sh_header_cta();
	$muted   = 'var(--sh-ink-soft)';
	?>
<main id="main" class="sh-main">
	<section data-screen-label="Summary" style="position: relative; overflow: hidden; background: var(--sh-paper-2); color: var(--sh-ink); border-bottom: 1px solid rgba(var(--sh-ink-rgb), 0.07);">
		<div aria-hidden="true" data-hero-grid style="position: absolute; inset: 0px; opacity: 0.5; background-image: linear-gradient(rgba(var(--sh-blue-rgb), 0.08) 1px, transparent 1px), linear-gradient(90deg, rgba(var(--sh-blue-rgb), 0.08) 1px, transparent 1px); background-size: 64px 64px; mask-image: radial-gradient(80% 90% at 50% 0%, rgb(0, 0, 0), transparent 75%); pointer-events: none;"></div>
		<div style="position: relative; max-width: 760px; margin: 0px auto; padding-inline: 20px; padding-top: 20px; padding-bottom: clamp(30px, 3.6vw, 44px);">
			<div class="sh-crumbs-light"><?php sh_breadcrumbs(); ?></div>
			<?php if ( 'publish' !== get_post_status() ) : ?>
			<div role="note" style="margin-top: 16px; display: flex; gap: 10px; background: rgb(255, 251, 234); border: 1px dashed rgb(217, 179, 0); border-radius: 12px; padding: 10px 14px; font-size: 14px; line-height: 1.7; color: rgb(92, 74, 0);"><span aria-hidden="true" style="font-weight: 800;">●</span><span><?php esc_html_e( 'مسودة للمراجعة — لا تظهر للزوار ولا تُفهرس.', 'seohouse' ); ?></span></div>
			<?php endif; ?>
			<div style="margin-top: 22px; font-size: 13.5px; font-weight: 600; color: var(--sh-blue);"><?php esc_html_e( 'دراسة حالة', 'seohouse' ); ?></div>
			<h1 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(26px, 2.8vw, 36px); line-height: 1.45; margin: 8px 0px 0px; text-wrap: pretty;"><?php the_title(); ?></h1>
			<?php if ( $g( 'summary' ) ) : ?>
			<p style="font-size: 17px; line-height: 1.9; color: <?php echo esc_attr( $muted ); ?>; margin: 12px 0px 0px; text-wrap: pretty;"><?php echo esc_html( $g( 'summary' ) ); ?></p>
			<?php endif; ?>
			<?php if ( $g( 'result' ) || $meta ) : ?>
			<div data-cs-top style="margin-top: 22px; display: grid; gap: 16px; align-items: stretch;">
				<?php if ( $g( 'result' ) ) : ?>
				<div style="border-radius: 14px; background: var(--sh-blue); color: rgb(255, 255, 255); padding: 14px 18px; display: flex; flex-direction: column; justify-content: center; min-width: 170px;">
					<span style="font-family: Alexandria, sans-serif; font-weight: 800; font-size: 28px; line-height: 1.15;"><bdi><?php echo esc_html( $g( 'result' ) ); ?></bdi></span>
					<?php if ( $g( 'result_label' ) ) : ?>
					<span style="font-size: 13.5px; font-weight: 600; color: rgb(221, 230, 255); margin-top: 4px;"><?php echo esc_html( $g( 'result_label' ) ); ?></span>
					<?php endif; ?>
				</div>
				<?php endif; ?>
				<?php if ( $meta ) : ?>
				<dl data-cs-meta style="margin: 0px; display: grid; gap: 10px 18px; align-content: center; background: rgb(255, 255, 255); border-radius: 14px; padding: 14px 18px; box-shadow: rgba(var(--sh-ink-rgb), 0.06) 0px 0px 0px 1px;">
					<?php foreach ( $meta as $k => $v ) : ?>
					<div style="min-width: 0px;"><dt style="font-size: 12.5px; color: var(--sh-slate);"><?php echo esc_html( $k ); ?></dt><dd style="margin: 2px 0px 0px; font-size: 14.5px; font-weight: 600; color: var(--sh-ink);">
						<?php
						if ( __( 'العميل', 'seohouse' ) === $k && $g( 'client_url' ) ) {
							printf( '<a href="%s" rel="noopener" target="_blank" style="color: inherit; border-bottom: 1px solid rgba(var(--sh-blue-rgb), .4);">%s</a>', esc_url( $g( 'client_url' ) ), esc_html( $v ) );
						} else {
							echo esc_html( $v );
						}
						?>
					</dd></div>
					<?php endforeach; ?>
				</dl>
				<?php endif; ?>
			</div>
			<?php endif; ?>
		</div>
	</section>

	<?php if ( $g( 'challenge_title' ) || $g( 'challenge_text' ) ) : ?>
	<section data-screen-label="Challenge" style="background: rgb(255, 255, 255); color: var(--sh-ink);">
		<div style="max-width: 760px; margin: 0px auto; padding-inline: 20px; padding-top: clamp(32px, 4vw, 52px); padding-bottom: clamp(28px, 3.4vw, 44px);">
			<div style="font-size: 13.5px; font-weight: 600; color: var(--sh-blue);"><?php esc_html_e( 'التحدي', 'seohouse' ); ?></div>
			<?php if ( $g( 'challenge_title' ) ) : ?>
			<h2 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(21px, 2vw, 26px); line-height: 1.5; margin: 8px 0px 0px; text-wrap: pretty;"><?php echo esc_html( $g( 'challenge_title' ) ); ?></h2>
			<?php endif; ?>
			<?php if ( $g( 'challenge_text' ) ) : ?>
			<div class="sh-cs-text" style="font-size: 17px; line-height: 2; margin: 14px 0px 0px; text-wrap: pretty; color: <?php echo esc_attr( $muted ); ?>;"><?php echo wp_kses_post( $g( 'challenge_text' ) ); ?></div>
			<?php endif; ?>
		</div>
	</section>
	<?php endif; ?>

	<?php if ( $g( 'solution_title' ) || $g( 'solution_intro' ) || $steps ) : ?>
	<section data-screen-label="Solution" style="background: rgb(255, 255, 255); color: var(--sh-ink); border-top: 1px solid rgba(var(--sh-ink-rgb), 0.07);">
		<div style="max-width: 760px; margin: 0px auto; padding-inline: 20px; padding-top: clamp(28px, 3.4vw, 44px); padding-bottom: clamp(32px, 4vw, 52px);">
			<div style="font-size: 13.5px; font-weight: 600; color: var(--sh-blue);"><?php esc_html_e( 'خطوات الحل', 'seohouse' ); ?></div>
			<?php if ( $g( 'solution_title' ) ) : ?>
			<h2 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(21px, 2vw, 26px); line-height: 1.5; margin: 8px 0px 0px; text-wrap: pretty;"><?php echo esc_html( $g( 'solution_title' ) ); ?></h2>
			<?php endif; ?>
			<?php if ( $g( 'solution_intro' ) ) : ?>
			<p style="font-size: 17px; line-height: 2; color: <?php echo esc_attr( $muted ); ?>; margin: 12px 0px 0px; text-wrap: pretty;"><?php echo esc_html( $g( 'solution_intro' ) ); ?></p>
			<?php endif; ?>
			<?php if ( $steps ) : ?>
			<ol data-cs-steps style="list-style: none; margin: 22px 0px 0px; padding: 0px; position: relative;">
				<?php foreach ( $steps as $i => $s ) : ?>
				<li style="position: relative; display: flex; gap: 16px; align-items: flex-start; padding-bottom: 20px;">
					<span aria-hidden="true" style="position: relative; z-index: 1; flex: 0 0 auto; width: 32px; height: 32px; border-radius: 999px; background: var(--sh-blue-tint); color: var(--sh-blue); box-shadow: var(--sh-blue) 0px 0px 0px 1.5px inset; font-weight: 700; font-size: 14px; display: flex; align-items: center; justify-content: center;"><?php echo (int) $i + 1; ?></span>
					<span style="min-width: 0px; padding-top: 3px;">
						<span style="display: block; font-family: Alexandria, sans-serif; font-weight: 700; font-size: 17.5px; line-height: 1.55; color: var(--sh-ink);"><?php echo esc_html( $s['title'] ); ?></span>
						<?php if ( ! empty( $s['text'] ) ) : ?>
						<span style="display: block; font-size: 16px; line-height: 1.9; color: <?php echo esc_attr( $muted ); ?>; margin-top: 4px; text-wrap: pretty;"><?php echo esc_html( $s['text'] ); ?></span>
						<?php endif; ?>
					</span>
				</li>
				<?php endforeach; ?>
			</ol>
			<?php endif; ?>
		</div>
	</section>
	<?php endif; ?>

	<?php if ( $g( 'results_text' ) || $metrics ) : ?>
	<section data-screen-label="Results" style="position: relative; overflow: hidden; background: linear-gradient(160deg, rgb(26, 59, 214) 0%, rgb(19, 42, 158) 55%, rgb(11, 22, 80) 100%); color: rgb(255, 255, 255);">
		<div aria-hidden="true" style="position: absolute; inset: 0px; opacity: 0.12; background-image: radial-gradient(rgb(255, 255, 255) 1px, transparent 1px); background-size: 22px 22px; pointer-events: none;"></div>
		<div style="position: relative; max-width: 760px; margin: 0px auto; padding-inline: 20px; padding-top: clamp(32px, 4vw, 52px); padding-bottom: clamp(32px, 4vw, 52px);">
			<div style="font-size: 13.5px; font-weight: 600; color: var(--sh-lime);"><?php esc_html_e( 'النتائج', 'seohouse' ); ?></div>
			<h2 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(21px, 2vw, 26px); line-height: 1.5; margin: 8px 0px 0px;"><?php esc_html_e( 'ما الذي تغيّر؟', 'seohouse' ); ?></h2>
			<?php if ( $g( 'results_text' ) ) : ?>
			<div class="sh-cs-text" style="font-size: 17px; line-height: 2; color: rgb(227, 233, 255); margin: 12px 0px 0px; text-wrap: pretty;"><?php echo wp_kses_post( $g( 'results_text' ) ); ?></div>
			<?php endif; ?>
			<?php if ( $metrics ) : ?>
			<div data-cs-metrics style="margin-top: 22px; display: grid; gap: 12px;">
				<?php foreach ( $metrics as $m ) : ?>
				<div style="border-radius: 14px; background: rgba(255, 255, 255, 0.1); box-shadow: rgba(255, 255, 255, 0.18) 0px 0px 0px 1px inset; padding: 16px 18px;">
					<div style="font-size: 14px; font-weight: 600; color: rgb(221, 230, 255);"><?php echo esc_html( $m['label'] ); ?></div>
					<div style="margin-top: 8px; display: flex; flex-wrap: wrap; align-items: baseline; gap: 4px 12px;">
						<?php if ( '' !== ( $m['before'] ?? '' ) ) : ?>
						<span style="display: inline; font-size: 15px; color: rgb(183, 197, 245);"><bdi><?php echo esc_html( $m['before'] . ( $m['unit'] ? ' ' . $m['unit'] : '' ) ); ?></bdi></span>
						<span aria-hidden="true" style="display: inline; color: var(--sh-lime);">←</span>
						<?php endif; ?>
						<?php if ( '' !== ( $m['after'] ?? '' ) ) : ?>
						<span style="font-family: Alexandria, sans-serif; font-weight: 800; font-size: clamp(24px, 2.4vw, 30px); color: rgb(255, 255, 255);"><bdi><?php echo esc_html( $m['after'] . ( $m['unit'] ? ' ' . $m['unit'] : '' ) ); ?></bdi></span>
						<?php endif; ?>
						<?php if ( '' !== ( $m['change'] ?? '' ) ) : ?>
						<span style="<?php echo '' === ( $m['after'] ?? '' ) ? 'font-family: Alexandria, sans-serif; font-weight: 800; font-size: clamp(24px, 2.4vw, 30px);' : 'font-size: 14px; font-weight: 700;'; ?> color: var(--sh-lime);"><bdi><?php echo esc_html( $m['change'] ); ?></bdi></span>
						<?php endif; ?>
					</div>
					<?php if ( ! empty( $m['source'] ) ) : ?>
					<div style="margin-top: 6px; font-size: 13px; color: rgb(183, 197, 245);"><?php echo esc_html( __( 'المصدر:', 'seohouse' ) . ' ' . $m['source'] ); ?></div>
					<?php endif; ?>
				</div>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>
			<?php
			$periods = array_filter(
				array(
					__( 'قبل:', 'seohouse' )    => $g( 'period_before' ),
					__( 'بعد:', 'seohouse' )    => $g( 'period_after' ),
					__( 'المصدر:', 'seohouse' ) => $g( 'source' ),
				)
			);
			if ( $periods && ( $g( 'period_before' ) || $g( 'period_after' ) ) ) :
				?>
			<dl style="margin: 16px 0px 0px; display: flex; flex-wrap: wrap; gap: 6px 24px; font-size: 14px; color: rgb(221, 230, 255);">
				<?php foreach ( $periods as $k => $v ) : ?>
				<div style="display: flex; gap: 6px;"><dt style="color: rgb(183, 197, 245);"><?php echo esc_html( $k ); ?></dt><dd style="margin: 0px; font-weight: 600;"><?php echo esc_html( $v ); ?></dd></div>
				<?php endforeach; ?>
			</dl>
			<?php endif; ?>
		</div>
	</section>
	<?php endif; ?>

	<?php if ( $gallery ) : ?>
	<section data-screen-label="Gallery" style="background: var(--sh-paper-2); color: var(--sh-ink);">
		<div style="max-width: 1000px; margin: 0px auto; padding: clamp(32px, 4vw, 52px) 20px;">
			<div style="max-width: 760px; margin: 0px auto; padding-inline: 0px;">
				<div style="font-size: 13.5px; font-weight: 600; color: var(--sh-blue);"><?php esc_html_e( 'نظرة على النتائج', 'seohouse' ); ?></div>
				<h2 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(21px, 2vw, 26px); line-height: 1.5; margin: 8px 0px 0px;"><?php esc_html_e( 'لقطات التقرير الأصلية', 'seohouse' ); ?></h2>
			</div>
			<div data-cs-gallery data-count="<?php echo (int) count( $gallery ); ?>" style="margin-top: 20px; display: grid; gap: 16px;">
				<?php
				foreach ( $gallery as $x ) :
					$alt  = $x['alt'] ? $x['alt'] : (string) get_post_meta( (int) $x['image'], '_wp_attachment_image_alt', true );
					$full = wp_get_attachment_image_url( (int) $x['image'], 'full' );
					$cap  = trim( ( $x['caption'] ?? '' ) . ( ! empty( $x['source'] ) ? ' — ' . __( 'المصدر:', 'seohouse' ) . ' ' . $x['source'] : '' ) );
					?>
				<figure style="margin: 0px; border-radius: 14px; background: rgb(255, 255, 255); padding: 10px; box-shadow: rgba(var(--sh-ink-rgb), 0.5) 0px 14px 34px -28px, rgba(var(--sh-ink-rgb), 0.06) 0px 0px 0px 1px;">
					<a href="<?php echo esc_url( $full ); ?>" data-sh-lightbox data-alt="<?php echo esc_attr( $alt ); ?>" aria-label="<?php echo esc_attr( __( 'فتح الصورة بالحجم الكامل:', 'seohouse' ) . ' ' . $alt ); ?>" style="display: block; width: 100%; padding: 0px; border: 0px; background: none; cursor: zoom-in;">
						<?php echo wp_get_attachment_image( (int) $x['image'], 'large', false, array( 'alt' => $alt, 'loading' => 'lazy', 'style' => 'display: block; width: 100%; height: auto; border-radius: 8px;' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</a>
					<?php if ( $cap ) : ?>
					<figcaption style="padding: 10px 4px 2px; font-size: 14px; line-height: 1.7; color: <?php echo esc_attr( $muted ); ?>;"><?php echo esc_html( $cap ); ?></figcaption>
					<?php endif; ?>
				</figure>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<section data-screen-label="Next" style="background: var(--sh-ink); border-bottom: 1px solid rgba(255, 255, 255, 0.08);">
		<div style="max-width: 1040px; margin: 0px auto; padding: clamp(28px, 3.2vw, 40px) 20px; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 16px 28px;">
			<div style="min-width: 0px;">
				<?php if ( $g( 'service_page' ) ) : ?>
				<div style="font-size: 13px; color: var(--sh-crumb);"><?php echo esc_html( $g( 'cta_title' ) ? $g( 'cta_title' ) : __( 'الخدمة المرتبطة', 'seohouse' ) ); ?></div>
				<a class="sh-hv-link" href="<?php echo esc_url( sh_link( $g( 'service_page' ) ) ); ?>" style="display: inline-flex; align-items: center; gap: 8px; margin-top: 6px; font-family: Alexandria, sans-serif; font-weight: 700; font-size: 19px; color: var(--sh-text);"><?php echo esc_html( $g( 'service_label' ) ? $g( 'service_label' ) : get_the_title( url_to_postid( sh_link( $g( 'service_page' ) ) ) ) ); ?> <span aria-hidden="true" style="color: var(--sh-sky);">←</span></a>
				<?php endif; ?>
				<?php if ( $g( 'cta_text' ) ) : ?>
				<p style="margin: 8px 0 0; color: var(--sh-muted); font-size: 15px;"><?php echo esc_html( $g( 'cta_text' ) ); ?></p>
				<?php endif; ?>
			</div>
			<div style="display: flex; flex-wrap: wrap; gap: 12px;">
				<?php $results = get_page_by_path( 'results' ); ?>
				<?php if ( $results ) : ?>
				<a href="<?php echo esc_url( get_permalink( $results ) ); ?>" class="sh-hv-next" style="min-height: 48px; display: inline-flex; align-items: center; padding: 0px 18px; border-radius: 12px; background: rgba(var(--sh-blue-rgb), 0.18); box-shadow: rgba(var(--sh-sky-rgb), 0.45) 0px 0px 0px 1px inset; color: var(--sh-text); font-weight: 600;"><?php esc_html_e( 'كل النتائج', 'seohouse' ); ?></a>
				<?php endif; ?>
				<a href="<?php echo esc_url( $cta['url'] ); ?>" class="sh-hv-cta" style="min-height: 48px; display: inline-flex; align-items: center; padding: 0px 22px; border-radius: 12px; background: var(--sh-lime); color: var(--sh-ink); font-weight: 700;"><?php esc_html_e( 'احجز مكالمة استشارية', 'seohouse' ); ?></a>
			</div>
		</div>
	</section>
</main>
	<?php
endwhile;
get_footer();
