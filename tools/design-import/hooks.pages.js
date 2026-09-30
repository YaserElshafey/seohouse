/**
 * Regions inside compiled sections that list WordPress records instead of fixed design values.
 * The container keeps its design markup; its children are produced by a theme partial that
 * reuses the design's item markup (see wordpress/themes/seohouse/parts/dynamic/).
 */
module.exports = ({ openTag, attrStyle, phpStr, hasStyle, firstTag }) => {
  /** Replace the children of the matched element with a partial call. */
  const inner = (name, test, partial, argsFn = () => ({})) => ({
    name, required: true,
    match: test,
    php: n => {
      const args = argsFn(n);
      const a = Object.entries(args).map(([k, v]) => `${phpStr(k)} => ${typeof v === 'number' ? v : phpStr(v)}`).join(', ');
      const tag = n.name;
      return `${openTag(n)}<?php get_template_part( 'parts/dynamic/${partial}', null, array( ${a} ) ); ?></${tag}>`;
    }
  });
  const attr = (n, a) => n.attribs && Object.prototype.hasOwnProperty.call(n.attribs, a);
  let mobTrack = 0;

  return {
    home: {
      Hero: () => {
        mobTrack = 0;
        return [
          inner('team-columns', n => attr(n, 'data-hero-cols'), 'team-columns', () => ({ columns: 3 })),
          inner('team-track', n => attr(n, 'data-mob-track'), 'team-track', n => ({ track: mobTrack++, img_style: attrStyle(firstTag(n, 'img')) }))
        ];
      },
      Results: () => [inner('case-list', n => n.name === 'ul' && !!firstTag(n, 'a') && /data-res-card/.test(JSON.stringify(firstTag(n, 'a').attribs)), 'case-list', () => ({ context: 'home', limit: 3 }))],
      Articles: () => [inner('posts-home', n => attr(n, 'data-grid') && n.attribs['data-grid'] === 'blog2', 'posts-home', () => ({}))]
    },
    results: {
      Cases: () => [
        inner('case-filters', n => n.attribs && n.attribs.role === 'group', 'case-filters', () => ({})),
        inner('case-list', n => n.name === 'ul', 'case-list', () => ({ context: 'results', limit: 0 }))
      ]
    },
    team: {
      Directory: () => [inner('team-directory', n => attr(n, 'data-tm-grid'), 'team-directory', () => ({}))]
    }
  };
};
