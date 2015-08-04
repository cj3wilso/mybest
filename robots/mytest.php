<?php
echo 'hello'.'<br>';

$myaddress = "33 2e Avenue, Verdun, QC H4G 2W2";

$reg_street = "[0-9]+[a-z]?[ ][0-9]?[a-z ]+";
$reg_city = "[a-z /]+";
$reg_prov_abv = "([A-Z]{2})*";
$reg_comma = ", ";
$reg_space = " ";
$reg_postal = "[A-Z][0-9][A-Z]([ ]?[0-9][A-Z][0-9])?";
$match_pattern3 = $reg_street.$reg_comma.$reg_city.$reg_comma.$reg_prov_abv.$reg_space.$reg_postal;
if (preg_match("#^$match_pattern3$#i", $myaddress, $matches)){
	echo $matches[0].'<br>';
	
	/*
	$address_all = explode(",", $matches[0]);
	$provpost = explode(' ', trim($address_all[2]), 2);
	print_r($provpost);
	*/
}
?>