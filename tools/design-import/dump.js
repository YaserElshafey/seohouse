// node dump.js <key> [label] — rendered design markup without header/footer, repeated rows collapsed
const cheerio = require('cheerio');
const [key, label] = process.argv.slice(2);
const d = JSON.parse(require('fs').readFileSync(`extract/${key}.json`));
const $ = cheerio.load(d.html, null, false);
$('span.sc-interp').each((i, e) => $(e).replaceWith($(e).html()));
$('header[data-sh-header], footer, #sh-drawer').remove();
$('[data-dc-tpl]').each((i, e) => { const t = $(e).attr('data-dc-tpl'); let n = $(e).next(); let k = 0; while (n.length && n.attr('data-dc-tpl') === t) { const x = n.next(); if (k++ >= 0) n.remove(); n = x; } });
$('[data-dc-tpl]').removeAttr('data-dc-tpl');
let out = label ? $.html($(`section[data-screen-label="${label}"]`)) : $.html($.root().children().first().children('section'));
out = out.replace(/\n\s*\n+/g, '\n');
console.log(out);
