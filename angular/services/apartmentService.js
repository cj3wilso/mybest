'use strict';

define(['app'], function (app) {
    var injectParams = ['$http', '$q'];
    var apartmentFactory = function ($http, $q) {
        var serviceBase = 'http://mybestapartments.ca/data/',
            factory = {};
		factory.getAptPage = function (propid) {
            return $http.get(serviceBase + 'apartment.php', {
				params: {
					prop_id: propid
				}
			}).then(
				function (results) {
					return results.data;
			});
        };
		factory.getSpotlightApt = function (propid) {
            return $http.get(serviceBase + 'homeFeaturedProperty.php').then(
				function (results) {
					return results.data;
			});
        };
        return factory;
    };
    apartmentFactory.$inject = injectParams;
    app.factory('apartmentService', apartmentFactory);
});