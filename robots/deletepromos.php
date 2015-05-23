#!/usr/bin/php
<?php
include("global.php");
$offline = $online = "";
include "mysqlconnect.php";
$check = mysql_query("SELECT created, id_prop FROM prop_promote WHERE expired = '0000-00-00 00:00:00'");
while ($row = @mysql_fetch_assoc($check)){
	$value = $row['id_prop'];
	//Calculate days promo has been online 
	//Needs to be less than 60 days
	$now = time(); // or your date as well
    $your_date = strtotime("2014-05-01");
	$your_date = strtotime($row['created']);
    $datediff = $now - $your_date;
	$days = floor($datediff/(60*60*24));
	$expired = date("c");
	if ($days > 60){
		mysql_query("UPDATE prop_promote SET expired='$expired' WHERE id_prop='$value';");
		$offline .= "<li>Property: $value EXPIRED!</li>";
	}else{
		$online .= "<li>Property: $value still online</li>";
	}
}
include "mysqlclose.php";
?>
<ol>
<?php echo $offline; ?>
</ol>
<ol>
<?php echo $online; ?>
</ol>