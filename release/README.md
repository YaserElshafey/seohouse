# SEO House — القالب 2.7.8 + Core 2.7.6: ملفات التثبيت

**هذه الجولة (سكيما مستقلة من Core):** تغيّرت حزمة Core فقط. Core هي المصدر الوحيد للبيانات المنظمة، سواء كانت وحدة Schema في Rank Math متوقفة أو تعمل. ارفع `seohouse-core.zip` بـ«استبدال الحالي بالمرفوع». حزمة القالب هي نفسها 2.7.8 المرفوعة سابقًا؛ لا حاجة لرفعها مرة أخرى.

| الملف | الإصدار | يُرفع؟ |
|---|---|---|
| `seohouse-core.zip` | SEO House Core **2.7.6** | **نعم**: مخطط Schema واحد كامل من Core في wp_head (بدل الاعتماد على وحدة Schema في Rank Math)، وتحديث «فحص البيانات المنظمة» |
| `seohouse-theme.zip` | قالب SEO House **2.7.8** | لا (بلا تغيير؛ نفس الملف بايتًا ببايت) |

- البصمات في `SHA256SUMS.txt`. تقرير الجولة في `docs/round-core-2.7.6-schema-independent.md` والاختبارات في `docs/qa-runs-core-2.7.6/`. الأنواع والحقول المطلوب مراجعتها وروابط أدوات جوجل في `docs/round-core-2.7.5-schema.md`.
- مختبَر من الحالة المثبّتة: القالب 2.7.7 + Core 2.7.3 ← القالب 2.7.8 + Core 2.7.6، مع ACF 6.3.12 وRank Math 1.0.279 (وحدة Schema متوقفة، وتعمل، وRank Math معطّلة).

## ترتيب الرفع

1. خذ نسخة احتياطية (نسخة فقط، لا استيراد).
2. «الإضافات ← أضف جديد ← رفع إضافة» ← `seohouse-core.zip` ← **«استبدال الحالي بالمرفوع»**.
3. أفرغ كاش الموقع والـCDN.
4. لا تشغّل التهيئة ولا استيراد المحتوى.

## بعد الرفع

- مصدر الصفحة الرئيسية يجب أن يحتوي `class="seohouse-schema"` مرة واحدة.
- «إعدادات سيو هاوس ← فحص البيانات المنظمة» ← «افحص الآن»: مصدر الإخراج، وغياب السكيما أو تكرارها في صفحات العينة، وبيانات الشركة المطبوعة، وقيم الشركة المحفوظة في Rank Math فقط (للنقل اليدوي)، والحالات التي تحتاج قرارك. لا يغيّر شيئًا.
- لا داعي لتشغيل وحدة Schema في Rank Math.

## روابط التنزيل المباشر

- https://raw.githubusercontent.com/YaserElshafey/seohouse/claude/design-v5-rollout/release/seohouse-core.zip
- https://raw.githubusercontent.com/YaserElshafey/seohouse/claude/design-v5-rollout/release/seohouse-theme.zip
