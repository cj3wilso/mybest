<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script src="//cdn.jsdelivr.net/modernizr/2.7.1/modernizr.min.js"></script>
<?php 
if(!isset($bootstrap)){
?>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<?php 
}
?>
<script src="https://maps.googleapis.com/maps/api/js?sensor=false&libraries=places&project=158500456510&key=AIzaSyAHa3Sez-R8caiKYjiQv1yzRnzL5fdUKw4"></script>
<script type="text/javascript">
// https://maps.googleapis.com/maps/api/place/autocomplete/json?input=toronto&types=geocode&components=country:ca&sensor=true&project=158500456510&key=AIzaSyAHa3Sez-R8caiKYjiQv1yzRnzL5fdUKw4
function initialize() {
	var input = document.getElementById('addressInput');
	var dropitem = $(".pac-container div");
 	var options = {
 		componentRestrictions: {country: 'ca'}
	};
	var autocomplete = new google.maps.places.Autocomplete(input, options);
	google.maps.event.addListener(autocomplete, 'place_changed', function() {
		$('#topsearch').submit();
    });
}
google.maps.event.addDomListener(window, 'load', initialize);
if($(window).width() < 752){ $('#findapartment').hide();$("#mobile-apt-menu").show();}
</script>
<script src="/assets/js/bootstrap-select.js"></script>
<script src="/assets/js/flatui-radio.js"></script>
<script src="/assets/js/flatui-checkbox.js"></script>
<script src="/assets/js/application.js?ver=20140116"></script>
<script>
$(".link-promote").click(function() {
	_gaq.push(["_trackEvent", "Free Coupon", "Click", "<?php echo $page; ?> Page"]);
});
</script>
<?php 
if(isset($footScripts)){echo $footScripts;}
?>