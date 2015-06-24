'use strict';

define(['app'], function (app) {
	app.filter('urlFormat', function () {
		return function (text) {
			var str = text.replace(/\s+/g, '-');
			return str.toLowerCase();
		};
	});	
});