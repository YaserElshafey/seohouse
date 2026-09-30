#!/usr/bin/env bash
# Local development: rebuild generated sources and reinstall WordPress content from scratch.
# Usage: tools/dev-reset.sh <design-dir>   (expects WordPress at $WP_PATH, default /srv/shwp)
set -euo pipefail
DESIGN="$1"; WP_PATH="${WP_PATH:-/srv/shwp}"; REPO="$(cd "$(dirname "$0")/.." && pwd)"
cd "$REPO/tools/design-import"
node convert.js "$DESIGN" "$REPO" > /dev/null
node core-groups.js "$REPO" > /dev/null
node build-content.js "$DESIGN" "$REPO" > /dev/null
cd "$WP_PATH"
wp db reset --yes --allow-root > /dev/null
find wp-content/uploads -mindepth 1 -maxdepth 1 -type d -name '[0-9][0-9][0-9][0-9]' -exec rm -rf {} + # media from the previous local import
wp core install --url="${WP_URL:-http://127.0.0.1:8080}" --title="سيو هاوس" --admin_user=admin --admin_password=admin --admin_email=dev@example.com --skip-email --allow-root > /dev/null
wp language core activate ar --allow-root > /dev/null 2>&1 || true
wp plugin activate secure-custom-fields seohouse-core --allow-root > /dev/null
wp theme activate seohouse --allow-root > /dev/null
wp seohouse import --allow-root
