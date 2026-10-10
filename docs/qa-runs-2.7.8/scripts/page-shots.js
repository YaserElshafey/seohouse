// Full-page screenshots. Usage: node page-shots.js <outdir> <prefix> <path> [widths...]
const { chromium } = require('/opt/node-tools/node_modules/playwright');
const [out, prefix, path, ...ws] = process.argv.slice(2);
(async () => { const b = await chromium.launch();
for (const w of (ws.length ? ws : [1440, 768, 390, 360]).map(Number)) { const p = await (await b.newContext({ viewport: { width: w, height: 900 } })).newPage();
  await p.goto('http://127.0.0.1:8090' + path, { waitUntil: 'networkidle' });
  await p.evaluate(async () => { for (let y = 0; y < document.body.scrollHeight; y += 700) { scrollTo(0, y); await new Promise(r => setTimeout(r, 60)); } scrollTo(0, 0); });
  await p.screenshot({ path: `${out}/${prefix}-${w}.png`, fullPage: true });
  console.log(w, 'scrollX', await p.evaluate(() => document.documentElement.scrollWidth > innerWidth)); }
await b.close(); })();
