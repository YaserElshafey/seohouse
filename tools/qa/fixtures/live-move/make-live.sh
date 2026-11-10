#!/usr/bin/env bash
# A local copy laid out like seohouse.agency after the move from /new/ (All-in-One WP Migration):
# root site with the /new/ database, Core 2.5.0 + theme 2.5.0 extracted OVER the old main-site theme
# folder (leftovers kept, as the migration plugin does), the 2.5.0 update applied, and the two legal
# pages in the state seen on the live site.
set -e
DST=/srv/shwplive; DB=shwplive; URL=http://127.0.0.1:8094; REPO=/home/user/seohouse
mysql -uroot -e "DROP DATABASE IF EXISTS $DB; CREATE DATABASE $DB CHARACTER SET utf8mb4; GRANT ALL ON $DB.* TO 'shwp'@'localhost';"
rm -rf $DST; mkdir -p $DST
(cd /srv/shwpseo/new && tar --exclude=./wp-content/plugins/seohouse-core --exclude=./wp-content/themes/seohouse --exclude=./wp-content/debug.log -cf - .) | (cd $DST && tar xf -)
sed -i "s/'shwpseonew'/'$DB'/" $DST/wp-config.php
mysql -uroot $DB < /tmp/claude-0/seo/new-like-real.sql
W="wp --allow-root --path=$DST"
$W search-replace 'http://127.0.0.1:8096/new' "$URL" --all-tables --quiet
$W option update blog_public 1 --quiet
# plugins: Core 2.5.0 (release file of 7756dd5)
git -C $REPO show 7756dd5:release/seohouse-core.zip > /tmp/claude-0/seo/core-2.5.0.zip
git -C $REPO show 7756dd5:release/seohouse-theme.zip > /tmp/claude-0/seo/theme-2.5.0.zip
(cd $DST/wp-content/plugins && unzip -q /tmp/claude-0/seo/core-2.5.0.zip)
# theme folder: the old main-site theme, then the 2.5.0 theme extracted over it (nothing removed)
mkdir -p $DST/wp-content/themes/seohouse
cp -a $REPO/wp-theme/seohouse/. $DST/wp-content/themes/seohouse/
(cd $DST/wp-content/themes && unzip -qo /tmp/claude-0/seo/theme-2.5.0.zip)
# the 2.5.0 update, as run on /new/ before the move
$W seohouse import > /tmp/claude-0/seo/live-250-import.txt 2>&1 || true
# privacy as on the live site: a published page on /privacy-policy/ with the template and no field values,
# not the page the setup had created (that one moved aside as a draft)
$W eval '
$old = get_page_by_path("privacy-policy");
wp_update_post(array("ID"=>$old->ID,"post_status"=>"draft","post_name"=>"privacy-policy-setup"));
$id = wp_insert_post(array("post_type"=>"page","post_status"=>"publish","post_title"=>"سياسة الخصوصية","post_name"=>"privacy-policy"));
update_post_meta($id,"_wp_page_template","page-templates/privacy-policy.php");
echo "privacy page at the address: #$id\n";'
$W rewrite flush --quiet
echo done
