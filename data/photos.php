<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include("global.php");
require("mysqli-connect.php");


//Show Tracking Record
$page_id="1vc9n";
if(isset($_GET["prop_id"])){$page_id = $_GET["prop_id"];}

$info3 = $conn->query("SELECT DISTINCT photo
FROM prop_photos
WHERE id_prop = '$page_id'
ORDER BY p_order ASC");
$images = array();
while( $row = $info3->fetch_array(MYSQLI_ASSOC)){
    $images[] = $row["photo"];
}

$json ='{"records":'.json_encode($images).'}';
echo $json;