/**
 * سيو هاوس — Shared Navigation & Footer v4
 * Updated: removed stores-seo service, added ecommerce sector, updated footer desc
 */
(function() {
  const path = location.pathname.split('/').pop() || 'index.html';

  const isWebDesign = ['web-design.html','wordpress.html','webflow.html','react-next.html','custom-dev.html'].includes(path);
  const isStores = ['stores.html','shopify.html','salla.html','zid.html','woocommerce.html'].includes(path);
  const isSEO = ['seo-service.html','backlinks.html','content.html','consulting.html'].includes(path);

  const navHTML = `
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="shared.css">
<header id="nav">
  <div class="wrap">
    <nav class="nav-bar">
      <a href="index.html" class="nav-logo" aria-label="سيو هاوس">
        <img src="uploads/White.png" alt="سيو هاوس" class="logo-w" height="30">
        <img src="uploads/Full color - on white background.png" alt="سيو هاوس" class="logo-c" height="30">
      </a>
      <ul class="nav-links">
        <li class="ni"><a href="index.html" ${path==='index.html'?'class="active"':''}>الرئيسية</a></li>
        <li class="ni" id="svcNi">
          <button class="ni-btn" id="svcBtn">الخدمات
            <svg class="chev" viewBox="0 0 24 24" fill="none" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
          </button>
          <div class="mega mega-wide">
            <div class="mega-body mega-body-wide">
              <nav class="mega-main">
                <p class="mega-lbl">تحسين محركات البحث</p>
                <a href="seo-service.html" class="mi ${isSEO && path==='seo-service.html'?'act':''}" data-sub="sub1">
                  <div class="mi-l"><div class="mi-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg></div><span>تحسين محركات البحث</span></div>
                  <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="var(--muted)" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
                </a>
                <p class="mega-lbl" style="margin-top:10px">إنشاء وتصميم</p>
                <a href="web-design.html" class="mi ${isWebDesign?'act':''}" data-sub="sub2">
                  <div class="mi-l"><div class="mi-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8m-4-4v4"/></svg></div><span>إنشاء وتصميم مواقع</span></div>
                  <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="var(--muted)" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
                </a>
                <a href="stores.html" class="mi ${isStores?'act':''}" data-sub="sub3">
                  <div class="mi-l"><div class="mi-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg></div><span>إنشاء وتصميم متاجر</span></div>
                  <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="var(--muted)" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
                </a>
                <a href="products.html" class="mi ${path==='products.html'?'act':''}" data-sub="sub4">
                  <div class="mi-l"><div class="mi-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg></div><span>رفع المنتجات للمتاجر</span></div>
                </a>
              </nav>
              <div class="mega-sub ${isSEO?'show':''}" id="sub1">
                <p class="mega-sub-lbl">خدمات السيو الفرعية</p>
                <div class="mega-sub-grid">
                  <a href="backlinks.html" class="sl"><span class="sl-dot"></span><div><strong>بناء الباك لينك</strong><p>روابط تقوّي سلطة موقعك</p></div></a>
                  <a href="content.html" class="sl"><span class="sl-dot"></span><div><strong>كتابة المحتوى</strong><p>محتوى يُرضي القارئ وجوجل</p></div></a>
                  <a href="consulting.html" class="sl"><span class="sl-dot"></span><div><strong>استشارات تحليل الأداء</strong><p>قراءة التقارير واتخاذ القرار</p></div></a>
                </div>
              </div>
              <div class="mega-sub ${isWebDesign?'show':''}" id="sub2">
                <p class="mega-sub-lbl">تخصّصات تصميم المواقع</p>
                <div class="mega-sub-grid">
                  <a href="wordpress.html" class="sl"><span class="sl-dot"></span><div><strong>تصميم مواقع ووردبريس</strong><p>إدارة محتوى مرنة لمواقع الشركات والتسويق</p></div></a>
                  <a href="webflow.html" class="sl"><span class="sl-dot"></span><div><strong>تصميم مواقع ويب فلو</strong><p>تصاميم عالية الجودة بإطلاق سريع</p></div></a>
                  <a href="react-next.html" class="sl"><span class="sl-dot"></span><div><strong>تصميم مواقع رياكت ونكست</strong><p>أداء متقدّم لمنتجات رقمية قابلة للتوسّع</p></div></a>
                  <a href="custom-dev.html" class="sl"><span class="sl-dot"></span><div><strong>برمجة مواقع خاصة</strong><p>متطلبات فريدة وأنظمة مخصّصة بالكامل</p></div></a>
                </div>
              </div>
              <div class="mega-sub ${isStores?'show':''}" id="sub3">
                <p class="mega-sub-lbl">منصّات المتاجر</p>
                <div class="mega-sub-grid">
                  <a href="shopify.html" class="sl"><span class="sl-dot"></span><div><strong>إنشاء متاجر شوبيفاي</strong><p>تجارة إلكترونية مرنة بمعايير دولية</p></div></a>
                  <a href="salla.html" class="sl"><span class="sl-dot"></span><div><strong>إنشاء متاجر سلة</strong><p>منصّة سعودية جاهزة للسوق المحلي</p></div></a>
                  <a href="zid.html" class="sl"><span class="sl-dot"></span><div><strong>إنشاء متاجر زد</strong><p>بنية محلية قوية لانطلاقة احترافية</p></div></a>
                  <a href="woocommerce.html" class="sl"><span class="sl-dot"></span><div><strong>إنشاء متاجر ووكومرس</strong><p>تحكّم كامل على ووردبريس</p></div></a>
                </div>
              </div>
              <div class="mega-sub" id="sub4"><p class="mega-sub-lbl">رفع المنتجات للمتاجر</p><p style="font-size:13px;color:var(--muted);line-height:1.8;padding-top:6px">رفع منظّم واحترافي عبر جميع المنصات — ليس مجرد إدخال بيانات.</p></div>
            </div>
            <div class="mega-foot"><a href="seo-service.html" class="mega-all">جميع خدمات السيو <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a></div>
          </div>
        </li>
        <li class="ni"><a href="results.html" ${path==='results.html'?'class="active"':''}>نتائج الأعمال</a></li>
        <li class="ni"><a href="about.html" ${path==='about.html'?'class="active"':''}>عن الشركة</a></li>
        <li class="ni"><a href="team.html" ${path==='team.html'?'class="active"':''}>فريق العمل</a></li>
        <li class="ni" id="secNi">
          <button class="ni-btn" id="secBtn">القطاعات
            <svg class="chev" viewBox="0 0 24 24" fill="none" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
          </button>
          <div class="sec-drop">
            <div class="sec-drop-grid">
              <a href="sector-ecommerce.html" class="sd-link"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>التجارة الإلكترونية</a>
              <a href="sector-health.html" class="sd-link"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>الصحة والطب</a>
              <a href="sector-education.html" class="sd-link"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2z"/><path d="M22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z"/></svg>التعليم والتدريب</a>
              <a href="sector-food.html" class="sd-link"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M18 8h1a4 4 0 010 8h-1"/><path d="M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8z"/></svg>الأغذية والمطاعم</a>
              <a href="sector-legal.html" class="sd-link"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 6l9 6 9-6"/><path d="M3 6v12l9 6 9-6V6"/></svg>القانون والاستشارات</a>
              <a href="sector-tech.html" class="sd-link"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>التقنية والبرمجيات</a>
            </div>
            <div style="padding:8px 6px 4px;border-top:1px solid var(--line);margin-top:6px">
              <a href="sectors.html" style="font-size:12px;font-weight:700;color:var(--blue);display:flex;align-items:center;gap:5px;padding:6px 10px;border-radius:6px">جميع القطاعات <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a>
            </div>
          </div>
        </li>
        <li class="ni"><a href="blog.html" ${path==='blog.html'||path==='blog-post.html'?'class="active"':''}>المدونة</a></li>
        <li class="ni"><a href="contact.html" ${path==='contact.html'?'class="active"':''}>اتصل بنا</a></li>
      </ul>
      <div class="nav-end">
        <a href="contact.html" class="btn btn-p" style="font-size:13px;padding:9px 18px">استشارة مجانية</a>
        <button class="burger" id="burger"><span></span><span></span><span></span></button>
      </div>
    </nav>
    <div class="mob" id="mob">
      <a href="index.html">الرئيسية</a>
      <div class="mob-head">تحسين محركات البحث</div>
      <a href="seo-service.html" class="mob-sub">تحسين محركات البحث</a>
      <a href="backlinks.html" class="mob-sub" style="padding-inline-start:calc(var(--pad) + 16px)">بناء الباك لينك</a>
      <a href="content.html" class="mob-sub" style="padding-inline-start:calc(var(--pad) + 16px)">كتابة المحتوى</a>
      <a href="consulting.html" class="mob-sub" style="padding-inline-start:calc(var(--pad) + 16px)">استشارات الأداء</a>
      <div class="mob-head">إنشاء وتصميم مواقع</div>
      <a href="web-design.html" class="mob-sub">نظرة عامة</a>
      <a href="wordpress.html" class="mob-sub" style="padding-inline-start:calc(var(--pad) + 16px)">تصميم ووردبريس</a>
      <a href="webflow.html" class="mob-sub" style="padding-inline-start:calc(var(--pad) + 16px)">تصميم ويب فلو</a>
      <a href="react-next.html" class="mob-sub" style="padding-inline-start:calc(var(--pad) + 16px)">تصميم رياكت ونكست</a>
      <a href="custom-dev.html" class="mob-sub" style="padding-inline-start:calc(var(--pad) + 16px)">برمجة مواقع خاصة</a>
      <div class="mob-head">إنشاء وتصميم متاجر</div>
      <a href="stores.html" class="mob-sub">نظرة عامة</a>
      <a href="shopify.html" class="mob-sub" style="padding-inline-start:calc(var(--pad) + 16px)">متاجر شوبيفاي</a>
      <a href="salla.html" class="mob-sub" style="padding-inline-start:calc(var(--pad) + 16px)">متاجر سلة</a>
      <a href="zid.html" class="mob-sub" style="padding-inline-start:calc(var(--pad) + 16px)">متاجر زد</a>
      <a href="woocommerce.html" class="mob-sub" style="padding-inline-start:calc(var(--pad) + 16px)">متاجر ووكومرس</a>
      <a href="products.html" class="mob-sub">رفع المنتجات</a>
      <a href="results.html">نتائج الأعمال</a>
      <a href="about.html">عن الشركة</a>
      <a href="team.html">فريق العمل</a>
      <a href="sectors.html">القطاعات</a>
      <a href="blog.html">المدونة</a>
      <a href="contact.html" class="mob-cta">احجز استشارة مجانية</a>
    </div>
  </div>
</header>`;

  const footerHTML = `
<footer id="footer">
  <div class="wrap">
    <div class="foot-top">
      <div class="foot-brand">
        <img src="uploads/White.png" alt="سيو هاوس" height="28">
        <p>متخصصون في تحسين محركات البحث وبناء المتاجر الإلكترونية. نخدم الأسواق السعودية والخليجية ونعمل بمنطق الأداء.</p>
        <div class="socials">
          <a href="#" class="soc" aria-label="X"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/></svg></a>
          <a href="#" class="soc" aria-label="LinkedIn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg></a>
          <a href="#" class="soc" aria-label="Instagram"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg></a>
        </div>
      </div>
      <div class="foot-col">
        <h4>تحسين محركات البحث</h4>
        <div class="foot-links">
          <a href="seo-service.html">خدمة السيو الشاملة</a>
          <a href="backlinks.html">بناء الباك لينك</a>
          <a href="content.html">كتابة المحتوى</a>
          <a href="consulting.html">استشارات الأداء</a>
        </div>
      </div>
      <div class="foot-col">
        <h4>تصميم المواقع</h4>
        <div class="foot-links">
          <a href="wordpress.html">تصميم ووردبريس</a>
          <a href="webflow.html">تصميم ويب فلو</a>
          <a href="react-next.html">رياكت ونكست</a>
          <a href="custom-dev.html">برمجة خاصة</a>
        </div>
      </div>
      <div class="foot-col">
        <h4>إنشاء المتاجر</h4>
        <div class="foot-links">
          <a href="shopify.html">متاجر شوبيفاي</a>
          <a href="salla.html">متاجر سلة</a>
          <a href="zid.html">متاجر زد</a>
          <a href="woocommerce.html">متاجر ووكومرس</a>
          <a href="products.html">رفع المنتجات</a>
        </div>
      </div>
      <div class="foot-col">
        <h4>الشركة</h4>
        <div class="foot-links">
          <a href="about.html">عن سيو هاوس</a>
          <a href="team.html">فريق العمل</a>
          <a href="results.html">نتائج الأعمال</a>
          <a href="sectors.html">القطاعات</a>
          <a href="blog.html">المدونة</a>
          <a href="contact.html">اتصل بنا</a>
        </div>
      </div>
    </div>
    <div class="foot-bot">
      <p>© 2025 سيو هاوس. جميع الحقوق محفوظة.</p>
      <div class="foot-leg">
        <a href="#">سياسة الخصوصية</a>
        <a href="#">الشروط والأحكام</a>
      </div>
    </div>
  </div>
</footer>`;

  document.write(navHTML);
  document.addEventListener('DOMContentLoaded', function() {
    document.body.insertAdjacentHTML('beforeend', footerHTML);
    initNav();
    initSR();
    initFAQ();
  });

  function initNav() {
    const nav = document.getElementById('nav');
    if(nav) window.addEventListener('scroll', () => nav.classList.toggle('solid', scrollY > 40), {passive:true});
    const burger = document.getElementById('burger');
    const mob = document.getElementById('mob');
    if(burger) burger.addEventListener('click', () => { burger.classList.toggle('on'); mob.classList.toggle('on'); });
    const svcNi = document.getElementById('svcNi');
    const svcBtn = document.getElementById('svcBtn');
    const secNi = document.getElementById('secNi');
    const secBtn = document.getElementById('secBtn');
    if(svcBtn) svcBtn.addEventListener('click', e => { e.stopPropagation(); svcNi.classList.toggle('op'); secNi?.classList.remove('op'); });
    if(secBtn) secBtn.addEventListener('click', e => { e.stopPropagation(); secNi.classList.toggle('op'); svcNi?.classList.remove('op'); });
    document.addEventListener('click', () => { svcNi?.classList.remove('op'); secNi?.classList.remove('op'); });
    svcNi?.addEventListener('click', e => e.stopPropagation());
    secNi?.addEventListener('click', e => e.stopPropagation());
    document.querySelectorAll('.mi[data-sub]').forEach(item => {
      item.addEventListener('mouseenter', () => {
        document.querySelectorAll('.mi').forEach(i => i.classList.remove('act'));
        document.querySelectorAll('.mega-sub').forEach(s => s.classList.remove('show'));
        item.classList.add('act');
        const sub = document.getElementById(item.dataset.sub);
        if(sub) sub.classList.add('show');
      });
    });
  }

  function initSR() {
    const obs = new IntersectionObserver(es => es.forEach(e => {
      if(e.isIntersecting) { e.target.classList.add('in'); obs.unobserve(e.target); }
    }), {threshold:.08});
    document.querySelectorAll('.sr').forEach(el => obs.observe(el));
  }

  function initFAQ() {
    document.querySelectorAll('.faq-item').forEach(item => {
      item.querySelector('.faq-q')?.addEventListener('click', () => {
        const isOpen = item.classList.contains('open');
        document.querySelectorAll('.faq-item').forEach(i => i.classList.remove('open'));
        if(!isOpen) item.classList.add('open');
      });
    });
    document.querySelector('.faq-item')?.classList.add('open');
  }
})();
