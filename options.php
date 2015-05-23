<?php
$page = "search";
$headStyles ='';
include("global.php");
$pageTitle = "Refine Your Search For ". $_GET['city'].', '.$_GET['prov'] ." Apartments";
include("form_options.php");
include("header.php");
?>
<ul class="nav nav-pills">
  <li class="active"> <a href="<?php echo $list.'/'.$_GET['prov'].'/'.urlencode($_GET['city']).'/'.urlencode($_GET['options']); ?>"><i class="icon-double-angle-left"></i> Back</a> </li>
</ul>
<form id="options" method="post">
  <input type="hidden" name="lat" value="<?php echo $lat; ?>" />
  <input type="hidden" name="lng" value="<?php echo $lng; ?>" />
  <input type="hidden" name="rad" value="<?php echo $radius; ?>" />
    <h1>Refine Your Search <small>For <?php echo $_GET['city'].', '.$_GET['prov']; ?> Apartments</small></h1>
  <h3>Basic Options</h3>
  <div class="row">
    <div class="col-lg-3">
      <div>Beds</div>
      <select name="beds" class="herolist">
        <option value="" <?php if( !isset($pBed) ) echo  "selected"; ?>>No Preference</option>
        <option value="Studio bed" <?php if( $pBed == "studio bed" ) echo  "selected"; ?>>Studio</option>
        <option value="1 bed" <?php if( $pBed == "1 bed" ) echo  "selected"; ?>>1 Bedroom</option>
        <option value="2 bed" <?php if( $pBed == "2 bed" ) echo  "selected"; ?>>2 Bedrooms</option>
        <option value="3 bed" <?php if( $pBed == "3 bed" ) echo  "selected"; ?>>3 Bedrooms</option>
        <option value="4 bed" <?php if( $pBed == "4 bed" ) echo  "selected"; ?>>4 Bedrooms</option>
      </select>
    </div>
    <div class="col-lg-3">
      <div>Bathrooms</div>
      <select name="bath" class="herolist">
        <option value="" <?php if( !isset($pBa) ) echo  "selected"; ?>>No Preference</option>
        <option value="1 ba" <?php if( $pBa == "1 ba" ) echo  "selected"; ?>>1 Bathroom</option>
        <option value="2 ba" <?php if( $pBa == "2 ba" ) echo  "selected"; ?>>2 Bathroom</option>
        <option value="3 ba" <?php if( $pBa == "3 ba" ) echo  "selected"; ?>>3 Bathroom</option>
      </select>
    </div>
    <div class="col-lg-3">
      <div>Price Range</div>
      <input type="hidden" id="rent" name="price" />
        <div id="slider-range" style="margin-bottom:6px;"></div>
        <input type="text" id="amount" style="border:0;" />
    </div>
    <div class="col-lg-3">
      <div>Distance</div>
      <select name="dist" class="herolist">
        <option value="" <?php if( !isset($pDist) ) echo  "selected"; ?>>No Preference</option>
        <option value="5km" <?php if( $pDist == "5km" ) echo  "selected"; ?>>5 km</option>
        <option value="10km" <?php if( $pDist == "10km" ) echo  "selected"; ?>>10 km</option>
        <option value="20km" <?php if( $pDist == "20km" ) echo  "selected"; ?>>20 km</option>
        <option value="30km" <?php if( $pDist == "30km" ) echo  "selected"; ?>>30 km</option>
        <option value="40km" <?php if( $pDist == "40km" ) echo  "selected"; ?>>40 km</option>
        <option value="50km" <?php if( $pDist == "50km" ) echo  "selected"; ?>>50 km</option>
        <option value="75km" <?php if( $pDist == "75km" ) echo  "selected"; ?>>75 km</option>
        <option value="100km" <?php if( $pDist == "100km" ) echo  "selected"; ?>>100 km</option>
      </select>
    </div>
  </div>
  <hr />
  <h3>Apartment Features</h3>
<?php
include 'pg_feat_options.php';
?>  
  <div class="row">
    <div class="col-lg-12">
    <div class="pull-right">
    <a class="btn btn-lg btn-default" href="<?php echo $opt.'/'.$_GET['prov'].'/'.$_GET['city'] ?>">Clear All</a>
    <input class="btn btn-lg btn-primary" id="done" type="submit" alt="Submit" name="options" value="View Results" /></div>
    </div>
  </div>
</form>
<?php
$maxrent = 10000;
if( isset($pPrice) ){ 
	$maxmin = explode("to", $pPrice);
}else{
	$maxmin[0]=0;$maxmin[1]=$maxrent;
}
include 'footer.php';
include 'footer_js.php';
?>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script>
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
</body>
</html>