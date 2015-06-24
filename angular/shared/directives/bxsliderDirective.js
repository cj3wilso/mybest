'use strict';

define(['app'], function (app) {
    app.directive('bxsliderDirective', function() {
	  function link(scope, element, attrs) {
		console.log('anything?');
		alert('i am loaded');
		
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
	});
});