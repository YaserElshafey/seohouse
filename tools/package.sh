#!/usr/bin/env bash
# Builds the installable packages in dist/:
#   seohouse-theme.zip    → Appearance › Themes › Add New › Upload
#   seohouse-core.zip     → Plugins › Add New › Upload (includes the content pack: «تهيئة الموقع»)
#   seohouse-content.zip  → optional, same pack separately (CLI: wp seohouse import --source=<dir>)
# No licence keys, secrets or third-party paid plugins are included.
set -euo pipefail
REPO="$(cd "$(dirname "$0")/.." && pwd)"
DIST="$REPO/dist"
rm -rf "$DIST" && mkdir -p "$DIST"
cd "$REPO/wordpress/themes"
zip -qr "$DIST/seohouse-theme.zip" seohouse -x '*.DS_Store' 'seohouse/node_modules/*'
# Core ships with the content pack inside (seohouse-core/content-pack): ASCII names, pack first,
# main plugin file last, integrity list (see tools/package-core.py)
python3 "$REPO/tools/package-core.py" "$REPO" "$DIST/seohouse-core.zip"
cd "$REPO"
zip -qr "$DIST/seohouse-content.zip" content-pack -x '*.DS_Store'
( cd "$DIST" && sha256sum *.zip > SHA256SUMS.txt )
ls -la "$DIST"
