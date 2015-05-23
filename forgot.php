<?php 
$page = "forgot";
$headStyles='';
include '_inc/global.php';
include '_inc/homeredirect.php';

//if the forget form submitted
if (isset($_POST['retrieve'])) { // if form has been submitted
	
	// makes sure they filled it in
 	if(!$_POST['email']) {
		$hasError = true;
		$message = 'You need to enter your email address.';
 	}

 	// checks it against the database
 	if (!get_magic_quotes_gpc()) {
 		$_POST['email'] = addslashes($_POST['email']);
 	}
	
	if($hasError != true){
		include '_inc/mysqlconnect.php';
		$check = mysql_query("SELECT * FROM users WHERE email = '".$_POST['email']."'")or die(mysql_error());
		//Gives error if user doesn't exist
		$check2 = mysql_num_rows($check);
		if ($check2 == 0) {
			$hasError = true;
			$message = 'Email does not exist, <a href="$adminForgot">try again</a> or <a href="$adminRegister">click here to register</a>.';
		}
		
		if($hasError != true){
			while($info = mysql_fetch_array( $check )) 	{
				$_POST['email'] = stripslashes($_POST['email']); 
				function generate_random_letters($length) {
					$random = '';
					for ($i = 0; $i < $length; $i++) {
						$random .= chr(rand(ord('a'), ord('z')));
					}
					return $random;
				}
				$password = generate_random_letters(6);
				$encript = md5($password);
		 
				//send email
				$emailTo = $_POST['email']; //Who are we sending email to?
				$subject = "Password retrieval for $company";
				$body = "Your new password is: $password \n\nGo to http://www.mybestapartments.ca$adminLogin?redirect=reset to login.";
				$headers = "From: $company <".$companyEmail.">" . "\r\n" . "Reply-To: $companyEmail";
				mail($emailTo, $subject, $body, $headers);
				$emailSent = true;
			
				//reset password
				mysql_query("update users set pwd='$encript' where email = '".$_POST['email']."'");
		
				//Then let user know it was a success
				$hasSubmit = true;
				$submessage = "Check your email. Your password has been sent to you!";
				
				// Closes connection
				include '_inc/mysqlclose.php';
			} 
		}
	}
} 	 

include '_inc/header.php';
if(isset($hasError)) { 
	echo '<div class="alert alert-error">'.$message.'</div>'; 
}
if(isset($hasSubmit)) { 
	echo '<div class="alert alert-success">'.$submessage.'</div>'; 
}
?>
<div class="login-screen">
  <div class="row">
    <div class="col-md-3">
      <div class="login-icon">
        <h4><small>Forgot password?</small></h4>
      </div>
    </div>
    <div class="login-form col-md-9">
      <form method="post" class="form-signin">
        <div class="form-group">
          <input name="email" type="text" class="form-control login-field" value="" placeholder="Enter your email" id="login-name">
          <label class="login-field-icon fui-user" for="login-name"></label>
        </div>
        <button name="retrieve" class="btn btn-primary btn-lg btn-block" type="submit">Retrieve Password</button>
        <div class="login-link"> <a href="<?php echo $adminLogin; ?>">Login</a> | <a href="<?php echo $adminRegister; ?>">Register</a> </div>
      </form>
    </div>
  </div>
</div>
<?php 
include '_inc/footer.php';
include 'footer_js.php';
?>
</body>
</html>