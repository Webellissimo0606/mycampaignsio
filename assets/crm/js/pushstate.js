var App = (function () {
	'use strict';
	App.pushstate = function () {
		function sayfahuple() {
			$('.huppur').on('click', function() {
				var pageurl = $(this).attr('href');
				$.ajax({
					type: 'POST',
					url: pageurl,
					success: function (data) {
						$('body').html(data).find("body").html();
					}
				});
				if (pageurl !== window.location) {
					window.history.pushState({
						path: pageurl
					}, '', pageurl);
				}
				return false;
			});
		}
		sayfahuple();
	};
	return App;
})(App || {});