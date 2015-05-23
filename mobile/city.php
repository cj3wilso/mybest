<?php
require("mysqlconnect.php");
$prov = $_GET["prov"];
$sql = "SELECT DISTINCT p.city,
	COUNT(p.city) AS entries 
	FROM properties p 
	WHERE p.prov = '$prov' AND p.city != '' AND p.removed = '0000-00-00 00:00:00' AND p.pub=1
	GROUP BY p.city
	ORDER BY entries DESC, p.city ASC";
$result = mysql_query($sql);
require("mysqlclose.php");

$return_arr = array();
while ($row = @mysql_fetch_assoc($result)){
  array_push($return_arr, array(
  'city' => $row['city']
  ));
 }
$return_arr = json_encode($return_arr);


header('Content-Type: application/json; charset=utf8');
echo '{ "repositories": '.$return_arr.'}';
?>
