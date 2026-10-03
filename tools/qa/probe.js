// node probe.js <url> <js-expression>  — evaluate an expression in a page (debug helper)
const path = require('path');
const { chromium } = require(require.resolve('playwright', { paths: [path.join(__dirname, '../design-import/node_modules')] }));
(async () => {
  const b = await chromium.launch(); const p = await b.newPage({ viewport: { width: +(process.env.W || 1440), height: 900 } });
  await p.goto(process.argv[2], { waitUntil: 'networkidle' });
  console.log(JSON.stringify(await p.evaluate(process.argv[3]), null, 1));
  await b.close();
})();
