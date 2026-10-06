<?php
/**
 * Section "Document" — SEO House - Privacy Policy.
 * Maintained by hand (2.6.0): one list of sections (title + body text, editable in the page's
 * «الأقسام» field) drives both the contents list and the text; anchors follow the order.
 * @sh-manual
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f        = $args['f'] ?? array();
$sections = array_values( array_filter( (array) ( $f['items_2'] ?? array() ), static fn( $r ) => is_array( $r ) && ( trim( (string) ( $r['title'] ?? '' ) ) !== '' || trim( (string) ( $r['body'] ?? '' ) ) !== '' ) ) );
$toc      = array();
foreach ( $sections as $i => $r ) {
	if ( trim( wp_strip_all_tags( (string) ( $r['title'] ?? '' ) ) ) !== '' ) {
		$toc[ $i ] = trim( wp_strip_all_tags( (string) $r['title'] ) );
	}
}
?>
<section data-screen-label="Document" style="position: relative; background: var(--sh-surface); color: var(--sh-ink);">
    <div data-lg-grid style="position: relative; max-width: 1120px; margin: 0px auto; padding: clamp(28px, 3.4vw, 50px) 20px clamp(32px, 4.4vw, 60px); display: grid; gap: clamp(22px, 3vw, 52px); align-items: start;">
      <?php if ( $toc ) : ?><nav data-lg-nav aria-label="محتويات الصفحة" style="border-radius: 14px; background: rgb(255, 255, 255); padding: 16px 18px; box-shadow: rgba(var(--sh-ink-rgb), 0.5) 0px 14px 32px -28px;">
        <?php if ( ! empty( $f['eyebrow'] ) ) : ?><div style="font-size: 13px; font-weight: 700; color: var(--sh-link); margin-bottom: 10px;"><?= esc_html( $f['eyebrow'] ) ?></div><?php endif; ?>
        <ol style="list-style: none; margin: 0px; padding: 0px; display: flex; flex-direction: column; gap: 2px;">
          <?php foreach ( $toc as $i => $label ) : ?><li><a href="#p<?= (int) ( $i + 1 ) ?>" class="hv-a18a86" style="display: flex; gap: 10px; font-size: 14.5px; color: var(--sh-text); padding: 7px 0px;"><span><?= esc_html( $label ) ?></span></a></li><?php endforeach; ?>
        </ol>
      </nav><?php endif; ?>
      <div style="min-width: 0px; max-width: 720px;">
        <?php if ( ! empty( $f['updated'] ) ) : ?><div style="font-size: 13.5px; color: var(--sh-text);"><?= esc_html( $f['label'] ?? '' ) ?> <time><?= esc_html( $f['updated'] ) ?></time></div><?php endif; ?>
        <?php foreach ( $sections as $i => $r ) : ?><div id="p<?= (int) ( $i + 1 ) ?>" style="scroll-margin-top: 96px; padding: <?= 0 === $i ? '18px' : '26px' ?> 0px 0px;">
          <?php if ( isset( $toc[ $i ] ) ) : ?><h2 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(23px, 2.2vw, 31px); line-height: 1.4; margin: 0px 0px 12px;"><?= sh_inline( $r['title'] ) ?></h2><?php endif; ?>
          <?php if ( ! empty( $r['body'] ) ) : ?><div style="font-size: 16px; line-height: 1.9; color: var(--sh-text);"><?= sh_doc_text( $r['body'] ) // escaped inside ?></div><?php endif; ?>
        </div><?php endforeach; ?>
      </div>
    </div>
  </section>
