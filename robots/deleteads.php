#!/usr/bin/php
<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
include("global.php");
$offline = $online = "";
function Visit($url){
       $agent = "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)";
	   $ch=curl_init();
       curl_setopt ($ch, CURLOPT_URL,$url );
	   //Follow the redirects for old urls.. then check if post is up
	   curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
       curl_setopt($ch, CURLOPT_USERAGENT, $agent);
       curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt ($ch,CURLOPT_VERBOSE,false);
       curl_setopt($ch, CURLOPT_TIMEOUT, 5);
       //curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, FALSE);
       //curl_setopt($ch,CURLOPT_SSLVERSION,3);
       //curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, FALSE);
       $page=curl_exec($ch);
       //echo curl_error($ch);
	   //echo $page;
       $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
       curl_close($ch);
	   //Include 300s now because some URLs are old and being redirected to live listing
       if( $httpcode>=200 && $httpcode<300 && strpos($page,'No Longer Available') === false && strpos($page,'Oops... Too late!') === false && strpos($page,'Welcome to Kijiji') === false ){
		    //if($httpcode>=300 && $httpcode<400){
			return true;
	   }else{ 
	   		return false;
	   }
}
require("mysqli-connect.php");
//Check all undeleted listings
$check = "SELECT where_posted, id_pg, created FROM properties WHERE deleted=0";
$check = $conn->query($check);
$today = date("c");
while($row = $check->fetch_array(MYSQLI_ASSOC)) {
	$value = $row['id_pg'];
	if ($row['where_posted'] != ""){
		//echo $row['where_posted']."<br>";
		//continue;
		$adStillUp = Visit($row['where_posted']);
		$todayDate = new DateTime;
		$createdDate = new DateTime($row['created']);
		if ( !$adStillUp || $createdDate->modify('+1 year') < $todayDate ){
			//echo $adStillUp."<br>";
			delTree("upload/server/php/files/$value");
			//Update properties table with delete id - so we can tell user this existed but gone
			$conn->query("UPDATE properties SET deleted=1, removed='$today' WHERE id_pg='$value'");
			$conn->query("DELETE FROM prop_feat WHERE id_prop='$value'");
			$conn->query("DELETE FROM prop_hours WHERE id_prop='$value'");
			$conn->query("DELETE FROM prop_intro WHERE id_prop='$value'");
			$conn->query("DELETE FROM prop_photos WHERE id_prop='$value' AND p_order <> 1");
			$conn->query("DELETE FROM prop_units WHERE id_prop='$value'");
			$conn->query("DELETE FROM prop_promote WHERE id_prop='$value'");
			$offline .= "<li>Property: $value DELETED! - Updated with delete</li>";
		}
		/*
		else{
			$online .= "<li>Property: $value still online</li>";
		}
		*/
	}
}
$conn->close();
?>
<ol>
<?php echo $offline; ?>
</ol>
<ol>
<?php //echo $online; ?>
</ol>