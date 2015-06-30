'use strict';

define(['app'], function (app) {
    app.register.controller('ListController', ['$scope', '$http', '$route', '$routeParams', '$location', 'hash', 'apartmentService',
	 function ($scope, $http, $route, $routeParams, $location, hash, apartmentService) {
		$scope.hash = hash;
		apartmentService.getListAddressFeatured($routeParams.prov, $routeParams.city)
            .then(function (data) {
				$scope.featured = data;
		}, function (error) {
			$window.alert('Error getting featured apartments: ' + error.message);
		});
		apartmentService.getList($routeParams.prov, $routeParams.city, $routeParams.page, $location.search().sort)
            .then(function (data) {
				$scope.results = data;
				//Sorting by dropdown
				$scope.sorting = function(order) {
					$location.search('sort', order);
				};
				$scope.sortBy = [
					{ "val": "created-desc", "label": "Date (New to Old)" }, 
					{ "val": "distance-asc", "label": "Distance Nearest" },
					{ "val": "rent-asc", "label": "Price (Low to High)" },
					{ "val": "rent-desc", "label": "Price (High to Low)" },
					{ "val": "name-asc", "label": "Name (A to Z)" },
					{ "val": "name-desc", "label": "Name (Z to A)" }
				];
				if(!$location.search().sort){
					$scope.sortList = $scope.sortBy[0].val;
				}else{
					$scope.sortList = $location.search().sort;
				}
				//Pager URLs
				$scope.pager = function(page) {
					if(hash==undefined){hash='';}
					var split = $location.path().split("/");
					var path = split[0]+'/'+split[1]+'/'+split[2]+'/'+split[3];
					var url = hash+path+'/'+page;
					$location.path(url);;
				};
				//Pager items
				var range = [];
				try{
					$scope.results.paginate.current
				}catch(e){
					//Too much switching crashes fetch for data
					//MySQL comes back with nothing. Why??
					var current = 1;
					console.log("no current ",e)
					console.log($scope.results);
				}
				if(!current){
					var current = parseInt($scope.results.paginate.current);
				}
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