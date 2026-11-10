// 2.7.1 fix-round UI checks (logged-out). Usage: node ui-271.js <base> <outdir>
const { chromium } = require('/opt/node-tools/node_modules/playwright');
const fs = require('fs');
const base = process.argv[2], out = process.argv[3];
const R = {};
const heroPages = ['/privacy-policy/', '/terms/', '/about/', '/team/', '/sectors/', '/contact/', '/blog/', '/results/', '/category/seo/', '/?s=seo'];
(async () => {
  const b = await chromium.launch();
  for (const w of [1440, 768, 390]) {
    const ctx = await b.newContext({ viewport: { width: w, height: 900 } });
    const page = await ctx.newPage();
    // 1. hero centering
    for (const u of heroPages) {
      const resp = await page.goto(base + u, { waitUntil: 'networkidle' });
      const r = await page.evaluate(() => {
        const sec = document.querySelector('section[data-screen-label="Hero"]');
        if (!sec) return { hero: false };
        const h1 = sec.querySelector('h1'); const box = h1.parentElement;
        const p = [...box.children].find(e => e.tagName === 'P');
        const cx = el => { const r = el.getBoundingClientRect(); return Math.round(r.left + r.width / 2); };
        const vw = document.documentElement.clientWidth;
        const line = el => { if (!el) return null; const r = el.getBoundingClientRect(); const lh = parseFloat(getComputedStyle(el).lineHeight) || 1; return Math.round(r.height / lh); };
        const btn = box.querySelector('a[class*="hv"], a[href][style*="border-radius"]');
        return {
          hero: true, vw, scrollX: document.documentElement.scrollWidth > vw,
          h1: { center: cx(h1), align: getComputedStyle(h1).textAlign, lines: line(h1), ws: getComputedStyle(h1).whiteSpace, fs: getComputedStyle(h1).fontSize },
          p: p ? { center: cx(p), align: getComputedStyle(p).textAlign, lines: line(p), width: Math.round(p.getBoundingClientRect().width), ws: getComputedStyle(p).whiteSpace, fs: getComputedStyle(p).fontSize, br: p.querySelectorAll('br').length } : null,
          btnCenter: btn ? cx(btn.parentElement) : null,
          articleP: (() => { const a = document.querySelector('.sh-prose p, [data-legal-content] p, [data-lg-grid] p'); return a ? getComputedStyle(a).textAlign : null; })(),
        };
      });
      r.status = resp.status();
      (R.hero ??= {})[`${w}${u}`] = r;
      if (['/privacy-policy/', '/about/', '/blog/', '/terms/'].includes(u)) await page.screenshot({ path: `${out}/hero-${w}${u.replace(/\W+/g, '-')}.png` });
    }
    // 2. home: platforms + reviews link
    await page.goto(base + '/', { waitUntil: 'networkidle' });
    await page.locator('section[data-screen-label="Platforms"]').scrollIntoViewIfNeeded();
    await page.evaluate(() => Promise.all([...document.querySelectorAll('[data-plat-chip] img')].map(i => { i.loading = 'eager'; return i.complete ? 0 : new Promise(r => { i.onload = i.onerror = r; }); })));
    R[`home-${w}`] = await page.evaluate(() => {
      const chips = [...document.querySelectorAll('[data-plat-chip]')];
      const imgs = chips.map(c => c.querySelector('img')).filter(Boolean);
      const track = chips[0]?.parentElement;
      return {
        chips: chips.length,
        visibleText: chips.filter(c => c.querySelector('img') && c.innerText.trim() !== '').length,
        noName: chips.filter(c => !c.getAttribute('aria-label') && !c.innerText.trim()).length,
        links: chips.filter(c => c.tagName === 'A' && c.href).length,
        blank: chips.filter(c => c.target === '_blank' && c.rel.includes('noopener')).length,
        distorted: imgs.filter(i => { if (!i.naturalWidth) return true; const r = i.getBoundingClientRect(); const fit = getComputedStyle(i).objectFit; return fit !== 'contain' && Math.abs(r.width / r.height - i.naturalWidth / i.naturalHeight) > 0.05; }).length,
        clipped: imgs.filter(i => { const r = i.getBoundingClientRect(), c = i.parentElement.getBoundingClientRect(); return r.height > c.height + 1 || r.width > c.width + 1; }).length,
        sizes: imgs.slice(0, 11).map(i => { const r = i.getBoundingClientRect(); return `${i.parentElement.getAttribute('aria-label')}:${Math.round(r.width)}x${Math.round(r.height)}`; }),
        animation: track ? getComputedStyle(track).animationName + ' ' + getComputedStyle(track).animationPlayState : null,
        reviewsLink: (() => { const a = document.querySelector('[data-reviews-all]'); return a ? { href: a.href, target: a.target, text: a.innerText.trim(), afterWidget: !!document.querySelector('[data-test-trustindex]') && (document.querySelector('[data-test-trustindex]').compareDocumentPosition(a) & 4) > 0 } : null; })(),
      };
    });
    const plat = page.locator('section[data-screen-label="Platforms"]'); await plat.scrollIntoViewIfNeeded(); await page.waitForTimeout(400);
    await plat.screenshot({ path: `${out}/platforms-${w}.png` });
    const rev = page.locator('section[data-screen-label="Reviews"]'); await rev.scrollIntoViewIfNeeded();
    await rev.screenshot({ path: `${out}/reviews-${w}.png` });
    // 3. sector cards: dynamic edge hover (desktop/tablet pointer)
    if (w !== 390) {
      const cards = page.locator('section[data-screen-label="Sectors"] [data-hcard]');
      const n = await cards.count(); const res = [];
      for (let i = 0; i < Math.min(n, 3); i++) {
        const c = cards.nth(i); await c.scrollIntoViewIfNeeded(); await page.mouse.move(5, 5); await page.waitForTimeout(400);
        const bb = await c.boundingBox();
        const samples = []; let flips = 0, last = null;
        // sweep across the top edge, bottom edge and side edges in 1px steps, inside/outside
        const paths = [];
        for (let d = -4; d <= 4; d++) for (const x of [bb.x + bb.width * 0.3, bb.x + bb.width * 0.7]) paths.push([x, bb.y + d], [x, bb.y + bb.height + d]);
        for (let d = -4; d <= 4; d++) paths.push([bb.x + d, bb.y + bb.height / 2], [bb.x + bb.width + d, bb.y + bb.height / 2]);
        // and a slow ride along the top edge at y = top+1 (the old jitter zone)
        for (let x = bb.x + 4; x < bb.x + bb.width - 4; x += 6) paths.push([x, bb.y + 1]);
        for (const [x, y] of paths) {
          await page.mouse.move(x, y); await page.waitForTimeout(60);
          const s = await c.evaluate(el => { const r = el.getBoundingClientRect(); return { top: Math.round(r.top * 10) / 10, h: el.matches(':hover'), bg: getComputedStyle(el).backgroundColor, tf: getComputedStyle(el).transform }; });
          samples.push(s);
        }
        // hold still on the edge and watch for oscillation over 1.2 s
        await page.mouse.move(bb.x + bb.width / 2, bb.y + 1);
        for (let k = 0; k < 24; k++) { await page.waitForTimeout(50); const h = await c.evaluate(el => el.matches(':hover')); if (last !== null && h !== last) flips++; last = h; }
        const tops = new Set(samples.map(s => s.top));
        res.push({ card: i, samples: samples.length, distinctTops: [...tops], transforms: [...new Set(samples.map(s => s.tf))], hoverBgs: [...new Set(samples.filter(s => s.h).map(s => s.bg))], restBgs: [...new Set(samples.filter(s => !s.h).map(s => s.bg))], flipsWhileStill: flips });
      }
      R[`sectors-${w}`] = res;
      // short video-like strip: 6 frames while crossing the top edge
      const c0 = cards.nth(0), bb = await c0.boundingBox();
      for (let k = 0; k < 4; k++) { await page.mouse.move(bb.x + bb.width / 2, bb.y - 2 + k * 2); await page.waitForTimeout(250); await page.screenshot({ path: `${out}/sector-edge-${w}-${k}.png`, clip: { x: Math.max(0, bb.x - 20), y: Math.max(0, bb.y - 20), width: Math.min(bb.width + 40, w - Math.max(0, bb.x - 20)), height: bb.height + 40 } }); }
    }
    // 4. member article cards
    for (const u of ['/team/maha-ali/', '/team/mohamed-moawad/']) {
      await page.goto(base + u, { waitUntil: 'networkidle' });
      const cards = page.locator('[data-pf-article]'); const n = await cards.count(); const st = [];
      for (let i = 0; i < n; i++) {
        const c = cards.nth(i); await c.scrollIntoViewIfNeeded(); await page.mouse.move(2, 2); await page.waitForTimeout(350);
        const get = () => c.evaluate(el => { const t = el.children[0] || el; const d = el.children[1]; const cs = x => getComputedStyle(x); return { bg: cs(el).backgroundColor, title: cs(t).color, date: d ? cs(d).color : null, titleText: t.innerText.slice(0, 60), overflow: el.scrollWidth > el.clientWidth + 1 }; });
        const normal = await get();
        const bb = await c.boundingBox(); await page.mouse.move(bb.x + bb.width / 2, bb.y + bb.height / 2); await page.waitForTimeout(400);
        const hover = await get();
        if (i === 0) await c.screenshot({ path: `${out}/member-${w}${u.replace(/\W+/g, '-')}hover.png` });
        await page.mouse.move(2, 2); await page.waitForTimeout(300);
        await c.evaluate(el => (el.matches('a') ? el : el.querySelector('a')).focus({ focusVisible: true })); await page.keyboard.press('Shift'); await page.waitForTimeout(400);
        const focus = await get();
        st.push({ normal, hover, focus });
      }
      R[`member-${w}${u}`] = st;
      await page.mouse.move(2, 2); await page.waitForTimeout(300);
      const sec = page.locator('[data-pf-article]').first(); if (await sec.count()) { await sec.scrollIntoViewIfNeeded(); await page.screenshot({ path: `${out}/member-${w}${u.replace(/\W+/g, '-')}.png` }); }
    }
    await ctx.close();
  }
  await b.close();
  fs.writeFileSync(out + '/ui.json', JSON.stringify(R, null, 1));
  console.log('done');
})();
