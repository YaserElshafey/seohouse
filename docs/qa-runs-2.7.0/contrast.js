// Contrast audit of the local test site: every visible text node vs. its effective background.
// Usage: node contrast.js <urls.json> <out.json> [shotsDir]
const fs = require('fs');
const { chromium } = require('/opt/node-tools/node_modules/playwright');
const urls = JSON.parse(fs.readFileSync(process.argv[2]));
const out = process.argv[3];
const shots = process.argv[4];

(async () => {
  const browser = await chromium.launch();
  const res = {};
  for (const [w, h] of [[1440, 900], [390, 844]]) {
    const ctx = await browser.newContext({ viewport: { width: w, height: h }, reducedMotion: 'reduce' });
    for (const u of urls) {
      const page = await ctx.newPage();
      await page.goto(u, { waitUntil: 'networkidle', timeout: 90000 }).catch(() => {});
      await page.waitForTimeout(400);
      const r = await page.evaluate(() => {
        const parse = c => { const m = c.match(/rgba?\(([^)]+)\)/); if (!m) return null; const p = m[1].split(',').map(Number); return { r: p[0], g: p[1], b: p[2], a: p.length > 3 ? p[3] : 1 }; };
        const lum = c => { const f = v => { v /= 255; return v <= 0.03928 ? v / 12.92 : Math.pow((v + 0.055) / 1.055, 2.4); }; return 0.2126 * f(c.r) + 0.7152 * f(c.g) + 0.0722 * f(c.b); };
        const mix = (top, bot) => ({ r: top.r * top.a + bot.r * (1 - top.a), g: top.g * top.a + bot.g * (1 - top.a), b: top.b * top.a + bot.b * (1 - top.a), a: 1 });
        const bgOf = el => {
          const layers = [];
          for (let e = el; e; e = e.parentElement) {
            const cs = getComputedStyle(e);
            if (cs.backgroundImage && cs.backgroundImage.includes('gradient') && !/(^|\s|,)0(px|%)?(\s|,|$)/.test(cs.backgroundSize)) {
              const m = cs.backgroundImage.match(/rgba?\([^)]+\)/g) || [];
              const cols = m.map(parse).filter(Boolean);
              if (cols.length) { const avg = cols.reduce((s, c) => ({ r: s.r + c.r / cols.length, g: s.g + c.g / cols.length, b: s.b + c.b / cols.length, a: Math.max(s.a, c.a) }), { r: 0, g: 0, b: 0, a: 0 }); layers.push(avg); if (avg.a >= 1) break; }
            }
            const c = parse(cs.backgroundColor);
            if (c && c.a > 0) { layers.push(c); if (c.a >= 1) break; }
          }
          let base = { r: 255, g: 255, b: 255, a: 1 };
          for (let i = layers.length - 1; i >= 0; i--) base = mix(layers[i], base);
          return base;
        };
        const bad = [];
        const walker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT);
        const seen = new Set();
        while (walker.nextNode()) {
          const t = walker.currentNode; const el = t.parentElement;
          if (!el || seen.has(el) || !t.textContent.trim()) continue;
          seen.add(el);
          const cs = getComputedStyle(el);
          const rect = el.getBoundingClientRect();
          if (cs.visibility === 'hidden' || cs.display === 'none' || +cs.opacity === 0 || rect.width < 2 || rect.height < 2) continue;
          if (el.closest('[aria-hidden="true"],[hidden],.screen-reader-text,script,style,noscript')) continue;
          let hiddenAncestor = false; for (let e = el; e; e = e.parentElement) { const s = getComputedStyle(e); if (s.display === 'none' || s.visibility === 'hidden' || +s.opacity === 0) { hiddenAncestor = true; break; } }
          if (hiddenAncestor) continue;
          const fg = parse(cs.color); if (!fg) continue;
          const bg = bgOf(el); const f = mix(fg, bg);
          const L1 = lum(f), L2 = lum(bg); const ratio = (Math.max(L1, L2) + 0.05) / (Math.min(L1, L2) + 0.05);
          const large = parseFloat(cs.fontSize) >= 24 || (parseFloat(cs.fontSize) >= 18.66 && +cs.fontWeight >= 700);
          if (ratio < (large ? 3 : 3)) bad.push({ text: t.textContent.trim().slice(0, 50), ratio: +ratio.toFixed(2), fg: cs.color, bg: `rgb(${bg.r | 0}, ${bg.g | 0}, ${bg.b | 0})`, size: cs.fontSize, style: (el.getAttribute('style') || '').slice(0, 160), anchor: (() => { for (let e = el; e; e = e.parentElement) { const a = [...e.attributes].map(x => x.name).filter(n => n.startsWith('data-') && !['data-screen-label', 'data-dc-tpl'].includes(n)); if (a.length) return e.tagName + '[' + a.join('][') + ']'; } return ''; })(), sec: (el.closest('section,header,footer,nav') || {}).getAttribute ? (el.closest('section,header,footer,nav').getAttribute('data-screen-label') || el.closest('section,header,footer,nav').tagName) : '' });
        }
        const ow = document.documentElement.scrollWidth > window.innerWidth + 1;
        return { bad, overflowX: ow, h1: document.querySelectorAll('h1').length };
      });
      res[`${w} ${u}`] = r;
      if (shots) { const name = u.replace(/^https?:\/\/[^/]+\//, '').replace(/[^a-z0-9]+/gi, '-').replace(/^-|-$/g, '') || 'home'; await page.screenshot({ path: `${shots}/${name}-${w}.jpg`, fullPage: true, type: 'jpeg', quality: 55 }); }
      await page.close();
    }
    await ctx.close();
  }
  await browser.close();
  fs.writeFileSync(out, JSON.stringify(res, null, 1));
  let n = 0;
  for (const [k, v] of Object.entries(res)) { if (v.bad.length || v.overflowX || v.h1 !== 1) { n++; console.log(k, `low-contrast:${v.bad.length} overflowX:${v.overflowX} h1:${v.h1}`); for (const b of v.bad.slice(0, 6)) console.log('    ', b.ratio, b.sec, '|', b.text, '|', b.fg, 'on', b.bg, b.size); } }
  console.log(`${Object.keys(res).length} page renders, ${n} with findings`);
})();
