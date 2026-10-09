#!/usr/bin/env bash
# Run after admin-edit-test.js on an installation: an update run must not overwrite anything an
# editor changed (pages, case study, settings, menus) and must not duplicate list items.
# Usage: tools/qa/protection-test.sh <wp-dir> <content-pack-dir>
set -uo pipefail
WPD="$1"; PACK="$2"; wp() { command wp --allow-root --path="$WPD" "$@"; }
rows0=$(wp post list --post_type=sh_row --format=count)
echo "== update run with untouched menus"; wp seohouse import --source="$PACK" --update --only=menus | grep "menu:"
M=$(wp menu list --fields=term_id --format=ids | cut -d' ' -f1); I=$(wp menu item list "$M" --fields=db_id --format=ids | cut -d' ' -f1)
wp menu item update "$I" --title="عنصر عدّله المحرر" >/dev/null
echo "== editor renamed an item in menu $M; full update run (bare --update)"
wp seohouse import --source="$PACK" --update | grep -E "^protected|^(created|updated|skipped|protected|failed) +[0-9]"
echo "menu edit kept: $(wp menu item list "$M" --fields=title --format=csv | grep -c 'عنصر عدّله المحرر')"
echo "list items before/after: $rows0 / $(wp post list --post_type=sh_row --format=count)"
