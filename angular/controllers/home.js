define(['app'], function (app) {
    app.controller('HomeCtrl', function ($scope, $http, $route, hash) { 
		$http.get("http://mybestapartments.ca/angular/homeFeaturedProperty.php")
		.success(function (response) {
			$scope.hash = hash;
			$scope.results = response;
		});
	});
}); 