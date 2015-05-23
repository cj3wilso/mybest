<?php
require("global.php");
include("form_results.php");

require("mysqlconnect.php");
if(!isset($sql_price)){$sql_price="";}
	// If Street OR City/Region search
	if (  $search_type == "street" || $search_type == "city" ) { 
		$sql = "SELECT *, 
		IF(COUNT( DISTINCT rent ) > 1,CONCAT('$', MIN(rent), ' - $', MAX(rent)),CONCAT('$', rent)) as rent, 
		IF(COUNT( DISTINCT beds ) > 1,CONCAT(MIN(beds), ' - ', MAX(beds)),beds) as beds, 
		( 6371 * acos( cos( radians($lat) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians($lng) ) + sin( radians($lat) ) * sin( radians( lat ) ) ) ) AS distance 
		FROM properties p 
		INNER JOIN prop_units u ON p.id_pg = u.id_prop $sql_beds $sql_bath $sql_price 
		$sql_feat 
		LEFT JOIN prop_photos c ON p.id_pg = c.id_prop AND c.p_order = 1  
		$sql_feat_where 
		GROUP BY p.id_pg 
		HAVING distance < $radius 
		ORDER BY distance, c.id";
		$result = mysql_query($sql);
	// If Property ID
	}else if ($search_type == "propid"){		
		$sql = "SELECT *, 
		IF(COUNT( DISTINCT rent ) > 1,CONCAT('$', MIN(rent), ' - $', MAX(rent)),CONCAT('$', rent)) as rent, 
		IF(COUNT( DISTINCT beds ) > 1,CONCAT(MIN(beds), ' - ', MAX(beds)),beds) as beds 
		FROM properties p 
		INNER JOIN prop_units u ON p.id_pg = u.id_prop 
		INNER JOIN prop_feat a ON p.id_pg = a.id_prop 
		INNER JOIN prop_feat b ON p.id_pg = b.id_prop 
		LEFT JOIN prop_photos c ON p.id_pg = c.id_prop AND c.p_order = 1   
		$sql_propid 
		GROUP BY p.id_pg 
		ORDER BY c.id";
		$result = mysql_query($sql);
	// If Property Name
	}else if ($search_type == "propname"){				
		$sql = "SELECT *, 
		IF(COUNT( DISTINCT rent ) > 1,CONCAT('$', MIN(rent), ' - $', MAX(rent)),CONCAT('$', rent)) as rent, 
		IF(COUNT( DISTINCT beds ) > 1,CONCAT(MIN(beds), ' - ', MAX(beds)),beds) as beds 
		FROM properties p 
		INNER JOIN prop_units u ON p.id_pg = u.id_prop 
		INNER JOIN prop_feat a ON p.id_pg = a.id_prop 
		INNER JOIN prop_feat b ON p.id_pg = b.id_prop 
		LEFT JOIN prop_photos c ON p.id_pg = c.id_prop AND c.p_order = 1   
		$sql_propname 
		GROUP BY p.id_pg 
		ORDER BY c.id";
		$result = mysql_query($sql);
	}
	require("mysqlclose.php");
	$totalrows = mysql_num_rows($result);

if (!$result) {
  die("Invalid query: " . mysql_error());
}

header("Content-type: text/xml");

// Start XML file, create parent node
$dom = new DOMDocument("1.0");
$node = $dom->createElement("markers");
$node->setAttribute("rows", $totalrows);
$parnode = $dom->appendChild($node);


// Iterate through the rows, adding XML nodes for each
while ($row = @mysql_fetch_assoc($result)){
	if ($row['photo']){
		$photo = "/upload/server/php/files/".$row['id_pg']."/thumbnail/".$row['photo'];
	}else{
		$photo = "http://placehold.it/115x115&text=No%20Photos";
	}
	if($row['address']){$tog= ", ";}else{$tog = "";}
	if($row['city']){$tog2= ", ";}else{$tog2 = "";}
	if($row['post']){$tog3= ", ";}else{$tog3 = "";}
	$fulladdress = $row['address'].$row['address2'].$tog.$row['city'].$tog2.$row['prov'].', '.$row['cntry'].$tog3.$row['post'];
	if($row['phone1'] != 0){ $phone = '<h4>('.$row['phone1'].') '.$row['phone2'].'-'.$row['phone3'].'</h4>';}else{$phone = '';}
  	$node = $dom->createElement("marker");
  	$newnode = $parnode->appendChild($node);
  	$newnode->setAttribute("name", $row['name']);
  	$newnode->setAttribute("address", $fulladdress);
  	$newnode->setAttribute("address2", $row['address2']);
  	$newnode->setAttribute("lat", $row['lat']);
  	$newnode->setAttribute("lng", $row['lng']);
  	$newnode->setAttribute("distance", $row['distance']);
  	$newnode->setAttribute("beds", $row['beds']);
  	$newnode->setAttribute("rent", $row['rent']);
  	$newnode->setAttribute("phone", $phone);
  	$newnode->setAttribute("detail", $detail);
  	$newnode->setAttribute("id_pg", $row['id_pg']);
  	$newnode->setAttribute("img", $photo);
}

echo $dom->saveXML();
?>
