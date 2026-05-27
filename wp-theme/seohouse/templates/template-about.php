<?php
/**
 * Template Name: About Page
 */
get_header();
?>

<?php
get_template_part( 'template-parts/layout/page-hero', null, [
    'tag'         => 'قصتنا',
    'title'       => 'نعمل بمنطق <em>الأداء</em><br>لا بمنطق الوعود',
    'description' => 'سيو هاوس متخصصون في تحسين محركات البحث، يخدمون الأسواق السعودية والخليجية. لا نُقدّم وعوداً — نُقدّم خطوات واضحة ونتائج قابلة للقياس.',
    'breadcrumb'  => [ 'عن الشركة' => '' ],
] );
?>

<section class="sec sec-white">
  <div class="wrap">
    <div class="about-grid">
      <div class="sr">
        <span class="tag" style="margin-bottom:12px">من نحن</span>
        <h2 class="h2" style="margin-bottom:16px">متخصصون في السيو،<br>لا في كل شيء</h2>
        <p class="bod" style="margin-bottom:18px">سيو هاوس تأسست لخدمة الأعمال السعودية والخليجية الباحثة عن حضور رقمي حقيقي في محركات البحث. لم نبنِ عملنا على الوعود — بنيناه على نتائج يمكن قياسها في Search Console.</p>
        <p class="bod" style="margin-bottom:24px">نؤمن أن أفضل طريقة لإثبات قيمتنا هي أن نعمل بشفافية كاملة — تقرير شهري، أهداف محددة، وتواصل مباشر مع الفريق المنفذ.</p>
        <a href="<?php echo esc_url( sh_page_url( 'team' ) ); ?>" class="btn btn-o">تعرّف على فريقنا</a>
      </div>
      <div class="sr d1">
        <div class="story-card">
          <div style="position:relative;z-index:1">
            <div style="font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.24);margin-bottom:20px">مبادئنا في العمل</div>
            <div style="display:flex;flex-direction:column;gap:14px">
              <div style="display:flex;align-items:flex-start;gap:12px;padding:14px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);border-radius:var(--r2)">
                <div style="width:32px;height:32px;border-radius:8px;background:rgba(30,46,245,.22);display:flex;align-items:center;justify-content:center;flex-shrink:0"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#7b90ff" stroke-width="1.8"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div>
                <div><div style="font-size:13.5px;font-weight:700;color:rgba(255,255,255,.8);margin-bottom:3px">البيانات قبل الآراء</div><div style="font-size:12px;color:rgba(255,255,255,.38);line-height:1.65">كل قرار يبدأ من Search Console وGoogle Analytics، لا من التخمين.</div></div>
              </div>
              <div style="display:flex;align-items:flex-start;gap:12px;padding:14px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);border-radius:var(--r2)">
                <div style="width:32px;height:32px;border-radius:8px;background:rgba(30,46,245,.22);display:flex;align-items:center;justify-content:center;flex-shrink:0"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#7b90ff" stroke-width="1.8"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></div>
                <div><div style="font-size:13.5px;font-weight:700;color:rgba(255,255,255,.8);margin-bottom:3px">الشفافية الكاملة</div><div style="font-size:12px;color:rgba(255,255,255,.38);line-height:1.65">تقرير شهري مفصّل — كل ما تحقق وكل ما لم يتحقق ولماذا.</div></div>
              </div>
              <div style="display:flex;align-items:flex-start;gap:12px;padding:14px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);border-radius:var(--r2)">
                <div style="width:32px;height:32px;border-radius:8px;background:rgba(30,46,245,.22);display:flex;align-items:center;justify-content:center;flex-shrink:0"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#7b90ff" stroke-width="1.8"><path d="M9 12l2 2 4-4M22 12c0 5.523-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2s10 4.477 10 10z"/></svg></div>
                <div><div style="font-size:13.5px;font-weight:700;color:rgba(255,255,255,.8);margin-bottom:3px">بدون عقود ملزمة</div><div style="font-size:12px;color:rgba(255,255,255,.38);line-height:1.65">نثبت قيمتنا شهراً بعد شهر — أنت دائماً من يقرر الاستمرار.</div></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="sec sec-surface">
  <div class="wrap">
    <div class="sh c sr"><span class="tag">قيمنا</span><h2 class="h2">ما يحكم طريقة عملنا</h2></div>
    <div class="values-grid">
      <div class="val-card sr">
        <div class="val-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div>
        <h3>الأداء أولاً</h3><p>كل قرار نتخذه يُقاس بأثره على الترتيب والزيارات والتحويلات.</p>
      </div>
      <div class="val-card sr d1">
        <div class="val-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg></div>
        <h3>الصدق مع العميل</h3><p>إذا كانت الكلمة صعبة أو النتيجة ستأخذ وقتاً — نقولها بوضوح.</p>
      </div>
      <div class="val-card sr d2">
        <div class="val-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
        <h3>معايير جوجل فقط</h3><p>نعمل وفق الدلائل الرسمية لجوجل — لا اختصارات تُعرّض موقعك للعقوبة.</p>
      </div>
      <div class="val-card sr d1">
        <div class="val-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg></div>
        <h3>فريق متخصص</h3><p>سيو تقني، محتوى، وباك لينك — خبراء في مجالهم تحت سقف واحد.</p>
      </div>
      <div class="val-card sr d2">
        <div class="val-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2z"/><path d="M22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z"/></svg></div>
        <h3>التطوير المستمر</h3><p>نتابع تحديثات جوجل ونُطوّر استراتيجياتنا باستمرار لنبقى في المقدمة.</p>
      </div>
      <div class="val-card sr d3">
        <div class="val-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
        <h3>الصبر والمتابعة</h3><p>السيو يحتاج وقتاً — نبقى إلى جانبك في كل مرحلة دون تخلٍّ.</p>
      </div>
    </div>
  </div>
</section>

<?php
get_template_part( 'template-parts/layout/cta-banner', null, [
    'tag'         => 'تعرّف أكثر',
    'title'       => 'هل تريد أن تعمل معنا؟',
    'description' => 'ابدأ باستشارة مجانية — وستعرف هل نحن الشريك المناسب لك.',
    'buttons'     => [
        [ 'text' => 'احجز استشارة مجانية', 'url' => sh_page_url( 'contact' ), 'class' => 'btn-w lg' ],
        [ 'text' => 'تعرّف على الفريق',    'url' => sh_page_url( 'team' ),    'class' => 'btn-g lg' ],
    ],
] );
?>

<?php get_footer(); ?>
