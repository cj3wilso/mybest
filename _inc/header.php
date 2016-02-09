<?php
//if (!isset($_COOKIE["expire"]) || time() > $_COOKIE["expire"] ){
	$sql = "(select COUNT(`city`) posts, prov, city from properties where prov = 'AB' AND removed = '0000-00-00 00:00:00' GROUP BY city order by posts DESC limit 6)
	union all
	(select COUNT(`city`) posts, prov, city from properties where prov = 'BC' AND city <> '' AND removed = '0000-00-00 00:00:00' GROUP BY city order by posts DESC limit 6)
	union all
	(select COUNT(`city`) posts, prov, city from properties where prov = 'MB' AND city <> '' AND removed = '0000-00-00 00:00:00' GROUP BY city order by posts DESC limit 6)
	union all
	(select COUNT(`city`) posts, prov, city from properties where prov = 'NB' AND city <> '' AND removed = '0000-00-00 00:00:00' GROUP BY city order by posts DESC limit 6)
	union all
	(select COUNT(`city`) posts, prov, city from properties where prov = 'NL' AND city <> '' AND removed = '0000-00-00 00:00:00' GROUP BY city order by posts DESC limit 6)
	union all
	(select COUNT(`city`) posts, prov, city from properties where prov = 'NS' AND city <> '' AND removed = '0000-00-00 00:00:00' GROUP BY city order by posts DESC limit 6)
	union all
	(select COUNT(`city`) posts, prov, city from properties where prov = 'ON' AND city <> '' AND removed = '0000-00-00 00:00:00' GROUP BY city order by posts DESC limit 6)
	union all
	(select COUNT(`city`) posts, prov, city from properties where prov = 'PE' AND city <> '' AND removed = '0000-00-00 00:00:00' GROUP BY city order by posts DESC limit 6)
	union all
	(select COUNT(`city`) posts, prov, city from properties where prov = 'QC' AND city <> '' AND removed = '0000-00-00 00:00:00' GROUP BY city order by posts DESC limit 6)
	union all
	(select COUNT(`city`) posts, prov, city from properties where prov = 'SK' AND city <> '' AND removed = '0000-00-00 00:00:00' GROUP BY city order by posts DESC limit 6)";
	$menuitems = mysql_query_cache($sql);
	$deskheader = $mobileheader = $newprov = $oldprov = "";

$deskheader = $mobileheader = $newprov = $oldprov = "";
foreach ($menuitems as $key => $value) {
	$newprov = $menuitems[$key]["prov"];
	if($newprov != $oldprov || $oldprov==""){ 
		//Save the header twice - once for regular view, once for mobile view
		$deskProv = '<li> <a href="' .$list.'/'.$menuitems[$key]['prov'].'">'.array_search ($menuitems[$key]['prov'], $provinces_array).'</a><ul>';
		$mobileProv = '<li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown">'.array_search ($menuitems[$key]['prov'], $provinces_array).'<b class="caret"></b></a><ul class="dropdown-menu">';
		if ($oldprov!=""){
			$deskheader .= '</ul></li>';
			$mobileheader .= '</ul></li>';
		}
		$deskheader .= $deskProv;
		$mobileheader .= $mobileProv;
	}
	$deskheader .= '<li><a href="' . $list.'/'.$menuitems[$key]['prov'].'/'.str_replace(" ", "+", $menuitems[$key]['city']).'">' .$menuitems[$key]['city'].' <span class="badge pull-right">' .$menuitems[$key]['posts'].'</span></a></li>';
	$mobileheader .= '<li><a href="' . $list.'/'.$menuitems[$key]['prov'].'/'.str_replace(" ", "+", $menuitems[$key]['city']).'">' .$menuitems[$key]['city'].' <span class="badge pull-right">' .$menuitems[$key]['posts'].'</span></a></li>';	
	$oldprov = $menuitems[$key]["prov"];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>
<?php if($pageTitle){echo $pageTitle;} ?>
</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="<?php echo $metaDesc; ?>">
<meta name="author" content="Christine Wilson">
<!--[if lt IE 9]>
	<script src="/assets/js/html5shiv.js"></script>
<![endif]-->
<link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
<link href="//netdna.bootstrapcdn.com/font-awesome/3.0.2/css/font-awesome.css" rel="stylesheet">
<!--[if lt IE 9]>
<link href="//netdna.bootstrapcdn.com/font-awesome/3.0.2/css/font-awesome-ie7.css" rel="stylesheet">
<![endif]-->
<link href="/assets/css/flat-ui.css" rel="stylesheet">
<link href="/assets/css/mybest.css?ver=140705" rel="stylesheet">
<style>
iframe#twitter-widget-0{
	position:relative;
	top:6px;
}
</style>
<?php 
if(isset($headStyles)){echo $headStyles;}
if(isset($headScripts)){echo $headScripts;}
?>
<link rel="shortcut icon" href="/assets/ico/favicon.ico">
<!-- START: Google Analytics -->
<script type="text/javascript">
  var _gaq = _gaq || [];
  var pluginUrl = 
 '//www.google-analytics.com/plugins/ga/inpage_linkid.js';
  _gaq.push(['_require', 'inpage_linkid', pluginUrl]);
  _gaq.push(['_setAccount', 'UA-9873523-11']);
  
  <?php if($page=="404"){?>
  _gaq.push(['_trackPageview','/errorpage/?url=' + document.location.pathname + document.location.search + '&ref=' + document.referrer]);
  <?php }else{ ?>
  _gaq.push(['_trackPageview']);
  <?php } ?>

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'stats.g.doubleclick.net/dc.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
<!-- END: Google Analytics -->
<!-- START: CRAZY EGG HEATMAP -->
<script type="text/javascript">
setTimeout(function(){var a=document.createElement("script");
var b=document.getElementsByTagName("script")[0];
a.src=document.location.protocol+"//dnn506yrbagrg.cloudfront.net/pages/scripts/0015/2093.js?"+Math.floor(new Date().getTime()/3600000);
a.async=true;a.type="text/javascript";b.parentNode.insertBefore(a,b)}, 1);
</script>
<!-- END: CRAZY EGG HEATMAP -->
</head>

<body itemscope itemtype="http://schema.org/WebPage" id="top" class="<?php echo $page; ?>">
<!-- START: Facebook JavaScript SDK -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&appId=137263626371962&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<!-- END: Facebook JavaScript SDK -->
<div id="wrap">
<div class="container">
<div class="row">
  <div class="col-sm-4">
    <div class="logo" itemscope itemtype="http://schema.org/Organization"><a href="http://mybestapartments.ca/" itemprop="url"><img src="/assets/img/logo-mybest.png" alt="My Best Apartments" width="367" height="36" class="img-responsive" itemprop="logo"></a></div>
  </div>
  <div class="col-sm-8">
    <form method="post" id="topsearch" style="width:100%;">
      <input type="search" placeholder="Search by City, Address, Page ID, Headline" class="form-control" autocomplete="off" name="addressInput" id="addressInput" style="width:100%;" />
      <span class="input-icon fui-search" style="height:32px;margin-top:6px;margin-right:15px;"></span>
    </form>
  </div>
</div>
<div class="navbar navbar-default">
  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
  <div class="navbar-collapse collapse navbar-collapse-01">
    <ul id="mobile-apt-menu" style="display:none" class="nav navbar-nav navbar-left">
      <?php echo $mobileheader; ?>
      </li>
    </ul>
    </ul>
    <ul class="nav navbar-nav navbar-left">
      <li class="<?php if($page=="cnty" || $page=="prov" || $page=="city" || $page=="map" || $page=="apartment"){echo 'active';}?>"> <a href="<?php echo $list; ?>" id="findapartment">Find Apartments</a>
        <ul>
          <?php echo $deskheader; ?>
        </ul>
      </li>
    </ul>
    </li>
    <li <?php if($page=="add"){echo ' class="active"';}?>> <a href="<?php echo $advertise; ?>">Add a Rental</a> </li>
    <li <?php if($page=="contact"){echo ' class="active"';}?>> <a href="<?php echo $contact; ?>">Contact</a> </li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
      <li <?php if($page=="faves"){echo ' class="active"';}?>><a href="<?php echo $faves; ?>">My Places</a></li>
      <?php if(isset($_SESSION['ID_my_site'])){ ?>
      <li><a href="<?php echo $adminLogout; ?>">Log Out</a></li>
      <li <?php if($page=="admin"){echo ' class="active"';}?>><a href="<?php echo $adminHome; ?>">Admin Area</a></li>
      <?php }else{ ?>
      <li <?php if($page=="signin"){echo ' class="active"';}?>><a href="<?php echo $adminLogin; ?>">Log In</a></li>
      <li <?php if($page=="register"){echo ' class="active"';}?>><a href="<?php echo $adminRegister; ?>">Register</a></li>
      <?php } ?>
    </ul>
  </div>
  <!--/.nav --> 
</div>
<!-- /navbar -->