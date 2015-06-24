'use strict';

define(['app'], function (app) {
    var injectParams = ['$http', '$q'];
    var navFactory = function ($http, $q) {
        var serviceBase = 'http://mybestapartments.ca/data/',
            factory = {};
		factory.getNav = function () {
            return $http.get(serviceBase + 'homeNav.php').then(
				function (results) {
					return results.data;
			});
        };
        return factory;
    };
    navFactory.$inject = injectParams;
    app.factory('navService', navFactory);
});