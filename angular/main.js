require.config({
    baseUrl: 'angular',
    urlArgs: 'v=1.03',    
    paths: {
        'jquery': 'https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min',
		'angular': 'http://cdnjs.cloudflare.com/ajax/libs/angular.js/1.4.1/angular',
        'angular-route': 'http://cdnjs.cloudflare.com/ajax/libs/angular.js/1.4.1/angular-route',
        'angular-sanitize': 'http://cdnjs.cloudflare.com/ajax/libs/angular.js/1.4.1/angular-sanitize',
		'ui-bootstrap': 'https://cdnjs.cloudflare.com/ajax/libs/angular-ui-bootstrap/0.13.0/ui-bootstrap-tpls',
		'bx-slider': 'http://bxslider.com/lib/jquery.bxslider'
    },
    shim: { 
		'angular-route': ['angular'],
		'angular-sanitize': ['angular'], 
		'ui-bootstrap': ['angular'],
		'bx-slider': ['angular']
	}
});

require(
    [
		'app',
		'shared/services/routeResolver',
		'shared/services/authService',
		'shared/services/apartmentService',
		'shared/services/contactService',
		'nav/navService',
		'nav/NavController',
		'shared/filters/urlFilter',
		'shared/filters/setDecimalFilter',
		'shared/directives/bxSliderDirective',
		'shared/controllers/modalController'
    ],
    function () {
        angular.bootstrap(document, ['app']);
    });