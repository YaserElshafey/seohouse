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
	// 2.8.8: a client with a logo gets the client box (logo, field, country) instead of the data list
	$logo = $show_client && ! empty( $f['client_logo'] ) ? (int) $f['client_logo'] : 0;
	if ( $logo ) {
		$meta = array_filter(
			array(
				__( 'المجال', 'seohouse' ) => $g( 'sector' ),
				__( 'الدولة', 'seohouse' ) => $g( 'market' ),
			)
		);
	}
	$steps   = array_values( array_filter( (array) ( $f['steps'] ?? array() ), static fn( $s ) => ! empty( $s['title'] ) ) );
	$metrics = array_values( array_filter( (array) ( $f['metrics'] ?? array() ), static fn( $m ) => ! empty( $m['label'] ) && ( '' !== ( $m['after'] ?? '' ) || '' !== ( $m['change'] ?? '' ) ) ) );
	// only screenshots confirmed for this case are shown (unverified ones stay in the admin for review)
	$gallery = array_values( array_filter( (array) ( $f['gallery'] ?? array() ), static fn( $x ) => ! empty( $x['image'] ) && ! empty( $x['verified'] ) ) );
	$cta     = sh_header_cta();
	$muted   = 'var(--sh-text)';
	?>
<main id="main" class="sh-main">
	<section data-screen-label="Summary" style="position: relative; overflow: hidden; background: var(--sh-surface); color: var(--sh-ink); border-bottom: 1px solid var(--sh-line);">
		<div aria-hidden="true" data-hero-grid style="position: absolute; inset: 0px; opacity: 0.5; background-image: linear-gradient(rgba(40, 84, 232, 0.08) 1px, transparent 1px), linear-gradient(90deg, rgba(40, 84, 232, 0.08) 1px, transparent 1px); background-size: 64px 64px; mask-image: radial-gradient(80% 90% at 50% 0%, rgb(0, 0, 0), transparent 75%); pointer-events: none;"></div>
		<div style="position: relative; max-width: 760px; margin: 0px auto; padding-inline: 20px; padding-top: 20px; padding-bottom: clamp(30px, 3.6vw, 44px);">
			<div class="sh-crumbs-light"><?php sh_breadcrumbs(); ?></div>
			<?php if ( 'publish' !== get_post_status() || ! empty( get_post()->sh_preview ) ) : ?>
			<div role="note" style="margin-top: 16px; display: flex; gap: 10px; background: rgb(255, 251, 234); border: 1px dashed rgb(217, 179, 0); border-radius: 12px; padding: 10px 14px; font-size: 14px; line-height: 1.7; color: var(--sh-text);"><span aria-hidden="true" style="font-weight: 800;">●</span><span><?php esc_html_e( 'مسودة للمراجعة — لا تظهر للزوار ولا تُفهرس.', 'seohouse' ); ?></span></div>
			<?php endif; ?>
			<div style="margin-top: 22px; font-size: 13.5px; font-weight: 600; color: var(--sh-link);"><?php esc_html_e( 'دراسة حالة', 'seohouse' ); ?></div>
			<h1 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(26px, 2.8vw, 36px); line-height: 1.45; margin: 8px 0px 0px; text-wrap: pretty;"><?php the_title(); ?></h1>
			<?php if ( $g( 'summary' ) ) : ?>
			<p style="font-size: 17px; line-height: 1.9; color: <?php echo esc_attr( $muted ); ?>; margin: 12px 0px 0px; text-wrap: pretty;"><?php echo esc_html( $g( 'summary' ) ); ?></p>
			<?php endif; ?>
			<?php if ( $g( 'result' ) || $meta || $logo ) : ?>
			<div data-cs-top style="margin-top: 22px; display: grid; gap: 16px; align-items: stretch;">
				<?php if ( $g( 'result' ) ) : ?>
				<div style="border-radius: 14px; background: var(--sh-blue); color: rgb(255, 255, 255); padding: 14px 18px; display: flex; flex-direction: column; justify-content: center; min-width: 170px;">
					<span style="font-family: Alexandria, sans-serif; font-weight: 800; font-size: 28px; line-height: 1.15;"><bdi><?php echo esc_html( $g( 'result' ) ); ?></bdi></span>
					<?php if ( $g( 'result_label' ) ) : ?>
					<span style="font-size: 13.5px; font-weight: 600; color: rgb(255, 255, 255); margin-top: 4px;"><?php echo esc_html( $g( 'result_label' ) ); ?></span>
					<?php endif; ?>
				</div>
				<?php endif; ?>
				<?php if ( $logo ) : ?>
				<div class="sh-cs-client" style="display: flex; align-items: center; gap: 16px 22px; flex-wrap: wrap; background: rgb(255, 255, 255); border-radius: 14px; padding: 14px 18px; box-shadow: 0 0 0 1px var(--sh-line);">
					<span class="sh-case-logo sh-case-logo--lg"><?php echo wp_get_attachment_image( $logo, 'medium', false, array( 'alt' => sprintf( /* translators: %s: client */ __( 'شعار %s', 'seohouse' ), $g( 'client' ) ), 'decoding' => 'async' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<?php if ( $meta ) : ?>
					<dl style="margin: 0px; display: flex; flex-wrap: wrap; gap: 10px 28px;">
						<?php foreach ( $meta as $k => $v ) : ?>
						<div style="min-width: 0px;"><dt style="font-size: 12.5px; color: var(--sh-text);"><?php echo esc_html( $k ); ?></dt><dd style="margin: 2px 0px 0px; font-size: 14.5px; font-weight: 600; color: var(--sh-ink);"><?php echo esc_html( $v ); ?></dd></div>
						<?php endforeach; ?>
					</dl>
					<?php endif; ?>
				</div>
				<?php elseif ( $meta ) : ?>
				<dl data-cs-meta style="margin: 0px; display: grid; gap: 10px 18px; align-content: center; background: rgb(255, 255, 255); border-radius: 14px; padding: 14px 18px; box-shadow: 0 0 0 1px var(--sh-line);">
					<?php foreach ( $meta as $k => $v ) : ?>
					<div style="min-width: 0px;"><dt style="font-size: 12.5px; color: var(--sh-text);"><?php echo esc_html( $k ); ?></dt><dd style="margin: 2px 0px 0px; font-size: 14.5px; font-weight: 600; color: var(--sh-ink);">
						<?php
						if ( __( 'العميل', 'seohouse' ) === $k && $g( 'client_url' ) ) {
							printf( '<a href="%s" rel="noopener" target="_blank" style="color: inherit; border-bottom: 1px solid rgba(40, 84, 232, .4);">%s</a>', esc_url( $g( 'client_url' ) ), esc_html( $v ) );
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
	<section data-screen-label="Challenge" style="background: var(--sh-surface); color: var(--sh-ink);">
		<div style="max-width: 760px; margin: 0px auto; padding-inline: 20px; padding-top: clamp(32px, 4vw, 52px); padding-bottom: clamp(28px, 3.4vw, 44px);">
			<div style="font-size: 13.5px; font-weight: 600; color: var(--sh-link);"><?php esc_html_e( 'التحدي', 'seohouse' ); ?></div>
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
	<section data-screen-label="Solution" style="background: var(--sh-surface); color: var(--sh-ink); border-top: 1px solid var(--sh-line);">
		<div style="max-width: 760px; margin: 0px auto; padding-inline: 20px; padding-top: clamp(28px, 3.4vw, 44px); padding-bottom: clamp(32px, 4vw, 52px);">
			<div style="font-size: 13.5px; font-weight: 600; color: var(--sh-link);"><?php esc_html_e( 'خطوات الحل', 'seohouse' ); ?></div>
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
					<span aria-hidden="true" style="position: relative; z-index: 1; flex: 0 0 auto; width: 32px; height: 32px; border-radius: 999px; background: var(--sh-blue-tint); color: var(--sh-link); box-shadow: var(--sh-blue) 0px 0px 0px 1.5px inset; font-weight: 700; font-size: 14px; display: flex; align-items: center; justify-content: center;"><?php echo (int) $i + 1; ?></span>
					<span style="min-width: 0px; padding-top: 3px;">
						<span style="display: block; font-family: Alexandria, sans-serif; font-weight: 700; font-size: 17.5px; line-height: 1.55; color: rgb(37, 43, 51);"><?php echo esc_html( $s['title'] ); ?></span>
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

	<?php
	// 2.8.8: the main result as three cards (before / after / increase, each with its period), then the
	// extra lines and the source; cases without it keep the metrics list
	$main = '' !== $g( 'main_after' ) || '' !== $g( 'main_change' );
	?>
	<?php if ( $g( 'results_text' ) || $metrics || $main ) : ?>
	<section data-screen-label="Results" data-blue-field style="position: relative; overflow: hidden; background: linear-gradient(rgb(46, 90, 240) 0%, rgb(40, 84, 232) 60%, rgb(36, 76, 214) 100%); color: rgb(255, 255, 255);">
		<div aria-hidden="true" style="position: absolute; inset: 0px; opacity: 0.12; background-image: radial-gradient(rgb(255, 255, 255) 1px, transparent 1px); background-size: 22px 22px; pointer-events: none;"></div>
		<div style="position: relative; max-width: 760px; margin: 0px auto; padding-inline: 20px; padding-top: clamp(32px, 4vw, 52px); padding-bottom: clamp(32px, 4vw, 52px);">
			<div style="font-size: 13.5px; font-weight: 600; color: rgb(255, 255, 255);"><?php esc_html_e( 'النتائج', 'seohouse' ); ?></div>
			<h2 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(21px, 2vw, 26px); line-height: 1.5; margin: 8px 0px 0px;"><?php esc_html_e( 'ما الذي تغيّر؟', 'seohouse' ); ?></h2>
			<?php if ( $g( 'results_text' ) ) : ?>
			<div class="sh-cs-text" style="font-size: 17px; line-height: 2; color: rgb(255, 255, 255); margin: 12px 0px 0px; text-wrap: pretty;"><?php echo wp_kses_post( $g( 'results_text' ) ); ?></div>
			<?php endif; ?>
			<?php if ( $main ) : ?>
			<?php if ( $g( 'main_metric' ) ) : ?>
			<p style="margin: 14px 0px 0px; font-size: 16.5px; font-weight: 600; color: rgb(255, 255, 255);"><?php echo esc_html( $g( 'main_metric' ) ); ?></p>
			<?php endif; ?>
			<div data-cs-main style="margin-top: 16px; display: grid; gap: 12px;">
				<?php
				$cards = array(
					array( __( 'قبل', 'seohouse' ), $g( 'main_before' ), $g( 'main_before_period' ), '' ),
					array( __( 'بعد', 'seohouse' ), $g( 'main_after' ), $g( 'main_after_period' ), '' ),
					array( __( 'الزيادة', 'seohouse' ), $g( 'main_change' ), $g( 'main_change_note' ), 'up' ),
				);
				foreach ( $cards as $card ) :
					if ( '' === (string) $card[1] ) {
						continue;
					}
					?>
				<div class="sh-cs-card<?php echo 'up' === $card[3] ? ' sh-cs-card--up' : ''; ?>">
					<span class="sh-cs-card__label"><?php echo esc_html( $card[0] ); ?></span>
					<span class="sh-cs-card__value"><bdi><?php echo esc_html( $card[1] ); ?></bdi></span>
					<?php if ( '' !== (string) $card[2] ) : ?>
					<span class="sh-cs-card__sub"><?php echo esc_html( $card[2] ); ?></span>
					<?php endif; ?>
				</div>
				<?php endforeach; ?>
				<?php foreach ( array( 'extra_line' => '', 'extra_result' => __( 'نتيجة إضافية', 'seohouse' ) ) as $k => $label ) : ?>
					<?php if ( $g( $k ) ) : ?>
				<div class="sh-cs-card sh-cs-card--wide">
					<?php if ( '' !== $label ) : ?><span class="sh-cs-card__label"><?php echo esc_html( $label ); ?></span><?php endif; ?>
					<span class="sh-cs-card__text"><?php echo sh_bidi_numbers( $g( $k ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper ?></span>
				</div>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
			<?php if ( $g( 'source' ) ) : ?>
			<p style="margin: 16px 0px 0px; font-size: 14px; color: rgb(255, 255, 255);"><?php echo esc_html( __( 'المصدر:', 'seohouse' ) . ' ' . $g( 'source' ) ); ?></p>
			<?php endif; ?>
			<?php elseif ( $metrics ) : ?>
			<div data-cs-metrics style="margin-top: 22px; display: grid; gap: 12px;">
				<?php foreach ( $metrics as $m ) : ?>
				<div style="border-radius: 14px; background: rgba(255, 255, 255, 0.12); box-shadow: inset 0 0 0 1px var(--sh-line); padding: 16px 18px;">
					<div style="font-size: 14px; font-weight: 600; color: rgb(255, 255, 255);"><?php echo esc_html( $m['label'] ); ?></div>
					<div style="margin-top: 8px; display: flex; flex-wrap: wrap; align-items: baseline; gap: 4px 12px;">
						<?php if ( '' !== ( $m['before'] ?? '' ) ) : ?>
						<span style="display: inline; font-size: 15px; color: rgb(255, 255, 255);"><bdi><?php echo esc_html( $m['before'] . ( $m['unit'] ? ' ' . $m['unit'] : '' ) ); ?></bdi></span>
						<span aria-hidden="true" style="display: inline; color: rgb(255, 255, 255);"><?php echo esc_html( sh_arrow() ); ?></span>
						<?php endif; ?>
						<?php if ( '' !== ( $m['after'] ?? '' ) ) : ?>
						<span style="font-family: Alexandria, sans-serif; font-weight: 800; font-size: clamp(24px, 2.4vw, 30px); color: rgb(255, 255, 255);"><bdi><?php echo esc_html( $m['after'] . ( $m['unit'] ? ' ' . $m['unit'] : '' ) ); ?></bdi></span>
						<?php endif; ?>
						<?php if ( '' !== ( $m['change'] ?? '' ) ) : ?>
						<span style="<?php echo '' === ( $m['after'] ?? '' ) ? 'font-family: Alexandria, sans-serif; font-weight: 800; font-size: clamp(24px, 2.4vw, 30px);' : 'font-size: 14px; font-weight: 700;'; ?> color: rgb(255, 255, 255);"><bdi><?php echo esc_html( $m['change'] ); ?></bdi></span>
						<?php endif; ?>
					</div>
					<?php if ( ! empty( $m['source'] ) ) : ?>
					<div style="margin-top: 6px; font-size: 13px; color: rgb(255, 255, 255);"><?php echo esc_html( __( 'المصدر:', 'seohouse' ) . ' ' . $m['source'] ); ?></div>
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
			if ( ! $main && $periods && ( $g( 'period_before' ) || $g( 'period_after' ) ) ) :
				?>
			<dl style="margin: 16px 0px 0px; display: flex; flex-wrap: wrap; gap: 6px 24px; font-size: 14px; color: rgb(255, 255, 255);">
				<?php foreach ( $periods as $k => $v ) : ?>
				<div style="display: flex; gap: 6px;"><dt style="color: rgb(255, 255, 255);"><?php echo esc_html( $k ); ?></dt><dd style="margin: 0px; font-weight: 600;"><?php echo esc_html( $v ); ?></dd></div>
				<?php endforeach; ?>
			</dl>
			<?php endif; ?>
		</div>
	</section>
	<?php endif; ?>

	<?php if ( $gallery ) : ?>
	<section data-screen-label="Gallery" style="background: var(--sh-surface); color: var(--sh-ink);">
		<div style="max-width: 1000px; margin: 0px auto; padding: clamp(32px, 4vw, 52px) 20px;">
			<div style="max-width: 760px; margin: 0px auto; padding-inline: 0px;">
				<div style="font-size: 13.5px; font-weight: 600; color: var(--sh-link);"><?php esc_html_e( 'نظرة على النتائج', 'seohouse' ); ?></div>
				<h2 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(21px, 2vw, 26px); line-height: 1.5; margin: 8px 0px 0px;"><?php esc_html_e( 'لقطات التقرير الأصلية', 'seohouse' ); ?></h2>
			</div>
			<div data-cs-gallery data-count="<?php echo (int) count( $gallery ); ?>" style="margin-top: 20px; display: grid; gap: 16px;">
				<?php
				foreach ( $gallery as $x ) :
					$alt  = $x['alt'] ? $x['alt'] : (string) get_post_meta( (int) $x['image'], '_wp_attachment_image_alt', true );
					$full = wp_get_attachment_image_url( (int) $x['image'], 'full' );
					$cap  = trim( ( $x['caption'] ?? '' ) . ( ! empty( $x['source'] ) ? ' — ' . __( 'المصدر:', 'seohouse' ) . ' ' . $x['source'] : '' ) );
					?>
				<figure style="margin: 0px; border-radius: 14px; background: rgb(255, 255, 255); padding: 10px; box-shadow: 0 14px 34px -28px rgba(6,11,31,.5), 0 0 0 1px var(--sh-line);">
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

	<?php
	$analytics = (int) $g( 'analytics_image' );
	$rankings  = array_values( array_filter( (array) ( $f['rankings'] ?? array() ), static fn( $x ) => ! empty( $x['image'] ) && ! empty( $x['keyword'] ) ) );
	$head      = static function ( string $eyebrow, string $title ): void {
		printf(
			'<div style="font-size: 13.5px; font-weight: 600; color: var(--sh-link);">%s</div><h2 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(21px, 2vw, 26px); line-height: 1.5; margin: 8px 0px 0px;">%s</h2>',
			esc_html( $eyebrow ),
			esc_html( $title )
		);
	};
	?>
	<?php if ( $analytics ) : ?>
	<section data-screen-label="Analytics" style="background: var(--sh-surface); color: var(--sh-ink);">
		<div style="max-width: 1000px; margin: 0px auto; padding: clamp(32px, 4vw, 52px) 20px;">
			<div style="max-width: 760px; margin: 0px auto;"><?php $head( __( 'الدليل', 'seohouse' ), __( 'الدليل من Google Analytics', 'seohouse' ) ); ?></div>
			<?php
			$alt  = $g( 'analytics_alt' ) ? $g( 'analytics_alt' ) : (string) get_post_meta( $analytics, '_wp_attachment_image_alt', true );
			$full = (string) wp_get_attachment_image_url( $analytics, 'full' );
			?>
			<figure style="margin: 20px 0px 0px; border-radius: 16px; background: rgb(255, 255, 255); padding: 10px; box-shadow: 0 14px 34px -28px rgba(6,11,31,.5), 0 0 0 1px var(--sh-line);">
				<a href="<?php echo esc_url( $full ); ?>" data-sh-lightbox data-alt="<?php echo esc_attr( $alt ); ?>" aria-label="<?php echo esc_attr( __( 'فتح الصورة بالحجم الكامل:', 'seohouse' ) . ' ' . $alt ); ?>" class="sh-shot__img">
					<?php echo wp_get_attachment_image( $analytics, 'full', false, array( 'alt' => $alt, 'loading' => 'lazy', 'decoding' => 'async', 'sizes' => '(max-width: 1000px) 100vw, 980px' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</a>
				<?php if ( $g( 'source' ) ) : ?>
				<figcaption style="padding: 12px 6px 4px; font-size: 14px; line-height: 1.7; color: <?php echo esc_attr( $muted ); ?>;"><?php echo esc_html( __( 'المصدر:', 'seohouse' ) . ' ' . $g( 'source' ) ); ?></figcaption>
				<?php endif; ?>
			</figure>
		</div>
	</section>
	<?php endif; ?>

	<?php if ( $rankings ) : ?>
	<section data-screen-label="Rankings" style="background: var(--sh-surface); color: var(--sh-ink); border-top: 1px solid var(--sh-line);">
		<div style="max-width: 1000px; margin: 0px auto; padding: clamp(32px, 4vw, 52px) 20px;">
			<div style="max-width: 760px; margin: 0px auto;"><?php $head( __( 'نتائج البحث', 'seohouse' ), __( 'ترتيبنا في جوجل', 'seohouse' ) ); ?></div>
			<div class="sh-rank-grid" data-count="<?php echo (int) count( $rankings ); ?>" style="margin-top: 20px;">
				<?php
				foreach ( $rankings as $x ) {
					get_template_part( 'parts/case-shot', null, array( 'image' => (int) $x['image'], 'alt' => (string) ( $x['alt'] ?? '' ), 'keyword' => (string) $x['keyword'], 'position' => (string) ( $x['position'] ?? '' ) ) );
				}
				?>
			</div>
			<p style="margin: 16px 0px 0px; font-size: 14px; color: <?php echo esc_attr( $muted ); ?>;"><?php esc_html_e( 'لقطات من نتائج بحث جوجل وقت إعداد الدراسة', 'seohouse' ); ?></p>
		</div>
	</section>
	<?php endif; ?>

	<?php if ( $g( 'quote' ) ) : ?>
	<section data-screen-label="Testimonial" style="background: var(--sh-surface); color: var(--sh-ink); border-top: 1px solid var(--sh-line);">
		<div style="max-width: 760px; margin: 0px auto; padding: clamp(32px, 4vw, 52px) 20px;">
			<?php $head( __( 'بكلمات العميل', 'seohouse' ), __( 'رأي العميل', 'seohouse' ) ); ?>
			<figure class="sh-cs-quote">
				<blockquote><p><?php echo esc_html( $g( 'quote' ) ); ?></p></blockquote>
				<?php if ( $g( 'quote_name' ) || $g( 'quote_company' ) ) : ?>
				<figcaption><b><?php echo esc_html( $g( 'quote_name' ) ); ?></b><?php echo $g( 'quote_name' ) && $g( 'quote_company' ) ? '، ' : ''; ?><span><?php echo esc_html( $g( 'quote_company' ) ); ?></span></figcaption>
				<?php endif; ?>
			</figure>
		</div>
	</section>
	<?php endif; ?>

	<section data-screen-label="Next" style="background: var(--sh-bg); border-bottom: 1px solid var(--sh-line);">
		<div style="max-width: 1040px; margin: 0px auto; padding: clamp(28px, 3.2vw, 40px) 20px; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 16px 28px;">
			<div style="min-width: 0px;">
				<?php if ( $g( 'service_page' ) ) : ?>
				<div style="font-size: 13px; color: var(--sh-text);"><?php echo esc_html( $g( 'cta_title' ) ? $g( 'cta_title' ) : __( 'الخدمة المرتبطة', 'seohouse' ) ); ?></div>
				<a class="sh-hv-link" href="<?php echo esc_url( sh_link( $g( 'service_page' ) ) ); ?>" style="display: inline-flex; align-items: center; gap: 8px; margin-top: 6px; font-family: Alexandria, sans-serif; font-weight: 700; font-size: 19px; color: var(--sh-ink);"><?php echo esc_html( $g( 'service_label' ) ? $g( 'service_label' ) : get_the_title( url_to_postid( sh_link( $g( 'service_page' ) ) ) ) ); ?> <span aria-hidden="true" style="color: var(--sh-link);"><?php echo esc_html( sh_arrow() ); ?></span></a>
				<?php endif; ?>
				<?php if ( $g( 'cta_text' ) ) : ?>
				<p style="margin: 8px 0 0; color: var(--sh-text); font-size: 15px;"><?php echo esc_html( $g( 'cta_text' ) ); ?></p>
				<?php endif; ?>
			</div>
			<div style="display: flex; flex-wrap: wrap; gap: 12px;">
				<?php $results = get_page_by_path( 'results' ); $results = $results ? get_post( sh_tr_id( (int) $results->ID ) ) : null; // the results page in the visitor's language ?>
				<?php if ( $results ) : ?>
				<a href="<?php echo esc_url( get_permalink( $results ) ); ?>" class="sh-hv-next" style="min-height: 48px; display: inline-flex; align-items: center; padding: 0px 18px; border-radius: 12px; background: rgba(40, 84, 232, 0.18); box-shadow: rgba(40, 84, 232, 0.45) 0px 0px 0px 1px inset; color: var(--sh-ink); font-weight: 600;"><?php esc_html_e( 'كل النتائج', 'seohouse' ); ?></a>
				<?php endif; ?>
				<a href="<?php echo esc_url( $cta['url'] ); ?>" class="sh-hv-cta" style="min-height: 48px; display: inline-flex; align-items: center; padding: 0px 22px; border-radius: 12px; background: var(--sh-blue); color: rgb(255, 255, 255); font-weight: 700;"><?php esc_html_e( 'احجز مكالمة استشارية', 'seohouse' ); ?></a>
			</div>
		</div>
	</section>

	<?php
	$others = '';
	if ( sh_cases_v2() ) {
		ob_start();
		get_template_part( 'parts/dynamic/case-list', null, array( 'context' => 'related', 'exclude' => array( $id ), 'limit' => 3 ) );
		$others = trim( (string) ob_get_clean() );
	}
	if ( '' !== $others ) :
		?>
	<section data-screen-label="More cases" style="background: var(--sh-surface); color: var(--sh-ink);">
		<div style="max-width: 1100px; margin: 0px auto; padding: clamp(32px, 4vw, 52px) 20px;">
			<?php $head( __( 'نتائج أخرى', 'seohouse' ), __( 'دراسات حالة أخرى', 'seohouse' ) ); ?>
			<ul style="list-style: none; margin: 20px 0px 0px; padding: 0px; display: flex; flex-direction: column; gap: 12px;"><?php echo $others; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the part ?></ul>
		</div>
	</section>
	<?php endif; ?>
</main>
	<?php
endwhile;
get_footer();
