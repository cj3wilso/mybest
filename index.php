<?php
$page = "home";
$metaDesc = "FREE Canadian Apartment guide. Find your next place or post an ad for free.";
include("global.php");
$pageTitle = "Apartment Rentals in Canada";
$headScripts='<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.min.js"></script>';
include("header.php");
?>
<div ng-app="myApp" ng-controller="homeFeaturedProperty" ng-cloak> 
	<div ng-repeat="(key, value) in results">
		<ul ng-if="key == 'IPCity'" class="nav nav-pills" style="padding-bottom:8px;">
		  <li class="active pull-right"> <a href="{{ results.IPCity.CityURL }}">View All {{ results.IPCity.City }} Apartments <i class="icon-double-angle-right"></i></a> </li>
		</ul>
	</div>

	<div id="panels" class="row" itemscope itemtype="https://schema.org/LocalBusiness" ng-repeat="x in results.records">
		<!-- Google geo loc -->
		<div itemprop="geo" itemscope itemtype="http://schema.org/GeoCoordinates">
			<meta itemprop="latitude" content="{{ x.Lat }}">
			<meta itemprop="longitude" content="{{ x.Lng }}">
		</div>
	  <div class="col-xs-8">
		<div class="tile tile-text">
			<div class="tile-text-block">
				<div class="tile-text-title"><a href="{{ x.URL }}">Spotlight<span class="hidden-xs hidden-sm"> Apartment</span></a></div>
				<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress" class="tile-text-thin">In <span itemprop="addressLocality">{{ x.City }}</span>, <span itemprop="addressRegion">{{ x.Prov }}</span>, Canada</div>
			</div>
			<div class="tile-text-block">
			<div class="tile-text-thin">Priced From: {{ x.Rent }}, Bedrooms: {{ x.Beds }}</div>
			<div itemprop="name" class="tile-text-subtitle" title="{{ x.Name }}">{{ x.Name }}</div>
			<div class="tile-text-thin">Page ID: {{ x.ID }}, Posted: {{ x.Date }}</div>
			</div>
			<div class="cta">
				<span ng-if="x.ExternalURL"><a href="{{ x.ExternalURL }}" target="_blank" class="btn btn-inverse tile-btn-secondary"><div class="fui-eye"></div> View Website</a></span>
				<span ng-if="x.Phone"><a href="{{ x.PhoneURL }}" class="btn btn-inverse tile-btn-secondary"><div class="fui-chat"></div>  {{ x.Phone }} </a></span>
				<a href="#myModal" role="button" class="btn btn-primary avail tile-btn-primary" data-toggle="modal" data-prop="{{ x.ID }}"><div class="fui-mail"></div> Check Availability</a>
			
			</div>
			<a href="{{ x.URL }}"><div class="tile-more-stripe">View <span class="hidden-xs hidden-sm">More Details On This </span>Apartment</div></a>
		  </div>
	  </div>
	  <div class="col-xs-4"><div class="tile-img-feat"><a class="tile-feat" href="{{ x.URL }}">
		<span ng-if="!x.Photo"><img src="http://placehold.it/390x390&text=Photos%20Coming%20Soon" itemprop="image" width="390" height="390" class="img-rounded img-responsive" style="border:1px solid #eff0f2;" alt="Photos Coming Soon"></span>
		<span ng-if="x.Photo"><img src="/upload/server/php/files/{{ x.ID }}/slide/{{ x.Photo }}" itemprop="image" width="390" height="390" class="img-rounded img-responsive" style="border:1px solid #eff0f2;" alt="{{ x.Name }}"></span>
		</a></div></div>
	</div>
</div>
<?php
include 'footer.php';
?>
<!-- Modal -->
<div class="modal fade" id="myModal">
<div class="modal-dialog">
      <div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">Contact Form</h4>
  </div>
  <form id="contactSeller" method="post">
    <input id="prop" name="prop" type="hidden" />
    <input name="page" type="hidden" value="list" />
    <div class="modal-body">
      <div class="form_result"></div>
      <div class="row form-group">
        <div class="col-lg-3">Your Name</div>
        <div class="col-lg-9">
          <input class="form-control" id="cname" type="text" name="dname" minlength="2" />
        </div>
      </div>
      <div class="row form-group">
        <div class="col-lg-3">Your Email <span class="red">*</span></div>
        <div class="col-lg-9">
          <input class="form-control email required" id="cemail" type="text" name="demail" />        </div>
      </div>
      <div class="row">
      <div class="col-lg-12">
      Your Message <span class="red">*</span> <br />
        <textarea class="form-control required" id="ccomment" name="dcomment" rows="5"></textarea>
        <small class="pull-right"><span class="red">*</span> Required field</small> 
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
      <button id="seller" class="btn btn-primary" type="submit" name="seller">Send</button>
    </div>
  </form>
</div></div>
</div>
<!-- #myModal -->
<?php
include 'footer_js.php';
?>
<script>
var app = angular.module('myApp', []);
app.controller('homeFeaturedProperty', function($scope, $http) {
    $http.get("http://mybestapartments.ca/angular/homeFeaturedProperty.php")
    .success(function (response) {$scope.results = response;});
});
</script>
<script>
$(".avail").click(function() {
  $("#myModal").find(".alert").hide();
  var id_prop = $(this).attr("data-prop");
  $("input[name=prop]").val(id_prop);
});
$("#seller").click(function() {
    var url = "http://mybestapartments.ca/_inc/form_contact_seller.php";
    $.ajax({
           type: "POST",
           url: url,
           data: $("#contactSeller").serialize(),
           success: function(data){
			   $("#contactSeller").find(".form_result").html(data);
			   _gaq.push(["_trackEvent", "Appointment", "Email", "Home Page"]);
           }
         });
	$("#contactSeller")[0].reset();
    return false;
});
</script>
<script>
console.log("<p>i am root "+window.location.href.split('www')[0] + 'www'+"</p>");
</script>
</body></html>