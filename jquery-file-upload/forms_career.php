<?php
	include_once('global.php'); 
	include('admin_security.php');
	include('library/utils/gbs_db_handlers.php');
	include('library/awh_sql/get_plan_details_by_city.php');
	include('library/gbs_lm/lookup-lm-divisions.php');
	include('_include/getdivision.php');
	
//******************************************************************************
//*	Open connection
//******************************************************************************
$dbObj = new GBSDatabase($local_datasource_connection_string) or die('Problem occured during connection to the datasource');

//******************************************************************************
//*	END
//******************************************************************************

//Create city ID
$city_id = "'".$_SESSION['cityIDAdmin_session']."'";
//Look up all divisions if admin
if($_SESSION['Permissions_session']==0){
	$city_id="'2','78','90','3','1','85','30','35','76','81','88','55'";
}
$query_career = "SELECT name, email, city_id, position, subject FROM careers WHERE city_id IN ($city_id) ORDER BY city_id, subject";
$career = $dbObj->execute_query($query_career);

//If form posted - save new data
if(isset($_POST['careers'])){	
	$num_of_vars = count($_POST['position']);
	for ($i=0; $i < $num_of_vars; $i++){
		$updateSQL = "UPDATE careers SET name='".pg_escape_string($_POST['name'][$i])."', email='".pg_escape_string($_POST['email'][$i])."' WHERE position='".$_POST['position'][$i]."' AND city_id = '".$_POST['city_id'][$i]."'";
  		$Result1 = $dbObj->execute_query($updateSQL);
	}
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
<h3>Edit Career Contact Form</h3>

<form name="careers" method="post">
	<?php
		while ($rs = pg_fetch_array($career)) { 
			$current_cityid = $rs['city_id'];
			if(!isset($last_cityid) || $current_cityid != $last_cityid){
				echo "<h3>".ucwords($mainLoc[$rs['city_id']]["location"])."</h3>";
			}
			?>
            <table align="center" width="100%" border="0" class="table table-striped">
            <tr valign="top">
            <th align="right" width="30%"><strong>
			For: 		</strong></th>
		<th align="left">
			<strong><?php echo $rs['subject']; ?>	</strong></th>
			</tr>
    		<tr valign="top">
			<td align="right" width="30%">
			Name: <font color="red">*</font>		</td>
		<td align="left">
			<input type="hidden" name="city_id[]" value="<?php echo $rs['city_id']; ?>">
            <input type="hidden" name="position[]" value="<?php echo $rs['position']; ?>">
            <input name="name[]" size="30" maxlength="30" value="<?php echo $rs['name']; ?>">		
        </td>
	</tr>
	<tr valign="top">
		<td align="right" width="30%">
			Email: <font color="red">*</font>		</td>
		<td align="left">
			<input name="email[]" size="50" maxlength="40" value="<?php echo $rs["email"]; ?>">		</td>
	</tr>
    </table>
    <br>
            <?php
			$last_cityid=$rs['city_id'];
		} 
	?>
<table align="center" width="100%" border="0" class="table table-striped">
    <tr>
	 <td>&nbsp;</td>
	 <td width="70%"><font color="red">*</font> Denotes Required Field </td>
	</tr>
</table>

<TABLE WIDTH=95% ALIGN=center BORDER=0 CELLSPACING=1 CELLPADDING=1>
	<TR>
		<TD align=center>
            <INPUT type="reset" value="<< Back" id=reset1 name=reset1 onClick="redirect('forms.php');">
			<input type="submit" value="Save Changes" id=submit1 name="careers">
		</TD>
	</TR>
</TABLE>
<BR>
</form>
</div>

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
//-->
</script>
<SCRIPT ID=clientEventHandlersJS LANGUAGE=javascript>
<!--
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
