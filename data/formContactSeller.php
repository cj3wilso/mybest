<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json; charset=UTF-8");

echo 'is this working?? ';
print_r($_POST);

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
print_r($request);
$email = $request->contact->email;
$page = $request->contact->page;
$prop = trim($request->contact->prop);
$dname = trim($request->contact->dname);
$emailFrom = trim($request->contact->demail);
if(function_exists('stripslashes')) {
	$dcomment = stripslashes(trim($request->contact->dcomment));
} else {
	$dcomment = trim($request->contact->dcomment);
}

echo 'is this working?? '.$prop;

//If the form is submitted
require("global.php");
	require("mysqlconnect.php");
	$info = mysql_fetch_array(mysql_query("SELECT * FROM properties WHERE id_pg='$prop'"));
	if( $info['email'] == "" ){
		$info['email'] = "cj3wilso@gmail.com";//$companyEmail
	}else{
	}
	$contactEmail = $info['email'];
	require("mysqlclose.php");
	$dsubject = "Contact from $company";
	if($info['where_posted']==""){
		$url = "http://$domain/apartment/".$info['prov'].'/'.urlencode($info['city']).'/'.urlencode($info['name']).'/'.$info['id_pg'];
	}else{
		$url = $info['where_posted'];
	}
	
	//Check to make sure that the name field is not empty
	if($dname == '') {
		$hasError = true;
	}
	
	//Check to make sure sure that a valid email address is submitted
	if($emailFrom == '')  {
		$hasError = true;
	} else if (!filter_var($emailFrom, FILTER_VALIDATE_EMAIL) ) {
		$hasError = true;
		echo 'not valid';
	}

	//Check to make sure comments were entered
	if($dcomment == '') {
		$hasError = true;
	}

	//If there is no error, send the email
	if(!isset($hasError)) {
		$emailTo = $contactEmail; //Who are we emailing?
		$dbody = $info['name']."<br><br>Name: $dname <br><br>Email: $emailFrom <br><br>Message:<br> $dcomment <br><br>URL:".$url."<br><br>$page";
		//$dheaders = 'From: My Best Apartments <'.$emailTo.'>' . "\r\n" . 'Reply-To: ' . $emailFrom. "\r\n";
		$dheaders  = "From: $company <$companyEmail>\r\n" .
		"X-Mailer: php\r\n";
		$dheaders .= "MIME-Version: 1.0\r\n";
		$dheaders .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		$dheaders .= "Reply-To: $emailFrom\r\n";
		if (mail($emailTo, $dsubject, $dbody, $dheaders) ) {
		   $emailSent = true;
		} else {
		   $emailSent = false;
		}
		//Send me real sellers emails - let's see how often they are contacted
		if($info['where_posted']==""){
			mail("cj3wilso@gmail.com", "User from $company", "EMAIL FOR REAL USER <br><br>".$dbody, $dheaders);
		}
			
	}

if(isset($hasError)) { //If errors are found 
    echo "Please check if you've filled all the fields with valid information. Thank you.";
} 
if(isset($emailSent) && $emailSent == true) { //If email is sent 
    echo "Email Successfully Sent!";
} 
echo $prop;
?>