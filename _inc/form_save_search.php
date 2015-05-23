<?php
require("global.php");
include 'mysqlconnect.php';
$today = date("c");
$email = $_POST['email'];
if($_POST['user'] != ""){
	$id_name = "id_user";
	$id_value = "'".$_POST['user']."'";
}else{
	$id_name = "id_session";
	$id_value = "'".$_POST['session']."'";
}
$saveurl = mysql_real_escape_string($_POST['saveurl']);
if ($_POST['type'] == 'insert') {
	$query = "SELECT url FROM user_search WHERE $id_name = $id_value AND url = '".$saveurl."';";
	$result = mysql_query ($query) or die(mysql_error());
	$rows = mysql_num_rows ( $result );
	if($rows>0) {
		$query = "UPDATE user_search SET deleted=0, modified='$today', email_results=$email WHERE $id_name = $id_value AND url = '".$saveurl."'";
	}else{
		$query = "INSERT INTO user_search ($id_name, url, created, modified, email_results) VALUES ($id_value, '".$saveurl."', '$today', '$today', $email) ;";
	}
}else{
	$query = "UPDATE user_search SET deleted=1, modified='$today' WHERE $id_name = $id_value AND url = '".$saveurl."'";
}
$result = mysql_query($query);
if (!$result) {
	echo json_encode(mysql_error());
}else{
	if($email==1){
		echo json_encode('You will now be emailed new results daily.');
	}else{
		echo json_encode('You won\'t be emailed new results anymore.');
	}
}
include 'mysqlclose.php';