/**
 * سيو هاوس — Main Theme JS
 * Handles: nav scroll, burger menu, mega menu, scroll reveal, FAQ, calendar, filters
 */
(function () {
  document.addEventListener('DOMContentLoaded', function () {
    initNav();
    initSR();
    initFAQ();
    initCalendar();
    initFilters();
    initTimeSlots();
    initBookBtn();
    initContactForm();
  });

  /* ── Navigation ── */
  function initNav() {
    const nav = document.getElementById('nav');
    if (nav) {
      window.addEventListener('scroll', function () {
        nav.classList.toggle('solid', window.scrollY > 40);
      }, { passive: true });
    }

    const burger = document.getElementById('burger');
    const mob = document.getElementById('mob');
    if (burger && mob) {
      burger.addEventListener('click', function () {
        burger.classList.toggle('on');
        mob.classList.toggle('on');
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

  /* ── Booking calendar ── */
  var calYear = new Date().getFullYear();
  var calMonth = new Date().getMonth();
  var calSelectedDay = -1;
  var months = ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو',
    'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'];

  function renderCalendar() {
    var mEl = document.getElementById('calMonth');
    var dEl = document.getElementById('calDays');
    if (!mEl || !dEl) return;

    mEl.textContent = months[calMonth] + ' ' + calYear;
    var firstDay = new Date(calYear, calMonth, 1).getDay();
    var daysInMonth = new Date(calYear, calMonth + 1, 0).getDate();
    var today = new Date();
    var html = '';

    for (var i = 0; i < firstDay; i++) {
      html += '<div class="cal-d empty"></div>';
    }
    for (var d = 1; d <= daysInMonth; d++) {
      var dt = new Date(calYear, calMonth, d);
      var todayMidnight = new Date(today.getFullYear(), today.getMonth(), today.getDate());
      var isPast = dt < todayMidnight;
      var isToday = dt.toDateString() === today.toDateString();
      var isSel = d === calSelectedDay;
      html += '<button class="cal-d' + (isPast ? ' past' : '') + (isToday ? ' today' : '') + (isSel ? ' sel' : '') + '" data-d="' + d + '">' + d + '</button>';
    }
    dEl.innerHTML = html;

    dEl.querySelectorAll('.cal-d:not(.past):not(.empty)').forEach(function (el) {
      el.addEventListener('click', function () {
        calSelectedDay = parseInt(el.dataset.d);
        renderCalendar();
      });
    });
  }

  function initCalendar() {
    var prevBtn = document.getElementById('calPrev');
    var nextBtn = document.getElementById('calNext');
    if (!prevBtn && !nextBtn) return;

    if (prevBtn) {
      prevBtn.addEventListener('click', function () {
        calMonth--;
        if (calMonth < 0) { calMonth = 11; calYear--; }
        renderCalendar();
      });
    }
    if (nextBtn) {
      nextBtn.addEventListener('click', function () {
        calMonth++;
        if (calMonth > 11) { calMonth = 0; calYear++; }
        renderCalendar();
      });
    }
    renderCalendar();
  }

  /* ── Time slots ── */
  function initTimeSlots() {
    document.querySelectorAll('.ts:not(.taken)').forEach(function (ts) {
      ts.addEventListener('click', function () {
        document.querySelectorAll('.ts').forEach(function (t) { t.classList.remove('sel'); });
        ts.classList.add('sel');
      });
    });
  }

  /* ── Book button ── */
  function initBookBtn() {
    var btn = document.getElementById('bookBtn');
    if (!btn) return;
    btn.addEventListener('click', function () {
      btn.textContent = '✓ تم الحجز — ستصلك رسالة التأكيد';
      btn.style.background = 'var(--green)';
      setTimeout(function () {
        btn.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>تأكيد الحجز';
        btn.style.background = '';
      }, 3500);
    });
  }

  /* ── Contact form ── */
  function initContactForm() {
    var form = document.getElementById('conForm');
    if (!form) return;
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var wrap = document.getElementById('formWrap');
      var success = document.getElementById('formSuccess');
      if (wrap) wrap.style.display = 'none';
      if (success) success.style.display = 'block';
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
