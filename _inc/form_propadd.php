<?php
$prop=$_SESSION['prop'];
//This code runs if the form has been submitted
if (isset ($_POST['publish']) || isset ($_POST['draft'])) { 
	include '_inc/geocode.can.class.php';
	include '_inc/mysqlconnect.php';
	
	//Remove French accents from city - to avoid duplicates
	$accents = explode(",","ç,æ,œ,á,é,í,ó,ú,à,è,ì,ò,ù,ä,ë,ï,ö,ü,ÿ,â,ê,î,ô,û,å,e,i,ø,u");
	$noaccents = explode(",","c,ae,oe,a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,y,a,e,i,o,u,a,e,i,o,u");
	
	$p_name = mysql_real_escape_string($_POST["name"]);
	$address = mysql_real_escape_string($_POST['streetnumber'])." ".mysql_real_escape_string($_POST['streetaddress']).", ".mysql_real_escape_string($_POST['city']).", ".mysql_real_escape_string($_POST['prov'])." ".mysql_real_escape_string($_POST['post']);
	$p_address2 = mysql_real_escape_string($_POST["address2"]);
	$p_phone1 = mysql_real_escape_string($_POST["phone1"]);
	$p_phone2 = mysql_real_escape_string($_POST["phone2"]);
	$p_phone3 = mysql_real_escape_string($_POST["phone3"]);
	$p_email = mysql_real_escape_string($_POST["email"]);
	$p_url = mysql_real_escape_string($_POST["url"]);
	$d_desc = mysql_real_escape_string($_POST["text"]);
	$SundayFromH = (isset($_POST['hours']["SundayFromH"]) ? mysql_real_escape_string($_POST['hours']["SundayFromH"]) : "");
	$SundayFromM = (isset($_POST['hours']["SundayFromM"]) ? mysql_real_escape_string($_POST['hours']["SundayFromM"]) : "");
	$SundayFromAP = (isset($_POST['hours']["SundayFromAP"]) ? mysql_real_escape_string($_POST['hours']["SundayFromAP"]) : ""); 
	$SundayToH = (isset($_POST['hours']["SundayToH"]) ? mysql_real_escape_string($_POST['hours']["SundayToH"]) : ""); 
	$SundayToM = (isset($_POST['hours']["SundayToM"]) ? mysql_real_escape_string($_POST['hours']["SundayToM"]) : "");
	$SundayToAP = (isset($_POST['hours']["SundayToAP"]) ? mysql_real_escape_string($_POST['hours']["SundayToAP"]) : "");
	$MondayFromH = (isset($_POST['hours']["MondayFromH"]) ? mysql_real_escape_string($_POST['hours']["MondayFromH"]) : ""); 
	$MondayFromM = (isset($_POST['hours']["MondayFromM"]) ? mysql_real_escape_string($_POST['hours']["MondayFromM"]) : "");
	$MondayFromAP = (isset($_POST['hours']["MondayFromAP"]) ? mysql_real_escape_string($_POST['hours']["MondayFromAP"]) : ""); 
	$MondayToH = (isset($_POST['hours']["MondayToH"]) ? mysql_real_escape_string($_POST['hours']["MondayToH"]) : "");
	$MondayToM = (isset($_POST['hours']["MondayToM"]) ? mysql_real_escape_string($_POST['hours']["MondayToM"]) : ""); 
	$MondayToAP = (isset($_POST['hours']["MondayToAP"]) ? mysql_real_escape_string($_POST['hours']["MondayToAP"]) : ""); 
	$TuesdayFromH = (isset($_POST['hours']["TuesdayFromH"]) ? mysql_real_escape_string($_POST['hours']["TuesdayFromH"]) : ""); 
	$TuesdayFromM = (isset($_POST['hours']["TuesdayFromM"]) ? mysql_real_escape_string($_POST['hours']["TuesdayFromM"]) : "");
	$TuesdayFromAP = (isset($_POST['hours']["TuesdayFromAP"]) ? mysql_real_escape_string($_POST['hours']["TuesdayFromAP"]) : ""); 
	$TuesdayToH = (isset($_POST['hours']["TuesdayToH"]) ? mysql_real_escape_string($_POST['hours']["TuesdayToH"]) : ""); 
	$TuesdayToM = (isset($_POST['hours']["TuesdayToM"]) ? mysql_real_escape_string($_POST['hours']["TuesdayToM"]) : ""); 
	$TuesdayToAP = (isset($_POST['hours']["TuesdayToAP"]) ? mysql_real_escape_string($_POST['hours']["TuesdayToAP"]) : "");
	$WednesdayFromH = (isset($_POST['hours']["WednesdayFromH"]) ? mysql_real_escape_string($_POST['hours']["WednesdayFromH"]) : "");
	$WednesdayFromM = (isset($_POST['hours']["WednesdayFromM"]) ? mysql_real_escape_string($_POST['hours']["WednesdayFromM"]) : ""); 
	$WednesdayFromAP = (isset($_POST['hours']["WednesdayFromAP"]) ? mysql_real_escape_string($_POST['hours']["WednesdayFromAP"]) : ""); 
	$WednesdayToH = (isset($_POST['hours']["WednesdayToH"]) ? mysql_real_escape_string($_POST['hours']["WednesdayToH"]) : ""); 
	$WednesdayToM = (isset($_POST['hours']["WednesdayToM"]) ? mysql_real_escape_string($_POST['hours']["WednesdayToM"]) : ""); 
	$WednesdayToAP = (isset($_POST['hours']["WednesdayToAP"]) ? mysql_real_escape_string($_POST['hours']["WednesdayToAP"]) : "");
	$ThursdayFromH = (isset($_POST['hours']["ThursdayFromH"]) ? mysql_real_escape_string($_POST['hours']["ThursdayFromH"]) : ""); 
	$ThursdayFromM = (isset($_POST['hours']["ThursdayFromM"]) ? mysql_real_escape_string($_POST['hours']["ThursdayFromM"]) : ""); 
	$ThursdayFromAP = (isset($_POST['hours']["ThursdayFromAP"]) ? mysql_real_escape_string($_POST['hours']["ThursdayFromAP"]) : "");
	$ThursdayToH = (isset($_POST['hours']["ThursdayToH"]) ? mysql_real_escape_string($_POST['hours']["ThursdayToH"]) : "");
	$ThursdayToM = (isset($_POST['hours']["ThursdayToM"]) ? mysql_real_escape_string($_POST['hours']["ThursdayToM"]) : ""); 
	$ThursdayToAP = (isset($_POST['hours']["ThursdayToAP"]) ? mysql_real_escape_string($_POST['hours']["ThursdayToAP"]) : "");
	$FridayFromH = (isset($_POST['hours']["FridayFromH"]) ? mysql_real_escape_string($_POST['hours']["FridayFromH"]) : ""); 
	$FridayFromM = (isset($_POST['hours']["FridayFromM"]) ? mysql_real_escape_string($_POST['hours']["FridayFromM"]) : ""); 
	$FridayFromAP = (isset($_POST['hours']["FridayFromAP"]) ? mysql_real_escape_string($_POST['hours']["FridayFromAP"]) : "");
	$FridayToH = (isset($_POST['hours']["FridayToH"]) ? mysql_real_escape_string($_POST['hours']["FridayToH"]) : ""); 
	$FridayToM = (isset($_POST['hours']["FridayToM"]) ? mysql_real_escape_string($_POST['hours']["FridayToM"]) : ""); 
	$FridayToAP = (isset($_POST['hours']["FridayToAP"]) ? mysql_real_escape_string($_POST['hours']["FridayToAP"]) : ""); 
	$SaturdayFromH = (isset($_POST['hours']["SaturdayFromH"]) ? mysql_real_escape_string($_POST['hours']["SaturdayFromH"]) : "");
	$SaturdayFromM = (isset($_POST['hours']["SaturdayFromM"]) ? mysql_real_escape_string($_POST['hours']["SaturdayFromM"]) : "");
	$SaturdayFromAP = (isset($_POST['hours']["SaturdayFromAP"]) ? mysql_real_escape_string($_POST['hours']["SaturdayFromAP"]) : "");
	$SaturdayToH = (isset($_POST['hours']["SaturdayToH"]) ? mysql_real_escape_string($_POST['hours']["SaturdayToH"]) : "");
	$SaturdayToM = (isset($_POST['hours']["SaturdayToM"]) ? mysql_real_escape_string($_POST['hours']["SaturdayToM"]) : "");
	$SaturdayToAP = (isset($_POST['hours']["SaturdayToAP"]) ? mysql_real_escape_string($_POST['hours']["SaturdayToAP"]) : "");
	
	//If user not logged in, also submit registration
	if ( !isset($_SESSION['ID_my_site']) ){ include("form_register.php"); }
	
	/* PROPERTY INFO */
	
	// Get Geo Coordinates 
	$aInput = array();
	$aInput['stno'] = trim($_POST['streetnumber']);
	$aInput['addresst'] = trim(ucwords($_POST['streetaddress']));
	$aInput['city'] = trim(str_ireplace($accents, $noaccents, $_POST['city']));
	//City shouldn't end with word 'city'
	$aInput['city'] = str_ireplace( ' city', "", ucwords($aInput['city']) );
	$aInput['prov'] = trim($_POST['prov']);
	$aInput['postal'] = trim(strtoupper($_POST['post']));
	$class_exists = class_exists('GeoCode_ca');
	if ($class_exists) {
		$oGeoCoder = new GeoCode_ca();
		list($lat, $lng, $search, $type, $city, $region, $prov, $provLng, $post, $street) = $oGeoCoder->GetGeo($aInput);
	}
	//Gives error if address not street address
	// route lets something like "222" through
	// && $type != "route"
	if (($type != "notype") && $class_exists) {
	//if (($type != "street_address" && $type != "postal_code") && $class_exists) {
		$hasError = true;
		$message = 'Address must contain a street address or postal code.';
	}
	//CHECK WHETHER GEOCODE IS WORKING
	if ($class_exists) {
		//Gives error if address already exists
		$check = mysql_query("SELECT * FROM properties WHERE lat = '$lat' AND lng = '$lng' WHERE deleted <> 1");
		if (mysql_num_rows($check) != 0) {
			$hasError = true;
			$message = 'That property address already exists on '.$company.'. Please <a href="#myModal" role="button" data-toggle="modal">sign in</a> to edit your property or call us at '.$companyPhone.' if you think there is a mistake.';
		}
	}
	if (!isset($hasError)){ 
		$user_id = (isset($myID)) ? $myID : $_SESSION['ID_my_site'];
		$today = date("M j Y");
		$created = date("c");
		$pub = 0;
		if(isset ($_POST['publish']))$pub = 1;
		//CHECK WHETHER GEOCODE IS WORKING
		if ($class_exists) {
			$insert_propinfo = "INSERT INTO properties (id_user, id_pg, name, address, address2, city, region, prov, post, phone1, phone2, phone3, email, url, lat, lng, date, pub, created) VALUES ($user_id, '$prop','".addslashes($p_name)."','".addslashes($street)."','".addslashes($p_address2)."','".addslashes($city)."','".addslashes($region)."','".addslashes($prov)."','".$aInput['postal']."','$p_phone1','$p_phone2','$p_phone3','$p_email','$p_url', $lat, $lng, '$today', $pub, '$created') ;";
		}else{
		//GEOCODE NOT WORKING - NEED TO CLEAN UP DATA MANUALLY
			$insert_propinfo = "INSERT INTO properties (id_user, id_pg, name, address, address2, phone1, phone2, phone3, email, url, date, pub, created) VALUES ($user_id, '$prop','".addslashes($p_name)."','".addslashes($address)."','".addslashes($p_address2)."','$p_phone1','$p_phone2','$p_phone3','$p_email','$p_url', '$today', $pub, '$created') ;";
			$message = "Property: <br> ".$prop;
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			mail('cj3wilso@gmail.com', 'Geocode not working: '.$prop, $message, $headers);

		}
		
		
		/* UNIT DETAILS */
		// Insert all unit rows
		if(!isset($_POST['rowcount'])){$_POST['rowcount']=0;}
		$sql=$comma="";
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
			$sql .= $comma." ('".$prop."','".$u_order."','".$u_rent."','".$u_style."','".$u_bed."','".$u_bath."','".$u_sq."','".$u_dep."')";
		}
		$insert_unit = "INSERT INTO prop_units (id_prop,u_order,rent,style,beds,ba,sq_ft,dep) VALUES " . $sql;
		
			
		/* PROPERTY DESCRIPTION */
		$insert_propdesc = "INSERT INTO prop_intro (id_prop, text) VALUES ('$prop','".addslashes($d_desc)."') ;";
		
			
		/* APARTMENT FEATURES */
		$feat = "";
		$comma = "";
		if(!empty($_POST['inter']))foreach ($_POST['inter'] as $key => $value){
			$feat_title = "Interior Features";
			$feat .= $comma." ('".$prop."','".$prop.$feat_title.$value."','".$feat_title."','".mysql_real_escape_string($value)."')";
			$comma = ",";
		}
		if(!empty($_POST['appl']))foreach ($_POST['appl'] as $key => $value){
			$feat_title = "Appliances";
			$feat .= $comma." ('".$prop."','".$prop.$feat_title.$value."','".$feat_title."','".mysql_real_escape_string($value)."')";
		}
		if(!empty($_POST['trans']))foreach ($_POST['trans'] as $key => $value){
			$feat_title = "Transportation";
			$feat .= $comma." ('".$prop."','".$prop.$feat_title.$value."','".$feat_title."','".mysql_real_escape_string($value)."')";
		}
		if(!empty($_POST['tv']))foreach ($_POST['tv'] as $key => $value){
			$feat_title = "TV &amp; Internet";
			$feat .= $comma." ('".$prop."','".$prop.$feat_title.$value."','".$feat_title."','".mysql_real_escape_string($value)."')";
		}
		if(!empty($_POST['health']))foreach ($_POST['health'] as $key => $value){
			$feat_title = "Health / Outdoor";
			$feat .= $comma." ('".$prop."','".$prop.$feat_title.$value."','".$feat_title."','".mysql_real_escape_string($value)."')";
		}
		if(!empty($_POST['laund']))foreach ($_POST['laund'] as $key => $value){
			$feat_title = "Laundry";
			$feat .= $comma." ('".$prop."','".$prop.$feat_title.$value."','".$feat_title."','".mysql_real_escape_string($value)."')";
		}
		if(!empty($_POST['secur']))foreach ($_POST['secur'] as $key => $value){
			$feat_title = "Parking / Security";
			$feat .= $comma." ('".$prop."','".$prop.$feat_title.$value."','".$feat_title."','".mysql_real_escape_string($value)."')";
		}
		if(!empty($_POST['lease']))foreach ($_POST['lease'] as $key => $value){
			$feat_title = "Lease Options";
			$feat .= $comma." ('".$prop."','".$prop.$feat_title.$value."','".$feat_title."','".mysql_real_escape_string($value)."')";
		}
		if(!empty($_POST['pet']))foreach ($_POST['pet'] as $key => $value){
			$feat_title = "Pets";
			$feat .= $comma." ('".$prop."','".$prop.$feat_title.$value."','".$feat_title."','".mysql_real_escape_string($value)."')";
		}
		if(!empty($_POST['amenet']))foreach ($_POST['amenet'] as $key => $value){
			$feat_title = "Additional Ameneties";
			$feat .= $comma." ('".$prop."','".$prop.$feat_title.$value."','".$feat_title."','".mysql_real_escape_string($value)."')";
		}
		if(!empty($_POST['senior']))foreach ($_POST['senior'] as $key => $value){
			$feat_title = "Senior";
			$feat .= $comma." ('".$prop."','".$prop.$feat_title.$value."','".$feat_title."','".mysql_real_escape_string($value)."')";
		}
		if($feat!=""){
			$insert_feat = "INSERT INTO prop_feat (id_prop,feat_uniq,type,feat) VALUES " . $feat;
			
		}
		
		/* HOURS OF OPERATION */
		if(!empty($_POST['hours'])){
			$insert_prophours = "INSERT INTO prop_hours (id_prop,SundayFromH, SundayFromM,SundayFromAP,SundayToH,SundayToM,SundayToAP,MondayFromH, MondayFromM,MondayFromAP,MondayToH,MondayToM,MondayToAP,TuesdayFromH, TuesdayFromM,TuesdayFromAP,TuesdayToH,TuesdayToM,TuesdayToAP,WednesdayFromH, WednesdayFromM,WednesdayFromAP,WednesdayToH,WednesdayToM,WednesdayToAP,ThursdayFromH, ThursdayFromM,ThursdayFromAP,ThursdayToH,ThursdayToM,ThursdayToAP,FridayFromH, FridayFromM,FridayFromAP,FridayToH,FridayToM,FridayToAP,SaturdayFromH, SaturdayFromM,SaturdayFromAP,SaturdayToH,SaturdayToM,SaturdayToAP) VALUES ('$prop','".$SundayFromH."','".$SundayFromM."','".$SundayFromAP."','".$SundayToH."','".$SundayToM."','".$SundayToAP."','".$MondayFromH."','".$MondayFromM."','".$MondayFromAP."','".$MondayToH."','".$MondayToM."','".$MondayToAP."','".$TuesdayFromH."','".$TuesdayFromM."','".$TuesdayFromAP."','".$TuesdayToH."','".$TuesdayToM."','".$TuesdayToAP."','".$WednesdayFromH."','".$WednesdayFromM."','".$WednesdayFromAP."','".$WednesdayToH."','".$WednesdayToM."','".$WednesdayToAP."','".$ThursdayFromH."','".$ThursdayFromM."','".$ThursdayFromAP."','".$ThursdayToH."','".$ThursdayToM."','".$ThursdayToAP."','".$FridayFromH."','".$FridayFromM."','".$FridayFromAP."','".$FridayToH."','".$FridayToM."','".$FridayToAP."','".$SaturdayFromH."','".$SaturdayFromM."','".$SaturdayFromAP."','".$SaturdayToH."','".$SaturdayToM."','".$SaturdayToAP."');";
		}
		if(isset($insert_propinfo)){mysql_query($insert_propinfo);}
		if(isset($insert_unit)){mysql_query($insert_unit);}
		if(isset($insert_propdesc)){mysql_query($insert_propdesc);}
		if(isset($insert_feat)){mysql_query($insert_feat);}
		if(isset($insert_prophours)){mysql_query($insert_prophours);}
		
		//Get user's email
		$check = mysql_query("SELECT email FROM users WHERE id = '$user_id'");
		while($row = mysql_fetch_array( $check, MYSQL_ASSOC )) 	{
			$user_email = $row["email"];
		}
		
		//Email me to let me know someone added a new property
		$message = "New property added: <br> 
		http://mybestapartments.ca/rent/".$prov."/".urlencode($city)."/".cleanUrl($p_name)."/".$prop."<br>Address: $address<br>Email of user: $user_email";
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		mail('cj3wilso@gmail.com', 'New property added: '.$prop, $message, $headers);
		
		if (!empty($_POST['promote'])){
			include '_inc/form_pp_send.php';
		}else{
			header("Location: $adminHome");
		}
	}
	// Closes connection
	include '_inc/mysqlclose.php';
}
//if the signin form is submitted 
if (isset($_POST['signin'])) { 
	include("form_signin.php");
}