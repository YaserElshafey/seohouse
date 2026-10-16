#!/usr/bin/env bash
# Upgrade test on the /new/ test site (never the live site).
#
#   upgrade-new.sh prepare <theme-2.1.0.zip> <core-2.2.5.zip> <rank-math.zip>
#       The owner's state before this round: fresh WordPress in /new/ with pages already sitting on
#       design URLs, ACF + SEO House theme 2.1.0 + Core 2.2.5 + Rank Math, initialised with
#       «اعتمد الصفحات الموجودة», then edited by hand (a page section, a Rank Math title, a menu
#       label, the client logos list).
#   upgrade-new.sh snapshot <out.json>
#       IDs, slugs, URLs, templates, modified dates, edited values, counts.
set -euo pipefail
WPD=/srv/shwpnew/new; wp() { command wp --allow-root --path="$WPD" "$@"; }
cmd="$1"; shift

if [ "$cmd" = prepare ]; then
  THEME="$1"; CORE="$2"; RM="$3"
  bash "$(dirname "$0")/reset-new.sh"
  # pages that existed on /new/ before the design import (same URLs as the design)
  svc=$(wp post create --post_type=page --post_status=publish --post_title='الخدمات (قديمة)' --post_name=services --post_content='نص قديم' --porcelain)
  seo=$(wp post create --post_type=page --post_status=publish --post_title='السيو (قديمة)' --post_name=seo --post_parent="$svc" --post_content='نص قديم' --porcelain)
  wp post create --post_type=page --post_status=publish --post_title='مصر (قديمة)' --post_name=egypt --post_parent="$seo" --post_content='نص قديم عن مصر' --porcelain >/dev/null
  wp post create --post_type=page --post_status=publish --post_title='من نحن (قديمة)' --post_name=about --post_content='نص قديم' --porcelain >/dev/null
  wp post create --post_type=page --post_status=publish --post_title='اتصل بنا (قديمة)' --post_name=contact --post_content='نص قديم' --porcelain >/dev/null
  wp plugin activate advanced-custom-fields >/dev/null
  wp theme install "$THEME" --activate --force >/dev/null
  wp plugin install "$CORE" --activate --force >/dev/null
  wp plugin install "$RM" --activate --force >/dev/null
  wp option update rank_math_registration_skip 1 >/dev/null
  echo "versions: theme $(wp theme get seohouse --field=version) core $(wp plugin get seohouse-core --field=version) rank-math $(wp plugin get seo-by-rank-math --field=version)"
  # initialisation as the owner ran it with Core 2.2.5 (adopt existing pages on /new/)
  wp eval '$i = new SH_Importer( SH_Importer::default_dir(), array( "adopt_existing_pages" => true ) ); $ok = $i->run(); echo ( $ok ? "import ok " : "import FAILED " ) . wp_json_encode( $i->counts ) . "\n";'
  # the owner's edits after the import
  wp eval '
    $about = (int) get_page_by_path( "about" )->ID;
    $f = acf_get_field( "s_hero", $about );
    foreach ( $f["sub_fields"] as $s ) { if ( "title" === $s["name"] ) { update_field( $f["key"], array( $s["key"] => "عن سيو هاوس — نص عدّله المحرر" ), $about ); } }
    $ksa = (int) get_page_by_path( "services/seo/ksa" )->ID;
    update_post_meta( $ksa, "rank_math_title", "عنوان Rank Math كتبه المحرر للسعودية" );
    $logos = get_field( "sh_client_logos", "option", false ); array_pop( $logos ); update_field( "field_sh_opt_client_logos", $logos, "option" );
    echo "edits: about hero title, KSA Rank Math title, client logos -1\n";
  '
  menu=$(wp menu list --fields=term_id,name --format=csv | grep 'القائمة الرئيسية' | cut -d, -f1)
  item=$(wp menu item list "$menu" --fields=db_id,title --format=csv | sed -n 2p | cut -d, -f1)
  wp menu item update "$item" --title='الرئيسية (عدّلها المحرر)' >/dev/null
  echo "edit: first primary menu item label"
  exit 0
fi

if [ "$cmd" = snapshot ]; then
  OUT="$1"
  wp eval '
    $pages = array();
    foreach ( get_posts( array( "post_type" => array( "page", "post", "case_study", "team_member" ), "post_status" => "any", "posts_per_page" => -1, "orderby" => "ID", "order" => "ASC" ) ) as $p ) {
      $pages[] = array( "id" => $p->ID, "type" => $p->post_type, "status" => $p->post_status, "slug" => $p->post_name, "url" => get_permalink( $p ), "template" => get_page_template_slug( $p ), "key" => get_post_meta( $p->ID, "_sh_source_key", true ), "title" => $p->post_title, "pre_adoption" => metadata_exists( "post", $p->ID, "_sh_pre_adoption" ) );
    }
    $id = static fn( $path ) => (int) get_page_by_path( $path )->ID;
    $h1 = static function ( $path ) use ( $id ) { $s = get_field( "s_hero", $id( $path ) ); return $s["title"] ?? ""; };
    $menu = wp_get_nav_menu_items( get_nav_menu_locations()["primary"] ?? 0 );
    echo wp_json_encode( array(
      "core" => SH_CORE_VERSION, "theme" => wp_get_theme( "seohouse" )->get( "Version" ),
      "last_import" => get_option( "sh_content_last_import" )["version"] ?? "",
      "pages" => $pages,
      "counts" => array( "attachments" => (int) wp_count_posts( "attachment" )->inherit, "sh_row" => array_sum( (array) wp_count_posts( "sh_row" ) ), "pages_publish" => (int) wp_count_posts( "page" )->publish, "pages_draft" => (int) wp_count_posts( "page" )->draft ),
      "edited" => array(
        "about_h1" => $h1( "about" ),
        "ksa_rank_math_title" => get_post_meta( $id( "services/seo/ksa" ), "rank_math_title", true ),
        "client_logos" => count( (array) get_field( "sh_client_logos", "option", false ) ),
        "menu_first" => $menu ? $menu[0]->title : "",
      ),
      "design" => array( "egypt_h1" => $h1( "services/seo/egypt" ), "ksa_h1" => $h1( "services/seo/ksa" ), "uae_h1" => $h1( "services/seo/uae" ), "react_h1" => $h1( "services/web-design/react-next" ) ),
      "platforms" => count( (array) get_field( "sh_platforms", "option", false ) ),
      "rank_math_titles" => (int) $GLOBALS["wpdb"]->get_var( "SELECT COUNT(*) FROM {$GLOBALS["wpdb"]->postmeta} WHERE meta_key = \"rank_math_title\" AND meta_value <> \"\"" ),
    ), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
  ' > "$OUT"
  echo "snapshot → $OUT"
  exit 0
fi
echo "usage: $0 prepare|snapshot ..." >&2; exit 2
