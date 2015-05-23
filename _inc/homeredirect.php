<?php
//Checks if there is a login cookie and redirects to homepage
//For Login/Register Pages
 if(isset($_SESSION['ID_my_site']))
 //if there is, it logs you in and directes you to the home page
 { 
 	if (isset($_GET["redirect"])){
		$redirect = $_GET["redirect"];
		header("Location: $redirect");
	}else{
		header("Location: $adminHome");
	}
 }
?>