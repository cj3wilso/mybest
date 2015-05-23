<?php
//If the form is submitted
require("global.php");
$emailTo = $companyEmail;
$dsubject = "Feedback for $company";
	
//Check to make sure sure that a valid email address is submitted
if (!filter_var($_POST['demail'], FILTER_VALIDATE_EMAIL)) {
	$hasError = true;
} else {
	$emailFrom = trim($_POST['demail']);
}

//Check to make sure comments were entered
if(trim($_POST['dcomment']) == '') {
	$hasError = true;
} else {
	if(function_exists('stripslashes')) {
		$dcomment = stripslashes(trim($_POST['dcomment']));
	} else {
		$dcomment = trim($_POST['dcomment']);
	}
}

$dname = trim($_POST['dname']);

//If there is no error, send the email
if(!isset($hasError)) {
	$dbody = "Name: $dname \n\nEmail: $emailFrom \n\nMessage:\n $dcomment";
	$dheaders = 'From: My Best Apartments <'.$emailTo.'>' . "\r\n" . 'Reply-To: ' . $emailFrom;
	if (mail($emailTo, $dsubject, $dbody, $dheaders) ) {
		$emailSent = true;
	} else {
		$emailSent = false;
	}
}
?>
<!-- Message -->
<?php if(isset($hasError)) { //If errors are found ?>
<div class="alert alert-danger">Please check if you've filled all the fields with valid information. Thank you.</div>
<?php } ?>
<?php if(isset($emailSent) && $emailSent == true) { //If email is sent ?>
<div class="alert alert-success"><strong>Email Successfully Sent!</strong></div>
<?php } ?>