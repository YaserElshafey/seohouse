const { chromium } = require('/opt/node-tools/node_modules/playwright');
const base = 'http://127.0.0.1:8090/wp-admin/', out = process.argv[2];
(async () => { const b = await chromium.launch(); const p = await (await b.newContext({ viewport: { width: 1440, height: 1100 }, storageState: '/home/claude/wptest-fixture/admin-state.json' })).newPage();
const menu = async () => p.evaluate(() => [...document.querySelectorAll('#toplevel_page_seohouse-settings .wp-submenu a')].map(a => a.innerText.trim()));
const notices = async () => p.evaluate(() => [...document.querySelectorAll('.notice')].map(n => n.innerText.trim().slice(0, 60)));
await p.goto(base + 'admin.php?page=seohouse-settings', { waitUntil: 'networkidle' });
console.log('menu (default):', JSON.stringify(await menu()));
console.log('notices:', JSON.stringify(await notices()));
console.log('tabs:', JSON.stringify(await p.evaluate(() => [...document.querySelectorAll('.acf-tab-wrap a')].map(a => a.innerText.trim()))));
await p.screenshot({ path: out + '/settings-default.png' });
await p.locator('.acf-tab-wrap a', { hasText: 'الاستشارة' }).click(); await p.waitForTimeout(400);
console.log('booking tab fields:', JSON.stringify(await p.evaluate(() => [...document.querySelectorAll('.acf-field')].filter(f => f.offsetParent).map(f => (f.querySelector(':scope > .acf-label label') || {}).innerText).filter(Boolean))));
await p.screenshot({ path: out + '/settings-booking-tab.png', fullPage: true });
await p.locator('.acf-tab-wrap a', { hasText: 'بيانات الشركة' }).click(); await p.waitForTimeout(400);
console.log('company tab fields:', JSON.stringify(await p.evaluate(() => [...document.querySelectorAll('.acf-field')].filter(f => f.offsetParent).map(f => (f.querySelector(':scope > .acf-label label') || {}).innerText).filter(Boolean))));
for (const s of ['seohouse-content-setup', 'seohouse-migrate-posts', 'seohouse-seo-transfer', 'seohouse-rankmath']) { const r = await p.goto(base + 'admin.php?page=' + s); console.log('open hidden', s, r.status(), (await p.locator('body').innerText()).slice(0, 60).replace(/\n/g, ' ')); }
// switch on
await p.goto(base + 'admin.php?page=seohouse-settings-tools', { waitUntil: 'networkidle' });
await p.locator('input[name=sh_show_tools]').scrollIntoViewIfNeeded(); await p.screenshot({ path: out + '/settings-tools-switch.png', fullPage: true });
await p.check('input[name=sh_show_tools]'); await p.click('button[name=sh_tools_visibility]'); await p.waitForLoadState('networkidle');
console.log('menu (tools on):', JSON.stringify(await menu()));
await p.uncheck('input[name=sh_show_tools]'); await p.click('button[name=sh_tools_visibility]'); await p.waitForLoadState('networkidle');
console.log('menu (tools off again):', JSON.stringify(await menu()));
// save settings form unchanged (hidden values must survive)
await p.goto(base + 'admin.php?page=seohouse-settings', { waitUntil: 'networkidle' });
await p.locator('#publish, input[type=submit].button-primary, button.button-primary').first().click(); await p.waitForLoadState('networkidle');
console.log('saved settings page; notices:', JSON.stringify(await notices()));
await b.close(); })();
