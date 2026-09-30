/* SEO House — interactions from the approved design (vanilla JS, no dependencies). */
(function () {
	'use strict';
	var d = document;
	var $$ = function (sel, root) { return Array.prototype.slice.call((root || d).querySelectorAll(sel)); };
	var cfg = window.SH || {};

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

	/* ---------------------------------------------------------------- contact page form (same endpoint as booking, source "contact") */
	$$('[data-sh-contact]').forEach(function (box) {
		var form = box.querySelector('[data-ct-form]'), sent = box.querySelector('[data-ct-sent]');
		var err = box.querySelector('[data-ct-error]'), errText = box.querySelector('[data-ct-error-text]');
		if (!form || !sent) return;
		function showErr(m) { errText.textContent = m || ''; err.hidden = false; }
		function hideErr() { err.hidden = true; errText.textContent = ''; }
		$$('input,textarea,select', form).forEach(function (i) { i.addEventListener('input', hideErr); });
		function val(n) { return form[n] ? String(form[n].value || '').trim() : ''; }
		function optText(n) { var el = form[n]; return el && el.selectedIndex >= 0 ? el.options[el.selectedIndex].text : ''; }
		form.addEventListener('submit', function (e) {
			e.preventDefault();
			if (!val('name') || !val('company')) return showErr('أدخل الاسم واسم الشركة للمتابعة.');
			if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val('email'))) return showErr('أدخل بريدًا إلكترونيًا صحيحًا.');
			if (!val('phone')) return showErr('أدخل رقم الهاتف أو واتساب.');
			if (!val('goal')) return showErr('اكتب الهدف أو التحدي الأساسي.');
			if (form.getAttribute('aria-busy') === 'true') return;
			form.setAttribute('aria-busy', 'true');
			fetch((cfg.rest || '/wp-json/seohouse/v1/') + 'lead', { method: 'POST', body: new FormData(form), credentials: 'same-origin' })
				.then(function (r) { return r.json().then(function (j) { return { ok: r.ok, j: j }; }); })
				.then(function (res) {
					form.removeAttribute('aria-busy');
					if (!res.ok || !res.j || !res.j.ok) return showErr((res.j && res.j.message) || (cfg.i18n && cfg.i18n.failed));
					var sum = box.querySelector('[data-ct-summary]');
					if (sum) sum.textContent = 'السوق: ' + optText('market') + ' · الخدمة: ' + optText('service');
					var bk = cfg.booking || {}, embed = box.querySelector('[data-bk-embed]'), receipt = box.querySelector('[data-ct-receipt]');
					if (bk.url && embed) {
						var u = new URL(bk.url);
						u.searchParams.set('name', val('name')); u.searchParams.set('email', val('email'));
						var f = d.createElement('iframe');
						f.src = u.toString(); f.title = 'اختيار الموعد'; f.loading = 'lazy';
						embed.innerHTML = ''; embed.appendChild(f); embed.hidden = false;
						if (receipt) receipt.hidden = true;
					}
					form.hidden = true; sent.hidden = false;
					var t = sent.querySelector('[data-ct-sent-title]'); if (t) t.focus();
					if (window.dataLayer) window.dataLayer.push({ event: 'sh_lead_submitted', sh_source: 'contact', sh_service: val('service') });
				})
				.catch(function () { form.removeAttribute('aria-busy'); showErr(cfg.i18n && cfg.i18n.failed); });
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
		var dots = $$('button[aria-label^="التقييم "]', sec).filter(function (b) { return !/السابق|التالي/.test(b.getAttribute('aria-label')); });
		var onDot = dots[0] ? dots[0].getAttribute('style') : '', offDot = dots[1] ? dots[1].getAttribute('style') : '';
		function show(k) {
			start = (k + n) % n;
			for (var i = 0; i < n; i++) { var c = cards[(start + i) % n]; c.setAttribute('data-rev', String(i)); grid.appendChild(c); }
			dots.forEach(function (d, i) { d.setAttribute('style', i % n === start ? onDot : offDot); d.setAttribute('aria-current', i % n === start ? 'true' : 'false'); });
		}
		$$('button', sec).forEach(function (b) {
			var l = b.getAttribute('aria-label') || '';
			if (/السابق/.test(l)) b.addEventListener('click', function () { show(start - 1); });
			else if (/التالي/.test(l)) b.addEventListener('click', function () { show(start + 1); });
		});
		dots.forEach(function (d, i) { d.addEventListener('click', function () { show(i % n); }); });
		// swipe on touch screens
		var x0 = null;
		grid.addEventListener('touchstart', function (e) { x0 = e.touches[0].clientX; }, { passive: true });
		grid.addEventListener('touchend', function (e) { if (x0 === null) return; var dx = e.changedTouches[0].clientX - x0; if (Math.abs(dx) > 40) show(start + (dx > 0 ? 1 : -1)); x0 = null; });
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
	$$('[data-sh-lightbox]').forEach(function (a) {
		a.addEventListener('click', function (e) {
			e.preventDefault();
			var box = d.createElement('div');
			box.className = 'sh-lightbox'; box.setAttribute('role', 'dialog'); box.setAttribute('aria-modal', 'true');
			box.innerHTML = '<button type="button" aria-label="إغلاق">✕</button><img alt="">';
			box.querySelector('img').src = a.getAttribute('href');
			box.querySelector('img').alt = a.getAttribute('data-alt') || '';
			box.addEventListener('click', function (ev) { if (ev.target === box || ev.target.tagName === 'BUTTON') { box.remove(); a.focus(); } });
			d.body.appendChild(box);
			box.querySelector('button').focus();
		});
	});

	/* ---------------------------------------------------------------- booking form */
	$$('[data-sh-booking]').forEach(function (box) {
		var form = box.querySelector('[data-bk-form]');
		var step2 = box.querySelector('[data-bk-step2]');
		var err = box.querySelector('[data-bk-error]');
		var svc = box.querySelector('[data-bk-service]');
		var label = box.querySelector('[data-bk-label]'), counter = box.querySelector('[data-bk-counter]'), bar = box.querySelector('[data-bk-bar]');
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
		function stage(n) {
			var two = n === 2;
			form.hidden = two; step2.hidden = !two;
			if (label) label.textContent = label.getAttribute(two ? 'data-l2' : 'data-l1');
			if (counter) counter.textContent = counter.getAttribute(two ? 'data-c2' : 'data-c1');
			if (bar) bar.style.width = two ? '100%' : '50%';
		}
		if (!step2.hidden) stage(2);
		box.querySelector('[data-bk-back]').addEventListener('click', function () { stage(1); });
		form.addEventListener('submit', function (e) {
			e.preventDefault();
			var name = form.name.value.trim(), contact = form.contact.value.trim();
			if (!svc.value) return showErr('اختر الخدمة المطلوبة.');
			if (!name) return showErr('اكتب اسمك.');
			if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(contact) && !/^\+?[0-9\s-]{8,}$/.test(contact)) return showErr('اكتب رقم جوال أو بريدًا إلكترونيًا صحيحًا.');
			if (form.getAttribute('aria-busy') === 'true') return;
			form.setAttribute('aria-busy', 'true');
			var data = new FormData(form);
			fetch((cfg.rest || '/wp-json/seohouse/v1/') + 'lead', { method: 'POST', body: data, credentials: 'same-origin' })
				.then(function (r) { return r.json().then(function (j) { return { ok: r.ok, j: j }; }); })
				.then(function (res) {
					form.removeAttribute('aria-busy');
					if (!res.ok || !res.j || !res.j.ok) return showErr((res.j && res.j.message) || (cfg.i18n && cfg.i18n.failed));
					stage(2);
					var bk = cfg.booking || {};
					var embed = box.querySelector('[data-bk-embed]'), receipt = box.querySelector('[data-bk-receipt]');
					if (bk.url && embed) {
						var u = new URL(bk.url);
						u.searchParams.set('name', name);
						if (contact.indexOf('@') > 0) u.searchParams.set('email', contact);
						var f = d.createElement('iframe');
						f.src = u.toString(); f.title = 'اختيار الموعد'; f.loading = 'lazy';
						embed.innerHTML = ''; embed.appendChild(f); embed.hidden = false;
						if (receipt) receipt.hidden = true;
					}
					if (window.dataLayer) window.dataLayer.push({ event: 'sh_lead_submitted', sh_source: 'booking', sh_service: svc.value });
				})
				.catch(function () { form.removeAttribute('aria-busy'); showErr(cfg.i18n && cfg.i18n.failed); });
		});
	});

	/* ---------------------------------------------------------------- click tracking (no personal data) */
	d.addEventListener('click', function (e) {
		var a = e.target.closest && e.target.closest('a[href^="#booking"], a[href*="/contact/"], a[href^="tel:"], a[href^="mailto:"], a[href*="wa.me"]');
		if (!a || !window.dataLayer) return;
		var h = a.getAttribute('href');
		window.dataLayer.push({ event: 'sh_cta_click', sh_target: h.indexOf('tel:') === 0 ? 'phone' : h.indexOf('mailto:') === 0 ? 'email' : h.indexOf('wa.me') >= 0 ? 'whatsapp' : h.charAt(0) === '#' ? 'booking' : 'contact' });
	});
})();
