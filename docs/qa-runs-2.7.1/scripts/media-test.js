const { chromium } = require('/opt/node-tools/node_modules/playwright');
const fs = require('fs');
(async () => {
  const b = await chromium.launch();
  const ctx = await b.newContext({ storageState: '/home/claude/wptest-fixture/admin-state.json' });
  const page = await ctx.newPage();
  await page.goto('http://127.0.0.1:8090/wp-admin/upload.php', { waitUntil: 'networkidle' });
  const nonce = await page.evaluate(() => window.wpApiSettings?.nonce || (window.wp?.apiFetch?.nonceMiddleware?.nonce));
  const out = [];
  for (const f of process.argv.slice(2)) {
    const t0 = Date.now();
    const r = await page.request.post('http://127.0.0.1:8090/wp-json/wp/v2/media', { headers: { 'X-WP-Nonce': nonce }, multipart: { file: { name: f.split('/').pop(), mimeType: f.endsWith('png') ? 'image/png' : 'image/jpeg', buffer: fs.readFileSync(f) } }, timeout: 300000 });
    const j = await r.json().catch(() => ({}));
    out.push({ file: f.split('/').pop(), status: r.status(), ms: Date.now() - t0, id: j.id, mime: j.mime_type, src: j.source_url, sizes: Object.fromEntries(Object.entries(j.media_details?.sizes || {}).map(([k, v]) => [k, v.source_url.split('/').pop()])), err: j.message });
  }
  // the media grid as an editor sees it
  await page.goto('http://127.0.0.1:8090/wp-admin/upload.php?mode=grid', { waitUntil: 'networkidle' }); await page.waitForTimeout(1500);
  const thumbs = await page.evaluate(() => [...document.querySelectorAll('.attachments .attachment img')].slice(0, 6).map(i => ({ src: i.src.split('/').pop(), ok: i.complete && i.naturalWidth > 0 })));
  await page.screenshot({ path: '/home/claude/wptest-fixture/runs/r271-ui/media-grid.png' });
  console.log(JSON.stringify({ out, thumbs }, null, 1));
  await b.close();
})();
