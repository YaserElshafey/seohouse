# SEO House 2.6.0 — ملفات التثبيت

| الملف | المحتوى |
|---|---|
| `seohouse-theme.zip` | قالب **SEO House 2.6.0**: ملفات الإصدار المدرجة في `theme-files.json` فقط، ولا يوجد فيه `front-page.php` |
| `seohouse-core.zip` | **SEO House Core 2.6.0** كاملة، مع حزمة المحتوى 2.6.0 داخلها |

- البصمات في `SHA256SUMS.txt`.
- بُنيا من الالتزام `b734dab` بالأمر `tools/package.sh`، وكل ملف مطابق لمجلده في المستودع.
- الاختبارات في `docs/qa-runs-2.6.0/`، والتفاصيل في `docs/review-2.6.0.md`.

## الرفع على الموقع الأساسي

1. خذ نسخة احتياطية من الاستضافة أو من All-in-One WP Migration (نسخة فقط، لا استيراد).
2. **«المظهر ← قوالب ← أضف جديد ← رفع قالب»** ← `seohouse-theme.zip` ← **«استبدال الحالي بالمرفوع»**. هذا يستبدل مجلد القالب كله، فيحذف `front-page.php.old` والملفات القديمة.
3. **«الإضافات ← أضف جديد ← رفع إضافة»** ← `seohouse-core.zip` ← **«استبدال الحالي بالمرفوع»**.
4. **«سيو هاوس ← تهيئة الموقع»**، أسفل الصفحة:
   - **«سياسة الخصوصية والشروط والأحكام»**: راجع النص، ثم «تطبيق النص المعتمد» لكل صفحة.
   - **«تحويلات الموقع السابق»**: «إضافة التحويلات الناقصة».
   - ثم «فحص الموقع».

## روابط التنزيل المباشر

- https://raw.githubusercontent.com/YaserElshafey/seohouse/claude/new-session-9obdta/release/seohouse-theme.zip
- https://raw.githubusercontent.com/YaserElshafey/seohouse/claude/new-session-9obdta/release/seohouse-core.zip
