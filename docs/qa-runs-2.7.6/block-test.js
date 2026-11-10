// «خدماتنا في الأسواق العربية» layout checks. Usage: node block-test.js <outdir>
const { chromium } = require('/opt/node-tools/node_modules/playwright');
const out = process.argv[2];
const pages = { ksa: '/services/seo/ksa/', egypt: '/services/seo/egypt/', uae: '/services/seo/uae/' };
(async () => {
  const b = await chromium.launch();
  for (const w of [1440, 1280, 1180, 1179, 1024, 768, 390, 360]) for (const [k, u] of Object.entries(pages)) {
    const ctx = await b.newContext({ viewport: { width: w, height: 900 } }); const p = await ctx.newPage();
    await p.goto('http://127.0.0.1:8090' + u, { waitUntil: 'networkidle' });
    const sec = p.locator('.sh-other-markets'); await sec.scrollIntoViewIfNeeded(); await p.mouse.move(1, 1);
    const r = await sec.evaluate(s => {
      const R = e => e.getBoundingClientRect(), t = s.querySelector('.sh-other-markets__title'), inner = s.querySelector('.sh-other-markets__inner');
      const tr = document.createRange(); tr.selectNodeContents(t); const trr = tr.getBoundingClientRect(), ir = R(inner);
      const cards = [...s.querySelectorAll('.sh-other-markets__card')].map(c => {
        const l = c.querySelector('.sh-other-markets__label'), cr = R(c), cs = getComputedStyle(l), rg = document.createRange(); rg.selectNodeContents(l); const lr = rg.getBoundingClientRect();
        return { text: l.textContent, href: c.getAttribute('href').replace(location.origin, ''), lines: Math.round(R(l).height / parseFloat(cs.lineHeight)), fs: cs.fontSize, fw: cs.fontWeight, h: Math.round(cr.height), w: Math.round(cr.width), dx: Math.round((lr.left + lr.width / 2) - (cr.left + cr.width / 2)), dy: Math.round((lr.top + lr.height / 2) - (cr.top + 2 + (cr.height - 2) / 2)), overflow: l.scrollWidth > l.clientWidth + 1 || lr.right > cr.right || lr.left < cr.left, top: Math.round(cr.top) };
      });
      return { titleDx: Math.round((trr.left + trr.width / 2) - (ir.left + ir.width / 2)), titleFs: getComputedStyle(t).fontSize, titleFw: getComputedStyle(t).fontWeight, cols: new Set(cards.map(c => c.top)).size === 1 ? cards.length : (cards.length / new Set(cards.map(c => c.top)).size), rows: new Set(cards.map(c => c.top)).size, cards, scrollX: document.documentElement.scrollWidth > innerWidth };
    });
    console.log(`${w} ${k} title dx=${r.titleDx} ${r.titleFs}/${r.titleFw} rows=${r.rows} scrollX=${r.scrollX} | ` + r.cards.map(c => `${c.text}→${c.href} lines=${c.lines} ${c.fs}/${c.fw} ${c.w}x${c.h} dx=${c.dx} dy=${c.dy}${c.overflow ? ' OVERFLOW' : ''}`).join(' ; '));
    if (k === 'egypt' && [1440, 768, 390, 360].includes(w)) {
      await sec.screenshot({ path: `${out}/egypt-${w}.png` });
      if (w === 1440 || w === 390) { // hover (desktop) and focus (keyboard) states
        if (w === 1440) { await p.locator('.sh-other-markets__card').first().hover(); await p.waitForTimeout(400); await sec.screenshot({ path: `${out}/egypt-${w}-hover.png` });
          console.log('   hover:', await p.locator('.sh-other-markets__card').first().evaluate(c => getComputedStyle(c).backgroundColor + ' / ' + getComputedStyle(c.querySelector('.sh-other-markets__label')).color)); }
      }
    }
    if (k === 'egypt' && w === 390) { // enlarged text (200%): may wrap, must not overflow
      await p.addStyleTag({ content: '.sh-other-markets__label{font-size:34px !important}' }); await p.waitForTimeout(200);
      const z = await sec.evaluate(s => [...s.querySelectorAll('.sh-other-markets__label')].map(l => { const c = l.parentElement.getBoundingClientRect(), r = document.createRange(); r.selectNodeContents(l); const lr = r.getBoundingClientRect(); return { lines: Math.round(l.getBoundingClientRect().height / parseFloat(getComputedStyle(l).lineHeight)), inside: lr.left >= c.left && lr.right <= c.right }; }));
      console.log('   enlarged text 34px @390:', JSON.stringify(z), 'scrollX', await p.evaluate(() => document.documentElement.scrollWidth > innerWidth));
      await sec.screenshot({ path: `${out}/egypt-390-enlarged-text.png` });
    }
    await ctx.close();
  }
  await b.close();
})();
