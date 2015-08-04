<?php
echo 'hello'.'<br>';

$address = "38 dan leckie way, m5v2v6, Toronto, ON";

$reg_street = "[0-9]+[a-z]?[ ][0-9]?[a-z ]+";
$reg_city = "[a-z /]+";
$reg_prov_abv = "([A-Z]{2})*";
$reg_comma = ", ";
$reg_space = " ";
$reg_postal = "[A-Z][0-9][A-Z]([ ]?[0-9][A-Z][0-9])?";
$match_pattern3 = $reg_street.$reg_comma.$reg_postal.$reg_comma.$reg_city.$reg_comma.$reg_prov_abv;
if (preg_match("#^$match_pattern3$#i", $address, $matches)){
	echo $matches[0].'<br>';
	
	/*
	$address_all = explode(",", $matches[0]);
	$provpost = explode(' ', trim($address_all[2]), 2);
	print_r($provpost);
	*/
}
?>