#!/usr/bin/env bash
# Local test site laid out like seohouse.agency today (SQLite, local only):
#   theme folder = old main-site theme + SEO House theme 2.5.0 extracted over it (leftovers kept,
#   front-page.php renamed front-page.php.old as on the server), Core 2.5.0, ACF 6.3.12, Rank Math 1.0.279.
# Creates /home/claude/wpbase-acf63 (once) and /home/claude/wptest; deletes nothing else.
set -euo pipefail
WP="wp --allow-root --path=/home/claude/wptest"
R250=/tmp/claude-0/-home-claude/b72197a4-3992-5966-a439-59cbc767a6dd/scratchpad/r250

if [ ! -e /home/claude/wpbase-acf63 ]; then
	cp -r /home/claude/wpbase /home/claude/wpbase-acf63
	rm -rf /home/claude/wpbase-acf63/wp-content/plugins/advanced-custom-fields
	cp -r /home/claude/wpsrc/acf-6.3.12 /home/claude/wpbase-acf63/wp-content/plugins/advanced-custom-fields
	rm -rf /home/claude/wpbase-acf63/wp-content/plugins/advanced-custom-fields/.git
	sed -i 's#/home/claude/wpbase/#/home/claude/wptest/#' /home/claude/wpbase-acf63/wp-content/db.php
fi
if [ -e /home/claude/wptest ]; then rm -rf /home/claude/wptest; fi
cp -r /home/claude/wpbase-acf63 /home/claude/wptest
grep -q "/home/claude/wptest/" /home/claude/wptest/wp-content/db.php || sed -i 's#/home/claude/wpbase/#/home/claude/wptest/#' /home/claude/wptest/wp-content/db.php

$WP config create --dbname=wptest --dbuser=x --dbpass=x --skip-check >/dev/null
$WP config set DB_DIR /home/claude/wptest/wp-content/database >/dev/null
$WP config set WP_ENVIRONMENT_TYPE production >/dev/null
$WP config set WP_DEBUG true --raw >/dev/null
$WP config set WP_DEBUG_LOG true --raw >/dev/null
$WP config set WP_DEBUG_DISPLAY false --raw >/dev/null
$WP core install --url="http://127.0.0.1:8090" --title="سيو هاوس" --admin_user=admin --admin_password=admin --admin_email=dev@example.com --skip-email >/dev/null
$WP option update permalink_structure '/%postname%/' >/dev/null
$WP plugin activate advanced-custom-fields seo-by-rank-math >/dev/null

# Core 2.5.0 (release file of commit 7756dd5)
(cd /home/claude/wptest/wp-content/plugins && unzip -q "$R250/seohouse-core.zip")
$WP plugin activate seohouse-core >/dev/null
# theme folder as on the server: old theme, then 2.5.0 extracted over it, front-page.php → .old
mkdir -p /home/claude/wptest/wp-content/themes/seohouse
cp -a /home/claude/seohouse-src/wp-theme/seohouse/. /home/claude/wptest/wp-content/themes/seohouse/
(cd /home/claude/wptest/wp-content/themes && unzip -qo "$R250/seohouse-theme.zip")
mv /home/claude/wptest/wp-content/themes/seohouse/front-page.php /home/claude/wptest/wp-content/themes/seohouse/front-page.php.old
$WP theme activate seohouse >/dev/null

# content as set up by the 2.5.0 tool
$WP seohouse import 2>/dev/null | tail -3
# legal pages as seen on the live site: privacy published with empty fields (not the setup page)
$WP eval '
$old = get_page_by_path("privacy-policy");
wp_update_post(array("ID"=>$old->ID,"post_status"=>"draft","post_name"=>"privacy-policy-setup"));
$id = wp_insert_post(array("post_type"=>"page","post_status"=>"publish","post_title"=>"سياسة الخصوصية","post_name"=>"privacy-policy"));
update_post_meta($id,"_wp_page_template","page-templates/privacy-policy.php");
$t = get_page_by_path("terms"); wp_update_post(array("ID"=>$t->ID,"post_status"=>"publish"));' 2>/dev/null
$WP rewrite flush 2>/dev/null
$WP plugin list --fields=name,status,version 2>/dev/null
$WP theme list --status=active --fields=name,version 2>/dev/null
echo "theme folder files: $(find /home/claude/wptest/wp-content/themes/seohouse -type f | wc -l)"
