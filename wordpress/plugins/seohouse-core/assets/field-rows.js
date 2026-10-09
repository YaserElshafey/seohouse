/**
 * SEO House Core — "قائمة عناصر" (sh_rows) editor: add, remove, reorder (drag or ↑/↓) and
 * collapse item records inline. New items are cloned from the list's own template with
 * acf.duplicate(), which also initialises their ACF fields.
 */
(function ($) {
	'use strict';
	if (typeof acf === 'undefined') return;

	function lists($scope) { return $scope.find('.sh-rows__list').addBack('.sh-rows__list'); }
	function box($el) { return $el.closest('.sh-rows'); }
	function ownList($box) { return $box.children('.sh-rows__list'); }

	function refresh($box) {
		var $rows = ownList($box).children('.sh-rows__row');
		$rows.each(function (i) { $(this).find('> .sh-rows__bar > .sh-rows__num').text(i + 1); });
		$box.children('.sh-rows__empty').prop('hidden', $rows.length > 0);
		var max = parseInt($box.data('max'), 10) || 0;
		$box.children('.sh-rows__add').prop('disabled', max > 0 && $rows.length >= max);
		ownList($box).trigger('change'); // let ACF mark the form as changed
	}

	function sortable($scope) {
		lists($scope).each(function () {
			var $l = $(this);
			if ($l.hasClass('ui-sortable') || $l.closest('.sh-rows__tpl').length) return;
			$l.sortable({
				handle: '> .sh-rows__bar > .sh-rows__handle',
				items: '> .sh-rows__row',
				axis: 'y',
				placeholder: 'sh-rows__placeholder',
				forcePlaceholderSize: true,
				start: function (e, ui) { acf.doAction('sortstart', ui.item, ui.placeholder); },
				stop: function (e, ui) { acf.doAction('sortstop', ui.item, ui.placeholder); refresh(box($l)); }
			});
		});
	}

	function setOpen($row, open) {
		$row.toggleClass('-collapsed', !open);
		$row.find('> .sh-rows__bar > .sh-rows__toggle').attr('aria-expanded', open ? 'true' : 'false');
	}

	function title($row) {
		var $box = box($row), key = $box.data('title-key'), t = '';
		var $f = key ? $row.find('> .sh-rows__body > .acf-field[data-key="' + key + '"]').find('input[type=text],textarea').first() : $();
		if (!$f.length) $f = $row.find('> .sh-rows__body > .acf-field').find('input[type=text],textarea').first();
		t = ($f.val() || '').replace(/<[^>]*>/g, '').trim();
		$row.find('> .sh-rows__bar .sh-rows__title').text(t.length > 80 ? t.slice(0, 80) + '…' : t);
	}

	$(document).on('click', '.sh-rows__add', function (e) {
		e.preventDefault();
		var $box = box($(this));
		var $tpl = $box.children('.sh-rows__tpl').find('> ol > .sh-rows__row');
		var $new = acf.duplicate({
			target: $tpl,
			append: function ($el, $el2) { ownList($box).append($el2); }
		});
		setOpen($new, true);
		sortable($new);
		refresh($box);
		$new.find('input[type=text],textarea').first().trigger('focus');
	});

	$(document).on('click', '.sh-rows__remove', function (e) {
		e.preventDefault();
		var $row = $(this).closest('.sh-rows__row'), $box = box($row);
		var min = parseInt($box.data('min'), 10) || 0;
		if (min && ownList($box).children('.sh-rows__row').length <= min) { window.alert(acf.__('Minimum rows reached ({min} rows)').replace('{min}', min)); return; }
		if (!window.confirm('حذف هذا العنصر؟ يُحذف نهائيًا عند حفظ الصفحة.')) return;
		acf.doAction('remove', $row);
		$row.remove();
		refresh($box);
	});

	$(document).on('click', '.sh-rows__toggle', function (e) {
		e.preventDefault();
		var $row = $(this).closest('.sh-rows__row');
		setOpen($row, $row.hasClass('-collapsed'));
	});

	$(document).on('click', '.sh-rows__up, .sh-rows__down', function (e) {
		e.preventDefault();
		var $row = $(this).closest('.sh-rows__row');
		if ($(this).hasClass('sh-rows__up')) $row.prev('.sh-rows__row').before($row); else $row.next('.sh-rows__row').after($row);
		$(this).trigger('focus');
		refresh(box($row));
	});

	$(document).on('input change', '.sh-rows__body input[type=text], .sh-rows__body textarea', function () {
		title($(this).closest('.sh-rows__row'));
	});

	acf.addAction('ready', function ($el) { sortable($el || $(document)); });
	acf.addAction('append', function ($el) { sortable($el); });
})(jQuery);
