// Real browser submissions as a logged-out visitor. Usage: node forms-test.js <base> <out.json> [scenario...]
// Scenarios: home, contact, home-after-contact (same email+service), double-click, stale-cache
const { chromium } = require('/opt/node-tools/node_modules/playwright');
const fs = require('fs');
const base = process.argv[2]; const out = process.argv[3];
const scen = process.argv.slice(4);
const results = [];
const email = s => `test-${s}-${Date.now()}@example.com`;

async function homeForm(page, contact, service = 'seo', clicks = 1) {
  await page.goto(base + '/', { waitUntil: 'networkidle' });
  const box = page.locator('[data-sh-booking]').first();
  await box.scrollIntoViewIfNeeded();
  await box.locator(`[data-bk-pick="${service}"]`).click();
  await box.locator('input[name="name"]').fill('اختبار الرئيسية');
  if (await box.locator('input[name="email"]').count()) { await box.locator('input[name="email"]').fill(contact); await box.locator('input[name="phone"]').fill('+20 100 123 4567'); }
  else await box.locator('input[name="contact"]').fill(contact);
  await page.waitForTimeout(3500); // time trap: >3 s after page generation
  const resps = [];
  page.on('response', r => { if (r.url().includes('/seohouse/v1/lead')) resps.push(r); });
  for (let i = 0; i < clicks; i++) await box.locator('button[type="submit"]').click({ noWaitAfter: true, force: true });
  await page.waitForTimeout(2500);
  const bodies = [];
  for (const r of resps) bodies.push({ status: r.status(), body: await r.json().catch(() => null) });
  const step2 = await box.locator('[data-bk-step2]').isVisible();
  const err = await box.locator('[data-bk-error]').isVisible() ? await box.locator('[data-bk-error]').textContent() : '';
  return { requests: bodies.length, responses: bodies, successShown: step2, error: err };
}
async function contactForm(page, mail, clicks = 1) {
  await page.goto(base + '/contact/', { waitUntil: 'networkidle' });
  const f = page.locator('[data-ct-form]');
  await f.locator('input[name="name"]').fill('اختبار التواصل');
  await f.locator('input[name="company"]').fill('شركة الاختبار');
  await f.locator('input[name="email"]').fill(mail);
  await f.locator('input[name="phone"]').fill('+201000000000');
  const sel = f.locator('select'); const n = await sel.count();
  for (let i = 0; i < n; i++) { const opts = await sel.nth(i).locator('option').all(); if (opts.length > 1) await sel.nth(i).selectOption({ index: 1 }); }
  await f.locator('textarea[name="goal"]').fill('هدف تجريبي');
  await page.waitForTimeout(3500);
  const resps = [];
  page.on('response', r => { if (r.url().includes('/seohouse/v1/lead')) resps.push(r); });
  for (let i = 0; i < clicks; i++) await f.locator('button[type="submit"]').click({ noWaitAfter: true, force: true });
  await page.waitForTimeout(2500);
  const bodies = [];
  for (const r of resps) bodies.push({ status: r.status(), body: await r.json().catch(() => null) });
  const sent = await page.locator('[data-ct-sent]').isVisible();
  const err = await page.locator('[data-ct-error]').isVisible() ? await page.locator('[data-ct-error-text]').textContent() : '';
  return { requests: bodies.length, responses: bodies, successShown: sent, error: err };
}

(async () => {
  const b = await chromium.launch();
  const ctx = await b.newContext({ viewport: { width: 1440, height: 900 } });
  for (const s of scen) {
    const page = await ctx.newPage();
    let r;
    if (s === 'home') r = await homeForm(page, email('home'));
    if (s === 'contact') r = await contactForm(page, email('contact'));
    if (s === 'home-after-contact') { const m = email('same'); const c = await contactForm(page, m); const p2 = await ctx.newPage(); r = { contact: c, home: await homeForm(p2, m, 'seo') }; }
    if (s === 'home-double') r = await homeForm(page, email('dbl'), 'seo', 3);
    if (s === 'contact-double') r = await contactForm(page, email('cdbl'), 3);
    if (s === 'stale-cache') {
      // a page served from cache: the hidden ts is from the time the page was generated (2 days ago)
      await page.route(base + '/', async route => { const resp = await route.fetch(); let body = await resp.text(); body = body.replace(/name="ts" value="\d+"/g, `name="ts" value="${Math.floor(Date.now() / 1000) - 2 * 86400}"`); route.fulfill({ response: resp, body }); });
      r = await homeForm(page, email('stale'));
    }
    results.push({ scenario: s, ...r });
    console.log(s, JSON.stringify(r));
    await page.close();
  }
  await b.close();
  fs.writeFileSync(out, JSON.stringify(results, null, 1));
})();
