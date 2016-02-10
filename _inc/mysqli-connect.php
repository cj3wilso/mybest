<?php
$root = realpath((getenv('DOCUMENT_ROOT') && strpos(realpath(__FILE__),preg_quote(realpath(getenv('DOCUMENT_ROOT')))) !== false) ? getenv('DOCUMENT_ROOT') : str_replace(@$_SERVER['PHP_SELF'], '', dirname(__FILE__)));
$root = str_replace('/_inc','',$root);

$db_host = "127.0.0.1";
$db_password = "57575757aA";
$db_port = "3306";
// Connects to your Database
if($root == "/www/var/sites/dev_best"){
	$db_username = $db_name = "cj3wilso_rentdev";
}else{
	$db_username = $db_name = "cj3wilso_rent254";
}

$conn = new mysqli($db_host, $db_username, $db_password, $db_name, $db_port);
if ($conn->connect_errno) {
	echo "Failed to connect to MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error;
}
?>