/**
 * Approved colour tokens of the final design export. Every inline colour that
 * matches one of these is rewritten to a CSS custom property so the theme
 * settings ("التصميم") can adjust them in one place.
 */
const TOKENS = [
  ['ink', '#060B1F'],
  ['text', '#EDF1FA'],
  ['muted', '#B9C4DC'],
  ['sky', '#4CACFF'],
  ['lime', '#C7FF32'],
  ['lime-hover', '#D8FF63'],
  ['blue', '#2F5BFF'],
  ['dim', '#8FA0BE'],
  ['crumb', '#8494B5'],
  ['crumb-current', '#D6DEF0'],
  ['ink-soft', '#3E4A66'],
  ['slate', '#55607C'],
  ['surface', '#0B1438'],
  ['surface-2', '#0B1335'],
  ['surface-3', '#1A2340'],
  ['paper', '#F7F9FD'],
  ['paper-2', '#F4F7FD'],
  ['blue-tint', '#EEF2FF']
];

const hexToRgb = h => { const n = parseInt(h.slice(1), 16); return [(n >> 16) & 255, (n >> 8) & 255, n & 255]; };
const BY_RGB = new Map(TOKENS.map(([k, h]) => [hexToRgb(h).join(','), k]));
const BY_HEX = new Map(TOKENS.map(([k, h]) => [h.toLowerCase(), k]));

/** rgb()/rgba()/hex → var() in any CSS text (inline style or stylesheet). */
function tokenize(css) {
  if (!css) return css;
  css = css.replace(/rgba?\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*(?:,\s*([\d.]+)\s*)?\)/g, (m, r, g, b, a) => {
    const k = BY_RGB.get(`${r},${g},${b}`);
    if (!k) return m;
    return a === undefined ? `var(--sh-${k})` : `rgba(var(--sh-${k}-rgb), ${a})`;
  });
  css = css.replace(/#([0-9a-fA-F]{6})\b/g, (m) => {
    const k = BY_HEX.get(m.toLowerCase());
    return k ? `var(--sh-${k})` : m;
  });
  return css;
}

function rootCss() {
  const lines = TOKENS.map(([k, h]) => `  --sh-${k}: ${h};\n  --sh-${k}-rgb: ${hexToRgb(h).join(', ')};`);
  return `:root {\n${lines.join('\n')}\n}\n`;
}

module.exports = { TOKENS, tokenize, rootCss, hexToRgb };
