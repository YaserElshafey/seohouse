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
  var pilStrip  = document.getElementById('pilCount');
  var pilCur    = -1;
  var pilRaf    = null;

  function pilActivate(idx) {
    if (pilCur === idx) return;          /* skip if already active */
    pilCur = idx;
    pilTabs.forEach(function (t) { t.classList.remove('active'); });
    var tab = document.querySelector('.svc-seo-final .pil-count-item[data-i="' + idx + '"]');
    if (!tab) return;
    tab.classList.add('active');
    /* Scroll the strip horizontally without touching the page.
       Using getBoundingClientRect (visual coords) so RTL works correctly. */
    if (pilStrip && pilStrip.scrollWidth > pilStrip.clientWidth) {
      var sr = pilStrip.getBoundingClientRect();
      var tr = tab.getBoundingClientRect();
      pilStrip.scrollLeft += (tr.left + tr.width / 2) - (sr.left + sr.width / 2);
    }
  }

  function pilTick() {
    pilRaf = null;
    if (!pilScenes.length) return;
    /* Scene whose vertical midpoint is closest to 38% of viewport height */
    var refY = window.innerHeight * 0.38;
    var best = -1, bestDist = Infinity;
    pilScenes.forEach(function (s) {
      var r = s.getBoundingClientRect();
      var dist = Math.abs(r.top + r.height / 2 - refY);
      if (dist < bestDist) { bestDist = dist; best = parseInt(s.dataset.i, 10); }
    });
    if (best >= 0) pilActivate(best);
  }

  if (pilScenes.length && pilTabs.length) {
    window.addEventListener('scroll', function () {
      if (!pilRaf) pilRaf = requestAnimationFrame(pilTick);
    }, { passive: true });
    pilTick(); /* set initial active tab on load */

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
