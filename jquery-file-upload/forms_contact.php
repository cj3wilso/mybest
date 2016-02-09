<?php
	include_once('global.php'); 
	include('admin_security.php');
	include('library/utils/gbs_db_handlers.php');
	include('library/gbs_lm/lookup-lm-divisions.php');
  	require_once('gbs_dbs/gbs_lm_functions.php');
	require_once ('library/getcontrolflag.php');

//******************************************************************************
//*	Open connection
//******************************************************************************
$dbObj = new GBSDatabase($local_datasource_connection_string) or die('Problem occured during connection to the datasource');

//******************************************************************************
//*	END
//******************************************************************************

init_db_connection();
$control_name = "isc_email_address";

//Look up all divisions if admin
if($_SESSION['Permissions_session']==0){
	//Admin is ATL
	$local_division_id = $division_id = "'".lookup_lm_gbs_division_id(2)."','".lookup_lm_gbs_division_id(78)."','".lookup_lm_gbs_division_id(90)."','".lookup_lm_gbs_division_id(3)."','".lookup_lm_gbs_division_id(1)."','".lookup_lm_gbs_division_id(85)."','".lookup_lm_gbs_division_id(30)."','".lookup_lm_gbs_division_id(35)."','".lookup_lm_gbs_division_id(76)."','".lookup_lm_gbs_division_id(81)."','".lookup_lm_gbs_division_id(88)."','".lookup_lm_gbs_division_id(55)."'";
	//$local_division_id="'0'";
	//$local_division_id = $division_id = lookup_lm_gbs_division_id($_SESSION['cityIDAdmin_session']);
	//echo $local_division_id;
	//" . $dbObj->escape_string($local_division_id) . "
	$get_division_control_record_sql = "
			SELECT 
				control_file.*,
				'1' as show_order 
			
			FROM control_file 
			
			WHERE division_id IN ('ATL','RAL') 
				AND control_id = '" . $dbObj->escape_string($control_name) ."' 
				
			UNION 
			
			SELECT 
				control_file.*, 
				'2' as show_order 
			
			FROM control_file 
			
			WHERE division_id = '' 
				AND control_id = '" . $dbObj->escape_string($control_name) . "' 
			
			
			ORDER BY show_order 
		";
		$result = $gbsDbObj->execute_query($get_division_control_record_sql);
}else{
	//If not admin look up one email
	$local_division_id = $division_id = lookup_lm_gbs_division_id($_SESSION['cityIDAdmin_session']);
	$email = get_control_flag($control_name, $local_division_id);
}

//Save when submit
if(isset($_POST['contactSubmit'])){	
		$update_division_control_record_sql = "
			UPDATE control_file 
			
			SET \"control_text_value\"= '".pg_escape_string($_POST['email'])."' 
			
			WHERE \"division_id\" = '" . $local_division_id . "' 
				AND \"control_id\" = '" . $control_name ."' 
		";
		$gbsDbObj->execute_query($update_division_control_record_sql);
		header('Location: forms.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<link rel="stylesheet" href='http://fonts.googleapis.com/css?family=Muli:400,300,300italic' type='text/css'>
<?php include('includes-bt/global-header.php'); ?>
<LINK rel="stylesheet" type="text/css" href="admin.css">
</head>
<BODY onLoad="ass_info.style.display='none'">
<!-- nav bar start -->
<?php include('admin/topNavNoMenu.php'); ?>
<!-- nav bar end -->
<div class="container">
<h3>Edit Internet Concierge Contact Form</h3>

<form name="contactForm" method="post">
<?php 
//If admin
if(isset($result)){
	while ($row = pg_fetch_array($result)) { 
	?>
<table align="center" width="100%" border="0" class="table table-striped">
	<tr valign="top">
		<td align="right" width="30%">
			Email: <font color="red">*</font>		</td>
		<td align="left">
			<input name="email" size="50" maxlength="40" value="<?php echo $row["control_text_value"]; ?>"></td>
	</tr>
</table>
<?php 
//Else show one record
	}}else{
	?>
    <table align="center" width="100%" border="0" class="table table-striped">
	<tr valign="top">
		<td align="right" width="30%">
			Email: <font color="red">*</font>		</td>
		<td align="left">
			<input name="email" size="50" maxlength="40" value="<?php echo $email; ?>">		</td>
	</tr>
</table>
   <?php 
	}
	?>
<font color="red">*</font> Denotes Required Field
<table WIDTH=95% align="center" border="0" cellspacing="1" cellpadding="1">
	<tr>
		<td align="center">
            <INPUT type="reset" value="<< Back" id=reset1 name=reset1 onClick="redirect('forms.php');">
			<input type="submit" value="Save Changes" id=submit1 name="contactSubmit">
		</td>
	</tr>
</table>
<BR>
</form>

<script language="JavaScript" src="/_include/functions.js"></script>
<script language="JavaScript">
function redirect(url){
	window.location.href = url;
}
function openWin(url,windowName,options){
		var WindowHandle=window.open(url,windowName,options);
	}
	
function DialogOpenUpload(winname, storage){
   var sFeatures, sSaveVal;
   
   sFeatures = "dialogHeight: " + 450 + "px;";
   sFeatures += " dialogWidth: " + 530 + "px;";
   sFeatures += " edge: Raised; center: Yes; help: No; resizable: Yes; status: No; scroll: No;";   
   var retValueUpload = window.showModalDialog (winname, "IUpload", sFeatures);
		
	sSaveVal = storage.value;
	storage.value = retValueUpload;
	
	if (storage.value == "undefined"){
		storage.value = sSaveVal + "," + "none";
	}	
}


function checkdata()
{
if (document.community.Name.value.length == 0) {
alert ("Please enter the Community name.");
return false;
}
if (document.community.ShortName.value.length == 0) {
alert ("Please enter the Community Short name. It's a required field for Homebuledr.com data feed.");
return false;
}
if (document.community.project_id.value.length == 0) {
alert ("Please select the DB Community ID.");
return false;
}
if (document.community.AddressLine1.value.length == 0) {
alert ("Please enter the Address Line 1 field.");
return false;
}
if (document.community.City.value.length == 0) {
alert ("Please enter the City.");
return false;
}
if (document.community.StateProvince.value.length == 0) {
alert ("Please enter the state or province.");
return false;
}
if (document.community.ZipPostalCode.value.length == 0) {
alert ("Please enter your Zip or postal code.");
return false;
}
if (document.community.Country.value.length == 0) {
alert ("Please enter your country name.");
return false;
}
return true; 
}

function ass_info_expand() {


	if (ass_info_sign.innerText == '+') {
		ass_info_sign.innerHTML = '-';
		ass_info.style.display = 'block';	
	}
	else {
		ass_info_sign.innerText = '+';
		ass_info.style.display = 'none';		
	}
	
}

function Limit_Keypress(objMe, iLimit) {
	if (objMe.value.length >= iLimit){
		return false;
	}
}
//-->
</SCRIPT>
</body>
</html>
