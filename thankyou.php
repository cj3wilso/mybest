<?php
$page = "paymentreceived";
$pageTitle = "Thank you";
$metaDesc = "";
include("global.php");
$prop=$_SESSION['prop'];
$access_token = $_SESSION["access_token"];
$token_type = $_SESSION["token_type"];

if(isset($_GET["PayerID"])){
	include '_inc/mysqlconnect.php';
	$select_promote = "SELECT * FROM prop_promote WHERE token = '$_GET[token]' AND id_prop='".$prop."' AND payer_id is null;";
	$result2 = mysql_query($select_promote);
	$result = mysql_fetch_array($result2);
	if($result){
		$update_proppromote = "UPDATE prop_promote SET payer_id='$_GET[PayerID]' WHERE token='$_GET[token]' AND id_prop='".$prop."'";
		mysql_query($update_proppromote) or die("A MySQL error has occurred.<br />Your Query: " . $update_proppromote . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
		$curl = curl_init();
		$url = "https://api.paypal.com/v1/payments/payment/".$result["pay_resource"]."/execute/";
		$fields = '{ "payer_id" : "'.$_GET["PayerID"].'" }';
		curl_setopt_array($curl, array(
			CURLOPT_HTTPHEADER => array("Content-Type: application/json","Authorization:".$token_type." ".$access_token),
			CURLOPT_URL => $url,
			CURLOPT_POST => 1,
			CURLOPT_POSTFIELDS => $fields,
			CURLOPT_RETURNTRANSFER => 1
		));
		$resp = curl_exec($curl);
		curl_close($curl);
	}
	
	include '_inc/mysqlclose.php';
}
include("header.php");
?>

<div class="page-header">
  <h1>Thank you</h1>
</div>
<div class="row">
  	<div class="col-lg-12">
 	We've received your payment successfully. You're ad is now promoted!
	</div>
</div>

<?php
if($result){
	$footScripts = '<script>';
	while ($row = @mysql_fetch_assoc($result2)){
		if($row["sku"]=="A0012"){
			$footScripts .= '_gaq.push(["_trackEvent", "Promote", "Purchase", "Top Page", 5]);';
		}else if ($row["sku"]=="B0012"){
			$footScripts .= '_gaq.push(["_trackEvent", "Promote", "Purchase", "Home Page", 10]);';
		}
	}
	$footScripts .= '</script>';
}
	
include 'footer.php';
include 'footer_js.php';
?>
</body>
</html>