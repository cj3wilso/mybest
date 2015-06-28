'use strict';

define(['app'], function (app) {
    app.register.controller('ListController', ['$scope', '$http', '$route', '$routeParams', 'hash', 'apartmentService',
	 function ($scope, $http, $route, $routeParams, hash, apartmentService) {
		$scope.hash = hash;
		apartmentService.getListAddressFeatured($routeParams.prov, $routeParams.city)
            .then(function (data) {
				$scope.featured = data;
		}, function (error) {
			$window.alert('Error getting featured apartments: ' + error.message);
		});
		apartmentService.getList($routeParams.prov, $routeParams.city, $routeParams.page)
            .then(function (data) {
				$scope.results = data;
				var range = [];
				var current = parseInt($scope.results.paginate.current);
				$scope.start = current;
				$scope.items = 5;
				for(var i=current;i<=$scope.results.paginate.total;i++) {
				  if (i == $scope.start+$scope.items) { break; }
				  range.push(i);
				}
				$scope.range = range;
		}, function (error) {
			$window.alert('Error getting list: ' + error.message);
		});
     }]);
});