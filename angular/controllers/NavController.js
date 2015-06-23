'use strict';

define(['app'], function (app) {

    var injectParams = ['$scope', '$http', 'hash', 'navService'];

    var NavController = function ($scope, $http, hash, navService ) {
		navService.getNav()
            .then(function (data) {
                $scope.hash = hash;
				$scope.results = data;
		}, function (error) {
			$window.alert('Error getting navigation items: ' + error.message);
		});
    };

    NavController.$inject = injectParams;


    //Loaded normally since the script is loaded upfront 
    //Dynamically loaded controller use app.register.controller
    app.controller('NavController', NavController);

});