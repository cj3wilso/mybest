require.config({
    baseUrl: 'angular',
    urlArgs: 'v=1.01'
});

require(
    [
		'app',
		'services/routeResolver',
		'services/authService',
		'services/apartmentService',
		'services/navService',
		'filters/urlFormat',
		'controllers/NavController',
		'controllers/ModalController'
    ],
    function () {
        angular.bootstrap(document, ['app']);
    });