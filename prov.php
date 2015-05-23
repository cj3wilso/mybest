<?php
include("form_quicksearch.php");
if (  !isset ($_POST['submit']) ) { 
	require("mysqlconnect.php");
	
	// Show provinces in Canada
	$sql = "SELECT DISTINCT p.prov
	FROM properties p 
	WHERE p.removed = '0000-00-00 00:00:00' AND p.pub=1
	ORDER BY p.prov";
	
	$result = mysql_query($sql);
	$num_rows = mysql_num_rows($result);
	
	require("mysqlclose.php");
	
}
include("header.php");
?>
<ul class="breadcrumb">
  <li><a href="/">Home</a> </li>
  <li class="active">Canada</li>
</ul>
<h1>Current rentals in Canada</h1>
<table class="table table-striped table-hover" style="margin-top:25px;">
<tr>
	<?php
if ($num_rows==0) {
	  ?>
      <h4>No rentals at this time.</h4>
      <?php
	}else {
	$table = 1;
	$json = file_get_contents("$root/_inc/prov.json");
	$resp = json_decode($json, true);
	while ($row = @mysql_fetch_assoc($result)){
		foreach ($resp["country"] as $country) {
			if ( $country["prov"] == $row['prov'] ) {
				?>
                <td><a href="/rent/<?php echo $row['prov']; ?>"><?php echo $country["provLng"]; ?></a></td>
                <?php
				if ($table % 3 == 0){
					?>
                  </tr>
                  <tr>
                  <?php
				}
				$table ++;
			}
		}
		}
  $table--;
  while ($table % 3 != 0){
	?>
    <td>&nbsp;</td>
    <?php
	$table ++;
  }
}
?>
</tr>
</table>
<?php
include("footer.php");
include 'footer_js.php';
?>
<script>
<?php 
//If user redirected from list page - record the URL
if(isset($_SESSION["listredirect"]) && $_SESSION["listredirect"]!=""){
?>
_gaq.push(["_trackEvent", "List Page Redirect", "<?php echo $_SESSION["listredirect"];?>", "Prov Page"]);
<?php
$_SESSION["listredirect"]="";
}
?>
</script>
</body>
</html>