/* service-seo-final.js — scoped to .svc-seo-final */
(function () {
  'use strict';

  /* ── Typing animation ──────────────────────────────────────────── */
  var typedEl = document.getElementById('sfTyped');
  if (typedEl) {
    var phrases = ['مكتب محاماة', 'مكتب محاسبة', 'متجر فرشاة شعر'];
    var pi = 0, ci = 0, deleting = false;
    function tick() {
      var phrase = phrases[pi];
      typedEl.textContent = deleting ? phrase.slice(0, ci--) : phrase.slice(0, ci++);
      if (!deleting && ci > phrase.length) { deleting = true; setTimeout(tick, 1800); return; }
      if (deleting && ci < 0) { deleting = false; pi = (pi + 1) % phrases.length; ci = 0; setTimeout(tick, 320); return; }
      setTimeout(tick, deleting ? 55 : 110);
    }
    tick();
  }

  /* ── Scroll reveal ─────────────────────────────────────────────── */
  var srEls = document.querySelectorAll('.svc-seo-final .sr');
  if (srEls.length && window.IntersectionObserver) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); }
      });
    }, { threshold: 0.12 });
    srEls.forEach(function (el) { io.observe(el); });
  } else {
    srEls.forEach(function (el) { el.classList.add('in'); });
  }

  /* ── Pillars: scroll-driven panel switching ────────────────────── */
  (function () {
    var section = document.getElementById('pillars');
    if (!section) return;
    var tabs   = section.querySelectorAll('.pil-count-item');
    var panels = document.querySelectorAll('#pilPanels .pil-panel');
    var strip  = document.getElementById('pilCount');
    var dots   = document.querySelectorAll('#pilProgress .pil-dot');
    var cur = -1, raf = null;

    function getPilTop() {
      var hdr = document.querySelector('.site-header') || document.querySelector('header:not(#pillars *)');
      var hdrH = hdr ? hdr.getBoundingClientRect().height : 0;
      var adminBar = document.getElementById('wpadminbar');
      return hdrH + (adminBar ? adminBar.getBoundingClientRect().height : 0);
    }

    function activate(idx) {
      if (cur === idx) return;
      cur = idx;
      tabs.forEach(function (t) {
        t.classList.remove('active');
        t.setAttribute('aria-selected', 'false');
        t.setAttribute('tabindex', '-1');
      });
      var tab = document.querySelector('#pilCount .pil-count-item[data-i="' + idx + '"]');
      if (tab) {
        tab.classList.add('active');
        tab.setAttribute('aria-selected', 'true');
        tab.setAttribute('tabindex', '0');
        if (strip && strip.scrollWidth > strip.clientWidth) {
          var sr = strip.getBoundingClientRect();
          var tr = tab.getBoundingClientRect();
          strip.scrollLeft += (tr.left + tr.width / 2) - (sr.left + sr.width / 2);
        }
      }
      panels.forEach(function (p) {
        p.classList.remove('pil-panel-active');
        p.setAttribute('aria-hidden', 'true');
      });
      var panel = document.querySelector('#pilPanels .pil-panel[data-i="' + idx + '"]');
      if (panel) { panel.classList.add('pil-panel-active'); panel.removeAttribute('aria-hidden'); }
      dots.forEach(function (d, i) { d.classList.toggle('pil-dot-on', i === idx); });
    }

    function tick() {
      raf = null;
      var sTop = section.getBoundingClientRect().top;
      var scrolled = -sTop;
      var range = section.offsetHeight - window.innerHeight;
      if (range <= 0) { activate(0); return; }
      var progress = Math.max(0, Math.min(1, scrolled / range));
      activate(Math.min(Math.floor(progress * 5), 4));
    }

    window.addEventListener('scroll', function () {
      if (!raf) raf = requestAnimationFrame(tick);
    }, { passive: true });
    tick();

    tabs.forEach(function (tab) {
      tab.addEventListener('click', function () {
        var idx = parseInt(tab.dataset.i, 10);
        var range = section.offsetHeight - window.innerHeight;
        window.scrollTo({ top: section.offsetTop + range * (idx + 0.5) / 5, behavior: 'smooth' });
      });
      tab.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); tab.click(); }
      });
    });

    function setTop() {
      document.documentElement.style.setProperty('--pil-top', getPilTop() + 'px');
    }
    setTop();
    window.addEventListener('resize', setTop, { passive: true });
  })();

  /* ── Team filmstrip: start after page images ready ──────────────── */
  var stripEl = document.querySelector('.svc-seo-final .strip');
  if (stripEl) {
    stripEl.classList.add('strip-pre');
    window.addEventListener('load', function () { stripEl.classList.remove('strip-pre'); });
  }

  /* ── Results tabs ──────────────────────────────────────────────── */
  var rxTabs   = document.querySelectorAll('.svc-seo-final .rx-tab');
  var rxPanels = document.querySelectorAll('.svc-seo-final .rx-panel');
  rxTabs.forEach(function (tab) {
    tab.addEventListener('click', function () {
      var idx = tab.dataset.i;
      rxTabs.forEach(function (t)   { t.classList.remove('active'); });
      rxPanels.forEach(function (p) { p.classList.remove('on'); });
      tab.classList.add('active');
      var panel = document.querySelector('.svc-seo-final .rx-panel[data-i="' + idx + '"]');
      if (panel) panel.classList.add('on');
    });
  });

  /* ── Lightbox ──────────────────────────────────────────────────── */
  var lb     = document.getElementById('sfLb');
  var lbImg  = lb ? lb.querySelector('img') : null;
  var lbX    = lb ? lb.querySelector('.sf-lb-x') : null;
  function openLb(src) {
    if (!lb || !lbImg) return;
    lbImg.src = src;
    lb.classList.add('on');
    document.body.style.overflow = 'hidden';
  }
  function closeLb() {
    if (!lb) return;
    lb.classList.remove('on');
    lbImg.src = '';
    document.body.style.overflow = '';
  }
  document.querySelectorAll('.svc-seo-final [data-lb]').forEach(function (el) {
    el.addEventListener('click', function () { openLb(el.dataset.lb); });
  });
  if (lbX)  lbX.addEventListener('click', closeLb);
  if (lb)   lb.addEventListener('click', function (e) { if (e.target === lb) closeLb(); });
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeLb(); });

  /* ── FAQ accordion ─────────────────────────────────────────────── */
  document.querySelectorAll('.svc-seo-final .faq-q').forEach(function (q) {
    q.addEventListener('click', function () {
      var item = q.closest('.faq-item');
      var isOpen = item.classList.contains('open');
      document.querySelectorAll('.svc-seo-final .faq-item').forEach(function (i) {
        i.classList.remove('open');
        var a = i.querySelector('.faq-a');
        if (a) a.style.maxHeight = '0';
      });
      if (!isOpen) {
        item.classList.add('open');
        var ans = item.querySelector('.faq-a');
        if (ans) ans.style.maxHeight = ans.scrollHeight + 'px';
      }
    });
  });

})();
