<?php
include("global.php");

if(isset($_GET['id'])){
	$type = "Edit";
	$prop = $_GET['id'];
	$_SESSION["prop"]= $prop;
	// Query based on property ID
	include("loggedin.php");
	$page = "edit";
	$pageTitle = "Edit Property";
	$h1title = "Edit Info <small>Property Code: $prop</small>";
	include '_inc/mysqlconnect.php';
	$info = mysql_fetch_array(mysql_query("SELECT * 
	FROM properties 
	INNER JOIN prop_units ON prop_units.id_prop = '$prop'
	LEFT JOIN prop_intro ON prop_intro.id_prop = '$prop'  
	LEFT JOIN prop_hours ON prop_hours.id_prop = '$prop' 
	LEFT JOIN prop_feat ON prop_feat.id_prop = '$prop' 
	WHERE id_pg = '$prop'"));
	$info2 = mysql_query("SELECT * 
	FROM prop_feat 
	WHERE id_prop = '$prop' AND deleted = 0");
	$info3 = mysql_query("SELECT * 
	FROM prop_units 
	WHERE id_prop = '$prop'
	ORDER BY u_order ASC");
	$info4 = mysql_query("SELECT * 
		FROM prop_promote 
		WHERE payer_id IS NOT NULL AND id_prop = '$prop'");
	$promoonline = $promoexpired = $days = $top = $home = $promo_days_expired = NULL;
	while ($promo = mysql_fetch_array($info4)) {
		//If promo not expired
		if($promo['expired'] == '0000-00-00 00:00:00'){
			$promoonline = true;
			//get top promo
			if ($promo["sku"]=="A0012" && $promo["payer_id"]!=NULL){
				$top = "checked";
			}
			//get home promo
			if  ($promo["sku"]=="B0012" && $promo["payer_id"]!=NULL){
				$home = "checked";
			}
		}else{
			$promoexpired = $promo['expired'];
		}
	}
	//Don't show expired message if one of their promos is online
	if(!$promoonline && $promoexpired){
		//Don't show old checkboxes
		$top = $home = NULL;
		$now = time(); // or your date as well
		$your_date = strtotime($promoexpired);
		$datediff = $now - $your_date;
		$days = floor($datediff/(60*60*24));
		if($days==0){
			$promo_days_expired = "today";
		}else{
			$promo_days_expired = $days." days ago";
		}
	}
		
	$feat = array();
	while ($row = mysql_fetch_array($info2)) {
		$feat[] = $row['feat'];
	}
	if($info["pub"]==1){
		$online = true;
	}else{
		$online = false;
	}
	include '_inc/mysqlclose.php';
	
	// UPDATE WHEN SUBMITTED
	include '_inc/form_propupdate.php';
	
	$line = mysql_num_rows($info3);
	$streetnumber = $streetaddress = "";
	$streetnumRegex = "^\d+ ";
	if (preg_match("/".$streetnumRegex."/i",$info['address'], $matches)){
			$streetnumber = $matches[0];
			$streetaddress = str_replace($matches[0], "", $info['address']);
	}
}else{
	$page = "add";
	$pageTitle = "Add Property";
	$h1title = "Advertise your rental";
	//Check promotion options on add pages
	$top = $home = "checked";
	
	// ALLOW USERS TO SIGN IN
	$type = "Create";
	if(isset($_POST['signin'])){
		include '_inc/form_signin.php';
	}
	
	// Set Page ID 
	$random_id_length = 5; //set the random id length 
	$prop = crypt(uniqid(rand(),1));  //generate a random id encrypt it and store it in $prop 
	$prop = strip_tags(stripslashes($prop)); //to remove any slashes that might have come 
	$prop = str_replace(".","",$prop); //Removing any . or / and reversing the string 
	$prop = strrev(str_replace("/","",$prop)); 
	$prop = strtolower(substr($prop,0,$random_id_length)); //finally I take the first 5 characters from the $prop 
	$_SESSION['prop']=$prop;
	
	// If new page
	$line = 1;
	$default = true;
	$info = NULL;
	$info2 = NULL;
	$info3 = NULL;
	$info4 = NULL;
	$info["SundayFromH"] = $info["MondayFromH"] = $info["TuesdayFromH"] = $info["WednesdayFromH"] = $info["ThursdayFromH"] = $info["FridayFromH"] = $info["SaturdayFromH"] = 1;
	
	// ADD WHEN SUBMITTED
	include '_inc/form_propadd.php';
}
//Check whether user is signed in so we know whether a new or repeat user is adding a promo
//For Google Analtyics
if(isset($_SESSION['ID_my_site'])){
	$user = "Repeat user";
}else{
	$user = "New user";
}
//If an error page, show previous entries
if (isset($hasError)){ 
	$info = $info2 = $info3 = $units = $info4 = $_POST;
	$streetnumber = $streetaddress = "";
	$streetnumRegex = "^\d+ ";
	if (preg_match("/".$streetnumRegex."/i",$_POST['address'], $matches)){
			$streetnumber = $matches[0];
			$streetaddress = str_replace($matches[0], "", $_POST['address']);
	}
}
$headStyles='
<!-- blueimp Gallery styles -->
<link rel="stylesheet" href="http://blueimp.github.io/Gallery/css/blueimp-gallery.min.css">
<!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
<link rel="stylesheet" href="/jquery-file-upload/css/jquery.fileupload.css">
<link rel="stylesheet" href="/jquery-file-upload/css/jquery.fileupload-ui.css">
<!-- CSS adjustments for browsers with JavaScript disabled -->
<noscript>
<link rel="stylesheet" href="/jquery-file-upload/css/jquery.fileupload-noscript.css">
</noscript>
<noscript>
<link rel="stylesheet" href="/jquery-file-upload/css/jquery.fileupload-ui-noscript.css">
</noscript>
<style>
.slots {
	position: relative;
	width: 100%;
	min-height:340px;
	float: left;
	padding: 10px;
	margin: 0 10px 10px 0;
	display: block;
	text-decoration: none;
	overflow: hidden;
	border: 1px dashed #aaa;
	border-radius: 10px;
}
.delete-btn{
	position:relative;
	z-index:2000;
}
</style>
<!-- WYSIWYG -->
<script type="text/javascript" src="/assets/js/ckeditor/ckeditor.js"></script>
';
include("header.php");
?>
<div class="row">
     <div class="col-sm-3">       
<?php
if(isset($_SESSION['ID_my_site'])){
?>
<ul class="nav nav-pills">
  <li class="active"> <a href="<?php echo $adminHome; ?>"><i class="icon-double-angle-left"></i> Back to Admin Home</a> </li>
</ul>
<?php
} ?>
</div>
</div>
<div class="row">
     <div class="col-sm-4">  
<h1><?php echo $h1title; ?></h1>
</div>
<div class="col-sm-8">
<?php
if(isset($online) && $online==true){
		echo '<div class="alert alert-success pull-right">Status: Published (Online)</div>'; 
	}else if (isset($online) && $online==false){
		echo '<div class="alert alert-warning pull-right">Status: Draft (Offline)</div>'; 
	}
	?>
</div></div>
<?php 
	if(isset($hasError)) { 
		echo '<div class="alert alert-danger">'.$message.'</div>'; 
	}
	if(isset($hasSubmit)) { 
	echo '<div class="alert alert-success">'.$submessage.'</div>'; 
}
	?>
<form class="fileupload" method="POST" enctype="multipart/form-data" data-submit="/jquery-file-upload/area/admin.php">
  <div class="panel-group" id="accordion">
    <?php
if($page != "edit"){
	include 'pg_propsignin.php';
}
?>
    <div class="panel panel-default" id="beds-panel">
      <div class="panel-heading">
        <h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#info" class="collapsed"> Property Information </a> <small class="pull-right selected">Required</small> </h4>
      </div>
      <div id="info" class="collapse  
<?php
if ( isset($_SESSION["ID_my_site"]) ){
?> in <?php } ?>">
        <div class="panel-body">
          <div class="row form-group">
            <div class="col-sm-2">Property Name <span class="red">*</span></div>
            <div class="col-sm-3">
              <input class="form-control required" type="text" name="name" data-placement="bottom" value="<?php echo (isset($info["name"]) ? $info["name"] : ''); ?>" />
            </div>
            <div class="col-sm-2">Phone</div>
            <div class="col-sm-3">
            <div class="row">
            <div class="col-xs-4">
              <input class="form-control" type="text" pattern="[0-9]*" name="phone1" minlength="3" maxlength="3" value="<?php echo (isset($info["phone1"])&&$info["phone1"]!="000" ? $info["phone1"] : ''); ?>" />
            </div>
            <div class="col-xs-4" style="padding-left:0px;">
              <input class="form-control" type="text" pattern="[0-9]*" name="phone2" minlength="3" maxlength="3" value="<?php echo (isset($info["phone2"])&&$info["phone2"]!="000" ? $info["phone2"] : ''); ?>" />
            </div>
            <div class="col-xs-4" style="padding-left:0px;">
              <input class="form-control" type="text" pattern="[0-9]*" name="phone3" minlength="4" maxlength="4" value="<?php echo (isset($info["phone3"])&&$info["phone3"]!="000" ? $info["phone3"] : ''); ?>" />
            </div>
            </div>
            </div>
          </div>
          <div class="row form-group">
            <div class="col-sm-2">Email <span class="red">*</span></div>
            <div class="col-sm-3">
              <input class="form-control required" type="email" name="email" data-placement="bottom" value="<?php echo (isset($info["email"]) ? $info["email"] : ''); ?>" />
            </div>
            <div class="col-sm-2">URL </div>
            <div class="col-sm-3">
              <input class="form-control url" type="url" name="url" value="<?php echo (isset($info["url"]) ? $info["url"] : ''); ?>" />
            </div>
          </div>
          <div class="row form-group">
            <div class="col-sm-2">Street Number<span class="red">*</span></div>
            <div class="col-sm-2">
              <input class="form-control required" type="text" name="streetnumber" id="streetnumber" value="<?php echo (isset($streetnumber) ? $streetnumber : ''); ?>">
            </div>
            <div class="col-sm-1"></div>
            <div class="col-sm-2">Street Address<span class="red">*</span></div>
            <div class="col-sm-3">
              <input class="form-control required" type="text" name="streetaddress" id="streetaddress" value="<?php echo (isset($streetaddress) ? $streetaddress : ''); ?>">
            </div>
          </div>
          <div class="row form-group">
            <div class="col-sm-2">Unit #</div>
            <div class="col-sm-2">
              <input class="form-control" type="text" name="address2" value="<?php echo (isset($info["address2"]) ? $info["address2"] : ''); ?>">
            </div>
            <div class="col-sm-1"></div>
            <div class="col-sm-2">City <span class="red">*</span></div>
            <div class="col-sm-3">
              <input class="form-control required" type="text" name="city" value="<?php echo (isset($info["city"]) ? $info["city"] : ''); ?>" />
            </div>
          </div>
          <div class="row form-group">
          		<div class="col-sm-2">Prov <span class="red">*</span></div>
            <div class="col-sm-3">
            	<select class="form-control required" name="prov" >
                	<option value="">Please select</option>
                    <option value="AB" <?php if(isset($info["prov"])&& $info["prov"]=="AB"){ echo "selected"; } ?>>Alberta</option>
                    <option value="BC" <?php if(isset($info["prov"])&& $info["prov"]=="BC"){ echo "selected"; } ?>>British Columbia</option>
                    <option value="MB" <?php if(isset($info["prov"])&& $info["prov"]=="MB"){ echo "selected"; } ?>>Manitoba</option>
                    <option value="NB" <?php if(isset($info["prov"])&& $info["prov"]=="NB"){ echo "selected"; } ?>>New Brunswick</option>
                    <option value="NL" <?php if(isset($info["prov"])&& $info["prov"]=="NL"){ echo "selected"; } ?>>Newfoundland and Labrador</option>
                    <option value="NS" <?php if(isset($info["prov"])&& $info["prov"]=="NS"){ echo "selected"; } ?>>Nova Scotia</option>
                    <option value="ON" <?php if(isset($info["prov"])&& $info["prov"]=="ON"){ echo "selected"; } ?>>Ontario</option>
                    <option value="PE" <?php if(isset($info["prov"])&& $info["prov"]=="PE"){ echo "selected"; } ?>>Prince Edward Island</option>
                    <option value="QC" <?php if(isset($info["prov"])&& $info["prov"]=="QC"){ echo "selected"; } ?>>Quebec</option>
                    <option value="SK" <?php if(isset($info["prov"])&& $info["prov"]=="SK"){ echo "selected"; } ?>>Saskatchewan</option>
                    <option value="NT" <?php if(isset($info["prov"])&& $info["prov"]=="NT"){ echo "selected"; } ?>>Northwest Territories</option>
                    <option value="NU" <?php if(isset($info["prov"])&& $info["prov"]=="NU"){ echo "selected"; } ?>>Nunavut</option>
                    <option value="YT" <?php if(isset($info["prov"])&& $info["prov"]=="YT"){ echo "selected"; } ?>>Yukon</option>
                </select>
            </div>
            <div class="col-sm-2">Postal Code <span class="red">*</span></div>
            <div class="col-sm-3">
              <input class="form-control required" type="text" name="post" value="<?php echo (isset($info["post"]) ? $info["post"] : ''); ?>" />
            </div>
          </div>
          <div class="row">
            <div class="col-sm-2">Description <span class="red">*</span></div>
          </div>
          <div class="row form-group">
            <div class="col-sm-12">
            <!-- required -->
              <textarea class="form-control ckeditor" name="text" rows="5" maxlength="2800" minlength="50" data-placement="bottom"><?php echo (isset($info["text"]) ? $info["text"] : ''); ?></textarea>
            </div>
          </div>
		  
		  <h5>Rental Unit Details</h5>
          <dl id="unit-rows">
            <?php
$rows = 1;
for ($i=0; $i<$line; $i++) {
	$order = $i;
	if($page == "edit" && !isset($hasError)){
		$units = mysql_fetch_assoc($info3);
		$order = $units['u_order'];
	}
	if (isset($hasError)){ 
		$units["u_order"] = $units["u_order"][$i];
		$units["rent"] = $units["u_rent"][$i];
		$units["style"] = $units["u_style"][$i];
		$units["beds"] = $units["u_bed"][$i];
		$units["ba"] = $units["u_bath"][$i];
		$units["sq_ft"] = $units["u_sq"][$i];
		$units["dep"] = $units["u_dep"][$i];
		//echo print_r($_POST);
	}
?>
            <input type="hidden" name="u_order[<?php echo $order; ?>]" value="<?php echo $order; ?>" />
            <strong>Unit <?php echo $rows; ?></strong>
            <dd id="unit-row">
              <?php
    	if($rows != 1){
		?>
              <button class="pull-right btn btn-default btn-small delete-btn" name="remove[]" value="<?php echo (isset($units["u_order"]) ? $units["u_order"] : ''); ?>" type="submit"><i class="icon-trash"></i> Delete</button>
              <!--  id="unit-remove" -->
              <?php
		}
		?>
              <div class="row form-group">
                <div class="col-sm-2">Rent <span class="red">*</span></div>
                <div class="col-sm-3">
                  <input class="form-control required" type="text" name="u_rent[<?php echo (isset($units["rent"]) ? $order : ''); ?>]" data-placement="bottom" value="<?php echo (isset($units["rent"]) ? $units["rent"] : ''); ?>" />
                </div>
                <div class="col-sm-2">Style <span class="red">*</span></div>
                <div class="col-sm-3">
                  <select class="required select-basic" name="u_style[<?php echo (isset($units["style"]) ? $order : ''); ?>]" data-placement="bottom">
                    <option value="">Please select</option>
                    <option value="Apartment" <?php if( isset($units["style"]) && $units["style"] == "Apartment" ) echo  "selected"; ?>>Apartment</option>
                    <option value="Condo" <?php if( isset($units["style"]) && $units["style"] == "Condo" ) echo  "selected"; ?>>Condo</option>
                    <option value="House" <?php if( isset($units["style"]) && $units["style"] == "House" ) echo  "selected"; ?>>House</option>
                    <option value="Townhome" <?php if( isset($units["style"]) && $units["style"] == "Townhome" ) echo  "selected"; ?>>Townhome</option>
                    <option value="Loft" <?php if( isset($units["style"]) && $units["style"] == "Loft" ) echo  "selected"; ?>>Loft</option>
                  </select>
                </div>
              </div>
              <div class="row form-group">
                <div class="col-sm-2">Bedrooms <span class="red">*</span></div>
                <div class="col-sm-3">
                  <select class="required select-basic" name="u_bed[<?php echo (isset($units["beds"]) ? $order : ''); ?>]" data-placement="bottom">
                    <option value="">Please select</option>
                    <option value="Studio" <?php if( isset($units["beds"]) && $units["beds"] == "Studio" ) echo  "selected"; ?>>Studio</option>
                    <option value="1" <?php if( isset($units["beds"]) && $units["beds"] == "1" ) echo  "selected"; ?>>1 Bedroom</option>
                    <option value="1 plus Den" <?php if( isset($units["beds"]) && $units["beds"] == "1 plus Den" ) echo  "selected"; ?>>1 Bedroom + Den</option>
                    <option value="2" <?php if( isset($units["beds"]) && $units["beds"] == "2" ) echo  "selected"; ?>>2 Bedrooms</option>
                    <option value="2 plus Den" <?php if( isset($units["beds"]) && $units["beds"] == "2 plus Den" ) echo  "selected"; ?>>2 Bedroom + Den</option>
                    <option value="3" <?php if( isset($units["beds"]) && $units["beds"] == "3" ) echo  "selected"; ?>>3 Bedrooms</option>
                    <option value="4" <?php if( isset($units["beds"]) && $units["beds"] == "4" ) echo  "selected"; ?>>4 Bedrooms</option>
                  </select>
                </div>
                <div class="col-sm-2">Bathrooms </div>
                <div class="col-sm-3">
                  <select class="select-basic" name="u_bath[<?php echo (isset($units["ba"]) ? $order : ''); ?>]" data-placement="bottom">
                    <option value="">Please select</option>
                    <option value="1" <?php if( isset($units["ba"]) && $units["ba"] == "1" ) echo  "selected"; ?>>1 Bathroom</option>
                    <option value="2" <?php if( isset($units["ba"]) && $units["ba"] == "2" ) echo  "selected"; ?>>2 Bathrooms</option>
                    <option value="3" <?php if( isset($units["ba"]) && $units["ba"] == "3" ) echo  "selected"; ?>>3 Bathrooms</option>
                    <option value="4" <?php if( isset($units["ba"]) && $units["ba"] == "4" ) echo  "selected"; ?>>4 Bathrooms</option>
                  </select>
                </div>
              </div>
              <div class="row form-group">
                <div class="col-sm-2">Square Feet </div>
                <div class="col-sm-3">
                  <input class="form-control" type="text" name="u_sq[<?php echo (isset($units["sq_ft"]) ? $order : ''); ?>]" data-placement="bottom" value="<?php echo (isset($units["sq_ft"]) ? $units["sq_ft"] : ''); ?>" />
                </div>
                <div class="col-sm-2">Deposit </div>
                <div class="col-sm-3">
                  <input class="form-control" type="text" name="u_dep[<?php echo (isset($units["dep"]) ? $order : ''); ?>]" value="<?php echo (isset($units["dep"]) ? $units["dep"] : ''); ?>" />
                </div>
              </div>
            </dd>
            <?php
	$rows++;
	}
	?>
          </dl>
          <div class="row">
            <div class="col-sm-12"><a href="#" id="unit-add">Add Another Unit</a></div>
          </div>
		  
		  
		  
		  <!-- START UPLOAD AREA -->
          <h5>Upload Photos</h5>
		  Rental listings with photos get twice the amount of replies! <span class="red">Minimum size: 390px x 390px</span>
		  <div class="slots dropzone"> 
          <!-- Redirect browsers with JavaScript disabled to the origin page -->
            <noscript>
            <input type="hidden" name="redirect" value="http://blueimp.github.io/jQuery-File-Upload/">
            </noscript>
            <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
            <div class="row fileupload-buttonbar">
              <div class="col-sm-7"> 
                <!-- The fileinput-button span is used to style the file input field as button --> 
                <span class="btn btn-default fileinput-button"> <i class="glyphicon glyphicon-plus"></i> <span>Add files...</span>
                <input type="file" name="files[]" multiple>
                </span>
                <button type="submit" class="btn btn-default start"> <i class="glyphicon glyphicon-upload"></i> <span>Start upload</span> </button>
                <button type="reset" class="btn btn-default cancel"> <i class="glyphicon glyphicon-ban-circle"></i> <span>Cancel upload</span> </button>
                <button type="button" class="btn btn-default delete"> <i class="glyphicon glyphicon-trash"></i> <span>Delete</span> </button>
                <input type="checkbox" class="toggle">
                <!-- The global file processing state --> 
                <span class="fileupload-process"></span> </div>
              <!-- The global progress state -->
              <div class="col-sm-5 fileupload-progress fade"> 
                <!-- The global progress bar -->
                <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                  <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                </div>
                <!-- The extended global progress state -->
                <div class="progress-extended">&nbsp;</div>
              </div>
            </div>
            <p style="text-align:center;color:#ccc;">Drag and Drop Photos - Move to Reorder</p>
            <!-- The table listing the files available for upload/download -->
            <table role="presentation" class="table table-striped">
              <tbody class="files">
              </tbody>
            </table>
           
          <!-- The blueimp Gallery widget -->
          <div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" data-filter=":even">
            <div class="slides"></div>
            <h3 class="title"></h3>
            <a class="prev">‹</a> <a class="next">›</a> <a class="close">×</a> <a class="play-pause"></a>
            <ol class="indicator">
            </ol>
          </div>
          
          <!-- The template to display files available for upload --> 
          <script id="template-upload" type="text/x-tmpl">
        {% for (var i=0, file; file=o.files[i]; i++) { %}
            <tr class="template-upload fade">
                <td>
                    <span class="preview"></span>
                </td>
                <td>
                    <label class="description">
                    <span>Description:</span><br>			
                    <input type="text" name="description[]" value="" class="form-control" style="height:30px;margin-top:4px;" />
                    </label>
                    <p class="name">{%=file.name%}</p>
                    <strong class="error text-danger"></strong>
                </td>
                <td>
                    <p class="size">Processing...</p>
                    <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
                </td>
                <td>
                    {% if (!i && !o.options.autoUpload) { %}
                        <button class="btn btn-default start" disabled>
                            <i class="glyphicon glyphicon-upload"></i>
                            <span>Start</span>
                        </button>
                    {% } %}
                    {% if (!i) { %}
                        <button class="btn btn-default cancel">
                            <i class="glyphicon glyphicon-ban-circle"></i>
                            <span>Cancel</span>
                        </button>
                    {% } %}
                </td>
            </tr>
        {% } %}
        </script> 
          <!-- The template to display files available for download --> 
          <script id="template-download" type="text/x-tmpl">
        {% for (var i=0, file; file=o.files[i]; i++) { %}
            <tr class="template-download fade" id="recordsArray_{%=file.id%}">
                <td>
                    <span class="preview">
                        {% if (file.thumbnailUrl) { %}
                            <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
                        {% } %}
                    </span>
                </td>
                <td>
                    <p class="description">{%=file.description||''%}</p>
                    <p class="name">
                        {% if (file.url) { %}
                            <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                        {% } else { %}
                            <span>{%=file.name%}</span>
                        {% } %}
                    </p>
                    {% if (file.error) { %}
                        <div><span class="label label-danger">Error</span> {%=file.error%}</div>
                    {% } %}
                </td>
                <td>
                    <span class="size">{%=o.formatFileSize(file.size)%}</span>
                </td>
                <td>
                    {% if (file.deleteUrl) { %}
                        <button class="btn btn-default delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                            <i class="glyphicon glyphicon-trash"></i>
                            <span>Delete</span>
                        </button>
                        <input type="checkbox" name="delete" value="1" class="toggle">
                    {% } else { %}
                        <button class="btn btn-default cancel">
                            <i class="glyphicon glyphicon-ban-circle"></i>
                            <span>Cancel</span>
                        </button>
                    {% } %}
                </td>
            </tr>
        {% } %}
        </script> 
        </div>
        <!-- END UPLOAD AREA -->
		  
		  
		  
		  
        </div>
      </div>
    </div>
    <div class="panel panel-default" id="laundry-panel">
      <div class="panel-heading">
        <h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#promote" class="collapsed"> Promote Your Rental </a> <small class="pull-right">Promote for FREE, use coupon code "BEST"</small></h4>
      </div>
      <div id="promote" class="panel-collapse collapse">
        <div class="panel-body">
          <?php if(isset($promo_days_expired)){ ?>
          <div class="alert alert-danger">Your promotion expired <?php echo $promo_days_expired; ?>. Check below to promote your rental ad again.</div>
          <?php } ?>
          <div class="row">
            <div class="col-sm-3">
              <input name="promote[]" value="A0012" type="checkbox" <?php echo $top; ?>>
              Top Page Ad ($5.00)</div>
            <div class="col-sm-3">
              <input name="promote[]" value="B0012" type="checkbox" <?php echo $home; ?>>
              Home Page Ad ($10.00)</div>
            <div class="col-sm-2">Coupon Code: </div>
            <div class="col-sm-4">
              <input name="coupon" class="form-control" type="text" value="BEST">
            </div>
          </div>
          <small class="red">Ads will show for 60 days</small>
        </div>
      </div>
    </div>
    <div class="panel panel-default" id="pets-panel">
      <div class="panel-heading">
        <h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#features" class="collapsed"> Apartment Features </a> <small class="pull-right">Users search by these features</small> </h4>
      </div>
      <div id="features" class="panel-collapse collapse">
        <div class="panel-body">
          <?php
include 'pg_feat_options.php';
?>
        </div>
      </div>
    </div>
    <div class="panel panel-default" id="pets-panel">
      <div class="panel-heading">
        <h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#hours" class="collapsed"> Hours of Operation </a> </h4>
      </div>
      <div id="hours" class="panel-collapse collapse">
        <div class="panel-body">
          <div id="hoursOp">
            <div id="Sunday" class="row form-group day">
              <?php 
	  // If new property or no hours in database then set allclosed to true, and use defaults
	  if( !(isset($info["SundayFromH"]) && isset($info["MondayFromH"]) && isset($info["TuesdayFromH"]) && isset($info["WednesdayFromH"]) && isset($info["ThursdayFromH"]) && isset($info["FridayFromH"]) && isset($info["SaturdayFromH"])) || ($info["SundayFromH"] == 0 && $info["MondayFromH"] == 0 && $info["TuesdayFromH"] == 0 && $info["WednesdayFromH"] == 0 && $info["ThursdayFromH"] == 0 && $info["FridayFromH"] == 0 && $info["SaturdayFromH"] == 0) ){ 
	  $default = true; $allclosed = true;
	  }
	  ?>
              <div id="label" class="col-sm-1">Sunday: </div>
               <div class="col-sm-4">
               <div class="row">
              <div class="col-xs-4">
                <select name="hours[SundayFromH]" class="form-control hour from">
                  <option value="1" <?php if( isset($info["SundayFromH"]) && $info["SundayFromH"] == "1" ) echo  "selected"; ?>>1</option>
                  <option value="2" <?php if( isset($info["SundayFromH"]) && $info["SundayFromH"] == "2" ) echo  "selected"; ?>>2</option>
                  <option value="3" <?php if( isset($info["SundayFromH"]) && $info["SundayFromH"] == "3" ) echo  "selected"; ?>>3</option>
                  <option value="4" <?php if( isset($info["SundayFromH"]) && $info["SundayFromH"] == "4" ) echo  "selected"; ?>>4</option>
                  <option value="5" <?php if( isset($info["SundayFromH"]) && $info["SundayFromH"] == "5" ) echo  "selected"; ?>>5</option>
                  <option value="6" <?php if( isset($info["SundayFromH"]) && $info["SundayFromH"] == "6" ) echo  "selected"; ?>>6</option>
                  <option value="7" <?php if( isset($info["SundayFromH"]) && $info["SundayFromH"] == "7" ) echo  "selected"; ?>>7</option>
                  <option value="8" <?php if( isset($info["SundayFromH"]) && $info["SundayFromH"] == "8" ) echo  "selected"; ?>>8</option>
                  <option value="9" <?php if( $allclosed == true || (isset($info["SundayFromH"]) && $info["SundayFromH"] == "9") ){ echo  "selected"; } ?>>9</option>
                  <option value="10" <?php if( isset($info["SundayFromH"]) && $info["SundayFromH"] == "10" ) echo  "selected"; ?>>10</option>
                  <option value="11" <?php if( isset($info["SundayFromH"]) && $info["SundayFromH"] == "11" ) echo  "selected"; ?>>11</option>
                  <option value="12" <?php if( isset($info["SundayFromH"]) && $info["SundayFromH"] == "12" ) echo  "selected"; ?>>12</option>
                </select>
              </div>
              <div class="col-xs-4">
                <select name="hours[SundayFromM]" class="form-control min from">
                  <option value=":00" <?php if( isset($info["SundayFromH"]) && $info["SundayFromH"] == ":00" ) echo  "selected"; ?>>:00</option>
                  <option value=":15" <?php if( isset($info["SundayFromH"]) && $info["SundayFromH"] == ":15" ) echo  "selected"; ?>>:15</option>
                  <option value=":30" <?php if( isset($info["SundayFromH"]) && $info["SundayFromH"] == ":30" ) echo  "selected"; ?>>:30</option>
                  <option value=":45" <?php if( isset($info["SundayFromH"]) && $info["SundayFromH"] == ":45" ) echo  "selected"; ?>>:45</option>
                </select>
              </div>
              <div class="col-xs-4">
                <select name="hours[SundayFromAP]" class="form-control ampm from">
                  <option value="AM" <?php if( isset($info["SundayFromH"]) && $info["SundayFromH"] == "AM" ) echo  "selected"; ?>>AM</option>
                  <option value="PM" <?php if( isset($info["SundayFromH"]) && $info["SundayFromH"] == "PM" ) echo  "selected"; ?>>PM</option>
                </select>
              </div>
              </div>
              </div>
              <div class="col-sm-1 text-center">to</div>
              <div class="col-sm-4">
               <div class="row">
              <div class="col-xs-4">
                <select name="hours[SundayToH]" class="form-control hour to">
                  <option value="1" <?php if( isset($info["SundayToH"]) && $info["SundayToH"] == "1" ) echo  "selected"; ?>>1</option>
                  <option value="2" <?php if( isset($info["SundayToH"]) && $info["SundayToH"] == "2" ) echo  "selected"; ?>>2</option>
                  <option value="3" <?php if( isset($info["SundayToH"]) && $info["SundayToH"] == "3" ) echo  "selected"; ?>>3</option>
                  <option value="4" <?php if( isset($info["SundayToH"]) && $info["SundayToH"] == "4" ) echo  "selected"; ?>>4</option>
                  <option value="5" <?php if( isset($info["SundayToH"]) && $info["SundayToH"] == "5" || $default == true ) echo  "selected"; ?>>5</option>
                  <option value="6" <?php if( isset($info["SundayToH"]) && $info["SundayToH"] == "6" ) echo  "selected"; ?>>6</option>
                  <option value="7" <?php if( isset($info["SundayToH"]) && $info["SundayToH"] == "7" ) echo  "selected"; ?>>7</option>
                  <option value="8" <?php if( isset($info["SundayToH"]) && $info["SundayToH"] == "8" ) echo  "selected"; ?>>8</option>
                  <option value="9" <?php if( isset($info["SundayToH"]) && $info["SundayToH"] == "9" ) echo  "selected"; ?>>9</option>
                  <option value="10" <?php if( isset($info["SundayToH"]) && $info["SundayToH"] == "10" ) echo  "selected"; ?>>10</option>
                  <option value="11" <?php if( isset($info["SundayToH"]) && $info["SundayToH"] == "11" ) echo  "selected"; ?>>11</option>
                  <option value="12" <?php if( isset($info["SundayToH"]) && $info["SundayToH"] == "12" ) echo  "selected"; ?>>12</option>
                </select>
              </div>
              <div class="col-xs-4">
                <select name="hours[SundayToM]" class="form-control min to">
                  <option value=":00" <?php if( isset($info["SundayToM"]) && $info["SundayToM"] == ":00" ) echo  "selected"; ?>>:00</option>
                  <option value=":15" <?php if( isset($info["SundayToM"]) && $info["SundayToM"] == ":15" ) echo  "selected"; ?>>:15</option>
                  <option value=":30" <?php if( isset($info["SundayToM"]) && $info["SundayToM"] == ":30" ) echo  "selected"; ?>>:30</option>
                  <option value=":45" <?php if( isset($info["SundayToM"]) && $info["SundayToM"] == ":45" ) echo  "selected"; ?>>:45</option>
                </select>
              </div>
              <div class="col-xs-4">
                <select name="hours[SundayToAP]" class="form-control ampm to">
                  <option value="AM" <?php if( isset($info["SundayToAP"]) && $info["SundayToAP"] == "AM" ) echo  "selected"; ?>>AM</option>
                  <option value="PM" <?php if( isset($info["SundayToAP"]) && $info["SundayToAP"] == "PM" || $default == true ) echo  "selected"; ?>>PM</option>
                </select>
              </div>
              </div>
              </div>
              <span class="col-sm-2">
              <input type="checkbox" name="SundayClosed" value="closed" class="closed" <?php if( isset($info["SundayFromH"]) && $info["SundayFromH"] == 0 && !$allclosed ) echo  "checked"; ?>>
              Closed</span></div>
            <div id="Monday" class="row form-group day">
              <div id="label" class="col-sm-1">Monday: </div>
              <div class="col-sm-4">
               <div class="row">
              <div class="col-xs-4">
                <select name="hours[MondayFromH]" class="form-control hour from">
                  <option value="1" <?php if( isset($info["MondayFromH"]) && $info["MondayFromH"] == "1" ) echo  "selected"; ?>>1</option>
                  <option value="2" <?php if( isset($info["MondayFromH"]) && $info["MondayFromH"] == "2" ) echo  "selected"; ?>>2</option>
                  <option value="3" <?php if( isset($info["MondayFromH"]) && $info["MondayFromH"] == "3" ) echo  "selected"; ?>>3</option>
                  <option value="4" <?php if( isset($info["MondayFromH"]) && $info["MondayFromH"] == "4" ) echo  "selected"; ?>>4</option>
                  <option value="5" <?php if( isset($info["MondayFromH"]) && $info["MondayFromH"] == "5" ) echo  "selected"; ?>>5</option>
                  <option value="6" <?php if( isset($info["MondayFromH"]) && $info["MondayFromH"] == "6" ) echo  "selected"; ?>>6</option>
                  <option value="7" <?php if( isset($info["MondayFromH"]) && $info["MondayFromH"] == "7" ) echo  "selected"; ?>>7</option>
                  <option value="8" <?php if( isset($info["MondayFromH"]) && $info["MondayFromH"] == "8" ) echo  "selected"; ?>>8</option>
                  <option value="9" <?php if( isset($info["MondayFromH"]) && $info["MondayFromH"] == "9" || $default == true ) echo  "selected"; ?>>9</option>
                  <option value="10" <?php if( isset($info["MondayFromH"]) && $info["MondayFromH"] == "10" ) echo  "selected"; ?>>10</option>
                  <option value="11" <?php if( isset($info["MondayFromH"]) && $info["MondayFromH"] == "11" ) echo  "selected"; ?>>11</option>
                  <option value="12" <?php if( isset($info["MondayFromH"]) && $info["MondayFromH"] == "12" ) echo  "selected"; ?>>12</option>
                </select>
              </div>
              <div class="col-xs-4">
                <select name="hours[MondayFromM]" class="form-control min from">
                  <option value=":00" <?php if( isset($info["MondayFromM"]) && $info["MondayFromM"] == ":00" ) echo  "selected"; ?>>:00</option>
                  <option value=":15" <?php if( isset($info["MondayFromM"]) && $info["MondayFromM"] == ":15" ) echo  "selected"; ?>>:15</option>
                  <option value=":30" <?php if( isset($info["MondayFromM"]) && $info["MondayFromM"] == ":30" ) echo  "selected"; ?>>:30</option>
                  <option value=":45" <?php if( isset($info["MondayFromM"]) && $info["MondayFromM"] == ":45" ) echo  "selected"; ?>>:45</option>
                </select>
              </div>
              <div class="col-xs-4">
                <select name="hours[MondayFromAP]" class="form-control ampm from">
                  <option value="AM" <?php if( isset($info["MondayFromAP"]) && $info["MondayFromAP"] == "AM" ) echo  "selected"; ?>>AM</option>
                  <option value="PM" <?php if( isset($info["MondayFromAP"]) && $info["MondayFromAP"] == "PM" ) echo  "selected"; ?>>PM</option>
                </select>
              </div>
              </div>
              </div>
              <div class="col-sm-1 text-center">to</div>
              <div class="col-sm-4">
               <div class="row">
              <div class="col-xs-4">
                <select name="hours[MondayToH]" class="form-control hour to">
                  <option value="1" <?php if( isset($info["MondayToH"]) && $info["MondayToH"] == "1" ) echo  "selected"; ?>>1</option>
                  <option value="2" <?php if( isset($info["MondayToH"]) && $info["MondayToH"] == "2" ) echo  "selected"; ?>>2</option>
                  <option value="3" <?php if( isset($info["MondayToH"]) && $info["MondayToH"] == "3" ) echo  "selected"; ?>>3</option>
                  <option value="4" <?php if( isset($info["MondayToH"]) && $info["MondayToH"] == "4" ) echo  "selected"; ?>>4</option>
                  <option value="5" <?php if( isset($info["MondayToH"]) && $info["MondayToH"] == "5" || $default == true ) echo  "selected"; ?>>5</option>
                  <option value="6" <?php if( isset($info["MondayToH"]) && $info["MondayToH"] == "6" ) echo  "selected"; ?>>6</option>
                  <option value="7" <?php if( isset($info["MondayToH"]) && $info["MondayToH"] == "7" ) echo  "selected"; ?>>7</option>
                  <option value="8" <?php if( isset($info["MondayToH"]) && $info["MondayToH"] == "8" ) echo  "selected"; ?>>8</option>
                  <option value="9" <?php if( isset($info["MondayToH"]) && $info["MondayToH"] == "9" ) echo  "selected"; ?>>9</option>
                  <option value="10" <?php if( isset($info["MondayToH"]) && $info["MondayToH"] == "10" ) echo  "selected"; ?>>10</option>
                  <option value="11" <?php if( isset($info["MondayToH"]) && $info["MondayToH"] == "11" ) echo  "selected"; ?>>11</option>
                  <option value="12" <?php if( isset($info["MondayToH"]) && $info["MondayToH"] == "12" ) echo  "selected"; ?>>12</option>
                </select>
              </div>
              <div class="col-xs-4">
                <select name="hours[MondayToM]" class="form-control min to">
                  <option value=":00" <?php if( isset($info["MondayToM"]) && $info["MondayToM"] == ":00" ) echo  "selected"; ?>>:00</option>
                  <option value=":15" <?php if( isset($info["MondayToM"]) && $info["MondayToM"] == ":15" ) echo  "selected"; ?>>:15</option>
                  <option value=":30" <?php if( isset($info["MondayToM"]) && $info["MondayToM"] == ":30" ) echo  "selected"; ?>>:30</option>
                  <option value=":45" <?php if( isset($info["MondayToM"]) && $info["MondayToM"] == ":45" ) echo  "selected"; ?>>:45</option>
                </select>
              </div>
              <div class="col-xs-4">
                <select name="hours[MondayToAP]" class="form-control ampm to">
                  <option value="AM" <?php if( isset($info["MondayToAP"]) && $info["MondayToAP"] == "AM" ) echo  "selected"; ?>>AM</option>
                  <option value="PM" <?php if( isset($info["MondayToAP"]) && $info["MondayToAP"] == "PM" || $default == true ) echo  "selected"; ?>>PM</option>
                </select>
              </div>
              </div>
              </div>
              <span class="col-sm-2">
              <input type="checkbox" name="MondayClosed" value="closed" class="closed" <?php if( isset($info["MondayFromH"]) && $info["MondayFromH"] == 0 && !$allclosed ) echo  "checked"; ?>>
              Closed</span></div>
            <div id="Tuesday" class="row form-group day">
              <div id="label" class="col-sm-1">Tuesday: </div>
              <div class="col-sm-4">
               <div class="row">
              <div class="col-xs-4">
                <select name="hours[TuesdayFromH]" class="form-control hour from">
                  <option value="1" <?php if( isset($info["TuesdayFromH"]) && $info["TuesdayFromH"] == "1" ) echo  "selected"; ?>>1</option>
                  <option value="2" <?php if( isset($info["TuesdayFromH"]) && $info["TuesdayFromH"] == "2" ) echo  "selected"; ?>>2</option>
                  <option value="3" <?php if( isset($info["TuesdayFromH"]) && $info["TuesdayFromH"] == "3" ) echo  "selected"; ?>>3</option>
                  <option value="4" <?php if( isset($info["TuesdayFromH"]) && $info["TuesdayFromH"] == "4" ) echo  "selected"; ?>>4</option>
                  <option value="5" <?php if( isset($info["TuesdayFromH"]) && $info["TuesdayFromH"] == "5" ) echo  "selected"; ?>>5</option>
                  <option value="6" <?php if( isset($info["TuesdayFromH"]) && $info["TuesdayFromH"] == "6" ) echo  "selected"; ?>>6</option>
                  <option value="7" <?php if( isset($info["TuesdayFromH"]) && $info["TuesdayFromH"] == "7" ) echo  "selected"; ?>>7</option>
                  <option value="8" <?php if( isset($info["TuesdayFromH"]) && $info["TuesdayFromH"] == "8" ) echo  "selected"; ?>>8</option>
                  <option value="9" <?php if( isset($info["TuesdayFromH"]) && $info["TuesdayFromH"] == "9" || $default == true ) echo  "selected"; ?>>9</option>
                  <option value="10" <?php if( isset($info["TuesdayFromH"]) && $info["TuesdayFromH"] == "10" ) echo  "selected"; ?>>10</option>
                  <option value="11" <?php if( isset($info["TuesdayFromH"]) && $info["TuesdayFromH"] == "11" ) echo  "selected"; ?>>11</option>
                  <option value="12" <?php if( isset($info["TuesdayFromH"]) && $info["TuesdayFromH"] == "12" ) echo  "selected"; ?>>12</option>
                </select>
              </div>
              <div class="col-xs-4">
                <select name="hours[TuesdayFromM]" class="form-control min from">
                  <option value=":00" <?php if( isset($info["TuesdayFromM"]) && $info["TuesdayFromM"] == ":00" ) echo  "selected"; ?>>:00</option>
                  <option value=":15" <?php if( isset($info["TuesdayFromM"]) && $info["TuesdayFromM"] == ":15" ) echo  "selected"; ?>>:15</option>
                  <option value=":30" <?php if( isset($info["TuesdayFromM"]) && $info["TuesdayFromM"] == ":30" ) echo  "selected"; ?>>:30</option>
                  <option value=":45" <?php if( isset($info["TuesdayFromM"]) && $info["TuesdayFromM"] == ":45" ) echo  "selected"; ?>>:45</option>
                </select>
              </div>
              <div class="col-xs-4">
                <select name="hours[TuesdayFromAP]" class="form-control ampm from">
                  <option value="AM" <?php if( isset($info["TuesdayFromAP"]) && $info["TuesdayFromAP"] == "AM" ) echo  "selected"; ?>>AM</option>
                  <option value="PM" <?php if( isset($info["TuesdayFromAP"]) && $info["TuesdayFromAP"] == "PM" ) echo  "selected"; ?>>PM</option>
                </select>
              </div>
              </div>
              </div>
              <div class="col-sm-1 text-center">to</div>
              <div class="col-sm-4">
               <div class="row">
              <div class="col-xs-4">
                <select name="hours[TuesdayToH]" class="form-control hour to">
                  <option value="1" <?php if( isset($info["TuesdayToH"]) && $info["TuesdayToH"] == "1" ) echo  "selected"; ?>>1</option>
                  <option value="2" <?php if( isset($info["TuesdayToH"]) && $info["TuesdayToH"] == "2" ) echo  "selected"; ?>>2</option>
                  <option value="3" <?php if( isset($info["TuesdayToH"]) && $info["TuesdayToH"] == "3" ) echo  "selected"; ?>>3</option>
                  <option value="4" <?php if( isset($info["TuesdayToH"]) && $info["TuesdayToH"] == "4" ) echo  "selected"; ?>>4</option>
                  <option value="5" <?php if( isset($info["TuesdayToH"]) && $info["TuesdayToH"] == "5" || $default == true ) echo  "selected"; ?>>5</option>
                  <option value="6" <?php if( isset($info["TuesdayToH"]) && $info["TuesdayToH"] == "6" ) echo  "selected"; ?>>6</option>
                  <option value="7" <?php if( isset($info["TuesdayToH"]) && $info["TuesdayToH"] == "7" ) echo  "selected"; ?>>7</option>
                  <option value="8" <?php if( isset($info["TuesdayToH"]) && $info["TuesdayToH"] == "8" ) echo  "selected"; ?>>8</option>
                  <option value="9" <?php if( isset($info["TuesdayToH"]) && $info["TuesdayToH"] == "9" ) echo  "selected"; ?>>9</option>
                  <option value="10" <?php if( isset($info["TuesdayToH"]) && $info["TuesdayToH"] == "10" ) echo  "selected"; ?>>10</option>
                  <option value="11" <?php if( isset($info["TuesdayToH"]) && $info["TuesdayToH"] == "11" ) echo  "selected"; ?>>11</option>
                  <option value="12" <?php if( isset($info["TuesdayToH"]) && $info["TuesdayToH"] == "12" ) echo  "selected"; ?>>12</option>
                </select>
              </div>
              <div class="col-xs-4">
                <select name="hours[TuesdayToM]" class="form-control min to">
                  <option value=":00" <?php if( isset($info["TuesdayToM"]) && $info["TuesdayToM"] == ":00" ) echo  "selected"; ?>>:00</option>
                  <option value=":15" <?php if( isset($info["TuesdayToM"]) && $info["TuesdayToM"] == ":15" ) echo  "selected"; ?>>:15</option>
                  <option value=":30" <?php if( isset($info["TuesdayToM"]) && $info["TuesdayToM"] == ":30" ) echo  "selected"; ?>>:30</option>
                  <option value=":45" <?php if( isset($info["TuesdayToM"]) && $info["TuesdayToM"] == ":45" ) echo  "selected"; ?>>:45</option>
                </select>
              </div>
              <div class="col-xs-4">
                <select name="hours[TuesdayToAP]" class="form-control ampm to">
                  <option value="AM" <?php if( isset($info["TuesdayToAP"]) && $info["TuesdayToAP"] == "AM" ) echo  "selected"; ?>>AM</option>
                  <option value="PM" <?php if( isset($info["TuesdayToAP"]) && $info["TuesdayToAP"] == "PM" || $default == true ) echo  "selected"; ?>>PM</option>
                </select>
              </div>
              </div>
              </div>
              <span class="col-sm-2">
              <input type="checkbox" name="TuesdayClosed" value="closed" class="closed" <?php if( isset($info["TuesdayFromH"]) && $info["TuesdayFromH"] == 0 && !$allclosed ) echo  "checked"; ?>>
              Closed</span></div>
            <div id="Wednesday" class="row form-group day">
              <div id="label" class="col-sm-1">Wednesday: </div>
              <div class="col-sm-4">
               <div class="row">
              <div class="col-xs-4">
                <select name="hours[WednesdayFromH]" class="form-control hour from">
                  <option value="1" <?php if( isset($info["WednesdayFromH"]) && $info["WednesdayFromH"] == "1" ) echo  "selected"; ?>>1</option>
                  <option value="2" <?php if( isset($info["WednesdayFromH"]) && $info["WednesdayFromH"] == "2" ) echo  "selected"; ?>>2</option>
                  <option value="3" <?php if( isset($info["WednesdayFromH"]) && $info["WednesdayFromH"] == "3" ) echo  "selected"; ?>>3</option>
                  <option value="4" <?php if( isset($info["WednesdayFromH"]) && $info["WednesdayFromH"] == "4" ) echo  "selected"; ?>>4</option>
                  <option value="5" <?php if( isset($info["WednesdayFromH"]) && $info["WednesdayFromH"] == "5" ) echo  "selected"; ?>>5</option>
                  <option value="6" <?php if( isset($info["WednesdayFromH"]) && $info["WednesdayFromH"] == "6" ) echo  "selected"; ?>>6</option>
                  <option value="7" <?php if( isset($info["WednesdayFromH"]) && $info["WednesdayFromH"] == "7" ) echo  "selected"; ?>>7</option>
                  <option value="8" <?php if( isset($info["WednesdayFromH"]) && $info["WednesdayFromH"] == "8" ) echo  "selected"; ?>>8</option>
                  <option value="9" <?php if( isset($info["WednesdayFromH"]) && $info["WednesdayFromH"] == "9" || $default == true ) echo  "selected"; ?>>9</option>
                  <option value="10" <?php if( isset($info["WednesdayFromH"]) && $info["WednesdayFromH"] == "10" ) echo  "selected"; ?>>10</option>
                  <option value="11" <?php if( isset($info["WednesdayFromH"]) && $info["WednesdayFromH"] == "11" ) echo  "selected"; ?>>11</option>
                  <option value="12" <?php if( isset($info["WednesdayFromH"]) && $info["WednesdayFromH"] == "12" ) echo  "selected"; ?>>12</option>
                </select>
              </div>
              <div class="col-xs-4">
                <select name="hours[WednesdayFromM]" class="form-control min from">
                  <option value=":00" <?php if( isset($info["WednesdayFromM"]) && $info["WednesdayFromM"] == ":00" ) echo  "selected"; ?>>:00</option>
                  <option value=":15" <?php if( isset($info["WednesdayFromM"]) && $info["WednesdayFromM"] == ":15" ) echo  "selected"; ?>>:15</option>
                  <option value=":30" <?php if( isset($info["WednesdayFromM"]) && $info["WednesdayFromM"] == ":30" ) echo  "selected"; ?>>:30</option>
                  <option value=":45" <?php if( isset($info["WednesdayFromM"]) && $info["WednesdayFromM"] == ":45" ) echo  "selected"; ?>>:45</option>
                </select>
              </div>
              <div class="col-xs-4">
                <select name="hours[WednesdayFromAP]" class="form-control ampm from">
                  <option value="AM" <?php if( isset($info["WednesdayFromAP"]) && $info["WednesdayFromAP"] == "AM" ) echo  "selected"; ?>>AM</option>
                  <option value="PM" <?php if( isset($info["WednesdayFromAP"]) && $info["WednesdayFromAP"] == "PM" ) echo  "selected"; ?>>PM</option>
                </select>
              </div>
              </div>
              </div>
              <div class="col-sm-1 text-center">to</div>
              <div class="col-sm-4">
               <div class="row">
              <div class="col-xs-4">
                <select name="hours[WednesdayToH]" class="form-control hour to">
                  <option value="1" <?php if( isset($info["WednesdayToH"]) && $info["WednesdayToH"] == "1" ) echo  "selected"; ?>>1</option>
                  <option value="2" <?php if( isset($info["WednesdayToH"]) && $info["WednesdayToH"] == "2" ) echo  "selected"; ?>>2</option>
                  <option value="3" <?php if( isset($info["WednesdayToH"]) && $info["WednesdayToH"] == "3" ) echo  "selected"; ?>>3</option>
                  <option value="4" <?php if( isset($info["WednesdayToH"]) && $info["WednesdayToH"] == "4" ) echo  "selected"; ?>>4</option>
                  <option value="5" <?php if( isset($info["WednesdayToH"]) && $info["WednesdayToH"] == "5" || $default == true ) echo  "selected"; ?>>5</option>
                  <option value="6" <?php if( isset($info["WednesdayToH"]) && $info["WednesdayToH"] == "6" ) echo  "selected"; ?>>6</option>
                  <option value="7" <?php if( isset($info["WednesdayToH"]) && $info["WednesdayToH"] == "7" ) echo  "selected"; ?>>7</option>
                  <option value="8" <?php if( isset($info["WednesdayToH"]) && $info["WednesdayToH"] == "8" ) echo  "selected"; ?>>8</option>
                  <option value="9" <?php if( isset($info["WednesdayToH"]) && $info["WednesdayToH"] == "9" ) echo  "selected"; ?>>9</option>
                  <option value="10" <?php if( isset($info["WednesdayToH"]) && $info["WednesdayToH"] == "10" ) echo  "selected"; ?>>10</option>
                  <option value="11" <?php if( isset($info["WednesdayToH"]) && $info["WednesdayToH"] == "11" ) echo  "selected"; ?>>11</option>
                  <option value="12" <?php if( isset($info["WednesdayToH"]) && $info["WednesdayToH"] == "12" ) echo  "selected"; ?>>12</option>
                </select>
              </div>
              <div class="col-xs-4">
                <select name="hours[WednesdayToM]" class="form-control min to">
                  <option value=":00" <?php if( isset($info["WednesdayToM"]) && $info["WednesdayToM"] == ":00" ) echo  "selected"; ?>>:00</option>
                  <option value=":15" <?php if( isset($info["WednesdayToM"]) && $info["WednesdayToM"] == ":15" ) echo  "selected"; ?>>:15</option>
                  <option value=":30" <?php if( isset($info["WednesdayToM"]) && $info["WednesdayToM"] == ":30" ) echo  "selected"; ?>>:30</option>
                  <option value=":45" <?php if( isset($info["WednesdayToM"]) && $info["WednesdayToM"] == ":45" ) echo  "selected"; ?>>:45</option>
                </select>
              </div>
              <div class="col-xs-4">
                <select name="hours[WednesdayToAP]" class="form-control ampm to">
                  <option value="AM" <?php if( isset($info["WednesdayToAP"]) && $info["WednesdayToAP"] == "AM" ) echo  "selected"; ?>>AM</option>
                  <option value="PM" <?php if( isset($info["WednesdayToAP"]) && $info["WednesdayToAP"] == "PM" || $default == true ) echo  "selected"; ?>>PM</option>
                </select>
              </div>
              </div>
              </div>
              <span class="col-sm-2">
              <input type="checkbox" name="WednesdayClosed" value="closed" class="closed" <?php if( isset($info["WednesdayFromH"]) && $info["WednesdayFromH"] == 0 && !$allclosed ) echo  "checked"; ?>>
              Closed</span></div>
            <div id="Thursday" class="row form-group day">
              <div id="label" class="col-sm-1">Thursday: </div>
              <div class="col-sm-4">
               <div class="row">
              <div class="col-xs-4">
                <select name="hours[ThursdayFromH]" class="form-control hour from">
                  <option value="1" <?php if( isset($info["ThursdayFromH"]) && $info["ThursdayFromH"] == "1" ) echo  "selected"; ?>>1</option>
                  <option value="2" <?php if( isset($info["ThursdayFromH"]) && $info["ThursdayFromH"] == "2" ) echo  "selected"; ?>>2</option>
                  <option value="3" <?php if( isset($info["ThursdayFromH"]) && $info["ThursdayFromH"] == "3" ) echo  "selected"; ?>>3</option>
                  <option value="4" <?php if( isset($info["ThursdayFromH"]) && $info["ThursdayFromH"] == "4" ) echo  "selected"; ?>>4</option>
                  <option value="5" <?php if( isset($info["ThursdayFromH"]) && $info["ThursdayFromH"] == "5" ) echo  "selected"; ?>>5</option>
                  <option value="6" <?php if( isset($info["ThursdayFromH"]) && $info["ThursdayFromH"] == "6" ) echo  "selected"; ?>>6</option>
                  <option value="7" <?php if( isset($info["ThursdayFromH"]) && $info["ThursdayFromH"] == "7" ) echo  "selected"; ?>>7</option>
                  <option value="8" <?php if( isset($info["ThursdayFromH"]) && $info["ThursdayFromH"] == "8" ) echo  "selected"; ?>>8</option>
                  <option value="9" <?php if( isset($info["ThursdayFromH"]) && $info["ThursdayFromH"] == "9" || $default == true ) echo  "selected"; ?>>9</option>
                  <option value="10" <?php if( isset($info["ThursdayFromH"]) && $info["ThursdayFromH"] == "10" ) echo  "selected"; ?>>10</option>
                  <option value="11" <?php if( isset($info["ThursdayFromH"]) && $info["ThursdayFromH"] == "11" ) echo  "selected"; ?>>11</option>
                  <option value="12" <?php if( isset($info["ThursdayFromH"]) && $info["ThursdayFromH"] == "12" ) echo  "selected"; ?>>12</option>
                </select>
              </div>
              <div class="col-xs-4">
                <select name="hours[ThursdayFromM]" class="form-control min from">
                  <option value=":00" <?php if( isset($info["ThursdayFromM"]) && $info["ThursdayFromM"] == ":00" ) echo  "selected"; ?>>:00</option>
                  <option value=":15" <?php if( isset($info["ThursdayFromM"]) && $info["ThursdayFromM"] == ":15" ) echo  "selected"; ?>>:15</option>
                  <option value=":30" <?php if( isset($info["ThursdayFromM"]) && $info["ThursdayFromM"] == ":30" ) echo  "selected"; ?>>:30</option>
                  <option value=":45" <?php if( isset($info["ThursdayFromM"]) && $info["ThursdayFromM"] == ":45" ) echo  "selected"; ?>>:45</option>
                </select>
              </div>
              <div class="col-xs-4">
                <select name="hours[ThursdayFromAP]" class="form-control ampm from">
                  <option value="AM" <?php if( isset($info["ThursdayFromAP"]) && $info["ThursdayFromAP"] == "AM" ) echo  "selected"; ?>>AM</option>
                  <option value="PM" <?php if( isset($info["ThursdayFromAP"]) && $info["ThursdayFromAP"] == "PM" ) echo  "selected"; ?>>PM</option>
                </select>
              </div>
              </div>
              </div>
              <div class="col-sm-1 text-center">to</div>
              <div class="col-sm-4">
               <div class="row">
              <div class="col-xs-4">
                <select name="hours[ThursdayToH]" class="form-control hour to">
                  <option value="1" <?php if( isset($info["ThursdayToH"]) && $info["ThursdayToH"] == "1" ) echo  "selected"; ?>>1</option>
                  <option value="2" <?php if( isset($info["ThursdayToH"]) && $info["ThursdayToH"] == "2" ) echo  "selected"; ?>>2</option>
                  <option value="3" <?php if( isset($info["ThursdayToH"]) && $info["ThursdayToH"] == "3" ) echo  "selected"; ?>>3</option>
                  <option value="4" <?php if( isset($info["ThursdayToH"]) && $info["ThursdayToH"] == "4" ) echo  "selected"; ?>>4</option>
                  <option value="5" <?php if( isset($info["ThursdayToH"]) && $info["ThursdayToH"] == "5" || $default == true ) echo  "selected"; ?>>5</option>
                  <option value="6" <?php if( isset($info["ThursdayToH"]) && $info["ThursdayToH"] == "6" ) echo  "selected"; ?>>6</option>
                  <option value="7" <?php if( isset($info["ThursdayToH"]) && $info["ThursdayToH"] == "7" ) echo  "selected"; ?>>7</option>
                  <option value="8" <?php if( isset($info["ThursdayToH"]) && $info["ThursdayToH"] == "8" ) echo  "selected"; ?>>8</option>
                  <option value="9" <?php if( isset($info["ThursdayToH"]) && $info["ThursdayToH"] == "9" ) echo  "selected"; ?>>9</option>
                  <option value="10" <?php if( isset($info["ThursdayToH"]) && $info["ThursdayToH"] == "10" ) echo  "selected"; ?>>10</option>
                  <option value="11" <?php if( isset($info["ThursdayToH"]) && $info["ThursdayToH"] == "11" ) echo  "selected"; ?>>11</option>
                  <option value="12" <?php if( isset($info["ThursdayToH"]) && $info["ThursdayToH"] == "12" ) echo  "selected"; ?>>12</option>
                </select>
              </div>
              <div class="col-xs-4">
                <select name="hours[ThursdayToM]" class="form-control min to">
                  <option value=":00" <?php if( isset($info["ThursdayToM"]) && $info["ThursdayToM"] == ":00" ) echo  "selected"; ?>>:00</option>
                  <option value=":15" <?php if( isset($info["ThursdayToM"]) && $info["ThursdayToM"] == ":15" ) echo  "selected"; ?>>:15</option>
                  <option value=":30" <?php if( isset($info["ThursdayToM"]) && $info["ThursdayToM"] == ":30" ) echo  "selected"; ?>>:30</option>
                  <option value=":45" <?php if( isset($info["ThursdayToM"]) && $info["ThursdayToM"] == ":45" ) echo  "selected"; ?>>:45</option>
                </select>
              </div>
              <div class="col-xs-4">
                <select name="hours[ThursdayToAP]" class="form-control ampm to valid">
                  <option value="AM" <?php if( isset($info["ThursdayToAP"]) && $info["ThursdayToAP"] == "AM" ) echo  "selected"; ?>>AM</option>
                  <option value="PM" <?php if( isset($info["ThursdayToAP"]) && $info["ThursdayToAP"] == "PM" || $default == true ) echo  "selected"; ?>>PM</option>
                </select>
              </div>
              </div>
              </div>
              <span class="col-sm-2">
              <input type="checkbox" name="ThursdayClosed" value="closed" class="closed" <?php if( isset($info["ThursdayFromH"]) && $info["ThursdayFromH"] == 0 && !$allclosed ) echo  "checked"; ?>>
              Closed</span></div>
            <div id="Friday" class="row form-group day">
              <div id="label" class="col-sm-1">Friday: </div>
              <div class="col-sm-4">
               <div class="row">
              <div class="col-xs-4">
                <select name="hours[FridayFromH]" class="form-control hour from">
                  <option value="1" <?php if( isset($info["FridayFromH"]) && $info["FridayFromH"] == "1" ) echo  "selected"; ?>>1</option>
                  <option value="2" <?php if( isset($info["FridayFromH"]) && $info["FridayFromH"] == "2" ) echo  "selected"; ?>>2</option>
                  <option value="3" <?php if( isset($info["FridayFromH"]) && $info["FridayFromH"] == "3" ) echo  "selected"; ?>>3</option>
                  <option value="4" <?php if( isset($info["FridayFromH"]) && $info["FridayFromH"] == "4" ) echo  "selected"; ?>>4</option>
                  <option value="5" <?php if( isset($info["FridayFromH"]) && $info["FridayFromH"] == "5" ) echo  "selected"; ?>>5</option>
                  <option value="6" <?php if( isset($info["FridayFromH"]) && $info["FridayFromH"] == "6" ) echo  "selected"; ?>>6</option>
                  <option value="7" <?php if( isset($info["FridayFromH"]) && $info["FridayFromH"] == "7" ) echo  "selected"; ?>>7</option>
                  <option value="8" <?php if( isset($info["FridayFromH"]) && $info["FridayFromH"] == "8" ) echo  "selected"; ?>>8</option>
                  <option value="9" <?php if( isset($info["FridayFromH"]) && $info["FridayFromH"] == "9" || $default == true ) echo  "selected"; ?>>9</option>
                  <option value="10" <?php if( isset($info["FridayFromH"]) && $info["FridayFromH"] == "10" ) echo  "selected"; ?>>10</option>
                  <option value="11" <?php if( isset($info["FridayFromH"]) && $info["FridayFromH"] == "11" ) echo  "selected"; ?>>11</option>
                  <option value="12" <?php if( isset($info["FridayFromH"]) && $info["FridayFromH"] == "12" ) echo  "selected"; ?>>12</option>
                </select>
              </div>
              <div class="col-xs-4">
                <select name="hours[FridayFromM]" class="form-control min from">
                  <option value=":00" <?php if( isset($info["FridayFromM"]) && $info["FridayFromM"] == ":00" ) echo  "selected"; ?>>:00</option>
                  <option value=":15" <?php if( isset($info["FridayFromM"]) && $info["FridayFromM"] == ":15" ) echo  "selected"; ?>>:15</option>
                  <option value=":30" <?php if( isset($info["FridayFromM"]) && $info["FridayFromM"] == ":30" ) echo  "selected"; ?>>:30</option>
                  <option value=":45" <?php if( isset($info["FridayFromM"]) && $info["FridayFromM"] == ":45" ) echo  "selected"; ?>>:45</option>
                </select>
              </div>
              <div class="col-xs-4">
                <select name="hours[FridayFromAP]" class="form-control ampm from">
                  <option value="AM" <?php if( isset($info["FridayFromAP"]) && $info["FridayFromAP"] == "AM" ) echo  "selected"; ?>>AM</option>
                  <option value="PM" <?php if( isset($info["FridayFromAP"]) && $info["FridayFromAP"] == "PM" ) echo  "selected"; ?>>PM</option>
                </select>
              </div>
              </div>
              </div>
              <div class="col-sm-1 text-center">to</div>
              <div class="col-sm-4">
               <div class="row">
              <div class="col-xs-4">
                <select name="hours[FridayToH]" class="form-control hour to">
                  <option value="1" <?php if( isset($info["FridayToH"]) && $info["FridayToH"] == "1" ) echo  "selected"; ?>>1</option>
                  <option value="2" <?php if( isset($info["FridayToH"]) && $info["FridayToH"] == "2" ) echo  "selected"; ?>>2</option>
                  <option value="3" <?php if( isset($info["FridayToH"]) && $info["FridayToH"] == "3" ) echo  "selected"; ?>>3</option>
                  <option value="4" <?php if( isset($info["FridayToH"]) && $info["FridayToH"] == "4" ) echo  "selected"; ?>>4</option>
                  <option value="5" <?php if( isset($info["FridayToH"]) && $info["FridayToH"] == "5" || $default == true ) echo  "selected"; ?>>5</option>
                  <option value="6" <?php if( isset($info["FridayToH"]) && $info["FridayToH"] == "6" ) echo  "selected"; ?>>6</option>
                  <option value="7" <?php if( isset($info["FridayToH"]) && $info["FridayToH"] == "7" ) echo  "selected"; ?>>7</option>
                  <option value="8" <?php if( isset($info["FridayToH"]) && $info["FridayToH"] == "8" ) echo  "selected"; ?>>8</option>
                  <option value="9" <?php if( isset($info["FridayToH"]) && $info["FridayToH"] == "9" ) echo  "selected"; ?>>9</option>
                  <option value="10" <?php if( isset($info["FridayToH"]) && $info["FridayToH"] == "10" ) echo  "selected"; ?>>10</option>
                  <option value="11" <?php if( isset($info["FridayToH"]) && $info["FridayToH"] == "11" ) echo  "selected"; ?>>11</option>
                  <option value="12" <?php if( isset($info["FridayToH"]) && $info["FridayToH"] == "12" ) echo  "selected"; ?>>12</option>
                </select>
              </div>
              <div class="col-xs-4">
                <select name="hours[FridayToM]" class="form-control min to">
                  <option value=":00" <?php if( isset($info["FridayToM"]) && $info["FridayToM"] == ":00" ) echo  "selected"; ?>>:00</option>
                  <option value=":15" <?php if( isset($info["FridayToM"]) && $info["FridayToM"] == ":15" ) echo  "selected"; ?>>:15</option>
                  <option value=":30" <?php if( isset($info["FridayToM"]) && $info["FridayToM"] == ":30" ) echo  "selected"; ?>>:30</option>
                  <option value=":45" <?php if( isset($info["FridayToM"]) && $info["FridayToM"] == ":45" ) echo  "selected"; ?>>:45</option>
                </select>
              </div>
              <div class="col-xs-4">
                <select name="hours[FridayToAP]" class="form-control ampm to">
                  <option value="AM" <?php if( isset($info["FridayToAP"]) && $info["FridayToAP"] == "AM" ) echo  "selected"; ?>>AM</option>
                  <option value="PM" <?php if( isset($info["FridayToAP"]) && $info["FridayToAP"] == "PM" || $default == true ) echo  "selected"; ?>>PM</option>
                </select>
              </div>
              </div>
              </div>
              <span class="col-sm-2">
              <input type="checkbox" name="FridayClosed" value="closed" class="closed" <?php if( isset($info["FridayFromH"]) && $info["FridayFromH"] == 0 && !$allclosed ) echo  "checked"; ?>>
              Closed</span></div>
            <div id="Saturday" class="row form-group day">
              <div id="label" class="col-sm-1">Saturday: </div>
              <div class="col-sm-4">
               <div class="row">
              <div class="col-xs-4">
                <select name="hours[SaturdayFromH]" class="form-control hour from">
                  <option value="1" <?php if( isset($info["SaturdayFromH"]) && $info["SaturdayFromH"] == "1" ) echo  "selected"; ?>>1</option>
                  <option value="2" <?php if( isset($info["SaturdayFromH"]) && $info["SaturdayFromH"] == "2" ) echo  "selected"; ?>>2</option>
                  <option value="3" <?php if( isset($info["SaturdayFromH"]) && $info["SaturdayFromH"] == "3" ) echo  "selected"; ?>>3</option>
                  <option value="4" <?php if( isset($info["SaturdayFromH"]) && $info["SaturdayFromH"] == "4" ) echo  "selected"; ?>>4</option>
                  <option value="5" <?php if( isset($info["SaturdayFromH"]) && $info["SaturdayFromH"] == "5" ) echo  "selected"; ?>>5</option>
                  <option value="6" <?php if( isset($info["SaturdayFromH"]) && $info["SaturdayFromH"] == "6" ) echo  "selected"; ?>>6</option>
                  <option value="7" <?php if( isset($info["SaturdayFromH"]) && $info["SaturdayFromH"] == "7" ) echo  "selected"; ?>>7</option>
                  <option value="8" <?php if( isset($info["SaturdayFromH"]) && $info["SaturdayFromH"] == "8" ) echo  "selected"; ?>>8</option>
                  <option value="9" <?php if( isset($info["SaturdayFromH"]) && $info["SaturdayFromH"] == "9" || $default == true ) echo  "selected"; ?>>9</option>
                  <option value="10" <?php if( isset($info["SaturdayFromH"]) && $info["SaturdayFromH"] == "10" ) echo  "selected"; ?>>10</option>
                  <option value="11" <?php if( isset($info["SaturdayFromH"]) && $info["SaturdayFromH"] == "11" ) echo  "selected"; ?>>11</option>
                  <option value="12" <?php if( isset($info["SaturdayFromH"]) && $info["SaturdayFromH"] == "12" ) echo  "selected"; ?>>12</option>
                </select>
              </div>
              <div class="col-xs-4">
                <select name="hours[SaturdayFromM]" class="form-control min from">
                  <option value=":00" <?php if( isset($info["SaturdayFromM"]) && $info["SaturdayFromM"] == ":00" ) echo  "selected"; ?>>:00</option>
                  <option value=":15" <?php if( isset($info["SaturdayFromM"]) && $info["SaturdayFromM"] == ":15" ) echo  "selected"; ?>>:15</option>
                  <option value=":30" <?php if( isset($info["SaturdayFromM"]) && $info["SaturdayFromM"] == ":30" ) echo  "selected"; ?>>:30</option>
                  <option value=":45" <?php if( isset($info["SaturdayFromM"]) && $info["SaturdayFromM"] == ":45" ) echo  "selected"; ?>>:45</option>
                </select>
              </div>
              <div class="col-xs-4">
                <select name="hours[SaturdayFromAP]" class="form-control ampm from">
                  <option value="AM" <?php if( isset($info["SaturdayFromAP"]) && $info["SaturdayFromAP"] == "AM" ) echo  "selected"; ?>>AM</option>
                  <option value="PM" <?php if( isset($info["SaturdayFromAP"]) && $info["SaturdayFromAP"] == "PM" ) echo  "selected"; ?>>PM</option>
                </select>
              </div>
              </div>
              </div>
              <div class="col-sm-1 text-center">to</div>
              <div class="col-sm-4">
               <div class="row">
              <div class="col-xs-4">
                <select name="hours[SaturdayToH]" class="form-control hour to">
                  <option value="1" <?php if( isset($info["SaturdayToH"]) && $info["SaturdayToH"] == "1" ) echo  "selected"; ?>>1</option>
                  <option value="2" <?php if( isset($info["SaturdayToH"]) && $info["SaturdayToH"] == "2" ) echo  "selected"; ?>>2</option>
                  <option value="3" <?php if( isset($info["SaturdayToH"]) && $info["SaturdayToH"] == "3" ) echo  "selected"; ?>>3</option>
                  <option value="4" <?php if( isset($info["SaturdayToH"]) && $info["SaturdayToH"] == "4" ) echo  "selected"; ?>>4</option>
                  <option value="5" <?php if( isset($info["SaturdayToH"]) && $info["SaturdayToH"] == "5" || $default == true ) echo  "selected"; ?>>5</option>
                  <option value="6" <?php if( isset($info["SaturdayToH"]) && $info["SaturdayToH"] == "6" ) echo  "selected"; ?>>6</option>
                  <option value="7" <?php if( isset($info["SaturdayToH"]) && $info["SaturdayToH"] == "7" ) echo  "selected"; ?>>7</option>
                  <option value="8" <?php if( isset($info["SaturdayToH"]) && $info["SaturdayToH"] == "8" ) echo  "selected"; ?>>8</option>
                  <option value="9" <?php if( isset($info["SaturdayToH"]) && $info["SaturdayToH"] == "9" ) echo  "selected"; ?>>9</option>
                  <option value="10" <?php if( isset($info["SaturdayToH"]) && $info["SaturdayToH"] == "10" ) echo  "selected"; ?>>10</option>
                  <option value="11" <?php if( isset($info["SaturdayToH"]) && $info["SaturdayToH"] == "11" ) echo  "selected"; ?>>11</option>
                  <option value="12" <?php if( isset($info["SaturdayToH"]) && $info["SaturdayToH"] == "12" ) echo  "selected"; ?>>12</option>
                </select>
              </div>
              <div class="col-xs-4">
                <select name="hours[SaturdayToM]" class="form-control min to">
                  <option value=":00" <?php if( isset($info["SaturdayToM"]) && $info["SaturdayToM"] == ":00" ) echo  "selected"; ?>>:00</option>
                  <option value=":15" <?php if( isset($info["SaturdayToM"]) && $info["SaturdayToM"] == ":15" ) echo  "selected"; ?>>:15</option>
                  <option value=":30" <?php if( isset($info["SaturdayToM"]) && $info["SaturdayToM"] == ":30" ) echo  "selected"; ?>>:30</option>
                  <option value=":45" <?php if( isset($info["SaturdayToM"]) && $info["SaturdayToM"] == ":45" ) echo  "selected"; ?>>:45</option>
                </select>
              </div>
              <div class="col-xs-4">
                <select name="hours[SaturdayToAP]" class="form-control ampm to">
                  <option value="AM" <?php if( isset($info["SaturdayToAP"]) && $info["SaturdayToAP"] == "AM" ) echo  "selected"; ?>>AM</option>
                  <option value="PM" <?php if( isset($info["SaturdayToAP"]) && $info["SaturdayToAP"] == "PM" || $default == true ) echo  "selected"; ?>>PM</option>
                </select>
              </div>
              </div>
              </div>
              <span class="col-sm-2">
              <input type="checkbox" name="SaturdayClosed" value="closed" class="closed" <?php if( isset($info["SaturdayFromH"]) && $info["SaturdayFromH"] == 0 && !$allclosed ) echo  "checked"; ?>>
              Closed</span></div>
            <div class="alert alert-info row">
              <div class="col-sm-1">
                <input type="checkbox" name="closed" value="closed" class="allclosed" <?php if( isset($allclosed) && $allclosed == true ) echo  "checked"; ?>>
              </div>
              <span class="col-sm-3">No Hours of Operation</span> </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row" style="margin-top:20px;">
    <div class="col-sm-2"><input class="btn btn-lg btn-primary" id="draft" type="submit" name="draft" value="Save as Draft" /></div>
    <div class="col-sm-10">
      <div class="pull-right"><a class="btn btn-lg btn-default" href="<?php echo $adminHome; ?>">Cancel</a>
        <input class="btn btn-lg btn-primary" id="publish" type="submit" name="publish" value="Publish" />
      </div>
    </div>
  </div>
  <small><span class="red">*</span> Denotes a required field</small>
</form>

<!-- Modal -->
<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post">
        <input type="hidden" name="redirect" value="<?php echo $advertise; ?>">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Sign in</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-3">Email <span class="red">*</span></div>
            <div class="col-sm-9">
              <input class="form-control" type="email" name="email" placeholder="Email" data-rule-email="true" data-rule-required="true" />
            </div>
          </div>
          <div class="row">
            <div class="col-sm-3">Password <span class="red">*</span></div>
            <div class="col-sm-9">
              <input class="form-control" type="password" name="pass" placeholder="Password" minlength="6" data-rule-required="true" />
            </div>
          </div>
          <div class="row">
            <div class="col-sm-4">
              <input type="checkbox" name="remember" value="yes" />
              Keep me logged in </div>
            <div class="col-sm-8 pull-right"><a href="<?php echo $adminForgot; ?>">Forgot Password?</a></div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-default" data-dismiss="modal">Close</button>
          <button name="signin" class="btn btn-primary">Sign in</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php
/* JavaScript for File Uploader */
$footScripts = '';
include 'footer.php'; 
include 'footer_js.php';
?>
  <!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included --> 
  <script src="/jquery-file-upload/js/vendor/jquery.ui.widget.js"></script> 
  <!-- The Templates plugin is included to render the upload/download listings --> 
  <script src="/jquery-file-upload/blueimp/tmpl.min.js"></script> 
  <!-- The Load Image plugin is included for the preview images and image resizing functionality --> 
  <script src="/jquery-file-upload/blueimp/load-image.all.min.js"></script> 
  <!-- The Canvas to Blob plugin is included for image resizing functionality --> 
  <script src="/jquery-file-upload/blueimp/canvas-to-blob.min.js"></script> 
  <!-- blueimp Gallery script --> 
  <script src="/jquery-file-upload/blueimp/jquery.blueimp-gallery.min.js"></script> 
  <!-- The Iframe Transport is required for browsers without support for XHR file uploads --> 
  <script src="/jquery-file-upload/js/jquery.iframe-transport.js"></script> 
  <!-- The basic File Upload plugin --> 
  <script src="/jquery-file-upload/js/jquery.fileupload.js"></script> 
  <!-- The File Upload processing plugin --> 
  <script src="/jquery-file-upload/js/jquery.fileupload-process.js"></script> 
  <!-- The File Upload image preview & resize plugin --> 
  <script src="/jquery-file-upload/js/jquery.fileupload-image.js"></script> 
  <!-- The File Upload audio preview plugin --> 
  <script src="/jquery-file-upload/js/jquery.fileupload-audio.js"></script> 
  <!-- The File Upload video preview plugin --> 
  <script src="/jquery-file-upload/js/jquery.fileupload-video.js"></script> 
  <!-- The File Upload validation plugin --> 
  <script src="/jquery-file-upload/js/jquery.fileupload-validate.js"></script> 
  <!-- The File Upload user interface plugin --> 
  <script src="/jquery-file-upload/js/jquery.fileupload-ui.js"></script> 
  <!-- The main application script --> 
  <script src="/jquery-file-upload/js/main.js"></script> 
  <!-- The XDomainRequest Transport is included for cross-domain file deletion for IE 8 and IE 9 --> 
  <!--[if (gte IE 8)&(lt IE 10)]>
<script src="jquery-file-upload/js/cors/jquery.xdr-transport.js"></script>
<![endif]--> 
<script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js" type="text/javascript"></script> 

<script src="/assets/js/jquery.validate.js"></script> 
<script type="text/javascript">
<!-- VALIDATE FORM -->
$(document).ready(function() {
   	$.validator.setDefaults({
        ignore: []
    });
	$("form.fileupload").validate();
});
<!-- ADD MORE UNITS -->
$("#unit-add").click(function() {
	var counter = $("#unit-rows #rowcount").size() + 1;
	var before = counter - 1;
	var next = counter + 1;
	var order = $("#unit-rows #unit-row").size() + 1;
	$('<input type="hidden" name="u_order' + counter + '" value="' + order + '" /><input type="hidden" id="rowcount" name="rowcount" value="' + counter + '" ><strong>Unit ' + order + '</strong>').appendTo("#unit-rows");
	$("#unit-rows")
	.append($("#unit-row")
	.clone()
	.prepend( $('<a href="#" class="pull-right btn btn-default btn-small delete-btn" id="unit-remove"><i class="icon-trash"></i> Delete</a></div>') )
	.find("[name]")
	.each(function() { $(this).attr("name", $(this).attr("name").replace(/\[.*?\]/g,"") + counter); })
	.end());
	return false;
});
$("#unit-rows").on("click","#unit-remove",function(){
	$(this).parents("dd").prev().remove();
	$(this).parents("dd").remove();
     if( $("#unit-remove").not(":button") ){
	 	return false;
	}
});
<!-- HOURS OF OPERATION -->
// Only execute after all elements have been loaded
$(window).load(function(){
	<?php 
	if($type == "Create" || $allclosed == true){
	?>
	//Check all closed when prop is new or database has no values
	$('input.allclosed').prop('checked', true);
	<?php
	}
	?>
	
	//Call functions
	$("input.closed").change(closed).change();
	$("input.allclosed").change(allclosed).change();
	function closed() {
		if( $(this).is(":checked")) {
			$(this).closest(".day").find("select").attr("disabled", true);
		} else {
			// Only uncheck allclose if day checkbox was clicked
			$(this).on("click",function(){
				$("#hoursOp").find("input.allclosed").prop("checked",false);
			});
			$(this).closest(".day").find("select").attr("disabled", false);
		}
	}
	function allclosed() {
	   if($(this).filter(":checked").val() == "closed") {
			$("#hoursOp").find("select").attr("disabled", true);
			$("#hoursOp").find("input.closed").prop("checked",true);
		} else {
			$("#hoursOp").find("select").attr("disabled", false);
			$("#hoursOp").find("input.closed").prop("checked",false);
		}
	}
});
<!-- Track Google Event -->
$("#publish").click(function() {
	_gaq.push(["_trackEvent", "<?php echo $type; ?> Apartment Listing", "Publish", "<?php echo $user; ?>"]);
});
$("#draft").click(function() {
	_gaq.push(["_trackEvent", "<?php echo $type; ?> Apartment Listing", "Draft", "<?php echo $user; ?>"]);
});
</script>
</body></html>