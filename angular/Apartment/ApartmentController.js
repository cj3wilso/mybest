'use strict';

define(['app'], function (app) {
    app.register.controller('ApartmentController', ['$scope', '$http', '$route', '$routeParams', 'hash', 'apartmentService',
     function ($scope, $http, $route, $routeParams, hash, apartmentService) {
		apartmentService.getAptPage($routeParams.propid)
            .then(function (data) {
                $scope.hash = hash;
				$scope.results = data;	
		}, function (error) {
			$window.alert('Error getting apartment: ' + error.message);
		});
     }]);
});