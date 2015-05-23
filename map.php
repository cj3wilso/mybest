<?php
$page = "map";
$headStyles ='';
include("global.php");
include("form_results.php");
if ($search_type == "nosearch"){
	header('Location: '.$list);
}
include("form_quicksearch.php");
if( $city ){ 
	$pageTitle = "Rentals in ".$city.', '.$prov;
}else{
	$pageTitle = "View rental: ".$display_address;
}
include("header.php");
?>
<ul class="breadcrumb">
  <li><a href="/">Home</a> </li>
<?php
if( $search && $prov ){ 
?>
  <li><a href="<?php echo $list ?>">Canada</a> </li>
  <li><a href="<?php echo $list.'/'.$prov; ?>"><?php echo $prov; ?></a> </li>
  <li><a href="<?php echo $list.'/'.$prov.'/'.urldecode($city); ?>"><?php echo $city; ?></a> </li>
  <li class="active"><?php echo $display_address; ?></li>
<?php
}else if( $area ){ 
?>
<li><a href="<?php echo $list ?>">Canada</a> </li>
<li><a href="<?php echo $list.'/'.$prov; ?>"><?php echo $prov; ?></a> </li>
<li class="active"><?php echo $area; ?></li>
<?php
}else{ 
?>
<li class="active"><?php echo $display_address; ?></li>
<?php
}
$within="";
if($radius!=0.01){
	$within = "within ".$radius." kilometres";
}
?>
</ul>
<div class="row">
  <div class="col-lg-9"> 
  <h1>Apartments near "<?php echo urldecode($city).", ".$prov; ?>" <span class="badge"><span id="resultsnum"></span> <?php echo $within; ?></span></h1>
    <select id="locationSelect" class="visible-sm form-control"></select>
  </div>
  <div class="col-lg-3"> <a href="<?php echo $list.$listparam; ?>" class="btn btn-block btn-hg btn-primary">List view</a> </div>
</div>
<div class="row" style="position:relative;">
  <div class="col-lg-9" id="map" style="height: 600px"> </div>
  <div class="col-lg-3" style="position:static;">
    <div id="sidebar">
    <?php include("pg_quicksearch.php"); ?>
    <?php include("pg_feedback.php"); ?>
    </div>
  </div>
</div>
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
        <textarea class="form-control required" id="ccomment" name="dcomment" rows="5" minlength="10"></textarea>
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
if(!isset($maxrent)){$maxrent="";}
$open = "";
if( isset($pBed) ) $open .= '$( "#beds-panel small" ).text( $( "#beds label.checked" ).text() ).addClass("selected");';
if( isset($pBa) ) $open .= '$( "#baths-panel small" ).text( $( "#baths label.checked" ).text() ).addClass("selected");';
if( isset($pDist) ) $open .= '$( "#distance-panel small" ).text( $( "#distance label.checked" ).text() ).addClass("selected");';
if( isset($search_feat['washfacil']) || isset($search_feat['washunit']) || isset($search_feat['washconn']) ) $open .= '$( "#laundry-panel small" ).text( $( "#laundry label.checked" ).text() ).addClass("selected");';
if( isset($search_feat['petsallow']) ) $open .= '$( "#pets-panel small" ).text( $( "#pets label.checked" ).text() ).addClass("selected");';
if( isset($pPrice) ){ 
	$open .= '$( "#price-panel small" ).text( $( "#price #amount" ).val() ).addClass("selected");';
	$maxmin = explode("to", $pPrice);
}else{
	$maxmin[0]=0;$maxmin[1]=$maxrent;
}
include 'footer.php';
include 'footer_js.php';
?>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script>
$(window).load(function() {
	<?php echo $open; ?> 
});
$(".panel .radio").click(function() {
	var paneltext = $.trim($( this ).text());
	$( this ).parents( ".panel" ).find("small").text( paneltext );
	if( paneltext != "No Preference" ){
		$( this ).parents( ".panel" ).find("small").addClass("selected");
	}else{
		$( this ).parents( ".panel" ).find("small").removeClass("selected");
	}
});
$( ".panel #slider-range" ).on( "slidechange", function( event, ui ) {
	var paneltext = $.trim($( this ).parents( "#price" ).find("#amount").val());
	if( paneltext != "$0 - $10000" ){
		$( this ).parents( ".panel" ).find("small").text( paneltext ).addClass("selected");
	}else{
		$( this ).parents( ".panel" ).find("small").text( "No Preference" ).removeClass("selected");
	}
});
$(".support").click(function() {
  $("#feedback").find(".alert").hide();
});
$("#feedbackSubmit").click(function() {
	var url = "/_inc/form_feedback.php";
    $.ajax({
           type: "POST",
           url: url,
           data: $("#feedbackForm").serialize(),
           success: function(data){
			   $("#feedbackForm").find(".form_result").html(data);
           }
         });
	$("#feedbackForm")[0].reset();
    return false;
});
$(function() {
	$( "#slider-range" ).slider({
		range: true,
		min: 0,
		max: 10000,
		values: [ '<?php echo $maxmin[0]; ?>', '<?php echo $maxmin[1]; ?>' ],
		slide: function( event, ui ) {
			$( "#amount" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
			$( "#rent" ).val( ui.values[ 0 ] + "to" + ui.values[ 1 ] );
		}
	});
	$( "#amount" ).val( "$" + $( "#slider-range" ).slider( "values", 0 ) + " - $" + $( "#slider-range" ).slider( "values", 1 ) );
	$( "#rent" ).val( $( "#slider-range" ).slider( "values", 0 ) + "to" + $( "#slider-range" ).slider( "values", 1 ) );
});
</script>
<script src="/assets/js/page/maps.js" type="text/javascript"></script>
<script type="text/javascript">
var address = '<?php echo $address; ?>';
var params = '<?php echo $mapparam; ?>';
load();
if (address!=null){searchLocations(address, params);}
</script>
</body>
</html>