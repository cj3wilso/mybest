<?php 
$page = "register";
$pageTitle = "Register";
$headStyles='<style>
.form-signin {
    background-color: #FFFFFF;
    border: 1px solid #E5E5E5;
    border-radius: 5px 5px 5px 5px;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    margin: 0 auto 20px;
    max-width: 300px;
    padding: 19px 29px 29px;
}
.form-success{
	border-color:#1abc9c !important;
	border:3px solid #1abc9c;
}
.form-error{
	border-color:#bc291a !important;
	border:3px solid #bc291a;
}
</style>';
include '_inc/global.php';
include '_inc/homeredirect.php';

//if form is submitted 
if (isset($_POST['register'])) { 
	include '_inc/mysqlconnect.php';
	include("form_register.php");
	include '_inc/mysqlclose.php';
}
include("_inc/header.php");
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
        <h4>Register for <small>My Best Apartments</small></h4>
      </div>
    </div>
    <div class="login-form col-md-9">
      <form id="register" method="post">
        <div class="form-group">
          <input name="u_email" type="email" class="form-control login-field" value="" placeholder="Enter your email" id="login-name">
          <label class="login-field-icon fui-user" for="login-name"></label>
        </div>
        <div class="form-group">
          <input name="u_pass" type="password" class="form-control login-field" value="" placeholder="Password" id="login-pass">
          <label class="login-field-icon fui-lock" for="login-pass"></label>
        </div>
        <div class="form-group">
          <input name="u_pass_c" type="password" class="form-control login-field" value="" placeholder="Confirm Password" id="login-pass-con">
          <label class="login-field-icon fui-lock" for="login-pass"></label>
        </div>
        <label class="checkbox">
          <input type="checkbox" name="updates" data-toggle="checkbox" value="1" checked> Receive updates about website (Infrequent but let's you know about latest features)
        </label>
        <button name="register" class="btn btn-primary btn-lg btn-block" type="submit">Register</button>
        <div class="login-link"> <a href="<?php echo $adminLogin; ?>">Log In</a> | <a href="<?php echo $adminForgot; ?>">Lost your password?</a> </div>
      </form>
    </div>
  </div>
</div>
<?php 
include '_inc/footer.php';
include 'footer_js.php';
?>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.11.1/jquery.validate.min.js"></script> 
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.11.1/additional-methods.min.js"></script> 
<script type="text/javascript">
$(document).ready(function(){
	$("#register").validate({
		rules:{
			u_email:{
				required:true,
				email: true
			},
			u_pass:"required",
			u_pass_c:"required"
		},
		messages:{
			u_email:{
				required:"",
				email:""
			},
			u_pass:"",
			u_pass_c:""
		},
		highlight:function(element, errorClass, validClass) {
			$(element).removeClass('form-success');
			$(element).addClass('form-error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).removeClass('form-error');
			$(element).addClass('form-success');
		}
	});
});
</script>
</body></html>