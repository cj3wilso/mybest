define(['angularAMD'], function (angularAMD) {
	angularAMD.filter('urlFormat', function () {
		return function (text) {
			var str = text.replace(/\s+/g, '-');
			return str.toLowerCase();
		};
	});
});