#!/usr/bin/env python3
"""Evidence screenshots: source file → WebP (resized only, no crop), file name, alt, keyword, position."""
import json, os, sys, unicodedata
from PIL import Image

SRC = os.path.join(os.path.dirname(os.path.abspath(__file__)), 'shots-in')
OUT = os.path.join(os.path.dirname(os.path.abspath(__file__)), 'ev')

# case slug → analytics (folder/file, webp name, alt)
ANALYTICS = {
    'lavison-clinics': ('lavisonclinics.com/٢.png', 'lavison-clinics-google-analytics',
                        'تقرير Google Analytics لعيادات لافيزون التخصصية: طلبات الحجز (form_submit) من البحث العضوي 2,448 من يناير إلى أكتوبر 2026 مقابل 0 في الفترة نفسها من 2025'),
    'darbk': ('darbk.com/5.png', 'darbk-google-analytics',
              'تقرير Google Analytics لدربك: طلبات عروض الأسعار (form_submit) من البحث العضوي 159 من يناير إلى أكتوبر 2026 مقابل 1 في 2025 (+15,800%)، والزوار 15,681 مقابل 1,072'),
    'alrawda-machines': ('alrawda-machines.com/Screenshot.png', 'alrawda-machines-google-analytics',
                         'تقرير Google Analytics للروضة للماكينات: محادثات واتساب من البحث العضوي 89 من أغسطس إلى أكتوبر 2026 مقابل 7 في الأشهر الثلاثة السابقة (+1,171.43%)، والزوار 1,661 مقابل 700'),
    'arab-computers': ('arab-computers.com/Screenshot.png', 'arab-computers-google-analytics',
                       'تقرير Google Analytics للعربية للحاسبات: محادثات واتساب من البحث العضوي 336 من يناير إلى أكتوبر 2026 مقابل 84 في 2025 (+300%)، والزيارات 3,174 مقابل 1,605'),
    'jinan-travels': ('jinantravels.com/Screenshot.png', 'jinan-travels-google-analytics',
                      'تقرير Google Analytics لجنان للسفر والسياحة: طلبات التواصل من البحث العضوي 8,539 من يناير إلى أكتوبر 2026 مقابل 1,796 في 2025 (+375.45%)'),
    'shesafe': ('shesafe.sa/Screenshot.png', 'shesafe-google-analytics',
                'تقرير Google Analytics لشي سيف: المبيعات من البحث العضوي 55,580.20 ريال من أغسطس إلى أكتوبر 2026 مقابل 1,583.80 ريال في الأشهر الثلاثة السابقة (+3,409.29%)، والزوار 2,562 مقابل 247'),
}

# case slug → rankings [(folder/file, webp name, client, keyword, position)]
RANKINGS = {
    'lavison-clinics': [('lavisonclinics.com/Screenshot.png', 'lavison-clinics-google-ranking-psychiatrist', 'عيادات لافيزون التخصصية', 'دكتورة نفسيه', 'النتيجة الأولى'),
                        ('lavisonclinics.com/٦.png', 'lavison-clinics-google-ranking-addiction-doctor', 'عيادات لافيزون التخصصية', 'دكتور ادمان مخدرات', 'النتيجة الأولى')],
    'darbk': [('darbk.com/Screenshot.png', 'darbk-google-ranking-truck-tires', 'دربك', 'اطارات شاحنات', 'أول نتيجة بعد صور جوجل'),
              ('darbk.com/4.png', 'darbk-google-ranking-heavy-equipment-tires', 'دربك', 'اطارات معدات ثقيلة', 'أول نتيجة، وفي صور جوجل أيضًا')],
    'alrawda-machines': [('alrawda-machines.com/٤.png', 'alrawda-machines-google-ranking-sweets-packaging-machine', 'الروضة للماكينات', 'ماكينة تغليف حلويات', 'النتيجة الأولى'),
                         ('alrawda-machines.com/٧.png', 'alrawda-machines-google-ranking-tin-can-sealing-machine', 'الروضة للماكينات', 'ماكينة تقفيل علب الصفيح', 'النتيجة الأولى')],
    'arab-computers': [('arab-computers.com/٢.png', 'arab-computers-google-ranking-hp-authorized-reseller', 'العربية للحاسبات', 'hp authorized reseller', 'الأول في الخريطة وفي النتائج'),
                       ('arab-computers.com/٦.png', 'arab-computers-google-ranking-lenovo-distributor-egypt', 'العربية للحاسبات', 'lenovo distributor in egypt', 'أول نتيجة بعد ملخص جوجل الذكي')],
    'jinan-travels': [('jinantravels.com/7.png', 'jinan-travels-google-ranking-honeymoon-packages', 'جنان للسفر والسياحة', 'بكجات شهر العسل لشخصين', 'النتيجة الأولى')],
    'shesafe': [('shesafe.sa/٤.png', 'shesafe-google-ranking-organic-sanitary-pads', 'شي سيف', 'فوط صحية عضوية', 'في الصفحة الأولى'),
                ('shesafe.sa/٢.png', 'shesafe-google-ranking-organic-pads', 'شي سيف', 'فوط عضوية', ''),
                ('shesafe.sa/٣.png', 'shesafe-google-ranking-japanese-sanitary-pads', 'شي سيف', 'فوط صحية يابانية', ''),
                ('shesafe.sa/١.png', 'shesafe-google-ranking-saudi-sanitary-pads', 'شي سيف', 'فوط صحية سعودية', '')],
}

# results page table: (folder/file, webp name, client, sector, keyword, position)
TABLE = [
    ('lightingaddress.com/Screenshot.png', 'lightingaddress-google-ranking-led-lighting', 'عنوان الإضاءة', 'متجر إضاءة · السعودية', 'اضاءة ليد', 'مصدر في ملخص جوجل الذكي، وأول متجر في النتائج'),
    ('lightingaddress.com/6.png', 'lightingaddress-google-ranking-modern-ceiling-spotlights', 'عنوان الإضاءة', 'متجر إضاءة · السعودية', 'سبوتات السقف مودرن', 'النتيجة الأولى في الصفحة'),
    ('mk-kabbanifurniture.com/Screenshot.png', 'mk-kabbani-google-ranking-bedroom-full-set', 'ام كيه قباني للأثاث', 'متجر أثاث · الإمارات', 'bedroom full set', 'النتيجة الأولى في الصفحة'),
    ('mk-kabbanifurniture.com/٨.png', 'mk-kabbani-google-ranking-dubai-furniture-store', 'ام كيه قباني للأثاث', 'متجر أثاث · الإمارات', 'dubai furniture store', 'أول نتيجة بعد الخريطة'),
    ('afifamlak.com/4.png', 'afifamlak-google-ranking-mall-management', 'بن عفيف لإدارة العقارات', 'إدارة عقارات · السعودية', 'إدارة وتشغيل المولات', 'الأول، ومصدر في ملخص جوجل الذكي'),
    ('afifamlak.com/٧.png', 'afifamlak-google-ranking-office-buildings-management', 'بن عفيف لإدارة العقارات', 'إدارة عقارات · السعودية', 'إدارة المباني الإدارية', 'الأول، ومصدر في ملخص جوجل الذكي'),
    ('sahalfirm.com/Screenshot.png', 'sahalfirm-google-ranking-law-firm', 'سهل للمحاماة', 'محاماة واستشارات · السعودية', 'مكتب محاماة', 'أول نتيجة بعد الخريطة'),
    ('sahalfirm.com/2.png', 'sahalfirm-google-ranking-best-lawyer-riyadh', 'سهل للمحاماة', 'محاماة واستشارات · السعودية', 'أفضل محامي بالرياض', 'النتيجة الثانية في جوجل'),
    ('bedayaccpa.com/Screenshot.png', 'bedayaccpa-google-ranking-addiction-treatment-center', 'مركز بداية للطب النفسي', 'رعاية صحية · مصر', 'مركز علاج ادمان', 'أول نتيجة بعد الخريطة'),
    ('bedayaccpa.com/2.png', 'bedayaccpa-google-ranking-addiction-rehab', 'مركز بداية للطب النفسي', 'رعاية صحية · مصر', 'مصحة علاج ادمان', 'أول نتيجة بعد الخريطة'),
]


def find(rel):
    d, f = rel.split('/')
    for x in os.listdir(os.path.join(SRC, d)):
        if unicodedata.normalize('NFC', x) == f:
            return os.path.join(SRC, d, x)
    raise FileNotFoundError(rel)


def to_webp(rel, name, maxw):
    im = Image.open(find(rel)).convert('RGB')
    if im.width > maxw:
        im = im.resize((maxw, round(im.height * maxw / im.width)), Image.LANCZOS)
    out = os.path.join(OUT, name + '.webp')
    im.save(out, 'WEBP', quality=88, method=6)
    return out, im.size


def rank_alt(client, kw, pos):
    return f'نتيجة بحث جوجل لكلمة «{kw}»: موقع {client}' + (f' — {pos}' if pos else ' ضمن النتائج')


if __name__ == '__main__':
    os.makedirs(OUT, exist_ok=True)
    plan = []
    for slug, (rel, name, alt) in ANALYTICS.items():
        out, size = to_webp(rel, name, 1800); plan.append({'kind': 'analytics', 'case': slug, 'file': out, 'alt': alt, 'size': size})
    for slug, rows in RANKINGS.items():
        for rel, name, client, kw, pos in rows:
            out, size = to_webp(rel, name, 1600)
            plan.append({'kind': 'ranking', 'case': slug, 'file': out, 'alt': rank_alt(client, kw, pos), 'keyword': kw, 'position': pos, 'size': size})
    for rel, name, client, sector, kw, pos in TABLE:
        out, size = to_webp(rel, name, 1600)
        plan.append({'kind': 'table', 'file': out, 'alt': rank_alt(client, kw, pos), 'client': client, 'sector': sector, 'keyword': kw, 'position': pos, 'size': size})
    json.dump(plan, open(os.path.join(OUT, 'plan.json'), 'w'), ensure_ascii=False, indent=1)
    for p in plan:
        print(p['kind'], os.path.basename(p['file']), p['size'], os.path.getsize(p['file']) // 1024, 'KB')
