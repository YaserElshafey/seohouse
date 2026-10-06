#!/usr/bin/env bash
# Update test from the installed versions (theme 2.7.2, Core 2.7.1, ACF 6.3.12, Rank Math 1.0.279) with
# edited content, SQLite test site only. Usage: upgrade-test-270.sh <run-name> <core.zip> <theme.zip>
#   step A: Core ZIP replaces the plugin («استبدال الحالي بالمرفوع»), first admin request
#   step B: theme ZIP replaces the theme folder
set -uo pipefail
WP="wp --allow-root --path=/home/claude/wptest"
RUN="$1"; CORE_ZIP="$2"; THEME_ZIP="$3"
OUT=/home/claude/wptest-fixture/runs/$RUN
mkdir -p /home/claude/wptest-fixture/runs
if [ -e "$OUT" ]; then rm -rf "$OUT"; fi
mkdir -p "$OUT"
q() { grep -v -E "_load_textdomain_just_in_time|wp_update_|WordPress.org" || true; }

echo "== 1. restore the installed state: theme 2.7.2 + Core 2.7.1 + edits + earlier requests and bookings"
rm -rf /home/claude/wptest/wp-content/database /home/claude/wptest/wp-content/themes/seohouse /home/claude/wptest/wp-content/plugins/seohouse-core
cp -a /home/claude/wptest-fixture/state-272/database /home/claude/wptest/wp-content/database
cp -a /home/claude/wptest-fixture/state-272/theme-seohouse /home/claude/wptest/wp-content/themes/seohouse
cp -a /home/claude/wptest-fixture/state-272/plugin-seohouse-core /home/claude/wptest/wp-content/plugins/seohouse-core
: > /home/claude/wptest/wp-content/debug.log
$WP theme list --status=active --fields=name,version 2>&1 | q
$WP plugin list --fields=name,status,version 2>&1 | q | grep -E "seohouse|advanced|rank"

echo "== 2. snapshot + crawl before"
$WP eval-file /home/claude/wptest-fixture/snapshot.php "$OUT/before.json" 2>&1 | q
python3 /home/claude/wptest-fixture/crawl-text.py "$OUT/text-before.json"
cp /home/claude/wptest/wp-content/debug.log "$OUT/debug-before.log"; : > /home/claude/wptest/wp-content/debug.log

echo "== 3A. Core: replace with $(basename "$CORE_ZIP")"
$WP plugin install "$CORE_ZIP" --force 2>&1 | q | tail -1
$WP eval 'do_action("admin_init");' 2>&1 | q
$WP plugin list --fields=name,status,version 2>&1 | q | grep seohouse
echo "== 3B. theme: replace with $(basename "$THEME_ZIP")"
$WP theme install "$THEME_ZIP" --force 2>&1 | q | tail -1
$WP theme list --status=active --fields=name,version 2>&1 | q
$WP plugin list --fields=name,status,version 2>&1 | q | grep seohouse

echo "== 4. snapshot after + compare"
$WP eval-file /home/claude/wptest-fixture/snapshot.php "$OUT/after.json" 2>&1 | q
python3 /home/claude/wptest-fixture/compare.py "$OUT/before.json" "$OUT/after.json"

echo "== 5. crawl after + checks"
python3 /home/claude/wptest-fixture/crawl-text.py "$OUT/text-after.json"
python3 /home/claude/wptest-fixture/check-text.py "$OUT/text-before.json" "$OUT/text-after.json" "$OUT/after.json"

echo "== 6. PHP log after the update (ACF 6.3.12's own textdomain notice on WP 7.1 filtered; counted below)"
cp /home/claude/wptest/wp-content/debug.log "$OUT/debug-after.log"
echo "ACF textdomain notices (not from SEO House): $(grep -c _load_textdomain_just_in_time "$OUT/debug-after.log")"
grep -v -E "_load_textdomain_just_in_time|wordpress.org|wp_update_|WordPress.org" "$OUT/debug-after.log" | sed 's/^\[[^]]*\] //' | sort | uniq -c | sort -rn | head -20
echo "(end of log)"
