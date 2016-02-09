<?php
// Set the root
//Note this path seems to include the current _inc folder when using command line
//$root = realpath((getenv('DOCUMENT_ROOT') && ereg('^'.preg_quote(realpath(getenv('DOCUMENT_ROOT'))), realpath(__FILE__))) ? getenv('DOCUMENT_ROOT') : str_replace(dirname(@$_SERVER['PHP_SELF']), '', str_replace(DIRECTORY_SEPARATOR, '/', dirname(__FILE__))));
$root = realpath((getenv('DOCUMENT_ROOT') && strpos(realpath(__FILE__),preg_quote(realpath(getenv('DOCUMENT_ROOT')))) !== false) ? getenv('DOCUMENT_ROOT') : str_replace(@$_SERVER['PHP_SELF'], '', dirname(__FILE__)));
$root = str_replace('/_inc','',$root);
$expire=time()+60*60*24*60;
//echo '<!--'.$root.'-->';

/*Connect to Memcache*/
require_once("memcache.php");

if(php_sapi_name() != 'cli'){
	session_start();
	//see: http://prajapatinilesh.wordpress.com/2009/01/14/manually-set-php-session-timeout-php-session/
	//for more info
	if(!isset($_SESSION['LOG_TIME'])){$logtime =3600; }else{ $logtime =$_SESSION['LOG_TIME'];}
	ini_set('session.gc_maxlifetime', $logtime);
	session_set_cookie_params($logtime);
	header('Content-type: text/html; charset=UTF-8');
	$domain = $_SERVER['SERVER_NAME'];
	if($root == "/www/var/html/sites/dev_best"){
		error_reporting(E_ALL); ini_set('display_errors', '1');
	}
	//Find faves
	if(isset($_SESSION['ID_my_site'])){
		$fav_find = "uf.id_user = '".$_SESSION['ID_my_site']."'";
		$srch_find = "us.id_user = '".$_SESSION['ID_my_site']."'";
	}else{
		if(!isset($_COOKIE["fav"])){
			$expire=time()+60*60*24*60;
			$fav_value = uniqid('', true);
			setcookie("fav", $fav_value, $expire);
		}else{
			$fav_value = $_COOKIE["fav"];
		}
		$fav_find = "uf.id_session = '".$fav_value."'";
		$srch_find = "us.id_session = '".$fav_value."'";
	}
}

//Include geocode
include_once 'geocode.class.php';

//Project specific
$home = "/";
$list = "/rent";
$map = "/map";
$opt = "/options";
$advertise = "/add";
$about = "/about";
$contact = "/contact";
$detail = "/rent";
$upload = "/upload";
$faves = "/faves";
$radius_default = "30";
$radius_region = "60";

//Set company variables here
$company = "My Best Apartments";
$companyEmail = "info@mybestapartments.ca";
$companyPhone = "(416) 206-8985";
$corporation = "Design Essence Inc.";

//Admin pages
$adminLogin = "/admin-login";
$adminHome = "/admin";
$adminEdit = "/edit";
$adminRegister = "/register";
$adminLogout = "/logout";
$adminForgot = "/forgot";
$adminReset = "/reset";
$adminUsers = "/users";

//Get information from slashes
if(php_sapi_name() != 'cli'){
	$path = parse_url($_SERVER["REQUEST_URI"]);
	$level = explode("/", $path["path"]);
}

//OPTIONS - GET VARS
if (isset($_GET['options'])){
	$options = explode('-', $_GET['options']);
	$options = mysqlEscape($options);
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
}

// TOP SEARCH
if ( isset($_POST['addressInput']) && $_POST['addressInput'] != null ) { 
	setcookie ("geocode", "", time() - 3600);
	$_POST = mysqlEscape($_POST);
	$address = $search = trim($_POST['addressInput']);
	$search = str_replace(",", "", $search);
	// DOES CLASS EXIST?
	$class_exists = class_exists('geocoder');
	//IF STREET ADDRESS - GEOCODE TO GET LAT/LNG
	if (preg_match("/^[0-9]+[a-z]?[ ](?:[a-z0-9, ]+)*[a-z ]+$/i", $address, $matches) && $class_exists) {
		list($lat, $lng, $search, $type, $city, $region, $prov, $provLng, $post, $street) = geocoder::getLocationInfo($address);
		$area = $city;
		setcookie("geocode", "$lat+$lng+$search+$type+$city+$region+$prov+$provLng", time() + 3600);
		if ($city == NULL || $prov == NULL){
			header('Location: /rent');
		}
		// City Level
		if( $street == NULL && $city != NULL ){
			$search = NULL;
		// Prov Level
		}else if( $city == NULL && $prov != NULL ){
			$search = NULL;
			$area = NULL;
		}else if(  $prov == NULL ){
			$search = NULL;
			$area = NULL;
			$prov = NULL;
		}
		$sub = '/'.$prov.'/'.urlencode($area);
	}else if (citySearch($address,NULL) != NULL){
	// Check if city then get prov
		list($lat, $lng, $search, $type, $city, $region, $prov, $provLng, $sub) = citySearch($address,NULL);
	}else{
	// Prop ID, Prop Name only need Search variable
		$sub = NULL;
		$prov = NULL;
		$area = NULL;
	}
	//Set Map and List links
	if(!isset($area)){$area=NULL;}
	$maparray = array('prov'=>$prov,'city'=>urlencode($area),'search'=>$search);
	$listarray = array('search'=>$search);
	$mapbeg = (array_filter($maparray)) ? '?' : NULL;
	$listbeg = (array_filter($listarray)) ? '?' : NULL;
	$mapparam =  $mapbeg.http_build_query($maparray);
	$listparam = $sub.$listbeg.http_build_query($listarray);
	if(isset($page) && $page=="map"){
		header('Location: '.$map.$mapparam);
	}else{
		header('Location: '.$list.$listparam);
	}
}


// Find out if Property ID is mix of Numbers and Letters and has 5 characters
function isPropId($string){ 
	$int = preg_replace("/[^0-9]/", "", $string); 
	if (!is_int($int) && strlen($string)==5){
		return true;
	}else{
		return false;
	}
}
// City/Region Search
function citySearch($address,$prov){ 
	$city = cityRgnVars($address,$prov);
	return $city;
}
	// Get City/Region Variables
	function cityRgnVars($address,$prov){ 
		global $root;
		$json = file_get_contents("$root/_inc/cities.json");
		$resp = json_decode($json, true);
		foreach ($resp["cities"] as $list) {
			// Check if string contains city
			if ( stripos($address,$list["city"])!== false ) {
				// If prov then check prov value
				//if ( $prov != NULL  ) { $prov = provSearch($prov); }
				// If perfect city with no prov value, 
				//or if contains prov value 
				//or address contains matches prov
				if( ( strcasecmp(trim($address),$list["city"]) == 0 && $prov == NULL ) || 
				stripos($prov,$list["prov"])!== false || 
				stripos($prov,$list["provLng"])!== false || 
				stripos($address," ".$list["prov"])!== false ){
					$lat = $list["lat"];
					$lng = $list["lng"];
					$search = NULL;
					$type = "ADDRESS";
					$city = $list["city"];
					$region = $list["region"]; 
					$prov = $list["prov"];
					$provLng = $list["provLng"];
					$area=$city;
					$sub = '/'.$prov.'/'.urlencode($area);
					setcookie("geocode", "$lat+$lng+$search+$type+$city+$region+$prov+$provLng", time() + 3600);
					return array($lat, $lng, $search, $type, $city, $region, $prov, $provLng, $sub);
					
				}
			}
		}
		return false;
	}
	// Prov Search
	function provSearch($address){ 
		global $root;
		$json = file_get_contents("$root/_inc/prov.json");
		$resp = json_decode($json, true);
		foreach ($resp["country"] as $country) {
			if ( strcasecmp(trim($address),$country["prov"]) == 0 || stripos($address," ".$country["prov"])!== false || stripos($address,$country["provLng"])!== false ) {
				return $country["prov"];
			}
		}
		return NULL;
	}
	// CleanURL
	function cleanUrl($name){ 
		$url = preg_replace("/[^a-zA-Z0-9[:blank:]]/", "", $name); //Remove all characters except letters/numbers
		$url = preg_replace('/\s+/', ' ',$url); //Remove whitespace except first/last
		$url = urlencode($url);
		return $url;
	}
	// Get IP Address
	function getIPCity(){
		if (!function_exists('geoip_record_by_name')){ 
			global $provinces_array;
	   		$json = url_get_contents("http://api.ipinfodb.com/v3/ip-city/?key=bb1cb9f6d5a21ccbbe0684fb4e7ab2cf5314f070e764ac1802c726e59f0e8f65&ip=".$_SERVER['REMOTE_ADDR']."&format=json");
			$json = json_decode($json,true);
			$lat = $json['latitude'];
			$lng = $json['longitude'];
			$city = ucwords(strtolower($json['cityName']));
			$prov = ucwords(strtolower($json['regionName']));
			$prov_code = $provinces_array[$prov];
			return array($lat, $lng, $city, $prov_code, $json['countryCode']);
		}else{
			$geo = geoip_record_by_name($_SERVER['REMOTE_ADDR']);
			return array($geo["latitude"], $geo["longitude"], $geo["city"], $geo["region"], $geo["country_code"]);
		}
	}
	//CURL get contents function
	function url_get_contents ($Url) {
		if (!function_exists('curl_init')){ 
			die('CURL is not installed!');
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $Url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$output = curl_exec($ch);
		curl_close($ch);
		return $output;
	}	
	// Delete Files and Directory Folder
	function delTree($dir) { 
		if (file_exists($dir)) {
			$files = array_diff(scandir($dir), array('.','..')); 
			foreach ($files as $file) { 
				(is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file"); 
			} 
			return rmdir($dir);
		}else{
			return false;
		}
	}
	// Return MySQL escaped data
	function mysqlEscape($post) { 
		require("mysqlconnect.php");
		foreach($post as $key =>$value){
			$$key=mysql_real_escape_string($value);
		}
		require("mysqlclose.php");
		return $post;		
	}
	//Remove new lines and replace with breaks
	function nl2br2($string) { 
		$string = str_replace(array('\r\n', '\r', '\n'), "<br>", $string);
		return $string; 
	}
	$provinces_array=array(
		"Alberta"=>"AB",
		"British Columbia"=>"BC",
		"Manitoba"=>"MB",
		"New Brunswick"=>"NB",
		"Newfoundland"=>"NL",
		"Nova Scotia"=>"NS",
		"Ontario"=>"ON",
		"Prince Edward Island"=>"PE",
		"Quebec"=>"QC",
		"Saskatchewan"=>"SK"
		);
?>