<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include("global.php");
require("mysqli-connect.php");

if(!isset($_GET["prov"])){$_GET["prov"] = "ON";}
if(!isset($_GET["city"])){$_GET["city"] = "Toronto";}

$sort = 'created DESC';
$sort_options = array('name asc','name desc','rent asc','rent desc','created desc','distance asc');
$get_sort = (isset($_GET['sort']) ? $_GET['sort'] : '');
$sort_decoded = str_replace("-", " ", $get_sort);
if(isset($_GET['sort']) && in_array($sort_decoded,$sort_options) ){
	if($sort_decoded == 'rent asc'){
		$sort = "ABS(rent) ASC"; 
	}else if($sort_decoded == 'rent desc'){
		$sort = "ABS(rent) DESC";
	}else{
		$sort = $_GET['sort'];
	}
}


include("form_results.php");
include("paginator.class.php");
	
	if(!isset($sql_feat))$sql_feat="";
	if(!isset($sql_price))$sql_price="";
	// If Street OR City/Region search
	if (  $search_type == "street" || $search_type == "city" ) { 
		//Get row count
		$check = $conn->query("SELECT p.*, u.*,
		( 6371 * acos( cos( radians($lat) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians($lng) ) + sin( radians($lat) ) * sin( radians( lat ) ) ) ) AS distance 
		FROM (properties p) 
		INNER JOIN prop_units u ON (p.id_pg = u.id_prop $sql_beds $sql_bath $sql_price) 
		$sql_feat 
		$sql_feat_where 
		GROUP BY p.id_pg 
		HAVING distance < $radius");
		$totalrows = $check->num_rows;
		//$totalrows = mysql_num_rows($check) or die(mysql_error());
		//Need to get all information on properties.. but only show ones that contain 2 beds, 2 baths, pets allowed
		// Should I query in first query to grab the property ID.. then query the property IDS on the second one? I think that could work!
	
		//Start Pagination
		$pages = new Paginator;  
		$pages->items_total = $totalrows;
		$pages->mid_range = 7;
		$pages->paginate();
		
		// Show list of properties
		//INNER JOIN ( SELECT MAX(rent) AS maxrent FROM prop_units WHERE 1 = 1 ) AS umax 
		//Select the first photo in Left Join, then second left join finds photo on first photo of prop
		$sql = "SELECT p.*, c.photo, uf.id_prop AS star, STR_TO_DATE(date, '%M %d %Y') as latest, 
		IF(COUNT( DISTINCT u.rent ) > 1,CONCAT('$', MIN(u.rent), ' - $', MAX(u.rent)),CONCAT('$', u.rent)) AS rent, 
		IF(COUNT( DISTINCT u.beds ) > 1,CONCAT(MIN(u.beds), ' - ', MAX(u.beds)),u.beds) AS beds, 
		( 6371 * acos( cos( radians($lat) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians($lng) ) + sin( radians($lat) ) * sin( radians( lat ) ) ) ) AS distance 
		FROM properties p 
		INNER JOIN ( SELECT MAX(rent) AS maxrent FROM prop_units ) AS umax
		INNER JOIN prop_units u ON (p.id_pg = u.id_prop $sql_beds $sql_bath $sql_price) 
		$sql_feat 
		LEFT JOIN prop_photos c ON p.id_pg = c.id_prop AND c.p_order = 1 
		LEFT JOIN user_fav uf ON p.id_pg = uf.id_prop AND $fav_find
		$sql_feat_where 
		GROUP BY p.id_pg 
		HAVING distance < $radius 
		ORDER BY (CASE WHEN p.city = '".$city."' THEN 1 ELSE 0 END ) DESC, $sort
		$pages->limit";
		$result = $conn->query($sql);
	// If Property ID
	}else if ($search_type == "propid"){		
		//Get row count
		$check = $conn->query("SELECT p.*, u.* 
		FROM properties p 
		INNER JOIN prop_units u ON p.id_pg = u.id_prop 
		INNER JOIN prop_feat a on p.id_pg = a.id_prop 
		INNER JOIN prop_feat b on p.id_pg = b.id_prop 
		$sql_propid 
		GROUP BY p.id_pg");
		$totalrows = $check->num_rows;
	
		//Start Pagination
		$pages = new Paginator;  
		$pages->items_total = $totalrows;
		$pages->mid_range = 7;
		$pages->limit = 2;
		$pages->paginate();
		
		$sql = "SELECT p.*, c.photo, uf.id_prop AS star, STR_TO_DATE(date, '%M %d %Y') as latest, 
		IF(COUNT( DISTINCT rent ) > 1,CONCAT('$', MIN(rent), ' - $', MAX(rent)),CONCAT('$', rent)) as rent, 
		IF(COUNT( DISTINCT beds ) > 1,CONCAT(MIN(beds), ' - ', MAX(beds)),beds) as beds 
		FROM properties p 
		INNER JOIN ( SELECT MAX(rent) AS maxrent FROM prop_units ) AS umax 
		INNER JOIN prop_units u ON p.id_pg = u.id_prop 
		INNER JOIN prop_feat a ON p.id_pg = a.id_prop 
		INNER JOIN prop_feat b ON p.id_pg = b.id_prop 
		LEFT JOIN (SELECT id_prop, photo, MIN(p_order) AS first FROM prop_photos GROUP BY id_prop) AS cc 
			ON p.id_pg = cc.id_prop
		LEFT JOIN prop_photos c ON p.id_pg = c.id_prop AND c.p_order = cc.first 
		LEFT JOIN user_fav uf ON p.id_pg = uf.id_prop  AND $fav_find
		$sql_propid 
		GROUP BY p.id_pg 
		ORDER BY c.id ASC 
		$pages->limit";
		$result = $conn->query($sql);
	// If Property Name
	}else if ($search_type == "propname"){		
		//Get row count
		$check = $conn->query("SELECT p.*, u.* 
		FROM properties p 
		INNER JOIN prop_units u ON p.id_pg = u.id_prop 
		INNER JOIN prop_feat a on p.id_pg = a.id_prop 
		INNER JOIN prop_feat b on p.id_pg = b.id_prop 
		$sql_propname 
		GROUP BY p.id_pg");
		$totalrows = $check->num_rows;
	
		//Start Pagination
		$pages = new Paginator;  
		$pages->items_total = $totalrows;
		$pages->mid_range = 7;
		$pages->limit = 2;
		$pages->paginate();
		
		$sql = "SELECT p.*, c.photo, uf.id_prop AS star, STR_TO_DATE(date, '%M %d %Y') as latest, 
		IF(COUNT( DISTINCT rent ) > 1,CONCAT('$', MIN(rent), ' - $', MAX(rent)),CONCAT('$', rent)) as rent, 
		IF(COUNT( DISTINCT beds ) > 1,CONCAT(MIN(beds), ' - ', MAX(beds)),beds) as beds 
		FROM properties p 
		INNER JOIN ( SELECT MAX(rent) AS maxrent FROM prop_units ) AS umax 
		INNER JOIN prop_units u ON p.id_pg = u.id_prop 
		INNER JOIN prop_feat a ON p.id_pg = a.id_prop 
		INNER JOIN prop_feat b ON p.id_pg = b.id_prop 
		LEFT JOIN (SELECT id_prop, photo, MIN(p_order) AS first FROM prop_photos GROUP BY id_prop) AS cc 
			ON p.id_pg = cc.id_prop
		LEFT JOIN prop_photos c ON p.id_pg = c.id_prop AND c.p_order = cc.first 
		LEFT JOIN user_fav uf ON p.id_pg = uf.id_prop  AND $fav_find
		$sql_propname 
		GROUP BY p.id_pg 
		ORDER BY c.id ASC 
		$pages->limit";
		$result = $conn->query($sql);
	//If Search is NULL and city is not a city, redirect to Province Page
	}else if ($search_type == "nosearch"){
		$_SESSION["listredirect"]=$_SERVER['REQUEST_URI'];
		//header('Location: '.$list);
	}
$list = array();
while( $row = $result->fetch_array(MYSQLI_ASSOC)){
    $list[] = $row;
}
$result->close();
$json ='{';
$json .= '"paginate":{"total":"'.$pages->num_pages.'", "current": "'.$pages->current_page.'", "prov": "'.$list[0]["prov"].'", "city": "'.$list[0]["city"].'", "radius": "'.$radius.'", "rows": "'.$totalrows.'"}, ';
$json .='"records":'.json_encode($list);
$json .= '}';

//$json ='{"records":'.json_encode($list).'}';
echo $json;