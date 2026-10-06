const { chromium } = require('/opt/node-tools/node_modules/playwright');
(async () => {
  const b = await chromium.launch(); const page = await (await b.newContext({ viewport: { width: 1440, height: 900 } })).newPage();
  for (const [u, re] of [[process.argv[2], /test-upload-photo-1/], [process.argv[3], /test-upload-big-1/], [process.argv[4], /test-upload-big-1/], ['http://127.0.0.1:8090/team/mohamed-moawad/', /test-upload-big-2/], ['http://127.0.0.1:8090/team/', /test-upload-big-2/]]) {
    await page.goto(u, { waitUntil: 'networkidle' });
    await page.evaluate(async () => { for (let y = 0; y < document.body.scrollHeight; y += 600) { window.scrollTo(0, y); await new Promise(r => setTimeout(r, 80)); } });
    await page.waitForTimeout(800);
    const r = await page.evaluate(src => [...document.querySelectorAll('img')].filter(i => new RegExp(src).test(i.currentSrc || i.src)).map(i => ({ cur: (i.currentSrc || i.src).split('/').pop(), ok: i.complete && i.naturalWidth > 0, w: Math.round(i.getBoundingClientRect().width), srcset: (i.srcset || '').split(',').length })), re.source);
    console.log(u.replace('http://127.0.0.1:8090', ''), JSON.stringify(r));
  }
  await b.close();
})();
