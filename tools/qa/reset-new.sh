#!/usr/bin/env bash
# Test site in a folder (/new/) in the state the owner had: fresh WordPress, default theme,
# permalinks /%postname%/, ACF installed but inactive, no SEO House files. Never the live site.
set -euo pipefail
WPD=/srv/shwpnew/new; wp() { command wp --allow-root --path="$WPD" "$@"; }
wp db reset --yes >/dev/null
rm -rf "$WPD/wp-content/uploads/"* "$WPD/wp-content/plugins/seohouse-core" "$WPD/wp-content/themes/seohouse" "$WPD/wp-content/debug.log"
wp core install --url=http://127.0.0.1:8097/new --title="سيو هاوس" --admin_user=admin --admin_password=admin --admin_email=dev@example.com --skip-email >/dev/null
wp language core activate ar >/dev/null 2>&1 || true
wp theme activate twentytwentyfive >/dev/null
wp rewrite structure '/%postname%/' >/dev/null
echo "reset: home=$(wp option get home) permalinks=$(wp option get permalink_structure) plugins=$(wp plugin list --field=name --status=inactive | tr '\n' ' ')"
