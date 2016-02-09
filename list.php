<?php
$headStyles ='
<link rel="stylesheet" type="text/css" href="/assets/js/swipebox/swipebox.css" media="screen" />
<style>
.popover {
max-width: 400px;
}
</style>
';
include("form_results.php");
include("form_quicksearch.php");
include("class.walkscore.php");
if (  !isset ($_POST['submit']) ) { 
	include '_inc/paginator.class.php';
	require_once("mysqli-connect.php");
	
	//Get saved search
	$saved_search_sql = "SELECT us.email_results, us.url AS savedsearch
		FROM user_search us
		WHERE $srch_find AND url='".$conn->real_escape_string($_SERVER["REQUEST_URI"])."' AND deleted=0
		LIMIT 1";
	$saved_search_row = mysql_query_cache($saved_search_sql);
	$saved_search_rows = count($saved_search_row);
	if($saved_search_rows>0) {
		$saved_search_class = 'icon-star';
	}else{
		$saved_search_class = 'icon-star-empty';
	}
	
	$sort_options = array('name asc','name desc','rent asc','rent desc','created desc','distance asc');
	if(!isset($_GET['sort']) || !in_array(urldecode($_GET['sort']),$sort_options) ){
   		$sort = 'created DESC';
	}else{
		$sort = urldecode($_GET['sort']);
		if($sort == 'rent asc'){
			$sort = "ABS(rent) ASC"; 
		}else if($sort == 'rent desc'){
			$sort = "ABS(rent) DESC";
		}else{
			$sort = $_GET['sort'];
		}
	}
	if(!isset($sql_feat))$sql_feat="";
	if(!isset($sql_price))$sql_price="";
	// If Street OR City/Region search
	if (  $search_type == "street" || $search_type == "city" ) { 
		//Get row count
		$check = "SELECT p.*, u.*,
		( 6371 * acos( cos( radians($lat) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians($lng) ) + sin( radians($lat) ) * sin( radians( lat ) ) ) ) AS distance 
		FROM (properties p) 
		INNER JOIN prop_units u ON (p.id_pg = u.id_prop $sql_beds $sql_bath $sql_price) 
		$sql_feat 
		$sql_feat_where 
		GROUP BY p.id_pg 
		HAVING distance < $radius";
		$check = mysql_query_cache($check);
		$totalrows = count($check);
		//Need to get all information on properties.. but only show ones that contain 2 beds, 2 baths, pets allowed
		// Should I query in first query to grab the property ID.. then query the property IDS on the second one? I think that could work!
	
		//Start Pagination
		$pages = new Paginator;  
		$pages->items_total = $totalrows;
		$pages->mid_range = 7;
		$pages->paginate();
		
		// Show list of properties
		//INNER JOIN ( SELECT MAX(rent) AS maxrent FROM prop_units WHERE 1 = 1 ) AS umax 
		//Select the first photo in Left Join, then second left join finds photo on first photo of prop
		$sql = "SELECT p.*, c.photo, uf.id_prop AS star, STR_TO_DATE(date, '%M %d %Y') as latest, 
		IF(COUNT( DISTINCT u.rent ) > 1,CONCAT('$', MIN(u.rent), ' - $', MAX(u.rent)),CONCAT('$', u.rent)) AS rent, 
		IF(COUNT( DISTINCT u.beds ) > 1,CONCAT(MIN(u.beds), ' - ', MAX(u.beds)),u.beds) AS beds, 
		( 6371 * acos( cos( radians($lat) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians($lng) ) + sin( radians($lat) ) * sin( radians( lat ) ) ) ) AS distance 
		FROM properties p 
		INNER JOIN ( SELECT MAX(rent) AS maxrent FROM prop_units ) AS umax
		INNER JOIN prop_units u ON (p.id_pg = u.id_prop $sql_beds $sql_bath $sql_price) 
		$sql_feat 
		LEFT JOIN prop_photos c ON p.id_pg = c.id_prop AND c.p_order = 1 
		LEFT JOIN user_fav uf ON p.id_pg = uf.id_prop AND $fav_find
		$sql_feat_where 
		GROUP BY p.id_pg 
		HAVING distance < $radius 
		ORDER BY (CASE WHEN p.city = '".$city."' THEN 1 ELSE 0 END ) DESC, $sort
		$pages->limit";
		$result = mysql_query_cache($sql);
		
		/*
		LEFT JOIN (SELECT id_prop, photo, MIN(p_order) AS first FROM prop_photos GROUP BY id_prop) AS cc 
			ON p.id_pg = cc.id_prop
		 AND c.p_order = cc.first
		*/
		
		/* GET PROMOTED ADS */
		$select_promos ="SELECT pp.*, c.photo, uf.id_prop AS star, 
		IF(COUNT( DISTINCT u.rent ) > 1,CONCAT('$', MIN(u.rent), ' - $', MAX(u.rent)),CONCAT('$', u.rent)) AS rent, 
		IF(COUNT( DISTINCT u.beds ) > 1,CONCAT(MIN(u.beds), ' - ', MAX(u.beds)),u.beds) AS beds, 
		( 6371 * acos( cos( radians($lat) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians($lng) ) + sin( radians($lat) ) * sin( radians( lat ) ) ) ) AS distance 
		FROM prop_promote p 
		INNER JOIN properties pp ON p.id_prop = pp.id_pg 
		INNER JOIN prop_units u ON p.id_prop = u.id_prop 
		LEFT JOIN prop_photos c ON pp.id_pg = c.id_prop AND c.p_order = 1 
		LEFT JOIN user_fav uf ON p.id_prop = uf.id_prop AND $fav_find 
		WHERE p.expired = '0000-00-00 00:00:00' AND payer_id IS NOT NULL AND pp.pub = 1 AND sku = 'A0012' 
		GROUP BY p.id_prop 
		HAVING distance < 55 
		ORDER BY RAND() 
		LIMIT 1";
		/*
		LEFT JOIN (SELECT id_prop, photo, MIN(p_order) AS first FROM prop_photos GROUP BY id_prop) AS cc 
			ON pp.id_pg = cc.id_prop
		 AND c.p_order = cc.first
			*/
		//echo "<!--".$select_promos."-->";
		$promote = mysql_query_cache($select_promos);
		$promote_rows = count($promote);
	// If Property ID
	}else if ($search_type == "propid"){		
		//Get row count
		$check = "SELECT p.*, u.* 
		FROM properties p 
		INNER JOIN prop_units u ON p.id_pg = u.id_prop 
		INNER JOIN prop_feat a on p.id_pg = a.id_prop 
		INNER JOIN prop_feat b on p.id_pg = b.id_prop 
		$sql_propid 
		GROUP BY p.id_pg";
		$check = mysql_query_cache($check);
		$totalrows = count($check);
	
		//Start Pagination
		$pages = new Paginator;  
		$pages->items_total = $totalrows;
		$pages->mid_range = 7;
		$pages->limit = 2;
		$pages->paginate();
		
		$sql = "SELECT p.*, c.photo, uf.id_prop AS star, STR_TO_DATE(date, '%M %d %Y') as latest, 
		IF(COUNT( DISTINCT rent ) > 1,CONCAT('$', MIN(rent), ' - $', MAX(rent)),CONCAT('$', rent)) as rent, 
		IF(COUNT( DISTINCT beds ) > 1,CONCAT(MIN(beds), ' - ', MAX(beds)),beds) as beds 
		FROM properties p 
		INNER JOIN ( SELECT MAX(rent) AS maxrent FROM prop_units ) AS umax 
		INNER JOIN prop_units u ON p.id_pg = u.id_prop 
		INNER JOIN prop_feat a ON p.id_pg = a.id_prop 
		INNER JOIN prop_feat b ON p.id_pg = b.id_prop 
		LEFT JOIN (SELECT id_prop, photo, MIN(p_order) AS first FROM prop_photos GROUP BY id_prop) AS cc 
			ON p.id_pg = cc.id_prop
		LEFT JOIN prop_photos c ON p.id_pg = c.id_prop AND c.p_order = cc.first 
		LEFT JOIN user_fav uf ON p.id_pg = uf.id_prop  AND $fav_find
		$sql_propid 
		GROUP BY p.id_pg 
		ORDER BY c.id ASC 
		$pages->limit";
		$result = mysql_query_cache($sql);
	// If Property Name
	}else if ($search_type == "propname"){		
		//Get row count
		$check = "SELECT p.*, u.* 
		FROM properties p 
		INNER JOIN prop_units u ON p.id_pg = u.id_prop 
		INNER JOIN prop_feat a on p.id_pg = a.id_prop 
		INNER JOIN prop_feat b on p.id_pg = b.id_prop 
		$sql_propname 
		GROUP BY p.id_pg";
		$check = mysql_query_cache($check);
		$totalrows = count($check);
	
		//Start Pagination
		$pages = new Paginator;  
		$pages->items_total = $totalrows;
		$pages->mid_range = 7;
		$pages->limit = 2;
		$pages->paginate();
		
		$sql = "SELECT p.*, c.photo, uf.id_prop AS star, STR_TO_DATE(date, '%M %d %Y') as latest, 
		IF(COUNT( DISTINCT rent ) > 1,CONCAT('$', MIN(rent), ' - $', MAX(rent)),CONCAT('$', rent)) as rent, 
		IF(COUNT( DISTINCT beds ) > 1,CONCAT(MIN(beds), ' - ', MAX(beds)),beds) as beds 
		FROM properties p 
		INNER JOIN ( SELECT MAX(rent) AS maxrent FROM prop_units ) AS umax 
		INNER JOIN prop_units u ON p.id_pg = u.id_prop 
		INNER JOIN prop_feat a ON p.id_pg = a.id_prop 
		INNER JOIN prop_feat b ON p.id_pg = b.id_prop 
		LEFT JOIN (SELECT id_prop, photo, MIN(p_order) AS first FROM prop_photos GROUP BY id_prop) AS cc 
			ON p.id_pg = cc.id_prop
		LEFT JOIN prop_photos c ON p.id_pg = c.id_prop AND c.p_order = cc.first 
		LEFT JOIN user_fav uf ON p.id_pg = uf.id_prop  AND $fav_find
		$sql_propname 
		GROUP BY p.id_pg 
		ORDER BY c.id ASC 
		$pages->limit";
		$result = mysql_query_cache($sql);
	//If Search is NULL and city is not a city, redirect to Province Page
	}else if ($search_type == "nosearch"){
		$_SESSION["listredirect"]=$_SERVER['REQUEST_URI'];
		header('Location: '.$list);
	}
}
include("header.php");
$maxrent = "10000";
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
?>
</ul>
<div class="row">
  <div class="col-lg-9">
    <?php
if ( $totalrows < 5 && $totalrows != 0 ) {
	//Only show "too few results" message if they aren't looking up one property.
	if (  $search_type == "street" || $search_type == "city" ) {
	?>
    <div class="alert alert-info">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      <strong>Too Few Results?</strong> Try increasing the distance in the Quick Search form to the right. </div>
    <?php
	}
}
//If user was redirected because owner removed ad
//Show message about listing gone, search more ads in your city
if (isset($_SESSION["listremoved"]) && $_SESSION["listremoved"]!=""){
	echo $_SESSION["listremoved"];
	$_SESSION["listremoved"]="";
}
$within="";
if($radius!=0.01){
	$within = "within ".$radius." kilometres";
}
	  ?>
    <h1>Apartments near "<?php echo $display_address; ?>" <span class="badge"><?php echo $totalrows; ?> <?php echo $within; ?></span></h1>
    <div class="tile-text tile-text-thin" style="padding:0;line-height:12px;"><i class="save-search <?php echo $saved_search_class; ?>" data-prop="<?php echo $_SERVER["REQUEST_URI"];?>" data-toggle="popover"></i> Save Search &amp; Email New Results</div>
  </div>
  <div class="col-lg-3"><a href="<?php echo $map.$mapparam; ?>" class="btn btn-block btn-hg btn-primary">Map view</a> </div>
</div>
<div class="row">
<?php
if (!$result) {
	  ?>
<div class="col-md-9">
  <p>Sorry, we couldn't find any apartments that match your search criteria.</p>
  <p>Try removing a few of your search filters or increase the distance radius.</p>
  <?php
	}else {
	?>
  <div class="col-md-2" style="padding-top:20px;">
    <select name="herolist" id="sort-select" class="select-block">
      <option value='latest+desc' <?php if(!isset($_GET['sort']) || $_GET['sort']=='latest desc'){echo "selected";} ?>>Date (New to Old)</option>
      <?php if (  $search_type == "street" || $search_type == "city" ) { ?>
      <option value='distance+asc' <?php if($_GET['sort']=='distance asc'){echo "selected";} ?>>Distance Nearest</option>
      <?php } ?>
      <option value='rent+asc' <?php if($_GET['sort']=='rent asc'){echo "selected";} ?>>Price (Low to High)</option>
      <option value='rent+desc' <?php if($_GET['sort']=='rent desc'){echo "selected";} ?>>Price (High to Low)</option>
      <option value='name+asc' <?php if($_GET['sort']=='name asc'){echo "selected";} ?>>Name (A to Z)</option>
      <option value='name+desc' <?php if($_GET['sort']=='name desc'){echo "selected";} ?>>Name (Z to A)</option>
    </select>
  </div>
  <div class="col-md-7"> <span class="pagination pull-right">
    <ul>
      <?php echo $pages->display_pages() ?>
    </ul>
    </span> </div>
  <div class="col-lg-3">&nbsp; </div>
</div>
<div class="row" style="position:relative;">
  <div class="col-lg-9 list">
    <?php
	$lightbox="";
	
	/* START PROMOS */
	if(isset($promote_rows) && $promote_rows > 0){
		?>
    <div class="media-top-promo">Featured Apartment</div>
    <?php
    }
	$promo = $promote;
	foreach ($promo as $k => $v) {
		$nomagnify = NULL;
		$star_class = 'icon-star-empty';
		if ($promo[$k]['star']) $star_class = 'icon-star';
		$maxrent = (isset($promo[$k]['maxrent'])) ? $promo[$k]['maxrent'] : "10000";
		
		//Lightbox
		$lightbox .= "$('#pic".$promo[$k]['id_pg']."').click(function(e){
		e.preventDefault();
		$.swipebox([
		\n";
		foreach(glob($root.'/upload/server/php/files/'.$promo[$k]['id_pg'].'/slide/*.*') as $filename){
			if (getimagesize($filename) === false) {
				$filename = "http://placehold.it/115x115&text=Coming%20Soon";
			}else{
				$filename = str_replace($root, "", $filename);
			}
			$lightbox .= "			{href:'".$filename."', title:'".addslashes(trim($promo[$k]['name']))."'}, \n";
		}
		$lightbox .= "		], { initialIndexOnArray: 0, hideBarsDelay : 10000 });
	});\n\n";
		
		if ($promo[$k]['photo']){
			$image_url = "http://$_SERVER[HTTP_HOST]/upload/server/php/files/".$promo[$k]['id_pg']."/thumbnail/".$promo[$k]['photo'];
			if (getimagesize($image_url) === false) {
				$photo = "http://placehold.it/115x115&text=Coming%20Soon";
			}else{
				$photo = $image_url;
			}
		}else{
			$photo = "http://placehold.it/115x115&text=Coming%20Soon";
			$nomagnify = true;
		}
		$walk = "http://www.walkscore.com/score/".str_replace(" ", "-", $promo[$k]['address'])."-".str_replace(" ", "-", $promo[$k]['post'])."/lat=".$promo[$k]['lat']."/lng=".$promo[$k]['lng'];
		?>
    <div class="media promo" itemscope itemtype="https://schema.org/LocalBusiness"> 
      <!-- Google geo loc -->
      <div itemprop="geo" itemscope itemtype="http://schema.org/GeoCoordinates">
        <meta itemprop="latitude" content="<?php echo $promo[$k]['lat']; ?>">
        <meta itemprop="longitude" content="<?php echo $promo[$k]['lng']; ?>">
      </div>
      <div class="pull-left"> <a href="<?php echo $detail; ?>/<?php echo urlencode($promo[$k]['prov']); ?>/<?php echo urlencode($promo[$k]['city']); ?>/<?php echo cleanUrl($promo[$k]['name']); ?>/<?php echo $promo[$k]['id_pg']; ?>"><img class="media-object img-thumbnail javascript" src="/assets/img/grey.gif" data-original="<?php echo $photo; ?>" width="115" height="115" alt="<?php echo $promo[$k]['photo']; ?>">
        <noscript>
        <img class="media-object img-thumbnail" src="<?php echo $photo; ?>" width="115" height="115" itemprop="image">
        </noscript>
        </a> <span class="badge walkscore" style="margin-top:9px;"><a href="<?php echo $walk; ?>" target="_blank">See Walk Score</a></span>
        <?php if(!isset($nomagnify)){?>
        <a id="pic<?php echo $promo[$k]['id_pg'] ?>" class="ico-search" href="javascript:;"></a>
        <?php } ?>
      </div>
      <div class="media-body">
        <div class="row">
          <div class="col-md-7 maintext">
            <h4 class="media-heading" itemprop="name"><a href="<?php echo $detail; ?>/<?php echo urlencode($promo[$k]['prov']); ?>/<?php echo urlencode($promo[$k]['city']); ?>/<?php echo cleanUrl($promo[$k]['name']); ?>/<?php echo $promo[$k]['id_pg']; ?>"><?php echo $promo[$k]['name']; ?></a> <i class="<?php echo $star_class; ?>" data-prop="<?php echo $promo[$k]['id_pg']; ?>"></i> <span class="badge">Add to Faves</span></h4>
            <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress"> <span itemprop="streetAddress"><?php echo $promo[$k]['address']; if($promo[$k]['address2']){echo ', '.$promo[$k]['address2'];} ?></span>
              <?php if($promo[$k]['address']||$promo[$k]['address2']){echo ',';} ?>
              <span itemprop="addressLocality"><?php echo $promo[$k]['city']; ?></span>, <span itemprop="addressRegion"><?php echo $promo[$k]['prov']; ?></span>, <?php echo $promo[$k]['cntry']; ?>
              <?php if($promo[$k]['post']){echo ',';} ?>
              <span itemprop="postalCode"><?php echo $promo[$k]['post']; ?></span> <?php echo '('.number_format($promo[$k]['distance'], 2).' km)'; ?> </div>
            Price: <?php echo $promo[$k]['rent']; ?>, Beds: <?php echo $promo[$k]['beds']; ?> <br />
            Page ID: <?php echo $promo[$k]['id_pg']; ?>, Posted: <?php echo $promo[$k]['date']; ?> </div>
          <div class="col-md-3 text-right"> <a href="#myModal" role="button" class="btn btn-primary btn-block avail" data-toggle="modal" data-prop="<?php echo $promo[$k]['id_pg']; ?>">
            <div class="fui-mail"></div>
            Check Availability</a>
            <?php 
			if($promo[$k]['phone1'] != 0) echo '<a href="tel://1-'.$promo[$k]['phone1'].'-'.$promo[$k]['phone2'].'-'.$promo[$k]['phone3'].'" class="btn btn-block btn-inverse"><div class="fui-chat"></div> ('.$promo[$k]['phone1'].') '.$promo[$k]['phone2'].'-'.$promo[$k]['phone3'].'</a>'; ?>
            <?php if($promo[$k]['url']) echo '<a href="'.$promo[$k]['url'].'" target="_blank" class="btn btn-block btn-inverse"><div class="fui-eye"></div> View Website</a>'; ?>
          </div>
        </div>
      </div>
    </div>
    <?php
	}
	/* END PROMOS */
	
	/* START FREE RENTALS */
	$row = $result;
	foreach ($row as $k => $v) {
	//while ($row = @mysql_fetch_assoc($result)){
		$nomagnify = NULL;
		$star_class = 'icon-star-empty';
		if ($row[$k]['star']) $star_class = 'icon-star';
		$maxrent = (isset($row[$k]['maxrent'])) ? $row[$k]['maxrent'] : "10000";
		
		//Lightbox
		$lightbox .= "$('#pic".$row[$k]['id_pg']."').click(function(e){
		e.preventDefault();
		$.swipebox([
		\n";
		foreach(glob($root.'/upload/server/php/files/'.$row[$k]['id_pg'].'/slide/*.*') as $filename){
			if (getimagesize($filename) === false) {
				$filename = "http://placehold.it/115x115&text=Coming%20Soon";
			}else{
				$filename = str_replace($root, "", $filename);
			}
			$lightbox .= "			{href:'".$filename."', title:'".addslashes(trim($row[$k]['name']))."'}, \n";
		}
		$lightbox .= "		], { initialIndexOnArray: 0, showCount: true });
	});\n\n";
		
		if ($row[$k]['photo']){
			$image_url = "http://$_SERVER[HTTP_HOST]/upload/server/php/files/".$row[$k]['id_pg']."/thumbnail/".$row[$k]['photo'];
			if (getimagesize($image_url) === false) {
				$photo = "http://placehold.it/115x115&text=Coming%20Soon";
			}else{
				$photo = $image_url;
			}
		}else{
			$photo = "http://placehold.it/115x115&text=Coming%20Soon";
			$nomagnify = true;
		}
		$walk = "http://www.walkscore.com/score/".str_replace(" ", "-", $row[$k]['address'])."-".str_replace(" ", "-", $row[$k]['post'])."/lat=".$row[$k]['lat']."/lng=".$row[$k]['lng'];
		?>
    <div class="media" itemscope itemtype="https://schema.org/LocalBusiness"> 
      <!-- Google geo loc -->
      <div itemprop="geo" itemscope itemtype="http://schema.org/GeoCoordinates">
        <meta itemprop="latitude" content="<?php echo $row[$k]['lat']; ?>">
        <meta itemprop="longitude" content="<?php echo $row[$k]['lng']; ?>">
      </div>
      <div class="pull-left"> <a href="<?php echo $detail; ?>/<?php echo urlencode($row[$k]['prov']); ?>/<?php echo urlencode($row[$k]['city']); ?>/<?php echo cleanUrl($row[$k]['name']); ?>/<?php echo $row[$k]['id_pg']; ?>"><img class="media-object img-thumbnail javascript" src="/assets/img/grey.gif" data-original="<?php echo $photo; ?>" width="115" height="115" alt="<?php echo $row[$k]['photo']; ?>">
        <noscript>
        <img class="media-object img-thumbnail" src="<?php echo $photo; ?>" width="115" height="115" itemprop="image">
        </noscript>
        </a> <span class="badge walkscore" style="margin-top:9px;"><a href="<?php echo $walk; ?>" target="_blank">See Walk Score</a></span>
        <?php if(!isset($nomagnify)){?>
        <a id="pic<?php echo $row[$k]['id_pg'] ?>" class="ico-search" href="javascript:;"></a>
        <?php } ?>
      </div>
      <div class="media-body">
        <div class="row">
          <div class="col-md-7 maintext">
            <h4 class="media-heading" itemprop="name"><a href="<?php echo $detail; ?>/<?php echo urlencode($row[$k]['prov']); ?>/<?php echo urlencode($row[$k]['city']); ?>/<?php echo cleanUrl($row[$k]['name']); ?>/<?php echo $row[$k]['id_pg']; ?>"><?php echo $row[$k]['name']; ?></a> <i class="<?php echo $star_class; ?>" data-prop="<?php echo $row[$k]['id_pg']; ?>"></i> <span class="badge">Add to Faves</span></h4>
            <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress"> <span itemprop="streetAddress"><?php echo $row[$k]['address']; if($row[$k]['address2']){echo ', '.$row[$k]['address2'];} ?></span>
              <?php if($row[$k]['address']||$row[$k]['address2']){echo ',';} ?>
              <span itemprop="addressLocality"><?php echo $row[$k]['city']; ?></span>, <span itemprop="addressRegion"><?php echo $row[$k]['prov']; ?></span>, <?php echo $row[$k]['cntry']; ?>
              <?php if($row[$k]['post']){echo ',';} ?>
              <span itemprop="postalCode"><?php echo $row[$k]['post']; ?></span> <?php echo '('.number_format($row[$k]['distance'], 2).' km)'; ?> </div>
            Price: <?php echo $row[$k]['rent']; ?>, Beds: <?php echo $row[$k]['beds']; ?> <br />
            Page ID: <?php echo $row[$k]['id_pg']; ?>, Posted: <?php echo $row[$k]['date']; ?> </div>
          <div class="col-md-3 text-right"> <a href="#myModal" role="button" class="btn btn-block btn-primary avail" data-toggle="modal" data-prop="<?php echo $row[$k]['id_pg']; ?>">
            <div class="fui-mail"></div>
            Check Availability</a>
            <?php 
			if($row[$k]['phone1'] != 0) echo '<a href="tel://1-'.$row[$k]['phone1'].'-'.$row[$k]['phone2'].'-'.$row[$k]['phone3'].'" class="btn btn-block btn-inverse"><div class="fui-chat"></div> ('.$row[$k]['phone1'].') '.$row[$k]['phone2'].'-'.$row[$k]['phone3'].'</a>'; ?>
            <?php if($row[$k]['url']) echo '<a href="'.$row[$k]['url'].'" target="_blank" class="btn btn-block btn-inverse"><div class="fui-eye"></div> View Website</a>'; ?>
          </div>
        </div>
      </div>
    </div>
    <?php
	}
}
?>
  </div>
  <div class="col-lg-3" style="position:static;">
    <div id="sidebar">
      <?php include("pg_quicksearch.php"); ?>
      <div style="margin-top:35px;">
        <select id="locationSelect" class="visible-sm form-control">
        </select>
        <div id="map" style="height: 200px;"> </div>
        <div style="margin-top:5px;"> <small><span class="badge"><span id="resultsnum"></span></span> <?php echo $within; ?> <a href="<?php echo $map.$mapparam; ?>" class="pull-right">View Map</a></small> </div>
      </div>
      <?php include("pg_feedback.php"); ?>
      <br /><br />
      <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <!-- Responsive My Best -->
    <ins class="adsbygoogle"
         style="display:block"
         data-ad-client="ca-pub-0129866261545740"
         data-ad-slot="1798795409"
         data-ad-format="auto"></ins>
    <script>
    (adsbygoogle = window.adsbygoogle || []).push({});
    </script>
    </div>
  </div>
</div>
<?php
if ($result) {
	  ?>
<div class="row">
  <div class="col-md-2" style="padding-top:20px;">
    <select name="herolist" id="sort-select" class="select-block">
      <option value='latest+desc' <?php if(!isset($_GET['sort']) || $_GET['sort']=='latest desc'){echo "selected";} ?>>Date (New to Old)</option>
      <option value='distance+asc' <?php if($_GET['sort']=='distance asc'){echo "selected";} ?>>Distance Nearest</option>
      <option value='rent+asc' <?php if($_GET['sort']=='rent asc'){echo "selected";} ?>>Price (Low to High)</option>
      <option value='rent+desc' <?php if($_GET['sort']=='rent desc'){echo "selected";} ?>>Price (High to Low)</option>
      <option value='name+asc' <?php if($_GET['sort']=='name asc'){echo "selected";} ?>>Name (A to Z)</option>
      <option value='name+desc' <?php if($_GET['sort']=='name desc'){echo "selected";} ?>>Name (Z to A)</option>
    </select>
  </div>
  <div class="col-md-7"> <span class="pagination pull-right">
    <ul>
      <?php echo $pages->display_pages() ?>
    </ul>
    </span> </div>
  <div class="col-lg-3">&nbsp; </div>
</div>
<?php
}
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
              <input class="form-control email required" id="cemail" type="text" name="demail" />
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12"> Your Message <span class="red">*</span> <br />
              <textarea class="form-control required" id="ccomment" name="dcomment" rows="5" minlength="10"></textarea>
              <small class="pull-right"><span class="red">*</span> Required field</small> </div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
          <button id="seller" class="btn btn-primary" type="submit" name="seller">Send</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- #myModal --> 
<a href="#" class="go-top"></a>
<?php
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
<script src="//code.jquery.com/ui/1.10.3/jquery-ui.js"></script> 
<script src="//cdn.jsdelivr.net/jquery.lazyload/1.8.4/jquery.lazyload.min.js" charset="utf-8"></script> 
<script src="/assets/js/swipebox/jquery.swipebox.min.js"></script> 
<script>
$(window).load(function() {
	<?php echo $lightbox; ?> 
	<?php echo $open;?>
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
	if( paneltext != "$0 - $3500" ){
		$( this ).parents( ".panel" ).find("small").text( paneltext ).addClass("selected");
	}else{
		$( this ).parents( ".panel" ).find("small").text( "No Preference" ).removeClass("selected");
	}
});
$(function() {
	// Lazy Load
	$("img.media-object").css("display", "block").lazyload({ 
    	effect : "fadeIn" 
	});
	// Load Slider
	$( "#slider-range" ).slider({
		range: true,
		min: 0,
		max: 3500,
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
<script>
$(".avail").click(function() {
  $("#myModal").find(".alert").hide();
  var id_prop = $(this).attr("data-prop");
  $("input[name=prop]").val(id_prop);
});
$(".support").click(function() {
  $("#feedback").find(".alert").hide();
});
$("#seller").click(function() {
    var url = "/_inc/form_contact_seller.php";
    $.ajax({
           type: "POST",
           url: url,
           data: $("#contactSeller").serialize(),
           success: function(data){
			   $("#contactSeller").find(".form_result").html(data);
			   _gaq.push(["_trackEvent", "Appointment", "Email", "List Page"]);
           }
         });
	$("#contactSeller")[0].reset();
    return false;
});
$(".walkscore a").click(function() {
	_gaq.push(["_trackEvent", "Walk Score", "Click", "List Page"]);
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
$("#sort-select").on("change", function () {
	newUrl = updateQueryStringParameter(document.location.href, "sort", $(this).val());
	document.location.href= newUrl;
});
function updateQueryStringParameter(uri, key, value) {
  var re = new RegExp("([?|&])" + key + "=.*?(&|$)", "i");
  separator = uri.indexOf("?") !== -1 ? "&" : "?";
  if (uri.match(re)) {
    return uri.replace(re, "$1" + key + "=" + value + "$2");
  }
  else {
    return uri + separator + key + "=" + value;
  }
}
// Show or hide the scroll up button
$(window).scroll(function() {
	if ($(this).scrollTop() > 200) {
		$(".go-top").fadeIn(200);
	} else {
		$(".go-top").fadeOut(200);
	}
});
// Animate the scroll to top
$(".go-top").click(function(event) {
	event.preventDefault();
	$("html, body").animate({scrollTop: 0}, 300);
})
</script> 
<script>
//Save a Search
$('[data-toggle="popover"]').popover({
	html: true,
	placement: 'bottom',
	title : 'Save &amp; Email Results'+
	'<button type="button" id="close" class="close" onclick="$(&quot;[data-toggle=popover]&quot;).popover(&quot;hide&quot;);">&times;</button>',
	content: '<div style="font-size:12px;">Sweet! You\'ve saved this search. <br><br> <form id="emailupdates" method="post"></form></div>',
	trigger: 'manual'
}).on('click', function(e) {
		var type, prop, savemsg, checked, email_results_checkbox;
		email_results_checkbox = <?php if(isset($saved_search_row["email_results"])){echo $saved_search_row["email_results"]; }else{ echo 0;} ?>;
		savemsg = "";
		checked = "";
		saveurl = $(this).attr("data-prop");
		
		$(this).popover('show');
		$('.popover').off('click mouseenter').on('click mouseenter', function(e) {
			e.stopPropagation(); // prevent event for bubbling up => will not get caught with document.onclick
		});
		
		$( this ).toggleClass("icon-star-empty icon-star");
		if($( this ).hasClass( "icon-star" )){
			type = "insert";
			_gaq.push(["_trackEvent", "Save Search", "Add", "List Page"]);
			if(email_results_checkbox==1){
				checked = "checked";
			}
			savemsg = '<div style="font-size:12px;">Sweet! You\'ve saved this search. <br><br> <form id="emailupdates" method="post"><input type="checkbox" name="email" value="1" '+checked+'> Click here to get new results emailed to you daily.</form></div>';
		}else{
			type = "delete";
			_gaq.push(["_trackEvent", "Save Search", "Remove", "List Page"]);
			savemsg = '<div style="font-size:12px;">Search removed.</div>';
		}
		$(".popover-content").empty().append(savemsg);
		savesearch(type, saveurl);
		$("#emailupdates input").change(function() {
			checkbox_emailupdates(saveurl, $(this));
		});
		e.stopPropagation();
}).on('mouseover', function(e) {
	if( $( this ).hasClass( "icon-star" ) ){
		saveurl = $(this).attr("data-prop");
		getsearch(saveurl, $(this));
	}
});
function checkbox_emailupdates(saveurl, ele, type){
			if (type === undefined) type = "insert";
			//if(typeof type === "undefined"){type = "insert";}
			<?php if(isset($_SESSION['ID_my_site'])){ ?>
			if(ele.is(":checked")) {
				_gaq.push(["_trackEvent", "Save Search", "Email Updates", "List Page"]);
				savesearch(type, saveurl, 1);
			}else{
				_gaq.push(["_trackEvent", "Save Search", "Remove Email Updates", "List Page"]);
				savesearch(type, saveurl, 0);
			}
			<?php }else{ ?>
			_gaq.push(["_trackEvent", "Save Search", "Please Log In", "List Page"]);
			window.location.href = "/admin-login?redirect="+document.URL;
			<?php } ?>
}
function getsearch(saveurl, ele){
	$.ajax({
    	type: "GET",
      	url: "/_inc/form_get_search.php",
      	data: { saveurl: saveurl, user: '<?php if(isset($_SESSION['ID_my_site'])){echo $_SESSION['ID_my_site'];} ?>', session: '<?php if(isset($_COOKIE['fav'])){echo $_COOKIE["fav"];} ?>'},
       	success: function(data){
			ele.popover('show');
			$("#emailupdates").empty().append(data);
			$("#emailupdates input").change(function() {
				checkbox_emailupdates(saveurl, $(this));
		 	});
      	}
  	});
}
function savesearch(type, saveurl, email){
	if(typeof email === "undefined"){
		email = 0;
		checkbox = false;
	}else{
		email = email;
		checkbox = true;
	}
	$.ajax({
    	type: "POST",
      	url: "/_inc/form_save_search.php",
      	data: { type: type, saveurl: saveurl, user: '<?php if(isset($_SESSION['ID_my_site'])){echo $_SESSION['ID_my_site'];} ?>', session: '<?php if(isset($_COOKIE['fav'])){echo $_COOKIE["fav"];} ?>', email: email},
       	success: function(data){
			if(checkbox==true){
				$("#emailupdates").empty().append(data);
			}
      	}
  	});
}



	
	//Save Favorite Property
	$( ".media-body .icon-star-empty, .media-body .icon-star" ).click(function(){ 
	var type, url, prop;
	url = "/_inc/form_fav.php";
	prop = $(this).attr("data-prop");
	$( this ).toggleClass("icon-star-empty icon-star");
	if($( this ).hasClass( "icon-star" )){
		type = "insert";
		_gaq.push(["_trackEvent", "Faves", "Add", "List Page"]);
	}else{
		type = "delete";
		_gaq.push(["_trackEvent", "Faves", "Remove", "List Page"]);
	}
    $.ajax({
    	type: "POST",
      	url: url,
      	data: { type: type, prop: prop, user: '<?php if(isset($_SESSION['ID_my_site'])){echo $_SESSION['ID_my_site'];} ?>', session: '<?php if(isset($_COOKIE['fav'])){echo $_COOKIE["fav"];} ?>' },
       	success: function(data){
			//alert(data);
      	}
  	});
	return false;
	});
    <?php 
	//If user viewed deleted URL - Track it as event
	if(isset($_SESSION["listremoved"]) && $_SESSION["listremoved"]!=""){
	?>
	_gaq.push(["_trackEvent", "Removed Listing", "<?php echo $city.", ".$prov;?>", "List Page"]);
	<?php
	}
	?>
    </script> 
<script src="/assets/js/page/maps.js" type="text/javascript"></script> 
<script type="text/javascript">
//Show map
var address = '<?php echo $address; ?>';
var params = '<?php echo $mapparam; ?>';
load();
if (address!=null){searchLocations(address, params);}
</script>
</body>
</html>
