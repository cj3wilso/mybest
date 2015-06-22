define(['angularAMD', 'ui-bootstrap', 'angular-route', 'directive/loadScript', 'filter/urlFormat', 'controllers/nav', 'controllers/modal'], function (angularAMD) {
	var app = angular.module("myApp", ['ngRoute','ui.bootstrap']).constant('hash', hash);
	app.config(function ($routeProvider, $locationProvider) {
		if(!hash) {
			$locationProvider.html5Mode(true);
		}
		$routeProvider
		.when("/index.html", angularAMD.route({
			templateUrl: 'angular/views/home.html', controller: 'HomeCtrl', controllerUrl: 'controllers/home'
		}))
		.when("/", angularAMD.route({
			templateUrl: 'angular/views/home.html', controller: 'HomeCtrl', controllerUrl: 'controllers/home'
		}))
		.when("/rent", angularAMD.route({
			templateUrl: 'angular/views/page2.html', controller: 'Page2Ctrl', controllerUrl: 'controllers/page2'
		}))
		.when('/rent/:prov', angularAMD.route({
			templateUrl: 'angular/views/page2.html', controller: 'Page2Ctrl', controllerUrl: 'controllers/page2'
		}))
		.when('/rent/:prov/:city', angularAMD.route({
			templateUrl: 'angular/views/page2.html', controller: 'Page2Ctrl', controllerUrl: 'controllers/page2'
		}))
		.when('/map*', angularAMD.route({
			templateUrl: 'angular/views/page2.html', controller: 'Page2Ctrl', controllerUrl: 'controllers/page2'
		}))
		.when('/options*', angularAMD.route({
			templateUrl: 'angular/views/page2.html', controller: 'Page2Ctrl', controllerUrl: 'controllers/page2'
		}))
		.when('/rent/:prov/:city/:name/:propid', angularAMD.route({
			templateUrl: 'angular/views/apartment.html', controller: 'ApartmentCtrl', controllerUrl: 'controllers/apartment'
		}))
		.when('/add', angularAMD.route({
			templateUrl: 'angular/views/page2.html', controller: 'Page2Ctrl', controllerUrl: 'controllers/page2'
		}))
		.when('/contact', angularAMD.route({
			templateUrl: 'angular/views/page2.html', controller: 'Page2Ctrl', controllerUrl: 'controllers/page2'
		}))
		.when('/faves', angularAMD.route({
			templateUrl: 'angular/views/page2.html', controller: 'Page2Ctrl', controllerUrl: 'controllers/page2'
		}))
		.when('/admin-login', angularAMD.route({
			templateUrl: 'angular/views/page2.html', controller: 'Page2Ctrl', controllerUrl: 'controllers/page2'
		}))
		.when('/forgot', angularAMD.route({
			templateUrl: 'angular/views/page2.html', controller: 'Page2Ctrl', controllerUrl: 'controllers/page2'
		}))
		.when('/register', angularAMD.route({
			templateUrl: 'angular/views/page2.html', controller: 'Page2Ctrl', controllerUrl: 'controllers/page2'
		}))
		.when('/logout', angularAMD.route({
			templateUrl: 'angular/views/page2.html', controller: 'Page2Ctrl', controllerUrl: 'controllers/page2'
		}))
		.when('/admin', angularAMD.route({
			templateUrl: 'angular/views/page2.html', controller: 'Page2Ctrl', controllerUrl: 'controllers/page2'
		}))
		.otherwise({
			templateUrl: 'angular/views/home.html', controller: 'HomeCtrl', controllerUrl: 'controllers/home'
		});
	});
	return angularAMD.bootstrap(app);
});
