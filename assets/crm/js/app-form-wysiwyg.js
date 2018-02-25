var App = (function () {
	'use strict';
	App.textEditors = function () {

		//Summernote
		if( $("#replyeditor").length ){
			$('#replyeditor').summernote({
				height: 200
			});
		}

		if( $("#editor2").length ){
			$("#editor2").markdown({
				buttonSize: 'normal'
			});
		}
	};
	return App;
})(App || {});