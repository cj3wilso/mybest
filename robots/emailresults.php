#!/usr/bin/php
<?php
//error_reporting(E_ALL); ini_set('display_errors', '1');
include("global.php");
include("get_address.php");

//Check all users that want to be emailed
include("mysqlconnect.php");
$check = mysql_query("SELECT s.modified, u.email, s.url, s.id_user 
FROM user_search s
INNER JOIN users u ON s.id_user=u.id 
WHERE s.deleted=0 AND s.email_results=1");
include("mysqlclose.php");
$allemails = array();
while ($row = @mysql_fetch_assoc($check)){
	$get_options = $get_search = $get_prov = $get_city = "";
	$level = explode("/", $row["url"]);
	$get_prov=$level[2];
	$get_city=$level[3];
	if(isset($level[4])){$get_options=$level[4];}
	$get_search=NULL;
	list ($lat, $lng, $sql_beds, $sql_bath, $sql_price, $sql_feat, $sql_feat_where, $city, $prov) = get_address($get_search, $get_prov, $get_city, $get_options);
	$dbody="Latest apartment listings: <br><br>";
	include("mysqlconnect.php");
	$props = "SELECT p.*, c.photo, 
		IF(COUNT( DISTINCT u.rent ) > 1,CONCAT('$', MIN(u.rent), ' - $', MAX(u.rent)),CONCAT('$', u.rent)) AS rent, 
		IF(COUNT( DISTINCT u.beds ) > 1,CONCAT(MIN(u.beds), ' - ', MAX(u.beds)),u.beds) AS beds, 
		( 6371 * acos( cos( radians($lat) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians($lng) ) + sin( radians($lat) ) * sin( radians( lat ) ) ) ) AS distance 
		FROM properties p 
		INNER JOIN ( SELECT MAX(rent) AS maxrent FROM prop_units ) AS umax
		INNER JOIN prop_units u ON (p.id_pg = u.id_prop $sql_beds $sql_bath $sql_price) 
		$sql_feat 
		LEFT JOIN prop_photos c ON p.id_pg = c.id_prop AND c.p_order = 1 
		$sql_feat_where AND created > '".$row["modified"]."'
		GROUP BY p.id_pg 
		HAVING distance < $radius 
		ORDER BY (CASE WHEN p.city = '".$city."' THEN 1 ELSE 0 END ) DESC";
	$result = mysql_query($props);
	while ($prop = @mysql_fetch_assoc($result)){
		$dbody .= "<a href='http://mybestapartments.ca/rent/".$prop["prov"]."/".urlencode($prop["city"])."/".cleanUrl($prop["name"])."/".$prop["id_pg"]."'>".$prop["name"]."</a><br>";
	}
	$today = date("c");
	$update = mysql_query("UPDATE user_search
	SET modified='$today' 
	WHERE id_user=".$row["id_user"]." AND url='".$row["url"]."'");
	mysql_query($update);
	include("mysqlclose.php");
	if($dbody != "Latest apartment listings: <br><br>"){
		$emailTo = $row["email"]; //Who are we emailing?
		$dsubject = "Daily apartment search results";
		$dheaders  = "From: $company <$companyEmail>\r\n" .
		"X-Mailer: php\r\n";
		$dheaders .= "MIME-Version: 1.0\r\n";
		$dheaders .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		$dheaders .= "Reply-To: $companyEmail\r\n";
		if (mail($emailTo, $dsubject, $dbody, $dheaders) ) {
			echo "email sent/n/n";
		} else {
			mail("cj3wilso@gmail.com", "Problem: ".$dsubject, "This email didn't send: <br><br>".$dbody, $dheaders);
		}
	}
	$allemails[] = $dbody;
}

print_r($allemails);
?>