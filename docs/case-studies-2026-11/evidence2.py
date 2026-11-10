#!/usr/bin/env python3
"""The remaining ranking screenshots from the client folders (read one by one): search phrase as typed,
position only as far as the screenshot shows it. Converts to WebP and writes ev/plan2.json."""
import json, os, sys
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from evidence import to_webp, rank_alt, OUT

TOP = 'ضمن النتائج الأولى'
AIO_IN = 'مصدر في ملخص جوجل الذكي، وفي النتائج'
AIO_FIRST = 'مصدر في ملخص جوجل الذكي، وأول نتيجة بعده'
AIO_TOP = 'مصدر في ملخص جوجل الذكي، وضمن النتائج الأولى'

CASES = {
 'lavison-clinics': ('عيادات لافيزون التخصصية', [
   ('lavisonclinics.com/٣.png', 'lavison-clinics-google-ranking-child-behavior-specialist', 'اخصائي سلوك اطفال', TOP),
   ('lavisonclinics.com/٤.png', 'lavison-clinics-google-ranking-behavior-therapy-doctor', 'دكتور تعديل سلوك', TOP),
   ('lavisonclinics.com/٥.png', 'lavison-clinics-google-ranking-sleep-disorder-doctors', 'اطباء علاج اضطرابات النوم', AIO_FIRST)]),
 'darbk': ('دربك', [
   ('darbk.com/2.png', 'darbk-google-ranking-truck-tyres', 'كفرات شاحنات', TOP),
   ('darbk.com/3.png', 'darbk-google-ranking-truck-tyre-prices', 'سعر كفرات الشاحنات', TOP)]),
 'alrawda-machines': ('الروضة للماكينات', [
   ('alrawda-machines.com/1.png', 'alrawda-machines-google-ranking-shrink-machines', 'ماكينات الشرنك', TOP),
   ('alrawda-machines.com/٣.png', 'alrawda-machines-google-ranking-vacuum-machine', 'ماكينة فاكيوم سحب وطرد', TOP),
   ('alrawda-machines.com/٥.png', 'alrawda-machines-google-ranking-spice-packaging-machine', 'ماكينة تغليف علب البهارات', 'أول نتيجة بعد الفيديوهات'),
   ('alrawda-machines.com/٦.png', 'alrawda-machines-google-ranking-shrink-tunnel', 'فرن شرنك حراري', 'أول نتيجة بعد الصور')]),
 'arab-computers': ('العربية للحاسبات', [
   ('arab-computers.com/1.png', 'arab-computers-google-ranking-cisco-distributors', 'cisco distributors', TOP),
   ('arab-computers.com/٣.png', 'arab-computers-google-ranking-microsoft-authorized-reseller', 'microsoft authorized reseller', 'مصدر في ملخص جوجل الذكي، وثاني نتيجة بعد موقع مايكروسوفت'),
   ('arab-computers.com/٤.png', 'arab-computers-google-ranking-kaspersky-reseller', 'kaspersky reseller', AIO_TOP),
   ('arab-computers.com/٥.png', 'arab-computers-google-ranking-voip-phone-suppliers', 'voip phone suppliers', TOP)]),
 'jinan-travels': ('جنان للسفر والسياحة', [
   ('jinantravels.com/10.png', 'jinan-travels-google-ranking-honeymoon-for-hijabis', 'أفضل مكان لشهر العسل للمحجبات', AIO_IN),
   ('jinantravels.com/5.png', 'jinan-travels-google-ranking-kazakhstan-tourism', 'السياحة في كازاخستان', AIO_FIRST),
   ('jinantravels.com/6.png', 'jinan-travels-google-ranking-kyrgyzstan-tourism', 'سياحة قرغيزستان', AIO_IN),
   ('jinantravels.com/4.png', 'jinan-travels-google-ranking-greece-tourism', 'السياحة في اليونان', 'أول نتيجة بعد ملخص جوجل الذكي'),
   ('jinantravels.com/9.png', 'jinan-travels-google-ranking-america-tourism', 'السياحة في امريكا', 'أول نتيجة بعد اقتراحات الوجهات'),
   ('jinantravels.com/1.png', 'jinan-travels-google-ranking-albania-tourism', 'سياحة البانيا', TOP),
   ('jinantravels.com/3.png', 'jinan-travels-google-ranking-singapore-tourism', 'السياحة في سنغافورة', TOP),
   ('jinantravels.com/8.png', 'jinan-travels-google-ranking-south-korea-tourism', 'السياحة في كوريا الجنوبية', TOP)]),
}

TABLE = [
 ('lightingaddress.com/2.png', 'lightingaddress-google-ranking-electrical-push-connector', 'عنوان الإضاءة', 'متجر إضاءة · السعودية', 'كبس كهرباء', AIO_FIRST),
 ('lightingaddress.com/3.png', 'lightingaddress-google-ranking-electrical-connector', 'عنوان الإضاءة', 'متجر إضاءة · السعودية', 'كونكتر كهرباء', AIO_FIRST),
 ('lightingaddress.com/7.png', 'lightingaddress-google-ranking-light-sensor', 'عنوان الإضاءة', 'متجر إضاءة · السعودية', 'حساس ضوء', AIO_FIRST),
 ('lightingaddress.com/4.png', 'lightingaddress-google-ranking-adhesive-led-strip', 'عنوان الإضاءة', 'متجر إضاءة · السعودية', 'شريط ليد لاصق', 'أول نتيجة بعد الصور'),
 ('lightingaddress.com/8.png', 'lightingaddress-google-ranking-outdoor-led-profile', 'عنوان الإضاءة', 'متجر إضاءة · السعودية', 'بروفايل ليد خارجي', 'أول نتيجة بعد الصور'),
 ('lightingaddress.com/5.png', 'lightingaddress-google-ranking-magnetic-lighting', 'عنوان الإضاءة', 'متجر إضاءة · السعودية', 'اضاءة ماجنتك', TOP),
 ('mk-kabbanifurniture.com/٥.png', 'mk-kabbani-google-ranking-bedroom-sets', 'ام كيه قباني للأثاث', 'متجر أثاث · الإمارات', 'bedroom sets', AIO_FIRST),
 ('mk-kabbanifurniture.com/٢.png', 'mk-kabbani-google-ranking-dining-set-6', 'ام كيه قباني للأثاث', 'متجر أثاث · الإمارات', 'dining set 6', 'أول نتيجة بعد نتائج التسوق'),
 ('mk-kabbanifurniture.com/٧.png', 'mk-kabbani-google-ranking-dining-set', 'ام كيه قباني للأثاث', 'متجر أثاث · الإمارات', 'dining set', AIO_TOP),
 ('mk-kabbanifurniture.com/٦.png', 'mk-kabbani-google-ranking-dining-table-sets', 'ام كيه قباني للأثاث', 'متجر أثاث · الإمارات', 'dining table sets', TOP),
 ('mk-kabbanifurniture.com/٣.png', 'mk-kabbani-google-ranking-furniture', 'ام كيه قباني للأثاث', 'متجر أثاث · الإمارات', 'furniture', TOP),
 ('mk-kabbanifurniture.com/٤.png', 'mk-kabbani-google-ranking-furniture-store-near-me', 'ام كيه قباني للأثاث', 'متجر أثاث · الإمارات', 'furniture store near me', TOP),
 ('afifamlak.com/5.png', 'afifamlak-google-ranking-commercial-centers-management', 'بن عفيف لإدارة العقارات', 'إدارة عقارات · السعودية', 'إدارة المراكز التجارية', AIO_IN),
 ('afifamlak.com/١.png', 'afifamlak-google-ranking-property-management-company', 'بن عفيف لإدارة العقارات', 'إدارة عقارات · السعودية', 'شركة إدارة عقارات', AIO_IN),
 ('afifamlak.com/٢.png', 'afifamlak-google-ranking-property-maintenance', 'بن عفيف لإدارة العقارات', 'إدارة عقارات · السعودية', 'صيانة العقارات', TOP),
 ('afifamlak.com/٣.png', 'afifamlak-google-ranking-residential-compounds-management', 'بن عفيف لإدارة العقارات', 'إدارة عقارات · السعودية', 'إدارة المجمعات السكنية', TOP),
 ('afifamlak.com/٦.png', 'afifamlak-google-ranking-tenant-follow-up', 'بن عفيف لإدارة العقارات', 'إدارة عقارات · السعودية', 'متابعة المستأجرين', TOP),
 ('afifamlak.com/٨.png', 'afifamlak-google-ranking-real-estate-marketing-saudi', 'بن عفيف لإدارة العقارات', 'إدارة عقارات · السعودية', 'شركة تسويق عقاري في السعودية', TOP),
 ('bedayaccpa.com/3.png', 'bedayaccpa-google-ranking-psychiatric-hospital', 'مركز بداية للطب النفسي', 'رعاية صحية · مصر', 'مصحة نفسية', TOP),
 ('bedayaccpa.com/4.png', 'bedayaccpa-google-ranking-female-psychiatrist', 'مركز بداية للطب النفسي', 'رعاية صحية · مصر', 'دكتورة نفسيه', TOP),
 ('bedayaccpa.com/5.png', 'bedayaccpa-google-ranking-teen-behavior-therapy', 'مركز بداية للطب النفسي', 'رعاية صحية · مصر', 'دكتور تعديل سلوك المراهقين', TOP),
]

if __name__ == '__main__':
    plan = []
    for slug, (client, rows) in CASES.items():
        for rel, name, kw, pos in rows:
            out, size = to_webp(rel, name, 1600)
            plan.append({'kind': 'ranking', 'case': slug, 'file': out, 'alt': rank_alt(client, kw, pos), 'keyword': kw, 'position': pos, 'size': size})
    for rel, name, client, sector, kw, pos in TABLE:
        out, size = to_webp(rel, name, 1600)
        plan.append({'kind': 'table', 'file': out, 'alt': rank_alt(client, kw, pos), 'client': client, 'sector': sector, 'keyword': kw, 'position': pos, 'size': size})
    json.dump(plan, open(os.path.join(OUT, 'plan2.json'), 'w'), ensure_ascii=False, indent=1)
    print(len(plan), 'images')
