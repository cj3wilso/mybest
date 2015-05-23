<h2>Property Information</h2>
<div class="row">
  <div class="col-lg-2">Property Name <span class="red">*</span></div>
  <div class="col-lg-3"><input class="form-control required" type="text" name="p_name" data-placement="bottom" value="<?php echo $info['name']; ?>" /></div>
  <div class="col-lg-2">Phone</div>
  <div class="col-lg-1"><input class="form-control" type="tel" name="p_phone1" minlength="3" maxlength="3" value="<?php echo $info['phone1']; ?>" /></div>
  <div class="col-lg-1" style="padding-left:0px;"><input class="form-control" type="tel" name="p_phone2" minlength="3" maxlength="3" value="<?php echo $info['phone2']; ?>" /></div>
  <div class="col-lg-1" style="padding-left:0px;"><input class="form-control" type="tel" name="p_phone3" minlength="4" maxlength="4" value="<?php echo $info['phone3']; ?>" /></div>
</div>
<div class="row">
  <div class="col-lg-2">Email <span class="red">*</span></div>
  <div class="col-lg-3"><input class="form-control required" type="email" name="p_email" data-placement="bottom" value="<?php echo $info['email']; ?>" /></div>
  <div class="col-lg-2">URL </div>
  <div class="col-lg-3"><input class="form-control url" type="url" name="p_url" value="<?php echo $info['url']; ?>" /></div>
</div>
<div class="row">
  <div class="col-lg-2">Address 1<span class="red">*</span></div>
  <div class="col-lg-8"><input class="form-control required" type="text" name="p_address" id="address" autocomplete="off" data-placement="bottom" value="<?php echo $formed_address; ?>"></div>
</div>
<div class="row">
  <div class="col-lg-2">Address 2</div>
  <div class="col-lg-8"><input class="form-control" type="text" name="p_address2" value="<?php echo $info['address2']; ?>"></div>
</div>
<h2>Unit Details</h2>
<dl id="unit-rows">
<?php
$rows = 1;
for ($i=0; $i<$line; $i++) {
	if($prop){
		$units = mysql_fetch_assoc($info3);
		$order = $units['u_order'];
	}else{
		$order = $i;
	}
?>
  
  <input type="hidden" name="u_order[<?php echo $order; ?>]" value="<?php echo $order; ?>" />
  <h3>Unit <?php echo $rows; ?></h3>
  <dd id="unit-row">
    <?php
    	if($rows != 1){
		?>
    <button class="pull-right btn btn-default btn-small" name="remove[]" value="<?php echo $units['u_order']; ?>" type="submit"><i class="icon-trash"></i> Delete</button>
    <!--  id="unit-remove" -->
    <?php
		}
		?>
    <div class="row">
      <div class="col-lg-2">Rent <span class="red">*</span></div>
      <div class="col-lg-3"><input class="form-control required" type="text" name="u_rent[<?php echo $units['rent']; ?>]" data-placement="bottom" value="<?php echo $units['rent']; ?>" /></div>
      <div class="col-lg-2">Style <span class="red">*</span></div>
      <div class="col-lg-3"><select class="form-control required" name="u_style[<?php echo $units['style']; ?>]" data-placement="bottom">
        <option value="">Please select</option>
        <option value="Apartment" <?php if( $units["style"] == "Apartment" ) echo  "selected"; ?>>Apartment</option>
        <option value="Condo" <?php if( $units["style"] == "Condo" ) echo  "selected"; ?>>Condo</option>
        <option value="House" <?php if( $units["style"] == "House" ) echo  "selected"; ?>>House</option>
        <option value="Townhome" <?php if( $units["style"] == "Townhome" ) echo  "selected"; ?>>Townhome</option>
        <option value="Loft" <?php if( $units["style"] == "Loft" ) echo  "selected"; ?>>Loft</option>
      </select></div>
    </div>
    <div class="row">
      <div class="col-lg-2">Bedrooms <span class="red">*</span></div>
      <div class="col-lg-3"><select class="form-control required" name="u_bed[<?php echo $units['beds']; ?>]" data-placement="bottom">
        <option value="">Please select</option>
        <option value="Studio" <?php if( $units["beds"] == "Studio" ) echo  "selected"; ?>>Studio</option>
        <option value="1" <?php if( $units["beds"] == "1" ) echo  "selected"; ?>>1 Bedroom</option>
        <option value="1 plus Den" <?php if( $units["beds"] == "1 plus Den" ) echo  "selected"; ?>>1 Bedroom + Den</option>
        <option value="2" <?php if( $units["beds"] == "2" ) echo  "selected"; ?>>2 Bedrooms</option>
        <option value="2 plus Den" <?php if( $units["beds"] == "2 plus Den" ) echo  "selected"; ?>>2 Bedroom + Den</option>
        <option value="3" <?php if( $units["beds"] == "3" ) echo  "selected"; ?>>3 Bedrooms</option>
        <option value="4" <?php if( $units["beds"] == "4" ) echo  "selected"; ?>>4 Bedrooms</option>
      </select></div>
      <div class="col-lg-2">Bathrooms <span class="red">*</span></div>
      <div class="col-lg-3"><select class="form-control required" name="u_bath[<?php echo $units['ba']; ?>]" data-placement="bottom">
        <option value="">Please select</option>
        <option value="1" <?php if( $units["ba"] == "1" ) echo  "selected"; ?>>1 Bathroom</option>
        <option value="2" <?php if( $units["ba"] == "2" ) echo  "selected"; ?>>2 Bathrooms</option>
        <option value="3" <?php if( $units["ba"] == "3" ) echo  "selected"; ?>>3 Bathrooms</option>
        <option value="4" <?php if( $units["ba"] == "4" ) echo  "selected"; ?>>4 Bathrooms</option>
      </select></div>
    </div>
    <div class="row">
      <div class="col-lg-2">Square Feet </div>
      <div class="col-lg-3"><input class="form-control" type="text" name="u_sq[<?php echo $units['sq_ft']; ?>]" data-placement="bottom" value="<?php echo $units['sq_ft']; ?>" /></div>
      <div class="col-lg-2">Deposit </div>
      <div class="col-lg-3"><input class="form-control" type="text" name="u_dep[<?php echo $units['dep']; ?>]" value="<?php echo $units['dep']; ?>" /></div>
    </div>
  </dd>
  <?php
	$rows++;
	}
	?>
</dl>
<div class="row"><a href="#" id="unit-add">Add Another Unit</a></div>
<h2>Property Description</h2>
<div class="row">
  <div class="col-lg-2">Description <span class="red">*</span></div>
</div>
<div class="row">
  <div class="col-lg-12"><textarea class="form-control required" name="d_desc" rows="5" maxlength="2800" minlength="100" data-placement="bottom"><?php echo $info['text']; ?></textarea></div>
</div>
<?php
include 'pg_feat_options.php';
?>
<h2>Hours of Operation</h2>
<div id="hourForm">
  <div id="Sunday" class="row day">
    <?php 
	  if( $info["SundayFromH"] == 0 && $info["MondayFromH"] == 0 && $info["TuesdayFromH"] == 0 && $info["WednesdayFromH"] == 0 && $info["ThursdayFromH"] == 0 && $info["FridayFromH"] == 0 && $info["SaturdayFromH"] == 0 && $default != true )
	  $allclosed = true;
	  ?>
    <div id="label" class="col-lg-1">Sunday: </div>
    <div class="col-lg-1"><select name="hours[SundayFromH]" class="form-control hour from">
      <option value="1" <?php if( $info["SundayFromH"] == "1" ) echo  "selected"; ?>>1</option>
      <option value="2" <?php if( $info["SundayFromH"] == "2" ) echo  "selected"; ?>>2</option>
      <option value="3" <?php if( $info["SundayFromH"] == "3" ) echo  "selected"; ?>>3</option>
      <option value="4" <?php if( $info["SundayFromH"] == "4" ) echo  "selected"; ?>>4</option>
      <option value="5" <?php if( $info["SundayFromH"] == "5" ) echo  "selected"; ?>>5</option>
      <option value="6" <?php if( $info["SundayFromH"] == "6" ) echo  "selected"; ?>>6</option>
      <option value="7" <?php if( $info["SundayFromH"] == "7" ) echo  "selected"; ?>>7</option>
      <option value="8" <?php if( $info["SundayFromH"] == "8" ) echo  "selected"; ?>>8</option>
      <option value="9" <?php if( $info["SundayFromH"] == "9" || $default == true ) echo  "selected"; ?>>9</option>
      <option value="10" <?php if( $info["SundayFromH"] == "10" ) echo  "selected"; ?>>10</option>
      <option value="11" <?php if( $info["SundayFromH"] == "11" ) echo  "selected"; ?>>11</option>
      <option value="12" <?php if( $info["SundayFromH"] == "12" ) echo  "selected"; ?>>12</option>
    </select></div>
    <div class="col-lg-1"><select name="hours[SundayFromM]" class="form-control min from">
      <option value=":00" <?php if( $info["SundayFromH"] == ":00" ) echo  "selected"; ?>>:00</option>
      <option value=":15" <?php if( $info["SundayFromH"] == ":15" ) echo  "selected"; ?>>:15</option>
      <option value=":30" <?php if( $info["SundayFromH"] == ":30" ) echo  "selected"; ?>>:30</option>
      <option value=":45" <?php if( $info["SundayFromH"] == ":45" ) echo  "selected"; ?>>:45</option>
    </select></div>
    <div class="col-lg-1"><select name="hours[SundayFromAP]" class="form-control ampm from">
      <option value="AM" <?php if( $info["SundayFromH"] == "AM" ) echo  "selected"; ?>>AM</option>
      <option value="PM" <?php if( $info["SundayFromH"] == "PM" ) echo  "selected"; ?>>PM</option>
    </select></div>
    <div class="col-lg-1 text-center">to</div>
    <div class="col-lg-1"><select name="hours[SundayToH]" class="form-control hour to">
      <option value="1" <?php if( $info["SundayToH"] == "1" ) echo  "selected"; ?>>1</option>
      <option value="2" <?php if( $info["SundayToH"] == "2" ) echo  "selected"; ?>>2</option>
      <option value="3" <?php if( $info["SundayToH"] == "3" ) echo  "selected"; ?>>3</option>
      <option value="4" <?php if( $info["SundayToH"] == "4" ) echo  "selected"; ?>>4</option>
      <option value="5" <?php if( $info["SundayToH"] == "5" || $default == true ) echo  "selected"; ?>>5</option>
      <option value="6" <?php if( $info["SundayToH"] == "6" ) echo  "selected"; ?>>6</option>
      <option value="7" <?php if( $info["SundayToH"] == "7" ) echo  "selected"; ?>>7</option>
      <option value="8" <?php if( $info["SundayToH"] == "8" ) echo  "selected"; ?>>8</option>
      <option value="9" <?php if( $info["SundayToH"] == "9" ) echo  "selected"; ?>>9</option>
      <option value="10" <?php if( $info["SundayToH"] == "10" ) echo  "selected"; ?>>10</option>
      <option value="11" <?php if( $info["SundayToH"] == "11" ) echo  "selected"; ?>>11</option>
      <option value="12" <?php if( $info["SundayToH"] == "12" ) echo  "selected"; ?>>12</option>
    </select></div>
    <div class="col-lg-1"><select name="hours[SundayToM]" class="form-control min to">
      <option value=":00" <?php if( $info["SundayToM"] == ":00" ) echo  "selected"; ?>>:00</option>
      <option value=":15" <?php if( $info["SundayToM"] == ":15" ) echo  "selected"; ?>>:15</option>
      <option value=":30" <?php if( $info["SundayToM"] == ":30" ) echo  "selected"; ?>>:30</option>
      <option value=":45" <?php if( $info["SundayToM"] == ":45" ) echo  "selected"; ?>>:45</option>
    </select></div>
    <div class="col-lg-1"><select name="hours[SundayToAP]" class="form-control ampm to">
      <option value="AM" <?php if( $info["SundayToAP"] == "AM" ) echo  "selected"; ?>>AM</option>
      <option value="PM" <?php if( $info["SundayToAP"] == "PM" || $default == true ) echo  "selected"; ?>>PM</option>
    </select></div>
    <span class="col-lg-2">
    <input type="checkbox" name="SundayClosed" value="closed" class="closed" <?php if( $info["SundayFromH"] == 0 && !$allclosed ) echo  "checked"; ?>>
    Closed</span></div>
  <div id="Monday" class="row day">
    <div id="label" class="col-lg-1">Monday: </div>
    <div class="col-lg-1"><select name="hours[MondayFromH]" class="form-control hour from">
      <option value="1" <?php if( $info["MondayFromH"] == "1" ) echo  "selected"; ?>>1</option>
      <option value="2" <?php if( $info["MondayFromH"] == "2" ) echo  "selected"; ?>>2</option>
      <option value="3" <?php if( $info["MondayFromH"] == "3" ) echo  "selected"; ?>>3</option>
      <option value="4" <?php if( $info["MondayFromH"] == "4" ) echo  "selected"; ?>>4</option>
      <option value="5" <?php if( $info["MondayFromH"] == "5" ) echo  "selected"; ?>>5</option>
      <option value="6" <?php if( $info["MondayFromH"] == "6" ) echo  "selected"; ?>>6</option>
      <option value="7" <?php if( $info["MondayFromH"] == "7" ) echo  "selected"; ?>>7</option>
      <option value="8" <?php if( $info["MondayFromH"] == "8" ) echo  "selected"; ?>>8</option>
      <option value="9" <?php if( $info["MondayFromH"] == "9" || $default == true ) echo  "selected"; ?>>9</option>
      <option value="10" <?php if( $info["MondayFromH"] == "10" ) echo  "selected"; ?>>10</option>
      <option value="11" <?php if( $info["MondayFromH"] == "11" ) echo  "selected"; ?>>11</option>
      <option value="12" <?php if( $info["MondayFromH"] == "12" ) echo  "selected"; ?>>12</option>
    </select></div>
    <div class="col-lg-1"><select name="hours[MondayFromM]" class="form-control min from">
      <option value=":00" <?php if( $info["MondayFromM"] == ":00" ) echo  "selected"; ?>>:00</option>
      <option value=":15" <?php if( $info["MondayFromM"] == ":15" ) echo  "selected"; ?>>:15</option>
      <option value=":30" <?php if( $info["MondayFromM"] == ":30" ) echo  "selected"; ?>>:30</option>
      <option value=":45" <?php if( $info["MondayFromM"] == ":45" ) echo  "selected"; ?>>:45</option>
    </select></div>
    <div class="col-lg-1"><select name="hours[MondayFromAP]" class="form-control ampm from">
      <option value="AM" <?php if( $info["MondayFromAP"] == "AM" ) echo  "selected"; ?>>AM</option>
      <option value="PM" <?php if( $info["MondayFromAP"] == "PM" ) echo  "selected"; ?>>PM</option>
    </select></div>
    <div class="col-lg-1 text-center">to</div>
    <div class="col-lg-1"><select name="hours[MondayToH]" class="form-control hour to">
      <option value="1" <?php if( $info["MondayToH"] == "1" ) echo  "selected"; ?>>1</option>
      <option value="2" <?php if( $info["MondayToH"] == "2" ) echo  "selected"; ?>>2</option>
      <option value="3" <?php if( $info["MondayToH"] == "3" ) echo  "selected"; ?>>3</option>
      <option value="4" <?php if( $info["MondayToH"] == "4" ) echo  "selected"; ?>>4</option>
      <option value="5" <?php if( $info["MondayToH"] == "5" || $default == true ) echo  "selected"; ?>>5</option>
      <option value="6" <?php if( $info["MondayToH"] == "6" ) echo  "selected"; ?>>6</option>
      <option value="7" <?php if( $info["MondayToH"] == "7" ) echo  "selected"; ?>>7</option>
      <option value="8" <?php if( $info["MondayToH"] == "8" ) echo  "selected"; ?>>8</option>
      <option value="9" <?php if( $info["MondayToH"] == "9" ) echo  "selected"; ?>>9</option>
      <option value="10" <?php if( $info["MondayToH"] == "10" ) echo  "selected"; ?>>10</option>
      <option value="11" <?php if( $info["MondayToH"] == "11" ) echo  "selected"; ?>>11</option>
      <option value="12" <?php if( $info["MondayToH"] == "12" ) echo  "selected"; ?>>12</option>
    </select></div>
    <div class="col-lg-1"><select name="hours[MondayToM]" class="form-control min to">
      <option value=":00" <?php if( $info["MondayToM"] == ":00" ) echo  "selected"; ?>>:00</option>
      <option value=":15" <?php if( $info["MondayToM"] == ":15" ) echo  "selected"; ?>>:15</option>
      <option value=":30" <?php if( $info["MondayToM"] == ":30" ) echo  "selected"; ?>>:30</option>
      <option value=":45" <?php if( $info["MondayToM"] == ":45" ) echo  "selected"; ?>>:45</option>
    </select></div>
    <div class="col-lg-1"><select name="hours[MondayToAP]" class="form-control ampm to">
      <option value="AM" <?php if( $info["MondayToAP"] == "AM" ) echo  "selected"; ?>>AM</option>
      <option value="PM" <?php if( $info["MondayToAP"] == "PM" || $default == true ) echo  "selected"; ?>>PM</option>
    </select></div>
    <span class="col-lg-2">
    <input type="checkbox" name="MondayClosed" value="closed" class="closed" <?php if( $info["MondayFromH"] == 0 && !$allclosed ) echo  "checked"; ?>>
    Closed</span></div>
  <div id="Tuesday" class="row day">
    <div id="label" class="col-lg-1">Tuesday: </div>
    <div class="col-lg-1"><select name="hours[TuesdayFromH]" class="form-control hour from">
      <option value="1" <?php if( $info["TuesdayFromH"] == "1" ) echo  "selected"; ?>>1</option>
      <option value="2" <?php if( $info["TuesdayFromH"] == "2" ) echo  "selected"; ?>>2</option>
      <option value="3" <?php if( $info["TuesdayFromH"] == "3" ) echo  "selected"; ?>>3</option>
      <option value="4" <?php if( $info["TuesdayFromH"] == "4" ) echo  "selected"; ?>>4</option>
      <option value="5" <?php if( $info["TuesdayFromH"] == "5" ) echo  "selected"; ?>>5</option>
      <option value="6" <?php if( $info["TuesdayFromH"] == "6" ) echo  "selected"; ?>>6</option>
      <option value="7" <?php if( $info["TuesdayFromH"] == "7" ) echo  "selected"; ?>>7</option>
      <option value="8" <?php if( $info["TuesdayFromH"] == "8" ) echo  "selected"; ?>>8</option>
      <option value="9" <?php if( $info["TuesdayFromH"] == "9" || $default == true ) echo  "selected"; ?>>9</option>
      <option value="10" <?php if( $info["TuesdayFromH"] == "10" ) echo  "selected"; ?>>10</option>
      <option value="11" <?php if( $info["TuesdayFromH"] == "11" ) echo  "selected"; ?>>11</option>
      <option value="12" <?php if( $info["TuesdayFromH"] == "12" ) echo  "selected"; ?>>12</option>
    </select></div>
    <div class="col-lg-1"><select name="hours[TuesdayFromM]" class="form-control min from">
      <option value=":00" <?php if( $info["TuesdayFromM"] == ":00" ) echo  "selected"; ?>>:00</option>
      <option value=":15" <?php if( $info["TuesdayFromM"] == ":15" ) echo  "selected"; ?>>:15</option>
      <option value=":30" <?php if( $info["TuesdayFromM"] == ":30" ) echo  "selected"; ?>>:30</option>
      <option value=":45" <?php if( $info["TuesdayFromM"] == ":45" ) echo  "selected"; ?>>:45</option>
    </select></div>
    <div class="col-lg-1"><select name="hours[TuesdayFromAP]" class="form-control ampm from">
      <option value="AM" <?php if( $info["TuesdayFromAP"] == "AM" ) echo  "selected"; ?>>AM</option>
      <option value="PM" <?php if( $info["TuesdayFromAP"] == "PM" ) echo  "selected"; ?>>PM</option>
    </select></div>
    <div class="col-lg-1 text-center">to</div>
    <div class="col-lg-1"><select name="hours[TuesdayToH]" class="form-control hour to">
      <option value="1" <?php if( $info["TuesdayToH"] == "1" ) echo  "selected"; ?>>1</option>
      <option value="2" <?php if( $info["TuesdayToH"] == "2" ) echo  "selected"; ?>>2</option>
      <option value="3" <?php if( $info["TuesdayToH"] == "3" ) echo  "selected"; ?>>3</option>
      <option value="4" <?php if( $info["TuesdayToH"] == "4" ) echo  "selected"; ?>>4</option>
      <option value="5" <?php if( $info["TuesdayToH"] == "5" || $default == true ) echo  "selected"; ?>>5</option>
      <option value="6" <?php if( $info["TuesdayToH"] == "6" ) echo  "selected"; ?>>6</option>
      <option value="7" <?php if( $info["TuesdayToH"] == "7" ) echo  "selected"; ?>>7</option>
      <option value="8" <?php if( $info["TuesdayToH"] == "8" ) echo  "selected"; ?>>8</option>
      <option value="9" <?php if( $info["TuesdayToH"] == "9" ) echo  "selected"; ?>>9</option>
      <option value="10" <?php if( $info["TuesdayToH"] == "10" ) echo  "selected"; ?>>10</option>
      <option value="11" <?php if( $info["TuesdayToH"] == "11" ) echo  "selected"; ?>>11</option>
      <option value="12" <?php if( $info["TuesdayToH"] == "12" ) echo  "selected"; ?>>12</option>
    </select></div>
    <div class="col-lg-1"><select name="hours[TuesdayToM]" class="form-control min to">
      <option value=":00" <?php if( $info["TuesdayToM"] == ":00" ) echo  "selected"; ?>>:00</option>
      <option value=":15" <?php if( $info["TuesdayToM"] == ":15" ) echo  "selected"; ?>>:15</option>
      <option value=":30" <?php if( $info["TuesdayToM"] == ":30" ) echo  "selected"; ?>>:30</option>
      <option value=":45" <?php if( $info["TuesdayToM"] == ":45" ) echo  "selected"; ?>>:45</option>
    </select></div>
    <div class="col-lg-1"><select name="hours[TuesdayToAP]" class="form-control ampm to">
      <option value="AM" <?php if( $info["TuesdayToAP"] == "AM" ) echo  "selected"; ?>>AM</option>
      <option value="PM" <?php if( $info["TuesdayToAP"] == "PM" || $default == true ) echo  "selected"; ?>>PM</option>
    </select></div>
    <span class="col-lg-2">
    <input type="checkbox" name="closed" value="TuesdayClosed" class="closed" <?php if( $info["TuesdayFromH"] == 0 && !$allclosed ) echo  "checked"; ?>>
    Closed</span></div>
  <div id="Wednesday" class="row day">
    <div id="label" class="col-lg-1">Wednesday: </div>
    <div class="col-lg-1"><select name="hours[WednesdayFromH]" class="form-control hour from">
      <option value="1" <?php if( $info["WednesdayFromH"] == "1" ) echo  "selected"; ?>>1</option>
      <option value="2" <?php if( $info["WednesdayFromH"] == "2" ) echo  "selected"; ?>>2</option>
      <option value="3" <?php if( $info["WednesdayFromH"] == "3" ) echo  "selected"; ?>>3</option>
      <option value="4" <?php if( $info["WednesdayFromH"] == "4" ) echo  "selected"; ?>>4</option>
      <option value="5" <?php if( $info["WednesdayFromH"] == "5" ) echo  "selected"; ?>>5</option>
      <option value="6" <?php if( $info["WednesdayFromH"] == "6" ) echo  "selected"; ?>>6</option>
      <option value="7" <?php if( $info["WednesdayFromH"] == "7" ) echo  "selected"; ?>>7</option>
      <option value="8" <?php if( $info["WednesdayFromH"] == "8" ) echo  "selected"; ?>>8</option>
      <option value="9" <?php if( $info["WednesdayFromH"] == "9" || $default == true ) echo  "selected"; ?>>9</option>
      <option value="10" <?php if( $info["WednesdayFromH"] == "10" ) echo  "selected"; ?>>10</option>
      <option value="11" <?php if( $info["WednesdayFromH"] == "11" ) echo  "selected"; ?>>11</option>
      <option value="12" <?php if( $info["WednesdayFromH"] == "12" ) echo  "selected"; ?>>12</option>
    </select></div>
    <div class="col-lg-1"><select name="hours[WednesdayFromM]" class="form-control min from">
      <option value=":00" <?php if( $info["WednesdayFromM"] == ":00" ) echo  "selected"; ?>>:00</option>
      <option value=":15" <?php if( $info["WednesdayFromM"] == ":15" ) echo  "selected"; ?>>:15</option>
      <option value=":30" <?php if( $info["WednesdayFromM"] == ":30" ) echo  "selected"; ?>>:30</option>
      <option value=":45" <?php if( $info["WednesdayFromM"] == ":45" ) echo  "selected"; ?>>:45</option>
    </select></div>
    <div class="col-lg-1"><select name="hours[WednesdayFromAP]" class="form-control ampm from">
      <option value="AM" <?php if( $info["WednesdayFromAP"] == "AM" ) echo  "selected"; ?>>AM</option>
      <option value="PM" <?php if( $info["WednesdayFromAP"] == "PM" ) echo  "selected"; ?>>PM</option>
    </select></div>
    <div class="col-lg-1 text-center">to</div>
    <div class="col-lg-1"><select name="hours[WednesdayToH]" class="form-control hour to">
      <option value="1" <?php if( $info["WednesdayToH"] == "1" ) echo  "selected"; ?>>1</option>
      <option value="2" <?php if( $info["WednesdayToH"] == "2" ) echo  "selected"; ?>>2</option>
      <option value="3" <?php if( $info["WednesdayToH"] == "3" ) echo  "selected"; ?>>3</option>
      <option value="4" <?php if( $info["WednesdayToH"] == "4" ) echo  "selected"; ?>>4</option>
      <option value="5" <?php if( $info["WednesdayToH"] == "5" || $default == true ) echo  "selected"; ?>>5</option>
      <option value="6" <?php if( $info["WednesdayToH"] == "6" ) echo  "selected"; ?>>6</option>
      <option value="7" <?php if( $info["WednesdayToH"] == "7" ) echo  "selected"; ?>>7</option>
      <option value="8" <?php if( $info["WednesdayToH"] == "8" ) echo  "selected"; ?>>8</option>
      <option value="9" <?php if( $info["WednesdayToH"] == "9" ) echo  "selected"; ?>>9</option>
      <option value="10" <?php if( $info["WednesdayToH"] == "10" ) echo  "selected"; ?>>10</option>
      <option value="11" <?php if( $info["WednesdayToH"] == "11" ) echo  "selected"; ?>>11</option>
      <option value="12" <?php if( $info["WednesdayToH"] == "12" ) echo  "selected"; ?>>12</option>
    </select></div>
    <div class="col-lg-1"><select name="hours[WednesdayToM]" class="form-control min to">
      <option value=":00" <?php if( $info["WednesdayToM"] == ":00" ) echo  "selected"; ?>>:00</option>
      <option value=":15" <?php if( $info["WednesdayToM"] == ":15" ) echo  "selected"; ?>>:15</option>
      <option value=":30" <?php if( $info["WednesdayToM"] == ":30" ) echo  "selected"; ?>>:30</option>
      <option value=":45" <?php if( $info["WednesdayToM"] == ":45" ) echo  "selected"; ?>>:45</option>
    </select></div>
    <div class="col-lg-1"><select name="hours[WednesdayToAP]" class="form-control ampm to">
      <option value="AM" <?php if( $info["WednesdayToAP"] == "AM" ) echo  "selected"; ?>>AM</option>
      <option value="PM" <?php if( $info["WednesdayToAP"] == "PM" || $default == true ) echo  "selected"; ?>>PM</option>
    </select></div>
    <span class="col-lg-2">
    <input type="checkbox" name="WednesdayClosed" value="closed" class="closed" <?php if( $info["WednesdayFromH"] == 0 && !$allclosed ) echo  "checked"; ?>>
    Closed</span></div>
  <div id="Thursday" class="row day">
    <div id="label" class="col-lg-1">Thursday: </div>
    <div class="col-lg-1"><select name="hours[ThursdayFromH]" class="form-control hour from">
      <option value="1" <?php if( $info["ThursdayFromH"] == "1" ) echo  "selected"; ?>>1</option>
      <option value="2" <?php if( $info["ThursdayFromH"] == "2" ) echo  "selected"; ?>>2</option>
      <option value="3" <?php if( $info["ThursdayFromH"] == "3" ) echo  "selected"; ?>>3</option>
      <option value="4" <?php if( $info["ThursdayFromH"] == "4" ) echo  "selected"; ?>>4</option>
      <option value="5" <?php if( $info["ThursdayFromH"] == "5" ) echo  "selected"; ?>>5</option>
      <option value="6" <?php if( $info["ThursdayFromH"] == "6" ) echo  "selected"; ?>>6</option>
      <option value="7" <?php if( $info["ThursdayFromH"] == "7" ) echo  "selected"; ?>>7</option>
      <option value="8" <?php if( $info["ThursdayFromH"] == "8" ) echo  "selected"; ?>>8</option>
      <option value="9" <?php if( $info["ThursdayFromH"] == "9" || $default == true ) echo  "selected"; ?>>9</option>
      <option value="10" <?php if( $info["ThursdayFromH"] == "10" ) echo  "selected"; ?>>10</option>
      <option value="11" <?php if( $info["ThursdayFromH"] == "11" ) echo  "selected"; ?>>11</option>
      <option value="12" <?php if( $info["ThursdayFromH"] == "12" ) echo  "selected"; ?>>12</option>
    </select></div>
    <div class="col-lg-1"><select name="hours[ThursdayFromM]" class="form-control min from">
      <option value=":00" <?php if( $info["ThursdayFromM"] == ":00" ) echo  "selected"; ?>>:00</option>
      <option value=":15" <?php if( $info["ThursdayFromM"] == ":15" ) echo  "selected"; ?>>:15</option>
      <option value=":30" <?php if( $info["ThursdayFromM"] == ":30" ) echo  "selected"; ?>>:30</option>
      <option value=":45" <?php if( $info["ThursdayFromM"] == ":45" ) echo  "selected"; ?>>:45</option>
    </select></div>
    <div class="col-lg-1"><select name="hours[ThursdayFromAP]" class="form-control ampm from">
      <option value="AM" <?php if( $info["ThursdayFromAP"] == "AM" ) echo  "selected"; ?>>AM</option>
      <option value="PM" <?php if( $info["ThursdayFromAP"] == "PM" ) echo  "selected"; ?>>PM</option>
    </select></div>
    <div class="col-lg-1 text-center">to</div>
    <div class="col-lg-1"><select name="hours[ThursdayToH]" class="form-control hour to">
      <option value="1" <?php if( $info["ThursdayToH"] == "1" ) echo  "selected"; ?>>1</option>
      <option value="2" <?php if( $info["ThursdayToH"] == "2" ) echo  "selected"; ?>>2</option>
      <option value="3" <?php if( $info["ThursdayToH"] == "3" ) echo  "selected"; ?>>3</option>
      <option value="4" <?php if( $info["ThursdayToH"] == "4" ) echo  "selected"; ?>>4</option>
      <option value="5" <?php if( $info["ThursdayToH"] == "5" || $default == true ) echo  "selected"; ?>>5</option>
      <option value="6" <?php if( $info["ThursdayToH"] == "6" ) echo  "selected"; ?>>6</option>
      <option value="7" <?php if( $info["ThursdayToH"] == "7" ) echo  "selected"; ?>>7</option>
      <option value="8" <?php if( $info["ThursdayToH"] == "8" ) echo  "selected"; ?>>8</option>
      <option value="9" <?php if( $info["ThursdayToH"] == "9" ) echo  "selected"; ?>>9</option>
      <option value="10" <?php if( $info["ThursdayToH"] == "10" ) echo  "selected"; ?>>10</option>
      <option value="11" <?php if( $info["ThursdayToH"] == "11" ) echo  "selected"; ?>>11</option>
      <option value="12" <?php if( $info["ThursdayToH"] == "12" ) echo  "selected"; ?>>12</option>
    </select></div>
    <div class="col-lg-1"><select name="hours[ThursdayToM]" class="form-control min to">
      <option value=":00" <?php if( $info["ThursdayToM"] == ":00" ) echo  "selected"; ?>>:00</option>
      <option value=":15" <?php if( $info["ThursdayToM"] == ":15" ) echo  "selected"; ?>>:15</option>
      <option value=":30" <?php if( $info["ThursdayToM"] == ":30" ) echo  "selected"; ?>>:30</option>
      <option value=":45" <?php if( $info["ThursdayToM"] == ":45" ) echo  "selected"; ?>>:45</option>
    </select></div>
    <div class="col-lg-1"><select name="hours[ThursdayToAP]" class="form-control ampm to valid">
      <option value="AM" <?php if( $info["ThursdayToAP"] == "AM" ) echo  "selected"; ?>>AM</option>
      <option value="PM" <?php if( $info["ThursdayToAP"] == "PM" || $default == true ) echo  "selected"; ?>>PM</option>
    </select></div>
    <span class="col-lg-2">
    <input type="checkbox" name="ThursdayClosed" value="closed" class="closed" <?php if( $info["ThursdayFromH"] == 0 && !$allclosed ) echo  "checked"; ?>>
    Closed</span></div>
  <div id="Friday" class="row day">
    <div id="label" class="col-lg-1">Friday: </div>
    <div class="col-lg-1"><select name="hours[FridayFromH]" class="form-control hour from">
      <option value="1" <?php if( $info["FridayFromH"] == "1" ) echo  "selected"; ?>>1</option>
      <option value="2" <?php if( $info["FridayFromH"] == "2" ) echo  "selected"; ?>>2</option>
      <option value="3" <?php if( $info["FridayFromH"] == "3" ) echo  "selected"; ?>>3</option>
      <option value="4" <?php if( $info["FridayFromH"] == "4" ) echo  "selected"; ?>>4</option>
      <option value="5" <?php if( $info["FridayFromH"] == "5" ) echo  "selected"; ?>>5</option>
      <option value="6" <?php if( $info["FridayFromH"] == "6" ) echo  "selected"; ?>>6</option>
      <option value="7" <?php if( $info["FridayFromH"] == "7" ) echo  "selected"; ?>>7</option>
      <option value="8" <?php if( $info["FridayFromH"] == "8" ) echo  "selected"; ?>>8</option>
      <option value="9" <?php if( $info["FridayFromH"] == "9" || $default == true ) echo  "selected"; ?>>9</option>
      <option value="10" <?php if( $info["FridayFromH"] == "10" ) echo  "selected"; ?>>10</option>
      <option value="11" <?php if( $info["FridayFromH"] == "11" ) echo  "selected"; ?>>11</option>
      <option value="12" <?php if( $info["FridayFromH"] == "12" ) echo  "selected"; ?>>12</option>
    </select></div>
    <div class="col-lg-1"><select name="hours[FridayFromM]" class="form-control min from">
      <option value=":00" <?php if( $info["FridayFromM"] == ":00" ) echo  "selected"; ?>>:00</option>
      <option value=":15" <?php if( $info["FridayFromM"] == ":15" ) echo  "selected"; ?>>:15</option>
      <option value=":30" <?php if( $info["FridayFromM"] == ":30" ) echo  "selected"; ?>>:30</option>
      <option value=":45" <?php if( $info["FridayFromM"] == ":45" ) echo  "selected"; ?>>:45</option>
    </select></div>
    <div class="col-lg-1"><select name="hours[FridayFromAP]" class="form-control ampm from">
      <option value="AM" <?php if( $info["FridayFromAP"] == "AM" ) echo  "selected"; ?>>AM</option>
      <option value="PM" <?php if( $info["FridayFromAP"] == "PM" ) echo  "selected"; ?>>PM</option>
    </select></div>
    <div class="col-lg-1 text-center">to</div>
    <div class="col-lg-1"><select name="hours[FridayToH]" class="form-control hour to">
      <option value="1" <?php if( $info["FridayToH"] == "1" ) echo  "selected"; ?>>1</option>
      <option value="2" <?php if( $info["FridayToH"] == "2" ) echo  "selected"; ?>>2</option>
      <option value="3" <?php if( $info["FridayToH"] == "3" ) echo  "selected"; ?>>3</option>
      <option value="4" <?php if( $info["FridayToH"] == "4" ) echo  "selected"; ?>>4</option>
      <option value="5" <?php if( $info["FridayToH"] == "5" || $default == true ) echo  "selected"; ?>>5</option>
      <option value="6" <?php if( $info["FridayToH"] == "6" ) echo  "selected"; ?>>6</option>
      <option value="7" <?php if( $info["FridayToH"] == "7" ) echo  "selected"; ?>>7</option>
      <option value="8" <?php if( $info["FridayToH"] == "8" ) echo  "selected"; ?>>8</option>
      <option value="9" <?php if( $info["FridayToH"] == "9" ) echo  "selected"; ?>>9</option>
      <option value="10" <?php if( $info["FridayToH"] == "10" ) echo  "selected"; ?>>10</option>
      <option value="11" <?php if( $info["FridayToH"] == "11" ) echo  "selected"; ?>>11</option>
      <option value="12" <?php if( $info["FridayToH"] == "12" ) echo  "selected"; ?>>12</option>
    </select></div>
    <div class="col-lg-1"><select name="hours[FridayToM]" class="form-control min to">
      <option value=":00" <?php if( $info["FridayToM"] == ":00" ) echo  "selected"; ?>>:00</option>
      <option value=":15" <?php if( $info["FridayToM"] == ":15" ) echo  "selected"; ?>>:15</option>
      <option value=":30" <?php if( $info["FridayToM"] == ":30" ) echo  "selected"; ?>>:30</option>
      <option value=":45" <?php if( $info["FridayToM"] == ":45" ) echo  "selected"; ?>>:45</option>
    </select></div>
    <div class="col-lg-1"><select name="hours[FridayToAP]" class="form-control ampm to">
      <option value="AM" <?php if( $info["FridayToAP"] == "AM" ) echo  "selected"; ?>>AM</option>
      <option value="PM" <?php if( $info["FridayToAP"] == "PM" || $default == true ) echo  "selected"; ?>>PM</option>
    </select></div>
    <span class="col-lg-2">
    <input type="checkbox" name="FridayClosed" value="closed" class="closed" <?php if( $info["FridayFromH"] == 0 && !$allclosed ) echo  "checked"; ?>>
    Closed</span></div>
  <div id="Saturday" class="row day">
    <div id="label" class="col-lg-1">Saturday: </div>
    <div class="col-lg-1"><select name="hours[SaturdayFromH]" class="form-control hour from">
      <option value="1" <?php if( $info["SaturdayFromH"] == "1" ) echo  "selected"; ?>>1</option>
      <option value="2" <?php if( $info["SaturdayFromH"] == "2" ) echo  "selected"; ?>>2</option>
      <option value="3" <?php if( $info["SaturdayFromH"] == "3" ) echo  "selected"; ?>>3</option>
      <option value="4" <?php if( $info["SaturdayFromH"] == "4" ) echo  "selected"; ?>>4</option>
      <option value="5" <?php if( $info["SaturdayFromH"] == "5" ) echo  "selected"; ?>>5</option>
      <option value="6" <?php if( $info["SaturdayFromH"] == "6" ) echo  "selected"; ?>>6</option>
      <option value="7" <?php if( $info["SaturdayFromH"] == "7" ) echo  "selected"; ?>>7</option>
      <option value="8" <?php if( $info["SaturdayFromH"] == "8" ) echo  "selected"; ?>>8</option>
      <option value="9" <?php if( $info["SaturdayFromH"] == "9" || $default == true ) echo  "selected"; ?>>9</option>
      <option value="10" <?php if( $info["SaturdayFromH"] == "10" ) echo  "selected"; ?>>10</option>
      <option value="11" <?php if( $info["SaturdayFromH"] == "11" ) echo  "selected"; ?>>11</option>
      <option value="12" <?php if( $info["SaturdayFromH"] == "12" ) echo  "selected"; ?>>12</option>
    </select></div>
    <div class="col-lg-1"><select name="hours[SaturdayFromM]" class="form-control min from">
      <option value=":00" <?php if( $info["SaturdayFromM"] == ":00" ) echo  "selected"; ?>>:00</option>
      <option value=":15" <?php if( $info["SaturdayFromM"] == ":15" ) echo  "selected"; ?>>:15</option>
      <option value=":30" <?php if( $info["SaturdayFromM"] == ":30" ) echo  "selected"; ?>>:30</option>
      <option value=":45" <?php if( $info["SaturdayFromM"] == ":45" ) echo  "selected"; ?>>:45</option>
    </select></div>
    <div class="col-lg-1"><select name="hours[SaturdayFromAP]" class="form-control ampm from">
      <option value="AM" <?php if( $info["SaturdayFromAP"] == "AM" ) echo  "selected"; ?>>AM</option>
      <option value="PM" <?php if( $info["SaturdayFromAP"] == "PM" ) echo  "selected"; ?>>PM</option>
    </select></div>
    <div class="col-lg-1 text-center">to</div>
    <div class="col-lg-1"><select name="hours[SaturdayToH]" class="form-control hour to">
      <option value="1" <?php if( $info["SaturdayToH"] == "1" ) echo  "selected"; ?>>1</option>
      <option value="2" <?php if( $info["SaturdayToH"] == "2" ) echo  "selected"; ?>>2</option>
      <option value="3" <?php if( $info["SaturdayToH"] == "3" ) echo  "selected"; ?>>3</option>
      <option value="4" <?php if( $info["SaturdayToH"] == "4" ) echo  "selected"; ?>>4</option>
      <option value="5" <?php if( $info["SaturdayToH"] == "5" || $default == true ) echo  "selected"; ?>>5</option>
      <option value="6" <?php if( $info["SaturdayToH"] == "6" ) echo  "selected"; ?>>6</option>
      <option value="7" <?php if( $info["SaturdayToH"] == "7" ) echo  "selected"; ?>>7</option>
      <option value="8" <?php if( $info["SaturdayToH"] == "8" ) echo  "selected"; ?>>8</option>
      <option value="9" <?php if( $info["SaturdayToH"] == "9" ) echo  "selected"; ?>>9</option>
      <option value="10" <?php if( $info["SaturdayToH"] == "10" ) echo  "selected"; ?>>10</option>
      <option value="11" <?php if( $info["SaturdayToH"] == "11" ) echo  "selected"; ?>>11</option>
      <option value="12" <?php if( $info["SaturdayToH"] == "12" ) echo  "selected"; ?>>12</option>
    </select></div>
    <div class="col-lg-1"><select name="hours[SaturdayToM]" class="form-control min to">
      <option value=":00" <?php if( $info["SaturdayToM"] == ":00" ) echo  "selected"; ?>>:00</option>
      <option value=":15" <?php if( $info["SaturdayToM"] == ":15" ) echo  "selected"; ?>>:15</option>
      <option value=":30" <?php if( $info["SaturdayToM"] == ":30" ) echo  "selected"; ?>>:30</option>
      <option value=":45" <?php if( $info["SaturdayToM"] == ":45" ) echo  "selected"; ?>>:45</option>
    </select></div>
    <div class="col-lg-1"><select name="hours[SaturdayToAP]" class="form-control ampm to">
      <option value="AM" <?php if( $info["SaturdayToAP"] == "AM" ) echo  "selected"; ?>>AM</option>
      <option value="PM" <?php if( $info["SaturdayToAP"] == "PM" || $default == true ) echo  "selected"; ?>>PM</option>
    </select></div>
    <span class="col-lg-2">
    <input type="checkbox" name="SaturdayClosed" value="closed" class="closed" <?php if( $info["SaturdayFromH"] == 0 && !$allclosed ) echo  "checked"; ?>>
    Closed</span></div>
  <div class="alert alert-info row">
    <div class="col-lg-1"><input type="checkbox" name="closed" value="closed" class="allclosed" <?php if( $allclosed ) echo  "checked"; ?>></div>
    <span class="col-lg-3">No Hours of Operation</span> </div>
</div>
<?php 
$footScripts = '<script src="assets/js/jquery.validate.js"></script>
<script type="text/javascript">
<!-- VALIDATE FORM -->
$(document).ready(function() {
   	$("form#postrent").validate();
});
<!-- ADD MORE UNITS -->
$("#unit-add").click(function() {
	var counter = $("#unit-rows #rowcount").size() + 1;
	var before = counter - 1;
	var next = counter + 1;
	var order = $("#unit-rows #unit-row").size() + 1;
	$(\'<input type="hidden" name="u_order\' + counter + \'" value="\' + order + \'" /><input type="hidden" id="rowcount" name="rowcount" value="\' + counter + \'" ><h3>Unit \' + order + \'</h3>\').appendTo("#unit-rows");
	$("#unit-rows")
	.append($("#unit-row")
	.clone()
	.prepend( $(\'<a href="#" class="pull-right btn btn-default btn-small" id="unit-remove"><i class="icon-trash"></i> Delete</a></div>\') )
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
$("input.closed").change(closed).change();
$("input.allclosed").change(allclosed).change();
function closed() {
   if($(this).filter(":checked").val() == "closed") {
		$(this).closest(".day").find("select").attr("disabled", true);
    } else if($("input.allclosed").filter(":checked").val() != "closed") {
		$(this).closest(".day").find("select").attr("disabled", false);
    }
}
function allclosed() {
   if($(this).filter(":checked").val() == "closed") {
		$("#hourForm").find("select").attr("disabled", true);
    } else {
		$("input.closed:not(:checked)").closest(".day").find("select").attr("disabled", false);
    }
}
<!-- Address Autocomplete -->
function forminit() {
	var input = document.getElementById("address");
 	var options = {
 		componentRestrictions: {country: "ca"}
	};
	var autocomplete = new google.maps.places.Autocomplete(input, options);
}
google.maps.event.addDomListener(window, "load", forminit);
</script>
	   ';
?>