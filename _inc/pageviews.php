<?php
require('class.gapi.php');

/* Define variables */
/* Unlock account for first time use 
https://accounts.google.com/DisplayUnlockCaptcha */
$ga_email = 'cj3wilso@gmail.com';
/* Two step auth application password */
$ga_password = 'pyisjetwsaplrjtr';
//$ga_password = 'ldkthoqnpwhhgdal';
$ga_profile_id = '70390211';
$ga_url = $_SERVER['REQUEST_URI'];

/* Create a new Google Analytics request and pull the results */
$ga = new gapi($ga_email,$ga_password);
?>   

<?php
$ga->requestReportData($ga_profile_id, 'pagePath', array('pageviews'), null, 'pagePath == '.$ga_url,'2013-03-20', date("Y-m-d"));
$results = $ga->getResults();
?>