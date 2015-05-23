<p>&nbsp;</p>
<a href="#feedback" role="button" class="btn btn-block btn-lg btn-default support" data-toggle="modal">Support</a> 

<!-- Feedback -->
<div class="modal fade" id="feedback">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Support Form</h4>
      </div>
      <form id="feedbackForm" method="post">
        <input id="prop" name="prop" type="hidden" />
        <div class="modal-body">
          <div class="form_result"></div>
          <div class="row form-group">
            <div class="col-lg-3">Your Name</div>
            <div class="col-lg-9">
              <input class="form-control" id="cname" type="text" name="dname" minlength="2" />
            </div>
          </div>
          <div class="row form-group">
            <div class="col-lg-3">Your Email</div>
            <div class="col-lg-9">
              <input class="form-control email" id="cemail" type="text" name="demail" />
            </div>
          </div>
          <div class="row">
          <div class="col-lg-12">
          Your Feedback <span class="red">*</span>
            <textarea class="form-control required" id="ccomment" name="dcomment" rows="5" minlength="10"></textarea>
            <small class="pull-right"><span class="red">*</span> Required field</small>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
          <button id="feedbackSubmit" class="btn btn-primary" type="submit" name="feedbackSubmit">Send</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- #Feedback -->