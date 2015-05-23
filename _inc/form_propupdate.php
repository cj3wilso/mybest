<?php
// Update Property
if (isset ($_POST['publish']) || isset ($_POST['draft'])) { 
	include '_inc/geocode.can.class.php';
	
	//Remove French accents from city - to avoid duplicates
	$accents = explode(",","ç,æ,œ,á,é,í,ó,ú,à,è,ì,ò,ù,ä,ë,ï,ö,ü,ÿ,â,ê,î,ô,û,å,e,i,ø,u");
	$noaccents = explode(",","c,ae,oe,a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,y,a,e,i,o,u,a,e,i,o,u");
	
	$address = $_POST['streetnumber']." ".$_POST['streetaddress'].", ".$_POST['city'].", ".$_POST['prov']." ".$_POST['post'];
	$aInput = array();
	$aInput['stno'] = trim($_POST['streetnumber']);
	$aInput['addresst'] = trim(ucwords($_POST['streetaddress']));
	$aInput['city'] = trim(str_ireplace($accents, $noaccents, $_POST['city']));
	//City shouldn't end with word 'city'
	$aInput['city'] = str_ireplace( ' city', "", ucwords($aInput['city']) );
	$aInput['prov'] = $_POST['prov'];
	$aInput['postal'] = trim(strtoupper($_POST['post']));
	$class_exists = class_exists('GeoCode_ca');
	
	if ($class_exists) {
		$oGeoCoder = new GeoCode_ca();
		list($lat, $lng, $search, $type, $city, $region, $prov, $provLng, $post, $street) = $oGeoCoder->GetGeo($aInput);
		
		//Gives error if address not street address
		// route lets something like "222" through
	// && $type != "route"
	
		if (($type != "notype") && $class_exists) {
		//if (($type != "street_address" && $type != "postal_code") && $class_exists) {
			$hasError = true;
			$message = 'Address must contain a street address or postal code.';
		}
		//Gives error if address already exists
		$check = mysql_query("SELECT * FROM properties WHERE lat = '$lat' AND lng = '$lng' WHERE deleted <> 1");
		if (mysql_num_rows($check) != 0) {
			$hasError = true;
			$message = 'That property address already exists on '.$company.'. Please <a href="#myModal" role="button" data-toggle="modal">sign in</a> to edit your property or call us at '.$companyPhone.' if you think there is a mistake.';
		}
	}
	
	if (!isset($hasError)){ 
		include '_inc/mysqlconnect.php';
		
		/* PROPERTIES */
		$name = strip_tags($_POST['name']);
		$pub = 0;
		$modified = date("c");
		if(isset ($_POST['publish']))$pub = 1;
		//CHECK WHETHER GEOCODE IS WORKING
		if ($class_exists) {
			$update_prop = "UPDATE properties 
			SET name='$name', phone1='$_POST[phone1]', phone2='$_POST[phone2]', phone3='$_POST[phone3]', 
			email='$_POST[email]', url='$_POST[url]', address='$street', address2='$_POST[address2]', 
			city='$city', region='$region', prov='$prov', post='".$aInput['postal']."', lat='$lat', lng='$lng', pub=$pub, modified='$modified' 
			WHERE id_pg IN ('$prop')";
		}else{
		//GEOCODE NOT WORKING - NEED TO CLEAN UP DATA MANUALLY
			$update_prop = "UPDATE properties 
			SET name='$name', phone1='$_POST[phone1]', phone2='$_POST[phone2]', phone3='$_POST[phone3]', 
			email='$_POST[email]', url='$_POST[url]', address='$address', address2='$_POST[address2]', pub=$pub, modified='$modified'  
			WHERE id_pg IN ('$prop')";
			$message = "Property: <br> ".$prop;
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			mail('cj3wilso@gmail.com', 'Geocode not working: '.$prop, $message, $headers);
		}
		mysql_query($update_prop);
		
		/* UNIT DETAILS */
		// Update unit rows
		/*
		if( array_keys($_POST['u_bed']) != array_values($_POST['u_bed']) 
		|| array_keys($_POST['u_bath']) != array_values($_POST['u_bath']) 
		|| array_keys($_POST['u_sq']) != array_values($_POST['u_sq']) 
		|| array_keys($_POST['u_rent']) != array_values($_POST['u_rent']) 
		|| array_keys($_POST['u_dep']) != array_values($_POST['u_dep']) 
		|| array_keys($_POST['u_style']) != array_values($_POST['u_style']) ){
			*/
			$update_units = "UPDATE prop_units SET ";
			$update_units .= "beds = CASE ";
			foreach ($_POST['u_bed'] as $key => $value) {
				$next = array_search($key,array_keys($_POST['u_bed']))+1;
				if(!isset($_POST['u_order'][$next])){
					$_POST['u_order'][$next]=NULL;
				}
				$update_units .= "WHEN u_order = '".$_POST['u_order'][$next]."' THEN '".$value."' ";
			}
			$update_units .= "ELSE beds END ";
			$update_units .= ", ba = CASE ";
			foreach ($_POST['u_bath'] as $key => $value) {
				$next = array_search($key,array_keys($_POST['u_bath']))+1;
				if(!isset($_POST['u_order'][$next])){
					$_POST['u_order'][$next]=NULL;
				}
				$update_units .= "WHEN u_order = '".$_POST['u_order'][$next]."' THEN '".$value."' ";
			}
			$update_units .= "ELSE ba END ";
			$update_units .= ", sq_ft = CASE ";
			foreach ($_POST['u_sq'] as $key => $value) {
				$next = array_search($key,array_keys($_POST['u_sq']))+1;
				if(!isset($_POST['u_order'][$next])){
					$_POST['u_order'][$next]=NULL;
				}
				$update_units .= "WHEN u_order = '".$_POST['u_order'][$next]."' THEN '".$value."' ";
			}
			$update_units .= "ELSE sq_ft END ";
			$update_units .= ", rent = CASE ";
			foreach ($_POST['u_rent'] as $key => $value) {
				$next = array_search($key,array_keys($_POST['u_rent']))+1;
				if(!isset($_POST['u_order'][$next])){
					$_POST['u_order'][$next]=NULL;
				}
				$update_units .= "WHEN u_order = '".$_POST['u_order'][$next]."' THEN '".$value."' ";
			}
			$update_units .= "ELSE rent END ";
			$update_units .= ", dep = CASE ";
			foreach ($_POST['u_dep'] as $key => $value) {
				$next = array_search($key,array_keys($_POST['u_dep']))+1;
				if(!isset($_POST['u_order'][$next])){
					$_POST['u_order'][$next]=NULL;
				}
				$update_units .= "WHEN u_order = '".$_POST['u_order'][$next]."' THEN '".$value."' ";
			}
			$update_units .= "ELSE dep END ";
			$update_units .= ", style = CASE ";
			foreach ($_POST['u_style'] as $key => $value) {
				$next = array_search($key,array_keys($_POST['u_style']))+1;
				if(!isset($_POST['u_order'][$next])){
					$_POST['u_order'][$next]=NULL;
				}
				$update_units .= "WHEN u_order = '".$_POST['u_order'][$next]."' THEN '".$value."' ";
			}
			$update_units .= "ELSE style END ";
			$update_units .= "WHERE id_prop IN ('$prop')";
			mysql_query($update_units);
		/*
		}
		*/
		// Insert new unit rows
		if( isset($_POST['rowcount']) ){
			for($i=1; $i<=$_POST['rowcount']; $i++) {
				$u_order = $_POST['u_order' . $i];
				$u_rent = $_POST['u_rent' . $i];
				$u_style = $_POST['u_style' . $i];
				$u_bed = $_POST['u_bed' . $i];
				$u_bath = $_POST['u_bath' . $i];
				$u_sq = $_POST['u_sq' . $i];
				$u_dep = $_POST['u_dep' . $i];
				if($i!=1)$comma=",";
				$sql .= $comma." ('".$prop."','".$u_order."','".$u_rent."','".$u_style."','".$u_bed."','".$u_bath."','".$u_sq."','".$u_dep."')";
			}
			$insert_unit = "INSERT INTO prop_units (id_prop,u_order,rent,style,beds,ba,sq_ft,dep) VALUES " . $sql;
			mysql_query($insert_unit);
		}
		
		/* DESCRIPTION */
		$update_intro = "UPDATE prop_intro 
		SET text='$_POST[text]'
		WHERE id_prop IN ('$prop')";
		mysql_query($update_intro);
		
		/* APARTMENT FEATURES */
		$feat ="";
		$comma ="";
		/* Check whether someone deselected the checkbox 
		If "not" checkbox is selected but regular one isn't, we know user wants to remove feature */	
		if(!empty($_POST['inter']) || !empty($_POST['not_inter']))$feat_title = "Interior Features";
			foreach($_POST['not_inter'] as $key => $value) {
				if(isset($_POST['inter'][$key])) {
					$feat .= $comma." ('".$prop."','".$prop.$feat_title.$value."','".$feat_title."','".$value."',0)";
				}else{
					$feat .= $comma." ('".$prop."','".$prop.$feat_title.$value."','".$feat_title."','".$value."',1)";
				}
				$comma = ",";
			}
				
		if(!empty($_POST['appl']) || !empty($_POST['not_appl']))$feat_title = "Appliances";
			foreach($_POST['not_appl'] as $key => $value) {
				if(isset($_POST['appl'][$key])) {
					$feat .= $comma." ('".$prop."','".$prop.$feat_title.$value."','".$feat_title."','".$value."',0)";
				}else{
					$feat .= $comma." ('".$prop."','".$prop.$feat_title.$value."','".$feat_title."','".$value."',1)";
				}
			}
				
		if(!empty($_POST['trans']) || !empty($_POST['not_trans']))$feat_title = "Transportation";
			foreach($_POST['not_trans'] as $key => $value) {
				if(isset($_POST['trans'][$key])) {
					$feat .= $comma." ('".$prop."','".$prop.$feat_title.$value."','".$feat_title."','".$value."',0)";
				}else{
					$feat .= $comma." ('".$prop."','".$prop.$feat_title.$value."','".$feat_title."','".$value."',1)";
				}
			}
				
		if(!empty($_POST['tv']) || !empty($_POST['not_tv']))$feat_title = "TV &amp; Internet";
			foreach($_POST['not_tv'] as $key => $value) {
				if(isset($_POST['tv'][$key])) {
					$feat .= $comma." ('".$prop."','".$prop.$feat_title.$value."','".$feat_title."','".$value."',0)";
				}else{
					$feat .= $comma." ('".$prop."','".$prop.$feat_title.$value."','".$feat_title."','".$value."',1)";
				}
			}
				
		if(!empty($_POST['health']) || !empty($_POST['not_health']))$feat_title = "Health / Outdoor";
			foreach($_POST['not_health'] as $key => $value) {
				if(isset($_POST['health'][$key])) {
					$feat .= $comma." ('".$prop."','".$prop.$feat_title.$value."','".$feat_title."','".$value."',0)";
				}else{
					$feat .= $comma." ('".$prop."','".$prop.$feat_title.$value."','".$feat_title."','".$value."',1)";
				}
			}
				
		if(!empty($_POST['laund']) || !empty($_POST['not_laund']))$feat_title = "Laundry";
			foreach($_POST['not_laund'] as $key => $value) {
				if(isset($_POST['laund'][$key])) {
					$feat .= $comma." ('".$prop."','".$prop.$feat_title.$value."','".$feat_title."','".$value."',0)";
				}else{
					$feat .= $comma." ('".$prop."','".$prop.$feat_title.$value."','".$feat_title."','".$value."',1)";
				}
			}
				
		if(!empty($_POST['secur']) || !empty($_POST['not_secur']))$feat_title = "Parking / Security";
			foreach($_POST['not_secur'] as $key => $value) {
				if(isset($_POST['secur'][$key])) {
					$feat .= $comma." ('".$prop."','".$prop.$feat_title.$value."','".$feat_title."','".$value."',0)";
				}else{
					$feat .= $comma." ('".$prop."','".$prop.$feat_title.$value."','".$feat_title."','".$value."',1)";
				}
			}
				
		if(!empty($_POST['lease']) || !empty($_POST['not_lease']))$feat_title = "Lease Options";
			foreach($_POST['not_lease'] as $key => $value) {
				if(isset($_POST['lease'][$key])) {
					$feat .= $comma." ('".$prop."','".$prop.$feat_title.$value."','".$feat_title."','".$value."',0)";
				}else{
					$feat .= $comma." ('".$prop."','".$prop.$feat_title.$value."','".$feat_title."','".$value."',1)";
				}
			}
				
		if(!empty($_POST['pet']) || !empty($_POST['not_pet']))$feat_title = "Pets";
			foreach($_POST['not_pet'] as $key => $value) {
				if(isset($_POST['pet'][$key])) {
					$feat .= $comma." ('".$prop."','".$prop.$feat_title.$value."','".$feat_title."','".$value."',0)";
				}else{
					$feat .= $comma." ('".$prop."','".$prop.$feat_title.$value."','".$feat_title."','".$value."',1)";
				}
			}
				
		if(!empty($_POST['amenet']) || !empty($_POST['not_amenet']))$feat_title = "Additional Ameneties";
			foreach($_POST['not_amenet'] as $key => $value) {
				if(isset($_POST['amenet'][$key])) {
					$feat .= $comma." ('".$prop."','".$prop.$feat_title.$value."','".$feat_title."','".$value."',0)";
				}else{
					$feat .= $comma." ('".$prop."','".$prop.$feat_title.$value."','".$feat_title."','".$value."',1)";
				}
			}
				
		if(!empty($_POST['senior']) || !empty($_POST['not_senior']))$feat_title = "Senior";
			foreach($_POST['not_senior'] as $key => $value) {
				if(isset($_POST['senior'][$key])) {
					$feat .= $comma." ('".$prop."','".$prop.$feat_title.$value."','".$feat_title."','".$value."',0)";
				}else{
					$feat .= $comma." ('".$prop."','".$prop.$feat_title.$value."','".$feat_title."','".$value."',1)";
				}
			}

		if($feat != ""){
			$insert_feat = "REPLACE INTO prop_feat (id_prop,feat_uniq,type,feat,deleted) VALUES " . $feat;
			mysql_query($insert_feat);
		}
		
		/* HOURS OF OPERATION */
		$update_prophours = "UPDATE prop_hours 
		SET SundayFromH='".$_POST['hours']["SundayFromH"]."', SundayFromM='".$_POST['hours']["SundayFromM"]."', 
		SundayFromAP='".$_POST['hours']["SundayFromAP"]."', SundayToH='".$_POST['hours']["SundayToH"]."', 
		SundayToM='".$_POST['hours']["SundayToM"]."', SundayToAP='".$_POST['hours']["SundayToAP"]."', 
		MondayFromH='".$_POST['hours']["MondayFromH"]."', MondayFromM='".$_POST['hours']["MondayFromM"]."', 
		MondayFromAP='".$_POST['hours']["MondayFromAP"]."', MondayToH='".$_POST['hours']["MondayToH"]."', 
		MondayToM='".$_POST['hours']["MondayToM"]."', MondayToAP='".$_POST['hours']["MondayToAP"]."', 
		TuesdayFromH='".$_POST['hours']["TuesdayFromH"]."', TuesdayFromM='".$_POST['hours']["TuesdayFromM"]."', 
		TuesdayFromAP='".$_POST['hours']["TuesdayFromAP"]."', TuesdayToH='".$_POST['hours']["TuesdayToH"]."', 
		TuesdayToM='".$_POST['hours']["TuesdayToM"]."', TuesdayToAP='".$_POST['hours']["TuesdayToAP"]."', 
		WednesdayFromH='".$_POST['hours']["WednesdayFromH"]."', WednesdayFromM='".$_POST['hours']["WednesdayFromM"]."', 
		WednesdayFromAP='".$_POST['hours']["WednesdayFromAP"]."', WednesdayToH='".$_POST['hours']["WednesdayToH"]."', 
		WednesdayToM='".$_POST['hours']["WednesdayToM"]."', WednesdayToAP='".$_POST['hours']["WednesdayToAP"]."', 
		ThursdayFromH='".$_POST['hours']["ThursdayFromH"]."', ThursdayFromM='".$_POST['hours']["ThursdayFromM"]."', 
		ThursdayFromAP='".$_POST['hours']["ThursdayFromAP"]."', ThursdayToH='".$_POST['hours']["ThursdayToH"]."', 
		ThursdayToM='".$_POST['hours']["ThursdayToM"]."', ThursdayToAP='".$_POST['hours']["ThursdayToAP"]."', 
		FridayFromH='".$_POST['hours']["FridayFromH"]."', FridayFromM='".$_POST['hours']["FridayFromM"]."', 
		FridayFromAP='".$_POST['hours']["FridayFromAP"]."', FridayToH='".$_POST['hours']["FridayToH"]."', 
		FridayToM='".$_POST['hours']["FridayToM"]."', FridayToAP='".$_POST['hours']["FridayToAP"]."', 
		SaturdayFromH='".$_POST['hours']["SaturdayFromH"]."', SaturdayFromM='".$_POST['hours']["SaturdayFromM"]."', 
		SaturdayFromAP='".$_POST['hours']["SaturdayFromAP"]."', SaturdayToH='".$_POST['hours']["SaturdayToH"]."', 
		SaturdayToM='".$_POST['hours']["SaturdayToM"]."', SaturdayToAP='".$_POST['hours']["SaturdayToAP"]."' 
		WHERE id_prop IN ('$prop')";
		mysql_query($update_prophours);
		
		include '_inc/mysqlclose.php';
		if (!empty($_POST['promote'])){
			include '_inc/form_pp_send.php';
		}else{
			header("Location: $adminHome");
		}
	}
}
//Remove Unit Line
if (isset($_POST['remove'])) { 
	foreach ($_POST['remove'] as $key => $value)
	{		
		include '_inc/mysqlconnect.php';
		mysql_query("DELETE FROM prop_units WHERE u_order='$value'");
		include '_inc/mysqlclose.php';
		header("Location: $adminEdit/$prop");
	}
}