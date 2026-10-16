// Hero mobile photo rows (test site only). Usage: node tracks.js <label> [widths...]
// For each width: who is in each row, overlap between rows, copy widths vs. visible row width,
// the two copies identical (seamless loop), and coverage sampled across the whole animation cycle.
const { chromium } = require('/opt/node-tools/node_modules/playwright');
const label = process.argv[2] || 'run';
const widths = (process.argv.slice(3).length ? process.argv.slice(3) : ['360', '390', '430', '768', '859']).map(Number);
(async () => {
  const b = await chromium.launch();
  let fail = 0;
  for (const w of widths) {
    const ctx = await b.newContext({ viewport: { width: w, height: 900 }, deviceScaleFactor: 1, isMobile: w < 600, hasTouch: w < 600, extraHTTPHeaders: process.env.SHS ? { 'X-SHS': process.env.SHS } : {} });
    const p = await ctx.newPage();
    await p.goto('http://127.0.0.1:8090/', { waitUntil: 'networkidle' });
    await p.evaluate(() => document.querySelectorAll('[data-mob-face]').forEach(i => { i.loading = 'eager'; }));
    await p.waitForFunction(() => [...document.querySelectorAll('[data-mob-face]')].every(i => i.complete), null, { timeout: 30000 });
    const r = await p.evaluate(() => {
      const tracks = [...document.querySelectorAll('[data-hero-mob] [data-mob-track]')];
      return tracks.map(t => {
        const imgs = [...t.querySelectorAll('img')];
        const box = t.parentElement.getBoundingClientRect();
        const anim = t.getAnimations()[0];
        const dur = anim ? anim.effect.getComputedTiming().duration : 0;
        const key = i => i.currentSrc.replace(/-\d+x\d+(?=\.\w+$)/, '');
        const n = imgs.length / 2;
        const copyA = imgs.slice(0, n).map(key), copyB = imgs.slice(n).map(key);
        // pause and sample the whole cycle: the track must always cover the visible row
        let gaps = 0, samples = 0;
        if (anim) {
          anim.pause();
          for (let k = 0; k <= 40; k++) {
            anim.currentTime = dur * k / 40;
            const tb = t.getBoundingClientRect();
            samples++;
            if (tb.left > box.left + 0.5 || tb.right < box.right - 0.5) gaps++;
          }
          // loop seam: frame at the very end equals the frame at the start shifted by one copy
          // (either direction: the end frame equals the start frame with the copies swapped)
          anim.currentTime = 0; const a0 = imgs[0].getBoundingClientRect().left, b0 = imgs[n].getBoundingClientRect().left;
          anim.currentTime = dur - 0.001; const a1 = imgs[0].getBoundingClientRect().left, b1 = imgs[n].getBoundingClientRect().left;
          var seam = Math.min(Math.abs(b1 - a0), Math.abs(a1 - b0));
          // direction on screen: where the first photo moves between two moments of the cycle
          anim.currentTime = dur * 0.2; const xa = imgs[0].getBoundingClientRect().left;
          anim.currentTime = dur * 0.3; const xb = imgs[0].getBoundingClientRect().left;
          var dir = xb > xa ? 'right' : (xb < xa ? 'left' : 'still');
          anim.play();
        }
        const copyW = imgs.slice(0, n).reduce((s, i) => s + i.getBoundingClientRect().width + parseFloat(getComputedStyle(i).marginInlineEnd), 0);
        return {
          faces: [...new Set(copyA)].map(s => s.split('/').pop()),
          alts: imgs.filter(i => i.alt).map(i => i.alt),
          photos: imgs.length, copyIdentical: JSON.stringify(copyA) === JSON.stringify(copyB),
          copyW: Math.round(copyW), trackW: Math.round(t.scrollWidth || t.getBoundingClientRect().width), rowW: Math.round(box.width),
          dur, gaps, samples, seam: Math.round((seam || 0) * 100) / 100, dir, playing: anim ? anim.playState : 'none',
        };
      });
    });
    const [a, c] = r;
    const overlap = a && c ? a.faces.filter(f => c.faces.includes(f)) : [];
    const ok = r.length === 2 && overlap.length === 0 && r[0].dir === 'right' && r[1].dir === 'left' && r.every(x => x.copyIdentical && x.gaps === 0 && x.copyW >= x.rowW && x.seam < 1);
    if (!ok) fail++;
    console.log(`${w}px  rows=${r.length}  overlap=${overlap.length} ${ok ? 'OK' : 'FAIL'}`);
    r.forEach((x, i) => console.log(`   row${i + 1}: ${x.faces.length} people, ${x.photos} imgs, copy ${x.copyW}px vs row ${x.rowW}px, identical copies=${x.copyIdentical}, gaps ${x.gaps}/${x.samples}, seam ${x.seam}px, moves ${x.dir}, ${x.playing}, names read: ${x.alts.length}`));
    await p.locator('[data-hero-mob]').screenshot({ path: `${label}-${w}.png` });
    await ctx.close();
  }
  await b.close();
  console.log(fail ? `FAIL (${fail})` : 'ALL OK');
})();
