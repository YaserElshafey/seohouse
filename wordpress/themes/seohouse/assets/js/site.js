/* SEO House — interactions from the approved design (vanilla JS, no dependencies). */
(function () {
	'use strict';
	var d = document;
	var $$ = function (sel, root) { return Array.prototype.slice.call((root || d).querySelectorAll(sel)); };
	var cfg = window.SH || {};
	// visitor messages in the page language (inc/enqueue.php); the Arabic text is the fallback
	function t(k, ar) { return (cfg.i18n && cfg.i18n[k]) || ar; }

	/* ---------------------------------------------------------------- mega menu */
	var menus = $$('[data-sh-menu]');
	function closeAll(except) {
		menus.forEach(function (m) {
			var b = m.querySelector('button[aria-controls]');
			if (!b || m === except) return;
			b.setAttribute('aria-expanded', 'false');
			var p = d.getElementById(b.getAttribute('aria-controls'));
			if (p) p.hidden = true;
		});
	}
	menus.forEach(function (m) {
		var b = m.querySelector('button[aria-controls]');
		if (!b) return;
		var p = d.getElementById(b.getAttribute('aria-controls'));
		var hoverOpened = false;
		function set(open) { b.setAttribute('aria-expanded', open ? 'true' : 'false'); if (p) p.hidden = !open; }
		m.addEventListener('mouseenter', function () { if (window.matchMedia('(hover: hover)').matches) { closeAll(m); set(true); hoverOpened = true; } });
		m.addEventListener('mouseleave', function () { if (hoverOpened) { set(false); hoverOpened = false; } });
		m.addEventListener('focusout', function (e) { if (!m.contains(e.relatedTarget)) set(false); });
		b.addEventListener('click', function () {
			if (hoverOpened && b.getAttribute('aria-expanded') === 'true') { hoverOpened = false; return; }
			var open = b.getAttribute('aria-expanded') !== 'true';
			closeAll(m); set(open);
			if (open && p) { var first = p.querySelector('a'); if (first && d.activeElement === b && !hoverOpened) { /* keep focus on button; arrow keys move */ } }
		});
		m.addEventListener('keydown', function (e) {
			if (!p || p.hidden) return;
			var links = $$('a', p), i = links.indexOf(d.activeElement);
			if (e.key === 'ArrowDown') { e.preventDefault(); (links[i + 1] || links[0]).focus(); }
			if (e.key === 'ArrowUp') { e.preventDefault(); (links[i - 1] || links[links.length - 1]).focus(); }
		});
	});
	d.addEventListener('pointerdown', function (e) { if (!(e.target.closest && e.target.closest('[data-sh-menu]'))) closeAll(); });

	/* ---------------------------------------------------------------- drawer */
	var drawer = d.getElementById('sh-drawer');
	var opener = d.querySelector('[data-sh-drawer-open]');
	function drawerSet(open) {
		if (!drawer) return;
		drawer.hidden = !open;
		if (opener) opener.setAttribute('aria-expanded', open ? 'true' : 'false');
		d.documentElement.style.overflow = open ? 'hidden' : '';
		d.documentElement.classList.toggle('sh-drawer-open', open); // hides the floating contact buttons (theme.css)
		if (open) { var c = drawer.querySelector('[data-sh-drawer-close]'); if (c) c.focus(); } else if (opener) opener.focus();
	}
	if (opener) opener.addEventListener('click', function () { drawerSet(true); });
	$$('[data-sh-drawer-close]').forEach(function (b) { b.addEventListener('click', function () { drawerSet(false); }); });
	if (drawer) {
		drawer.addEventListener('click', function (e) { var a = e.target.closest('a'); if (a && a.getAttribute('href').charAt(0) === '#') drawerSet(false); });
		drawer.addEventListener('keydown', function (e) {
			if (e.key !== 'Tab') return;
			var f = $$('a,button', drawer).filter(function (x) { return x.offsetParent !== null; });
			if (!f.length) return;
			if (e.shiftKey && d.activeElement === f[0]) { e.preventDefault(); f[f.length - 1].focus(); }
			else if (!e.shiftKey && d.activeElement === f[f.length - 1]) { e.preventDefault(); f[0].focus(); }
		});
	}
	$$('[data-sh-group]').forEach(function (b) {
		b.addEventListener('click', function () {
			var p = d.getElementById(b.getAttribute('aria-controls'));
			var open = b.getAttribute('aria-expanded') !== 'true';
			$$('[data-sh-group]').forEach(function (o) {
				if (o === b) return; o.setAttribute('aria-expanded', 'false');
				var op = d.getElementById(o.getAttribute('aria-controls')); if (op) op.hidden = true;
				var s = o.querySelector('[data-sign]'); if (s) s.textContent = '+';
			});
			b.setAttribute('aria-expanded', open ? 'true' : 'false');
			if (p) { p.hidden = !open; p.style.display = open ? 'flex' : ''; }
			var sign = b.querySelector('[data-sign]'); if (sign) sign.textContent = open ? '−' : '+';
		});
	});
	d.addEventListener('keydown', function (e) {
		if (e.key !== 'Escape') return;
		var openBtn = d.querySelector('[data-sh-menu] button[aria-expanded="true"]');
		if (openBtn) { closeAll(); openBtn.focus(); return; }
		if (drawer && !drawer.hidden) drawerSet(false);
		var lb = d.querySelector('.sh-lightbox'); if (lb) lb.remove();
	});

	/* ---------------------------------------------------------------- accordions (FAQ and design accordions) */
	$$('[data-sh-accordion]').forEach(function (root) {
		var single = root.getAttribute('data-sh-accordion') !== 'multi';
		$$('[data-sh-acc]', root).forEach(function (b) {
			b.addEventListener('click', function () {
				var open = b.getAttribute('aria-expanded') !== 'true';
				if (single) $$('[data-sh-acc]', root).forEach(function (o) { if (o !== b) toggle(o, false); });
				toggle(b, open);
			});
		});
	});
	function toggle(b, open) {
		b.setAttribute('aria-expanded', open ? 'true' : 'false');
		var p = d.getElementById(b.getAttribute('aria-controls'));
		if (p) p.hidden = !open;
		var s = b.querySelector('[data-sign]'); if (s) s.textContent = open ? (s.getAttribute('data-on') || '−') : (s.getAttribute('data-off') || '+');
		$$('[data-style-on]', b).forEach(function (x) { x.setAttribute('style', open ? x.getAttribute('data-style-on') : x.getAttribute('data-style-off')); });
	}

	/* ---------------------------------------------------------------- tabs (design tab groups) */
	$$('[data-sh-tabs]').forEach(function (root) {
		var tabs = $$('[data-sh-tab]', root);
		var hover = root.getAttribute('data-sh-tabs') === 'hover';
		tabs.forEach(function (t, i) {
			t.addEventListener('click', function () { select(i); });
			if (hover) { t.addEventListener('mouseenter', function () { select(i); }); t.addEventListener('focus', function () { select(i); }); }
			t.addEventListener('keydown', function (e) {
				if (e.key === 'ArrowLeft' || e.key === 'ArrowDown') { e.preventDefault(); select((i + 1) % tabs.length, true); }
				if (e.key === 'ArrowRight' || e.key === 'ArrowUp') { e.preventDefault(); select((i - 1 + tabs.length) % tabs.length, true); }
			});
		});
		function select(i, focus) {
			tabs.forEach(function (t, k) {
				var on = k === i;
				t.setAttribute(t.hasAttribute('aria-pressed') ? 'aria-pressed' : 'aria-selected', on ? 'true' : 'false');
				t.setAttribute('tabindex', on ? '0' : '-1');
				var onS = t.getAttribute('data-style-on'), offS = t.getAttribute('data-style-off');
				if (onS && offS) t.setAttribute('style', on ? onS : offS);
				$$('[data-style-on]', t).forEach(function (x) { x.setAttribute('style', on ? x.getAttribute('data-style-on') : x.getAttribute('data-style-off')); });
				var panel = d.getElementById(t.getAttribute('aria-controls'));
				if (panel) panel.hidden = !on;
			});
			if (focus) tabs[i].focus();
		}
	});

	/* ---------------------------------------------------------------- marquee pause (logos / team) */
	$$('[data-sh-pause]').forEach(function (b) {
		var target = d.getElementById(b.getAttribute('aria-controls'));
		b.addEventListener('click', function () {
			var paused = b.getAttribute('aria-pressed') === 'true';
			b.setAttribute('aria-pressed', paused ? 'false' : 'true');
			if (target) { if (paused) target.removeAttribute('data-sh-paused'); else target.setAttribute('data-sh-paused', ''); }
			var l = b.querySelector('[data-label]');
			if (l) l.textContent = paused ? b.getAttribute('data-label-pause') : b.getAttribute('data-label-play');
		});
	});

	/* ---------------------------------------------------------------- shuffle once per page load (home team columns, as in the design) */
	var shuffleRoots = [];
	$$('[data-sh-shuffle-group]').forEach(function (g) { if (shuffleRoots.indexOf(g.parentNode) < 0) shuffleRoots.push(g.parentNode); });
	shuffleRoots.forEach(function (root) {
		var groups = $$(':scope > [data-sh-shuffle-group]', root);
		var ATTR = ['src', 'srcset', 'width', 'height'];
		var faces = [];
		groups.forEach(function (g) {
			$$('img[data-sh-face]:not([aria-hidden="true"])', g).forEach(function (img) {
				var f = { alt: img.getAttribute('alt') || '' };
				ATTR.forEach(function (n) { f[n] = img.getAttribute(n); });
				faces.push(f);
			});
		});
		if (faces.length < 2) return;
		for (var i = faces.length - 1; i > 0; i--) { var j = Math.floor(Math.random() * (i + 1)); var t = faces[i]; faces[i] = faces[j]; faces[j] = t; }
		// each column holds its photos twice (seamless loop); the second copy stays hidden and alt-less
		var k = 0;
		groups.forEach(function (g) {
			var slots = $$('img[data-sh-face]', g), n = slots.length / 2, mine = faces.slice(k, k + n);
			k += n;
			slots.forEach(function (img, idx) {
				var f = mine[idx % n];
				if (!f) return;
				ATTR.forEach(function (a) { if (f[a] === null) img.removeAttribute(a); else img.setAttribute(a, f[a]); });
				img.setAttribute('alt', idx < n ? f.alt : '');
			});
		});
	});

	/* ---------------------------------------------------------------- lead forms: fill time + one id per fill
	   The fill time is measured here, not from the time printed in the (possibly cached) page;
	   the id lets the server store a request once however many times it is sent. */
	function leadData(form) {
		var data = new FormData(form);
		var shown = Number(form.getAttribute('data-shown') || 0);
		data.set('elapsed', String(shown ? Date.now() - shown : 0));
		if (cfg.lang) data.set('lang', cfg.lang);
		if (!form.getAttribute('data-sid')) form.setAttribute('data-sid', (window.crypto && crypto.randomUUID) ? crypto.randomUUID() : String(Date.now()) + '-' + Math.random().toString(36).slice(2));
		data.set('sid', form.getAttribute('data-sid'));
		return data;
	}
	// phone number, local or international: digits (Arabic-Indic accepted), optional leading +,
	// spaces, dashes, dots and brackets; 7–15 digits. No country code is required or added.
	function normPhone(v) {
		v = String(v || '').replace(/[٠-٩]/g, function (c) { return String(c.charCodeAt(0) - 0x660); }).replace(/[۰-۹]/g, function (c) { return String(c.charCodeAt(0) - 0x6F0); });
		return v.replace(/[\s().\-\u200E\u200F\u202A-\u202E]/g, '');
	}
	function isPhone(v) { return /^\+?[0-9]{7,15}$/.test(normPhone(v)); }
	function leadBusy(form, on) {
		if (on) form.setAttribute('aria-busy', 'true'); else form.removeAttribute('aria-busy');
		$$('button[type="submit"]', form).forEach(function (b) { b.disabled = !!on; });
	}
	$$('[data-ct-form], [data-bk-form]').forEach(function (f) { f.setAttribute('data-shown', String(Date.now())); });

	/* ---------------------------------------------------------------- contact page form (same endpoint as booking, source "contact") */
	$$('[data-sh-contact]').forEach(function (box) {
		var form = box.querySelector('[data-ct-form]'), sent = box.querySelector('[data-ct-sent]');
		var err = box.querySelector('[data-ct-error]'), errText = box.querySelector('[data-ct-error-text]');
		if (!form || !sent) return;
		function showErr(m) { errText.textContent = m || ''; err.hidden = false; }
		function hideErr() { err.hidden = true; errText.textContent = ''; }
		$$('input,textarea,select', form).forEach(function (i) { i.addEventListener('input', function () { hideErr(); i.removeAttribute('aria-invalid'); }); });
		['name', 'company', 'email', 'phone', 'goal'].forEach(function (n) { if (form[n]) form[n].setAttribute('aria-required', 'true'); });
		function val(n) { return form[n] ? String(form[n].value || '').trim() : ''; }
		function optText(n) { var el = form[n]; return el && el.selectedIndex >= 0 ? el.options[el.selectedIndex].text : ''; }
		// the fields to fix are marked (aria-invalid, red border) and the first one gets the focus
		function bad(names, m) {
			names.forEach(function (n) { if (form[n]) form[n].setAttribute('aria-invalid', 'true'); });
			if (form[names[0]]) form[names[0]].focus();
			showErr(m);
		}
		form.addEventListener('submit', function (e) {
			e.preventDefault();
			var empty = ['name', 'company'].filter(function (n) { return !val(n); });
			if (empty.length) return bad(empty, t('nameCo', 'أدخل الاسم واسم الشركة للمتابعة.'));
			if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val('email'))) return bad(['email'], t('email', 'أدخل بريدًا إلكترونيًا صحيحًا.'));
			if (!isPhone(val('phone'))) return bad(['phone'], t('phone', 'أدخل رقم هاتف صحيحًا.'));
			if (!val('goal')) return bad(['goal'], t('goal', 'اكتب الهدف أو التحدي الأساسي.'));
			if (form.getAttribute('aria-busy') === 'true') return;
			leadBusy(form, true);
			fetch((cfg.rest || '/wp-json/seohouse/v1/') + 'lead', { method: 'POST', body: leadData(form), credentials: 'same-origin', cache: 'no-store' })
				.then(function (r) { return r.json().catch(function () { return null; }).then(function (j) { return { ok: r.ok, j: j }; }); })
				.then(function (res) {
					leadBusy(form, false);
					if (!res.ok || !res.j || !res.j.ok) return showErr((res.j && res.j.message) || (cfg.i18n && cfg.i18n.failed));
					form.removeAttribute('data-sid');
					var sum = box.querySelector('[data-ct-summary]');
					if (sum) sum.textContent = t('market', 'السوق:') + ' ' + optText('market') + ' · ' + t('service', 'الخدمة:') + ' ' + optText('service');
					form.hidden = true; sent.hidden = false;
					var ttl = sent.querySelector('[data-ct-sent-title]'); if (ttl) ttl.focus(); // not "t": that would hide the t() helper used above
					if (window.dataLayer) window.dataLayer.push({ event: 'sh_lead_submitted', sh_source: 'contact', sh_service: val('service') });
				})
				.catch(function () { leadBusy(form, false); showErr(cfg.i18n && cfg.i18n.failed); });
		});
		var reset = box.querySelector('[data-ct-reset]');
		if (reset) reset.addEventListener('click', function () {
			form.reset(); hideErr(); sent.hidden = true; form.hidden = false;
			if (form.ts) form.ts.value = String(Math.floor(Date.now() / 1000));
		});
	});

	/* ---------------------------------------------------------------- review cards carousel (design data-rev) */
	$$('section[data-screen-label="Reviews"]').forEach(function (sec) {
		var grid = sec.querySelector('[data-rev]') && sec.querySelector('[data-rev]').parentNode;
		if (!grid) return;
		var cards = $$(':scope > [data-rev]', grid);
		var n = cards.length, start = 0;
		// buttons are told apart by their labels, in Arabic or English
		var PREV = /السابق|previous/i, NEXT = /التالي|next/i;
		var dots = $$('button[aria-label]', sec).filter(function (b) { var l = b.getAttribute('aria-label'); return /^(التقييم|review) /i.test(l) && !PREV.test(l) && !NEXT.test(l); });
		var onDot = dots[0] ? dots[0].getAttribute('style') : '', offDot = dots[1] ? dots[1].getAttribute('style') : '';
		function show(k) {
			start = (k + n) % n;
			for (var i = 0; i < n; i++) { var c = cards[(start + i) % n]; c.setAttribute('data-rev', String(i)); grid.appendChild(c); }
			dots.forEach(function (d, i) { d.setAttribute('style', i % n === start ? onDot : offDot); d.setAttribute('aria-current', i % n === start ? 'true' : 'false'); });
		}
		$$('button', sec).forEach(function (b) {
			var l = b.getAttribute('aria-label') || '';
			if (PREV.test(l)) b.addEventListener('click', function () { show(start - 1); });
			else if (NEXT.test(l)) b.addEventListener('click', function () { show(start + 1); });
		});
		dots.forEach(function (d, i) { d.addEventListener('click', function () { show(i % n); }); });
		// swipe on touch screens
		var x0 = null;
		grid.addEventListener('touchstart', function (e) { x0 = e.touches[0].clientX; }, { passive: true });
		grid.addEventListener('touchend', function (e) { if (x0 === null) return; var dx = e.changedTouches[0].clientX - x0; if (Math.abs(dx) > 40) show(start + ((dx > 0) === (getComputedStyle(grid).direction === 'rtl') ? 1 : -1)); x0 = null; });
	});

	/* ---------------------------------------------------------------- list filters (results index) */
	$$('[data-sh-filter]').forEach(function (b) {
		b.addEventListener('click', function () {
			var k = b.getAttribute('data-sh-filter');
			var group = b.parentNode;
			$$('[data-sh-filter]', group).forEach(function (o) { o.setAttribute('aria-pressed', o === b ? 'true' : 'false'); });
			var scope = group.parentNode;
			$$('[data-sh-filter-item]', scope).forEach(function (it) {
				it.hidden = !(k === 'all' || (' ' + it.getAttribute('data-sh-filter-item') + ' ').indexOf(' ' + k + ' ') >= 0);
			});
		});
	});

	/* ---------------------------------------------------------------- lightbox */
	/* 2.8.11: the image at full size; when the link carries details (data-lb-*), a panel beside it (under it on
	   phones) with the client, field, search phrase and position, and previous / next within its gallery
	   ([data-sh-gallery]); arrow keys follow the reading direction, Escape closes. */
	var rtl = d.documentElement.dir === 'rtl';
	function lbOpen(a) {
		var group = a.closest('[data-sh-gallery]');
		// the gallery's items (data-lb-item): a grid's links, or a browser's list; filtered-out ones are skipped
		var items = group ? $$('[data-lb-item]', group).filter(function (x) { return !x.closest('[hidden]'); }) : [];
		if (!items.length) items = [a];
		var cur = a.hasAttribute('data-sg-stage') && group ? group.querySelector('.sh-sg__item[aria-current="true"]') : a;
		var i = Math.max(0, items.indexOf(cur));
		var box = d.createElement('div');
		box.className = 'sh-lightbox'; box.setAttribute('role', 'dialog'); box.setAttribute('aria-modal', 'true');
		box.innerHTML = '<div class="sh-lb__stage"><img alt=""></div><aside class="sh-lb__panel" hidden><p class="sh-lb__count"></p><h2 class="sh-lb__title"></h2><p class="sh-lb__sub"></p><dl class="sh-lb__facts"></dl><p class="sh-lb__alt"></p></aside>'
			+ '<button type="button" class="sh-lb__close">✕</button><button type="button" class="sh-lb__nav" data-dir="-1"></button><button type="button" class="sh-lb__nav" data-dir="1"></button>';
		var img = box.querySelector('img'), panel = box.querySelector('.sh-lb__panel');
		var navs = $$('.sh-lb__nav', box);
		box.querySelector('.sh-lb__close').setAttribute('aria-label', t('close', 'إغلاق'));
		navs[0].setAttribute('aria-label', t('lbPrev', 'الصورة السابقة')); navs[0].textContent = rtl ? '›' : '‹';
		navs[1].setAttribute('aria-label', t('lbNext', 'الصورة التالية')); navs[1].textContent = rtl ? '‹' : '›';
		function text(sel, v) { var el = box.querySelector(sel); el.textContent = v || ''; el.hidden = !v; }
		function show(k) {
			i = (k + items.length) % items.length;
			var x = items[i], g = function (n) { return x.getAttribute('data-lb-' + n) || ''; };
			img.src = x.getAttribute('href') || x.getAttribute('data-href'); img.alt = x.getAttribute('data-alt') || '';
			box.querySelector('.sh-lb__stage').scrollTop = 0;
			var rich = !!(g('title') || g('kw'));
			box.classList.toggle('sh-lb--rich', rich); panel.hidden = !rich;
			navs.forEach(function (b) { b.hidden = items.length < 2; });
			if (!rich) return;
			text('.sh-lb__count', items.length > 1 ? t('lbOf', '%1$s من %2$s').replace('%1$s', i + 1).replace('%2$s', items.length) : '');
			text('.sh-lb__title', g('title')); text('.sh-lb__sub', g('sub')); text('.sh-lb__alt', x.getAttribute('data-alt'));
			var facts = box.querySelector('.sh-lb__facts'); facts.innerHTML = '';
			[['lbKw', 'كلمة البحث', g('kw') ? '«' + g('kw') + '»' : ''], ['lbPos', 'الترتيب', g('pos')], ['lbDate', 'تاريخ اللقطة', g('date')]].forEach(function (f) {
				if (!f[2]) return;
				var dt = d.createElement('dt'), dd = d.createElement('dd');
				dt.textContent = t(f[0], f[1]); dd.textContent = f[2]; dd.setAttribute('dir', 'auto');
				if (f[0] === 'lbPos') dd.className = 'sh-lb__pos';
				facts.appendChild(dt); facts.appendChild(dd);
			});
			facts.hidden = !facts.children.length;
		}
		function close() {
			box.remove(); d.removeEventListener('keydown', keys);
			var x = items[i] || a;
			if (x.classList.contains('sh-sg__item')) x.click(); // the browser shows the screenshot the viewer ended on
			x.focus();
		}
		function keys(e) {
			if (e.key === 'Escape') { close(); return; }
			if (e.key !== 'ArrowLeft' && e.key !== 'ArrowRight') return;
			e.preventDefault();
			var step = e.key === 'ArrowRight' ? 1 : -1; // forward is to the right in English, to the left in Arabic
			show(i + (rtl ? -step : step));
		}
		box.addEventListener('click', function (ev) {
			var nav = ev.target.closest('.sh-lb__nav');
			if (nav) { show(i + Number(nav.getAttribute('data-dir'))); return; }
			if (ev.target === box || ev.target.classList.contains('sh-lb__stage') || ev.target.closest('.sh-lb__close')) close();
		});
		d.addEventListener('keydown', keys);
		show(i);
		d.body.appendChild(box);
		box.querySelector('.sh-lb__close').focus();
	}
	$$('[data-sh-lightbox]').forEach(function (a) {
		a.addEventListener('click', function (e) { e.preventDefault(); lbOpen(a); });
	});

	/* ---------------------------------------------------------------- screenshot browser (2.8.14)
	   A preview in the list shows its screenshot in the large frame (and its details under it); chips
	   filter the list by client. The large frame opens the viewer above. */
	$$('[data-sh-sg]').forEach(function (sg) {
		var stage = sg.querySelector('[data-sg-stage]'), simg = stage.querySelector('img');
		var list = sg.querySelector('.sh-sg__list');
		function field(n, v) {
			var el = sg.querySelector('[data-sg="' + n + '"]');
			if (!el) return;
			el.textContent = v || '';
			if (n !== 'keyword') el.hidden = !v;
		}
		function pick(btn, scroll) {
			$$('.sh-sg__item', sg).forEach(function (b) { b.setAttribute('aria-current', 'false'); });
			btn.setAttribute('aria-current', 'true');
			simg.src = btn.getAttribute('data-large');
			simg.setAttribute('srcset', btn.getAttribute('data-srcset') || '');
			simg.alt = btn.getAttribute('data-alt') || '';
			stage.setAttribute('href', btn.getAttribute('data-href'));
			stage.setAttribute('data-alt', btn.getAttribute('data-alt') || '');
			['title', 'sub', 'kw', 'pos', 'date'].forEach(function (k) { stage.setAttribute('data-lb-' + k, btn.getAttribute('data-lb-' + k) || ''); });
			field('title', btn.getAttribute('data-title'));
			field('sub', btn.getAttribute('data-sub'));
			field('keyword', btn.getAttribute('data-lb-kw'));
			field('position', btn.getAttribute('data-lb-pos'));
			var dt = sg.querySelector('[data-sg="date"]'), dv = btn.getAttribute('data-lb-date');
			if (dt) { dt.textContent = dv ? (dt.getAttribute('data-label') || '%s').replace('%s', dv) : ''; dt.hidden = !dv; }
			if (scroll !== false && list.scrollTo) {
				var li = btn.parentNode;
				list.scrollTo({ top: li.offsetTop - list.offsetTop - 8, left: li.offsetLeft - list.offsetLeft - 8, behavior: 'smooth' });
			}
		}
		$$('.sh-sg__item', sg).forEach(function (b) { b.addEventListener('click', function () { pick(b); }); });
		$$('[data-sg-filter]', sg).forEach(function (chip) {
			chip.addEventListener('click', function () {
				var who = chip.getAttribute('data-sg-filter'), first = null;
				$$('[data-sg-filter]', sg).forEach(function (c) { c.setAttribute('aria-pressed', c === chip ? 'true' : 'false'); });
				$$('li[data-sg-client]', list).forEach(function (li) {
					var on = !who || li.getAttribute('data-sg-client') === who;
					li.hidden = !on;
					if (on && !first) first = li;
				});
				if (list.scrollTo) list.scrollTo({ top: 0, left: 0 });
				if (first) pick(first.querySelector('.sh-sg__item'), false);
			});
		});
	});

	/* ---------------------------------------------------------------- booking form (one step) */
	$$('[data-sh-booking]').forEach(function (box) {
		var form = box.querySelector('[data-bk-form]');
		var done = box.querySelector('[data-bk-step2]');
		var err = box.querySelector('[data-bk-error]');
		var svc = box.querySelector('[data-bk-service]');
		if (!form) return;
		$$('[data-bk-pick]', form).forEach(function (b) {
			b.addEventListener('click', function () {
				$$('[data-bk-pick]', form).forEach(function (o) { o.setAttribute('aria-pressed', o === b ? 'true' : 'false'); });
				svc.value = b.getAttribute('data-bk-pick'); hideErr();
			});
		});
		function showErr(m) { err.textContent = m; err.hidden = false; }
		function hideErr() { err.hidden = true; err.textContent = ''; }
		$$('input', form).forEach(function (i) { i.addEventListener('input', hideErr); });
		form.addEventListener('submit', function (e) {
			e.preventDefault();
			var name = form.name.value.trim(), email = form.email ? form.email.value.trim() : '';
			if (!svc.value) return showErr(t('pick', 'اختر الخدمة المطلوبة.'));
			if (!name) { form.name.focus(); return showErr(t('name', 'اكتب اسمك.')); }
			if (!form.email && form.contact) {
				// markup of a page cached before 2.7.2 (single «contact» field): accepted as before
				var c = form.contact.value.trim();
				if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(c) && !isPhone(c)) return showErr(t('contact', 'اكتب رقم جوال أو بريدًا إلكترونيًا صحيحًا.'));
			} else {
				if (!/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(email)) { form.email.focus(); return showErr(t('email', 'أدخل بريدًا إلكترونيًا صحيحًا.')); }
				if (!isPhone(form.phone.value)) { form.phone.focus(); return showErr(t('phone', 'أدخل رقم هاتف صحيحًا.')); }
			}
			if (form.getAttribute('aria-busy') === 'true') return;
			leadBusy(form, true);
			fetch((cfg.rest || '/wp-json/seohouse/v1/') + 'lead', { method: 'POST', body: leadData(form), credentials: 'same-origin', cache: 'no-store' })
				.then(function (r) { return r.json().catch(function () { return null; }).then(function (j) { return { ok: r.ok, j: j }; }); })
				.then(function (res) {
					leadBusy(form, false);
					if (!res.ok || !res.j || !res.j.ok) return showErr((res.j && res.j.message) || (cfg.i18n && cfg.i18n.failed));
					form.removeAttribute('data-sid');
					form.hidden = true;
					if (done) { done.hidden = false; done.focus(); }
					if (window.dataLayer) window.dataLayer.push({ event: 'sh_lead_submitted', sh_source: 'booking', sh_service: svc.value });
				})
				.catch(function () { leadBusy(form, false); showErr(cfg.i18n && cfg.i18n.failed); });
		});
	});


	/* ---------------------------------------------------------------- click tracking (no personal data) */
	d.addEventListener('click', function (e) {
		var a = e.target.closest && e.target.closest('a[href^="#booking"], a[href*="/contact/"], a[href^="tel:"], a[href^="mailto:"], a[href*="wa.me"]');
		if (!a || !window.dataLayer) return;
		var h = a.getAttribute('href');
		window.dataLayer.push({ event: 'sh_cta_click', sh_target: h.indexOf('tel:') === 0 ? 'phone' : h.indexOf('mailto:') === 0 ? 'email' : h.indexOf('wa.me') >= 0 ? 'whatsapp' : h.charAt(0) === '#' ? 'booking' : 'contact' });
	});

	/* ---------------------------------------------------------------- floating contact buttons (Chaty plugin), Arabic pages
	   They sit at the inline end (left in RTL) so they do not cover the start of Arabic lines; their
	   hover labels open towards the page and read in Arabic. The widget is added after load. */
	if (d.documentElement.dir === 'rtl') {
		var chatyAr = { 'Contact us': 'تواصل معنا', 'Phone': 'اتصال', 'WhatsApp': 'واتساب', 'Hide': 'إخفاء' };
		var fixChaty = function () {
			var w = d.querySelector('.chaty-widget');
			if (!w || w.getAttribute('data-sh-rtl')) return !!w;
			w.setAttribute('data-sh-rtl', '1');
			d.documentElement.classList.add('sh-chaty-rtl');
			$$('.pos-left', w).forEach(function (x) { x.classList.remove('pos-left'); x.classList.add('pos-right'); });
			$$('[data-hover]', w).forEach(function (x) { var v = chatyAr[x.getAttribute('data-hover')]; if (v) x.setAttribute('data-hover', v); });
			$$('.on-hover-text p', w).forEach(function (p) { var v = chatyAr[p.textContent.trim()]; if (v) p.textContent = v; });
			return true;
		};
		if (!fixChaty() && window.MutationObserver) {
			var chatyObs = new MutationObserver(function () { if (fixChaty()) chatyObs.disconnect(); });
			chatyObs.observe(d.body, { childList: true, subtree: true });
			setTimeout(function () { chatyObs.disconnect(); }, 20000);
		}
	}

	/* ---------------------------------------------------------------- scrolling strips: images that only come into view by the
	   animation (a transform) are never reached by lazy loading, which left empty gaps; after the page
	   has loaded they are fetched (small images, each one once for both copies) */
	window.addEventListener('load', function () {
		setTimeout(function () {
			$$('[style*="shRtl"] img[loading="lazy"]').forEach(function (img) { img.loading = 'eager'; });
		}, 300);
	});

	/* ---------------------------------------------------------------- marquee copies: links in the hidden duplicate set are not focusable */
	$$('[aria-hidden="true"] a[href]').forEach(function (a) { a.setAttribute('tabindex', '-1'); });
})();
