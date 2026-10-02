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
# Core ships with the content pack inside (seohouse-core/content-pack): one-step setup, no upload
STAGE="$(mktemp -d)"
cp -rL "$REPO/wordpress/plugins/seohouse-core" "$STAGE/seohouse-core"
cp -r "$REPO/content-pack" "$STAGE/seohouse-core/content-pack"
rm -f "$STAGE/seohouse-core/content-pack/build-log.txt"
(cd "$STAGE" && zip -qr "$DIST/seohouse-core.zip" seohouse-core -x '*.DS_Store')
rm -rf "$STAGE"
cd "$REPO"
zip -qr "$DIST/seohouse-content.zip" content-pack -x '*.DS_Store'
( cd "$DIST" && sha256sum *.zip > SHA256SUMS.txt )
ls -la "$DIST"
