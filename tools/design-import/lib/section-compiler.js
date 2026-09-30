/**
 * Compiles one rendered design section (DOM) into:
 *   - a PHP template that prints the same markup from ACF values,
 *   - the ACF sub-field definitions for that section (layout),
 *   - the seed values extracted from the design (for the importer).
 *
 * Rules (see docs/acf-field-map.md for the resulting map):
 *   - A run of text / inline markup that contains letters or digits becomes a text field.
 *   - <a href="/path/"> becomes a page link field, external links a URL field; #anchors stay literal.
 *   - <img> (png/jpg) becomes an image field (attachment ID); <img src="*.svg"> a select of approved design logos.
 *   - Consecutive siblings with the same structure become a Repeater (nested up to 3 levels).
 *     Attribute values that differ between rows become a restricted "variant" select, or a
 *     position rule (first/last row) or a row counter when the pattern is obvious.
 *   - aria-hidden decoration, inline SVG and symbols stay in the template.
 */
const crypto = require('crypto');
const { tokenize } = require('./tokens');

const INLINE_TAGS = new Set(['span', 'bdi', 'strong', 'b', 'em', 'br', 'small', 'sup', 'sub', 'code', 'mark', 'u', 's', 'i']);
const VOID = new Set(['img', 'br', 'hr', 'input', 'meta', 'link', 'source', 'area', 'col', 'wbr']);
const HAS_WORD = /[\p{L}\p{N}]/u;
const ROLE_AR = {
  title: 'العنوان', heading: 'عنوان فرعي', text: 'النص', eyebrow: 'الوسم', label: 'نص', link: 'الرابط', link_label: 'نص الرابط',
  button: 'نص الزر', image: 'الصورة', logo: 'الشعار', items: 'العناصر', variant: 'نمط العرض', icon: 'الأيقونة', symbol: 'الرمز',
  aria: 'وصف للقارئ الآلي', value: 'القيمة', caption: 'التعليق'
};

const esc = s => String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
const escAttr = s => String(s).replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;');
const md5 = s => crypto.createHash('md5').update(s).digest('hex');
const preview = s => { const t = String(s).replace(/<[^>]+>/g, '').replace(/\s+/g, ' ').trim(); return t.length > 38 ? t.slice(0, 36) + '…' : t; };

function isTag(n) { return n && (n.type === 'tag' || n.type === 'script' || n.type === 'style'); }
function isWsText(n) { return n.type === 'text' && !n.data.trim(); }
function textOf(n) { if (n.type === 'text') return n.data; if (!n.children) return ''; return n.children.map(textOf).join(''); }

/** Structural signature; runs of identical child signatures collapse so rows with 3 or 4 bullets still match. */
function sig(n) {
  if (n.type === 'text') return n.data.trim() ? 'T' : '';
  if (!isTag(n)) return '';
  if (n.name === 'svg') return 'svg';
  if (['bdi', 'strong', 'b', 'em', 'br', 'small', 'sup', 'sub'].includes(n.name)) return 'T';
  const attrs = Object.keys(n.attribs || {}).filter(a => a !== 'data-dc-tpl' && a !== 'style' && a !== 'class').sort().join(',');
  const kids = [];
  for (const c of n.children || []) {
    const s = sig(c);
    if (!s) continue;
    if (s === 'T' && kids.length && (kids[kids.length - 1] === 'T')) continue;
    if (kids.length && (kids[kids.length - 1] === s || kids[kids.length - 1] === s + '+')) kids[kids.length - 1] = s + '+';
    else kids.push(s);
  }
  return `${n.name}[${attrs}](${kids.join(' ')})`;
}
const baseSig = s => s.replace(/\+$/, '');

/** Serialize a subtree literally (used for SVG and decoration). */
function serialize(n, ctx) {
  if (n.type === 'text') return esc(n.data);
  if (n.type === 'comment') return '';
  if (!isTag(n)) return '';
  const attrs = ctx.renderAttrs(n, null);
  const open = `<${n.name}${attrs}>`;
  if (VOID.has(n.name)) return open;
  return open + (n.children || []).map(c => serialize(c, ctx)).join('') + `</${n.name}>`;
}

class Scope {
  constructor(compiler, parent, varName, idxName, rows, fieldList, path) {
    this.c = compiler; this.parent = parent; this.v = varName; this.i = idxName;
    this.rows = rows;           // one value object per aligned instance
    this.fields = fieldList;    // ACF sub_fields array to push into
    this.names = new Set(fieldList.map(f => f.name));
    this.path = path;
    this.variants = [];         // [{token, values[]}]
  }
  uniq(base) { let n = base, k = 2; while (this.names.has(n)) n = `${base}_${k++}`; this.names.add(n); return n; }
}

class SectionCompiler {
  /**
   * @param {object} o { pageKey, layout, hoverMap, assets (Set), icons (Map), dynamic: [{match(node), php}] }
   */
  constructor(o) { Object.assign(this, o); this.varDepth = 0; }

  key(path) { return 'field_' + md5(`${this.pageKey}/${this.layout}/${path}`).slice(0, 13); }

  renderAttrs(n, scope, attrOverrides = {}) {
    let out = '';
    for (const [k, v0] of Object.entries(n.attribs || {})) {
      if (k === 'data-dc-tpl') continue;
      if (k in attrOverrides) { if (attrOverrides[k] !== null) out += ` ${k}="${attrOverrides[k]}"`; continue; }
      let v = v0;
      if (k === 'style') v = tokenize(v);
      if (k === 'class') v = this.mapClasses(v);
      if (v === '' && k.startsWith('data-')) { out += ` ${k}`; continue; }
      out += ` ${k}="${escAttr(v)}"`;
    }
    return out;
  }

  mapClasses(v) {
    return v.split(/\s+/).filter(Boolean).map(c => this.hoverMap[c] || c).join(' ');
  }

  field(scope, role, def, pathTail) {
    const name = scope.uniq(def.name || role);
    const f = {
      key: this.key(`${scope.path}/${name}`), label: def.label || ROLE_AR[role] || role, name,
      type: def.type || 'text', instructions: def.instructions || '', required: 0,
      ...def.extra
    };
    delete f.extra;
    scope.fields.push(f);
    return f;
  }

  roleFor(el) {
    const t = el.name;
    if (t === 'h1' || t === 'h2') return 'title';
    if (/^h[3-6]$/.test(t)) return 'heading';
    if (t === 'p' || t === 'li' || t === 'blockquote' || t === 'dd') return 'text';
    if (t === 'button') return 'button';
    if (t === 'a') return 'link_label';
    if (t === 'figcaption') return 'caption';
    const st = (el.attribs && el.attribs.style) || '';
    const fs = +(st.match(/font-size:\s*([\d.]+)px/) || [])[1];
    const fw = +(st.match(/font-weight:\s*(\d+)/) || [])[1];
    if (fs && fs <= 14 && fw >= 600) return 'eyebrow';
    if (fw >= 700 || /Alexandria/.test(st)) return 'heading';
    if (fs && fs >= 15) return 'text';
    return 'label';
  }

  /** Returns true for an inline element that should be part of a text run. */
  inlineContent(n, parentHasText) {
    if (n.type === 'text') return true;
    if (!isTag(n) || !INLINE_TAGS.has(n.name)) return false;
    if (n.name === 'br') return true;
    if (n.attribs && n.attribs['aria-hidden'] === 'true') return false;
    const kidsOk = (n.children || []).every(c => c.type === 'text' || (isTag(c) && ['br', 'bdi', 'strong', 'b', 'em', 'span', 'small', 'sup', 'sub'].includes(c.name) && (c.children || []).every(g => g.type === 'text')));
    if (!kidsOk) return false;
    if (!HAS_WORD.test(textOf(n))) return false;
    if (n.name === 'bdi' || n.name === 'strong' || n.name === 'b' || n.name === 'em' || n.name === 'small' || n.name === 'sup' || n.name === 'sub') return true;
    return parentHasText; // styled span/i only when mixed with the parent's own words
  }

  runHtml(nodes) {
    return nodes.map(n => n.type === 'text' ? esc(n.data) : serialize(n, this)).join('').replace(/\s+/g, ' ').trim();
  }

  /** Entry: compile section element. */
  compile(sectionEl) {
    const value = {};
    const fields = [];
    const scope = new Scope(this, null, '$f', null, [value], fields, this.layout);
    const php = this.walkTag([sectionEl], scope, true);
    this.resolveVariants(scope);
    return { php: (scope.variantPrelude || '') + this.finalize(php, scope), fields, value };
  }

  finalize(php, scope) {
    for (const v of scope.variants) php = php.split(v.token).join(v.php);
    return php;
  }

  // ---------------------------------------------------------------- walking
  walk(nodes, scope) {
    const n = nodes[0];
    if (n.type === 'text') return this.walkLiteralText(nodes, scope);
    if (n.type === 'comment') return '';
    if (!isTag(n)) return '';
    return this.walkTag(nodes, scope, false);
  }

  walkLiteralText(nodes, scope) {
    const vals = nodes.map(x => x.data);
    if (vals.every(v => v === vals[0])) return esc(vals[0]);
    return this.differingText(vals, scope, 'symbol');
  }

  /** Text that differs between rows but is not "content" (aria-hidden numbers, symbols). */
  differingText(vals, scope, role) {
    const trimmed = vals.map(v => v.trim());
    const lead = vals[0].match(/^\s*/)[0], trail = vals[0].match(/\s*$/)[0];
    const seq = trimmed.every((v, k) => /^\d{1,2}$/.test(v) && +v === +trimmed[0] + k) && scope.i;
    // sequential counters restart per parent row; accept if each instance equals its row index + base
    if (seq || this.isRowCounter(trimmed, scope)) {
      const pad = trimmed[0].length === 2 && trimmed[0][0] === '0';
      const base = +trimmed[0] - (this.rowIndexOf(scope, 0));
      const expr = pad ? `sprintf('%02d', ${scope.i} + ${base})` : `(${scope.i} + ${base})`;
      return `${lead}<?= esc_html(${expr}) ?>${trail}`;
    }
    const f = this.field(scope, role, { type: 'text', label: `${ROLE_AR[role]}: ${preview(trimmed[0])}` }, role);
    scope.rows.forEach((r, k) => { r[f.name] = trimmed[k]; });
    return `${lead}<?= esc_html(${scope.v}['${f.name}'] ?? '') ?>${trail}`;
  }

  rowIndexOf(scope, k) { return scope.rowIndex ? scope.rowIndex[k] : k; }
  isRowCounter(vals, scope) {
    if (!scope.i || !scope.rowIndex) return false;
    const base = +vals[0] - scope.rowIndex[0];
    return vals.every((v, k) => /^\d{1,2}$/.test(v) && +v === scope.rowIndex[k] + base);
  }

  walkTag(nodes, scope, isRoot) {
    const n = nodes[0];
    const tag = n.name;
    // dynamic regions handed to hand-built partials
    for (const d of this.dynamic || []) {
      if (!d.inside && d.match(n)) {
        d.used = true;
        if (d.wrap) {
          // compile normally, but let WordPress replace it when a live source is connected
          d.inside = true;
          const inner = this.walkTag(nodes, scope, isRoot);
          d.inside = false;
          return `<?php if ( ! sh_dynamic_slot( '${d.name}' ) ) : ?>${inner}<?php endif; ?>`;
        }
        return typeof d.php === 'function' ? d.php(n, this) : d.php;
      }
    }
    if (tag === 'nav' && n.attribs && n.attribs['aria-label'] === 'مسار التنقل') return '<?php sh_breadcrumbs(); ?>';
    if (tag === 'script' || tag === 'style') return '';
    if (tag === 'svg') return this.walkSvg(nodes, scope);
    if (tag === 'img') return this.walkImg(nodes, scope);

    const overrides = {};
    const pre = [], post = [];
    // attributes that differ between aligned instances
    const names = new Set(nodes.flatMap(x => Object.keys(x.attribs || {})));
    let hrefField = null;
    for (const a of names) {
      if (a === 'data-dc-tpl') continue;
      const vals = nodes.map(x => (x.attribs || {})[a]);
      if (tag === 'a' && a === 'href') {
        const hv = vals.map(v => v || '');
        if (hv.every(v => v.startsWith('#')) && hv.every(v => v === hv[0])) continue;
        if (hv.every(v => v.startsWith('#'))) { overrides[a] = this.variantToken(scope, vals, 'href'); continue; }
        hrefField = this.linkField(scope, hv, nodes);
        overrides[a] = `<?= esc_url(${hrefField.expr}) ?>`;
        continue;
      }
      if (vals.every(v => v === vals[0])) {
        if (a === 'id' && isRoot) { overrides[a] = `<?= esc_attr(sh_anchor(${scope.v}, '${escAttr(vals[0])}')) ?>`; this.anchorDefault = vals[0]; }
        continue;
      }
      if (['alt', 'aria-label', 'title'].includes(a) && vals.some(v => v && HAS_WORD.test(v))) {
        const f = this.field(scope, 'aria', { type: 'text', label: `${ROLE_AR.aria}: ${preview(vals[0] || '')}` });
        scope.rows.forEach((r, k) => { r[f.name] = vals[k] || ''; });
        overrides[a] = `<?= esc_attr(${scope.v}['${f.name}'] ?? '') ?>`;
        continue;
      }
      let vv = vals.map(v => v === undefined ? null : v);
      if (a === 'style') vv = vv.map(v => v === null ? null : tokenize(v));
      if (a === 'class') vv = vv.map(v => v === null ? null : this.mapClasses(v));
      overrides[a] = this.variantToken(scope, vv, a);
    }

    const open = `<${tag}${this.renderAttrs(n, scope, overrides)}>`;
    if (VOID.has(tag)) return open;
    const inner = this.walkChildren(nodes, scope, n);
    let html = `${open}${inner.php}</${tag}>`;

    // hide optional wrappers when their only content is empty
    if (!isRoot) {
      const conds = [];
      if (hrefField) conds.push(`!empty(${hrefField.expr})`);
      if (inner.soleField) conds.push(`!empty(${inner.soleField})`);
      if (conds.length) html = `<?php if (${conds.join(' && ')}) : ?>${html}<?php endif; ?>`;
    }
    return pre.join('') + html + post.join('');
  }

  linkField(scope, hrefs, nodes) {
    const internal = hrefs.every(h => h.startsWith('/') && !h.startsWith('//'));
    const external = hrefs.every(h => /^https?:\/\//.test(h));
    const label = `${ROLE_AR.link}: ${preview(textOf(nodes[0]))}`;
    let f;
    if (internal && hrefs.every(h => this.routeResolvable(h))) {
      f = this.field(scope, 'link', { type: 'page_link', label, extra: { post_type: ['page', 'case_study', 'team_member', 'post'], allow_null: 1, allow_archives: 1, multiple: 0 } });
      scope.rows.forEach((r, k) => { r[f.name] = { __route: hrefs[k] }; });
    } else {
      f = this.field(scope, 'link', { type: external ? 'url' : 'text', label, instructions: 'رابط داخلي يبدأ بـ / أو رابط كامل أو #قسم', extra: {} });
      scope.rows.forEach((r, k) => { r[f.name] = hrefs[k]; });
    }
    return { field: f, expr: `sh_link(${scope.v}['${f.name}'] ?? '')` };
  }

  walkChildren(nodes, scope, parent) {
    // Group each instance's children into runs; instances are structurally identical by signature.
    const perInst = nodes.map(x => this.childUnits(x));
    const len = perInst[0].length;
    if (!perInst.every(u => u.length === len)) {
      // should not happen (signature equal); fall back to literal of first instance
      return { php: (nodes[0].children || []).map(c => serialize(c, this)).join('') };
    }
    let php = '';
    let fieldCount = 0, soleField = null, otherContent = false;
    for (let u = 0; u < len; u++) {
      const units = perInst.map(p => p[u]);
      const kind = units[0].kind;
      php += units[0].ws || '';
      if (kind === 'tail') continue;
      if (kind === 'text') {
        const r = this.textRun(units, scope, parent);
        php += r.php; if (r.field) { fieldCount++; soleField = r.expr; } else if (r.visible) otherContent = true;
        continue;
      }
      // element run
      const counts = units.map(x => x.nodes.length);
      if (counts.every(c => c === 1)) {
        const out = this.walk(units.map(x => x.nodes[0]), scope);
        php += out;
        if (/\S/.test(out)) otherContent = true;
      } else {
        php += this.repeater(units, scope, parent);
        otherContent = true;
      }
    }
    return { php, soleField: fieldCount === 1 && !otherContent ? soleField : null };
  }

  /** Split children into units: text runs (text + inline markup) and element runs (same signature). */
  childUnits(el) {
    const kids = (el.children || []).filter(c => c.type !== 'comment');
    const parentHasText = kids.some(c => c.type === 'text' && HAS_WORD.test(c.data));
    const units = [];
    let ws = '';
    let i = 0;
    while (i < kids.length) {
      const c = kids[i];
      if (this.inlineContent(c, parentHasText)) {
        const run = [];
        while (i < kids.length && this.inlineContent(kids[i], parentHasText)) { run.push(kids[i]); i++; }
        const txt = run.map(x => x.type === 'text' ? x.data : 'x').join('');
        if (!txt.trim()) { ws += txt; continue; }
        units.push({ kind: 'text', nodes: run, ws }); ws = '';
        continue;
      }
      if (!isTag(c)) { i++; continue; }
      const s = sig(c);
      const run = [c]; i++;
      while (true) {
        let j = i; while (j < kids.length && isWsText(kids[j])) j++;
        if (j < kids.length && isTag(kids[j]) && baseSig(sig(kids[j])) === baseSig(s) && this.groupable(c, kids[j])) { run.push(kids[j]); i = j + 1; }
        else break;
      }
      units.push({ kind: 'el', nodes: run, ws }); ws = '';
    }
    if (ws) units.push({ kind: 'tail', nodes: [], ws });
    return units;
  }

  groupable(a, b) {
    if (a.name === 'svg' || a.name === 'br') return false;
    if (!HAS_WORD.test(textOf(a)) && !this.hasImage(a)) return false; // decoration stays literal
    const at = a.attribs && a.attribs['data-dc-tpl'], bt = b.attribs && b.attribs['data-dc-tpl'];
    if (at && at === bt) return true;
    // text leaves with different styling are different roles (title + description), not a list
    const leaf = n => (n.children || []).every(c => c.type === 'text' || (isTag(c) && ['bdi', 'strong', 'b', 'em', 'br', 'small', 'sup', 'sub'].includes(c.name)));
    if (leaf(a) && leaf(b)) {
      const ks = new Set([...Object.keys(a.attribs || {}), ...Object.keys(b.attribs || {})]);
      for (const k of ks) { if (['data-dc-tpl', 'href', 'alt', 'aria-label', 'title'].includes(k)) continue; if ((a.attribs || {})[k] !== (b.attribs || {})[k]) return false; }
      return true;
    }
    // limit variant noise between static siblings
    let diffs = 0;
    const cmp = (x, y) => {
      if (!x || !y || x.type !== y.type) return;
      if (isTag(x)) {
        for (const k of new Set([...Object.keys(x.attribs || {}), ...Object.keys(y.attribs || {})])) {
          if (k === 'data-dc-tpl' || k === 'href' || k === 'src' || k === 'alt') continue;
          if ((x.attribs || {})[k] !== (y.attribs || {})[k]) diffs++;
        }
        if (x.name === 'svg') return;
        const xs = (x.children || []).filter(c => isTag(c)), ys = (y.children || []).filter(c => isTag(c));
        for (let k = 0; k < Math.min(xs.length, ys.length); k++) cmp(xs[k], ys[k]);
      }
    };
    cmp(a, b);
    return diffs <= 4;
  }

  hasImage(n) { if (n.name === 'img') return true; return (n.children || []).some(c => isTag(c) && this.hasImage(c)); }

  textRun(units, scope, parent) {
    const htmls = units.map(u => this.runHtml(u.nodes));
    const raw = units[0].nodes.map(x => x.type === 'text' ? x.data : '').join('');
    const lead = raw.match(/^\s+/) ? ' ' : '';
    const rawEnd = units[0].nodes.slice(-1)[0];
    const trail = rawEnd && rawEnd.type === 'text' && /\s$/.test(rawEnd.data) ? ' ' : '';
    const words = htmls.some(h => HAS_WORD.test(h.replace(/<[^>]+>/g, '')));
    const hidden = parent.attribs && parent.attribs['aria-hidden'] === 'true';
    if (!words || hidden) {
      if (htmls.every(h => h === htmls[0])) return { php: units[0].nodes.map(x => x.type === 'text' ? esc(x.data) : serialize(x, this)).join(''), visible: !!htmls[0] };
      return { php: this.differingText(units.map(u => textOf({ children: u.nodes })), scope, 'symbol'), visible: true };
    }
    // row counters: "1", "2", "3" inside repeaters
    const plain = htmls.map(h => h.replace(/<[^>]+>/g, '').trim());
    if (scope.i && plain.every(p => /^\d{1,2}$/.test(p)) && this.isRowCounter(plain, scope) && !htmls.some(h => /</.test(h))) {
      return { php: this.differingText(plain, scope, 'symbol'), visible: true };
    }
    const isHtml = htmls.some(h => /<[a-z]/i.test(h));
    const role = this.roleFor(parent);
    const long = htmls.some(h => h.length > 90);
    const f = this.field(scope, role, {
      type: isHtml || long ? 'textarea' : 'text',
      label: `${ROLE_AR[role] || 'نص'}: ${preview(htmls[0])}`,
      instructions: isHtml ? 'يمكن استخدام وسوم نصية بسيطة مثل <bdi> و<strong> و<br>.' : '',
      extra: isHtml || long ? { rows: 3, new_lines: '' } : {}
    });
    scope.rows.forEach((r, k) => { r[f.name] = htmls[k]; });
    const expr = `${scope.v}['${f.name}']`;
    const fn = isHtml ? 'sh_inline' : 'esc_html';
    return { php: `${lead}<?= ${fn}(${expr} ?? '') ?>${trail}`, field: f, expr };
  }

  repeater(units, scope, parent) {
    // all rows of all instances, aligned
    const d = ++this.varDepth;
    const rv = `$r${d}`, ri = `$i${d}`;
    const first = units[0].nodes[0];
    const label = `${ROLE_AR.items}: ${preview(textOf(first))}`;
    const f = this.field(scope, 'items', { type: 'repeater', label, extra: { layout: 'block', button_label: 'إضافة عنصر', sub_fields: [], min: 0, max: 0 } });
    const rows = [], nodes = [], rowIndex = [];
    units.forEach((u, k) => {
      const arr = u.nodes.map(() => ({}));
      scope.rows[k][f.name] = arr;
      u.nodes.forEach((nd, j) => { rows.push(arr[j]); nodes.push(nd); rowIndex.push(j); });
    });
    const child = new Scope(this, scope, rv, ri, rows, f.sub_fields, `${scope.path}/${f.name}`);
    child.rowIndex = rowIndex;
    child.rowCounts = units.map(u => u.nodes.length);
    const body = this.walk(nodes, child);
    this.resolveVariants(child);
    const tpl = (child.variantPrelude || '') + this.finalize(body, child);
    this.varDepth--;
    // collapsed row title = first text sub field
    const firstText = f.sub_fields.find(s => s.type === 'text' || s.type === 'textarea');
    if (firstText) f.collapsed = firstText.key;
    const maxRows = Math.max(...child.rowCounts);
    f.instructions = `العدد المعتمد في التصميم: ${maxRows}. يمكن الإضافة والحذف وإعادة الترتيب.`;
    const n = `${ri}_n`;
    return `<?php $${rv.slice(1)}_list = ${scope.v}['${f.name}'] ?? []; ${n} = is_array($${rv.slice(1)}_list) ? count($${rv.slice(1)}_list) : 0; foreach ((array) $${rv.slice(1)}_list as ${ri} => ${rv}) : ?>${tpl}<?php endforeach; ?>`;
  }

  /** Records differing attribute values; resolved after the row template is complete. */
  variantToken(scope, vals, attr) {
    const token = `%%V${md5(scope.path + attr + JSON.stringify(vals) + scope.variants.length).slice(0, 10)}%%`;
    scope.variants.push({ token, values: vals, attr });
    return token;
  }

  resolveVariants(scope) {
    if (!scope.variants.length) return;
    const n = scope.rows.length;
    const idx = scope.rowIndex || scope.rows.map((_, k) => k);
    const counts = scope.rowCounts;
    const lastOf = k => { if (!counts) return k === n - 1; let acc = 0; for (const c of counts) { if (k < acc + c) return k === acc + c - 1; acc += c; } return false; };
    const remaining = [];
    for (const v of scope.variants) {
      const vals = v.values;
      const firsts = vals.filter((_, k) => idx[k] === 0), rest = vals.filter((_, k) => idx[k] !== 0);
      const lasts = vals.filter((_, k) => lastOf(k)), notLast = vals.filter((_, k) => !lastOf(k));
      const same = a => a.length && a.every(x => x === a[0]);
      if (scope.i && same(firsts) && same(rest) && firsts[0] !== rest[0]) {
        v.php = this.attrExpr(`${scope.i} === 0`, firsts[0], rest[0], v.attr);
      } else if (scope.i && same(lasts) && same(notLast) && lasts[0] !== notLast[0]) {
        v.php = this.attrExpr(`${scope.i} === ${scope.i}_n - 1`, lasts[0], notLast[0], v.attr);
      } else remaining.push(v);
    }
    if (!remaining.length) return;
    // combine remaining diffs into restricted variants
    const combos = new Map();
    const rowKey = [];
    for (let k = 0; k < n; k++) {
      const tuple = JSON.stringify(remaining.map(v => v.values[k]));
      if (!combos.has(tuple)) combos.set(tuple, `v${combos.size + 1}`);
      rowKey.push(combos.get(tuple));
    }
    const choices = {};
    const table = {};
    for (const [tuple, key] of combos) {
      const vals = JSON.parse(tuple);
      choices[key] = `النمط ${key.slice(1)}` + (this.describeVariant(remaining, vals) ? ` — ${this.describeVariant(remaining, vals)}` : '');
      table[key] = vals;
    }
    const f = this.field(scope, 'variant', { type: 'select', label: ROLE_AR.variant, instructions: 'أنماط مأخوذة من التصميم المعتمد فقط.', extra: { choices, default_value: 'v1', allow_null: 0, ui: 0, return_format: 'value' } });
    scope.rows.forEach((r, k) => { r[f.name] = rowKey[k]; });
    const tableVar = `$vt_${f.key.slice(6, 14)}`;
    const tablePhp = `<?php ${tableVar} = ${this.phpArray(table)}; $vk_${f.key.slice(6, 14)} = ${tableVar}[${scope.v}['${f.name}'] ?? 'v1'] ?? ${tableVar}['v1']; ?>`;
    remaining.forEach((v, j) => {
      v.php = `<?= esc_attr($vk_${f.key.slice(6, 14)}[${j}] ?? '') ?>`;
      v.pre = tablePhp;
    });
    scope.variantPrelude = tablePhp;
  }

  describeVariant(remaining, vals) {
    for (let j = 0; j < remaining.length; j++) {
      const m = String(vals[j] || '').match(/var\(--sh-([a-z-]+)\)/);
      if (m) return { lime: 'فسفوري', sky: 'سماوي', blue: 'أزرق', dim: 'رمادي', muted: 'رمادي فاتح', text: 'أبيض', ink: 'داكن', paper: 'فاتح' }[m[1]] || m[1];
    }
    return '';
  }

  attrExpr(cond, a, b, attr) {
    return `<?= esc_attr(${cond} ? ${this.phpStr(a ?? '')} : ${this.phpStr(b ?? '')}) ?>`;
  }

  phpStr(s) { return `'${String(s).replace(/\\/g, '\\\\').replace(/'/g, "\\'")}'`; }
  phpArray(o) {
    if (o === null || o === undefined) return 'null';
    if (typeof o !== 'object') return this.phpStr(o);
    if (Array.isArray(o)) return '[' + o.map(x => x === null ? 'null' : this.phpStr(x)).join(', ') + ']';
    return '[' + Object.entries(o).map(([k, v]) => `${this.phpStr(k)} => ${this.phpArray(v)}`).join(', ') + ']';
  }

  walkSvg(nodes, scope) {
    const lits = nodes.map(x => serialize(x, this));
    if (lits.every(l => l === lits[0])) return lits[0];
    const ids = lits.map(l => this.registerIcon(l));
    const choices = {};
    for (const id of ids) choices[id] = this.icons.get(id).label;
    const f = this.field(scope, 'icon', { type: 'select', label: ROLE_AR.icon, instructions: 'أيقونات التصميم المعتمد.', extra: { choices, default_value: ids[0], allow_null: 0, return_format: 'value' } });
    scope.rows.forEach((r, k) => { r[f.name] = ids[k]; });
    return `<?= sh_icon(${scope.v}['${f.name}'] ?? '${ids[0]}') ?>`;
  }

  registerIcon(markup) {
    const id = 'i' + md5(markup).slice(0, 8);
    if (!this.icons.has(id)) this.icons.set(id, { markup, label: `أيقونة ${this.icons.size + 1}` });
    return id;
  }

  walkImg(nodes, scope) {
    const n = nodes[0];
    const srcs = nodes.map(x => (x.attribs.src || ''));
    const alts = nodes.map(x => x.attribs.alt || '');
    const others = {};
    for (const [k, v] of Object.entries(n.attribs)) if (!['src', 'alt', 'data-dc-tpl'].includes(k)) others[k] = k === 'style' ? tokenize(v) : v;
    const attrPhp = this.phpArray(others);
    if (srcs.every(s => s.startsWith('data:'))) {
      const lits = nodes.map(x => serialize(x, this));
      if (lits.every(l => l === lits[0])) return lits[0];
      const ids = lits.map(l => this.registerIcon(l));
      const choices = {}; for (const id of ids) choices[id] = this.icons.get(id).label;
      const f = this.field(scope, 'icon', { type: 'select', label: ROLE_AR.icon, instructions: 'أيقونات التصميم المعتمد.', extra: { choices, default_value: ids[0], allow_null: 0, return_format: 'value' } });
      scope.rows.forEach((r, k) => { r[f.name] = ids[k]; });
      return `<?= sh_icon(${scope.v}['${f.name}'] ?? '${ids[0]}') ?>`;
    }
    if (srcs.every(s => /\.svg$/i.test(s))) {
      srcs.forEach(s => this.assets.add(s));
      const choices = {};
      for (const s of this.svgChoices) choices[s] = s.split('/').pop().replace('.svg', '');
      for (const s of srcs) choices[s] = s.split('/').pop().replace('.svg', '');
      const f = this.field(scope, 'logo', { type: 'select', label: `${ROLE_AR.logo}: ${preview(alts[0] || srcs[0].split('/').pop())}`, instructions: 'شعارات المنصات المعتمدة في التصميم.', extra: { choices, default_value: srcs[0], allow_null: 1, return_format: 'value' } });
      scope.rows.forEach((r, k) => { r[f.name] = srcs[k]; });
      const altExpr = alts.every(a => a === alts[0]) ? this.phpStr(alts[0]) : "''";
      return `<?= sh_svg_img(${scope.v}['${f.name}'] ?? '', ${altExpr}, ${attrPhp}) ?>`;
    }
    srcs.forEach(s => this.assets.add(s));
    const f = this.field(scope, 'image', { type: 'image', label: `${ROLE_AR.image}: ${preview(alts[0] || srcs[0].split('/').pop())}`, extra: { return_format: 'id', preview_size: 'medium', library: 'all' } });
    scope.rows.forEach((r, k) => { r[f.name] = { __asset: srcs[k], alt: alts[k] }; });
    const altFallback = alts.every(a => a === alts[0]) ? this.phpStr(alts[0]) : "''";
    return `<?= sh_image(${scope.v}['${f.name}'] ?? 0, ${attrPhp}, ${altFallback}) ?>`;
  }

  routeResolvable(h) { return this.routes ? this.routes(h) : true; }
}

module.exports = { SectionCompiler, sig, textOf, serialize };
