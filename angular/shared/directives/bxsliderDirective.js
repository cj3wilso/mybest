'use strict';

define(['app'], function (app) {
	app.directive('bxSlider', function ($timeout) {
		return {
			restrict: 'A',
			scope: {
				prop: '@',
				size: '@',
				name : '@',
				thumbPager: '@'
			},
			controller: function($scope, apartmentService, hash) {
				apartmentService.getPhotos($scope.prop).then(function (data) {
					$scope.hash = hash;
					$scope.images = data;
					$scope.pager = data;
				}, function (error) {
					$window.alert('Error getting photos: ' + error.message);
				});
			},
			template: '<ul class="bxslider" id="{{name}}">' +
					   '<li ng-repeat="image in images.records">' +
						'<img ng-show="size == \'390\'" ng-src="http://mybestapartments.ca/upload/server/php/files/{{prop}}/slide/{{image}}" alt="" itemprop="image" width="{{size}}" height="{{size}}" class="img-responsive" />' +
						'<img ng-show="size == \'115\'" ng-src="http://mybestapartments.ca/upload/server/php/files/{{prop}}/thumbnail/{{image}}" alt="" itemprop="image" width="{{size}}" height="{{size}}" class="img-responsive" />' +
					   '</li>' +
					  '</ul>' +
					  '<div id="bx-pager" style="text-align: center;" ng-show="thumbPager == \'true\'">' +
					  '<a ng-repeat="thumb in pager.records" data-slide-index="{{($index + 1) - 1}}" href="">' +
					  '<img ng-src="http://mybestapartments.ca/upload/server/php/files/{{prop}}/mobile/{{thumb}}" width="60" height="60" />' +
					  '</a>' +
					  '</div>',
			link: function (scope, elm, attrs) {
				var loadSlider = function () {	
					elm.css({ 
						width: scope.size+'px' 
					});
					var slider = $('#'+scope.name).bxSlider({
						slideWidth: scope.size,
						startingSlide: 0,
						pagerCustom: '#bx-pager'
					});
				}
				scope.$watch("pager", function() {
					if (scope.pager) {
						loadSlider();
					}
				});
			}
		};
	});
});