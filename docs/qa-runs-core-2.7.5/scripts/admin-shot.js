// Screenshot of the structured data check (test site only). Usage: node admin-shot.js <out.png> [scenario]
const { chromium } = require('/opt/node-tools/node_modules/playwright');
(async () => {
  const b = await chromium.launch();
  const ctx = await b.newContext({ storageState: '/home/claude/wptest-fixture/admin-state.json', viewport: { width: 1440, height: 1000 }, extraHTTPHeaders: process.argv[3] ? { 'X-SHS': process.argv[3] } : {} });
  const p = await ctx.newPage();
  await p.goto('http://127.0.0.1:8090/wp-admin/admin.php?page=seohouse-schema');
  await Promise.all([p.waitForNavigation({ timeout: 60000 }), p.click('text=افحص الآن')]);
  const h = p.locator('.wrap h1');
  const box = await h.boundingBox();
  await p.screenshot({ path: process.argv[2], fullPage: true, clip: { x: 150, y: box.y - 10, width: 1290, height: 900 } });
  console.log(await p.locator('.wrap table tbody tr').count(), 'rows;', await p.locator('.wrap ul li').count(), 'cases');
  await b.close();
})();
