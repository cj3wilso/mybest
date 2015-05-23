<?php
include("global.php");
if( isset($level[1]) && isset($_GET["search"]) ){ //If prop id or prop name
	$page = "city";
	$pageTitle = "View apartment rental: ".$_GET["search"];
	$metaDesc = "See apartment details for property search: ".$_GET["search"];
	include("list.php");
}else if( isset($level[3]) ){ //If street address or city
	$page = "city";
	// Page number needed so no duplicate title/desc tags
	$numpage=$results="";
	if(isset($_GET["page"]))$numpage=" - Page ".$_GET["page"];
	if(isset($_GET["ipp"]))$results=", ".$_GET["ipp"]." results per page";
	$pageTitle = "Apartments rentals in ".urldecode($level[3]).', '.$level[2].", Canada".$numpage.$results;
	$metaDesc = "Find apartments that meet your needs in ".$level[3].", ".$level[2].", Canada".$numpage.$results;
	include("list.php");
}else if (isset($level[2])){
	$page = "prov";
	$pageTitle = "Apartments rentals in ".$level[2].", Canada";
	$metaDesc = "Find apartments that meet your needs in ".$level[2].", Canada.";
	include("city.php");
}else{
	$page = "cnty";
	$pageTitle = "Rentals in Canada";
	$metaDesc = "See all apartment rentals available in Canada on My Best Apartments.";
	include("prov.php");
}
?>