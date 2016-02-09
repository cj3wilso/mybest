<?php
$page = "apartment";
$headStyles='
<link rel="stylesheet" type="text/css" href="/assets/js/swipebox/swipebox.css" media="screen" />
<style>
body{
 	height: auto; 
}
#map_canvas { height: 400px; width:100%; }
div.gm-style-iw {
    height:75px;
	width:250px;
    overflow:hidden;
}
div.gm-style-iw #cat{
	text-transform:capitalize;
}
.gm-style-cc{
	display:none;
}
</style>';

include("global.php");
include("class.walkscore.php");

//Show Tracking Record
$page_id = $level[5];
$info = mysql_query_cache("SELECT *, uf.id_prop AS star, p.id_user AS owner,
GROUP_CONCAT(CONCAT('<tr><td>', style, '</td><td>', beds, '</td><td>', ba, '</td><td>', sq_ft, '</td><td>$',  rent, '</td><td>', dep, '</td></tr>') 
	ORDER BY u_order ASC SEPARATOR '') AS unit
FROM properties p 
LEFT JOIN prop_units ON prop_units.id_prop = '$page_id'
LEFT JOIN prop_intro ON prop_intro.id_prop = '$page_id' 
LEFT JOIN prop_hours ON prop_hours.id_prop = '$page_id' 
LEFT JOIN user_fav uf ON uf.id_prop = '$page_id' AND $fav_find 
WHERE id_pg = '$page_id'");
$info = $info[0];
//If no name or unit then redirect
//If deleted then go to list page with message
if($info['name'] == NULL || $info['unit'] == NULL || $info['deleted']==1 || $info['pub']==='0'){
	header("HTTP/1.0 404 Not Found");
	if($info['deleted']==1 || $info['pub']==='0'){
		$_SESSION["listremoved"]='<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Oh snap!</strong> "'.$info['name']." in ".$info['city'].", ".$info['prov'].'"</i> is no longer available.<br>Find equally awesome apartments near '.$info['city'].", ".$info['prov'].' below.</div>';
	}
	header("Location: $list/".$_GET["prov"]."/".urlencode($_GET["city"])."/");
}
//Redirect users to correct URL
$correctURL = "/rent/".urlencode($info['prov'])."/".urlencode($info['city'])."/".cleanUrl($info['name'])."/".$info['id_pg'];
if($_SERVER["REQUEST_URI"] != $correctURL){
	header ('HTTP/1.1 301 Moved Permanently');
	header("Location: $correctURL");
}
$check = mysql_query_cache("SELECT type, GROUP_CONCAT(CONCAT('<tr><td>', feat, '</td></tr>') SEPARATOR '') AS featlist
FROM prop_feat
WHERE id_prop = '$page_id' AND deleted = 0
GROUP BY type");
$featrows = count($check);
$info3 = mysql_query_cache("SELECT photo
FROM prop_photos
WHERE id_prop = '$page_id'
ORDER BY p_order ASC");
$pageTitle = $info['name'].' in '.$info['city'].', '.$info['prov'];
$metaDesc = "See Apartment photos, location, description and features for ".$info['name'].' in '.$info['city'].', '.$info['prov'];
if($info['email']){
	$contactEmail = $info['email'];
}else{
	$id = $info["id_user"];
	$user = mysql_query_cache("SELECT email 
	FROM users 
	WHERE id = '$id'");
	$user = $user[0];
	$contactEmail = $user["email"];
}

//include("pageviews.php");
include("header.php");


if( $info["SundayFromH"] == 0 && $info["MondayFromH"] == 0 && $info["TuesdayFromH"] == 0 && $info["WednesdayFromH"] == 0 && $info["ThursdayFromH"] == 0 && $info["FridayFromH"] == 0 && $info["SaturdayFromH"] == 0 ) $allclosed = true;
$star_class = 'icon-star-empty';
if ($info['star']) $star_class = 'icon-star';

//Get photos as array
$photorows = count($info3);
$slideshow1 = $slideshow2 = '';
$photos = $info3;
foreach ($photos as $k => $v) {
	$photoURL = 'http://'.$_SERVER['HTTP_HOST'].'/upload/server/php/files/'.$page_id.'/slide/'.rawurlencode($photos[$k]['photo']);
	if (getimagesize($photoURL) === false) {
		$photorows=0;
		break;
	}
	$slideshow1 .= '<a href="/upload/server/php/files/'.$page_id.'/slide/'.rawurlencode($photos[$k]['photo']).'" class="swipebox" title="'.$info['name'].'"><img src="/upload/server/php/files/'.$page_id.'/slide/'.rawurlencode($photos[$k]['photo']).'" itemprop="image" alt="'.$photos[$k]['photo'].'" width="390" height="390" class="img-rounded img-responsive"></a>';
	$slideshow2 .= '<img src="/upload/server/php/files/'.$page_id.'/slide/'.rawurlencode($photos[$k]['photo']).'" itemprop="image" alt="'.$photos[$k]['photo'].'" width="80" height="80" class="img-rounded img-responsive">';
}
?>
<style>
#cycle-2 .cycle-slide-active {
	border: 3px solid #1abc9c;
}
#cycle-2 .cycle-slide {
	margin: 0 12px;
}
#slideshow-2 {
	position: relative;
}
#slideshow-2 .col-xs-1 {
	position: static !important;
}
#slideshow-2 .col-xs-1 i {
	position: absolute;
	top: 30%;
}
#slideshow-2 .col-xs-1 i.fui-arrow-left {
	left: 1em;
}
#slideshow-2 .col-xs-1 i.fui-arrow-right {
	right: 1em;
}
</style>
<?php if( isset($_SESSION['ID_my_site']) && $info['owner'] == $_SESSION['ID_my_site']){ ?>
<small><a href="/edit/<?php echo $_GET["id"]; ?>" class="pull-right">Edit</a></small>
<?php } ?>
<div itemscope itemtype="https://schema.org/LocalBusiness">
<ul class="nav nav-pills">
  <li class="active"> <a href="<?php echo $list.'/'.$info['prov'].'/'.$info['city']; ?>"><span class="fui-arrow-left"></span> See more apartments near <?php echo $info['city'].', '.$info['prov']; ?></a> </li>
</ul>

<!-- Google geo loc -->
<div itemprop="geo" itemscope itemtype="http://schema.org/GeoCoordinates">
	<meta itemprop="latitude" content="<?php echo $info['lat']; ?>">
	<meta itemprop="longitude" content="<?php echo $info['lng']; ?>">
</div>

<!-- Begin Photo/Desc Area -->
<div class="row" style="position:relative;">
  <div class="col-md-7">
    <div style="font-size:15px;color:#667683;text-transform:uppercase;font-weight:500;text-align:left;line-height:26px;padding:48px 0 95px 0px; min-height:359px;">
      <div style="margin-bottom:31px;">
        <div itemprop="name" style="font-size:30px;color:#2c3e50;font-weight:600;line-height:35px;"><?php echo $info['name']; ?></div>
        <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress" style="font-weight:100;"><span itemprop="streetAddress"><?php echo $info['address']; if($info['address2']){echo ', '.$info['address2'];} ?></span><?php if($info['address']||$info['address2']){echo ',';} ?> <span itemprop="addressLocality"><?php echo $info['city']; ?></span>, <span itemprop="addressRegion"><?php echo $info['prov']; ?></span>, <?php echo $info['cntry']; ?><?php if($info['post']){echo ',';} ?> <span itemprop="postalCode"><?php echo $info['post']; ?></span></div>
      </div>
      <div style="margin-bottom:31px;"> 
        <!--<div style="font-size:21px;font-weight:500;color:#667683;">Beautiful one bedroom unit in Applewood – rent all incl.</div>-->
        <div style="font-weight:100;">Page ID: <?php echo $info['id_pg']; ?>, Posted: <?php echo $info['date']; ?></div>
        <div style="font-weight:100;"><i class="<?php echo $star_class; ?>" data-prop="<?php echo $info['id_pg']; ?>"></i> Add to My Places</div>
        <div style="font-weight:100;"><?php echo walkscore::get($info['address']." ".$info['address2']." ".$info['city']." ".$info['prov']." ".$info['cntry']." ".$info['post'],$info['lat'],$info['lng']); ?></div>
        <?php 
       //Show pageviews
		foreach($results as $result) {
			$pageview = number_format($result->getPageviews());
			$pageview_increase = $pageview * 2 + 3;
		?>
        <div style="font-weight:100;">Pageviews: <?php echo $pageview_increase; ?></div>
        <?php 
        }
		?>
      </div>
      <div class="hm-cta pull-right" style="position:absolute;bottom:48px;right:0px;">
        <?php if($info['url']){ ?>
        <a href="<?php echo $info['url']; ?>" target="_blank" class="btn btn-inverse" style="background-color:#09977b;">
        <div class="fui-eye"></div>
        View Website</a>
        <?php } ?>
        <?php if($info['phone1'] != 0){ ?>
        <a href="tel://1-<?php echo $info['phone1'];?>-<?php echo $info['phone2'];?>-<?php echo $info['phone3']; ?>" class="btn btn-inverse" style="background-color:#09977b;">
          <div class="fui-chat"></div>
          (<?php echo $info['phone1'];?>) <?php echo $info['phone2'];?>-<?php echo $info['phone3']; ?></a>
        <?php } ?>
        <a href="#myModal" role="button" class="btn btn-primary avail" data-toggle="modal" style="background-color:#1abc9c;">
        <div class="fui-mail"></div>
        Check Availability</a> </div>
    </div>
  </div>
  <div class="col-md-5">
    <?php if($photorows > 0){ ?>
    <div id="slideshow-1" style="position:relative;display:inline-block;float:right;">
      <div id="cycle-1" class="cycle-slideshow" style="width: auto; float: right; position: relative;" 
        data-cycle-slides="> a"
        data-cycle-timeout="0"
        > <?php echo $slideshow1; ?> </div>
      <img src="/assets/img/enlarge.png" style="position:absolute;bottom:8px;right:8px;z-index:100;pointer-events: none;"> </div>
    <?php }else{ ?>
    <img src="http://placehold.it/390x390&text=Photos%20Coming%20Soon" itemprop="image" alt="Photos Coming Soon" width="390" height="390" class="img-rounded img-responsive pull-right">
    <?php } ?>
  </div>
</div>
<?php if($photorows > 0){ ?>
<div class="tile cycle-pager">
  <div class="row" id="slideshow-2">
    <div class="col-xs-1"><i class="fui-arrow-left"></i></div>
    <div class="col-xs-10">
      <div id="cycle-2" class="cycle-slideshow"
        data-cycle-timeout="0"
        data-cycle-prev="#slideshow-2 .cycle-prev"
        data-cycle-next="#slideshow-2 .cycle-next"
        data-cycle-caption="#slideshow-2 .custom-caption"
        data-cycle-caption-template="Slide {{slideNum}} of {{slideCount}}"
        data-cycle-fx="carousel"
        data-cycle-carousel-fluid="true"
        data-allow-wrap="false"
        > <?php echo $slideshow2; ?> </div>
    </div>
    <div class="col-xs-1"> <i class="fui-arrow-right"></i></div>
  </div>
  <!--<span class="custom-caption"></span>--> 
</div>
<?php } ?>
<!-- End Photo/Desc Area -->

<div class="row" id="featlist">
  <div class="col-lg-2">
    <section class="navspy" data-spy="affix" data-offset-top="650">
      <div class="well sidebar-nav">
        <ul class="nav nav-list" data-target=".navbar">
          <li><a href="#units"><i class="icon-home pull-right"></i> Units</a></li>
          <li><a href="#directions"><i class="icon-map-marker pull-right"></i> Nearby</a> </li>
          <li><a href="#info"><i class="icon-info-sign pull-right"></i> About</a></li>
          <?php if($featrows > 0){ ?>
          <li><a href="#feat"><i class="icon-list pull-right"></i> Features</a></li>
          <?php } ?>
          <?php if (!isset($allclosed)){ ?>
          <li><a href="#operate"><i class="icon-time pull-right"></i> Hours</a></li>
          <?php } ?>
        </ul>
      </div>
    </section>
  </div>
  <div class="col-lg-10">
    <section id="units">
      <h3>Units</h3>
      <hr  />
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th>Style</th>
            <th>Beds</th>
            <th>Baths</th>
            <th>Sq. Ft.</th>
            <th>Rent</th>
            <th>Deposit</th>
          </tr>
        </thead>
        <tbody>
          <?php echo $info['unit']; ?>
        </tbody>
      </table>
    </section>
    <section id="directions">
      <h3>Nearby</h3>
      <hr  />
      <div class="row">
        <div class="col-lg-8">
          <div id="map_canvas"></div>
        </div>
        <div class="col-lg-4">
          <p class="clearfix"><a class="btn btn-block btn-primary btn-lg pull-right directions" href="https://maps.google.com/maps?saddr=&daddr=<?php echo $info['address'].', '.$info['city'].', '.$info['prov'].', '.$info['cntry'].', '.$info['post']; ?>" target="_blank">Get directions</a></p>
          <p> <br />
            <strong><?php echo $info['name']; ?></strong>
            <?php if($info['phone1'] != 0){ ?>
            <?php echo '<br />('.$info['phone1'].') '.$info['phone2'].'-'.$info['phone3']; ?>
            <?php } ?>
          <address>
          <?php echo $info['address']; if($info['address2']){echo ', '.$info['address2'];} if($info['address'] || $info['address2']){echo '<br>';} echo $info['city'].', '.$info['prov'].'<br>'.$info['cntry']; if($info['post']){echo ', '.$info['post'];} ?>
          </address>
          </p>
        </div>
      </div>
    </section>
    <section id="info">
      <h3>About</h3>
      <hr  />
      <div itemprop="description">
      <?php 
	  //$text =  nl2br2(nl2br($info['text']));
	  echo str_replace(array('\r', '\n'), '', $info['text']); ?>
      </div>
    </section>
    <?php if($featrows > 0){ ?>
    <section id="feat">
      <h3>Features</h3>
      <hr  />
      <?php
$row = 1;
$feat = $check;
foreach ($feat as $k => $v) {
if ($row == 1)
{
?>
      <div class="row">
        <?php
 }
?>
        <div class="col-lg-3"> <strong><?php echo $feat[$k]['type']; ?></strong>
          <table class="table table-striped">
            <?php echo $feat[$k]['featlist']; ?>
          </table>
        </div>
        <?php
if (($row % 4) == 0)
{
?>
      </div>
      <div class="row">
        <?php
 }
 $row ++;
}
?>
      </div>
    </section>
    <?php } ?>
    <?php 
	  if (!isset($allclosed)){
	 ?>
    <section id="operate">
      <h3>Operating Hours</h3>
      <hr  />
      <table id="optable" class="table table-striped">
        <tr>
          <th>Sunday</th>
          <?php if( $info["SundayFromH"] == 0 ) { ?>
          <td colspan="3"><span class="badge badge-important">Closed</span></td>
          <?php }else{ ?>
          <td><?php echo $info['SundayFromH'].$info['SundayFromM'].' '.$info['SundayFromAP']; ?></td>
          <td>to</td>
          <td><?php echo $info['SundayToH'].$info['SundayToM'].' '.$info['SundayToAP']; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <th>Monday</th>
          <?php if( $info["MondayFromH"] == 0 ) { ?>
          <td colspan="3"><span class="badge badge-important">Closed</span></td>
          <?php }else{ ?>
          <td><?php echo $info['MondayFromH'].$info['MondayFromM'].' '.$info['MondayFromAP']; ?></td>
          <td>to</td>
          <td><?php echo $info['MondayToH'].$info['MondayToM'].' '.$info['MondayToAP']; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <th>Tuesday</th>
          <?php if( $info["TuesdayFromH"] == 0 ) { ?>
          <td colspan="3"><span class="badge badge-important">Closed</span></td>
          <?php }else{ ?>
          <td><?php echo $info['TuesdayFromH'].$info['TuesdayFromM'].' '.$info['TuesdayFromAP']; ?></td>
          <td>to</td>
          <td><?php echo $info['TuesdayToH'].$info['TuesdayToM'].' '.$info['TuesdayToAP']; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <th>Wednesday</th>
          <?php if( $info["WednesdayFromH"] == 0 ) { ?>
          <td colspan="3"><span class="badge badge-important">Closed</span></td>
          <?php }else{ ?>
          <td><?php echo $info['WednesdayFromH'].$info['WednesdayFromM'].' '.$info['WednesdayFromAP']; ?></td>
          <td>to</td>
          <td><?php echo $info['WednesdayToH'].$info['WednesdayToM'].' '.$info['WednesdayToAP']; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <th>Thursday</th>
          <?php if( $info["ThursdayFromH"] == 0 ) { ?>
          <td colspan="3"><span class="badge badge-important">Closed</span></td>
          <?php }else{ ?>
          <td><?php echo $info['ThursdayFromH'].$info['ThursdayFromM'].' '.$info['ThursdayFromAP']; ?></td>
          <td>to</td>
          <td><?php echo $info['ThursdayToH'].$info['ThursdayToM'].' '.$info['ThursdayToAP']; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <th>Friday</th>
          <?php if( $info["FridayFromH"] == 0 ) { ?>
          <td colspan="3"><span class="badge badge-important">Closed</span></td>
          <?php }else{ ?>
          <td><?php echo $info['FridayFromH'].$info['FridayFromM'].' '.$info['FridayFromAP']; ?></td>
          <td>to</td>
          <td><?php echo $info['FridayToH'].$info['FridayToM'].' '.$info['FridayToAP']; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <th>Saturday</th>
          <?php if( $info["SaturdayFromH"] == 0 ) { ?>
          <td colspan="3"><span class="badge badge-important">Closed</span></td>
          <?php }else{ ?>
          <td><?php echo $info['SaturdayFromH'].$info['SaturdayFromM'].' '.$info['SaturdayFromAP']; ?></td>
          <td>to</td>
          <td><?php echo $info['SaturdayToH'].$info['SaturdayToM'].' '.$info['SaturdayToAP']; ?></td>
          <?php } ?>
        </tr>
      </table>
    </section>
    <?php 
	  }
	 ?>
  </div>
  <!-- .span10 --> 
</div>
<!-- #featlist --> 
</div> 
<!-- LocalBusiness Google Data Structure --> 
<!-- Modal -->
<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Contact Form</h4>
      </div>
      <form id="contactSeller" method="post">
        <input id="prop" name="prop" type="hidden" value="<?php echo $info['id_pg'];?>" />
        <input name="page" type="hidden" value="apartment" />
        <div class="modal-body">
          <div class="form_result"></div>
          <div class="row form-group">
            <div class="col-lg-3">Your Name</div>
            <div class="col-lg-9">
              <input class="form-control" id="cname" type="text" name="dname" minlength="2" />
            </div>
          </div>
          <div class="row form-group">
            <div class="col-lg-3">Your Email <span class="red">*</span></div>
            <div class="col-lg-9">
              <input class="form-control email required" id="cemail" type="text" name="demail" />
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12"> Your Message <span class="red">*</span>
              <textarea class="form-control required" id="ccomment" name="dcomment" rows="5" minlength="10"></textarea>
              <small class="pull-right"><span class="red">*</span> Required field</small> </div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
          <button id="seller" class="btn btn-primary" type="submit" name="seller">Send</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- #myModal --> 
<a href="#" class="go-top"></a> 
<script type="text/javascript">
var address = '<?php echo $info['address']; ?>';
var params = '?radius=1';
</script>
<?php
include 'footer.php'; 
include 'footer_js.php'; 
?>
<script src="http://malsup.github.io/jquery.cycle2.js"></script> 
<script src="http://malsup.github.io/jquery.cycle2.carousel.js"></script> 
<script src="http://malsup.github.io/jquery.cycle2.tile.js"></script> 
<!--<script src="/assets/js/cycle2/jquery.cycle2.swipe.min.js"></script> --> 
<!--<script src="/assets/js/cycle2/ios6fix.js"></script> --> 
<script src="/assets/js/swipebox/jquery.swipebox.min.js"></script> 
<script src="/assets/js/page/maps-stores.js"></script> 
<script type="text/javascript">
initialize('<?php echo $info['lat']; ?>','<?php echo $info['lng']; ?>',"'<?php echo $info['name']; ?>'","'<?php echo $info['address']; ?>', '<?php echo $info['city']; ?>'");
</script> 
<script type="text/javascript">
//Start Advanced Cycle2 Slideshow
jQuery(document).ready(function($){
	//Pager Scroll
	$('#slideshow-2 .fui-arrow-right, #slideshow-2 .fui-arrow-left').click(function(){
		var numphotos = $( ".cycle-carousel-wrap .cycle-slide" ).length;
		var lastindex, firstindex;
		var i = 1;
		d = $('#cycle-2');
		dtop = d.position().left;
		$('#cycle-2 .cycle-slide').each(function () {
			p = $(this);
			var carouselWrap = $( ".cycle-carousel-wrap" ).css("left");
			carouselWrap = carouselWrap.replace("px", "");
			ptop = Number(p.position().left) + Number(carouselWrap);
			if (ptop > dtop && ptop + p.width()  < dtop + d.width()) {
				//alert('This one is fully visible: ' + $(this).index());
				lastindex = $(this).index();
				if(i==1){
					firstindex = $(this).index();
				}
				i++;
			}
		});
		if ( $(this).attr('class') == "fui-arrow-right" ) {
			nextindex=lastindex+1;
			//If index is not greater than number of photos
			if(numphotos > nextindex){
				slideshows.cycle('goto', lastindex);
			}
		}else{
			previndex=firstindex-1-i;
			nthprev = previndex+1;
			//alert('i'+i);
			//alert('previndex'+previndex);
			if(nthprev > 0){
				slideshows.cycle('goto', previndex);
			}else{
				slideshows.cycle('goto', 0);
			}
		}
	});
	var slideshows = $('.cycle-slideshow').on('cycle-next cycle-prev', function(e, opts) {
		// advance the other slideshow
		slideshows.not(this).cycle('goto', opts.currSlide);
	});
	$('#cycle-2 .cycle-slide').click(function(){
		var index = $('#cycle-2').data('cycle.API').getSlideIndex(this);
		slideshows.cycle('goto', index);
	});
});
//End Advanced Cycle2 Slideshow		
	
$(".avail").click(function() {
	$("#myModal").find(".alert").hide();
});
	$(".swipebox").swipebox({
		hideBarsDelay : 10000, // delay before hiding bars
		hideBarsOnMobile : true // false will show the caption and navbar on mobile devices
	});
	$(".apt-sidenav").affix();
	onResize = function() {
		var sideBarNavWidth=$(".col-lg-2").width() - parseInt($(".sidebar-nav").css("paddingLeft")) - parseInt($(".sidebar-nav").css("paddingRight"));
		$(".navspy").css("width", sideBarNavWidth);
	}
	onResize();
	$(window).bind("resize", onResize);
$("#seller").click(function() {
    var url = "/_inc/form_contact_seller.php";
    $.ajax({
           type: "POST",
           url: url,
           data: $("#contactSeller").serialize(),
           success: function(data){
			   $("#contactSeller").find(".form_result").html(data);
			   _gaq.push(["_trackEvent", "Appointment", "Email", "Apartment Page"]);
           }
         });
	$("#contactSeller")[0].reset();
    return false;
});
$(".walkscore a").click(function() {
	_gaq.push(["_trackEvent", "Walk Score", "Click", "Apartment Page"]);
});
$(".directions").click(function() {
	_gaq.push(["_trackEvent", "Directions", "Click", "Apartment Page"]);
});
// Show or hide the scroll up button
$(window).scroll(function() {
	if ($(this).scrollTop() > 200) {
		$(".go-top").fadeIn(200);
	} else {
		$(".go-top").fadeOut(200);
	}
});
// Animate the scroll to top
$(".go-top").click(function(event) {
	event.preventDefault();
	$("html, body").animate({scrollTop: 0}, 300);
})
</script> 
<script>$( ".icon-star-empty, .icon-star" ).click(function(){ 
	var type, url, prop;
	url = "/_inc/form_fav.php";
	prop = $(this).attr("data-prop");
	$( this ).toggleClass("icon-star-empty icon-star");
	if($( this ).hasClass( "icon-star" )){
		type = "insert";
		_gaq.push(["_trackEvent", "Faves", "Add", "List Page"]);
	}else{
		type = "delete";
		_gaq.push(["_trackEvent", "Faves", "Remove", "List Page"]);
	}
    $.ajax({
    	type: "POST",
      	url: url,
      	data: { type: type, prop: prop, user: '<?php if(isset($_SESSION['ID_my_site'])){echo $_SESSION['ID_my_site'];} ?>', session: '<?php echo $_COOKIE["fav"]; ?>' },
       	success: function(data){
			//alert(data);
      	}
  	});
	return false;
	});</script>
</body></html>