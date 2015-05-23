var up206b = {};
var map;
var infoWindow;
var infoWindowMain;

//trace function for debugging
function trace(message){
	if (typeof console != 'undefined'){
   		console.log(message);
 	}
}

//Function that gets run when the document loads
function initialize(lat,lng,name,address){
	var latlng = new google.maps.LatLng(lat,lng);
  	var mapOptions = {
   		zoom: 16,
      	center: latlng,
     	mapTypeId: google.maps.MapTypeId.ROADMAP
	};
 	map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
	infoWindow = new google.maps.InfoWindow();
	infoWindowMain = new google.maps.InfoWindow();
 	var marker = new google.maps.Marker({
   		position: latlng,
      	map: map,
      	title: name,
		zIndex: google.maps.Marker.MAX_ZINDEX + 1
  	});
	infoWindowMain.setOptions({
        content: "<strong>"+marker.title+"</strong><br>"+address,
        position: latlng,
		zIndex: google.maps.Marker.MAX_ZINDEX + 1
    });
    infoWindowMain.open(map, marker); 
	google.maps.event.addListener(marker, 'click', function() {
		infoWindowMain.open(map, marker);
		infoWindow.close();
	});
	//Map schools
  	up206b.placesRequest('Shopping Facilities',latlng,1000,['store','shopping_mall','department_store'],'shoppingmall.png');
	up206b.placesRequest('Schools and Universities',latlng,1000,['school','university'],'/assets/ico/google/university.png');
	up206b.placesRequest('Taxis, Trains, Cars, Buses, Subways',latlng,1000,['airport','car_rental','bus_station','subway_station','taxi_stand','train_station'],'/assets/ico/google/taxi.png');
	up206b.placesRequest('Food and Restaurants',latlng,1000,['food','bakery','restaurant'],'/assets/ico/google/fastfood.png');
	up206b.placesRequest('Health and Amenities',latlng,1000,['hospital','doctor','dentist','laundry','post_office'],'/assets/ico/google/deptstore.png');
	up206b.placesRequest('Entertainment and Recreational',latlng,1000,['movie_theater','park','bar','art_gallery','amusement_park','gym'],'/assets/ico/google/theater.png');
	$('.gm-style-cc').hide();
}

//Request places from Google
up206b.placesRequest = function(title,latlng,radius,types,icon){
	//Parameters for our places request
	var request = {
   		location: latlng,
    	radius: radius,
   		types: types
  	};
    //Make the service call to google
    var callPlaces = new google.maps.places.PlacesService(map);
    callPlaces.search(request, function(results,status){
 		//trace what Google gives us back
   		trace(results);
		$.each(results, function(i,place){
        	var type = "";
			$.each(types, function(i,name){
				type += name.replace("_", " ") + ", ";
			})
			type = type.slice(0,-2)
			var placeLoc = place.geometry.location;
          	var marker = new google.maps.Marker({
            	map: map,
            	position: place.geometry.location,
             	icon: icon,
             	title: place.name,
				info: type
        	});
			google.maps.event.addListener(marker, 'click', function() {
				infoWindow.setContent("<strong>"+this.title+"</strong><br><div id='cat'>Category: "+this.info+'</div>');
				_gaq.push(["_trackEvent", "Map Icons", "Info Window", "Apartment Page"]);
				infoWindow.open(map, marker);
				infoWindowMain.close();
		 	});
  		})
	});
}