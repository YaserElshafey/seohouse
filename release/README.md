# SEO House — القالب 2.7.8 + Core 2.7.5: ملفات التثبيت

**هذه الجولة (البيانات المنظمة Schema):** تغيّرت حزمة Core فقط. ارفع `seohouse-core.zip` بـ«استبدال الحالي بالمرفوع». حزمة القالب هي نفسها 2.7.8 المرفوعة سابقًا؛ لا حاجة لرفعها مرة أخرى.

| الملف | الإصدار | يُرفع؟ |
|---|---|---|
| `seohouse-core.zip` | SEO House Core **2.7.5** | **نعم**: استكمال البيانات المنظمة داخل graph الخاص بـRank Math، وصفحة «فحص البيانات المنظمة» |
| `seohouse-theme.zip` | قالب SEO House **2.7.8** | لا (بلا تغيير؛ نفس الملف بايتًا ببايت) |

- البصمات في `SHA256SUMS.txt`. تقرير الجولة والحقول المطلوب مراجعتها في `docs/round-core-2.7.5-schema.md`، والاختبارات في `docs/qa-runs-core-2.7.5/`.
- مختبَر من الحالة المثبّتة: القالب 2.7.7 + Core 2.7.3 ← القالب 2.7.8 + Core 2.7.5، مع ACF 6.3.12 وRank Math 1.0.279.

## ترتيب الرفع

1. خذ نسخة احتياطية (نسخة فقط، لا استيراد).
2. «الإضافات ← أضف جديد ← رفع إضافة» ← `seohouse-core.zip` ← **«استبدال الحالي بالمرفوع»**.
3. أفرغ كاش الموقع والـCDN.
4. لا تشغّل التهيئة ولا استيراد المحتوى.

## بعد الرفع

- «إعدادات سيو هاوس ← فحص البيانات المنظمة» ← «افحص الآن»: يعرض أي تعارض بين بيانات الشركة في Rank Math والظاهرة في الموقع، والحالات التي تحتاج قرارك. لا يغيّر شيئًا.
- القائمة الكاملة في `docs/round-core-2.7.5-schema.md` (الجزء 3)، وروابط الاختبار في Schema Markup Validator وRich Results Test (الجزء 5).

## روابط التنزيل المباشر

- https://raw.githubusercontent.com/YaserElshafey/seohouse/claude/design-v5-rollout/release/seohouse-core.zip
- https://raw.githubusercontent.com/YaserElshafey/seohouse/claude/design-v5-rollout/release/seohouse-theme.zip
