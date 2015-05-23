<?php
// SHOW LIST/MAP AFTER REDIRECT
function get_address($get_search, $get_prov, $get_city, $get_options){
	global $radius_default, $radius;
	$bed = $ba = $price = $search_feat = $washfacil = $washunit = $washconn = $garage = $covered = $pBed = NULL;
	
	//OPTIONS - GET VARS
	$options = explode('-', urldecode($get_options));
	$options = mysqlEscape(array_filter($options));
	$search_feat = array();
	foreach ($options as $value) {
		switch ($value) {
		// BASIC SEARCH
		case preg_match("/^((studio)?([0-9]{1})?){1,6} bed(?: plus den)?$/", $value, $matches) != 0:
			$bed = substr($value, 0, strpos($value,'bed'));
			if (preg_match("/plus den$/", $value, $matches)){
				$bed .= substr($value, strpos($value,'plus den'),8 );
			}
			$pBed = $value;
			break;
		case preg_match("/^([0-9]{1}) ba$/", $value, $matches) != 0:
			$ba = substr($value, 0, strpos($value,'ba'));
			$pBa = $value;
			break;
		case preg_match("/^([0-9]+)to([0-9]+)$/", $value, $matches) != 0:
			$price = $pPrice = $value;
			break;
		case preg_match("/^([0-9]+)km$/", $value, $matches) != 0:
			$dist = substr($value, 0, strpos($value,'km'));
			$dist = substr($dist, 0, 3);
			$pDist = $value;
			break;
		// INTERIOR FEATURES
		case $value == 'air conditioning':
			$search_feat['cond'] = $value;
			break;
		case $value == 'balcony':
			$search_feat['balcony'] = $value;
			break;
		case $value == 'ceiling fan':
			$search_feat['ceiling'] = $value;
			break;
		case $value == 'extra storage':
			$search_feat['storage'] = $value;
			break;
		case $value == 'fireplace':
			$search_feat['fireplace'] = $value;
			break;
		case $value == 'garden tub':
			$search_feat['tub'] = $value;
			break;
		case $value == 'hardwood flooring':
			$search_feat['hardwood'] = $value;
			break;
		case $value == 'island kitchen':
			$search_feat['island'] = $value;
			break;
		case $value == 'new or renovated interior':
			$search_feat['new'] = $value;
			break;
		case $value == 'oversized closets':
			$search_feat['closet'] = $value;
			break;
		case $value == 'view':
			$search_feat['view'] = $value;
			break;
		case $value == 'floor to ceiling windows':
			$search_feat['windows'] = $value;
			break;
		// APPLIANCES
		case $value == 'dishwasher':
			$search_feat['dish'] = $value;
			break;
		case $value == 'gas range':
			$search_feat['gasrange'] = $value;
			break;
		case $value == 'microwave':
			$search_feat['microwave'] = $value;
			break;
		case $value == 'stainless steel appliances':
			$search_feat['stainless'] = $value;
			break;
		// TRANSPORTATION
		case $value == 'campus shuttle':
			$search_feat['campus'] = $value;
			break;
		case $value == 'public transportation':
			$search_feat['pubtran'] = $value;
			break;
		case $value == 'university shuttle service':
			$search_feat['unishuttle'] = $value;
			break;
		// TV & INTERNET
		case $value == 'cable ready':
			$search_feat['cableready'] = $value;
			break;
		case $value == 'high speed internet access':
			$search_feat['hispeed'] = $value;
			break;
		case $value == 'internet included':
			$search_feat['netincluded'] = $value;
			break;
		case $value == 'wireless internet access':
			$search_feat['wireless'] = $value;
			break;
		case $value == 'internet lounge':
			$search_feat['intlounge'] = $value;
			break;
		// HEALTH / OUTDOOR
		case $value == 'swimming pool':
			$search_feat['pool'] = $value;
			break;
		case $value == 'fitness center':
			$search_feat['fitness'] = $value;
			break;
		case $value == 'park':
			$search_feat['park'] = $value;
			break;
		case $value == 'playground':
			$search_feat['playground'] = $value;
			break;
		case $value == 'rooftop patio':
			$search_feat['rooftop'] = $value;
			break;
		case $value == 'whirlpool':
			$search_feat['whirlpool'] = $value;
			break;
		case $value == 'sauna':
			$search_feat['sauna'] = $value;
			break;
		case $value == 'bbq':
			$search_feat['bbq'] = $value;
			break;
		case $value == 'tennis court':
			$search_feat['tennis'] = $value;
			break;
		case $value == 'basketball court':
			$search_feat['basket'] = $value;
			break;
		case $value == 'trail, bike, hike, jog':
			$search_feat['trail'] = $value;
			break;
		// LAUNDRY
		case $value == 'laundry facility':
			$search_feat['washfacil'] = $value;
			break;
		case $value == 'washer and dryer in unit':
			$search_feat['washunit'] = $value;
			break;
		case $value == 'washer and dryer connections':
			$search_feat['washconn'] = $value;
			break;
		// PARKING / SECURITY
		case $value == 'free parking':
			$search_feat['freepark'] = $value;
			break;
		case $value == 'visitor parking':
			$search_feat['visitpark'] = $value;
			break;
		case $value == 'covered parking':
			$search_feat['covered'] = $value;
			break;
		case $value == 'garage':
			$search_feat['garage'] = $value;
			break;
		case $value == 'full concierge service':
			$search_feat['concierge'] = $value;
			break;
		case $value == 'alarm':
			$search_feat['alarm'] = $value;
			break;
		// LEASE OPTIONS	
		case $value == 'accepts credit cards':
			$search_feat['acceptscredit'] = $value;
			break;
		case $value == 'accepts electronic payments':
			$search_feat['acceptselectron'] = $value;
			break;
		case $value == 'all paid utilities':
			$search_feat['paidutil'] = $value;
			break;
		case $value == 'corporate billing available':
			$search_feat['corpbill'] = $value;
			break;
		case $value == 'individual leases':
			$search_feat['indivlease'] = $value;
			break;
		case $value == 'short term available':
			$search_feat['shortterm'] = $value;
			break;
		case $value == 'some paid utilities':
			$search_feat['someutil'] = $value;
			break;
		case $value == 'sublets allowed':
			$search_feat['sublet'] = $value;
			break;
		case $value == 'subsidies':
			$search_feat['subsidies'] = $value;
			break;
		// PETS
		case $value == 'pets allowed':
			$search_feat['petsallow'] = $value;
			break;
		case $value == 'pet park':
			$search_feat['petpark'] = $value;
			break;
		// ADDITIONAL AMENETIES
		case $value == 'recreation room':
			$search_feat['recroom'] = $value;
			break;
		case $value == 'emergency maintenance':
			$search_feat['emergmain'] = $value;
			break;
		case $value == 'theatre':
			$search_feat['theatre'] = $value;
			break;
		case $value == 'furnished apartments':
			$search_feat['furnish'] = $value;
			break;
		case $value == 'business center':
			$search_feat['buscent'] = $value;
			break;
		case $value == 'conference room':
			$search_feat['confroom'] = $value;
			break;
		case $value == 'disability access':
			$search_feat['disabil'] = $value;
			break;
		case $value == 'elevator':
			$search_feat['elevat'] = $value;
			break;
		case $value == 'green community':
			$search_feat['green'] = $value;
			break;
		case $value == 'housekeeping available':
			$search_feat['housekeep'] = $value;
			break;
		case $value == 'smoke free':
			$search_feat['smokefree'] = $value;
			break;
		// SENIOR
		case $value == 'assisted living':
			$search_feat['assist'] = $value;
			break;
		case $value == 'independent living':
			$search_feat['indep'] = $value;
			break;
		}
	}
	
	
	// DEFAULT VALUES
	$search = (isset($get_search)) ? $get_search : NULL;
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
	}else if ( isset($get_city) && isset($get_prov) && citySearch($get_city,$get_prov) != NULL ){
	//If City/Region Search
		list($lat, $lng, $search, $type, $city, $region, $prov, $provLng, $sub) = citySearch($get_city,$get_prov);
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
	$display_address = (isset($get_search)) ?  $get_search : urldecode($level[3]).', '.$level[2];
	
	//CONDITIONAL QUERIES
	$sql_feat = $sql_price = $wash = $park = "";
	$sql_propid = ($search_type == "propid") ? "WHERE p.id_pg = '$search'" : "";
	$sql_propname = ($search_type == "propname") ? "WHERE p.name LIKE '%$search%'" : "";
	
	// BASIC SEARCH
	$sql_beds = (isset($bed)) ? "AND u.beds = '$bed'" : "";
	$sql_bath = (isset($ba)) ? "AND u.ba = $ba" : "";
	(isset($price)) ? $replace = str_replace("to", " AND ", $price) : "";
	(isset($price)) ? $sql_price = "AND u.rent BETWEEN $replace" : "";
	
	// ALL FEATURES
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
	$sql_feat_where = " WHERE p.pub=1 ";
	$sql_feat_where .= (isset($washfacil) || isset($washunit) || isset($washconn)) ? " AND laund.feat = '".urldecode($washfacil)."' OR laund.feat='".urldecode($washunit)."' OR laund.feat='".urldecode($washconn)."' " : "";
	$sql_feat_where .= (isset($garage) || isset($covered)) ? "OR park.feat = '".urldecode($garage)."' OR park.feat='".urldecode($covered)."' " : "";
	return array ($lat, $lng, $sql_beds, $sql_bath, $sql_price, $sql_feat, $sql_feat_where, $city, $prov);
}
?>