'use strict';

define(['app'], function (app) {
    app.register.controller('ApartmentController', ['$scope', '$http', '$route', '$rootScope', '$routeParams', 'hash', 'apartmentService',
     function ($scope, $http, $route, $rootScope, $routeParams, hash, apartmentService) {
		apartmentService.getAptPage($routeParams.propid)
            .then(function (data) {
                $scope.hash = hash;
				$scope.results = data;	
				$rootScope.propid = $scope.results.records["id_pg"];
		}, function (error) {
			$window.alert('Error getting apartment: ' + error.message);
		});
     }]);
});