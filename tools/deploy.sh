#!/usr/bin/env bash
# Publish SEO House packages to a site through Core's REST routes (seohouse/v1, Core 2.8.0+).
#
#   tools/deploy.sh site                          versions, search visibility, backups (read-only)
#   tools/deploy.sh theme [release/seohouse-theme.zip]
#   tools/deploy.sh core  [release/seohouse-core.zip]
#   tools/deploy.sh rollback theme|core
#
# SH_SITE   site address (default https://seohouse.agency)
# SH_AUTH   user:application-password — only for a local test site. For the live site the cloud
#           environment adds the Authorization header itself (network secret for the site's host), so no
#           password is ever written here, in the repository or in the shell history.
set -euo pipefail
SITE="${SH_SITE:-https://seohouse.agency}"
API="${SITE%/}/wp-json/seohouse/v1"
AUTH=()
[ -n "${SH_AUTH:-}" ] && AUTH=(-u "$SH_AUTH")
REPO="$(cd "$(dirname "$0")/.." && pwd)"

call() { curl -sS --fail-with-body "${AUTH[@]}" "$@"; echo; }

case "${1:-}" in
	site) call "$API/site" ;;
	theme|core)
		kind="$1"
		zip="${2:-$REPO/release/seohouse-$([ "$kind" = core ] && echo core || echo theme).zip}"
		[ -f "$zip" ] || { echo "missing $zip" >&2; exit 1; }
		sha=$(sha256sum "$zip" | cut -c1-64)
		if [ -f "$REPO/release/SHA256SUMS.txt" ] && [ "$(dirname "$(readlink -f "$zip")")" = "$REPO/release" ]; then
			grep -q "^$sha  $(basename "$zip")$" "$REPO/release/SHA256SUMS.txt" || { echo "$zip does not match release/SHA256SUMS.txt" >&2; exit 1; }
		fi
		call -X POST "$API/deploy" -F "kind=$kind" -F "sha256=$sha" -F "package=@$zip"
		;;
	rollback)
		[ "${2:-}" = theme ] || [ "${2:-}" = core ] || { echo "rollback theme|core" >&2; exit 1; }
		call -X POST "$API/rollback" -d "kind=$2"
		;;
	*) sed -n '2,12p' "$0" >&2; exit 1 ;;
esac
