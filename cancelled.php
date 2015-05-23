<?php
$page = "paymentcancelled";
$pageTitle = "Cancelled Payment";
$metaDesc = "";
include("global.php");
$prop=$_SESSION['prop'];

if(isset($_GET["token"])){
	include '_inc/mysqlconnect.php';
	$delete_proppromote = "DELETE FROM prop_promote WHERE token='$_GET[token]' AND id_prop='".$prop."'";
	mysql_query($delete_proppromote);
	include '_inc/mysqlclose.php';
}

include("header.php");
?>

<div class="page-header">
  <h1>Payment Cancelled</h1>
</div>
<div class="row">
  	<div class="col-lg-12">
 	Your payment was cancelled at your request. You have not been charged.
	</div>
</div>

<?php
include 'footer.php';
include 'footer_js.php';
?>
</body>
</html>