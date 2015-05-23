<?php 
$page = "reset";
$headStyles='';
include '_inc/global.php';
include("loggedin.php");

if (isset($_POST['reset'])) { 
	//This makes sure they did not leave any fields blank
	 if (!$_POST['old'] | !$_POST['pass'] | !$_POST['pass2'] ) {
			$hasError = true;
			$message = "You did not complete all of the required fields.";
	 }
	// this makes sure both passwords entered match
	if ($_POST['pass'] != $_POST['pass2']) {
		$hasError = true;
		$message = "Your passwords did not match.";
	}
	if($hasError != true){
		$encriptold = md5($_POST['old']);
		if (!get_magic_quotes_gpc()) {
			$encriptold = addslashes($encriptold);
		}
		include '_inc/mysqlconnect.php';
		$check = mysql_query("SELECT * FROM users WHERE pwd= '$encriptold'")or die(mysql_error());
	 
		//Gives error if password doesn't exist
		$check2 = mysql_num_rows($check);
		if ($check2 == 0) {
			$hasError = true;
			$message = "Password does not exist, <a href='$adminReset'>try again</a> or get your <a href='$adminForgot'>password emailed to you</a>.";
		}
				
		if($hasError != true){
			while($info = mysql_fetch_array( $check )) 	{	
				$encriptnew = md5($_POST['pass']);
				if (!get_magic_quotes_gpc()) {
					$encriptnew = addslashes($encriptnew);
				}
				//reset password
				mysql_query("update users set pwd='$encriptnew' where email = '".$info['email']."';");
				// Closes connection
				include '_inc/mysqlclose.php';
				header("Location: $adminHome"); 
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
        <h4><small>Reset password</small></h4>
      </div>
    </div>
    <div class="login-form col-md-9">
      <form method="post" class="form-signin">
        <div class="form-group">
          <input name="old" type="password" class="form-control login-field" value="" placeholder="Old password" id="login-name">
          <label class="login-field-icon fui-user" for="login-name"></label>
        </div>
        <div class="form-group">
          <input name="pass" type="password" class="form-control login-field" value="" placeholder="New password" id="new-pwd">
          <label class="login-field-icon fui-lock" for="new-pwd"></label>
        </div>
        <div class="form-group">
          <input name="pass2" type="password" class="form-control login-field" value="" placeholder="Confirm password" id="conf-pwd">
          <label class="login-field-icon fui-lock" for="conf-pwd"></label>
        </div>
        <button name="reset" class="btn btn-primary btn-lg btn-block" type="submit">Confirm Password</button>
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