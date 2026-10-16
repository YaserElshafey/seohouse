// Full-page screenshot of «فحص البيانات المنظمة» after «افحص الآن» (test site only). Usage: node admin-shot.js <out.png> [scenario]
const { chromium } = require('/opt/node-tools/node_modules/playwright');
(async () => {
  const b = await chromium.launch();
  const ctx = await b.newContext({ storageState: '/home/claude/wptest-fixture/admin-state.json', viewport: { width: 1440, height: 1000 }, extraHTTPHeaders: process.argv[3] ? { 'X-SHS': process.argv[3] } : {} });
  const p = await ctx.newPage();
  await p.goto('http://127.0.0.1:8090/wp-admin/admin.php?page=seohouse-schema');
  await Promise.all([p.waitForNavigation({ timeout: 120000 }), p.click('text=افحص الآن')]);
  await p.locator('#wpbody-content .wrap').screenshot({ path: process.argv[2] });
  console.log(await p.locator('.wrap table').count(), 'tables');
  await b.close();
})();
