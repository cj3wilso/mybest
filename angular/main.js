require.config({
    baseUrl: "angular",
    
    // alias libraries paths.  Must set 'angular'
    paths: {
        'angular': 'http://cdnjs.cloudflare.com/ajax/libs/angular.js/1.4.1/angular',
        'angular-route': 'http://cdnjs.cloudflare.com/ajax/libs/angular.js/1.4.1/angular-route.min',
		'angular-sanitize': 'http://cdnjs.cloudflare.com/ajax/libs/angular.js/1.4.1/angular-sanitize.min',
        'angularAMD': 'http://cdn.jsdelivr.net/angular.amd/0.2.0/angularAMD.min',
		'ui-bootstrap' : 'https://cdnjs.cloudflare.com/ajax/libs/angular-ui-bootstrap/0.13.0/ui-bootstrap-tpls'
    },
    
    // Add angular modules that does not support AMD out of the box, put it in a shim
    shim: {
        'angularAMD': ['angular'],
        'angular-route': ['angular'],
		'angular-sanitize': ['angular'],
        'ui-bootstrap': ['angular']
    },
    
    // kick start application
    deps: ['app']
});