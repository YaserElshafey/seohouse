<?php
/**
 * Template part: execution platforms — floating logo cloud
 */
$platforms     = sh_get_platforms();
$has_platforms = $platforms->have_posts();

$items = [];
if ( $has_platforms ) {
    while ( $platforms->have_posts() ) {
        $platforms->the_post();
        $icon    = sh_field( 'platform_icon' );
        $url     = sh_field( 'platform_url' );
        $items[] = [
            'title'    => get_the_title(),
            'icon_url' => ! empty( $icon['url'] ) ? $icon['url'] : '',
            'url'      => $url,
        ];
    }
    wp_reset_postdata();
}

// Fallback static platforms if none are added yet
if ( empty( $items ) ) {
    $items = [
        [ 'title' => 'WordPress',        'icon_url' => '', 'svg_id' => 'logo-wp' ],
        [ 'title' => 'Shopify',          'icon_url' => '', 'svg_id' => 'logo-shopify' ],
        [ 'title' => 'سلة',              'icon_url' => '', 'svg_id' => 'logo-salla' ],
        [ 'title' => 'زد',               'icon_url' => '', 'svg_id' => 'logo-zid' ],
        [ 'title' => 'WooCommerce',      'icon_url' => '', 'svg_id' => 'logo-woo' ],
        [ 'title' => 'Google Analytics', 'icon_url' => '', 'svg_id' => 'logo-ga' ],
        [ 'title' => 'Search Console',   'icon_url' => '', 'svg_id' => 'logo-sc' ],
        [ 'title' => 'Magento',          'icon_url' => '', 'svg_id' => 'logo-magento' ],
    ];
}
?>

<?php if ( empty( $has_platforms ) ) : ?>
<!-- Inline SVG symbols for fallback platform logos -->
<svg xmlns="http://www.w3.org/2000/svg" style="display:none" aria-hidden="true">
  <symbol id="logo-wp" viewBox="0 0 64 64"><circle cx="32" cy="32" r="30" fill="#21759B"/><path d="M9 32a23 23 0 0011.7 19.9L11 26.5a22.9 22.9 0 00-2 5.5zm38.5-1.2c0-2.4-.9-4-1.6-5.3-1-1.6-1.9-3-1.9-4.6 0-1.8 1.4-3.5 3.3-3.5h.3a22.9 22.9 0 00-34.6 4.3c2.3 0 5 0 5 .1 1.2 0 1.4.7.5.7 0 0-1.4.2-3 .3l9.4 28 5.7-17-4-11.1-2.8-.3c-1.3-.1-1.1-.8 0-.7 0 0 5.4.4 7.4.4 2.3 0 5-.4 5-.4 1.2 0 1.4.7.5.7 0 0-1.4.2-3 .3l9.3 27.8 2.7-8.7c1.2-3.7 2-6.3 2-8.6zM32.5 35l-7.7 22.5a22.9 22.9 0 0014.1-.4c0-.2 0-.3-.1-.5L32.5 35zm20.3-13.4c.1.7.2 1.5.2 2.4 0 2.3-.4 4.9-1.7 8.2L44.2 53a22.9 22.9 0 008.6-31.4z" fill="#fff"/></symbol>
  <symbol id="logo-shopify" viewBox="0 0 64 64"><path d="M45.5 13c-.2-.3-.6-.3-.7-.3l-3 .2-2.2-2.3c-.2-.2-.7-.2-.8-.1L37.2 11c-.8-2.6-2.3-5-5.1-5h-.3c-.8-1-1.8-1.5-2.7-1.5C22 4.7 18.7 13.4 17.7 18l-5.3 1.6c-1.6.5-1.7.6-2 2.1L5.8 53l25 4.5L46 54s-.4-25.8-.4-26-.1-.7 0-.7c.5 0 3.8-.3 3.8-.3l-.9-9c-.5-.3-2.9-5-3-5zm-9-1.7l-1.8.6v-1.2c0-1.4-.2-2.5-.5-3.4 1.4.2 2.3 1.8 2.4 4zm-3.4 1l-3.8 1.2c.4-1.5 1.1-3 2-4 .4-.4.9-.8 1.5-1 .2.7.4 1.7.4 3v.8zm-3-5.9c.5 0 1 .2 1.4.5-.8.4-1.5 1-2.2 1.7-1.2 1.3-2.1 3.2-2.5 5L23 15.6c1-3 3.4-9.1 7-9.1z" fill="#95BF47"/><path d="M44.7 12.7l-3 .2-2.2-2.3c-.1-.1-.3-.2-.5-.2v47.1L46 54s-.4-25.8-.4-26-.1-.7 0-.7c.5 0 3.8-.3 3.8-.3l-.9-9c-.5-.3-2.9-5-3-5z" fill="#5E8E3E"/><path d="M34.7 27.2L33 32c-2-1-3.3-1.6-5.5-1.6-2.6 0-2.7 1.6-2.7 2 0 2.2 5.8 3 5.8 8.2 0 4-2.6 6.7-6 6.7-4.2 0-6.4-2.6-6.4-2.6l1-3.7s2.2 2 4.1 2c1.3 0 1.8-1 1.8-1.7 0-2.8-4.7-3-4.7-7.7 0-4 2.8-7.8 8.6-7.8 2.2 0 3.3.6 4.7 1.4z" fill="#fff"/></symbol>
  <symbol id="logo-woo" viewBox="0 0 64 64"><path d="M58 12H6c-3.3 0-6 2.7-6 6v22c0 3.3 2.7 6 6 6h25l13 12-3-12h17c3.3 0 6-2.7 6-6V18c0-3.3-2.7-6-6-6z" fill="#7F54B3"/><path d="M9 22c.4-1 1-1.5 2-1.6 1.8-.2 2.8.7 3.1 2.6.7 4 1.3 7.4 2 10.3l5.2-9.9c.5-.9 1.1-1.4 1.9-1.5 1.2-.1 1.9.7 2.2 2.3.6 3 1.4 5.6 2.4 7.8.6-6.5 1.7-11.2 3.4-14.1.4-.7 1-1 1.8-1.1.7 0 1.3.2 1.8.7s.8 1 .9 1.7c0 .5-.1 1-.3 1.4-1 1.9-1.8 5.1-2.4 9.5-.6 4.3-.9 7.7-.7 10 .1.7 0 1.2-.3 1.7-.3.6-.8.9-1.4 1-.7 0-1.5-.3-2.2-1-2.6-2.6-4.6-6.6-6.1-11.8-1.8 3.6-3.2 6.3-4 8.1-1.6 3.1-3 4.7-4.2 4.8-.7.1-1.3-.6-1.8-1.9-1.4-3.5-2.8-10.4-4.4-20.5-.1-.7 0-1.4.3-1.9l1.8-2.6z" fill="#fff"/></symbol>
  <symbol id="logo-salla" viewBox="0 0 64 64"><circle cx="32" cy="32" r="30" fill="#BAF3E6"/><text x="32" y="40" font-family="Cairo,Arial,sans-serif" font-size="24" font-weight="900" fill="#004E45" text-anchor="middle">سلة</text></symbol>
  <symbol id="logo-zid" viewBox="0 0 64 64"><circle cx="32" cy="32" r="30" fill="#1A2C53"/><text x="32" y="40" font-family="Cairo,Arial,sans-serif" font-size="22" font-weight="900" fill="#5DD9B0" text-anchor="middle">زد</text></symbol>
  <symbol id="logo-ga" viewBox="0 0 64 64"><rect x="42" y="10" width="12" height="44" rx="6" fill="#F9AB00"/><rect x="26" y="24" width="12" height="30" rx="6" fill="#E37400"/><circle cx="16" cy="48" r="6" fill="#E37400"/></symbol>
  <symbol id="logo-sc" viewBox="0 0 64 64"><rect x="4" y="4" width="56" height="56" rx="10" fill="#fff" stroke="#dadce0"/><path d="M16 38h32M16 30h24M16 22h28" stroke="#4285F4" stroke-width="3" stroke-linecap="round"/><circle cx="42" cy="42" r="9" fill="none" stroke="#EA4335" stroke-width="3.5"/><path d="M49 49l6 6" stroke="#EA4335" stroke-width="3.5" stroke-linecap="round"/></symbol>
  <symbol id="logo-magento" viewBox="0 0 64 64"><path d="M32 4L8 18v28l5 2.8V20.5l19-11 19 11V48.8L56 46V18L32 4z" fill="#F26322"/><path d="M35 50.8L32 52.5l-3-1.7V25.5l-9 5.3v25.5l12 6.8 12-6.8V30.8l-9-5.3v25.3z" fill="#F26322"/></symbol>
</svg>
<?php endif; ?>

<section id="platforms" class="sec">
  <div class="wrap">
    <div class="sh c sr" style="margin-bottom:28px">
      <span class="tag">منصات التنفيذ</span>
      <h2 class="h2">نعمل على المنصات التي تثق بها</h2>
    </div>
    <div class="plat-cloud">
      <?php foreach ( $items as $idx => $item ) :
          $tag   = $item['url'] ? 'a' : 'div';
          $attrs = $item['url'] ? 'href="' . esc_url( $item['url'] ) . '" target="_blank" rel="noopener"' : '';
      ?>
        <<?php echo $tag; ?> <?php echo $attrs; ?> class="plat-badge" style="--i:<?php echo $idx; ?>" title="<?php echo esc_attr( $item['title'] ); ?>">
          <div class="plat-badge-inner">
            <div class="plat-ico-wrap">
              <?php if ( ! empty( $item['icon_url'] ) ) : ?>
                <img src="<?php echo esc_url( $item['icon_url'] ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>">
              <?php elseif ( ! empty( $item['svg_id'] ) ) : ?>
                <svg aria-hidden="true" focusable="false"><use href="#<?php echo esc_attr( $item['svg_id'] ); ?>"/></svg>
              <?php else : ?>
                <span><?php echo esc_html( mb_substr( $item['title'], 0, 2 ) ); ?></span>
              <?php endif; ?>
            </div>
          </div>
        </<?php echo $tag; ?>>
      <?php endforeach; ?>
    </div>
  </div>
</section>
