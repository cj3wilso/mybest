<?php
$prov = provSearch($_GET["prov"]);
if ( $prov == NULL ){
	//If Prov Doesn't Exist - Go to Country Level
	header ('HTTP/1.1 301 Moved Permanently');
	header('Location: '.$list);
}

include("form_quicksearch.php");
	require("mysqlconnect.php");
	
	// Show cities that have rentals
	$sql = "SELECT DISTINCT p.city,
	COUNT(p.city) AS entries 
	FROM properties p 
	WHERE p.prov = '$prov' AND p.city != '' AND p.removed = '0000-00-00 00:00:00' AND p.pub=1
	GROUP BY p.city
	ORDER BY entries DESC, p.city ASC";
	
	//, COUNT(DISTINCT prov) AS count 
	
	$result = mysql_query($sql);
	$num_rows = mysql_num_rows($result);
	
	require("mysqlclose.php");
	
include("header.php");
$json = file_get_contents("$root/_inc/prov.json");
$resp = json_decode($json, true);
foreach ($resp["country"] as $country) {
	if ( $country["prov"] == $prov ) {
		$provLng = $country["provLng"];
	}
}
?>

<ul class="breadcrumb">
  <li><a href="/">Home</a> </li>
  <li><a href="<?php echo $list ?>">Canada</a> </li>
  <li class="active"><?php echo $prov; ?></li>
</ul>
<?php
if ($num_rows==0) {
?>
<h4>No rentals in <?php echo $provLng; ?> at this time.</h4>
<?php
}else {
?>
<h1>Current rentals in <?php echo $provLng; ?></h1>
<?php
}
?>
<table class="table table-striped table-hover" style="margin-top:25px;">
  <tr>
    <?php
	$table = 1;
	while ($row = @mysql_fetch_assoc($result)){
	?>
    <td> <div style="float:left;width:40px;margin-right:6px;"><span class="label label-default"><?php echo $row['entries']; ?></span></div> <a href="/rent/<?php echo $prov; ?>/<?php echo urlencode($row['city']); ?>"><?php echo $row['city']; ?></a> </td>
    <?php
	if ($table % 3 == 0){
	?>
  </tr>
  <tr>
  <?php
  }$table ++;}
  $table--;
  while ($table % 3 != 0){
	?>
    <td>&nbsp;</td>
    <?php
	$table ++;
  }
  ?>
  </tr>
</table>
<?php
include("footer.php");
include 'footer_js.php';
?>
</body>
</html>