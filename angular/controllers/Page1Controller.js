'use strict';

define(['app'], function (app) {
	app.register.controller('Page1Controller', function ($scope, $route) { 
		$scope.model = { 
		   message: 'otherwise is home?' 
		};
	});
});