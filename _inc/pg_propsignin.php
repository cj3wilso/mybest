  <?php
if ( !isset($_SESSION["ID_my_site"]) ){
?>
  <div class="panel panel-default" id="beds-panel">
          <div class="panel-heading">
            <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#signin" class="collapsed">
                Create Account 
              </a> 
              <small class="pull-right selected">Required</small>
            </h4>
          </div>
          <div id="signin" class="collapse in">
            <div class="panel-body">
            <div class="row form-group">
            <div class="col-lg-12">
            Already have an account? <a href="#myModal" role="button" class="btn btn-lg btn-primary" data-toggle="modal">SIGN IN</a>
            </div>
            </div>
              <div class="row form-group">
    <div class="col-lg-2">Email <span class="red">*</span></div>
    <div class="col-lg-3"><input class="form-control required" type="email" name="u_email" placeholder="Email" data-placement="bottom"  /></div>
  </div>
  <div class="row form-group">
    <div class="col-lg-2">Password <span class="red">*</span></div>
    <div class="col-lg-3"><input class="form-control required" type="password" name="u_pass" placeholder="Password" minlength="6" data-placement="bottom" /></div>
    <div class="col-lg-2">Confirm Password <span class="red">*</span></div>
    <div class="col-lg-3"><input class="form-control required" type="password" name="u_pass_c" placeholder="Confirm Password" minlength="6" data-rule-equalto="true" data-placement="bottom" /></div>
  </div>
  <label class="checkbox">
	<input type="checkbox" name="updates" data-toggle="checkbox" value="1" checked> Receive updates about website (Infrequent but let's you know about latest features)
  </label>
             
            </div>
          </div>
        </div>
  
  

  <?php
}
?>