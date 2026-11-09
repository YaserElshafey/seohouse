<?php
/**
 * Screenshot browser (2.8.14): one large screenshot with its details, and beside it a scrolling list of
 * small previews (under it, side-scrolling, on phones). A preview shows its screenshot in the large
 * frame; the large frame opens the full-size viewer with previous / next over the whole list. With
 * more than one client, chips filter the list by client. Only the large frame loads a large image;
 * the list loads small ones, lazily, so the section keeps one height and stays light as screenshots
 * are added.
 *
 * @var array $args { items: [{image, alt, title, sub, keyword, position, date, lb_title, lb_sub}], filter (bool) }
 * @package SEOHouse
 */

defined( 'ABSPATH' ) || exit;

$items = array_values( array_filter( (array) ( $args['items'] ?? array() ), static fn( $x ) => ! empty( $x['image'] ) ) );
if ( ! $items ) {
	return;
}
$prep = array();
foreach ( $items as $x ) {
	$id  = (int) $x['image'];
	$alt = trim( (string) ( $x['alt'] ?? '' ) );
	$alt = '' !== $alt ? $alt : (string) get_post_meta( $id, '_wp_attachment_image_alt', true );
	$lg  = wp_get_attachment_image_src( $id, 'large' );
	$th  = wp_get_attachment_image_src( $id, 'medium' );
	if ( ! $lg ) {
		continue;
	}
	$prep[] = array(
		'id'       => $id,
		'alt'      => $alt,
		'full'     => (string) wp_get_attachment_image_url( $id, 'full' ),
		'large'    => (string) $lg[0],
		'srcset'   => (string) wp_get_attachment_image_srcset( $id, 'large' ),
		'thumb'    => $th ? (string) $th[0] : (string) $lg[0],
		'title'    => (string) ( $x['title'] ?? '' ),
		'sub'      => (string) ( $x['sub'] ?? '' ),
		'keyword'  => (string) ( $x['keyword'] ?? '' ),
		'position' => (string) ( $x['position'] ?? '' ),
		'date'     => (string) ( $x['date'] ?? '' ),
		'lb_title' => (string) ( $x['lb_title'] ?? ( $x['title'] ?? '' ) ),
		'lb_sub'   => (string) ( $x['lb_sub'] ?? ( $x['sub'] ?? '' ) ),
	);
}
if ( ! $prep ) {
	return;
}
$first   = $prep[0];
$clients = array_values( array_unique( array_filter( array_column( $prep, 'title' ) ) ) );
$filter  = ! empty( $args['filter'] ) && count( $clients ) > 1;
$attrs   = static function ( array $p ): string {
	$out = '';
	foreach ( array( 'title' => 'lb_title', 'sub' => 'lb_sub', 'kw' => 'keyword', 'pos' => 'position', 'date' => 'date' ) as $k => $f ) {
		$out .= sprintf( ' data-lb-%s="%s"', $k, esc_attr( $p[ $f ] ) );
	}
	return $out . sprintf( ' data-alt="%s"', esc_attr( $p['alt'] ) );
};
?>
<div class="sh-sg" data-sh-sg data-sh-gallery>
	<div class="sh-sg__main">
		<a class="sh-sg__stage sh-shot__frame" href="<?php echo esc_url( $first['full'] ); ?>" data-sh-lightbox data-sg-stage<?php echo $attrs( $first ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above ?> aria-label="<?php echo esc_attr( __( 'فتح الصورة بالحجم الكامل:', 'seohouse' ) . ' ' . $first['alt'] ); ?>">
			<img src="<?php echo esc_url( $first['large'] ); ?>" srcset="<?php echo esc_attr( $first['srcset'] ); ?>" sizes="(max-width: 899px) 100vw, 720px" alt="<?php echo esc_attr( $first['alt'] ); ?>" loading="lazy" decoding="async">
		</a>
		<div class="sh-sg__info" aria-live="polite">
			<p class="sh-sg__title" data-sg="title"<?php echo '' === $first['title'] ? ' hidden' : ''; ?>><?php echo esc_html( $first['title'] ); ?></p>
			<p class="sh-sg__sub" data-sg="sub"<?php echo '' === $first['sub'] ? ' hidden' : ''; ?>><?php echo esc_html( $first['sub'] ); ?></p>
			<p class="sh-shot__kw"><?php esc_html_e( 'كلمة البحث:', 'seohouse' ); ?> <b>«<bdi data-sg="keyword"><?php echo esc_html( $first['keyword'] ); ?></bdi>»</b></p>
			<p class="sh-sg__meta">
				<span class="sh-shot__pos" data-sg="position"<?php echo '' === $first['position'] ? ' hidden' : ''; ?>><?php echo esc_html( $first['position'] ); ?></span>
				<span class="sh-sg__date" data-sg="date" data-label="<?php echo esc_attr( __( 'لقطة بتاريخ %s', 'seohouse' ) ); ?>"<?php echo '' === $first['date'] ? ' hidden' : ''; ?>><?php echo esc_html( sprintf( /* translators: %s: date the screenshot was taken */ __( 'لقطة بتاريخ %s', 'seohouse' ), $first['date'] ) ); ?></span>
			</p>
		</div>
	</div>
	<div class="sh-sg__side">
		<?php if ( $filter ) : ?>
		<div class="sh-sg__chips" role="group" aria-label="<?php esc_attr_e( 'تصفية حسب العميل', 'seohouse' ); ?>">
			<button type="button" data-sg-filter="" aria-pressed="true"><?php esc_html_e( 'كل العملاء', 'seohouse' ); ?></button>
			<?php foreach ( $clients as $c ) : ?>
			<button type="button" data-sg-filter="<?php echo esc_attr( $c ); ?>" aria-pressed="false"><?php echo esc_html( $c ); ?></button>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
		<p class="sh-sg__count"><?php echo esc_html( sprintf( /* translators: %d: number of screenshots */ __( '%d لقطة', 'seohouse' ), count( $prep ) ) ); ?></p>
		<ol class="sh-sg__list" aria-label="<?php esc_attr_e( 'اللقطات', 'seohouse' ); ?>">
			<?php foreach ( $prep as $i => $p ) : ?>
			<li data-sg-client="<?php echo esc_attr( $p['title'] ); ?>">
				<button type="button" class="sh-sg__item" data-lb-item data-href="<?php echo esc_url( $p['full'] ); ?>" data-large="<?php echo esc_url( $p['large'] ); ?>" data-srcset="<?php echo esc_attr( $p['srcset'] ); ?>" data-title="<?php echo esc_attr( $p['title'] ); ?>" data-sub="<?php echo esc_attr( $p['sub'] ); ?>"<?php echo $attrs( $p ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above ?><?php echo 0 === $i ? ' aria-current="true"' : ''; ?>>
					<span class="sh-sg__thumb"><img src="<?php echo esc_url( $p['thumb'] ); ?>" alt="" loading="lazy" decoding="async"></span>
					<span class="sh-sg__label">
						<?php if ( '' !== $p['title'] ) : ?><span class="sh-sg__label-title"><?php echo esc_html( $p['title'] ); ?></span><?php endif; ?>
						<span class="sh-sg__label-kw">«<bdi><?php echo esc_html( $p['keyword'] ); ?></bdi>»</span>
					</span>
				</button>
			</li>
			<?php endforeach; ?>
		</ol>
	</div>
</div>
