#!/usr/bin/env python3
"""Arabic service and industry pages: SEO titles with the markets (14 Nov 2026).
Every page without a country gets «في السعودية ومصر والإمارات»; the SEO country pages (ksa, egypt, uae) and Salla
(already Saudi) are left as they are; Zid is a Saudi platform, so Saudi Arabia only (as in English).
Usage: python3 geo_titles_ar.py [payload]"""
import json, sys
G, S = ' في السعودية ومصر والإمارات', ' | سيو هاوس'
P = {
    47: 'القطاعات التي نخدمها' + G,
    31: 'سيو المتاجر الإلكترونية' + G,
    33: 'سيو المراكز التعليمية' + G,
    35: 'تسويق المطاعم بالسيو' + G,
    37: 'تسويق العيادات بالسيو' + G,
    39: 'تسويق مكاتب المحاماة بالسيو' + G,
    41: 'تسويق العقارات بالسيو' + G,
    43: 'سيو شركات البرمجيات' + G,
    45: 'تسويق شركات السياحة بالسيو' + G,
    67: 'خدمات السيو وتصميم المواقع' + G,
    65: 'شركة تحسين محركات البحث' + G,
    49: 'خدمة باك لينك وبناء روابط' + G,
    51: 'استشارة سيو وتحليل الأداء' + G,
    53: 'خدمة كتابة محتوى سيو' + G,
    61: 'خدمة السيو الداخلي' + G,
    63: 'خدمة السيو التقني' + G,
    77: 'تصميم متجر إلكتروني' + G,
    75: 'تصميم متجر زد احترافي في السعودية',
    71: 'تصميم متجر شوبيفاي Shopify' + G,
    73: 'تصميم متجر ووكومرس' + G,
    27: 'خدمة إضافة منتجات للمتاجر' + G,
    87: 'شركة تصميم مواقع' + G,
    85: 'شركة برمجة مواقع وأنظمة' + G,
    91: 'تطوير مواقع React وNext.js' + G,
    93: 'تصميم مواقع ويب فلو Webflow' + G,
    95: 'تصميم مواقع ووردبريس' + G,
}
P = {i: t + S for i, t in P.items()}
if __name__ == '__main__':
    if sys.argv[1:] == ['payload']:
        json.dump({'dry_run': False, 'items': [{'id': i, 'title': t} for i, t in P.items()]}, open('geo-ar-payload.json', 'w'), ensure_ascii=False, indent=1)
    for i, t in P.items():
        print(i, len(t), t)
