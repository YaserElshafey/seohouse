#!/usr/bin/env bash
# Builds the installable packages in dist/:
#   seohouse-core.zip   → Plugins › Add New › Upload. The plugin folder exactly as in the repository,
#                         content pack included (seohouse-core/content-pack); checked by
#                         tools/verify-core-zip.py before it is written as final.
#   seohouse-theme.zip  → Appearance › Themes › Add New › Upload
# No licence keys, secrets or third-party paid plugins are included.
set -euo pipefail
REPO="$(cd "$(dirname "$0")/.." && pwd)"
DIST="$REPO/dist"
rm -rf "$DIST" && mkdir -p "$DIST"
python3 "$REPO/tools/package-core.py" "$REPO" "$DIST/seohouse-core.zip"
cd "$REPO/wordpress/themes"
zip -qrX "$DIST/seohouse-theme.zip" seohouse -x '*.DS_Store' 'seohouse/node_modules/*'
( cd "$DIST" && sha256sum *.zip > SHA256SUMS.txt )
ls -la "$DIST"
