<?php
//if the signin form is submitted 
if (isset($_POST['signin'])) { 
	// makes sure they filled it in
	if(!$_POST['email'] | !$_POST['pass']) {
 		$hasError = true;
		$message = 'You did not fill in a required field.';
 	}
 	// checks if super admin
 	if (strcasecmp($_POST['email'], $companyEmail) == 0) {
 		setcookie(admin, "yes", time() + (30 * 24 * 60 * 60)); 
 	}else{
		setcookie(admin, "no", time() + (30 * 24 * 60 * 60)); 
	}
	// checks it against the database
 	if (!get_magic_quotes_gpc()) {
 		$_POST['email'] = addslashes($_POST['email']);
 	}
 	if (!isset($hasError)){ 
		// Connects to your Database 
		include '_inc/mysqlconnect.php';
		$check = mysql_query("SELECT * FROM users WHERE email = '".$_POST['email']."'")or die(mysql_error());
		//Gives error if user doesn't exist
		$check2 = mysql_num_rows($check);
		// Closes connection
		include '_inc/mysqlclose.php';
		if ($check2 == 0) {
			$hasError = true;
			$message = 'That email does not exist in our database. Register an account below.';
		}
		
	}

 	if (!isset($hasError)){ 
	while($info = mysql_fetch_array( $check )) 	
 	{
		$myID = $info['id'];
		$_POST['pass'] = stripslashes($_POST['pass']);
		$info['pwd'] = stripslashes($info['pwd']);
		$_POST['pass'] = md5($_POST['pass']);
	
		//Gives error if the password is wrong
		if ($_POST['pass'] != $info['pwd']) {
			$hasError = true;
			$message = $_POST['pass'].' | '.$info['pwd'].'Incorrect password, please try again.';
		}
		
		/*
		if ($info['approved'] == 0) {
			$hasError = true;
			$message = 'Your account has not been approved yet. An administrator will get back with your shortly.';
		}
		*/
		
		if (!isset($hasError)){ 
			// if login is ok then we add a cookie 
			$_POST['email'] = stripslashes($_POST['email']); 
			if ($_POST['remember'] == 'yes'){
				$logtime = time() + (30 * 24 * 60 * 60);
			}else{
				$logtime = time() + 3600;
			}
			$_SESSION['ID_my_site']=$myID;
			$_SESSION['LAST_ACTIVITY'] = time();
			$_SESSION['LOG_TIME'] = $logtime; 
			
			//Then let user know it was a success
			$hasSubmit = true;
			$submessage = "You are signed in."; 
		
			//Then redirect to the members area or the redirect URL
			if ($_POST['redirect'] != ""){
				$redirect = $_POST['redirect'];
				header("Location: $redirect");
			}else{
				header("Location: $home"); 
			}
		} 
 	}
	}
}
?>