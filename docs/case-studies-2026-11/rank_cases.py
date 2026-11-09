#!/usr/bin/env python3
"""Ranking case studies for clients with Google screenshots but no Analytics data: every figure is a count
of the screenshots (search phrases, AI Overview citations); no before/after numbers, no work steps."""
import json, sys
sys.path.insert(0, '.')
import cases_en

AR_DATE, EN_DATE = '1 نوفمبر 2026', '1 November 2026'
plan = json.load(open('ev/plan.json')) + json.load(open('ev/plan2.json'))
media = json.load(open('media-ids.json'))

C = [
 dict(slug='lighting-address', client='عنوان الإضاءة', en='Lighting Address', sector='متجر إضاءة', market='السعودية', en_sector='Lighting store', en_market='Saudi Arabia', filt='ecommerce', order=8,
      desc='متجر سعودي لحلول الإضاءة LED للمنازل والمشاريع.', en_desc='A Saudi store for LED lighting solutions for homes and projects.',
      challenge='الظهور لكلمات المنتجات التي يبحث بها المشتري مباشرة', en_challenge='Showing up for the product searches buyers type directly',
      topic='لمنتجات الإضاءة', en_topic='lighting product'),
 dict(slug='mk-kabbani-furniture', client='ام كيه قباني للأثاث', en='MK Kabbani Furniture', sector='متجر أثاث', market='الإمارات', en_sector='Furniture store', en_market='UAE', filt='ecommerce', order=9,
      desc='متجر أثاث في الإمارات لغرف النوم وطاولات الطعام والأثاث المنزلي.', en_desc='A furniture store in the UAE for bedrooms, dining sets and home furniture.',
      challenge='الظهور بجانب متاجر الأثاث الكبرى في كلمات البحث الإنجليزية', en_challenge='Standing next to the big furniture retailers in English searches',
      topic='للأثاث', en_topic='furniture'),
 dict(slug='bin-afif-property-management', client='بن عفيف لإدارة العقارات', en='Bin Afif Property Management', sector='إدارة عقارات', market='السعودية', en_sector='Property management', en_market='Saudi Arabia', filt='real-estate', order=10,
      desc='شركة سعودية لإدارة وتشغيل العقارات والمراكز التجارية والمباني.', en_desc='A Saudi company that manages and operates properties, shopping centers and buildings.',
      challenge='الظهور لخدمات إدارة العقارات عند الشركات والملاك', en_challenge='Being found for property management services by companies and owners',
      topic='لخدمات إدارة العقارات', en_topic='property management'),
 dict(slug='bedaya-psychiatric-center', client='مركز بداية للطب النفسي', en='Bedaya Psychiatric Center', sector='رعاية صحية', market='مصر', en_sector='Healthcare', en_market='Egypt', filt='health', order=11, logo='bedaya',
      desc='مركز للطب النفسي وعلاج الإدمان في الإسكندرية.', en_desc='A center for psychiatry and addiction treatment in Alexandria.',
      challenge='الظهور لمن يبحث عن علاج نفسي وعلاج الإدمان في الإسكندرية', en_challenge='Reaching people searching for psychiatric and addiction care in Alexandria',
      topic='للطب النفسي وعلاج الإدمان', en_topic='psychiatry and addiction care'),
]


def build(logo_ids):
    ar_out, en_out = [], []
    for c in C:
        shots = [p for p in plan if p['kind'] == 'table' and p['client'] == c['client']]
        n = len(shots); aio = sum(1 for p in shots if 'ملخص جوجل الذكي' in p['position'] and p['position'].startswith(('مصدر', 'الأول')))
        aio_txt = f'، منها {aio} يظهر فيها مصدرًا في ملخص جوجل الذكي' if aio else ''
        en_aio = f', {aio} of them with the site cited in Google’s AI Overview' if aio else ''
        summary = f'{n} كلمات بحث {c["topic"]} يظهر فيها الموقع في النتائج الأولى على جوجل{aio_txt}'
        results = f'يظهر الموقع في النتائج الأولى على جوجل لـ {n} كلمات بحث{aio_txt}. اللقطات أدناه من نتائج بحث جوجل بتاريخ {AR_DATE}.'
        logo = logo_ids.get(c.get('logo', ''), 0)
        top = sum(1 for p in shots if 'أول نتيجة' in p['position'] or p['position'].startswith(('النتيجة الأولى', 'الأول،')))
        src_ar, src_en = f'لقطات بحث جوجل، {AR_DATE}', f'Google search screenshots, {EN_DATE}'
        m_ar = [{'label': 'كلمات بحث يظهر فيها الموقع في النتائج الأولى', 'after': str(n), 'source': src_ar}]
        m_en = [{'label': 'Searches with the site in the top results', 'after': str(n), 'source': src_en}]
        if top:
            m_ar.append({'label': 'منها كلمات يظهر فيها الموقع أول نتيجة (بعد الخريطة أو الصور أو الملخص إن وُجدت)', 'after': str(top), 'source': src_ar})
            m_en.append({'label': 'Of these, searches where the site is the first result (after the map, images or overview, if any)', 'after': str(top), 'source': src_en})
        if aio:
            m_ar.append({'label': 'منها كلمات يظهر فيها الموقع مصدرًا في ملخص جوجل الذكي', 'after': str(aio), 'source': src_ar})
            m_en.append({'label': 'Of these, searches where the site is cited in Google’s AI Overview', 'after': str(aio), 'source': src_en})
        ar = {'title': c['client'], 'slug': c['slug'], 'status': 'publish', 'menu_order': c['order'],
              'fields': {'crumb': c['client'], 'summary': summary, 'result': str(n), 'result_label': f'كلمات بحث في النتائج الأولى على جوجل',
                         'sector': c['sector'], 'market': c['market'], 'filter': [c['filt']], 'client': c['client'], 'client_public': 1, 'client_logo': logo,
                         'source': f'لقطات من نتائج بحث جوجل بتاريخ {AR_DATE}', 'challenge_title': c['challenge'], 'challenge_text': c['desc'],
                         'results_text': results, 'metrics': m_ar, 'main_after_period': 'نوفمبر 2026', 'case_date': 'نوفمبر 2026',
                         'rankings': [{'image': p['id'], 'keyword': p['keyword'], 'position': p['position'], 'alt': p['alt'], 'date': AR_DATE} for p in shots]},
              'seo': {'rank_math_title': f'دراسة حالة سيو: {c["client"]} | سيو هاوس',
                      'rank_math_description': f'دراسة حالة سيو لـ{c["client"]}: {summary}.',
                      'rank_math_focus_keyword': 'دراسة حالة سيو'}}
        ar_out.append(ar)
        en_summary = f'{n} {c["en_topic"]} searches with the site in Google’s top results{en_aio}'
        en = {'slug': c['slug'] + '-en', 'title': c['en'], 'index': True,
              'fields': {'crumb': c['en'], 'summary': en_summary, 'result': str(n), 'result_label': 'searches in Google’s top results',
                         'sector': c['en_sector'], 'market': c['en_market'], 'filter': [c['filt']], 'client': c['en'], 'client_public': 1, 'client_logo': logo,
                         'source': f'Google search results captured on {EN_DATE}', 'challenge_title': c['en_challenge'], 'challenge_text': c['en_desc'],
                         'results_text': f'The site shows up in Google’s top results for {n} searches{en_aio}. The screenshots below are Google search results captured on {EN_DATE}.', 'metrics': m_en,
                         'main_after_period': 'November 2026', 'case_date': 'November 2026',
                         'rankings': [{'image': p['id'], 'keyword': p['keyword'], 'position': cases_en.POS[p['position']], 'alt': cases_en.en_alt(c['en'], p['keyword'], cases_en.POS[p['position']]), 'date': EN_DATE} for p in shots]},
              'seo': {'rank_math_title': f'SEO Case Study: {c["en"]} | SEO House',
                      'rank_math_description': f'SEO case study for {c["en"]}: {en_summary}.',
                      'rank_math_focus_keyword': 'seo case study'}}
        en_out.append(en)
    return ar_out, en_out


if __name__ == '__main__':
    ar, en = build({'bedaya': 3519})
    json.dump(ar, open('rank-cases-ar.json', 'w'), ensure_ascii=False, indent=1); json.dump(en, open('rank-cases-en.json', 'w'), ensure_ascii=False, indent=1)
    for a, e in zip(ar, en):
        print([(m['after'], m['label'][:30]) for m in a['fields']['metrics']])
        print(a['slug'], a['fields']['result'], len(a['seo']['rank_math_title']), len(a['seo']['rank_math_description']), '|', a['fields']['summary'])
        print('   ', len(e['seo']['rank_math_title']), len(e['seo']['rank_math_description']), '|', e['fields']['summary'])
