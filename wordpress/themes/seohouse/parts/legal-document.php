<?php
/**
 * Body of the legal pages (privacy policy, terms), shared by sections/{privacy-policy,terms}/document.php.
 *
 * Source of the text, in this order — nothing is copied or written:
 *   1. the page's «الأقسام» field (title + text per section);
 *   2. the page's own editor content (post_content), when the fields are empty: a page created or
 *      filled outside the design template keeps its text visible;
 *   3. neither: visitors see no body; editors see where to enter the text and any other page using
 *      the same template that does hold text (e.g. the page the setup created).
 * Numbering: titles that already start with a number ("1. …") are shown as saved; the automatic
 * number is only added when no title carries one.
 *
 * @package SEOHouse
 * @var array $args { f: layout values, anchor: id prefix, numbers: bool, toc_style: 'card'|'line' }
 */

defined( 'ABSPATH' ) || exit;

$f        = $args['f'] ?? array();
$anchor   = (string) ( $args['anchor'] ?? 's' );
$numbers  = ! empty( $args['numbers'] );
$toc_card = 'card' === ( $args['toc_style'] ?? 'line' );
$own_num  = static fn( string $t ): bool => (bool) preg_match( '/^\s*(?:[0-9]+|[٠-٩]+)\s*[.)\-–:،]/u', $t );

$sections = array_values(
	array_filter(
		(array) ( $f['items_2'] ?? array() ),
		static fn( $r ) => is_array( $r ) && ( trim( (string) ( $r['title'] ?? '' ) ) !== '' || trim( (string) ( $r['body'] ?? '' ) ) !== '' )
	)
);
$toc = array();
foreach ( $sections as $i => $r ) {
	$t = trim( wp_strip_all_tags( (string) ( $r['title'] ?? '' ) ) );
	if ( '' !== $t ) {
		$toc[ $i ] = $t;
	}
}
// automatic numbers only when none of the saved titles is numbered already
$auto = $numbers && $toc && ! array_filter( $toc, $own_num );

// fallback: the page's editor content
$content = '';
if ( ! $sections ) {
	$raw = (string) get_post_field( 'post_content', get_queried_object_id() );
	if ( '' !== trim( wp_strip_all_tags( strip_shortcodes( $raw ) ) ) ) {
		$content = (string) apply_filters( 'the_content', $raw ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals -- core filter
		$n       = 0;
		$content = preg_replace_callback(
			'#<h2(\s[^>]*)?>(.*?)</h2>#is',
			static function ( $m ) use ( &$toc, &$n, $anchor ) {
				$attrs = (string) ( $m[1] ?? '' );
				if ( preg_match( '/\sid="([^"]+)"/', $attrs, $id ) ) {
					$toc[ '#' . $id[1] ] = trim( wp_strip_all_tags( $m[2] ) );
					return $m[0];
				}
				++$n;
				$toc[ '#' . $anchor . 'c' . $n ] = trim( wp_strip_all_tags( $m[2] ) );
				return '<h2 id="' . esc_attr( $anchor . 'c' . $n ) . '" style="scroll-margin-top: 96px;"' . $attrs . '>' . $m[2] . '</h2>';
			},
			$content
		);
	}
}
$href = static fn( $k ) => is_string( $k ) && str_starts_with( $k, '#' ) ? $k : '#' . $anchor . ( (int) $k + 1 );
?>
<section data-screen-label="Document" style="position: relative; background: var(--sh-surface); color: var(--sh-ink);">
	<div data-lg-grid style="position: relative; max-width: 1120px; margin: 0px auto; padding: clamp(28px, 3.4vw, 50px) 20px clamp(32px, 4.4vw, 60px); display: grid; gap: clamp(22px, 3vw, 52px); align-items: start;">
		<?php if ( $toc ) : ?>
		<nav data-lg-nav aria-label="<?php esc_attr_e( 'محتويات الصفحة', 'seohouse' ); ?>" style="<?php echo $toc_card ? 'border-radius: 14px; background: rgb(255, 255, 255); padding: 16px 18px; box-shadow: rgba(var(--sh-ink-rgb), 0.5) 0px 14px 32px -28px;' : 'border-inline-start: 2px solid rgba(var(--sh-blue-rgb), 0.3); padding-inline-start: 16px;'; ?>">
			<?php if ( ! empty( $f['eyebrow'] ) ) : ?><div style="font-size: 13px; font-weight: 700; color: var(--sh-link); margin-bottom: 10px;"><?php echo esc_html( $f['eyebrow'] ); ?></div><?php endif; ?>
			<ol style="list-style: none; margin: 0px; padding: 0px; display: flex; flex-direction: column; gap: 2px;">
				<?php
				$n = 0;
				foreach ( $toc as $k => $label ) :
					++$n;
					?>
				<li><a href="<?php echo esc_attr( $href( $k ) ); ?>" class="hv-a18a86" style="display: flex; gap: 10px; font-size: 14.5px; color: var(--sh-text); padding: 7px 0px;"><?php if ( $auto ) : ?><span style="color: var(--sh-text); font-size: 12.5px; min-width: 18px;"><?php echo (int) $n; ?></span><?php endif; ?><span><?php echo esc_html( $label ); ?></span></a></li>
				<?php endforeach; ?>
			</ol>
		</nav>
		<?php endif; ?>
		<div style="min-width: 0px; max-width: 720px;">
			<?php if ( ! empty( $f['updated'] ) ) : ?><div style="font-size: 13.5px; color: var(--sh-text);"><?php echo esc_html( $f['label'] ?? '' ); ?> <time><?php echo esc_html( $f['updated'] ); ?></time></div><?php endif; ?>
			<?php
			$n = 0;
			foreach ( $sections as $i => $r ) :
				?>
			<div id="<?php echo esc_attr( $anchor . ( $i + 1 ) ); ?>" style="scroll-margin-top: 96px; padding: <?php echo 0 === $i ? '18px' : '26px'; ?> 0px 0px;<?php echo $numbers ? ' border-top: 1px solid var(--sh-line);' : ''; ?>">
				<?php
				if ( isset( $toc[ $i ] ) ) :
					++$n;
					?>
				<h2 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(23px, 2.2vw, 31px); line-height: 1.4; margin: 0px 0px 12px;"><?php if ( $auto ) : ?><span style="color: var(--sh-link);"><?php echo (int) $n; ?>.</span> <?php endif; ?><?php echo sh_inline( $r['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $r['body'] ) ) : ?><div style="font-size: 16px; line-height: 1.9; color: var(--sh-text);"><?php echo sh_doc_text( $r['body'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></div><?php endif; ?>
			</div>
			<?php endforeach; ?>
			<?php if ( $content ) : ?>
			<div class="sh-prose is-light" data-legal-content><?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput -- the_content ?></div>
			<?php endif; ?>
			<?php
			if ( ! $sections && ! $content && current_user_can( 'edit_pages' ) ) :
				$tpl    = (string) get_page_template_slug( get_queried_object_id() );
				$others = get_posts(
					array(
						'post_type'      => 'page',
						'post_status'    => array( 'publish', 'draft', 'pending', 'private' ),
						'posts_per_page' => 10,
						'post__not_in'   => array( get_queried_object_id() ),
						'meta_key'       => '_wp_page_template', // phpcs:ignore WordPress.DB.SlowDBQuery
						'meta_value'     => $tpl, // phpcs:ignore WordPress.DB.SlowDBQuery
					)
				);
				$with = array();
				foreach ( $others as $o ) {
					$rows = function_exists( 'sh_core_sections' ) ? sh_core_sections( (int) $o->ID ) : array();
					$has  = '' !== trim( wp_strip_all_tags( (string) $o->post_content ) );
					foreach ( $rows as $row ) {
						foreach ( (array) ( $row['items_2'] ?? array() ) as $it ) {
							$has = $has || '' !== trim( (string) ( $it['body'] ?? '' ) );
						}
					}
					if ( $has ) {
						$with[] = $o;
					}
				}
				?>
			<div class="sh-admin-hint" role="note" style="margin: 0; color: var(--sh-ink); border-color: rgba(40, 84, 232, .5);">
				<strong><?php esc_html_e( 'لا يوجد نص محفوظ لهذه الصفحة (يظهر هذا التنبيه للمحررين فقط).', 'seohouse' ); ?></strong><br>
				<?php esc_html_e( 'حقل «الأقسام» فارغ ومحتوى المحرر فارغ. أدخل النص من «تحرير الصفحة» ← «المستند» ← «الأقسام» (عنوان ونص لكل قسم).', 'seohouse' ); ?>
				<?php if ( $with ) : ?>
				<br><?php esc_html_e( 'صفحات أخرى بنفس القالب فيها نص محفوظ:', 'seohouse' ); ?>
					<?php foreach ( $with as $o ) : ?>
				<br>— <a href="<?php echo esc_url( (string) get_edit_post_link( $o->ID ) ); ?>"><?php echo esc_html( get_the_title( $o ) . ' #' . $o->ID . ' (' . get_post_status( $o ) . ', /' . $o->post_name . '/)' ); ?></a>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
			<?php endif; ?>
		</div>
	</div>
</section>
