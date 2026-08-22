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

  /* ── Pillars: scroll-driven active + click-to-scroll ───────────── */
  var pilScenes = document.querySelectorAll('.svc-seo-final .scene');
  var pilTabs   = document.querySelectorAll('.svc-seo-final .pil-count-item');
  function setPillarActive(idx) {
    pilTabs.forEach(function (t) { t.classList.remove('active'); });
    var tab = document.querySelector('.svc-seo-final .pil-count-item[data-i="' + idx + '"]');
    if (tab) {
      tab.classList.add('active');
      tab.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
    }
  }
  if (pilScenes.length && pilTabs.length) {
    if (window.IntersectionObserver) {
      var sio = new IntersectionObserver(function (entries) {
        entries.forEach(function (e) {
          if (e.isIntersecting) { setPillarActive(e.target.dataset.i); }
        });
      }, { rootMargin: '-30% 0px -60% 0px' });
      pilScenes.forEach(function (s) { sio.observe(s); });
    }
    pilTabs.forEach(function (item) {
      item.addEventListener('click', function () {
        var scene = document.querySelector('.svc-seo-final .scene[data-i="' + item.dataset.i + '"]');
        if (scene) scene.scrollIntoView({ behavior: 'smooth', block: 'start' });
      });
    });
  }

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
