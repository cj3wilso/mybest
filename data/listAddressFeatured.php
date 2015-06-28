<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include("global.php");
require("mysqli-connect.php");

if(!isset($_GET["prov"])){$_GET["prov"] = "QC";}
if(!isset($_GET["city"])){$_GET["city"] = "Laval";}
if(!isset($sort)){$sort = 'created DESC';}

include("form_results.php");
include("paginator.class.php");
	
	if(!isset($sql_feat))$sql_feat="";
	if(!isset($sql_price))$sql_price="";
	// If Street OR City/Region search
		
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
		$promote = $conn->query($select_promos) or die(mysql_error());
		$promote_rows = $promote->num_rows;
	

$list = array();
while( $row = $promote->fetch_array(MYSQLI_ASSOC)){
    $list[] = $row;
}
$promote->close();

$json ='{"records":'.json_encode($list).'}';
echo $json;