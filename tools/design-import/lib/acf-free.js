/**
 * Field definitions for ACF (free): no PRO-only field types.
 *  - repeater          → "sh_rows" (SEO House Core list field: one sh_row record per item)
 *  - flexible_content  → not produced (page sections are Group fields, see hooks.fieldGroup)
 * The transform keeps field keys, names and settings, so values, the importer and the
 * templates keep the same shape.
 */
const PRO_ONLY = new Set(['repeater', 'flexible_content', 'gallery', 'clone']);

function toFree(fields) {
  return (fields || []).map(f => {
    const o = { ...f };
    if (o.type === 'repeater') {
      o.type = 'sh_rows';
      delete o.pagination; delete o.rows_per_page;
    }
    if (o.sub_fields) o.sub_fields = toFree(o.sub_fields);
    if (PRO_ONLY.has(o.type)) throw new Error(`PRO-only field type "${o.type}" in ${o.key} (${o.name})`);
    return o;
  });
}

/** Throws if any PRO-only type remains anywhere in a field group. */
function assertFree(group) {
  const walk = fs => (fs || []).forEach(f => {
    if (PRO_ONLY.has(f.type)) throw new Error(`PRO-only field type "${f.type}" in ${group.key}: ${f.name}`);
    walk(f.sub_fields);
  });
  walk(group.fields);
  for (const or of group.location || []) for (const r of or) if (r.param === 'options_page') throw new Error(`options_page location in ${group.key}`);
  return group;
}

module.exports = { toFree, assertFree };
