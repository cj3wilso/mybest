'use strict';

define(['app'], function (app) {
    var injectParams = ['$http', '$q'];
    var contactFactory = function ($http, $q) {
        var serviceBase = 'http://mybestapartments.ca/data/',
            factory = {};
		factory.contactSeller = function (contactForm) {
            return $http.post(serviceBase + 'formContactSeller.php', contactForm).then(
				function (results) {
					return results.data;
			});
        };
        return factory;
    };
    contactFactory.$inject = injectParams;
    app.factory('contactService', contactFactory);
});