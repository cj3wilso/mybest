<?php
require("global.php");
require("mysqli-connect.php");
$today = date(c);
if($_POST['user'] != ""){
	$id_name = "id_user";
	$id_value = "'".$_POST['user']."'";
}else{
	$id_name = "id_session";
	$id_value = "'".$_POST['session']."'";
}
if ($_POST['type'] == 'insert') {
	$query = "SELECT FROM user_fav WHERE $id_name = $id_value AND id_prop = '".$_POST['prop']."'";
	$result = $conn->query($query);
	if(!$result) $query = "INSERT INTO user_fav ($id_name, id_prop, created) VALUES ($id_value, '".$_POST['prop']."', '$today') ;";
}else{
	$query = "DELETE FROM user_fav WHERE $id_name = $id_value AND id_prop = '".$_POST['prop']."'";
}
$result = $conn->query($query);
if (!$result) {
	echo json_encode($conn->error);
}else{
	echo json_encode('success');
}
$conn->close();