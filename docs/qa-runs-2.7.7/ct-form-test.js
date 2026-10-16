const { chromium } = require('/opt/node-tools/node_modules/playwright');
(async()=>{const b=await chromium.launch();for (const w of [1440,390]) { const p=await (await b.newContext({viewport:{width:w,height:900}})).newPage();
await p.goto('http://127.0.0.1:8090/contact/',{waitUntil:'networkidle'});const f=p.locator('[data-ct-form]');
await f.locator('input[name=name]').fill('اختبار التواصل');await f.locator('input[name=company]').fill('شركة');await f.locator('input[name=email]').fill(`ct-${w}-${Date.now()}@example.com`);await f.locator('input[name=phone]').fill('01050861791');
for (const s of await f.locator('select').all()) await s.selectOption({index:1});await f.locator('textarea[name=goal]').fill('هدف تجريبي');
console.log(w,'button:',(await f.locator('button[type=submit]').innerText()).trim());
await p.waitForTimeout(3000);const resp=p.waitForResponse(r=>r.url().includes('/seohouse/v1/lead'));await f.locator('button[type=submit]').click();const r=await resp;console.log(w,'status',r.status(),JSON.stringify(await r.json()));
await p.waitForTimeout(800);const sent=p.locator('[data-ct-sent]');console.log(w,'success visible:',await sent.isVisible(),'|',(await sent.innerText()).replace(/\s+/g,' ').trim());
await sent.screenshot({path:`runs/r277/contact-success-${w}.png`});}
await b.close();})();
