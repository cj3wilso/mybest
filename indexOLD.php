<?php
$page = "home";
$metaDesc = "FREE Canadian Apartment guide. Find your next place or post an ad for free.";
include("global.php");

require("mysqlconnect.php");

//Do we have any live ads?
$gotads ="SELECT p.id_prop 
		FROM prop_promote p 
		INNER JOIN properties pp ON p.id_prop = pp.id_pg 
		WHERE p.expired = '0000-00-00 00:00:00' AND p.payer_id IS NOT NULL AND pp.pub=1 AND p.sku = 'B0012'";
$gotads = mysql_query($gotads);

//Test
//unset($_COOKIE['lastSearch']);
//$_SERVER['REMOTE_ADDR'] = "216.110.94.228"; //Houston

//Set country code - Changes if IP address in another country
$cnty="CA";

//Get user's last searched city from cookie
if(isset($_COOKIE['lastSearch'])){
	$lastSearch = explode("+", $_COOKIE['lastSearch']);
	list($lat, $lng, $city, $prov_code) = $lastSearch;
}

//If no cookie - Get the city from IP address
if(!isset($lastSearch)){$ipCity = list($lat, $lng, $city, $prov_code, $cnty) = getIPCity();}

//Queuries to select promos from saved srearch or user IP lat/lng
$select_distance=",( 6371 * acos( cos( radians($lat) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians($lng) ) + sin( radians($lat) ) * sin( radians( lat ) ) ) ) AS distance";
$having_distance="HAVING distance < $radius_default";

//Do we have any ads in city?
$cityquery = "SELECT p.id_prop
		$select_distance
		FROM prop_promote p 
		INNER JOIN properties pp ON p.id_prop = pp.id_pg 
		INNER JOIN prop_units u ON p.id_prop = u.id_prop 
		WHERE p.expired = '0000-00-00 00:00:00' AND p.payer_id IS NOT NULL AND pp.pub=1 AND p.sku = 'B0012' 
		$having_distance";
$city_exists = mysql_query($cityquery);

//If no ads in city then don't search lat/lng
if(mysql_num_rows($city_exists) == 0){
	$select_distance=$having_distance=NULL;
}

//If country code isn't CA then don't show "Search by city" area
if($cnty != "CA"){$city=NULL;}

if(mysql_num_rows($gotads) == 0){
	/* If no ads then show any post */
	/* http://jan.kneschke.de/projects/mysql/order-by-rand/ */
	$sql = "SELECT *,  
		CONCAT('$', MIN(u.rent)) as rent, 
		IF(COUNT( DISTINCT u.beds ) > 1,CONCAT(MIN(u.beds), ' - ', MAX(u.beds)),u.beds) as beds
		FROM properties pp 
		JOIN
		   (SELECT (RAND() *
						 (SELECT MAX(id)
							FROM properties pp WHERE pp.pub=1 $where_city)) AS id)
			AS p2 
		INNER JOIN prop_units ON pp.id_pg = prop_units.id_prop 
		INNER JOIN prop_feat a ON pp.id_pg = a.id_prop 
		INNER JOIN prop_feat b ON pp.id_pg = b.id_prop 
		LEFT JOIN prop_photos c ON pp.id_pg = c.id_prop AND c.p_order = 1 
		WHERE pp.id >= p2.id AND pp.pub=1 $city 
		GROUP BY pp.id_pg  
		ORDER BY pp.id ASC 
		LIMIT 1";
	$row_promo = mysql_fetch_array(mysql_query($sql));
}else{
	/* Select a random promo - either by city or site wide */
	$promo_sql ="SELECT *,
		CONCAT('$', MIN(u.rent)) as rent, 
		IF(COUNT( DISTINCT u.beds ) > 1,CONCAT(MIN(u.beds), ' - ', MAX(u.beds)),u.beds) as beds 
		$select_distance 
		FROM prop_promote p 
		INNER JOIN properties pp ON p.id_prop = pp.id_pg 
		INNER JOIN prop_units u ON p.id_prop = u.id_prop 
		LEFT JOIN prop_photos c ON p.id_prop = c.id_prop AND c.p_order = 1 
		WHERE p.expired = '0000-00-00 00:00:00' AND p.payer_id IS NOT NULL AND pp.pub=1 AND p.sku = 'B0012'  
		GROUP BY p.id_prop 
		$having_distance 
		ORDER BY RAND() 
		LIMIT 1";
	$promoresult = mysql_query($promo_sql);
	$row_promo = mysql_fetch_array($promoresult);
	$promo_num_rows = mysql_num_rows($promoresult);
}
require("mysqlclose.php");
$pageTitle = "Apartment Rentals in Canada";
$headScripts="";
include("header.php");
?>
<?php
if(isset($city)){
?>
<ul class="nav nav-pills" style="padding-bottom:8px;">
  <li class="active pull-right"> <a href="<?php echo $list.'/'.$prov_code.'/'.urlencode($city); ?>">View All <?php echo $city; ?> Apartments <i class="icon-double-angle-right"></i></a> </li>
</ul>
<?php
}
?>
<div id="panels" class="row" itemscope itemtype="https://schema.org/LocalBusiness">
	<!-- Google geo loc -->
	<div itemprop="geo" itemscope itemtype="http://schema.org/GeoCoordinates">
    	<meta itemprop="latitude" content="<?php echo $row_promo['lat']; ?>">
        <meta itemprop="longitude" content="<?php echo $row_promo['lng']; ?>">
  	</div>
  <div class="col-xs-8">
    <div class="tile tile-text">
    	<div class="tile-text-block">
            <div class="tile-text-title"><a href="<?php echo $detail.'/'.$row_promo['prov'].'/'.urlencode($row_promo['city']).'/'.cleanUrl($row_promo['name']).'/'.$row_promo['id_pg']; ?>">Spotlight<span class="hidden-xs hidden-sm"> Apartment</span></a></div>
            <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress" class="tile-text-thin">In <span itemprop="addressLocality"><?php echo $row_promo['city']; ?></span>, <span itemprop="addressRegion"><?php echo $row_promo['prov']; ?></span>, Canada</div>
        </div>
        <div class="tile-text-block">
        <div class="tile-text-thin">Priced From: <?php echo $row_promo['rent']; ?>, Bedrooms: <?php echo $row_promo['beds']; ?></div>
        <div itemprop="name" class="tile-text-subtitle" title="<?php echo $row_promo['name']; ?>"><?php echo $row_promo['name']; ?></div>
        <div class="tile-text-thin">Page ID: <?php echo $row_promo['id_pg']; ?>, Posted: <?php echo $row_promo['date']; ?></div>
        </div>
        <div class="cta">
        	<?php if($row_promo['url']){ ?><a href="<?php echo $row_promo['url']; ?>" target="_blank" class="btn btn-inverse tile-btn-secondary"><div class="fui-eye"></div> View Website</a><?php } ?>
            <?php if($row_promo['phone1'] != 0){ ?><div class="btn btn-inverse tile-btn-secondary"><div class="fui-chat"></div>  (<?php echo $row_promo['phone1'];?>) <?php echo $row_promo['phone2'];?>-<?php echo $row_promo['phone3']; ?></div><?php } ?>
            <a href="#myModal" role="button" class="btn btn-primary avail tile-btn-primary" data-toggle="modal" data-prop="<?php echo $row_promo['id_pg']; ?>"><div class="fui-mail"></div> Check Availability</a>
        
        </div>
        <a href="<?php echo $detail.'/'.$row_promo['prov'].'/'.urlencode($row_promo['city']).'/'.cleanUrl($row_promo['name']).'/'.$row_promo['id_pg']; ?>"><div class="tile-more-stripe">View <span class="hidden-xs hidden-sm">More Details On This </span>Apartment</div></a>
      </div>
  </div>
  <div class="col-xs-4"><div class="tile-img-feat"><a class="tile-feat" href="<?php echo $detail.'/'.$row_promo['prov'].'/'.urlencode($row_promo['city']).'/'.cleanUrl($row_promo['name']).'/'.$row_promo['id_pg']; ?>">
    <?php
        if ($row_promo['photo']!=NULL){
		?>
    <img src="/upload/server/php/files/<?php echo $row_promo['id_pg'] ?>/slide/<?php echo $row_promo['photo']; ?>" itemprop="image" width="390" height="390" class="img-rounded img-responsive" style="border:1px solid #eff0f2;" alt="<?php echo $row_promo['name']; ?>">
    <?php
		}else{
		?>
    <img src="http://placehold.it/390x390&text=Photos%20Coming%20Soon" itemprop="image" width="390" height="390" class="img-rounded img-responsive" style="border:1px solid #eff0f2;" alt="Photos Coming Soon">
    <?php
		}
		?>
    </a></div></div>
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
$(".avail").click(function() {
  $("#myModal").find(".alert").hide();
  var id_prop = $(this).attr("data-prop");
  $("input[name=prop]").val(id_prop);
});
$("#seller").click(function() {
    var url = "/_inc/form_contact_seller.php";
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
</body></html>