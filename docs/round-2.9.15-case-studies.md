# Core 2.9.15 + القالب 2.8.9 — دراسات الحالة الجديدة، وACF المجانية، وويدجت التقييمات الإنجليزي

## ما اتنشر على الموقع
- **ACF:** ACF المجانية 6.8.11 مفعّلة بدل ACF Pro 6.3.12 (متوقفة ومتروكة للرجوع).
  - مقارنة 132 صفحة قبل وبعد: لا فرق، ماعدا تغيير «Industries» المقصود في `/en/`، وترتيب نتائج بحث متساوية الصلة.
- **التقييمات في الصفحات الإنجليزية:** قالب Trustindex الإنجليزي ولغة `en` (فلتر على خيارات الإضافة في الصفحات الإنجليزية فقط).
  - القالب يُنزّل مرة من `cdn.trustindex.io` ويُحفظ في `sh_ti_template_en`.
  - نص كل تقييم يأخذ اتجاهه من لغته (`unicode-bidi: plaintext`).
- **Sectors → Industries في الإنجليزي:** القوائم، والصفحة الرئيسية، ونصوص القالب.

## دراسات الحالة (البرومبت في `docs/case-studies-2026-11/BRIEF.md`)
- **حقول جديدة في «بيانات دراسة الحالة»:**
  - المؤشر الأساسي، وقبل وبعد والزيادة بفتراتها، وسطر تحت الزيادة.
  - سطر إضافي، ونتيجة إضافية.
  - لقطة Analytics ووصفها، ولقطات الترتيب (اللقطة، كلمة البحث، الترتيب، النص البديل).
  - رأي العميل واسمه وشركته.
  - قيمة فلتر جديدة: «الصناعة».
- **قسم «من ترتيب عملائنا في جوجل» في صفحة النتائج (`s_rankings`):** مخفي (`sh_hide`).
  - المحرر يشوفه بإضافة `?sh_show_hidden=1`.
- **القالب:**
  - كارت النتائج: اللوجو، واسم العميل، والمجال · الدولة، ورقم الكارت.
  - صفحة الدراسة:
    - مربع العميل باللوجو.
    - 3 كروت نتائج: قبل وبعد والزيادة.
    - أقسام «الدليل من Google Analytics» و«ترتيبنا في جوجل» و«رأي العميل».
    - دراسات أخرى في آخر الصفحة.
  - الصور تُفتح بحجمها الكامل (lightbox بتمرير).
- **قبل الموافقة يفضل العرض القديم للزوار:**
  - الشكل الجديد يظهر في المعاينة فقط (`?sh_preview=1` للمحررين)، وفي المسودات.
  - يظهر للكل عند تفعيل الخيار `sh_cases_v2`.
- **REST:**
  - `POST seohouse/v1/content/case`: إنشاء أو تعديل دراسة عربية غير منشورة. الحقل `retire` يعلّم الدراسات المنشورة اللي هتتسحب بعد الموافقة.
  - `POST seohouse/v1/content/section`: قيم قسم في صفحة.
- **المسودات (عربي فقط):**

  | الدراسة | المعرّف | الرابط بعد النشر |
  |---|---|---|
  | لافيزون | 3228 | `/results/lavison-clinics/` |
  | دربك | 3233 | `/results/darbk/` |
  | الروضة | 3238 | `/results/alrawda-machines/` |
  | العربية للحاسبات | 3244 | `/results/arab-computers/` |
  | جنان | 3249 | `/results/jinan-travels/` |
  | شي سيف | 3255 | `/results/shesafe/` |
  | جلستك | 3260 | `/results/jalsatak/` |

  - اللوجوهات من 3220 إلى 3226، ولقطة Analytics جلستك 3227.
- **ناقص:** لقطات Analytics والترتيب لكل العملاء ماعدا جلستك (الملف الأساسي لم يصل).

## النشر (9 نوفمبر، Core 2.9.16–2.9.17)
- **2.9.16:** روابط معاينة بمفتاح سري (`?sh_preview=<key>`، والمسار `POST/DELETE seohouse/v1/preview-key`).
  - الروابط noindex وبدون كاش.
  - المفتاح اتلغى بعد النشر.
- **2.9.17:** المسار `POST seohouse/v1/cases/approve` بيرجّع الدراسات القديمة مسودات، ويعمل تحويلات 301، ويفعّل `sh_cases_v2`.
  - التحويلات بتتحفظ في `sh_case_redirects`، وبتشتغل لما الرابط يرجّع 404.
  - تحويلات Rank Math محتاجة صلاحية مش موجودة لحساب النشر (403)، فالتحويلات شغالة من Core.
- **الأدلة:** 29 لقطة WebP (تصغير فقط، بدون قص) بنص بديل وصفي، أرقامها 3266–3294 (`evidence-live.json`).
  - 6 لقطات Analytics، و13 لقطة ترتيب للدراسات، و10 لقطات لقسم صفحة النتائج.
  - أرقام كل لقطة Analytics اتراجعت على البرومبت.
- **منشور:** الدراسات السبعة، وقسم الترتيب ظاهر.
- **اتسحب:** 157 و158 و159 بقوا مسودات، والتحويلات:
  - جلستك القديمة ← `/results/jalsatak/`.
  - لافيزون القديمة ← `/results/lavison-clinics/`.
  - مبهر ← `/results/`.
- **خريطة الموقع:** فيها الدراسات السبعة الجديدة فقط بالعربي.
- **الإنجليزي:** الدراسات الإنجليزية الثلاث (ترجمة القديمة) لسه منشورة، ومستنية قرار.

## بعد النشر (9 نوفمبر، Core 2.9.18–2.9.21 + القالب 2.8.11–2.8.13)
- **اللوجوهات ولقطة Analytics جلستك:** كانت محفوظة بأرقام مرفقات النسخة المحلية (ملف البيانات اتكتب عليه في تجربة محلية)، واتصلحت بأرقام الموقع.
  - `content/case` بقى يقبل `allow_published` لتصحيح دراسة منشورة.
- **الجاليري:**
  - كل اللقطات في إطار واحد 3:2، والصورة كاملة جواه بدون قص.
  - العارض بيعرض الصورة ولوحة تفاصيل: العميل، والمجال، وكلمة البحث، والترتيب، وتاريخ اللقطة، والوصف.
  - فيه تنقل بالأسهم والكيبورد حسب اتجاه اللغة، واللوحة بتنزل تحت الصورة على الموبايل.
- **تاريخ اللقطة** (حقل `date` في قوائم الترتيب): 1 نوفمبر 2026، وهو تاريخ ملفات اللقطات. بيظهر تحت كل لقطة وفي العارض.
- **باقي لقطات الترتيب:** 42 لقطة إضافية، اتراجعت واحدة واحدة (`evidence2.py`).
  - الدراسات: لافيزون 5، دربك 4، الروضة 6، العربية للحاسبات 6، جنان 9، شي سيف 4.
  - قسم صفحة النتائج: 31 لقطة.
  - `jinantravels.com/11.png` متضافتش، حسب البرومبت.
- **الترتيب:** بيتكتب بس زي ما هو ظاهر في اللقطة. لو اللقطة مقصوصة من فوق، المكتوب «ضمن النتائج الأولى».
- **العملاء من غير دراسة حالة** (عنوان الإضاءة، ام كيه قباني، بن عفيف، سهل، بداية): مفيش لقطات Analytics، فمتعملهمش دراسات.
- **الإنجليزي:**
  - الدراسات السبعة اتنشرت على `/en/results/<slug>/` (slug `-en`، رابط بنفس شكل العربي).
  - القديمة 3127 و3138 و3149 بقت مسودات، ومعاها تحويلات 301.
  - قسم الترتيب الإنجليزي فيه 31 لقطة.
  - **2.9.19:** روابط الترجمة الإنجليزية لدراسات الحالة كانت بترجع 404، لأن `case_study=<slug>` كان فاضل في الطلب، واتصلحت.
- **كروت الإنجليزي:** عمود الرقم أعرض، فوصف المؤشر بقى على سطر واحد زي العربي.

## Core 2.9.22 + القالب 2.8.14
- **عارض اللقطات** (`parts/shot-gallery.php`): بيُستخدم في قسم صفحة النتائج، وفي الدراسات اللي فيها 4 لقطات أو أكتر.
  - لقطة كبيرة وتحتها تفاصيلها، وقايمة معاينات صغيرة بسكرول (أفقي على الموبايل)، وفلتر بالعميل.
  - الصورة الكبيرة بتفتح العارض الكامل، وبيتنقل بين كل القايمة.
  - الصور الصغيرة بمقاس medium وبتتحمّل lazy. صور صفحة النتائج بقت حوالي 350KB بدل 31 صورة كبيرة.
- **تواريخ الدراسات:**
  - فترة النتائج (`main_after_period`) كعلامة بأيقونة تقويم على الكروت.
  - مربع العميل فيه: المجال، والدولة، وفترة النتائج، وتاريخ الدراسة (حقل جديد `case_date`: نوفمبر 2026 / November 2026).
- **الصفحة الرئيسية:** قايمة النتائج بقت بكارت صفحة النتائج (لوجو، اسم، مجال · دولة، فترة). عمود الرقم بقى أعرض، لأن 120px كانت بتخلّي «+15,800%» يركب على الاسم.

## 2.8.15 – 2.8.22 (theme) + 4 new case studies (9 Nov 2026)

- Case cards: a small, plain «فترة النتائج: …» (icon + text, no box) in the card's free space, set apart from «دراسة الحالة». On the home (narrower column) it sits above the link. A client without a logo gets their initial in the logo box.
- English cards: narrower number column (names on one line); on the English home the label under the number can wrap.
- Screenshot browser: client filter is now a drop-down (`select[data-sg-filter]`, «كل العملاء (31)», one option per client with its count); the count beside it follows the filter; client headings in the list; fade + «مرّر لعرض باقي اللقطات» until the end of the list.
- Case page client box (`.sh-cs-client`): logo centered on top, the details (field, country, results period, case date) in a 2 × 2 grid under it, the same in Arabic and English; used for every public client, with or without a logo. The result column is fixed at 180–230px.
- New published case studies, built only from the Google screenshots (no Analytics, so no before/after numbers or work steps): Lighting Address 3520/3533, MK Kabbani Furniture 3546/3559, Bin Afif Property Management 3572/3585, Bedaya Psychiatric Center 3598/3607 (AR/EN). Numbers = screenshot counts (searches in the top results; first result; cited in Google's AI Overview), screenshots dated 1 Nov 2026. Source: `docs/case-studies-2026-11/rank_cases.py` and `rank-cases-*.json`.
- Logos: Bedaya from the brief (3519); Lighting Address, MK Kabbani and Bin Afif taken from the clients' own websites (3664–3666).
- Note: `content/case` finds an existing case by `id` only, not by slug — sending a slug again creates a copy (`-2`). Always send `id` + `allow_published` for an update.
