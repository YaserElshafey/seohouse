// Booking flow, logged-out visitor. Usage: node booking-test.js <outdir>
const { chromium, devices } = require('/opt/node-tools/node_modules/playwright');
const { execSync } = require('child_process');
const fs = require('fs');
const base = 'http://127.0.0.1:8090', out = process.argv[2];
const wp = c => execSync(`wp --allow-root --path=/home/claude/wptest ${c} 2>/dev/null`).toString().trim();
const ev = php => wp(`eval '${php}'`);
const R = [];
function setBooking(provider, url, token) {
  for (const [k, v, fk] of [['sh_booking_provider', provider, 'booking_provider'], ['sh_booking_url', url, 'booking_url'], ['sh_booking_token', token, 'booking_token'], ['sh_booking_duration', '30', 'booking_duration']]) {
    wp(`option update options_${k} "${v}"`); wp(`option update _options_${k} field_sh_opt_${fk}`);
  }
}
function reset() { ev('global $wpdb; $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE \\"_transient%sh_lead_%\\""); delete_option("test_calendly_down"); delete_option("test_calendly_canceled");'); }
const leadCount = () => +ev('echo count(get_posts(["post_type"=>"sh_lead","post_status"=>"private","numberposts"=>-1,"fields"=>"ids"]));');
const lastLead = () => JSON.parse(ev('$p=get_posts(["post_type"=>"sh_lead","post_status"=>"private","numberposts"=>1,"orderby"=>"ID","order"=>"DESC"])[0]; $g=fn($k)=>get_post_meta($p->ID,"_sh_".$k,true); echo wp_json_encode(["id"=>$p->ID,"state"=>sh_lead_state_label($p->ID),"email"=>$g("email"),"phone"=>$g("phone"),"contact"=>$g("contact"),"source"=>sh_lead_source_label($p->ID),"ref"=>$g("ref"),"booking_state"=>$g("booking_state"),"booking_error"=>$g("booking_error"),"start"=>$g("booking_start"),"time"=>sh_lead_booking_time($p->ID),"mail"=>$g("mail"),"bmail"=>$g("booking_mail")], JSON_UNESCAPED_UNICODE);'));

async function open(browser, mobile) {
  const ctx = await browser.newContext(mobile ? { ...devices['iPhone 13'], viewport: { width: 390, height: 844 }, timezoneId: 'Asia/Riyadh', locale: 'ar' } : { viewport: { width: 1440, height: 900 }, timezoneId: 'Africa/Cairo', locale: 'ar' });
  const page = await ctx.newPage();
  await page.goto(base + '/', { waitUntil: 'networkidle' });
  const box = page.locator('[data-sh-booking]').first();
  await box.scrollIntoViewIfNeeded();
  return { ctx, page, box };
}
async function fill(box, page, d) {
  await box.locator('[data-bk-pick="seo"]').click();
  await box.locator('input[name="name"]').fill(d.name ?? 'زائر اختبار');
  await box.locator('input[name="email"]').fill(d.email ?? '');
  await box.locator('input[name="phone"]').fill(d.phone ?? '');
  await page.waitForTimeout(3000);
}
async function submit(box, page) {
  const reqs = []; page.on('request', r => { if (r.url().includes('/seohouse/v1/lead')) reqs.push(r.url().replace(base, '')); });
  await box.locator('button[type="submit"]').click();
  await page.waitForTimeout(2500);
  const err = await box.locator('[data-bk-error]').isVisible() ? (await box.locator('[data-bk-error]').textContent()).trim() : '';
  return { reqs, err };
}
const ui = box => box.evaluate(b => ({
  label: b.querySelector('[data-bk-label]').textContent, counter: b.querySelector('[data-bk-counter]').textContent,
  step2: !b.querySelector('[data-bk-step2]').hidden, receipt: !b.querySelector('[data-bk-receipt]').hidden && b.querySelector('[data-bk-receipt]').innerText.replace(/\s+/g, ' ').trim(),
  iframe: (b.querySelector('[data-bk-embed] iframe') || {}).src || null, head: (b.querySelector('[data-bk-sched-head]') || {}).innerText || null,
  tz: (b.querySelector('[data-bk-tz]') || {}).innerText || null, confirmed: b.querySelector('[data-bk-confirmed]') && !b.querySelector('[data-bk-confirmed]').hidden ? b.querySelector('[data-bk-confirmed]').innerText.replace(/\s+/g, ' ') : false,
  pending: b.querySelector('[data-bk-pending]') && !b.querySelector('[data-bk-pending]').hidden, scrollX: document.documentElement.scrollWidth > innerWidth, iframeH: b.querySelector('[data-bk-embed] iframe') ? Math.round(b.querySelector('[data-bk-embed] iframe').getBoundingClientRect().height) : 0,
}));
const log = (name, r) => { R.push({ name, ...r }); console.log('##', name, JSON.stringify(r, null, 0)); };

(async () => {
  const browser = await chromium.launch();
  for (const mobile of [true, false]) {
    const tag = mobile ? 'm390' : 'd1440';
    // A. not connected
    reset(); setBooking('none', '', '');
    let { ctx, page, box } = await open(browser, mobile);
    log(`${tag} A0 step1 labels`, await box.evaluate(b => ({ label: b.querySelector('[data-bk-label]').textContent, l2: b.querySelector('[data-bk-label]').dataset.l2, button: b.querySelector('button[type=submit]').textContent.trim(), fields: [...b.querySelectorAll('input:not([type=hidden])')].map(i => i.name + (i.required ? '*' : '')) })));
    await page.screenshot({ path: `${out}/${tag}-A-step1.png`, fullPage: false });
    // B. browser validation
    for (const [k, d] of [['no-email', { email: '', phone: '+201001234567' }], ['bad-email', { email: 'abc@', phone: '+201001234567' }], ['no-phone', { email: 'a@example.com', phone: '' }], ['no-country-code', { email: 'a@example.com', phone: '0501234567' }], ['letters', { email: 'a@example.com', phone: '+20abc' }]]) {
      await page.reload({ waitUntil: 'networkidle' }); box = page.locator('[data-sh-booking]').first();
      await fill(box, page, d); log(`${tag} B ${k}`, await submit(box, page));
    }
    let n0 = leadCount();
    await page.reload({ waitUntil: 'networkidle' }); box = page.locator('[data-sh-booking]').first();
    await fill(box, page, { email: `a-${tag}@example.com`, phone: '٠٠٩٦٦ ٥٠ ١٢٣ ٤٥٦٧' });
    log(`${tag} A submit (Arabic digits, 00)`, { ...(await submit(box, page)), ui: await ui(box), newLeads: leadCount() - n0, lead: lastLead() });
    await box.screenshot({ path: `${out}/${tag}-A-step2-not-connected.png` });
    await ctx.close();

    // C. Calendly connected + token
    reset(); setBooking('calendly', 'http://127.0.0.1:8091/seohouse/30min', 'test-token');
    ({ ctx, page, box } = await open(browser, mobile));
    log(`${tag} C step1`, await box.evaluate(b => ({ l2: b.querySelector('[data-bk-label]').dataset.l2, button: b.querySelector('button[type=submit]').textContent.trim() })));
    n0 = leadCount();
    await fill(box, page, { email: `c-${tag}@example.com`, phone: '+966 50 123 4567' });
    const sub = await submit(box, page);
    await page.waitForTimeout(1500);
    const afterSave = lastLead();
    log(`${tag} C after save (visitor has not picked a time)`, { ...sub, ui: await ui(box), newLeads: leadCount() - n0, lead: afterSave });
    await box.screenshot({ path: `${out}/${tag}-C-scheduler.png` });
    // forged message from the page itself (not the scheduler frame): ignored
    await page.evaluate(ref => window.postMessage({ event: 'calendly.event_scheduled', payload: { invitee: { uri: `https://api.calendly.com/scheduled_events/EVT-${ref}/invitees/INV-${ref}` } } }, '*'), afterSave.ref);
    await page.waitForTimeout(1500);
    log(`${tag} C forged message from page`, { ui: await ui(box), lead: lastLead() });
    // pick a slot inside the scheduler frame
    const fr = page.frameLocator('[data-bk-embed] iframe');
    await fr.locator('[data-slot]').first().click(); await fr.locator('#confirm').click();
    await page.waitForTimeout(3000);
    log(`${tag} C booked`, { ui: await ui(box), newLeads: leadCount() - n0, lead: lastLead() });
    await box.screenshot({ path: `${out}/${tag}-C-confirmed.png` });
    // the scheduler sends the message again (e.g. second click): no new request, same state
    await fr.locator('#confirm').click(); await page.waitForTimeout(2000);
    log(`${tag} C repeated message`, { newLeads: leadCount() - n0, lead: lastLead() });
    await ctx.close();

    // D. Calendly connected, no token: never "confirmed"
    reset(); setBooking('calendly', 'http://127.0.0.1:8091/seohouse/30min', '');
    ({ ctx, page, box } = await open(browser, mobile)); n0 = leadCount();
    await fill(box, page, { email: `d-${tag}@example.com`, phone: '+20 100 123 4567' }); await submit(box, page);
    await page.waitForTimeout(1000);
    const frD = page.frameLocator('[data-bk-embed] iframe'); await frD.locator('[data-slot]').first().click(); await frD.locator('#confirm').click(); await page.waitForTimeout(2500);
    log(`${tag} D no token`, { ui: await ui(box), newLeads: leadCount() - n0, lead: lastLead() });
    await box.screenshot({ path: `${out}/${tag}-D-no-token.png` });
    await ctx.close();

    // F. API unreachable at booking time, then re-checked from the admin
    reset(); setBooking('calendly', 'http://127.0.0.1:8091/seohouse/30min', 'test-token'); wp('option update test_calendly_down 1');
    ({ ctx, page, box } = await open(browser, mobile)); n0 = leadCount();
    await fill(box, page, { email: `f-${tag}@example.com`, phone: '+971 50 123 4567' }); await submit(box, page); await page.waitForTimeout(1000);
    const frF = page.frameLocator('[data-bk-embed] iframe'); await frF.locator('[data-slot]').first().click(); await frF.locator('#confirm').click(); await page.waitForTimeout(2500);
    const fl = lastLead();
    log(`${tag} F API down`, { ui: await ui(box), newLeads: leadCount() - n0, lead: fl });
    wp('option delete test_calendly_down');
    log(`${tag} F admin re-check`, { res: ev(`echo wp_json_encode(sh_lead_booking_verify(${fl.id}), JSON_UNESCAPED_UNICODE);`), lead: lastLead() });
    await ctx.close();
  }
  await browser.close();
  fs.writeFileSync(out + '/booking.json', JSON.stringify(R, null, 1));
})();
