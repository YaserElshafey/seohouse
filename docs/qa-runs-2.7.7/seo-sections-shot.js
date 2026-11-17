const { chromium } = require('/opt/node-tools/node_modules/playwright');
(async()=>{const b=await chromium.launch();for(const w of [1440,768,390,360]){const p=await (await b.newContext({viewport:{width:w,height:900}})).newPage();await p.goto('http://127.0.0.1:8090/services/seo/',{waitUntil:'networkidle'});
const labels=await p.evaluate(()=>[...document.querySelectorAll('section[data-screen-label]')].map(s=>s.getAttribute('data-screen-label')));
for (const lab of ['Layers','Process','Tools','FAQ']) { const s=p.locator(`section[data-screen-label="${lab}"]`).first(); if(!await s.count()){console.log(w,lab,'missing',labels.join(','));continue;}
  if(lab==='FAQ'){ const q=s.locator('button, summary').first(); if(await q.count()) await q.click().catch(()=>{}); const q5=s.locator('button, summary').nth(4); if(await q5.count()) await q5.click().catch(()=>{}); await p.waitForTimeout(300);}
  await s.scrollIntoViewIfNeeded(); await p.waitForTimeout(300); await s.screenshot({path:`runs/r277/seo-${lab}-${w}.png`});
  console.log(w,lab,JSON.stringify(await s.evaluate(e=>{const c=e.getBoundingClientRect();const over=[...e.querySelectorAll('p,span,div,h2,h3,li')].filter(x=>{const r=x.getBoundingClientRect();return r.width>0&&(r.right>c.right+1||r.left<c.left-1)}).length;return {h:Math.round(c.height),overflowEls:over}})));}
console.log(w,'scrollX',await p.evaluate(()=>document.documentElement.scrollWidth>innerWidth));}
await b.close();})();
