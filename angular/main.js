require.config({
    baseUrl: 'angular',
    urlArgs: 'v=1.02',    
    paths: {
        'angular': 'http://cdnjs.cloudflare.com/ajax/libs/angular.js/1.4.1/angular',
        'angular-route': 'http://cdnjs.cloudflare.com/ajax/libs/angular.js/1.4.1/angular-route',
        'angular-sanitize': 'http://cdnjs.cloudflare.com/ajax/libs/angular.js/1.4.1/angular-sanitize',
		'ui-bootstrap': 'https://cdnjs.cloudflare.com/ajax/libs/angular-ui-bootstrap/0.13.0/ui-bootstrap-tpls'
    },
    shim: { 
		'angular-route': ['angular'],
		'angular-sanitize': ['angular'], 
		'ui-bootstrap': ['angular'], 
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
		'shared/filters/urlFormat',
		'nav/NavController',
		'shared/controllers/modalController'
    ],
    function () {
        angular.bootstrap(document, ['app']);
    });