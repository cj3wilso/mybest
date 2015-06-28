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
		factory.getPhotos = function (propid) {
            return $http.get(serviceBase + 'photos.php', {
				params: {
					prop_id: propid
				}
			}).then(
				function (results) {
					return results.data;
			});
        };
		factory.getList = function (prov, city, page) {
            return $http.get(serviceBase + 'list.php', {
				params: {
					prov: prov,
					city: city,
					page: page
				}
			}).then(
				function (results) {
					return results.data;
			});
        };
		factory.getListAddressFeatured = function (prov, city) {
            return $http.get(serviceBase + 'listAddressFeatured.php', {
				params: {
					prov: prov,
					city: city
				}
			}).then(
				function (results) {
					return results.data;
			});
        };
        return factory;
    };
    apartmentFactory.$inject = injectParams;
    app.factory('apartmentService', apartmentFactory);
});