#!/usr/bin/env node
// Full-page screenshots of a review install (desktop 1440 + mobile 390), drafts via logged-in preview.
// Usage: node review-shots.js --wp http://127.0.0.1:8095 --out review/screens
const path = require('path'), fs = require('fs');
const { chromium } = require(require.resolve('playwright', { paths: [path.join(__dirname, '../design-import/node_modules')] }));
const argv = process.argv.slice(2), opt = (n, d) => { const i = argv.indexOf('--' + n); return i >= 0 ? argv[i + 1] : d; };
const WP = opt('wp'), OUT = opt('out'); fs.mkdirSync(OUT, { recursive: true });
const pages = JSON.parse(opt('pages', '[]'));
(async () => {
  const b = await chromium.launch();
  for (const [w, tag] of [[1440, 'desktop'], [390, 'mobile']]) {
    const ctx = await b.newContext({ viewport: { width: w, height: 900 } });
    const p = await ctx.newPage();
    await p.goto(WP + '/wp-login.php'); await p.fill('#user_login', 'admin'); await p.fill('#user_pass', 'admin');
    await Promise.all([p.waitForNavigation(), p.click('#wp-submit')]);
    for (const [name, url, logged] of pages) {
      const page = logged ? p : await (await b.newContext({ viewport: { width: w, height: 900 } })).newPage();
      await page.goto(WP + url, { waitUntil: 'networkidle' });
      await page.addStyleTag({ content: '#wpadminbar{display:none!important}html{margin-top:0!important}*{animation-play-state:paused!important}' });
      await page.evaluate(async () => { for (let y = 0; y < document.body.scrollHeight; y += 700) { window.scrollTo(0, y); await new Promise(r => setTimeout(r, 60)); } window.scrollTo(0, 0); });
      await page.waitForTimeout(400);
      await page.screenshot({ path: path.join(OUT, `${name}-${tag}.jpg`), fullPage: true, type: 'jpeg', quality: 70 });
      console.log(name, tag, page.url().replace(WP, ''));
    }
    await ctx.close();
  }
  await b.close();
})();
