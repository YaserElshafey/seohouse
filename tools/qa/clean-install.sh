#!/usr/bin/env bash
# Clean-install test of the three packages on a brand-new WordPress + ACF (free) from WordPress.org.
# Usage: tools/qa/clean-install.sh <wp-core-dir-to-copy> <target-dir> <db-name> <port>
# Needs: wp-cli, MariaDB user shwp/shwp, dist/*.zip (tools/package.sh). Never touches the live site.
set -euo pipefail
SRC="$1"; DST="$2"; DB="$3"; PORT="$4"; REPO="$(cd "$(dirname "$0")/../.." && pwd)"; DIST="$REPO/dist"
wp() { command wp --allow-root --path="$DST" "$@"; }
mysql -e "DROP DATABASE IF EXISTS $DB; CREATE DATABASE $DB CHARACTER SET utf8mb4; GRANT ALL ON $DB.* TO 'shwp'@'localhost';"
rm -rf "$DST" /tmp/sh-content-$DB; mkdir -p "$DST"
(cd "$SRC" && tar --exclude=./wp-content --exclude=./wp-config.php -cf - .) | (cd "$DST" && tar xf -)
mkdir -p "$DST"/wp-content/{plugins,themes,uploads,languages}; cp -r "$SRC"/wp-content/languages/. "$DST"/wp-content/languages/
wp config create --dbname="$DB" --dbuser=shwp --dbpass=shwp --skip-check >/dev/null
wp config set WP_ENVIRONMENT_TYPE production >/dev/null
wp config set WP_DEBUG true --raw >/dev/null; wp config set WP_DEBUG_LOG true --raw >/dev/null; wp config set WP_DEBUG_DISPLAY false --raw >/dev/null
wp core install --url="http://127.0.0.1:$PORT" --title="سيو هاوس" --admin_user=admin --admin_password=admin --admin_email=dev@example.com --skip-email >/dev/null
wp language core activate ar >/dev/null 2>&1 || true
echo "== WordPress $(wp core version), plugins before: $(wp plugin list --field=name | wc -l)"
wp plugin install advanced-custom-fields --activate 2>&1 | tail -1
wp plugin install "$DIST/seohouse-core.zip" --activate 2>&1 | tail -1
wp theme install "$DIST/seohouse-theme.zip" --activate 2>&1 | tail -1
mkdir -p /tmp/sh-content-$DB && (cd /tmp/sh-content-$DB && unzip -q "$DIST/seohouse-content.zip")
PACK=/tmp/sh-content-$DB/content-pack
wp plugin list --status=active --fields=name,version
echo "== dry run";  wp seohouse import --source=$PACK --dry-run | tail -6
echo "pages after dry run (WordPress defaults only): $(wp post list --post_type=page --post_status=any --format=count)"
echo "== import";   wp seohouse import --source=$PACK | tail -6
echo "== verify";   wp seohouse verify --source=$PACK | tail -1
echo "== re-run";   wp seohouse import --source=$PACK | tail -6
echo "records: pages $(wp post list --post_type=page --post_status=any --format=count), posts $(wp post list --post_type=post --post_status=any --format=count), cases $(wp post list --post_type=case_study --post_status=any --format=count), team $(wp post list --post_type=team_member --format=count), list items $(wp post list --post_type=sh_row --format=count), media $(wp post list --post_type=attachment --post_status=inherit --format=count)"
