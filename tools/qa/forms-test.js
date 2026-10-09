#!/usr/bin/env node
/**
 * Front-end form test (visitor, mobile width):
 *   booking form (shared section)  → validation message, real submission, step 2 shown;
 *   contact page form              → validation message, real submission, confirmation shown;
 *   honeypot filled                → rejected, nothing stored.
 * Stored requests are checked afterwards with WP-CLI (see the report section in docs).
 *
 * Usage: node forms-test.js --wp http://127.0.0.1:8080 [--booking /services/seo/technical/] [--out report.json]
 */
const path = require('path');
const fs = require('fs');
const { chromium } = require(require.resolve('playwright', { paths: [path.join(__dirname, '../design-import/node_modules'), __dirname] }));

const argv = process.argv.slice(2);
const opt = (n, d) => { const i = argv.indexOf('--' + n); return i >= 0 ? argv[i + 1] : d; };
const WP = opt('wp', 'http://127.0.0.1:8080').replace(/\/$/, '');
const results = [];
const check = (name, ok, detail = '') => { results.push({ name, ok: !!ok, detail }); console.log(`${ok ? 'PASS' : 'FAIL'}  ${name}${detail ? '  — ' + detail : ''}`); };
const stamp = Date.now().toString(36);

(async () => {
  const browser = await chromium.launch();
  const page = await browser.newPage({ viewport: { width: 390, height: 900 } });
  const errors = [];
  page.on('pageerror', e => errors.push(e.message));

  // booking form
  await page.goto(WP + opt('booking', '/services/seo/technical/'), { waitUntil: 'networkidle' });
  const bk = page.locator('[data-sh-booking]').first();
  await bk.locator('[data-bk-form] button[type=submit]').click();
  check('booking: validation message without data', (await bk.locator('[data-bk-error]').textContent()).trim().length > 0, (await bk.locator('[data-bk-error]').textContent()).trim());
  await bk.locator('[data-bk-pick]').first().click();
  await bk.locator('input[name=name]').fill('اختبار الحجز ' + stamp);
  await bk.locator('input[name=contact]').fill(`booking-${stamp}@example.com`);
  await page.waitForTimeout(3500); // minimum fill time (spam check)
  await bk.locator('[data-bk-form] button[type=submit]').click();
  await page.waitForTimeout(2000);
  check('booking: submitted, step 2 shown', await bk.locator('[data-bk-step2]').isVisible());

  // contact form
  await page.goto(WP + '/contact/', { waitUntil: 'networkidle' });
  const ct = page.locator('[data-ct-form]');
  await ct.locator('button[type=submit]').click();
  check('contact: validation message without data', (await page.textContent('[data-ct-error-text]')).trim().length > 0, (await page.textContent('[data-ct-error-text]')).trim());
  await ct.locator('[name=name]').fill('اختبار التواصل ' + stamp);
  await ct.locator('[name=company]').fill('شركة اختبار');
  await ct.locator('[name=email]').fill(`contact-${stamp}@example.com`);
  await ct.locator('[name=phone]').fill('+966500000000');
  await ct.locator('[name=service]').selectOption('web');
  await ct.locator('[name=goal]').fill('هدف تجريبي');
  await page.waitForTimeout(3500);
  await ct.locator('button[type=submit]').click();
  await page.waitForTimeout(2000);
  check('contact: submitted, confirmation shown', await page.isVisible('[data-ct-sent]'), await page.textContent('[data-ct-summary]'));

  // honeypot
  const res = await page.evaluate(async (rest) => {
    const fd = new FormData();
    fd.set('service', 'seo'); fd.set('name', 'bot'); fd.set('contact', 'bot@example.com'); fd.set('company_website', 'http://spam.example'); fd.set('ts', String(Math.floor(Date.now() / 1000) - 60));
    const r = await fetch(rest + 'lead', { method: 'POST', body: fd });
    return { status: r.status, body: await r.json() };
  }, WP + '/wp-json/seohouse/v1/');
  check('honeypot: request rejected', res.status === 422 && res.body.ok === false, `${res.status} ${res.body.code}`);
  check('no JavaScript errors', errors.length === 0, errors.slice(0, 2).join(' | '));

  await browser.close();
  const failed = results.filter(r => !r.ok).length;
  if (opt('out', '')) fs.writeFileSync(opt('out'), JSON.stringify({ date: new Date().toISOString(), stamp, passed: results.length - failed, failed, results }, null, 2));
  console.log(`\n${results.length - failed}/${results.length} passed (stamp ${stamp})`);
  process.exit(failed ? 1 : 0);
})().catch(e => { console.error(e); process.exit(2); });
