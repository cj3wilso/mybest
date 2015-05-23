<?php
require("global.php");
include 'mysqlconnect.php';
if($_GET['user'] != ""){
	$id_name = "id_user";
	$id_value = "'".$_GET['user']."'";
}else{
	$id_name = "id_session";
	$id_value = "'".$_GET['session']."'";
}
$saveurl = mysql_real_escape_string($_GET['saveurl']);

$query = "SELECT email_results FROM user_search WHERE $id_name = $id_value AND url = '".$saveurl."' LIMIT 1;";
$result = mysql_query ($query) or die(mysql_error());
$rows = mysql_num_rows ( $result );
$saved_search_row = mysql_fetch_array($result);

if (!$result) {
	echo json_encode(mysql_error());
}else{
	if($saved_search_row['email_results']==0){
		echo json_encode("<input type='checkbox' name='email' value='1'> Click here to get new results emailed to you daily.");
	}else{
		echo json_encode("<input type='checkbox' name='email' value='1' checked> Click here to get new results emailed to you daily.");
	}
}
include 'mysqlclose.php';