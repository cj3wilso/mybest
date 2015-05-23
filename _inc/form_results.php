<?php
// SHOW LIST/MAP AFTER REDIRECT
if ( !isset($_POST['addressInput']) ) { 
	// DEFAULT VALUES
	$search = (isset($_GET["search"])) ? $_GET['search'] : NULL;
	$address = $search = $getsearch = urldecode($search);
	$radius = $radius_default;
	$sub = NULL;
	$prov = NULL;
	$area = NULL;
	
	// GET URL VALUES
	$radius = (isset($dist)) ? $dist : $radius;
	
	// DOES CLASS EXIST?
	$class_exists = class_exists('geocoder');
	
	//IF STREET ADDRESS - GEOCODE TO GET LAT/LNG
	if (preg_match("/^[0-9]+[a-z]?[ ](?:[a-z0-9, ]+)*[a-z ]+$/i", $address, $matches) && $class_exists) {
		$search_type = "street";
		if( isset($_COOKIE['geocode']) ){
			$geocode = explode("+", $_COOKIE['geocode']);
			$lat = $geocode[0];	
			$lng = $geocode[1];
			$search = $geocode[2];
			$type = $geocode[3];
			$city = $geocode[4];
			$region = $geocode[5];
			$prov = $geocode[6];
			/*
			0>43.648361 - lat
			1>-79.381493 -lng
			2>100 King Street West, Toronto, ON, Canada - search
			3>11 - type
			4>Toronto - city
			5> Toronto - region
			6>ON - prov
			7>Ontario - provLng
			print_r($_COOKIE['geocode']);
			*/
			if($city != NULL){
				$area = $city;
			}else{
				$area = $region;
			}
			$sub = '/'.$prov.'/'.urlencode($area);
		}
		if ( !isset($_COOKIE['geocode']) || $search != $getsearch ){
			setcookie ("geocode", "", time() - 3600);
			list($lat, $lng, $search, $type, $city, $region, $prov, $provLng) = geocoder::getLocationInfo($address);
			setcookie("geocode", "$lat+$lng+$search+$type+$city+$region+$prov+$provLng", time() + 3600);
			setcookie("lastSearch", $lat."+".$lng."+".$city."+".$prov, time() + 3600, "/","mybestapartments.ca");
		}
	}else if ( isset($_GET["city"]) && isset($_GET["prov"]) && citySearch($_GET["city"],$_GET["prov"]) != NULL ){
	//If City/Region Search
		list($lat, $lng, $search, $type, $city, $region, $prov, $provLng, $sub) = citySearch($_GET["city"],$_GET["prov"]);
		$area=$city;
		$sub = '/'.$prov.'/'.urlencode($area);
		$address = $area.', '.$prov;
		$search_type = "city";
		setcookie("lastSearch", $lat."+".$lng."+".$area."+".$prov, time() + 3600, "/","mybestapartments.ca");
	}else if ( isPropId($getsearch) == true ){
	//If Property ID
		$radius = 0.01;
		$address = "Canada";
		$search_type = "propid";
		setcookie ("lastSearch", "", time() - 3600);
	}else if ($getsearch != NULL){
	//If Property Name
		$radius = 0.01;
		$address = "Canada";
		$search_type = "propname";
		setcookie ("lastSearch", "", time() - 3600);
	}else{
	//Don't Query
		$search_type = "nosearch";
		setcookie ("lastSearch", "", time() - 3600);
	}
	
	//SET MAP/LIST LINKS
	//'search'=>$search,
	if ( isset($options) ){ $urloptions = array_filter($options, 'strlen'); }else{ $urloptions = array(); }
	if ( !isset($_GET['sort']) ){ $_GET['sort']=NULL; }
	if ( !isset($city) ){ $city=NULL; }
	$maparray = array_filter(array('prov'=>$prov,'city'=>$city,'search'=>$search,'sort'=>$_GET['sort'],'options'=>strtolower(implode("-", $urloptions))));
	$mapbeg = (array_filter($maparray)) ? '?' : NULL;
	$listarray = array('search'=>$search,'sort'=>$_GET['sort']);
	$listbeg = (array_filter($listarray)) ? '?' : NULL;
	$mapparam =  $mapbeg.http_build_query($maparray);
	$listparam = $sub.'/'.strtolower(urlencode(implode("-", $urloptions))).$listbeg.http_build_query($listarray);
	
	// Display Address for search pages
	if(!isset($level[2])){$level[2]="";}
	if(!isset($level[3])){$level[3]="";}
	$display_address = (isset($_GET["search"])) ?  $_GET["search"] : urldecode($level[3]).', '.$level[2];
	
	//CONDITIONAL QUERIES
	$sql_propid = ($search_type == "propid") ? "WHERE p.id_pg = '$search'" : "";
	$sql_propname = ($search_type == "propname") ? "WHERE p.name LIKE '%$search%'" : "";
	
	// BASIC SEARCH
	$sql_beds = (isset($bed)) ? "AND u.beds = '$bed'" : "";
	$sql_bath = (isset($ba)) ? "AND u.ba = $ba" : "";
	(isset($price)) ? $replace = str_replace("to", " AND ", $price) : "";
	(isset($price)) ? $sql_price = "AND u.rent BETWEEN $replace" : "";
	
	// ALL FEATURES
	$sql_feat = $wash = $park = "";
	if (!empty($search_feat)) {
		foreach ($search_feat as $key => $value){
			if(isset($washfacil) || isset($washunit) || isset($washconn)){
				$wash = " INNER JOIN prop_feat laund on p.id_pg = laund.id_prop ";
			}else if(isset($garage) || isset($covered)){
				$park = " INNER JOIN prop_feat park ON p.id_pg = park.id_prop ";
			}else{
				$sql_feat .= " INNER JOIN prop_feat $key on p.id_pg = $key.id_prop AND $key.feat = '".urldecode($value)."'";
			}
		}
	}
	$sql_feat .= $wash.$park;
	$sql_feat_where = " WHERE p.pub=1 AND p.deleted=0 ";
	$sql_feat_where .= (isset($washfacil) || isset($washunit) || isset($washconn)) ? " AND laund.feat = '".urldecode($washfacil)."' OR laund.feat='".urldecode($washunit)."' OR laund.feat='".urldecode($washconn)."' " : "";
	//if ($sql_feat_where != ""){$where_clause = " OR ";}else{$where_clause = " WHERE ";}
	$sql_feat_where .= (isset($garage) || isset($covered)) ? "OR park.feat = '".urldecode($garage)."' OR park.feat='".urldecode($covered)."' " : "";
}
?>