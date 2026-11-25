/**
 * Read top-level `const NAME = [...]` / `{...}` literals from a design file's data script.
 * Used only for widgets whose inactive tabs are not present in the rendered DOM.
 */
const fs = require('fs');

function readConsts(file, names) {
  const src = fs.readFileSync(file, 'utf8');
  const s = src.slice(src.indexOf('data-dc-script'));
  const out = {};
  for (const n of names) {
    const i = s.indexOf('const ' + n + ' =');
    if (i < 0) continue;
    const j = s.indexOf('=', i) + 1;
    let depth = 0, k = j, started = false, q = null;
    for (; k < s.length; k++) {
      const c = s[k];
      if (q) { if (c === '\\') { k++; continue; } if (c === q) q = null; continue; }
      if (c === '"' || c === "'" || c === '`') { q = c; continue; }
      if (c === '[' || c === '{') { depth++; started = true; }
      else if (c === ']' || c === '}') { depth--; if (started && depth === 0) { k++; break; } }
    }
    out[n] = Function('window', ...Object.keys(out), 'return ' + s.slice(j, k))({}, ...Object.values(out));
  }
  return out;
}

module.exports = { readConsts };
