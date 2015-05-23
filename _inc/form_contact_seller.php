<?php
//If the form is submitted
require("global.php");
	if(!isset($contactEmail)){
		$prop = $_POST["prop"];
		require("mysqlconnect.php");
		$info = mysql_fetch_array(mysql_query("SELECT * FROM properties WHERE id_pg='$prop'"));
		if( $info['email'] == "" ){
			$info['email'] = "cj3wilso@gmail.com";//$companyEmail
			$page = "Page: ".$_POST["page"];
		}else{
			$page = "";
		}
		$contactEmail = $info['email'];
		require("mysqlclose.php");
	}
	$dsubject = "Contact from $company";
	if($info['where_posted']==""){
		$url = "http://$domain/apartment/".$info['prov'].'/'.urlencode($info['city']).'/'.urlencode($info['name']).'/'.$info['id_pg'];
	}else{
		$url = $info['where_posted'];
	}
	
	//Check to make sure that the name field is not empty
	if(trim($_POST['dname']) == '') {
		$hasError = true;
	} else {
		$dname = trim($_POST['dname']);
	}
	
	//Check to make sure sure that a valid email address is submitted
	if(trim($_POST['demail']) == '')  {
		$hasError = true;
	} else if (!filter_var($_POST['demail'], FILTER_VALIDATE_EMAIL) ) {
		$hasError = true;
		echo 'not valid';
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
?>
<!-- Message -->
      <?php if(isset($hasError)) { //If errors are found ?>
      <div class="alert alert-danger">Please check if you've filled all the fields with valid information. Thank you.</div>
      <?php } ?>
      <?php if(isset($emailSent) && $emailSent == true) { //If email is sent ?>
      <div class="alert alert-success"><strong>Email Successfully Sent!</strong></div>
      <?php } ?>