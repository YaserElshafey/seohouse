#!/usr/bin/env bash
# Local upgrade test (SQLite test site only — nothing here touches the live site).
# Restores the edited 2.6.0 copy, upgrades the theme to dist/seohouse-theme.zip, and compares.
set -uo pipefail
WP="wp --allow-root --path=/home/claude/wptest"
OUT=/home/claude/wptest-fixture/run
rm -rf /home/claude/wptest-fixture/run && mkdir -p /home/claude/wptest-fixture/run

echo "== 1. restore the edited 2.6.0 site (database + theme 2.6.0)"
rm -rf /home/claude/wptest/wp-content/database
cp -a /home/claude/wptest-fixture/database-edited-2.6.0 /home/claude/wptest/wp-content/database
$WP theme install /home/claude/seohouse-src/release/seohouse-theme.zip --force 2>&1 | grep -v wp_update | tail -1
: > /home/claude/wptest/wp-content/debug.log
$WP theme list --status=active --fields=name,version 2>/dev/null

echo "== 2. colour settings saved with the 2.x defaults (as if «إعدادات سيو هاوس» had been saved)"
$WP eval '
foreach ( array( "ink" => "#060B1F", "blue" => "#2F5BFF", "sky" => "#4CACFF", "lime" => "#C7FF32", "paper" => "#F7F9FD", "text" => "#EDF1FA", "muted" => "#B9C4DC" ) as $k => $v ) {
	update_option( "options_sh_color_$k", $v ); update_option( "_options_sh_color_$k", "field_sh_opt_c_$k" );
}' 2>/dev/null

echo "== 3. snapshot before"
$WP eval-file /tmp/claude-0/-home-claude-repo/b72197a4-3992-5966-a439-59cbc767a6dd/scratchpad/snapshot.php $OUT/before.json 2>/dev/null
python3 /home/claude/wptest-fixture/crawl-text.py $OUT/text-before.json

echo "== 4. upgrade: theme ZIP replaces the installed theme"
$WP theme install /home/claude/seohouse-src/dist/seohouse-theme.zip --force 2>&1 | grep -v wp_update | tail -1
$WP theme list --status=active --fields=name,version 2>/dev/null
$WP plugin list --fields=name,status,version 2>/dev/null | grep -E "seohouse|advanced|rank"

echo "== 5. snapshot after + compare"
$WP eval-file /tmp/claude-0/-home-claude-repo/b72197a4-3992-5966-a439-59cbc767a6dd/scratchpad/snapshot.php $OUT/after.json 2>/dev/null
python3 /home/claude/wptest-fixture/compare.py $OUT/before.json $OUT/after.json

echo "== 6. crawl after"
python3 /home/claude/wptest-fixture/crawl-text.py $OUT/text-after.json
python3 /home/claude/wptest-fixture/check-text.py $OUT/text-before.json $OUT/text-after.json $OUT/after.json

echo "== 7. PHP log"
grep -v -E "wordpress.org|wp_update_|WordPress.org" /home/claude/wptest/wp-content/debug.log | sort | uniq -c | sort -rn | head -20
echo "(end of log)"
