'use strict';

define(['app'], function (app) {
	app.filter('urlFilter', function () {
		return function (text) {
			var str = text.replace(/[^a-zA-Z0-9\- ]/g,'').trim();
			//if we have a bunch of spaces and dash in middle
			//convert to 1 space
			str = str.replace(/\s+\-\s+/g, ' ');
			str = str.replace(/\s+/g, '-');
			return str.toLowerCase();
		};
	});	
});