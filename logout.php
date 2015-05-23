<?php 
//Set Global Variables - So its easier to modify pages
include 'global.php';
session_unset();     // unset $_SESSION variable for the run-time 
session_destroy();   // destroy session data in storage

$lastpage = $_SERVER['HTTP_REFERER'];
$pos1 = strpos($lastpage, "/admin");
$pos2 = strpos($lastpage, "/edit");
if ($pos1 === false && $pos2 === false){
	header("Location: $lastpage");
}else{
	header("Location: $home");
} 
?> 