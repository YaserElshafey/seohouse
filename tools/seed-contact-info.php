<?php
/**
 * One-off: fill the contact page settings with the company details.
 *
 * Not part of the theme — company data lives in the database, not in theme files.
 * Run once from the WordPress root:
 *
 *     wp eval-file /path/to/tools/seed-contact-info.php
 *
 * Only EMPTY fields are filled; anything already saved is left untouched and
 * reported, so it is safe to run more than once. Without WP-CLI, enter the same
 * values manually in: إعدادات الموقع ← صفحة التواصل ← بيانات التواصل.
 * The map fields are intentionally not seeded — add the Google Maps links yourself.
 */
if ( ! defined( 'ABSPATH' ) || ! function_exists( 'update_field' ) ) {
    echo "Run with: wp eval-file seed-contact-info.php (ACF must be active)\n";
    return;
}

$values = [
    'contact_phone'   => '+201050861791',
    'contact_email'   => 'info@seohouse.agency',
    'contact_address' => '234 شارع عبد السلام عارف، لوران، الإسكندرية، مصر.',
    'contact_hours'   => 'من الأحد إلى الخميس، من 9 صباحًا إلى 5 مساءً بتوقيت مصر.',
];

foreach ( $values as $name => $value ) {
    $current = trim( (string) get_field( $name, 'option', false ) );
    if ( '' !== $current ) {
        echo "skip  {$name}: already set to \"{$current}\"\n";
        continue;
    }
    update_field( $name, $value, 'option' );
    echo "set   {$name}\n";
}
