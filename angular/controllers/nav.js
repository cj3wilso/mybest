define(['angularAMD'], function (angularAMD) {
	angularAMD.controller('NavCtrl', function ($scope, $http, $route, hash) { 
		$http.get("http://mybestapartments.ca/angular/homeNav.php")
		.success(function (response) {
			$scope.hash = hash;
			$scope.results = response;
		});
	});
});