<?php 
$page = "signin";
$pageTitle="Login";
$headStyles='<style>
.form-signin {
    background-color: #FFFFFF;
    border: 1px solid #E5E5E5;
    border-radius: 5px 5px 5px 5px;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    margin: 0 auto 20px;
    max-width: 300px;
    padding: 19px 29px 29px;
}</style>';
include '_inc/global.php';
//include '_inc/homeredirect.php';

//if the signin form is submitted 
if (isset($_POST['signin'])) { 
	include("_inc/form_signin.php");
}
include("header.php");
 ?>
<?php 
if(isset($hasError)) { 
	echo '<div class="alert alert-warning">'.$message.'</div>'; 
}
?>

<div class="login-screen">
<div class="row">
          <div class="col-md-3">
          <div class="login-icon">
            <h4>Welcome to <small>My Best Apartments</small></h4>
          </div>
          </div>

          <div class="login-form col-md-9">
            <form method="post">
            <div class="form-group">
              <input name="email" type="text" class="form-control login-field" value="" placeholder="Enter your email" id="login-name">
              <label class="login-field-icon fui-user" for="login-name"></label>
            </div>

            <div class="form-group">
              <input name="pass" type="password" class="form-control login-field" value="" placeholder="Password" id="login-pass">
              <label class="login-field-icon fui-lock" for="login-pass"></label>
            </div>
            <label class="checkbox">
          <input type="checkbox" name="remember" data-toggle="checkbox" value="yes" /> Remember me
        </label>

            <button name="signin" class="btn btn-primary btn-lg btn-block" type="submit">Login</button>
        <div class="login-link">
        <a href="<?php echo $adminRegister; ?>">Register</a> | 
        <a href="<?php echo $adminForgot; ?>">Lost your password?</a>
         </div>
         </form>
          </div>
        </div>
        </div>
  
      
<?php 
include 'footer.php'; 
include 'footer_js.php';
?>
</body>
</html>