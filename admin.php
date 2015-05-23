<?php
$page = "admin";
$pageTitle = "Admin Home";
include("global.php");
include("loggedin.php");

require("mysqlconnect.php");
include '_inc/paginator.class.php';
// Show all admin properties

$sort_options = array('name asc','name desc','rent asc','rent desc','created desc');
if(!isset($_GET['sort']) || !in_array(urldecode($_GET['sort']),$sort_options) ){
  	$sort = 'created DESC';
}else{
	$sort = urldecode($_GET['sort']);
	if($sort == 'rent asc'){
		$sort = "ABS(rent) ASC"; 
	}else if($sort == 'rent desc'){
		$sort = "ABS(rent) DESC";
	}else{
		$sort = $_GET['sort'];
	}
}

   	$check = mysql_query("SELECT * 
  	FROM properties AS p 
   	INNER JOIN prop_units AS u 
   	ON p.id_user = $_SESSION[ID_my_site] AND u.id_prop = p.id_pg
   	LEFT JOIN prop_photos c ON p.id_pg = c.id_prop AND c.p_order = 1 
   	GROUP BY p.id_pg");
	$totalrows = mysql_num_rows($check);
	
	//Start Pagination
	$pages = new Paginator;  
	$pages->items_total = $totalrows;
	$pages->mid_range = 7;
	$pages->paginate();
	
	$sql = "SELECT *, STR_TO_DATE(date, '%M %d %Y') as latest, 
	IF(COUNT( DISTINCT rent ) > 1,CONCAT('$', MIN(rent), ' - $', MAX(rent)),CONCAT('$', rent)) as rent, 
	IF(COUNT( DISTINCT beds ) > 1,CONCAT(MIN(beds), ' - ', MAX(beds)),beds) as beds
  	FROM properties AS p 
   	INNER JOIN prop_units AS u 
   	ON p.id_user = $_SESSION[ID_my_site] AND u.id_prop = p.id_pg
   	LEFT JOIN prop_photos c ON p.id_pg = c.id_prop AND c.p_order = 1 
   	GROUP BY p.id_pg
	ORDER BY p.pub ASC, $sort 
 	$pages->limit";
	//ORDER BY c.id DESC
		
$result = mysql_query($sql);
require("mysqlclose.php");	

//Remove Property
if (isset($_POST['remove'])) { 
	$today = date("c");
	foreach ($_POST['remove'] as $key => $value)
	{		
		delTree("upload/server/php/files/$value");
		include '_inc/mysqlconnect.php';
		mysql_query("UPDATE properties SET deleted=1, removed='$today' WHERE id_pg='$value'");
		mysql_query("DELETE FROM prop_feat WHERE id_prop='$value'");
		mysql_query("DELETE FROM prop_hours WHERE id_prop='$value'");
		mysql_query("DELETE FROM prop_intro WHERE id_prop='$value'");
		mysql_query("DELETE FROM prop_photos WHERE id_prop='$value'");
		mysql_query("DELETE FROM prop_units WHERE id_prop='$value'");
		mysql_query("DELETE FROM prop_promote WHERE id_prop='$value'");
		include '_inc/mysqlclose.php';
		header("Location: $adminHome");
	}
}
include("header.php");
?>

<div class="row">
    <div class="col-lg-10"><h1>Admin's Properties <span class="badge"><?php echo $totalrows; ?></span></h1></div>
    <div class="col-lg-2"><a href="<?php echo $advertise; ?>" class="btn btn-block btn-lg btn-inverse pull-right"><i class="icon-plus-sign"></i> Add New</a></div>
</div>

<div class="row">
    <?php
if ($totalrows == 0) {
	  ?>
    <div class="col-lg-12">
    <div class="alert alert-info">You have no properties on My Best Apartments. <a href="<?php echo $advertise; ?>">Add one now?</a></div>
    <?php
	
	}else {
	?>
    <div class="col-md-4" style="padding-top:20px;">
    <select name="herolist" id="sort-select" class="select-block">
            <option value='latest+desc' <?php if(!isset($_GET['sort']) || $_GET['sort']=='latest desc'){echo "selected";} ?>>Date (New to Old)</option>
        <option value='distance+asc' <?php if(isset($_GET['sort']) && $_GET['sort']=='distance asc'){echo "selected";} ?>>Distance Nearest</option>
        <option value='rent+asc' <?php if(isset($_GET['sort']) && $_GET['sort']=='rent asc'){echo "selected";} ?>>Price (Low to High)</option>
        <option value='rent+desc' <?php if(isset($_GET['sort']) && $_GET['sort']=='rent desc'){echo "selected";} ?>>Price (High to Low)</option>
        <option value='name+asc' <?php if(isset($_GET['sort']) && $_GET['sort']=='name asc'){echo "selected";} ?>>Name (A to Z)</option>
        <option value='name+desc' <?php if(isset($_GET['sort']) && $_GET['sort']=='name desc'){echo "selected";} ?>>Name (Z to A)</option>
          </select>
  </div>
  <div class="col-md-8">
     <span class="pagination pull-right">
    <ul>
    <?php echo $pages->display_pages() ?>
    </ul>
    </span>
  </div>
</div>

<?php
if(isset($_GET["error"])) { 
		echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.$_GET["error"].'</div>'; 
	}
?>

<div class="row">
  <form id="postrent" method="post">
  <div class="col-lg-12 list">
    <?php
	$pub = "";
	while ($row = @mysql_fetch_assoc($result)){
		
		if ($row['photo']){
			$photo = "/upload/server/php/files/".$row['id_pg']."/thumbnail/".$row['photo'];
		}else{
			$photo = "http://placehold.it/115x115&text=No%20Photos";
		}
		if($pub != $row['pub']){
			if ($row['pub']==0){
				$pubtitle = "Drafts";
			}else{
				$pubtitle = "Published";
			}
		?>
    <h5><?php echo $pubtitle; ?></h5>
    <?php
		}
		$pub = $row['pub'];
	?>
    <div class="media"> <a href="<?php echo $detail; ?>/<?php echo urlencode($row['prov']); ?>/<?php echo urlencode($row['city']); ?>/<?php echo cleanUrl($row['name']); ?>/<?php echo $row['id_pg']; ?>" class="pull-left"> <img class="media-object img-thumbnail" src="<?php echo $photo; ?>" width="115" height="115" alt="<?php echo $row['photo']; ?>"> </a>
      <div class="media-body">
        <div class="row">
          <div class="col-md-6">
            <h4 class="media-heading"><a href="<?php echo $detail; ?>/<?php echo urlencode($row['prov']); ?>/<?php echo urlencode($row['city']); ?>/<?php echo cleanUrl($row['name']); ?>/<?php echo $row['id_pg']; ?>"><?php echo $row['name']; ?></a></h4>
            <?php echo $row['address']; if($row['address2']){echo ', '.$row['address2'];} if($row['address'] || $row['address2']){echo ', ';} echo $row['city'].', '.$row['prov'].', '.$row['cntry'].', '.$row['post']; ?><br />
            Price: <?php echo $row['rent']; ?>, Beds: <?php echo $row['beds']; ?> <br />
            Page ID: <?php echo $row['id_pg']; ?>, Posted:  <?php echo $row['date']; ?> </div>
          <div class="col-md-2">
            <?php if($row['phone1'] != 0) echo '<div class="btn btn-block btn-inverse"><div class="fui-chat"></div> ('.$row['phone1'].') '.$row['phone2'].'-'.$row['phone3'].'</div>'; ?>
            <?php if($row['url']) echo '<a href="'.$row['url'].'" target="_blank" class="btn btn-block btn-inverse"><div class="fui-eye"></div> View Website</a>'; ?>
          </div>
          <div class="col-md-2">
          <a href="<?php echo $adminEdit; ?>/<?php echo $row['id_pg']; ?>" class="pull-right btn btn btn-sm btn-default" style="margin-left:12px;"><i class="icon-edit"></i> Edit</a>
          <button class="pull-right btn btn-sm btn-danger remove" name="remove[]" value="<?php echo $row['id_pg']; ?>" type="submit"><i class="icon-trash"></i> Delete</button>
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
<div class="row">
<div class="col-md-4" style="padding-top:20px;">
    <select name="herolist" id="sort-select" class="select-block">
            <option value='latest+desc' <?php if(!isset($_GET['sort']) || $_GET['sort']=='latest desc'){echo "selected";} ?>>Date (New to Old)</option>
        <option value='distance+asc' <?php if($_GET['sort']=='distance asc'){echo "selected";} ?>>Distance Nearest</option>
        <option value='rent+asc' <?php if($_GET['sort']=='rent asc'){echo "selected";} ?>>Price (Low to High)</option>
        <option value='rent+desc' <?php if($_GET['sort']=='rent desc'){echo "selected";} ?>>Price (High to Low)</option>
        <option value='name+asc' <?php if($_GET['sort']=='name asc'){echo "selected";} ?>>Name (A to Z)</option>
        <option value='name+desc' <?php if($_GET['sort']=='name desc'){echo "selected";} ?>>Name (Z to A)</option>
          </select>
  </div>
  <div class="col-md-8">
     <span class="pagination pull-right">
    <ul>
    <?php echo $pages->display_pages() ?>
    </ul>
    </span>
  </div>
</div>
<?php
}
include 'footer.php';
include 'footer_js.php';
?>
<script src="//code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="//cdn.jsdelivr.net/jquery.lazyload/1.8.4/jquery.lazyload.min.js" charset="utf-8"></script>
<script>
<?php echo $open; ?> 
$(function() {
	$("img.media-object").css("display", "block").lazyload({ 
    	effect : "fadeIn" 
	});
});
$(".remove").on("click", function () {
	var r = confirm("Are you sure you want to delete this property? \nCannot be undone.");
	if (r == true) {
		return true;
	} else {
		return false;
	}
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
</script>
</body>
</html>