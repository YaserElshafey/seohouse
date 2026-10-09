const { chromium } = require('/opt/node-tools/node_modules/playwright');
(async () => { const b = await chromium.launch(); const p = await (await b.newContext({ viewport: { width: 1440, height: 1000 }, storageState: '/home/claude/wptest-fixture/admin-state.json' })).newPage();
await p.goto('http://127.0.0.1:8090/wp-admin/edit.php?post_type=sh_lead', { waitUntil: 'networkidle' }); await p.screenshot({ path: process.argv[2] }); await b.close(); })();
