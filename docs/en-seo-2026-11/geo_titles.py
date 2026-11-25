#!/usr/bin/env python3
"""English pages: titles, descriptions, H1s and the first paragraph aimed at Saudi Arabia, the UAE and Egypt
(10 Nov 2026). A global head term ("education seo", "technical seo services") is out of reach for a regional
agency; the same term with the market ("... in Saudi Arabia") is winnable and is what its clients search.

Rules: title <= 60 characters with " | SEO House"; full country names when they fit, else "KSA, UAE & Egypt";
description <= 160; Salla and Zid are Saudi platforms, so Saudi Arabia only; the SEO country pages
(ksa, uae, egypt) keep their single market; every claim already appears on the page.

Usage: python3 geo_titles.py            -> prints the table and lengths
       python3 geo_titles.py payload    -> writes geo-seo-payload.json / geo-hero-payload.json
"""
import json, sys

M3 = 'Saudi Arabia, the UAE and Egypt'
S = ' | SEO House'

# id: (title, description, focus keyword, H1 or None to keep, sentence added to the hero text or None)
P = {
    # industries
    2746: ('SEO by Industry in Saudi Arabia, UAE & Egypt' + S,
           'SEO for stores, clinics, real estate, education, travel, tech, law firms and restaurants in Saudi Arabia, the UAE and Egypt, built on how each searches.',
           'seo by industry', None, f'We work with businesses across {M3}.'),
    2768: ('E-commerce SEO in Saudi Arabia, UAE & Egypt' + S,
           "E-commerce SEO for stores in Saudi Arabia, the UAE and Egypt: category and product pages that win orders. One Saudi store grew search revenue 432% in 90 days.",
           'e-commerce seo saudi arabia', f'E-commerce SEO in {M3}', f'We work with online stores in {M3}.'),
    2798: ('Medical SEO for Clinics in KSA, UAE & Egypt' + S,
           'Medical SEO for clinics in Saudi Arabia, the UAE and Egypt: specialty, doctor and branch pages built for bookings. One medical site grew clicks 825 to 12.9K.',
           'medical seo saudi arabia', f'Medical SEO for clinics in {M3}', f'We work with clinics, hospitals and medical centers in {M3}.'),
    2826: ('Real Estate SEO in Saudi Arabia, UAE & Egypt' + S,
           'Real estate SEO for developers, contractors and agencies in Saudi Arabia, the UAE and Egypt: project and area pages that bring viewing requests from buyers.',
           'real estate seo saudi arabia', f'Real estate SEO in {M3}', f'We work with developers, contractors and real estate agencies in {M3}.'),
    2849: ('Education SEO in Saudi Arabia, UAE & Egypt' + S,
           'SEO for education and training centers and learning platforms in Saudi Arabia, the UAE and Egypt: clear course pages that turn searches into registrations.',
           'education seo saudi arabia', f'Education & training SEO in {M3}', f'We work with training centers, institutes and learning platforms in {M3}.'),
    2879: ('Travel & Tourism SEO in KSA, UAE & Egypt' + S,
           'SEO for travel and tourism companies in Saudi Arabia, the UAE and Egypt: destination and package pages plus seasonal content that take trip planners to booking.',
           'tourism seo saudi arabia', f'Travel & tourism SEO in {M3}', f'We work with travel agencies and tourism companies in {M3}.'),
    2903: ('SaaS & Software SEO in Saudi Arabia, UAE & Egypt' + S,
           'SEO for software and SaaS companies in Saudi Arabia, the UAE and Egypt: solution, use-case, integration and comparison pages that win demo and trial requests.',
           'saas seo saudi arabia', f'SaaS & software SEO in {M3}', f'We work with software, SaaS and tech companies in {M3}.'),
    2928: ('SEO for Law Firms in Saudi Arabia, UAE & Egypt' + S,
           'SEO for law firms in Saudi Arabia, the UAE and Egypt: clear practice area pages and trust signals. We took one law firm to #1 for the Arabic search “law firm”.',
           'seo for law firms saudi arabia', f'SEO for law firms in {M3}', f'We work with law firms and legal consultancies in {M3}.'),
    2954: ('Restaurant SEO in Saudi Arabia, UAE & Egypt' + S,
           'Restaurant SEO for restaurants and food brands in Saudi Arabia, the UAE and Egypt: branch and menu pages that show up in nearby searches and lead to orders.',
           'restaurant seo saudi arabia', f'Restaurant SEO in {M3}', f'We work with restaurants, cafés and food brands in {M3}.'),
    # SEO
    1794: ('SEO Agency in Saudi Arabia, UAE & Egypt' + S,
           'SEO House is an SEO agency helping businesses in Saudi Arabia, the UAE and Egypt rank higher on Google with content, technical SEO, links and tracked results.',
           'seo agency saudi arabia', f'SEO services in {M3}', None),
    1867: ('SEO Company in Saudi Arabia | SEO Services KSA' + S,
           'SEO House is an SEO company in Saudi Arabia helping businesses rank on Google, from competitor and content analysis to technical fixes and lead tracking.',
           'seo company in saudi arabia', None, None),
    2011: ('SEO Content Writing in Saudi Arabia, UAE & Egypt' + S,
           'SEO content writing for businesses in Saudi Arabia, the UAE and Egypt: articles, service, category and product pages built on search data and edited by people.',
           'seo content writing saudi arabia', f'SEO Content Writing in {M3}', f'We write for businesses and online stores in {M3}.'),
    1979: ('Technical SEO Services in KSA, UAE & Egypt' + S,
           'Technical SEO services in Saudi Arabia, the UAE and Egypt: we audit crawling, indexing, speed and structured data, fix issues by impact and track the results.',
           'technical seo services saudi arabia', f'Technical SEO Services in {M3}', f'We work on websites and online stores in {M3}.'),
    2483: ('On-Page SEO Services in KSA, UAE & Egypt' + S,
           'On-page SEO services in Saudi Arabia, the UAE and Egypt: one clear keyword per page, better titles, content and internal links, and no competing pages.',
           'on-page seo services saudi arabia', f'On-Page SEO Services in {M3}', f'We work on websites and online stores in {M3}.'),
    2507: ('Link Building Services in KSA, UAE & Egypt' + S,
           'Link building services in Saudi Arabia, the UAE and Egypt: backlinks from relevant sites with real audiences, every source vetted and every link documented.',
           'link building services saudi arabia', f'Link Building Services in {M3}', f'We build links for businesses and online stores in {M3}.'),
    2545: ('SEO Consulting in Saudi Arabia, UAE & Egypt' + S,
           'SEO consulting in Saudi Arabia, the UAE and Egypt: we turn Search Console and Analytics data into decisions, from traffic drops to migrations and priorities.',
           'seo consultant saudi arabia', f'SEO Consulting in {M3}', f'We advise businesses and in-house teams in {M3}.'),
    # web design
    2040: ('Website Design Company in KSA, UAE & Egypt' + S,
           'Website design company in Saudi Arabia, the UAE and Egypt: fast, mobile-friendly sites with an easy dashboard and Google-ready structure.',
           'website design company saudi arabia', f'Website design company in {M3}', f'We build websites for companies in {M3}.'),
    2246: ('WordPress Website Design in KSA, UAE & Egypt' + S,
           'WordPress website design in Saudi Arabia, the UAE and Egypt: on-brand blocks your team uses to add pages, with tuned speed, security, backups and SEO setup.',
           'wordpress website design saudi arabia', f'WordPress website design in {M3}', f'We build WordPress sites for companies in {M3}.'),
    2286: ('Webflow Website Design in KSA, UAE & Egypt' + S,
           'Webflow website design in Saudi Arabia, the UAE and Egypt: precise design and animation, a CMS your team can edit and built-in SEO, with no servers to run.',
           'webflow website design saudi arabia', f'Webflow website design in {M3}', f'We build Webflow sites for companies in {M3}.'),
    2322: ('React & Next.js Development in KSA, UAE & Egypt' + S,
           'React and Next.js development in Saudi Arabia, the UAE and Egypt: the right rendering for each page, a CMS your team edits without code, and integrations.',
           'next.js development saudi arabia', f'React and Next.js development in {M3}', f'We build sites and platforms for companies in {M3}.'),
    2353: ('Custom Web Development in KSA, UAE & Egypt' + S,
           'Custom web development in Saudi Arabia, the UAE and Egypt: portals, bookings, memberships and dashboards, with payment, shipping and accounting integrations.',
           'custom web development saudi arabia', f'Custom web development in {M3}', f'We build systems for companies in {M3}.'),
    # stores
    2070: ('E-commerce Store Design in KSA, UAE & Egypt' + S,
           'E-commerce store design in Saudi Arabia, the UAE and Egypt on Salla, Zid, Shopify or WooCommerce: clear categories, product pages that sell, payment, shipping.',
           'e-commerce store design saudi arabia', f'E-commerce store design in {M3}', f'We build online stores for brands in {M3}.'),
    2577: ('Salla Store Design & Setup in Saudi Arabia' + S,
           'Salla store design and setup in Saudi Arabia: your brand, clear categories, complete product data, local payment, shipping and Google Analytics, ready to sell.',
           'salla store design', 'Salla store design in Saudi Arabia', 'We build Salla stores for brands selling in Saudi Arabia.'),
    2607: ('Zid Store Design & Setup in Saudi Arabia' + S,
           'Zid store design and setup in Saudi Arabia: an organized catalog, orders and customers, plus shipping, payment and invoicing through Zid, with less manual work.',
           'zid store design', 'Zid store design in Saudi Arabia', 'We build Zid stores for brands selling in Saudi Arabia.'),
    2636: ('Shopify Store Design in KSA, UAE & Egypt' + S,
           'Shopify store design in Saudi Arabia, the UAE and Egypt: sections you edit yourself, an organized catalog, payment and shipping, and fewer apps to cut costs.',
           'shopify store design saudi arabia', f'Shopify store design in {M3}', f'We build Shopify stores for brands in {M3}.'),
    2666: ('WooCommerce Store Design in KSA, UAE & Egypt' + S,
           'WooCommerce store design in Saudi Arabia, the UAE and Egypt: products and content on one site, with payment, shipping, invoicing and full control of your data.',
           'woocommerce store design saudi arabia', f'WooCommerce store design in {M3}', f'We build WooCommerce stores for brands in {M3}.'),
    2698: ('Product Upload Services in KSA, UAE & Egypt' + S,
           'Product upload in Saudi Arabia, the UAE and Egypt for Salla, Zid, Shopify and WooCommerce stores: names, descriptions, photos, prices, variants, categories.',
           'product upload service saudi arabia', f'Product upload service in {M3}', f'We work with online stores in {M3}.'),
    # hubs
    1754: ('SEO & Web Design Services in KSA, UAE & Egypt' + S,
           'SEO House services for businesses in Saudi Arabia, the UAE and Egypt: SEO, website and store design, and product upload, to turn Google searches into orders.',
           'seo services saudi arabia', None, None),
    2383: ('SEO Case Studies from Saudi Arabia, UAE & Egypt' + S,
           'SEO case studies from projects in Saudi Arabia, the UAE and Egypt: e-commerce, healthcare, travel and more, with the source and period of every figure.',
           'seo case studies', None, None),
}


def check():
    bad = 0
    for i, (t, d, k, h, x) in P.items():
        flag = ('T!' if len(t) > 60 else '') + ('D!' if len(d) > 160 else '')
        bad += bool(flag)
        print(f'{i} {flag:4} T{len(t):3} D{len(d):3} | {t}\n{"":10}H1: {h}')
    print('over limit:', bad)


if __name__ == '__main__':
    if sys.argv[1:] == ['payload']:
        seo = {'dry_run': False, 'items': [{'id': i, 'title': t, 'description': d, 'focus': k} for i, (t, d, k, h, x) in P.items()]}
        json.dump(seo, open('geo-seo-payload.json', 'w'), ensure_ascii=False, indent=1)
        print('seo items:', len(seo['items']))
    else:
        check()
