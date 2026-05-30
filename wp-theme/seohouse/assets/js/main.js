/**
 * سيو هاوس — Main Theme JS
 * Handles: nav scroll, burger menu, mega menu, scroll reveal, FAQ, calendar, filters
 */
(function () {
  document.addEventListener('DOMContentLoaded', function () {
    initNav();
    initMobAcc();
    initSR();
    initFAQ();
    initFilters();
    initContactForm();
  });

  /* ── Navigation ── */
  function initNav() {
    const nav = document.getElementById('nav');
    const burger = document.getElementById('burger');
    const mob = document.getElementById('mob');

    if (nav) {
      window.addEventListener('scroll', function () {
        nav.classList.toggle('solid', window.scrollY > 40 || (mob && mob.classList.contains('on')));
      }, { passive: true });
    }

    if (burger && mob) {
      function closeMob() {
        burger.classList.remove('on');
        mob.classList.remove('on');
        document.body.classList.remove('mob-open');
        if (nav && window.scrollY <= 40) nav.classList.remove('solid');
        document.querySelectorAll('.mob-acc').forEach(function (acc) {
          acc.classList.remove('open');
          var tog = acc.querySelector('.mob-acc-tog');
          if (tog) tog.setAttribute('aria-expanded', 'false');
        });
      }
      burger.addEventListener('click', function () {
        var isOpen = mob.classList.toggle('on');
        burger.classList.toggle('on', isOpen);
        document.body.classList.toggle('mob-open', isOpen);
        if (nav) nav.classList.toggle('solid', isOpen || window.scrollY > 40);
      });
      mob.querySelectorAll('a').forEach(function (link) {
        link.addEventListener('click', closeMob);
      });
    }

    const svcNi = document.getElementById('svcNi');
    const svcBtn = document.getElementById('svcBtn');
    const secNi = document.getElementById('secNi');
    const secBtn = document.getElementById('secBtn');

    if (svcBtn) {
      svcBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        svcNi.classList.toggle('op');
        if (secNi) secNi.classList.remove('op');
      });
    }

    if (secBtn) {
      secBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        secNi.classList.toggle('op');
        if (svcNi) svcNi.classList.remove('op');
      });
    }

    document.addEventListener('click', function () {
      if (svcNi) svcNi.classList.remove('op');
      if (secNi) secNi.classList.remove('op');
    });

    if (svcNi) svcNi.addEventListener('click', function (e) { e.stopPropagation(); });
    if (secNi) secNi.addEventListener('click', function (e) { e.stopPropagation(); });

    document.querySelectorAll('.mi[data-sub]').forEach(function (item) {
      item.addEventListener('mouseenter', function () {
        document.querySelectorAll('.mi').forEach(function (i) { i.classList.remove('act'); });
        document.querySelectorAll('.mega-sub').forEach(function (s) { s.classList.remove('show'); });
        item.classList.add('act');
        var sub = document.getElementById(item.dataset.sub);
        if (sub) sub.classList.add('show');
      });
    });
  }

  /* ── Mobile accordion menu ── */
  function initMobAcc() {
    document.querySelectorAll('.mob-acc-tog').forEach(function (tog) {
      tog.addEventListener('click', function (e) {
        e.stopPropagation();
        var acc = tog.closest('.mob-acc');
        if (!acc) return;
        var isOpen = acc.classList.toggle('open');
        tog.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
      });
    });
  }

  /* ── Scroll reveal ── */
  function initSR() {
    if (!('IntersectionObserver' in window)) {
      document.querySelectorAll('.sr').forEach(function (el) { el.classList.add('in'); });
      return;
    }
    var obs = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (e.isIntersecting) {
          e.target.classList.add('in');
          obs.unobserve(e.target);
        }
      });
    }, { threshold: 0.08 });
    document.querySelectorAll('.sr').forEach(function (el) { obs.observe(el); });
  }

  /* ── FAQ accordion ── */
  function initFAQ() {
    var items = document.querySelectorAll('.faq-item');
    items.forEach(function (item) {
      var q = item.querySelector('.faq-q');
      if (!q) return;
      q.addEventListener('click', function () {
        var isOpen = item.classList.contains('open');
        items.forEach(function (i) { i.classList.remove('open'); });
        if (!isOpen) item.classList.add('open');
      });
    });
    var first = document.querySelector('.faq-item');
    if (first) first.classList.add('open');
  }

  /* ── Contact form ── */
  function initContactForm() {
    var form = document.getElementById('conForm');
    if (!form) return;
    var errEl = document.getElementById('conFormError');

    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var btn = form.querySelector('.form-submit');
      if (btn) { btn.disabled = true; btn.style.opacity = '0.7'; }
      if (errEl) errEl.textContent = '';

      var data = new FormData(form);
      data.append('action', 'sh_contact');
      data.append('nonce',  (window.SeohouseData || {}).nonce || '');

      fetch((window.SeohouseData || {}).ajaxUrl || '/wp-admin/admin-ajax.php', {
        method: 'POST',
        body: data,
      })
        .then(function (r) { return r.json(); })
        .then(function (res) {
          if (res.success) {
            var wrap    = document.getElementById('formWrap');
            var success = document.getElementById('formSuccess');
            if (wrap) wrap.style.display = 'none';
            if (success) success.style.display = 'block';
          } else {
            var msg = res.data && res.data.msg ? res.data.msg : 'حدث خطأ، يرجى المحاولة مرة أخرى';
            if (errEl) errEl.textContent = msg;
            if (btn) { btn.disabled = false; btn.style.opacity = ''; }
          }
        })
        .catch(function () {
          if (errEl) errEl.textContent = 'حدث خطأ في الاتصال، يرجى المحاولة مرة أخرى';
          if (btn) { btn.disabled = false; btn.style.opacity = ''; }
        });
    });
  }

  /* ── Result / case study filters ── */
  function initFilters() {
    var buttons = document.querySelectorAll('.filter-btn');
    if (!buttons.length) return;

    buttons.forEach(function (btn) {
      btn.addEventListener('click', function () {
        buttons.forEach(function (b) { b.classList.remove('act'); });
        btn.classList.add('act');

        var filter = btn.dataset.filter || 'all';
        var cards = document.querySelectorAll('.r-card, .cs-card');
        cards.forEach(function (card) {
          if (filter === 'all' || card.dataset.sector === filter) {
            card.style.display = '';
          } else {
            card.style.display = 'none';
          }
        });
      });
    });
  }
})();
