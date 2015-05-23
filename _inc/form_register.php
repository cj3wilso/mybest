<?php
//This makes sure they did not leave any fields blank
if (!$_POST['u_email'] | !$_POST['u_pass'] | !$_POST['u_pass_c'] ) {
	$hasError = true;
	$message = 'You did not complete all of the required fields.';
}
if($hasError != true){
	//Add user to MailChimp if subscribed
	if(isset($_POST['updates'])){
		$subscribe_url = "https://us6.api.mailchimp.com/2.0/lists/subscribe";
		$email_struct = new StdClass();
		$email_struct->email = $_POST['u_email'];
		//print_r($email_struct);
	
		//API is in general settings
		//ID for list is found on form pages - check source code and look for hidden field ID
		$parameters = array(
			'apikey' => 'abecf4899d158edd4f2805ca14adb060-us6',
			'id' => 'e6adec2ee0',
			'email' => $email_struct,
			'double_optin' => false,
			'send_welcome' => false
		);

		$curl = curl_init($subscribe_url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($parameters));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($curl);
		//echo $response;
	}
	
	// checks if the email is in use
	if (!get_magic_quotes_gpc()) {
 		$_POST['u_email'] = addslashes($_POST['u_email']);
 	}
 	$usercheck = $_POST['u_email'];
 	$check = mysql_query("SELECT email FROM users WHERE email = '$usercheck'")or die(mysql_error());
 	$check2 = mysql_num_rows($check);

 	//if the email exists it gives an error
	if ($check2 != 0) {
 		$hasError = true;
		$message = 'Sorry, the email '.$_POST['u_email'].' is already in use.';
 	}

 	// this makes sure both passwords entered match
 	if ($_POST['u_pass'] != $_POST['u_pass_c']) {
 		$hasError = true;
		$message = 'Your passwords did not match.';
 	}

 	// here we encrypt the password and add slashes if needed
 	$_POST['u_pass'] = md5($_POST['u_pass']);
 	if (!get_magic_quotes_gpc()) {
 		$_POST['u_pass'] = addslashes($_POST['u_pass']);
 		$_POST['u_email'] = addslashes($_POST['u_email']);
 	}
		
	//If there is no error do form
	if($hasError != true){
		
		//Insert new user into the database (with approved set to 0 so they can't login)
		$registered = date(c);
		$reg_ip = $_SERVER['REMOTE_ADDR'];
		$insert = "INSERT INTO users (email, pwd, type, registered, reg_ip) VALUES ('".$_POST['u_email']."', '".$_POST['u_pass']."', 1, '$registered', '$reg_ip')";
		$add_member = mysql_query($insert);
		$check = mysql_query("SELECT id FROM users WHERE email = '".$_POST['u_email']."'");
		while($info = mysql_fetch_array( $check )) 	{
			$logtime = time() + 3600;
			$myID = $info['id'];
			$_SESSION['ID_my_site']=$myID;
			$_SESSION['LAST_ACTIVITY'] = time();
			$_SESSION['LOG_TIME'] = $logtime;
		}
		//Email the administrator to approve/deny the account
		$emailTo = $_POST['u_email']; //Who are we sending email to?
		$subject = "You have registered for ".$company." admin account.";
		$body = "You have registered with email: ".$_POST['u_email']." to ".$company.".";
		$headers = 'From: '.$company.' <'.$companyEmail.'>' . "\r\n" . 'Reply-To: '.$companyEmail;
		mail($emailTo, $subject, $body, $headers);
		$emailSent = true;
		
		if ($_SERVER['PHP_SELF']=="/register.php"){
			header("Location: $adminHome"); 
		}
	}
}
?>