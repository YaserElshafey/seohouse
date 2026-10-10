# SEO House — القالب 2.7.8 + Core 2.7.4: ملفات التثبيت

**هذه الجولة (ترتيب صفحة التواصل وأيقونات الفوتر):** الحزمتان تغيّرتا. ارفع `seohouse-core.zip` أولًا ثم `seohouse-theme.zip`، كلتاهما بـ«استبدال الحالي بالمرفوع». الروابط المطلوب إدخالها يدويًا في `docs/round-2.7.8-contact-footer.md`.

| الملف | الإصدار | يُرفع؟ |
|---|---|---|
| `seohouse-core.zip` | SEO House Core **2.7.4** | **نعم، أولًا**: تبويب «الفوتر» في الإعدادات وفيه «روابط التواصل في الفوتر»، وحقول قسم «بيانات التواصل والخريطة» في صفحة التواصل |
| `seohouse-theme.zip` | قالب SEO House **2.7.8** | **نعم، ثانيًا**: بطاقة بيانات التواصل بجوار الخريطة، وحذف زر الاتجاهات، وأيقونات التواصل في الفوتر |

- البصمات في `SHA256SUMS.txt`. تقرير هذه الجولة في `docs/round-2.7.8-contact-footer.md`، واللقطات والاختبارات في `docs/qa-runs-2.7.8/`.
- مختبَر من الحالة المثبّتة: القالب 2.7.7 + Core 2.7.3 + ACF 6.3.12 + Rank Math 1.0.279، مع محتوى معدّل وبيانات تواصل وعنصر فيسبوك محفوظ.

## ترتيب الرفع

1. خذ نسخة احتياطية (نسخة فقط، لا استيراد).
2. «الإضافات ← أضف جديد ← رفع إضافة» ← `seohouse-core.zip` ← **«استبدال الحالي بالمرفوع»**.
3. «المظهر ← قوالب ← أضف جديد ← رفع قالب» ← `seohouse-theme.zip` ← **«استبدال الحالي بالمرفوع»**.
4. أفرغ كاش الموقع والـCDN.
5. لا تشغّل التهيئة ولا استيراد المحتوى.

## بعد الرفع

**روابط الفوتر:** «إعدادات سيو هاوس ← الفوتر ← روابط التواصل في الفوتر».
- تأكد من عنصر فيسبوك الموجود: `https://www.facebook.com/seohouse.agency`.
- أضف إنستجرام: `https://www.instagram.com/seohousepro`.
- أضف لينكدإن: `https://eg.linkedin.com/company/seohouse-net`.
- ثم «حفظ الإعدادات» وأفرغ الكاش.

## روابط التنزيل المباشر

- https://raw.githubusercontent.com/YaserElshafey/seohouse/claude/design-v5-rollout/release/seohouse-core.zip
- https://raw.githubusercontent.com/YaserElshafey/seohouse/claude/design-v5-rollout/release/seohouse-theme.zip
