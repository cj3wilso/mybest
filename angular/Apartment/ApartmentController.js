'use strict';

define(['app'], function (app) {

    //This controller retrieves data from the customersService and associates it with the $scope
    //The $scope is ultimately bound to the customers view due to convention followed by the routeResolver
    app.register.controller('ApartmentController', ['$scope', '$http', '$route', '$routeParams', 'hash', 'apartmentService',
     function ($scope, $http, $route, $routeParams, hash, apartmentService) {
		apartmentService.getAptPage($routeParams.propid)
            .then(function (data) {
                $scope.hash = hash;
				$scope.results = data;
		}, function (error) {
			$window.alert('Error getting apartment: ' + error.message);
		});
		if($scope.includeLibraries) { //the flag was set in the $rootScope object
			$scope = $scope.include(['plugin1', 'library1']);
		}
     }]);
});