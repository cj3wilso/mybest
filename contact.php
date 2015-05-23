<?php
$page = "contact";
$pageTitle = "Contact Us";
$metaDesc = "Contact us with any questions about posting an ad, paid advertising or comments about our website.";
include("global.php");
include("header.php");

//If the form is submitted
if(isset($_POST['submit'])) {

	$dphone = trim($_POST['dphone']);
	$durl = trim($_POST['durl']);
	
	//Check to make sure that the name field is not empty
	if(trim($_POST['dname']) == '') {
		$hasError = true;
	} else {
		$dname = trim($_POST['dname']);
	}
	
	//Check to make sure sure that a valid email address is submitted
	if(trim($_POST['demail']) == '')  {
		$hasError = true;
	} else if (!filter_var($_POST['demail'], FILTER_VALIDATE_EMAIL)) {
		$hasError = true;
	} else {
		$emailFrom = trim($_POST['demail']);
	}
	
	//Check to make sure that the subject field is not empty
	if(trim($_POST['dsubject']) == '') {
		$hasError = true;
	} else {
		$dsubject = trim($_POST['dsubject']);
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
		$emailTo = $companyEmail; //Put your own email address here
		$dbody = "Name: $dname \n\nEmail: $emailFrom \n\nPhone: $dphone \n\nURL: $durl  \n\nSubject: $dsubject \n\nComments:\n $dcomment";
		$dheaders = 'From: Design Essence <'.$emailTo.'>' . "\r\n" . 'Reply-To: ' . $emailFrom;

		if (mail($emailTo, $dsubject, $dbody, $dheaders) ) {
		   $emailSent = true;
		} else {
		   $emailSent = false;
		}
	}
}

?>

  <h1>Contact us <small>By email or form</small></h1>
  
<h3>Info</h3>
<div class="row">
  <div class="col-lg-4">
    <address>
    <strong>Email</strong><br>
    <a href="mailto:info@mybestapartments.ca">info@mybestapartments.ca</a>
    </address>
  </div>
</div>

<h3>Form</h3>
<!-- Message -->
<?php if(isset($hasError)) { //If errors are found ?>
<div class="alert alert-error">Please check if you've filled all the fields with valid information. Thank you.</div>
<?php } ?>
<?php if(isset($emailSent) && $emailSent == true) { //If email is sent ?>
<div class="alert alert-success"><strong>Email Successfully Sent!</strong><br />
  Thank you <?php echo $dname;?> for contacting <?php echo $company;?>, we'll be in touch with you soon.</div>
<?php } ?>

<!-- Example row of columns -->
<form id="contactForm" method="post">
  <div class="row form-group">
    <div class="col-lg-2">Your Name <span class="red">*</span></div>
    <div class="col-lg-4"><input class="form-control required" id="cname" type="text" name="dname" minlength="2" data-placement="bottom" /></div>
  </div>
  <div class="row form-group">
    <div class="col-lg-2">Your Email <span class="red">*</span></div>
    <div class="col-lg-4"><input class="form-control email required" id="cemail" type="text" name="demail" data-placement="bottom" /></div>
  </div>
  <div class="row form-group">
    <div class="col-lg-2">Your Phone</div>
    <div class="col-lg-4"><input class="form-control phoneUS" id="cphone" type="text" name="dphone" data-placement="bottom" /></div>
  </div>
  <div class="row form-group">
    <div class="col-lg-2">URL</div>
    <div class="col-lg-4"><input class="form-control url" id="curl" type="text" name="durl" data-placement="bottom" /></div>
  </div>
  <div class="row form-group">
    <div class="col-lg-2">Subject <span class="red">*</span></div>
    <div class="col-lg-4"><input class="form-control required" id="csubject" type="text" name="dsubject" minlength="2" data-placement="bottom" /></div>
  </div>
  <div class="row form-group">
    <div class="col-lg-12">
    Your Message <span class="red">*</span>
    <textarea class="form-control required" id="ccomment" name="dcomment" rows="10" minlength="10" data-placement="bottom"></textarea></div>
  </div>
  <div class="row">
    <div class="col-lg-10"><small><span class="red">*</span> Required field</small></div>
    <div class="col-lg-2"><input class="btn btn-lg btn-primary pull-right" id="submit" type="submit" alt="Submit" name="submit" value="Submit" /></div>
  </div>
</form>
<?php
include 'footer.php'; 
include 'footer_js.php';
?>
<script src="assets/js/jquery.validate.js"></script>
<script type="text/javascript">
<!-- VALIDATE FORM -->
$(document).ready(function() {
   	$("form#contactForm").validate();
});
</script>
</body>
</html>