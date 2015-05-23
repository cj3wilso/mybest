<?php
require("mysqlconnect.php");
$city = $_GET["city"];
$sql = "SELECT * 
FROM properties p 
INNER JOIN prop_units u ON (p.id_pg = u.id_prop) 
INNER JOIN prop_photos ph ON (p.id_pg = ph.id_prop AND ph.p_order = 1) 
WHERE city='$city' AND p.removed = '0000-00-00 00:00:00' AND p.pub=1 
ORDER BY name";
$result = mysql_query($sql);
require("mysqlclose.php");

$return_arr = array();
while ($row = @mysql_fetch_assoc($result)){
  	if($row['address2']){ $row['address2'] = ', '.$row['address2'];}
	if($row['address'] || $row['address2']){ $tog = ', ';}else{$tog = '';}
	if($row['post']){ $row['post'] = ', '.$row['post'];}
	$fulladdress = $row['address'].$row['address2'].$tog.$row['city'].', '.$row['prov'].', '.$row['cntry'].$row['post'];
  	array_push($return_arr, array(
		'photo' => "http://mybestapartments.ca/upload/server/php/files/".$row['id_pg']."/thumbnail/".$row['photo'],
  		'name' => $row['name'],
		'address' => $fulladdress,
		'price' => '$'.$row['rent'],
		'beds' => $row['beds'],
		'id' => $row['id_pg']
  	));
}
$return_arr = json_encode($return_arr);


header('Content-Type: application/json; charset=utf8');
echo '{ "repositories": '.$return_arr.'}';
?>
