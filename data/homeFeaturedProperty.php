<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include("global.php");
require("mysqli-connect.php");
/*Connect to Memcache*/
require_once("memcache.php");

//Do we have any live ads?
$gotads ="SELECT p.id_prop 
		FROM prop_promote p 
		INNER JOIN properties pp ON p.id_prop = pp.id_pg 
		WHERE p.expired = '0000-00-00 00:00:00' AND p.payer_id IS NOT NULL AND pp.pub=1 AND p.sku = 'B0012'";
$gotads = mysql_query_cache($gotads);

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
$city_exists = mysql_query_cache($cityquery);

//If no ads in city then don't search lat/lng
if(count($city_exists) == 0){
	$select_distance=$having_distance=NULL;
}

//If country code isn't CA then don't show "Search by city" area
if($cnty != "CA"){$city=NULL;}
$where_city = "";

if(count($gotads) == 0){
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
		INNER JOIN prop_units u ON pp.id_pg = u.id_prop 
		INNER JOIN prop_feat a ON pp.id_pg = a.id_prop 
		INNER JOIN prop_feat b ON pp.id_pg = b.id_prop 
		LEFT JOIN prop_photos c ON pp.id_pg = c.id_prop AND c.p_order = 1 
		WHERE pp.id >= p2.id AND pp.pub=1 $where_city 
		GROUP BY pp.id_pg  
		ORDER BY pp.id ASC 
		LIMIT 1";
	$promoresult = mysql_query_cache($sql,5);
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
	$promoresult = mysql_query_cache($promo_sql,5);
	$promo_num_rows = count($promoresult);
}
$outp = "";
$rs = $promoresult;
//while($rs = $promoresult->fetch_array(MYSQLI_ASSOC)) {
foreach ($rs as $k => $v) {
    if ($outp != "") {$outp .= ",";}
	$outp .= '{';
    $outp .= '"Name":"'  . $rs[$k]["name"] . '",';
    $outp .= '"ID":"'   . $rs[$k]["id_pg"]        . '",';
	$outp .= '"URL":"'   . $detail."/".$rs[$k]["prov"]."/".urlencode($rs[$k]["city"])."/".cleanUrl($rs[$k]["name"])."/".$rs[$k]["id_pg"] . '",';
	$outp .= '"Prov":"'   . $rs[$k]["prov"] . '",';
	$outp .= '"Date":"'   . $rs[$k]["date"] . '",';
	$outp .= '"Rent":"'   . $rs[$k]["rent"] . '",';
	$outp .= '"Beds":"'   . $rs[$k]["beds"] . '",';
	$outp .= '"ExternalURL":"'   . $rs[$k]["url"] . '",';
	$outp .= '"Lat":"'   . $rs[$k]["lat"] . '",';
	$outp .= '"Lng":"'   . $rs[$k]["lng"] . '",';
	if($rs[$k]["phone1"]!= 0){
		$outp .= '"Phone":"'   . "(" .$rs[$k]["phone1"].") ".$rs[$k]["phone2"]."-".$rs[$k]["phone3"] . '",';
		$outp .= '"PhoneURL":"'   . "tel://1-" .$rs[$k]["phone1"]."-".$rs[$k]["phone2"]."-".$rs[$k]["phone3"] . '",';
	}
	if ($rs[$k]['photo']!=NULL){
		$outp .= '"Photo":"'   . $rs[$k]["photo"] . '",';
	}
    $outp .= '"City":"'. $rs[$k]["city"]     . '"}';
}
$json ='{';
if(isset($city)){
	$json .= '"IPCity":{"City":"'.$city.'", "CityURL": "'.$list."/".$prov_code."/".urlencode($city).'"}, ';
}
$json .='"records":['.$outp.']';

$json .= '}';
echo($json);