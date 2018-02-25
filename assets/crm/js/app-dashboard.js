var App = (function () {
	'use strict';
	App.dashboard = function () {
		function counter() {
			$('[data-toggle="counter"]').each(function (i, e) {
				var _el = $(this);
				var prefix = '';
				var suffix = '';
				var start = 0;
				var end = 0;
				var decimals = 0;
				var duration = 2.5;
				if (_el.data('prefix')) {
					prefix = _el.data('prefix');
				}
				if (_el.data('suffix')) {
					suffix = _el.data('suffix');
				}
				if (_el.data('start')) {
					start = _el.data('start');
				}
				if (_el.data('end')) {
					end = _el.data('end');
				}
				if (_el.data('decimals')) {
					decimals = _el.data('decimals');
				}
				if (_el.data('duration')) {
					duration = _el.data('duration');
				}
				var count = new CountUp(_el.get(0), start, end, decimals, duration, {
					suffix: suffix,
					prefix: prefix,
				});
				count.start();
			});
		}
		//Show loading class toggle
		function toggleLoader() {
			$('.toggle-loading').on('click', function () {
				var parent = $(this).parents('.widget, .panel');

				if (parent.length) {
					parent.addClass('ciuis-loading-active');

					setTimeout(function () {
						parent.removeClass('ciuis-loading-active');
					}, 3000);
				}
			});
		}
		//Calendar widget
		function calendar() {
			var widget = $("#calendar-widget");
			var now = new Date();
			var year = now.getFullYear();
			var month = now.getMonth();
			var events = [year + '-' + (month + 1) + '-16', year + '-' + (month + 1) + '-20'];
			function checkRows(datepicker) {
				var dp = datepicker.dpDiv;
				var rows = $("tbody tr", dp).length;
				if (rows === 6) {
					dp.addClass('ui-datepicker-6rows');
				} else {
					dp.removeClass('ui-datepicker-6rows');
				}
			}
			//Extend default datepicker to support afterShow event
			$.extend($.datepicker, {
				_updateDatepicker_original: $.datepicker._updateDatepicker,
				_updateDatepicker: function (inst) {
					this._updateDatepicker_original(inst);
					var afterShow = this._get(inst, 'afterShow');
					if (afterShow) {
						afterShow.apply(inst, [inst]);
					}
				}
			});
			if (typeof jQuery.ui !== 'undefined') {
				widget.datepicker({
					showOtherMonths: true,
					selectOtherMonths: true,
					beforeShowDay: function (date) {
						var m = date.getMonth(),
							d = date.getDate(),
							y = date.getFullYear();
						if ($.inArray(y + '-' + (m + 1) + '-' + d, events) !== -1) {
							return [true, 'has-events', 'This day has events!'];
						} else {
							return [true, "", ""];
						}
					},
					afterShow: function (o) {
						//If datepicker has 6 rows add a class to the widget
						checkRows(o);
					}
				});
			}
		}
		counter();
		toggleLoader();
		calendar();
	};
	return App;
})(App || {});