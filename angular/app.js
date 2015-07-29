'use strict';

define(['shared/services/routeResolver'], function () {

    var app = angular.module('app', ['ngRoute', 'ngSanitize', 'routeResolverServices','ui.bootstrap']).constant('hash', hash);

    app.config(function ($routeProvider, routeResolverProvider, $controllerProvider, 
                  $compileProvider, $filterProvider, $provide, $locationProvider) {

            app.compileProvider = $compileProvider;
			
			if(!hash) {
				$locationProvider.html5Mode(true);
			}
			
			//Change default views and controllers directory using the following:
            //routeResolverProvider.routeConfig.setBaseDirectories('angular/views/', 'angular/controllers/');

            app.register =
            {
                controller: $controllerProvider.register,
                directive: $compileProvider.directive,
                filter: $filterProvider.register,
                factory: $provide.factory,
                service: $provide.service
            };

            //Define routes - controllers will be loaded dynamically
            var route = routeResolverProvider.route;

            $routeProvider
				.when('/index.html', route.resolve('Home'))
				.when('/', route.resolve('Home'))
				.when('/rent/:prov/:city', route.resolve('List'))
				.when('/rent/:prov/:city/:page', route.resolve('List'))
				.when('/rent/:prov/:city/:name/:propid', route.resolve('Apartment'))
				.when('/rent', route.resolve('Page2'))
				.when('/rent/:prov', route.resolve('Page2'))
				.when('/map*', route.resolve('Page2'))
				.when('/options*', route.resolve('Page2'))
				.when('/add', route.resolve('Page2'))
				.when('/contact', route.resolve('Page2'))
				.when('/faves', route.resolve('Page2'))
				.when('/admin-login', route.resolve('Page2'))
				.when('/forgot', route.resolve('Page2'))
				.when('/register', route.resolve('Page2'))
				.when('/logout', route.resolve('Page2'))
				.when('/admin', route.resolve('Page2', '', true))
                .otherwise({ redirectTo: '/index.html' });
    });
			
	app.config(['$httpProvider', function ($httpProvider) {
            // enable http caching
           $httpProvider.defaults.cache = true;
      }])
	//Authenticate logins on front end
	app.run(['$rootScope', '$location', 'authService',
		function ($rootScope, $location, authService) {
					
			//Client-side security. Server-side framework MUST add it's 
			//own security as well since client-based security is easily hacked
			$rootScope.$on("$routeChangeStart", function (event, next, current) {
				if (next && next.$$route && next.$$route.secure) {
					if (!authService.user.isAuthenticated) {
						$rootScope.$evalAsync(function () {
							authService.redirectToLogin();
						});
					}
				}
			});
			
			
			
		}
	]);
	return app;
});

