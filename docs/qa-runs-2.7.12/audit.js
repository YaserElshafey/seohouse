// Card/background separation audit. Usage: node audit.js urls.txt out.json
// A "card": element with radius >= 8px, >= 140x70px, holding text, inside a section. Flags cards whose
// surface is within 1.12:1 of the surface behind it AND whose border is within 1.25:1 of that surface.
const fs = require('fs');
const { chromium } = require('/opt/node-tools/node_modules/playwright');
const urls = fs.readFileSync(process.argv[2], 'utf8').split('\n').filter(Boolean);
(async () => {
  const b = await chromium.launch(); const res = {};
  for (const w of [1440, 390]) {
    const ctx = await b.newContext({ viewport: { width: w, height: 900 }, reducedMotion: 'reduce' });
    const p = await ctx.newPage();
    for (const u of urls) {
      await p.goto(u, { waitUntil: 'networkidle', timeout: 90000 }).catch(() => {});
      const r = await p.evaluate(() => {
        const parse = c => { const m = c && c.match(/rgba?\(([^)]+)\)/); if (!m) return null; const q = m[1].split(',').map(Number); return { r: q[0], g: q[1], b: q[2], a: q.length > 3 ? q[3] : 1 }; };
        const lum = c => { const f = v => { v /= 255; return v <= 0.03928 ? v / 12.92 : Math.pow((v + 0.055) / 1.055, 2.4); }; return 0.2126 * f(c.r) + 0.7152 * f(c.g) + 0.0722 * f(c.b); };
        const ratio = (a, c) => { const x = lum(a), y = lum(c); return (Math.max(x, y) + 0.05) / (Math.min(x, y) + 0.05); };
        const mix = (t, o) => ({ r: t.r * t.a + o.r * (1 - t.a), g: t.g * t.a + o.g * (1 - t.a), b: t.b * t.a + o.b * (1 - t.a), a: 1 });
        const own = e => { const cs = getComputedStyle(e); let c = parse(cs.backgroundColor); if ((!c || c.a === 0) && cs.backgroundImage.includes('gradient')) { const m = cs.backgroundImage.match(/rgba?\([^)]+\)/g) || []; const cols = m.map(parse).filter(Boolean); if (cols.length) c = cols.reduce((s, x) => ({ r: s.r + x.r / cols.length, g: s.g + x.g / cols.length, b: s.b + x.b / cols.length, a: Math.max(s.a, x.a) }), { r: 0, g: 0, b: 0, a: 0 }); } return c && c.a > 0 ? c : null; };
        const eff = e => { const L = []; for (; e; e = e.parentElement) { const c = own(e); if (c) { L.push(c); if (c.a >= 1) break; } } let base = { r: 255, g: 255, b: 255, a: 1 }; for (let i = L.length - 1; i >= 0; i--) base = mix(L[i], base); return base; };
        const out = [];
        for (const el of document.querySelectorAll('main section *, section *')) {
          const cs = getComputedStyle(el); const rad = parseFloat(cs.borderTopLeftRadius) || parseFloat(cs.borderTopRightRadius);
          if (rad < 8 || cs.display === 'inline' || cs.visibility === 'hidden') continue;
          const bx = el.getBoundingClientRect(); if (bx.width < 140 || bx.height < 70 || bx.width > innerWidth * .95) continue;
          if (!el.innerText || el.innerText.trim().length < 8 || !el.querySelector('h2,h3,h4,p,span,strong,div')) continue;
          const mine = own(el); const bw = parseFloat(cs.borderTopWidth);
          if (!mine && !(bw > 0)) continue;
          const behind = eff(el.parentElement); const surf = eff(el);
          const bc = parse(cs.borderTopColor); const border = bw > 0 && bc ? mix(bc, surf) : surf;
          const sr = ratio(surf, behind), br = ratio(border, behind);
          if (sr < 1.12 && br < 1.25) {
            const sec = el.closest('section'); const lab = sec && (sec.dataset.screenLabel || sec.className) || '?';
            const t = el.querySelector('h2,h3,h4,strong') || el;
            out.push({ sec: lab, tag: el.tagName, txt: t.innerText.trim().slice(0, 40), surf: sr.toFixed(2), border: br.toFixed(2), bg: cs.backgroundColor, behind: `rgb(${behind.r|0},${behind.g|0},${behind.b|0})`, alpha: mine ? mine.a : 0 });
          }
        }
        return out;
      });
      if (r.length) res[`${w} ${u.replace('http://127.0.0.1:8090', '')}`] = r;
    }
    await ctx.close();
  }
  fs.writeFileSync(process.argv[3], JSON.stringify(res, null, 1)); await b.close();
  const sig = {}; for (const [k, v] of Object.entries(res)) for (const c of v) { const s = `${c.sec} | ${c.bg} on ${c.behind}`; (sig[s] = sig[s] || new Set()).add(k.split(' ')[1]); }
  for (const [s, set] of Object.entries(sig).sort((a, b) => b[1].size - a[1].size)) console.log(set.size, s, [...set].slice(0, 4).join(' '));
})();
