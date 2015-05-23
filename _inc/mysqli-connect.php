<?php
$root = realpath((getenv('DOCUMENT_ROOT') && strpos(realpath(__FILE__),preg_quote(realpath(getenv('DOCUMENT_ROOT')))) !== false) ? getenv('DOCUMENT_ROOT') : str_replace(@$_SERVER['PHP_SELF'], '', dirname(__FILE__)));
$root = str_replace('/_inc','',$root);

$db_host = "localhost";
$db_password = "57575757aA";
// Connects to your Database
if($root == "/home2/cj3wilso/public_html/dev_best"){
	$db_username = $db_name = "cj3wilso_rentdev";
}else{
	$db_username = $db_name = "cj3wilso_rent254";
}

$conn = new mysqli($db_host, $db_username, $db_password, $db_name);
if ($conn->connect_errno) {
	echo "Failed to connect to MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error;
}
?>