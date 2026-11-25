// 2.7.3 checks: sticky header, home form (one step, centred services, phone), contact phone. Usage: node ui-273.js <outdir>
const { chromium } = require('/opt/node-tools/node_modules/playwright');
const fs = require('fs');
const { execSync } = require('child_process');
const clearRate = () => execSync(`wp --allow-root --path=/home/claude/wptest eval 'global $wpdb; $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE \\"_transient%sh_lead_rl_%\\"");' 2>/dev/null`);
const base = 'http://127.0.0.1:8090', out = process.argv[2];
const R = {}; const log = (k, v) => { R[k] = v; console.log('##', k, JSON.stringify(v)); };
(async () => {
  const b = await chromium.launch();
  for (const admin of [false, true]) for (const w of [1440, 768, 390]) {
    const tag = `${admin ? 'admin' : 'visitor'}-${w}`;
    const ctx = await b.newContext({ viewport: { width: w, height: 860 }, ...(admin ? { storageState: '/home/claude/wptest-fixture/admin-state.json' } : {}), hasTouch: w === 390 });
    const page = await ctx.newPage();
    const reqs = []; page.on('request', r => reqs.push(r.url()));
    await page.goto(base + '/', { waitUntil: 'networkidle' });
    const hdr = page.locator('[data-sh-header]');
    const top0 = await page.evaluate(() => { const m = document.querySelector('main'); return { mainTop: Math.round(m.getBoundingClientRect().top + scrollY), docH: document.documentElement.scrollHeight }; });
    const samples = [];
    for (const y of [0, 300, 1200, 3000, 6000]) {
      await page.evaluate(y => window.scrollTo(0, y), y); await page.waitForTimeout(250);
      samples.push(await page.evaluate(() => { const h = document.querySelector('[data-sh-header]').getBoundingClientRect(); const ab = document.getElementById('wpadminbar'); const abr = ab ? ab.getBoundingClientRect() : null; return { y: Math.round(scrollY), top: Math.round(h.top), bottom: Math.round(h.bottom), adminBarBottom: abr && getComputedStyle(ab).position === 'fixed' ? Math.round(abr.bottom) : (abr ? 'scrolls:' + Math.round(abr.bottom) : null), mainTop: Math.round(document.querySelector('main').getBoundingClientRect().top + scrollY), docH: document.documentElement.scrollHeight, scrollX: document.documentElement.scrollWidth > innerWidth }; }));
    }
    await page.screenshot({ path: `${out}/${tag}-scrolled.png` });
    // anchor link to the booking section (header CTA or any #booking link)
    await page.evaluate(() => window.scrollTo(0, 0)); await page.waitForTimeout(200);
    const anchor = await page.evaluate(async () => {
      const a = document.querySelector('a[href$="#booking"]'); if (!a) return 'no #booking link';
      location.hash = ''; a.click(); await new Promise(r => setTimeout(r, 1500));
      const t = document.getElementById('booking').getBoundingClientRect().top, hb = document.querySelector('[data-sh-header]').getBoundingClientRect().bottom;
      return { sectionTop: Math.round(t), headerBottom: Math.round(hb), covered: t < hb - 1 };
    });
    // dropdown while scrolled (desktop/tablet)
    let dropdown = null;
    if (w >= 1100) {
      await page.evaluate(() => window.scrollTo(0, 2500)); await page.waitForTimeout(300);
      await page.locator('[data-sh-menu] > button[aria-controls]').first().click(); await page.waitForTimeout(400);
      dropdown = await page.evaluate(() => { const p = document.querySelector('[data-sh-panel]:not([hidden])'); if (!p) return 'not open'; const r = p.getBoundingClientRect(), h = document.querySelector('[data-sh-header]').getBoundingClientRect(); const x = r.left + r.width / 2, y = r.top + 40; const el = document.elementFromPoint(x, Math.min(y, innerHeight - 1)); return { top: Math.round(r.top), headerBottom: Math.round(h.bottom), inView: r.top >= 0 && r.left >= -1 && r.right <= innerWidth + 1, onTop: !!(el && p.contains(el)) }; });
      await page.screenshot({ path: `${out}/${tag}-dropdown.png` });
      await page.keyboard.press('Escape');
    }
    // drawer (burger) while scrolled
    let drawer = null;
    const opener = page.locator('[data-sh-drawer-open]');
    if (await opener.isVisible()) {
      await page.evaluate(() => window.scrollTo(0, 2500)); await page.waitForTimeout(300);
      await opener.click(); await page.waitForTimeout(400);
      drawer = await page.evaluate(() => { const d = document.getElementById('sh-drawer'); const r = d.getBoundingClientRect(); const c = d.querySelector('[data-sh-drawer-close]').getBoundingClientRect(); const el = document.elementFromPoint(c.left + c.width / 2, c.top + c.height / 2); return { visible: !d.hidden, top: Math.round(r.top), closeTop: Math.round(c.top), closeClickable: !!(el && el.closest('[data-sh-drawer-close]')) }; });
      await page.screenshot({ path: `${out}/${tag}-drawer.png` });
      // a link in the drawer to #booking closes it and lands below the header
      const dl = page.locator('#sh-drawer a[href*="#booking"]');
      if (await dl.count()) { await dl.first().click(); await page.waitForTimeout(1500); drawer.anchor = await page.evaluate(() => ({ drawerHidden: document.getElementById('sh-drawer').hidden, covered: document.getElementById('booking').getBoundingClientRect().top < document.querySelector('[data-sh-header]').getBoundingClientRect().bottom - 1 })); }
      else await page.locator('[data-sh-drawer-close]').click();
    }
    // home form
    await page.goto(base + '/', { waitUntil: 'networkidle' });
    const box = page.locator('[data-sh-booking]').first(); await box.scrollIntoViewIfNeeded(); await page.waitForTimeout(300);
    const form = await box.evaluate(b => {
      const fs = b.querySelector('[data-bk-services]'), lg = fs.querySelector('legend'), wrap = fs.querySelector('div'), fr = b.querySelector('form').getBoundingClientRect();
      const btns = [...fs.querySelectorAll('[data-bk-pick]')].map(x => x.getBoundingClientRect());
      const rows = {}; btns.forEach(r => { const k = Math.round(r.top); (rows[k] ||= []).push(r); });
      const wr = wrap.getBoundingClientRect();
      const rowGaps = Object.values(rows).map(rs => { const l = Math.min(...rs.map(r => r.left)), r = Math.max(...rs.map(r => r.right)); return Math.round((l - wr.left) - (wr.right - r)); });
      const lr = document.createRange(); lr.selectNodeContents(lg); const tr = lr.getBoundingClientRect();
      return { steps: !!b.querySelector('[data-bk-counter],[data-bk-bar],[data-bk-label]'), text: b.innerText.includes('الخطوة'), legendOffset: Math.round((tr.left + tr.width / 2) - (fr.left + fr.width / 2)), rows: Object.keys(rows).length, rowCenterOffsets: rowGaps, overflow: btns.some(r => r.left < fr.left - 1 || r.right > fr.right + 1), button: b.querySelector('button[type=submit]').innerText.trim(), phoneHint: b.innerText.includes('رمز الدولة') };
    });
    await box.screenshot({ path: `${out}/${tag}-form.png` });
    log(`${tag}`, { samples, top0, anchor, dropdown, drawer, form });
    if (!admin) {
      clearRate();
      // submit with a local number in Arabic digits
      await box.locator('[data-bk-pick="web"]').click();
      await box.locator('input[name=name]').fill('اختبار 2.7.3'); await box.locator('input[name=email]').fill(`f273-${w}@example.com`);
      await box.locator('input[name=phone]').fill('abc'); await page.waitForTimeout(3000);
      await box.locator('button[type=submit]').click(); await page.waitForTimeout(500);
      const bad = await box.locator('[data-bk-error]').innerText();
      await box.locator('input[name=phone]').fill('٠١٠٠ ١٢٣-٤٥٦٧');
      const lead = []; page.on('response', async r => { if (r.url().includes('/seohouse/v1/lead')) lead.push({ s: r.status(), j: await r.json().catch(() => null) }); });
      await box.locator('button[type=submit]').click(); await page.waitForTimeout(2500);
      const after = await box.evaluate(b => ({ formHidden: b.querySelector('form').hidden, done: b.querySelector('[data-bk-step2]').hidden ? null : b.querySelector('[data-bk-step2]').innerText.trim() }));
      await box.screenshot({ path: `${out}/${tag}-success.png` });
      log(`${tag} submit`, { badPhoneError: bad, lead, after, calendlyOrIframe: reqs.filter(u => /calendly|8091/.test(u)).length + await page.locator('iframe').count() });
    }
    await ctx.close();
  }
  await b.close();
  fs.writeFileSync(out + '/ui.json', JSON.stringify(R, null, 1));
})();
