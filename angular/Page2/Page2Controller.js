'use strict';

define(['app'], function (app) {
	app.register.controller('Page2Controller', function ($scope, $route) { 
		$scope.model = 'This is my app 2';
	});
});