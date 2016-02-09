<?php
$root = realpath((getenv('DOCUMENT_ROOT') && strpos(realpath(__FILE__),preg_quote(realpath(getenv('DOCUMENT_ROOT')))) !== false) ? getenv('DOCUMENT_ROOT') : str_replace(@$_SERVER['PHP_SELF'], '', dirname(__FILE__)));
$root = str_replace('/_inc','',$root);
$db_host = "localhost";
$db_password = "57575757aA";
// Connects to your Database
if($root == "/www/var/html/sites/dev_best"){
	$db_username = $db_name = "cj3wilso_rentdev";
}else{
	$db_username = $db_name = "cj3wilso_rent254";
}
 

$con = mysql_connect($db_host, $db_username, $db_password) or die(mysql_error('Cannot connect as database user')); 
mysql_select_db($db_name) or die(mysql_error('Cannot select database')); 
?>