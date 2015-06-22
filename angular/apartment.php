<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include("global.php");
require("mysqli-connect.php");


//Show Tracking Record
$page_id="1w9k4";
if(isset($_GET["prop_id"])){$page_id = $_GET["prop_id"];}
$info = $conn->query("SELECT *, uf.id_prop AS star, p.id_user AS owner,
GROUP_CONCAT(CONCAT('<tr><td>', style, '</td><td>', beds, '</td><td>', ba, '</td><td>', sq_ft, '</td><td>$',  rent, '</td><td>', dep, '</td></tr>') 
	ORDER BY u_order ASC SEPARATOR '') AS unit
FROM properties p 
LEFT JOIN prop_units ON prop_units.id_prop = '$page_id'
LEFT JOIN prop_intro ON prop_intro.id_prop = '$page_id' 
LEFT JOIN prop_hours ON prop_hours.id_prop = '$page_id' 
LEFT JOIN user_fav uf ON uf.id_prop = '$page_id' AND $fav_find 
WHERE id_pg = '$page_id'");
$info = $info->fetch_array(MYSQLI_ASSOC);


//If no name or unit then redirect
//If deleted then go to list page with message
if($info['name'] == NULL || $info['unit'] == NULL || $info['deleted']==1 || $info['pub']==='0'){
	header("HTTP/1.0 404 Not Found");
	if($info['deleted']==1 || $info['pub']==='0'){
		$_SESSION["listremoved"]='<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Oh snap!</strong> "'.$info['name']." in ".$info['city'].", ".$info['prov'].'"</i> is no longer available.<br>Find equally awesome apartments near '.$info['city'].", ".$info['prov'].' below.</div>';
	}
	header("Location: $list/".$_GET["prov"]."/".urlencode($_GET["city"])."/");
}
//Redirect users to correct URL
/*
$correctURL = "/rent/".urlencode($info['prov'])."/".urlencode($info['city'])."/".cleanUrl($info['name'])."/".$info['id_pg'];
if($_SERVER["REQUEST_URI"] != $correctURL){
	header ('HTTP/1.1 301 Moved Permanently');
	header("Location: $correctURL");
}
*/
$json ='{"records":'.json_encode($info).'}';
echo $json;

/*
$check = mysql_query("SELECT type, GROUP_CONCAT(CONCAT('<tr><td>', feat, '</td></tr>') SEPARATOR '') AS featlist
FROM prop_feat
WHERE id_prop = '$page_id' AND deleted = 0
GROUP BY type");
$featrows = mysql_num_rows($check);
$info3 = mysql_query("SELECT photo
FROM prop_photos
WHERE id_prop = '$page_id'
ORDER BY p_order ASC");
$pageTitle = $info['name'].' in '.$info['city'].', '.$info['prov'];
$metaDesc = "See Apartment photos, location, description and features for ".$info['name'].' in '.$info['city'].', '.$info['prov'];
if($info['email']){
	$contactEmail = $info['email'];
}else{
	$id = $info["id_user"];
	$user = mysql_fetch_array(mysql_query("SELECT email 
	FROM users 
	WHERE id = '$id'"));
	$contactEmail = $user["email"];
}
*/