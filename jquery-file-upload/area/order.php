<?php
/*
 * Global variables and extensions for all 
 * jquery upload areas.
 */
 /*
$root = realpath((getenv('DOCUMENT_ROOT') && ereg('^'.preg_quote(realpath(getenv('DOCUMENT_ROOT'))), realpath(__FILE__))) ? getenv('DOCUMENT_ROOT') : str_replace(dirname(@$_SERVER['PHP_SELF']), '', str_replace(DIRECTORY_SEPARATOR, '/', dirname(__FILE__))));
$db_host = "localhost";
$db_password = "57575757aA";
// Connects to your Database
if($root == "/home2/cj3wilso/public_html/dev_best"){
	$db_username = $db_name = "cj3wilso_rentdev";
}else{
	$db_username = $db_name = "cj3wilso_rent254";
}
$con = mysql_connect($db_host, $db_username, $db_password) or die(mysql_error('Cannot connect as database user')); 
mysql_select_db($db_name) or die(mysql_error('Cannot select database')); 
*/
$options = array (
	'db_host' => 'localhost',
	'db_user' => 'cj3wilso_rent254',
	'db_pass' => '57575757aA',
	'db_name' => 'cj3wilso_rent254',
	'db_table' => 'prop_photos'
);
$db = new mysqli(
 	$options['db_host'],
  	$options['db_user'],
 	$options['db_pass'],
  	$options['db_name']
);

if($_POST['action']=="updateRecordsListings"){
	$updateRecordsArray = $_POST['recordsArray'];
	$listingCounter = 1;
	foreach ($updateRecordsArray as $recordIDValue) {
		$sql = 'UPDATE `'.$options['db_table'].
		'` SET `p_order` = ? WHERE `id` = ?';
		$query = $db->prepare($sql);
		$query->bind_param(
			'ii', 
			$listingCounter, 
			$recordIDValue
		);
		$query->execute();
		$listingCounter = $listingCounter + 1;
	}
}