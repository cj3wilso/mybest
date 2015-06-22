define(['angularAMD', 'angular-route'], function (angularAMD) {
  var app = angular.module("myApp", ['ngRoute', 'ngSanitize']).constant('hash', hash);
  app.config(function ($routeProvider, $locationProvider, $compileProvider) {
    if(!hash) {
		$locationProvider.html5Mode(true);
    }
	$routeProvider
    .when("/amd/home", angularAMD.route({
        templateUrl: 'amd/view_home.html', controller: 'HomeCtrl', controllerUrl: 'controller_home'
    }))
    .when("/amd/view1", angularAMD.route({
        templateUrl: 'amd/view_view1.html', controller: 'View1Ctrl', controllerUrl: 'controller_view1'
    }))
    .otherwise({redirectTo: "/amd/home"});
	
	return angularAMD.bootstrap(app);
});
define(['app'], function (app) {
    app.filter('urlFormat', function () {
        return function (text) {
			var str = text.replace(/\s+/g, '-');
			return str.toLowerCase();
        };
	});
});
define(['app'], function (app) {
    app.directive('loadScript', ['$interval', 'dateFilter', function($interval, dateFilter) {
	  function link(scope, element, attrs) {
		//Load BX CSS Library
		var bxsliderCSS=document.createElement("link");
		bxsliderCSS.setAttribute("rel", "stylesheet");
		bxsliderCSS.setAttribute("type", "text/css");
		bxsliderCSS.setAttribute("href", "assets/js/bxslider/jquery.bxslider.css");
		document.getElementById("cssPlugins").appendChild(bxsliderCSS);

		//Load BX JS Library
		var bxsliderJs= document.createElement('script');
		bxsliderJs.type= 'text/javascript';
		bxsliderJs.src= 'assets/js/bxslider/jquery.bxslider.min.js?v=150621';
		//element.append(bxsliderJs);
		document.getElementById("jsPlugins").appendChild(bxsliderJs);

		//Load BX function
		var bxLoad= document.createElement('script');
		bxLoad.type= 'text/javascript';
		bxLoad.src= 'bxLoad.js';
		document.getElementById("jsPlugins").appendChild(bxLoad);
	  }
	  return {
		link: link
	  };
	}]);
});