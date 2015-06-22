define(['app'], function (app) {
	app.controller('Page1Ctrl', function ($scope, $route) { 
		$scope.model = { 
		   message: 'otherwise is home?' 
		};
	});
});