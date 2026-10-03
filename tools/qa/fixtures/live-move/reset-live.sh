#!/usr/bin/env bash
set -e
mysql -uroot shwplive < /tmp/claude-0/seo/live-like.sql
rm -rf /srv/shwplive/wp-content/themes/seohouse /srv/shwplive/wp-content/plugins/seohouse-core /srv/shwplive/wp-content/uploads/seohouse-backups /srv/shwplive/wp-content/debug.log
cp -a /tmp/claude-0/seo/live-like-theme /srv/shwplive/wp-content/themes/seohouse
(cd /srv/shwplive/wp-content/plugins && unzip -q /tmp/claude-0/seo/core-2.5.0.zip)
