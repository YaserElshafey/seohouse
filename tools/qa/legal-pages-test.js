#!/usr/bin/env node
/**
 * Privacy policy and terms after the 2.5.0 content update: still drafts, each section shows its
 * heading and body (paragraphs and lists), no placeholder left, the contents list links to every
 * section, the update date is shown; desktop and phone screenshots of the logged-in preview.
 *
 * Usage: node legal-pages-test.js --wp http://127.0.0.1:8096/new --new-path /srv/shwpseo/new --out dir
 */
const path = require('path');
const fs = require('fs');
const { execFileSync } = require('child_process');
const { chromium } = require(require.resolve('playwright', { paths: [path.join(__dirname, '../design-import/node_modules')] }));
const argv = process.argv.slice(2), opt = (n, d) => { const i = argv.indexOf('--' + n); return i >= 0 ? argv[i + 1] : d; };
const WP = opt('wp').replace(/\/$/, ''), OUT = opt('out'), NEWP = opt('new-path');
fs.mkdirSync(OUT, { recursive: true });
const results = [];
const check = (name, ok, detail = '') => { results.push({ name, ok: !!ok, detail: String(detail).slice(0, 300) }); console.log(`${ok ? 'PASS' : 'FAIL'}  ${name}${detail ? '  — ' + String(detail).slice(0, 200) : ''}`); };
const wp = code => execFileSync('wp', ['--allow-root', 'eval', code], { cwd: NEWP, encoding: 'utf8' }).trim();
const md = f => fs.readFileSync(path.join(__dirname, '../../docs/legal', f), 'utf8');

(async () => {
  const b = await chromium.launch();
  const ctx = await b.newContext({ viewport: { width: 1400, height: 1000 } });
  const p = await ctx.newPage();
  const errors = [];
  p.on('pageerror', e => errors.push(e.message));
  await p.goto(WP + '/wp-login.php');
  await p.fill('#user_login', 'admin'); await p.fill('#user_pass', 'admin');
  await Promise.all([p.waitForNavigation(), p.click('#wp-submit')]);
  for (const [key, file, n] of [['privacy-policy', 'privacy-policy-ar.md', 8], ['terms', 'terms-ar.md', 9]]) {
    const id = wp(`echo get_page_by_path('${key}')->ID;`);
    check(`${key}: still a draft`, wp(`echo get_post_status(${id});`) === 'draft');
    await p.goto(`${WP}/?page_id=${id}&preview=true`);
    const main = p.locator('section[data-screen-label="Document"]');
    const heads = await main.locator('h2').allTextContents();
    check(`${key}: ${n} sections with a heading`, heads.length === n, heads.map(h => h.trim()).join(' | '));
    const bodies = await main.locator('h2 + div').count();
    check(`${key}: every section has its body text`, bodies === n, `${bodies}`);
    const text = (await main.textContent()).replace(/\s+/g, ' ');
    check(`${key}: no "[يُضاف النص القانوني…]" placeholder left`, !/يُضاف النص القانوني/.test(text));
    const src = md(file);
    const sample = src.split('\n').find(l => l.startsWith('- ')).slice(2, 60);
    check(`${key}: lists rendered as <ul>`, (await main.locator('ul li').count()) > 3 && text.includes(sample.replace(/\s+/g, ' ').trim().slice(0, 40)), sample);
    const upd = (src.match(/^updated: (.+)$/m) || [])[1];
    check(`${key}: «آخر تحديث: ${upd}» shown`, text.includes('آخر تحديث: ' + upd));
    const toc = await main.locator('nav a').evaluateAll(as => as.map(a => a.getAttribute('href')));
    const ids = await main.locator('h2').evaluateAll(hs => hs.map(h => '#' + h.parentElement.id));
    check(`${key}: contents list links to each section`, JSON.stringify(toc) === JSON.stringify(ids), toc.join(' '));
    const hero = ((src.split('---\n')[2] || '').split('\n## ')[0] || '').trim().slice(0, 50);
    check(`${key}: hero intro is the new text`, (await p.textContent('main, body')).replace(/\s+/g, ' ').includes(hero.replace(/\s+/g, ' ')), hero);
    await p.screenshot({ path: path.join(OUT, `${key}-desktop.png`), fullPage: true });
    const m = await ctx.newPage(); await m.setViewportSize({ width: 390, height: 844 });
    await m.goto(`${WP}/?page_id=${id}&preview=true`);
    const overflow = await m.evaluate(() => document.documentElement.scrollWidth - window.innerWidth);
    check(`${key}: phone width, no horizontal scroll`, overflow <= 1, `${overflow}px`);
    await m.screenshot({ path: path.join(OUT, `${key}-phone.png`), fullPage: true });
    await m.close();
  }
  // the editor sees the new fields
  const pid = wp(`echo get_page_by_path('privacy-policy')->ID;`);
  await p.goto(`${WP}/wp-admin/post.php?post=${pid}&action=edit`);
  await p.waitForSelector('.acf-field[data-name="items_2"]', { state: 'attached', timeout: 60000 });
  const bodyFields = await p.locator('.acf-field[data-name="items_2"] .sh-rows__row:not(.acf-clone) .acf-field[data-name="body"] textarea').count();
  check('editor: «نص القسم» textarea for each section, «تاريخ آخر تحديث» field', bodyFields === 8 && await p.locator('.acf-field[data-name="updated"] input').count() === 1, `${bodyFields} textareas`);
  check('no JavaScript errors', errors.length === 0, errors.slice(0, 2).join(' | '));
  await b.close();
  fs.writeFileSync(path.join(OUT, 'legal-pages.json'), JSON.stringify({ date: new Date().toISOString(), results }, null, 2));
  const failed = results.filter(r => !r.ok).length;
  console.log(`\n${results.length - failed}/${results.length} passed`);
  process.exit(failed ? 1 : 0);
})().catch(e => { console.error(e); process.exit(2); });
