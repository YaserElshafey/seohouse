#!/usr/bin/env bash
# Forms suite on the local test site (test-only mu-plugin records mail instead of sending).
W="wp --allow-root --path=/home/claude/wptest"
OUT=${1:-/home/claude/wptest-fixture/runs/forms}
mkdir -p "$OUT"; rm -f /home/claude/wptest/wp-content/mail-log.jsonl
reset() { $W eval 'global $wpdb; $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE \"_transient%sh_lead_rl_%\""); delete_option("test_mail_fail"); delete_option("test_store_fail");' 2>/dev/null; }
leads() { $W eval 'foreach(get_posts(["post_type"=>"sh_lead","post_status"=>"private","numberposts"=>-1,"orderby"=>"ID","order"=>"ASC"]) as $p) printf("#%d | %s | %s | mail=%s %s\n",$p->ID,get_post_meta($p->ID,"_sh_source",true),sh_lead_source_label($p->ID),get_post_meta($p->ID,"_sh_mail",true),get_post_meta($p->ID,"_sh_mail_error",true));' 2>/dev/null; }
echo "== browser, logged out (1440px)"
for s in home contact home-after-contact stale-cache home-double contact-double; do reset; node /home/claude/wptest-fixture/forms-test.js http://127.0.0.1:8090 "$OUT/$s.json" $s 2>&1 | cut -c1-330; done
echo "== same fill sent twice (same sid) — API"
reset
T=$(( $(date +%s) - 10 ))
for i in 1 2; do curl -s -X POST http://127.0.0.1:8090/wp-json/seohouse/v1/lead -F source=booking -F page_id=6 -F service=web -F name="سid" -F contact=sid-test@example.com -F elapsed=5000 -F sid=fixed-sid-123 -F ts=$T; echo; done
echo "== mail server fails: request kept, success shown, failure recorded"
reset; $W option update test_mail_fail 1 >/dev/null 2>&1
curl -s -X POST http://127.0.0.1:8090/wp-json/seohouse/v1/lead -F source=contact -F page_id=0 -F service=seo -F name="بريد فاشل" -F company="ش" -F email=mailfail@example.com -F phone=+201000000001 -F goal="هدف" -F elapsed=5000 -F sid=mf-1; echo
echo "== saving fails: no success"
reset; $W option update test_store_fail 1 >/dev/null 2>&1
curl -s -w " (HTTP %{http_code})" -X POST http://127.0.0.1:8090/wp-json/seohouse/v1/lead -F source=booking -F page_id=6 -F service=seo -F name="حفظ فاشل" -F contact=storefail@example.com -F elapsed=5000 -F sid=sf-1; echo
reset
echo "== stored requests"; leads
echo "== mails recorded"; python3 -c "
import json
for l in open('/home/claude/wptest/wp-content/mail-log.jsonl'):
    m=json.loads(l); print(m['result'], m['to'], '|', m['subject'][:90], '| has source line:', 'المصدر:' in m['message'])"
