'use strict';

define(['app'], function (app) {
	app.controller('ModalController', function ($scope, $modal, $log) {

	  $scope.items = ['item1', 'item2', 'item3'];

	  $scope.animationsEnabled = true;

	 
	 $scope.open = function (size) {

		var modalInstance = $modal.open({
		  animation: $scope.animationsEnabled,
		  templateUrl: 'modal.html',
		  controller: 'ModalInstanceController',
		  size: size,
		  resolve: {
			items: function () {
			  return $scope.items;
			}
		  }
		});

		modalInstance.result.then(function (selectedItem) {
		  $scope.selected = selectedItem;
		}, function () {
		  $log.info('Modal dismissed at: ' + new Date());
		});
	  };

	  $scope.toggleAnimation = function () {
		$scope.animationsEnabled = !$scope.animationsEnabled;
	  };

	});
	// Please note that $modalInstance represents a modal window (instance) dependency.
	// It is not the same as the $modal service used above.

	app.controller('ModalInstanceController', function ($scope, $modalInstance, items) {

	  $scope.items = items;
	  $scope.selected = {
		item: $scope.items[0]
	  };

	  $scope.ok = function () {
		//$modalInstance.close($scope.selected.item);
		
		var data = angular.element( document.querySelector( "#contactSeller" ).serialize() );
		console.log('form data is: '+formData);
		
		$http.post("http://mybestapartments.ca/_inc/form_contact_seller.php", data)
		.success(function (response) {
			$scope.message = response;
			_gaq.push(["_trackEvent", "Appointment", "Email", "Home Page"]);
		});
		
		angular.element( document.querySelector( "#contactSeller" ) )[0].reset();
		
	  };

	  $scope.cancel = function () {
		$modalInstance.dismiss('cancel');
	  };
	});
});