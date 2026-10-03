# SEO House 2.5.0 — ملفات التثبيت

| الملف | المحتوى | SHA-256 |
|---|---|---|
| `seohouse-core.zip` | **SEO House Core 2.5.0** كاملة: الكود وحزمة المحتوى 2.5.0 في `seohouse-core/content-pack/` (125 ملفًا مع `manifest.json` و`pack-files.json`) | في `SHA256SUMS.txt` |
| `seohouse-theme.zip` | قالب **SEO House 2.5.0** | في `SHA256SUMS.txt` |

- بُنيا من الالتزام `c6f41f3` بالأمر `tools/package.sh`، وكل ملف مطابق لمجلده في المستودع بايتًا ببايت.
- فُحص ملف الإضافة بالأداة `tools/verify-core-zip.py`.
- الاختبارات على الملفين نفسيهما في `docs/qa-runs-2.5.0/`.

## التحديث من 2.4.x

1. «المظهر ← قوالب ← أضف جديد ← رفع قالب» ← `seohouse-theme.zip` ← «استبدال الحالي بالمرفوع».
2. «الإضافات ← أضف جديد ← رفع إضافة» ← `seohouse-core.zip` ← «استبدال الحالي بالمرفوع».
3. «سيو هاوس ← تهيئة الموقع» ← «معاينة التهيئة» ← «إعادة التهيئة».
4. «سيو هاوس ← نقل عناوين وأوصاف SEO» ← «معاينة» ← «تنفيذ النقل».

## روابط التنزيل المباشر

- https://raw.githubusercontent.com/YaserElshafey/seohouse/claude/new-session-9obdta/release/seohouse-core.zip
- https://raw.githubusercontent.com/YaserElshafey/seohouse/claude/new-session-9obdta/release/seohouse-theme.zip
