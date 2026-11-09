<?php
/**
 * Section "Rankings" — «من ترتيب عملائنا في جوجل» (2.8.8): Google results screenshots of clients'
 * pages, each with the client, its field, the search phrase and the position. Nothing is shown
 * without screenshots. Hidden sections are seen by editors with ?sh_show_hidden=1.
 *
 * @sh-manual
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f     = $args['f'] ?? array();
$items = array_values( array_filter( (array) ( $f['items'] ?? array() ), static fn( $x ) => ! empty( $x['image'] ) && ! empty( $x['keyword'] ) ) );
if ( ! $items ) {
	return;
}
?>
<section data-screen-label="Rankings" style="background: var(--sh-surface); color: var(--sh-ink); border-top: 1px solid var(--sh-line);">
	<div style="max-width: 1100px; margin: 0px auto; padding: clamp(32px, 4vw, 56px) 20px;">
		<?php if ( ! empty( $f['sh_preview_hidden'] ) ) : ?>
		<div role="note" class="sh-hidden-note"><?php esc_html_e( 'قسم مخفي عن الزوار — يظهر لك فقط للمراجعة.', 'seohouse' ); ?></div>
		<?php endif; ?>
		<?php if ( ! empty( $f['eyebrow'] ) ) : ?><div style="font-size: 13px; font-weight: 600; color: var(--sh-link);"><?php echo esc_html( $f['eyebrow'] ); ?></div><?php endif; ?>
		<h2 data-h2 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(23px, 2.2vw, 31px); margin: 10px 0px 0px; line-height: 1.3;"><?php echo esc_html( ! empty( $f['title'] ) ? $f['title'] : __( 'من ترتيب عملائنا في جوجل', 'seohouse' ) ); ?></h2>
		<div style="margin-top: 22px;">
		<?php
		get_template_part(
			'parts/shot-gallery',
			null,
			array(
				'filter' => true,
				'items'  => array_map(
					static fn( $x ) => array(
						'image'    => (int) $x['image'],
						'alt'      => (string) ( $x['alt'] ?? '' ),
						'title'    => (string) ( $x['client'] ?? '' ),
						'sub'      => (string) ( $x['sector'] ?? '' ),
						'keyword'  => (string) $x['keyword'],
						'position' => (string) ( $x['position'] ?? '' ),
						'date'     => (string) ( $x['date'] ?? '' ),
					),
					$items
				),
			)
		);
		?>
		</div>
		<?php if ( ! empty( $f['note'] ) ) : ?>
		<p style="margin: 18px 0px 0px; font-size: 14px; color: var(--sh-text);"><?php echo esc_html( $f['note'] ); ?></p>
		<?php endif; ?>
	</div>
</section>
