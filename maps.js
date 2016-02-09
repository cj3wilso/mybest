/* ==========================================================
 * google maps api
 * ========================================================== */

function extractUrlValue(key, url)
{
    if (typeof(url) === 'undefined')
        url = window.location.href;
    var match = url.match('[?&]' + key + '=([^&]+)');
    return match ? match[1] : null;
}


var script = '<script type="text/javascript" src="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclusterer/src/markerclusterer';
      if (document.location.search.indexOf('compiled') !== -1) {
        script += '_compiled';
      }
      script += '.js"><' + '/script>';
      document.write(script);

var script2 = '<script type="text/javascript" src="oms.min'+'.js"><' + '/script>';
document.write(script2);


var map, infoWindow, locationSelect, oms;
    var markers = [];

    function load() {
      map = new google.maps.Map(document.getElementById("map"), {
        center: new google.maps.LatLng(44.335636, -78.079834),
        zoom: 8,
        mapTypeId: 'roadmap',
        mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU}
      });
	  oms = new OverlappingMarkerSpiderfier(map, {markersWontMove: true, markersWontHide: false, keepSpiderfied: true});
	  infoWindow = new google.maps.InfoWindow();
	  locationSelect = document.getElementById("locationSelect");
      locationSelect.onchange = function() {
        var markerNum = locationSelect.options[locationSelect.selectedIndex].value;
        if (markerNum != "none"){
          google.maps.event.trigger(markers[markerNum], 'click');
        }
      };
   }

   function searchLocations(address, params, infowin) {
     var geocoder = new google.maps.Geocoder();
     geocoder.geocode({address: address}, function(results, status) {
       
	   if (status == google.maps.GeocoderStatus.OK) {
		searchLocationsNear(results[0].geometry.location, address, params, infowin);
       } else {
         //alert(address + ' not found');
       }
     });
   }

   function clearLocations() {
     /*
	 infoWindow.close();
     for (var i = 0; i < markers.length; i++) {
       markers[i].setMap(null);
     }
     markers.length = 0;
	 */
	var markers = oms.getMarkers();
	if (markers) {
		for (i in markers) {
			markers[i].setMap(null);
		}
		markers.length = 0;
	}
	oms.clearMarkers();
	 
     locationSelect.innerHTML = "";
     var option = document.createElement("option");
     option.value = "none";
     option.innerHTML = "See all results:";
     locationSelect.appendChild(option);
   }

   function searchLocationsNear(center, input, params, infowin) {
	 clearLocations();
     var searchUrl = '/phpsqlsearch_genxml.php' + params + '&lat=' + center.lat() + '&lng=' + center.lng();
	 downloadUrl(searchUrl, function(data) {
	   var xml = parseXml(data);
	   document.getElementById("resultsnum").innerHTML=xml.documentElement.getAttribute("rows");
       var markerNodes = xml.documentElement.getElementsByTagName("marker");
       var bounds = new google.maps.LatLngBounds();
	   for (var i = 0; i < markerNodes.length; i++) {
		 var name = markerNodes[i].getAttribute("name");
         var address = markerNodes[i].getAttribute("address");
         var distance = parseFloat(markerNodes[i].getAttribute("distance"));
         var latlng = new google.maps.LatLng(
              parseFloat(markerNodes[i].getAttribute("lat")),
              parseFloat(markerNodes[i].getAttribute("lng")));
		var rent = markerNodes[i].getAttribute("rent");
		 var beds = markerNodes[i].getAttribute("beds");
		 var phone = markerNodes[i].getAttribute("phone");
		 var address = markerNodes[i].getAttribute("address");
		 var detail = markerNodes[i].getAttribute("detail");
		 var id_pg = markerNodes[i].getAttribute("id_pg");
		 var img = markerNodes[i].getAttribute("img");
         createOption(name, distance, i);
         createMarker(latlng, address, name, distance.toFixed(2)+' km', rent, beds, phone, address, detail, id_pg, img, infowin);
		 bounds.extend(latlng);
       }
       map.fitBounds(bounds);
	   var mcOptions = {gridSize: 50, maxZoom: 15};
	   var markerCluster = new MarkerClusterer(map, markers, mcOptions);
	   locationSelect.style.visibility = "visible";
       locationSelect.onchange = function() {
         var markerNum = locationSelect.options[locationSelect.selectedIndex].value;
         google.maps.event.trigger(markers[markerNum], 'click');
       };
      });
    }

    function createMarker(latlng, address, name, distance, rent, beds, phone, address, detail, id_pg, img, infowin) {
      var icon = 'http://www.google.com/intl/en_us/mapfiles/ms/micons/blue-dot.png';
	  if (1==2){ html += "<br />Distance: " + distance; icon = 'http://www.google.com/intl/en_us/mapfiles/ms/micons/red-dot.png'; }
      html = '<div class="media"><a href="'+detail+'/ON/Toronto/'+encodeURIComponent(name).replace(/%20/g, '+')+'/'+id_pg+'" class="pull-left"><img class="media-object img-polaroid" src="'+img+'"> </a> <div class="media-body"><h4 class="media-heading"><a href="'+detail+'/ON/Toronto/'+encodeURIComponent(name).replace(/%20/g, '+')+'/'+id_pg+'">' + name + "</b> <br/>" + '</a></h4>'+phone+address+' <br />Distance from centre: '+distance+'<br />Price: '+rent+', Beds: '+beds;
	  var marker = new google.maps.Marker({
        map: map,
        position: latlng,
		info: html,
		icon: icon
      });
      if (infowin != false){
		  /*
		  google.maps.event.addListener(marker, 'click', function() {
			infoWindow.setContent(this.info);
			infoWindow.open(map, marker);
		  });
		  */
		oms.addListener('click', function(marker) {
			infoWindow.setContent(marker.info);	
			infoWindow.open(map, marker);
		});
	  }
	  //markers.push(marker);
	  oms.addMarker(marker);
    }

	function createOption(name, distance, num) {
      var option = document.createElement("option");
      option.value = num;
      option.innerHTML = name + "(" + distance.toFixed(1) + ")";
      locationSelect.appendChild(option);
    }

    function downloadUrl(url, callback) {
      var request = window.ActiveXObject ?

          new ActiveXObject('Microsoft.XMLHTTP') :
          new XMLHttpRequest;

      request.onreadystatechange = function() {
        if (request.readyState == 4) {
          request.onreadystatechange = doNothing;
          callback(request.responseText, request.status);
        }
      };

      request.open('GET', url, true);
      request.send(null);
    }

    function parseXml(str) {
      if (window.ActiveXObject) {
        var doc = new ActiveXObject('Microsoft.XMLDOM');
        doc.loadXML(str);
        return doc;
      } else if (window.DOMParser) {
        return (new DOMParser).parseFromString(str, 'text/xml');
      }
    }

    function doNothing() {}