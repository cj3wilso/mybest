'use strict';

define(['angular-route', 'angular-sanitize', 'ui-bootstrap'], function () {

    var routeResolver = function () {

        this.$get = function () {
			return this;
        };

        this.routeConfig = function () {
            var viewsDirectory = 'angular/',
                controllersDirectory = 'angular/',

            setBaseDirectories = function (viewsDir, controllersDir) {
                viewsDirectory = viewsDir;
                controllersDirectory = controllersDir;
            },

            getViewsDirectory = function () {
                return viewsDirectory;
            },

            getControllersDirectory = function () {
                return controllersDirectory;
            };

            return {
                setBaseDirectories: setBaseDirectories,
                getControllersDirectory: getControllersDirectory,
                getViewsDirectory: getViewsDirectory
            };
        }();

        this.route = function (routeConfig) {

            var resolve = function (baseName, path, secure, directive) {
                if (!path) path = '';
				if (!directive) directive = '';
				//console.log(directive);

                var routeDef = {};
				var basePath = baseName + '/';
                routeDef.templateUrl = routeConfig.getViewsDirectory() + basePath + path + baseName + '.html';
                routeDef.controller = baseName + 'Controller';
                routeDef.secure = (secure) ? secure : false;
                //console.log(routeDef.controller);
				routeDef.resolve = {
                    load: ['$q', '$rootScope', function ($q, $rootScope) {
                        var dependencies = [routeConfig.getControllersDirectory() + basePath + path + baseName + 'Controller.js', directive];
						//console.log($q);
                        return resolveDependencies($q, $rootScope, dependencies);
                    }]
                };
				

                return routeDef;
            },

            resolveDependencies = function ($q, $rootScope, dependencies) {
                var defer = $q.defer();
                require(dependencies, function () {
                    defer.resolve();
                    $rootScope.$apply()
                });
                return defer.promise;
            };

            return {
                resolve: resolve
            }
        }(this.routeConfig);

    };

    var servicesApp = angular.module('routeResolverServices', []);

    //Must be a provider since it will be injected into module.config()    
    servicesApp.provider('routeResolver', routeResolver);
});