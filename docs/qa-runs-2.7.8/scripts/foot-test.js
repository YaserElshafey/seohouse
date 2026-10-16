const { chromium } = require('/opt/node-tools/node_modules/playwright');
(async()=>{const b=await chromium.launch();
for (const [w,path] of [[1440,'/'],[768,'/about/'],[390,'/contact/'],[360,'/services/seo/']]) { const ctx=await b.newContext({viewport:{width:w,height:900}}); const p=await ctx.newPage(); await p.goto('http://127.0.0.1:8090'+path,{waitUntil:'networkidle'});
  const f=p.locator('footer'); await f.scrollIntoViewIfNeeded(); await p.mouse.move(1,1); await p.waitForTimeout(300);
  const r=await p.evaluate(()=>{const ul=document.querySelector('.sh-foot-social'); if(!ul) return 'no icons'; const col=ul.parentElement.getBoundingClientRect(), ur=ul.getBoundingClientRect(); const about=ul.previousElementSibling;
    return {links:[...ul.querySelectorAll('a')].map(a=>({href:a.href,target:a.target,rel:a.rel,label:a.getAttribute('aria-label'),box:Math.round(a.getBoundingClientRect().width)+'x'+Math.round(a.getBoundingClientRect().height),icon:Math.round(a.querySelector('svg').getBoundingClientRect().width),color:getComputedStyle(a).color})), afterAbout: about && about.tagName, rowCenterOffset: Math.round((ur.left+ur.width/2)-(col.left+col.width/2)), justify:getComputedStyle(ul).justifyContent, textLinksLeft:[...document.querySelectorAll('footer a')].filter(a=>/^(فيسبوك|إنستجرام|لينكدإن)$/.test(a.textContent.trim())).length};});
  console.log(w,path,JSON.stringify(r));
  const a=p.locator('.sh-foot-social a').first(); const bb0=await a.boundingBox(); await a.hover(); await p.waitForTimeout(300); const bb1=await a.boundingBox();
  console.log('   hover moved:',bb0.x!==bb1.x||bb0.y!==bb1.y||bb0.width!==bb1.width,'bg:',await a.evaluate(e=>getComputedStyle(e).backgroundColor));
  await f.screenshot({path:`runs/r278/footer-${w}-hover.png`});
  await p.mouse.move(1,1); await p.keyboard.press('Tab'); await a.focus(); await p.keyboard.press('Shift'); await p.waitForTimeout(200);
  console.log('   focus outline:',await a.evaluate(e=>getComputedStyle(e).outlineStyle+' '+getComputedStyle(e).outlineColor));
  await p.locator('.sh-foot-social').screenshot({path:`runs/r278/footer-icons-${w}-focus.png`});
  await p.mouse.move(1,1); await a.evaluate(e=>e.blur()); await p.waitForTimeout(200); await f.screenshot({path:`runs/r278/footer-${w}.png`}); await ctx.close(); }
await b.close();})();
