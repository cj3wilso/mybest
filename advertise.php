<?php
$page = "advertise";
$pageTitle = "Advertise Your Rental Today";
$metaDesc = "Post your apartment rental with us today for free.";
include("global.php");
//This code runs if the form has been submitted
if (isset ($_POST['submit'])) { 
	
	include '_inc/mysqlconnect.php';
	
	$p_name = mysql_real_escape_string($_POST[p_name]);
	$address = mysql_real_escape_string($_POST['p_address']);
	$p_address2 = mysql_real_escape_string($_POST[p_address2]);
	$p_phone1 = mysql_real_escape_string($_POST[p_phone1]);
	$p_phone2 = mysql_real_escape_string($_POST[p_phone2]);
	$p_phone3 = mysql_real_escape_string($_POST[p_phone3]);
	$p_email = mysql_real_escape_string($_POST[p_email]);
	$p_url = mysql_real_escape_string($_POST[p_url]);
	$d_desc = mysql_real_escape_string($_POST[d_desc]);
	$SundayFromH = mysql_real_escape_string($_POST['hours'][SundayFromH]);
	$SundayFromM = mysql_real_escape_string($_POST['hours'][SundayFromM]);
	$SundayFromAP = mysql_real_escape_string($_POST['hours'][SundayFromAP]);
	$SundayToH = mysql_real_escape_string($_POST['hours'][SundayToH]);
	$SundayToM = mysql_real_escape_string($_POST['hours'][SundayToM]);
	$SundayToAP = mysql_real_escape_string($_POST['hours'][SundayToAP]);
	$MondayFromH = mysql_real_escape_string($_POST['hours'][MondayFromH]);
	$MondayFromM = mysql_real_escape_string($_POST['hours'][MondayFromM]);
	$MondayFromAP = mysql_real_escape_string($_POST['hours'][MondayFromAP]);
	$MondayToH = mysql_real_escape_string($_POST['hours'][MondayToH]);
	$MondayToM = mysql_real_escape_string($_POST['hours'][MondayToM]);
	$MondayToAP = mysql_real_escape_string($_POST['hours'][MondayToAP]);
	$TuesdayFromH = mysql_real_escape_string($_POST['hours'][TuesdayFromH]);
	$TuesdayFromM = mysql_real_escape_string($_POST['hours'][TuesdayFromM]);
	$TuesdayFromAP = mysql_real_escape_string($_POST['hours'][TuesdayFromAP]);
	$TuesdayToH = mysql_real_escape_string($_POST['hours'][TuesdayToH]);
	$TuesdayToM = mysql_real_escape_string($_POST['hours'][TuesdayToM]);
	$TuesdayToAP = mysql_real_escape_string($_POST['hours'][TuesdayToAP]);
	$WednesdayFromH = mysql_real_escape_string($_POST['hours'][WednesdayFromH]);
	$WednesdayFromM = mysql_real_escape_string($_POST['hours'][WednesdayFromM]);
	$WednesdayFromAP = mysql_real_escape_string($_POST['hours'][WednesdayFromAP]);
	$WednesdayToH = mysql_real_escape_string($_POST['hours'][WednesdayToH]);
	$WednesdayToM = mysql_real_escape_string($_POST['hours'][WednesdayToM]);
	$WednesdayToAP = mysql_real_escape_string($_POST['hours'][WednesdayToAP]);
	$ThursdayFromH = mysql_real_escape_string($_POST['hours'][ThursdayFromH]);
	$ThursdayFromM = mysql_real_escape_string($_POST['hours'][ThursdayFromM]);
	$ThursdayFromAP = mysql_real_escape_string($_POST['hours'][ThursdayFromAP]);
	$ThursdayToH = mysql_real_escape_string($_POST['hours'][ThursdayToH]);
	$ThursdayToM = mysql_real_escape_string($_POST['hours'][ThursdayToM]);
	$ThursdayToAP = mysql_real_escape_string($_POST['hours'][ThursdayToAP]);
	$FridayFromH = mysql_real_escape_string($_POST['hours'][FridayFromH]);
	$FridayFromM = mysql_real_escape_string($_POST['hours'][FridayFromM]);
	$FridayFromAP = mysql_real_escape_string($_POST['hours'][FridayFromAP]);
	$FridayToH = mysql_real_escape_string($_POST['hours'][FridayToH]);
	$FridayToM = mysql_real_escape_string($_POST['hours'][FridayToM]);
	$FridayToAP = mysql_real_escape_string($_POST['hours'][FridayToAP]);
	$SaturdayFromH = mysql_real_escape_string($_POST['hours'][SaturdayFromH]);
	$SaturdayFromM = mysql_real_escape_string($_POST['hours'][SaturdayFromM]);
	$SaturdayFromAP = mysql_real_escape_string($_POST['hours'][SaturdayFromAP]);
	$SaturdayToH = mysql_real_escape_string($_POST['hours'][SaturdayToH]);
	$SaturdayToM = mysql_real_escape_string($_POST['hours'][SaturdayToM]);
	$SaturdayToAP = mysql_real_escape_string($_POST['hours'][SaturdayToAP]);
	
	if ( !isset($_SESSION['ID_my_site']) ){ include("form_register.php"); }
	
	/* PROPERTY INFO */
	// Set Page ID 
	$random_id_length = 5; //set the random id length 
	$rnd_id = crypt(uniqid(rand(),1));  //generate a random id encrypt it and store it in $rnd_id 
	$rnd_id = strip_tags(stripslashes($rnd_id)); //to remove any slashes that might have come 
	$rnd_id = str_replace(".","",$rnd_id); //Removing any . or / and reversing the string 
	$rnd_id = strrev(str_replace("/","",$rnd_id)); 
	$rnd_id = strtolower(substr($rnd_id,0,$random_id_length)); //finally I take the first 5 characters from the $rnd_id 
	
	// Get Geo Coordinates 
	$class_exists = class_exists('geocoder');
	if ($class_exists) {
		list($lat, $lng, $type, $city, $region, $prov, $provLng, $post, $street) = geocoder::getLocationInfo($address);
	}
	//Gives error if address not street address
	if (($type != "STREET" && $type != "ADDRESS") && $class_exists) {
		$hasError = true;
		$message = 'Address must contain a street address or postal code.';
	}
	//CHECK WHETHER GEOCODE IS WORKING
	if ($class_exists) {
		//Gives error if address already exists
		$check = mysql_query("SELECT * FROM properties WHERE lat = '$lat' AND lng = '$lng'");
		if (mysql_num_rows($check) != 0) {
			$hasError = true;
			$message = 'That property address already exists on '.$company.'. Please <a href="#myModal" role="button" data-toggle="modal">sign in</a> to edit your property or call us at '.$companyPhone.' if you think there is a mistake.';
		}
	}
	
	if (!isset($hasError)){ 
		$user_id = (isset($_SESSION['ID_my_site'])) ? $_SESSION['ID_my_site'] : $myID;
		$today = date("M j Y");
		//CHECK WHETHER GEOCODE IS WORKING
		if ($class_exists) {
			$insert_propinfo = "INSERT INTO properties (id_user, id_pg, name, address, address2, city, region, prov, post, phone1, phone2, phone3, email, url, lat, lng, date) VALUES ($user_id, '$rnd_id','".addslashes($p_name)."','".addslashes($street)."','".addslashes($p_address2)."','".addslashes($city)."','".addslashes($region)."','".addslashes($prov)."','$post','$p_phone1','$p_phone2','$p_phone3','$p_email','$p_url', $lat, $lng, '$today') ;";
		}else{
		//GEOCODE NOT WORKING - NEED TO CLEAN UP DATA MANUALLY
			$insert_propinfo = "INSERT INTO properties (id_user, id_pg, name, address, address2, phone1, phone2, phone3, email, url, date) VALUES ($user_id, '$rnd_id','".addslashes($p_name)."','".addslashes($address)."','".addslashes($p_address2)."','$p_phone1','$p_phone2','$p_phone3','$p_email','$p_url', '$today') ;";
		}
		mysql_query($insert_propinfo);
		
		/* UNIT DETAILS */
		// Insert all unit rows
		for($i=0; $i<=$_POST['rowcount']; $i++) {
			if($i==0){$first=TRUE;}else{$first=FALSE;}
			$first==TRUE ? $u_order= $_POST['u_order'][0] : $u_order= mysql_real_escape_string($_POST['u_order' . $i]);
			$first==TRUE ? $u_rent= $_POST['u_rent'][0] : $u_rent= mysql_real_escape_string($_POST['u_rent' . $i]);
			$first==TRUE ? $u_style= $_POST['u_style'][0] : mysql_real_escape_string($u_style= $_POST['u_style' . $i]);
			$first==TRUE ? $u_bed= $_POST['u_bed'][0] : $u_bed= mysql_real_escape_string($_POST['u_bed' . $i]);
			$first==TRUE ? $u_bath= $_POST['u_bath'][0] : $u_bath= mysql_real_escape_string($_POST['u_bath' . $i]);
			$first==TRUE ? $u_sq= $_POST['u_sq'][0] : $u_sq= mysql_real_escape_string($_POST['u_sq' . $i]);
			$first==TRUE ? $u_dep= $_POST['u_dep'][0] : $u_dep= mysql_real_escape_string($_POST['u_dep' . $i]);
			if($i!=0)$comma=",";
			$sql .= $comma." ('".$rnd_id."','".$u_order."','".$u_rent."','".$u_style."','".$u_bed."','".$u_bath."','".$u_sq."','".$u_dep."')";
		}
		$insert_unit = "INSERT INTO prop_units (id_prop,u_order,rent,style,beds,ba,sq_ft,dep) VALUES " . $sql;
		mysql_query($insert_unit);
			
		/* PROPERTY DESCRIPTION */
		$insert_propdesc = "INSERT INTO prop_intro (id_prop, text) VALUES ('$rnd_id','".addslashes($d_desc)."') ;";
		mysql_query($insert_propdesc);
			
		/* APARTMENT FEATURES */
		if( !empty($_POST['inter']) || !empty($_POST['appl']) || !empty($_POST['trans']) || !empty($_POST['tv']) || !empty($_POST['health']) || !empty($_POST['laund']) || !empty($_POST['secur']) || !empty($_POST['lease']) || !empty($_POST['pet']) || !empty($_POST['amenet']) || !empty($_POST['senior']) ){
			$feat ='';
			$comma ='';
			if(!empty($_POST['inter']))foreach ($_POST['inter'] as $key => $value){
			  $feat_title = "Interior Features";
			  $feat .= $comma." ('".$rnd_id."','".$rnd_id.$feat_title.$value."','".$feat_title."','".mysql_real_escape_string($value)."')";
			  $comma = ",";
			}
			if(!empty($_POST['appl']))foreach ($_POST['appl'] as $key => $value){
			  $feat_title = "Appliances";
			  $feat .= $comma." ('".$rnd_id."','".$rnd_id.$feat_title.$value."','".$feat_title."','".mysql_real_escape_string($value)."')";
			  $comma = ",";
			}
			if(!empty($_POST['trans']))foreach ($_POST['trans'] as $key => $value){
			  $feat_title = "Transportation";
			  $feat .= $comma." ('".$rnd_id."','".$rnd_id.$feat_title.$value."','".$feat_title."','".mysql_real_escape_string($value)."')";
			  $comma = ",";
			}
			if(!empty($_POST['tv']))foreach ($_POST['tv'] as $key => $value){
			  $feat_title = "TV &amp; Internet";
			  $feat .= $comma." ('".$rnd_id."','".$rnd_id.$feat_title.$value."','".$feat_title."','".mysql_real_escape_string($value)."')";
			  $comma = ",";
			}
			if(!empty($_POST['health']))foreach ($_POST['health'] as $key => $value){
			  $feat_title = "Health / Outdoor";
			  $feat .= $comma." ('".$rnd_id."','".$rnd_id.$feat_title.$value."','".$feat_title."','".mysql_real_escape_string($value)."')";
			  $comma = ",";
			}
			if(!empty($_POST['laund']))foreach ($_POST['laund'] as $key => $value){
			  $feat_title = "Laundry";
			  $feat .= $comma." ('".$rnd_id."','".$rnd_id.$feat_title.$value."','".$feat_title."','".mysql_real_escape_string($value)."')";
			  $comma = ",";
			}
			if(!empty($_POST['secur']))foreach ($_POST['secur'] as $key => $value){
			  $feat_title = "Parking / Security";
			  $feat .= $comma." ('".$rnd_id."','".$rnd_id.$feat_title.$value."','".$feat_title."','".mysql_real_escape_string($value)."')";
			  $comma = ",";
			}
			if(!empty($_POST['lease']))foreach ($_POST['lease'] as $key => $value){
			  $feat_title = "Lease Options";
			  $feat .= $comma." ('".$rnd_id."','".$rnd_id.$feat_title.$value."','".$feat_title."','".mysql_real_escape_string($value)."')";
			  $comma = ",";
			}
			if(!empty($_POST['pet']))foreach ($_POST['pet'] as $key => $value){
			  $feat_title = "Pets";
			  $feat .= $comma." ('".$rnd_id."','".$rnd_id.$feat_title.$value."','".$feat_title."','".mysql_real_escape_string($value)."')";
			  $comma = ",";
			}
			if(!empty($_POST['amenet']))foreach ($_POST['amenet'] as $key => $value){
			  $feat_title = "Additional Ameneties";
			  $feat .= $comma." ('".$rnd_id."','".$rnd_id.$feat_title.$value."','".$feat_title."','".mysql_real_escape_string($value)."')";
			  $comma = ",";
			}
			if(!empty($_POST['senior']))foreach ($_POST['senior'] as $key => $value){
			  $feat_title = "Senior";
			  $feat .= $comma." ('".$rnd_id."','".$rnd_id.$feat_title.$value."','".$feat_title."','".mysql_real_escape_string($value)."')";
			  $comma = ",";
			}
			$insert_feat = "INSERT INTO prop_feat (id_prop,feat_uniq,type,feat) VALUES " . $feat;
			mysql_query($insert_feat);
		}
		
		/* HOURS OF OPERATION */
		if(!empty($_POST['hours'])){
			$insert_prophours = "INSERT INTO prop_hours (id_prop,SundayFromH, SundayFromM,SundayFromAP,SundayToH,SundayToM,SundayToAP,MondayFromH, MondayFromM,MondayFromAP,MondayToH,MondayToM,MondayToAP,TuesdayFromH, TuesdayFromM,TuesdayFromAP,TuesdayToH,TuesdayToM,TuesdayToAP,WednesdayFromH, WednesdayFromM,WednesdayFromAP,WednesdayToH,WednesdayToM,WednesdayToAP,ThursdayFromH, ThursdayFromM,ThursdayFromAP,ThursdayToH,ThursdayToM,ThursdayToAP,FridayFromH, FridayFromM,FridayFromAP,FridayToH,FridayToM,FridayToAP,SaturdayFromH, SaturdayFromM,SaturdayFromAP,SaturdayToH,SaturdayToM,SaturdayToAP) VALUES ('$rnd_id','".$SundayFromH."','".$SundayFromM."','".$SundayFromAP."','".$SundayToH."','".$SundayToM."','".$SundayToAP."','".$MondayFromH."','".$MondayFromM."','".$MondayFromAP."','".$MondayToH."','".$MondayToM."','".$MondayToAP."','".$TuesdayFromH."','".$TuesdayFromM."','".$TuesdayFromAP."','".$TuesdayToH."','".$TuesdayToM."','".$TuesdayToAP."','".$WednesdayFromH."','".$WednesdayFromM."','".$WednesdayFromAP."','".$WednesdayToH."','".$WednesdayToM."','".$WednesdayToAP."','".$ThursdayFromH."','".$ThursdayFromM."','".$ThursdayFromAP."','".$ThursdayToH."','".$ThursdayToM."','".$ThursdayToAP."','".$FridayFromH."','".$FridayFromM."','".$FridayFromAP."','".$FridayToH."','".$FridayToM."','".$FridayToAP."','".$SaturdayFromH."','".$SaturdayFromM."','".$SaturdayFromAP."','".$SaturdayToH."','".$SaturdayToM."','".$SaturdayToAP."');";
			mysql_query($insert_prophours);
		}
		header("Location: /upload/$rnd_id");
	}
	// Closes connection
	include '_inc/mysqlclose.php';
}
//if the signin form is submitted 
if (isset($_POST['signin'])) { 
	include("form_signin.php");
}
include("header.php");
?>

<div class="page-header">
  <h1>Advertise your rental <small>Part 1 of 2</small></h1>
</div>
<?php 
	if(isset($hasError)) { 
		echo '<div class="alert alert-error">'.$message.'</div>'; 
	}
	if(isset($hasSubmit)) { 
	echo '<div class="alert alert-success">'.$submessage.'</div>'; 
}
	?>
<form id="postrent" method="post">
  <?php
if ( !isset($_SESSION[ID_my_site]) ){
?>
  <h2>Create Account <small>Or <a href="#myModal" role="button" class="btn btn-primary" data-toggle="modal">sign in</a></small></h2>
  <div class="row">
    <div class="col-lg-2">Email <span class="red">*</span></div>
    <div class="col-lg-3"><input class="form-control required" type="email" name="u_email" placeholder="Email" data-placement="bottom"  /></div>
  </div>
  <div class="row">
    <div class="col-lg-2">Password <span class="red">*</span></div>
    <div class="col-lg-3"><input class="form-control required" type="password" name="u_pass" placeholder="Password" minlength="6" data-placement="bottom" /></div>
    <div class="col-lg-2">Confirm Password <span class="red">*</span></div>
    <div class="col-lg-3"><input class="form-control required" type="password" name="u_pass_c" placeholder="Confirm Password" minlength="6" data-rule-equalto="true" data-placement="bottom" /></div>
  </div>
  <?php
}else{
?>
  <a href="<?php echo $adminLogout; ?>?refresh=<?php echo rand(); ?>" class="btn btn-primary pull-right">Log out</a>
  <?php
}
?>
<?php
include 'pg_property.php';
?>
  <div class="row">
    <div class="col-lg-3"><small><span class="red">*</span> Required field</small></div>
    <div class="col-lg-9"><input class="btn pull-right btn-default" id="submit" type="submit" alt="Submit" name="submit" value="Next - Upload Images" /></div>
  </div>
</form>

<!-- Modal -->
<div class="modal fade" id="myModal">
<div class="modal-dialog">
      <div class="modal-content">
  <form method="post">
    <input type="hidden" name="redirect" value="<?php echo $advertise ?>">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Sign in</h4>
      </div>
    <div class="modal-body">
      <div class="row">
        <div class="col-lg-3">Email <span class="red">*</span></div>
        <div class="col-lg-9"><input class="form-control" type="email" name="email" placeholder="Email" data-rule-email="true" data-rule-required="true" /></div>
      </div>
      <div class="row">
        <div class="col-lg-3">Password <span class="red">*</span></div>
        <div class="col-lg-9"><input class="form-control" type="password" name="pass" placeholder="Password" minlength="6" data-rule-required="true" /></div>
      </div>
       <div class="row">
      <div class="col-lg-4"><input type="checkbox" name="remember" value="yes" /> Keep me logged in </div>
      <div class="col-lg-8 pull-right"><a href="<?php echo $adminForgot; ?>">Forgot Password?</a></div>
      </div>
      </div>
    <div class="modal-footer">
      <button class="btn btn-default" data-dismiss="modal">Close</button>
      <button name="signin" class="btn btn-primary">Sign in</button>
    </div>
  </form>
  </div></div>
</div>
<?php
include 'footer.php';
include 'footer_js.php';
?>
</body>
</html>