<?php
require("mysqlconnect.php");
$apt = $_GET["apt"];
$sql = "SELECT * 
FROM properties p 
INNER JOIN prop_units u ON (p.id_pg = u.id_prop) 
INNER JOIN prop_photos ph ON (p.id_pg = ph.id_prop AND ph.p_order = 1) 
INNER JOIN prop_intro i ON (p.id_pg = i.id_prop) 
WHERE id_pg='$apt'";
$sql2 = "SELECT * 
FROM prop_photos 
WHERE id_prop='$apt'";
$row = mysql_fetch_array(mysql_query($sql));
$row2 = mysql_query($sql2);
require("mysqlclose.php");

$photolist = "";
while ($photos = @mysql_fetch_assoc($row2)){
	$photolist .= "<img src='http://mybestapartments.ca/upload/server/php/files/".$apt."/slide/".$photos['photo']."' style='width:33%;'>";
}
$return_arr = array();
if($row['address2']){ $row['address2'] = ', '.$row['address2'];}
if($row['address'] || $row['address2']){ $tog = ', ';}else{$tog = '';}
if($row['post']){ $row['post'] = ', '.$row['post'];}
$fulladdress = $row['address'].$row['address2'].$tog.$row['city'].', '.$row['prov'].', '.$row['cntry'].$row['post'];
if($row['url']){ $row['url'] = '| <a href="'.$row['url'].'">View Website</a>';}
if($row['phone1'] != 0){ $phone = '| ('.$row['phone1'].') '.$row['phone2'].'-'.$row['phone3'];}else{$phone = '';}
  	array_push($return_arr, array(
		'photo' => $photolist,
  		'name' => $row['name'],
		'address' => $fulladdress,
		'website' => $row['url'],
		'phone' => $phone,
		'price' => $row['rent'],
		'style' => $row['style'],
		'beds' => $row['beds'],
		'baths' => $row['ba'],
		'ft' => $row['sq_ft'],
		'rent' => $row['rent'],
		'dep' => $row['dep'],
		'id' => $row['id_pg'],
		'intro' => $row['text'],
		'map' => '<img src="http://maps.googleapis.com/maps/api/staticmap?center='.urlencode($fulladdress).'&zoom=13&size=600x300&sensor=false&markers=color:blue%7C'.$row['lat'].','.$row['lng'].'" style="width:100%;">'
  	));
$return_arr = json_encode($return_arr);


header('Content-Type: application/json; charset=utf8');
//$brackets = array("[", "]","{","}");
//$return_arr = str_replace($brackets, "", $return_arr);
//$return_arr = $return_arr."}";
echo '{ "repositories": '.$return_arr.'}';
?>