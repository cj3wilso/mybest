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

	app.controller('ModalInstanceController', function ($scope, $rootScope, $modalInstance, items, $http, $window, contactService) {
		$scope.items = items;
		$scope.selected = {
			item: $scope.items[0]
		};
		$scope.ok = function () {
			$scope.$watch("page", function(){
				$scope.contact.page = $scope.page;
				$scope.contact.prop = $rootScope.propid;
				var formContact = $scope.contact;
				console.log(formContact);
				contactService.contactSeller(formContact).then(function (data) {
					console.log('form successful!');
					$window.alert('results here: ' + data);
					$scope.message = data;
					_gaq.push(["_trackEvent", "Appointment", "Email", "Home Page"]);
					//$modalInstance.close($scope.selected.item);
					angular.element( document.querySelector( "#contactSeller" ) )[0].reset();
				}, function (error) {
					$window.alert('Error emailing: ' + error.message);
					angular.element( document.querySelector( "#contactSeller" ) )[0].reset();
				});
			});
		
		};

		$scope.cancel = function () {
			$modalInstance.dismiss('cancel');
		};
	});
});