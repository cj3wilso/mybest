<?php
$page = "faves";
$pageTitle = "Faves";
include("global.php");

require("mysqlconnect.php");
include '_inc/paginator.class.php';

// Show user starred faves
$sort_options = array('name asc','name desc','rent asc','rent desc','latest desc');

   	/*
	$check = mysql_query("SELECT * 
  	FROM properties AS p 
   	INNER JOIN prop_units AS u ON u.id_prop = p.id_pg
   	INNER JOIN user_fav uf ON p.id_pg = uf.id_prop AND $fav_find 
   	GROUP BY p.id_pg");
	$totalrows = mysql_num_rows($check);
	
	//Start Pagination
	$pages = new Paginator;  
	$pages->items_total = $totalrows;
	$pages->mid_range = 7;
	$pages->paginate();
	*/
	
	$sql = "SELECT *, uf.id_prop AS star, STR_TO_DATE(date, '%M %d %Y') as latest, 
	IF(COUNT( DISTINCT rent ) > 1,CONCAT('$', MIN(rent), ' - $', MAX(rent)),CONCAT('$', rent)) as rent, 
	IF(COUNT( DISTINCT beds ) > 1,CONCAT(MIN(beds), ' - ', MAX(beds)),beds) as beds
  	FROM properties AS p 
   	INNER JOIN prop_units AS u ON u.id_prop = p.id_pg
   	LEFT JOIN prop_photos c ON p.id_pg = c.id_prop AND c.p_order = 1 
	INNER JOIN user_fav uf ON p.id_pg = uf.id_prop AND $fav_find 
   	GROUP BY p.id_pg";
		
$result = mysql_query($sql);
$prop_totalrows = mysql_num_rows($result);


$search_sql = "SELECT us.url AS savedsearch
	FROM user_search us
	WHERE $srch_find AND deleted=0";
$search_result = mysql_query($search_sql);
$search_totalrows = mysql_num_rows($search_result);
require("mysqlclose.php");	
include("header.php");
?>

<?php
//Start Searches
if ($search_totalrows == 0) {
	  ?>
    <h1>Saved Searches <span class="badge"><?php echo $search_totalrows; ?></span></h1>
    <div class="row">
    <div class="col-lg-12">
    <div class="alert alert-info">You have no saved searches yet. Click the star icon by "Save this Search" on the listing page.</div>
    </div>
    </div>
    <?php
	}else {
	?>
    <div class="row">
    <div class="col-lg-6">
    <h1>Saved Searches <span class="badge"><?php echo $search_totalrows; ?></span></h1>
    </div>
    <div class="col-lg-6">
    
  </div>
</div>

<div class="row">
  <form id="postrent" method="post">
  <div class="col-lg-12 searches">
    <?php
	while ($search = @mysql_fetch_assoc($search_result)){
		?>
    
    <p><i class="icon-star" data-prop="<?php echo $search["savedsearch"]; ?>"></i> <a href="<?php echo "http://".$_SERVER["HTTP_HOST"].$search["savedsearch"]; ?>"><?php echo "http://".$_SERVER["HTTP_HOST"].$search["savedsearch"]; ?></a></p>
   
    <?php
	}
?>
  </div>
   </form>
</div>
<?php
}
?>


<?php
//Start Properties
if ($prop_totalrows == 0) {
	  ?>
    <h1>Saved Properties <span class="badge"><?php echo $prop_totalrows; ?></span></h1>
    <div class="row">
    <div class="col-lg-12">
    <div class="alert alert-info">You have no properties saved yet. Click the star icon located by the property title on the listing page.</div>
    </div>
    </div>
    <?php
	
	}else {
	?>
    <div class="row">
    <div class="col-lg-6">
    <h1>Saved Properties <span class="badge"><?php echo $prop_totalrows; ?></span></h1>
    </div>
    <div class="col-lg-6">
    
  </div>
</div>

<div class="row">
  <form id="postrent" method="post">
  <div class="col-lg-12 list properties">
    <?php
	while ($row = @mysql_fetch_assoc($result)){
		if ($row['photo']){
			$photo = "/upload/server/php/files/".$row['id_pg']."/thumbnail/".$row['photo'];
			$alt = $row['photo'];
		}else{
			$photo = "http://placehold.it/115x115&text=No%20Photos";
			$alt = "Photos Coming Soon";
		}
		?>
    
    <div class="media"> <a href="<?php echo $detail; ?>/<?php echo urlencode($row['prov']); ?>/<?php echo urlencode($row['city']); ?>/<?php echo cleanUrl($row['name']); ?>/<?php echo $row['id_pg']; ?>" class="pull-left"> <img class="media-object img-thumbnail" src="<?php echo $photo; ?>" width="115" height="115" alt="<?php echo $alt; ?>"> </a>
      <div class="media-body">
        <div class="row">
          <div class="col-md-7">
            <h4 class="media-heading"><a href="<?php echo $detail; ?>/<?php echo urlencode($row['prov']); ?>/<?php echo urlencode($row['city']); ?>/<?php echo cleanUrl($row['name']); ?>/<?php echo $row['id_pg']; ?>"><?php echo $row['name']; ?></a> <i class="icon-star" data-prop="<?php echo $row['id_pg']; ?>"></i></h4>
            <?php echo $row['address']; if($row['address2']){echo ', '.$row['address2'];} if($row['address'] || $row['address2']){echo ', ';} echo $row['city'].', '.$row['prov'].', '.$row['cntry'].', '.$row['post']; ?><br />
            Price: <?php echo $row['rent']; ?>, Beds: <?php echo $row['beds']; ?> <br />
            Page ID: <?php echo $row['id_pg']; ?>, Posted:  <?php echo $row['date']; ?> </div>
          <div class="col-md-3">
            <?php if($row['phone1'] != 0) echo '<div class="btn btn-block btn-inverse"><div class="fui-chat"></div> ('.$row['phone1'].') '.$row['phone2'].'-'.$row['phone3'].'</div>'; ?>
            <?php if($row['url']) echo '<a href="'.$row['url'].'" target="_blank" class="btn btn-block btn-inverse"><div class="fui-eye"></div> View Website</a>'; ?>
          </div>
        </div>
      </div>
    </div>
   
    <?php
	}
?>
  </div>
   </form>
</div>
<?php
}
$footScripts='

';
include 'footer.php';
include 'footer_js.php';
?>
<script src="//code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="//cdn.jsdelivr.net/jquery.lazyload/1.8.4/jquery.lazyload.min.js" charset="utf-8"></script>
<script>
<?php //echo $open; ?>  
$(function() {
	$("img.media-object").css("display", "block").lazyload({ 
    	effect : "fadeIn" 
	});
});
$("#sort-select").on("change", function () {
	newUrl = updateQueryStringParameter(document.location.href, "sort", $(this).val());
	document.location.href= newUrl;
});
function updateQueryStringParameter(uri, key, value) {
  var re = new RegExp("([?|&])" + key + "=.*?(&|$)", "i");
  separator = uri.indexOf("?") !== -1 ? "&" : "?";
  if (uri.match(re)) {
    return uri.replace(re, "$1" + key + "=" + value + "$2");
  }
  else {
    return uri + separator + key + "=" + value;
  }
}
//Save a Search
$( ".searches .icon-star-empty, .searches .icon-star" ).click(function(){ 
	var type, url, prop;
	url = "/_inc/form_save_search.php";
	saveurl = $(this).attr("data-prop");
	$( this ).toggleClass("icon-star-empty icon-star");
	if($( this ).hasClass( "icon-star" )){
		type = "insert";
		_gaq.push(["_trackEvent", "Save Search", "Add", "Faves"]);
	}else{
		type = "delete";
		_gaq.push(["_trackEvent", "Save Search", "Remove", "Faves"]);
	}
    $.ajax({
    	type: "POST",
      	url: url,
      	data: { type: type, saveurl: saveurl, user: '<?php if(isset($_SESSION['ID_my_site'])){echo $_SESSION['ID_my_site'];} ?>', session: '<?php if(isset($_COOKIE['fav'])){echo $_COOKIE["fav"];} ?>' },
       	success: function(data){
			//alert(data);
      	}
  	});
	return false;
})

//Save Favorite Property
$( ".properties .icon-star-empty, .properties .icon-star" ).click(function(){ 
	var type, url, prop;
	url = "/_inc/form_fav.php";
	prop = $(this).attr("data-prop");
	$( this ).toggleClass("icon-star-empty icon-star");
	if($( this ).hasClass( "icon-star" )){
		type = "insert";
		_gaq.push(["_trackEvent", "Faves", "Add", "Faves"]);
	}else{
		type = "delete";
		_gaq.push(["_trackEvent", "Faves", "Remove", "Faves"]);
		//$( this ).parents(".media").hide();
	}
    $.ajax({
    	type: "POST",
      	url: url,
      	data: { type: type, prop: prop, user: '<?php if(isset($_SESSION['ID_my_site'])){echo $_SESSION['ID_my_site'];} ?>', session: '<?php if(isset($_COOKIE['fav'])){echo $_COOKIE["fav"];} ?>' },
       	success: function(data){
			//alert(data);
      	}
  	});
	return false;
	});
</script>
</body>
</html>