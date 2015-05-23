<form id="quickForm" method="post">
<input type="hidden" name="lat" value="<?php echo $lat; ?>" />
<input type="hidden" name="lng" value="<?php echo $lng; ?>" />
<input type="hidden" name="rad" value="<?php echo $radius; ?>" />
<h3 style="margin-top:0;">Refine Search</h3>
<div class="panel-group" id="accordion">
        <div class="panel panel-default" id="beds-panel">
          <div class="panel-heading">
            <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#beds" class="collapsed">
                Beds
              </a>
              <small class="pull-right">No Preference</small>
            </h4>
          </div>
          <div id="beds" class=" collapse">
            <div class="panel-body">
             <label class="radio">
             <input type="radio" name="beds" data-toggle="radio" value="" <?php if( !isset($pBed) ) echo  "checked"; ?>>
          No Preference </label>
        <label class="radio">
          <input type="radio" name="beds" data-toggle="radio" value="Studio bed" <?php if( isset($pBed) && $pBed == "studio bed" ) echo  "checked"; ?>>
          Studio </label>
        <label class="radio">
          <input type="radio" name="beds" data-toggle="radio" value="1 bed" <?php if( isset($pBed) && $pBed == "1 bed" ) echo  "checked"; ?>>
          1 Bedroom </label>
        <label class="radio">
          <input type="radio" name="beds" data-toggle="radio" value="1 bed plus den" <?php if( isset($pBed) && $pBed == "1 bed plus den" ) echo  "checked"; ?>>
          1 Bedroom + Den </label>
        <label class="radio">
          <input type="radio" name="beds" data-toggle="radio" value="2 bed" <?php if( isset($pBed) && $pBed == "2 bed" ) echo  "checked"; ?>>
          2 Bedrooms </label>
        <label class="radio">
          <input type="radio" name="beds" data-toggle="radio" value="2 bed plus den" <?php if( isset($pBed) && $pBed == "2 bed plus den" ) echo  "checked"; ?>>
          2 Bedroom + Den </label>
        <label class="radio">
          <input type="radio" name="beds" data-toggle="radio" value="3 bed" <?php if( isset($pBed) && $pBed == "3 bed" ) echo  "checked"; ?>>
          3 Bedrooms </label>
        <label class="radio">
          <input type="radio" name="beds" data-toggle="radio" value="4 bed" <?php if( isset($pBed) && $pBed == "4 bed" ) echo  "checked"; ?>>
          4 Bedrooms </label>
            </div>
          </div>
        </div>
        <div class="panel panel-default" id="baths-panel">
          <div class="panel-heading">
            <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#baths" class="collapsed">
                Bathrooms
              </a>
              <small class="pull-right">No Preference</small>
            </h4>
          </div>
          <div id="baths" class="panel-collapse collapse">
            <div class="panel-body">
              <label class="radio">
          <input type="radio" name="bath" data-toggle="radio" value="" <?php if( !isset($pBa) ) echo  "checked"; ?>>
          No Preference </label>
        <label class="radio">
          <input type="radio" name="bath" data-toggle="radio" value="1 ba" <?php if( isset($pBa) && $pBa == "1 ba" ) echo  "checked"; ?>>
          1 Bathroom </label>
        <label class="radio">
          <input type="radio" name="bath" data-toggle="radio" value="2 ba" <?php if( isset($pBa) && $pBa == "2 ba" ) echo  "checked"; ?>>
          2 Bathrooms </label>
        <label class="radio">
          <input type="radio" name="bath" data-toggle="radio" value="3 ba" <?php if( isset($pBa) && $pBa == "3 ba" ) echo  "checked"; ?>>
          3 Bathrooms </label>
            </div>
          </div>
        </div>
        <div class="panel panel-default" id="price-panel">
          <div class="panel-heading">
            <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#price" class="collapsed">
                Price
              </a>
              <small class="pull-right">No Preference</small>
            </h4>
          </div>
          <div id="price" class="panel-collapse collapse">
            <div class="panel-body">            
           		<input type="hidden" id="rent" name="price" />
            	<div id="slider-range" style="margin-bottom:6px;"></div>
            	<input type="text" id="amount" style="border:0;" />
            </div>
          </div>
        </div>
        <div class="panel panel-default" id="distance-panel">
          <div class="panel-heading">
            <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#distance" class="collapsed">
                Distance
              </a>
              <small class="pull-right">No Preference</small>
            </h4>
          </div>
          <div id="distance" class="panel-collapse collapse">
            <div class="panel-body">            
              <label class="radio">
          <input type="radio" name="dist" data-toggle="radio" value="" <?php if( !isset($pDist) ) echo  "checked"; ?>>
          No Preference </label>
        <label class="radio">
          <input type="radio" name="dist" data-toggle="radio" value="5km" <?php if( isset($pDist) && $pDist == "5km" ) echo  "checked"; ?>>
          5 km </label>
        <label class="radio">
          <input type="radio" name="dist" data-toggle="radio" value="10km" <?php if( isset($pDist) && $pDist == "10km" ) echo  "checked"; ?>>
          10 km </label>
        <label class="radio">
          <input type="radio" name="dist" data-toggle="radio" value="20km" <?php if( isset($pDist) && $pDist == "20km" ) echo  "checked"; ?>>
          20 km </label>
        <label class="radio">
          <input type="radio" name="dist" data-toggle="radio" value="30km" <?php if( isset($pDist) && $pDist == "30km" ) echo  "checked"; ?>>
          30 km </label>
        <label class="radio">
          <input type="radio" name="dist" data-toggle="radio" value="40km" <?php if( isset($pDist) && $pDist == "40km" ) echo  "checked"; ?>>
          40 km </label>
        <label class="radio">
          <input type="radio" name="dist" data-toggle="radio" value="50km" <?php if( isset($pDist) && $pDist == "50km" ) echo  "checked"; ?>>
          50 km </label>
        <label class="radio">
          <input type="radio" name="dist" data-toggle="radio" value="75km" <?php if( isset($pDist) && $pDist == "75km" ) echo  "checked"; ?>>
          75 km </label>
        <label class="radio">
          <input type="radio" name="dist" data-toggle="radio" value="100km" <?php if( isset($pDist) && $pDist == "100km" ) echo  "checked"; ?>>
          100 km </label>
            </div>
          </div>
        </div>
        <div class="panel panel-default" id="laundry-panel">
          <div class="panel-heading">
            <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#laundry" class="collapsed">
                Laundry
              </a>
              <small class="pull-right">No Preference</small>
            </h4>
          </div>
          <div id="laundry" class="panel-collapse collapse">
            <div class="panel-body">            
              <label class="radio">
          <input type="radio" name="laund" data-toggle="radio" value="" <?php if( !(isset($search_feat['washfacil']) || isset($search_feat['washunit']) || isset($search_feat['washconn'])) ) echo  "checked"; ?>>
          No Preference </label>
        <label class="radio">
          <input type="radio" name="laund" data-toggle="radio" value="Laundry Facility" <?php if( isset($search_feat['washfacil']) && urldecode($search_feat['washfacil']) == "laundry facility" ) echo  "checked"; ?>>
          Laundry Facility </label>
        <label class="radio">
          <input type="radio" name="laund" data-toggle="radio" value="Washer and Dryer in Unit" <?php if( isset($search_feat['washunit']) && urldecode($search_feat['washunit']) == "washer and dryer in unit" ) echo  "checked"; ?>>
          Washer and Dryer in Unit </label>
        <label class="radio">
          <input type="radio" name="laund" data-toggle="radio" value="Washer and Dryer Connections" <?php if( isset($search_feat['washconn']) && urldecode($search_feat['washconn']) == "washer and dryer connections" ) echo  "checked"; ?>>
           Washer and Dryer Connections </label>
            </div>
          </div>
        </div>
        <div class="panel panel-default" id="pets-panel">
          <div class="panel-heading">
            <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#pets" class="collapsed">
                Pets
              </a>
              <small class="pull-right">No Preference</small>
            </h4>
          </div>
          <div id="pets" class="panel-collapse collapse">
            <div class="panel-body">            
              <label class="radio">
          <input type="radio" name="pets" data-toggle="radio" value="" <?php if( !isset($search_feat['petsallow']) ) echo  "checked"; ?>>
          No Preference </label>
        <label class="radio">
          <input type="radio" name="pets" data-toggle="radio" value="Pets Allowed" <?php if( isset($search_feat['petsallow']) && urldecode($search_feat['petsallow']) == "pets allowed" ) echo  "checked"; ?>>
          Pets Allowed </label>
            </div>
          </div>
        </div>
      </div>

<div class="row" style="margin-top:12px;">
  <div class="col-md-6"><a class="btn btn-block btn-lg btn-inverse" href="<?php echo $opt.$listparam; ?>">More <span class="fui-arrow-right"></span></a></div>
  <div class="col-md-6"><input class="btn btn-block btn-lg btn-inverse" id="submit" type="submit" alt="Submit" name="submit" value="Update" /></div>
</div>
</form>