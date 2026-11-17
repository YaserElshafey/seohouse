#!/usr/bin/env python3
"""English versions of the 7 case studies (same figures; search phrases kept as searched)."""
import json

LINKS = 'Strengthened the domain with links from trusted websites'
SRC_10 = 'Google Analytics, January to October 2026 compared with the same period in 2025'
SRC_3 = 'Google Analytics, August to October 2026 compared with the previous three months'

CASES = {
 'lavison-clinics': dict(
  title='Lavison Clinics', sector='Healthcare', market='Alexandria, Egypt',
  result='2,448', result_label='booking requests from Google, from zero',
  summary='2,448 booking requests from Google in 10 months, for a website that started from zero',
  challenge='A brand-new website with no visibility on Google',
  desc='Clinics for psychiatry, addiction treatment and sleep disorders.',
  steps=['A page for every specialty, featuring the best doctors in Alexandria', 'Content that answers patients’ questions before they book', 'A booking form on every page, with every request tracked', LINKS],
  metric='Booking requests from Google', before='0', before_p='January – October 2025', after='2,448', after_p='January – October 2026', change='From zero', change_note='A new website that started from zero', source=SRC_10,
  ga_alt='Google Analytics report for Lavison Clinics: booking requests (form_submit) from organic search, 2,448 from January to October 2026 vs. 0 in the same period of 2025',
  seo_desc='SEO case study for Lavison Clinics: 2,448 booking requests from Google in 10 months for a website that started from zero. Challenge, solution and results.'),
 'darbk': dict(
  title='Darbk', sector='Truck & equipment tires', market='Saudi Arabia',
  result='+15,800%', result_label='quote requests from Google',
  summary='159 quote requests from Google in 10 months, up from one request in the same period the year before',
  challenge='Winning transport companies’ trust in a Saudi-made product',
  desc='A Saudi manufacturer of tires for trucks and heavy equipment.',
  steps=['Pages for truck, bus and equipment tires', 'An up-to-date truck tire price guide', 'A quote request form on every page', LINKS],
  metric='Quote requests from Google', before='1', before_p='January – October 2025', after='159', after_p='January – October 2026', change='+15,800%', source=SRC_10,
  extra='Visitors from Google grew from 1,072 to 15,681 (+1,363%)',
  quote='As a Saudi tire manufacturer, our biggest challenge was earning customers’ trust. With SEO House we were able to prove the strength of a local product and build real trust, and that showed in higher sales and a wider reach for the brand.',
  quote_name='Mohammed Bin Afif', quote_company='Darbk',
  ga_alt='Google Analytics report for Darbk: quote requests (form_submit) from organic search, 159 from January to October 2026 vs. 1 in 2025 (+15,800%), and visitors 15,681 vs. 1,072',
  seo_desc='SEO case study for Darbk: 159 quote requests from Google in 10 months, up from one request in the same period the year before. Challenge, solution and results.'),
 'alrawda-machines': dict(
  title='Al-Rawda Machinery', sector='Packaging machines', market='Egypt',
  result='+1,171%', result_label='WhatsApp chats from Google',
  summary='89 WhatsApp chats from Google in 3 months, up from 7 in the previous three months',
  challenge='Buyers search for the machine’s name, not the company’s',
  desc='A company supplying packaging and filling machines to factories.',
  steps=['Redesigned the website with a clearer page structure', 'A page for every machine: shrink wrapping, vacuum and can sealing', 'A page for every industry: confectionery, spices and more', 'Articles that explain the machines before buyers ask for a price', LINKS],
  metric='WhatsApp chats from Google', before='7', before_p='May – July 2026', after='89', after_p='August – October 2026', change='+1,171%', source=SRC_3,
  extra='Visitors from Google grew from 700 to 1,661 (+137%)',
  ga_alt='Google Analytics report for Al-Rawda Machinery: WhatsApp clicks from organic search, 89 from August to October 2026 vs. 7 in the previous three months (+1,171.43%), and visitors 1,661 vs. 700',
  seo_desc='SEO case study for Al-Rawda Machinery: 89 WhatsApp chats from Google in 3 months, up from 7 in the previous three months. Challenge, solution and results.'),
 'arab-computers': dict(
  title='Arab Computers', sector='IT solutions for businesses', market='Egypt',
  result='+300%', result_label='WhatsApp chats from Google',
  summary='336 WhatsApp chats from Google in 10 months, up from 84 in the same period the year before',
  challenge='Reaching IT managers at companies',
  desc='An authorized distributor of global technology brands for businesses.',
  steps=['A page for every brand: HP, Cisco and Lenovo', 'Improved the company’s visibility on Google Maps', 'Content that answers IT managers’ questions', LINKS],
  metric='WhatsApp chats from Google', before='84', before_p='January – October 2025', after='336', after_p='January – October 2026', change='+300%', source=SRC_10,
  extra='Visits from Google grew from 1,605 to 3,174 (+98%)',
  extra_result='#1 on Google for “hp authorized reseller”, and featured in Google’s AI Overviews',
  ga_alt='Google Analytics report for Arab Computers: WhatsApp clicks from organic search, 336 from January to October 2026 vs. 84 in 2025 (+300%), and sessions 3,174 vs. 1,605',
  seo_desc='SEO case study for Arab Computers: 336 WhatsApp chats from Google in 10 months, up from 84 in the same period the year before. Challenge, solution and results.'),
 'jinan-travels': dict(
  title='Jinan Travel & Tourism', sector='Travel & tourism', market='For travelers from Saudi Arabia',
  result='+375%', result_label='contact requests from Google',
  summary='8,539 contact requests from Google in 10 months, up from 1,796 in the same period the year before',
  challenge='Demand is seasonal, and what counts is the number of bookings',
  desc='A travel company offering trips and packages for families and honeymooners.',
  steps=['Fixed the technical issues that were holding the website back', 'A package page for every destination, with its itinerary and price', 'Seasonal articles ahead of travel seasons', 'Dedicated packages for families and honeymoons', LINKS],
  metric='Contact requests from Google', before='1,796', before_p='January – October 2025', after='8,539', after_p='January – October 2026', change='+375%', source=SRC_10,
  ga_alt='Google Analytics report for Jinan Travel & Tourism: contact requests from organic search, 8,539 from January to October 2026 vs. 1,796 in 2025 (+375.45%)',
  seo_desc='SEO case study for Jinan Travel & Tourism: 8,539 contact requests from Google in 10 months, up from 1,796 in the same period the year before. See how.'),
 'shesafe': dict(
  title='SheSafe', sector='Online store', market='Saudi Arabia',
  result='+3,409%', result_label='sales from Google',
  summary='SAR 55,580 in sales from Google in 3 months, up from SAR 1,584 in the previous three months',
  challenge='A crowded market full of competitors and big brands',
  desc='A Saudi brand of organic sanitary pads made from Japanese cotton.',
  steps=['A strong category page for organic sanitary pads', 'Product reviews that show as stars on Google', 'Product images that appear in image search', LINKS],
  metric='Sales from Google', before='SAR 1,584', before_p='May – July 2026', after='SAR 55,580', after_p='August – October 2026', change='+3,409%', source=SRC_3,
  extra='Visitors from Google grew from 247 to 2,562 (+937%)',
  extra_result='On the first page of Google for «فوط صحية عضوية» (organic sanitary pads)',
  ga_alt='Google Analytics report for SheSafe: revenue from organic search, SAR 55,580.20 from August to October 2026 vs. SAR 1,583.80 in the previous three months (+3,409.29%), and visitors 2,562 vs. 247',
  seo_desc='SEO case study for SheSafe: SAR 55,580 in sales from Google in 3 months, up from SAR 1,584 in the previous three months. Challenge, solution and results.'),
 'jalsatak': dict(
  title='Jalsatak', sector='Online furniture store', market='Saudi Arabia',
  result='+335%', result_label='sales from Google',
  summary='SAR 32,988 in sales from Google in 6 months, up from SAR 7,575 in the same period the year before',
  challenge='Visitors added to cart and stopped before paying',
  desc='An online store for bean bag seating.',
  steps=['Reorganized the pages and product categories', 'Clearer, stronger product descriptions and images', 'Simplified the checkout steps after the cart', LINKS],
  metric='Sales from Google', before='SAR 7,575', before_p='July – December 2023', after='SAR 32,988', after_p='July – December 2024', change='+335%',
  source='Google Analytics, July to December 2024 compared with the same period in 2023',
  extra='Purchases from Google grew from 17 to 45 (+165%)',
  quote='Our products are higher in quality and price than the market, which made persuading customers hard even though we had plenty of visitors. After the SEO work and improving the buying journey, we were able to turn visitors into real customers and multiply our sales.',
  quote_name='Bilal Siwan', quote_company='Jalsatak',
  ga_alt='Google Analytics report for Jalsatak: google / organic revenue SAR 32,988.22 (July – December 2024) vs. SAR 7,575.84 (July – December 2023), +335.44%, and purchases 45 vs. 17',
  seo_desc='SEO case study for Jalsatak: SAR 32,988 in sales from Google in 6 months, up from SAR 7,575 in the same period the year before. Challenge, solution and results.'),
}

POS = {
 'النتيجة الأولى': '#1 result', 'أول نتيجة بعد صور جوجل': 'First result after Google Images', 'أول نتيجة، وفي صور جوجل أيضًا': 'First result, and in Google Images too',
 'الأول في الخريطة وفي النتائج': '#1 on the map and in the results', 'أول نتيجة بعد ملخص جوجل الذكي': 'First result after the AI Overview', 'في الصفحة الأولى': 'On the first page',
 'مصدر في ملخص جوجل الذكي، وأول متجر في النتائج': 'A source in the AI Overview, and the first store in the results', 'النتيجة الأولى في الصفحة': '#1 result on the page',
 'أول نتيجة بعد الخريطة': 'First result after the map', 'الأول، ومصدر في ملخص جوجل الذكي': '#1, and a source in the AI Overview', 'النتيجة الثانية في جوجل': '#2 on Google', '': '',
 'ضمن النتائج الأولى': 'Among the top results', 'مصدر في ملخص جوجل الذكي، وفي النتائج': 'A source in the AI Overview, and in the results',
 'مصدر في ملخص جوجل الذكي، وأول نتيجة بعده': 'A source in the AI Overview, and the first result after it', 'مصدر في ملخص جوجل الذكي، وضمن النتائج الأولى': 'A source in the AI Overview, and among the top results',
 'أول نتيجة بعد الفيديوهات': 'First result after the videos', 'أول نتيجة بعد الصور': 'First result after the images',
 'مصدر في ملخص جوجل الذكي، وثاني نتيجة بعد موقع مايكروسوفت': 'A source in the AI Overview, and the second result after Microsoft’s site',
 'أول نتيجة بعد اقتراحات الوجهات': 'First result after the suggested destinations', 'أول نتيجة بعد نتائج التسوق': 'First result after the shopping results',
}
CLIENT = {'عنوان الإضاءة': 'Lighting Address', 'ام كيه قباني للأثاث': 'MK Kabbani Furniture', 'بن عفيف لإدارة العقارات': 'Bin Afif Property Management', 'سهل للمحاماة': 'Sahal Law Firm', 'مركز بداية للطب النفسي': 'Bedaya Psychiatric Center',
          'عيادات لافيزون التخصصية': 'Lavison Clinics', 'دربك': 'Darbk', 'الروضة للماكينات': 'Al-Rawda Machinery', 'العربية للحاسبات': 'Arab Computers', 'جنان للسفر والسياحة': 'Jinan Travel & Tourism', 'شي سيف': 'SheSafe'}
SECTOR = {'متجر إضاءة · السعودية': 'Lighting store · Saudi Arabia', 'متجر أثاث · الإمارات': 'Furniture store · UAE', 'إدارة عقارات · السعودية': 'Property management · Saudi Arabia',
          'محاماة واستشارات · السعودية': 'Law & legal advice · Saudi Arabia', 'رعاية صحية · مصر': 'Healthcare · Egypt'}


def en_alt(client, kw, pos):
    return f'Google search results for “{kw}”: {client}’s website' + (f' — {pos}' if pos else ' among the results')


def build(ar_cases, plan):
    out = []
    for c in ar_cases:
        slug = c['slug']; e = CASES[slug]; f = c['fields']
        client = e['title']
        rankings = [{'image': p['id'], 'keyword': p['keyword'], 'position': POS[p['position']], 'alt': en_alt(client, p['keyword'], POS[p['position']])}
                    for p in plan if p['kind'] == 'ranking' and p['case'] == slug]
        out.append({
            'source': c['id'], 'slug': slug + '-en', 'title': client, 'index': True,
            'fields': {
                'crumb': client, 'summary': e['summary'], 'result': e['result'], 'result_label': e['result_label'],
                'sector': e['sector'], 'market': e['market'], 'filter': f['filter'], 'client': client, 'client_public': 1, 'client_logo': f['client_logo'],
                'source': e['source'], 'challenge_title': e['challenge'], 'challenge_text': e['desc'], 'solution_title': 'The solution', 'steps': [{'title': s} for s in e['steps']],
                'main_metric': e['metric'], 'main_before': e['before'], 'main_before_period': e['before_p'], 'main_after': e['after'], 'main_after_period': e['after_p'],
                'main_change': e['change'], 'main_change_note': e.get('change_note', ''), 'extra_line': e.get('extra', ''), 'extra_result': e.get('extra_result', ''),
                'analytics_image': f.get('analytics_image') or 0, 'analytics_alt': e['ga_alt'], 'rankings': rankings,
                'quote': e.get('quote', ''), 'quote_name': e.get('quote_name', ''), 'quote_company': e.get('quote_company', ''),
            },
            'seo': {'rank_math_title': f'SEO Case Study: {client} | SEO House', 'rank_math_description': e['seo_desc'], 'rank_math_focus_keyword': 'seo case study'},
        })
    return out


def table(plan):
    rows = []
    for p in plan:
        if p['kind'] != 'table':
            continue
        c = CLIENT[p['client']]; pos = POS[p['position']]
        rows.append({'image': p['id'], 'client': c, 'sector': SECTOR[p['sector']], 'keyword': p['keyword'], 'position': pos, 'alt': en_alt(c, p['keyword'], pos)})
    return rows


if __name__ == '__main__':
    ar = json.load(open('cases-payload-final.json')); live = json.load(open('cases-live.json')); plan = json.load(open('ev/plan.json'))
    for c in ar:
        c['id'] = live[c['slug']]['id']
    en = build(ar, plan)
    for x in en:
        d = x['seo']['rank_math_description']; t = x['seo']['rank_math_title']
        print(x['slug'], len(t), len(d), x['fields']['client_logo'], x['fields']['analytics_image'], len(x['fields']['rankings']))
        assert len(t) <= 60 and 120 <= len(d) <= 160, (x['slug'], len(t), len(d))
    json.dump(en, open('cases-en-payload.json', 'w'), ensure_ascii=False, indent=1)
    json.dump(table(plan), open('rank-table-en.json', 'w'), ensure_ascii=False, indent=1)
