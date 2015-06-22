define(['app'], function (app) {
    app.controller('ApartmentCtrl', function ($scope, $http, $route, hash, $routeParams) { 
		$scope.format = 'M/d/yy h:mm:ss a';
		$scope.name = $routeParams.propid;
		$http.get("http://mybestapartments.ca/angular/apartment.php", {
			params: {
				prop_id: $routeParams.propid
			}
		})
		.success(function (response) {
			$scope.hash = hash;
			$scope.results = response;
		});
	});
});