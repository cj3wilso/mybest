define(['app'], function (app) {
	app.controller('Page2Ctrl', function ($scope, $route) { 
		$scope.model = { 
		   message: 'This is my app 2' 
		};
	});
});