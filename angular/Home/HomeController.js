'use strict';

define(['app'], function (app) {

    //This controller retrieves data from the customersService and associates it with the $scope
    //The $scope is ultimately bound to the customers view due to convention followed by the routeResolver
    app.register.controller('HomeController', ['$scope', '$rootScope', '$http', '$window', 'hash', 'apartmentService',
     function ($scope, $rootScope, $http, $window, hash, apartmentService) {
		
		apartmentService.getSpotlightApt()
            .then(function (data) {
                $scope.hash = hash;
				$scope.results = data;
				$rootScope.propid = $scope.results.records[0]["ID"];
		}, function (error) {
			$window.alert('Error getting apartment: ' + error.message);
		});
     }]);
});