#!/usr/bin/env bash
# Builds the installable packages in dist/:
#   seohouse-theme.zip    → Appearance › Themes › Add New › Upload
#   seohouse-core.zip     → Plugins › Add New › Upload
#   seohouse-content.zip  → SEO House › تجهيز المحتوى (or: wp seohouse import --source=<unzipped dir>)
# No licence keys, secrets or third-party paid plugins are included.
set -euo pipefail
REPO="$(cd "$(dirname "$0")/.." && pwd)"
DIST="$REPO/dist"
rm -rf "$DIST" && mkdir -p "$DIST"
cd "$REPO/wordpress/themes"
zip -qr "$DIST/seohouse-theme.zip" seohouse -x '*.DS_Store' 'seohouse/node_modules/*'
cd "$REPO/wordpress/plugins"
zip -qr "$DIST/seohouse-core.zip" seohouse-core -x '*.DS_Store'
cd "$REPO"
zip -qr "$DIST/seohouse-content.zip" content-pack -x '*.DS_Store'
( cd "$DIST" && sha256sum *.zip > SHA256SUMS.txt )
ls -la "$DIST"
