<?php

// makes sure they filled it in
if(!$_POST['email'] | !$_POST['pass']) {
 	$hasError = true;
	$message = 'You did not fill in a required field.';
}

// checks if super admin
if (strcasecmp($_POST['email'], $companyEmail) == 0) {
 	setcookie("admin", "yes", time() + (30 * 24 * 60 * 60)); 
}else{
	setcookie("admin", "no", time() + (30 * 24 * 60 * 60)); 
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
			$message = 'Incorrect password, please try again.';
		}
		
		if (!isset($hasError)){ 
			// if login is ok then we add a cookie 
			$_POST['email'] = stripslashes($_POST['email']); 
			if (isset($_POST['remember'])){
				$logtime = 30 * 24 * 60 * 60;
			}else{
				$logtime = 3600;
			}
			$_SESSION['ID_my_site']=$myID;
			$_SESSION['LAST_ACTIVITY'] = time();
			$_SESSION['LOG_TIME'] = $logtime;
			
			//Save faves from session to user id
			$session_fav = mysql_query("SELECT id, id_prop FROM user_fav WHERE id_session = '".$_COOKIE["fav"]."'")or die(mysql_error());
			while($result = mysql_fetch_array( $session_fav )){
				//Delete session ones that already exist for user
				$already_exists = mysql_query("SELECT id_prop 
				FROM user_fav 
				WHERE id_prop ='".$result['id_prop']."' AND id <> ".$result['id'])
				or die(mysql_error());
				if(mysql_num_rows($already_exists) != 0){
					mysql_query("DELETE FROM user_fav WHERE id_prop='".$result['id_prop']."' AND id = ".$result['id']);
				}
				
			}
			//Update session to user id
			mysql_query("UPDATE user_fav 
			SET id_user='".$_SESSION['ID_my_site']."' 
			WHERE id_session='".$_COOKIE["fav"]."'");
			//Update saved search too
			mysql_query("UPDATE user_search 
			SET id_user='".$_SESSION['ID_my_site']."' 
			WHERE id_session='".$_COOKIE["fav"]."'");
			
			// Closes connection
			include '_inc/mysqlclose.php';
			
			//Then let user know it was a success
			$hasSubmit = true;
			$submessage = "You are signed in."; 
		
			//Then redirect to the members area or the redirect URL
			if($myID){
				if (isset($_POST['redirect'])){
					$redirect = $_POST['redirect'];
					header("Location: $redirect");
				}else if (isset($_GET['redirect'])){
					$redirect = $_GET['redirect'];
					header("Location: $redirect"); 
				}else{
					header("Location: $adminHome"); 
				}
			}else{
				echo 'Error logging in.';
			}
		} 
 	}
}
?>