<?php
if(!empty($_POST['promote'])){
	include '_inc/mysqlconnect.php';
	//IF COUPON FIELD WAS ENTERED
	if(!empty($_POST['coupon'])){
		$select_coupon = "SELECT * FROM coupons WHERE code='".$_POST['coupon']."'";
		$coupon = mysql_fetch_array(mysql_query($select_coupon));
	}
	//GET AUTH TOKEN
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_HTTPHEADER => array("Accept: application/json","Accept-Language: en_US"),
		CURLOPT_URL => 'https://api.paypal.com/v1/oauth2/token',
		CURLOPT_USERPWD => "AdnFsxBkugLLxjPBUyUDvD6EiDftPMkYzIC1bQxRWCri7avDNqe2stakOpuw:EJXJRxA-vHqYucbfYxio8J7oBIYhYhk1gvSrWKTWu5e-x9G-7t8kb4GPW7CO",
		CURLOPT_POST => 1,
		CURLOPT_POSTFIELDS => "grant_type=client_credentials",
		CURLOPT_RETURNTRANSFER => 1
	));
	$resp = curl_exec($curl);
	curl_close($curl);
	$obj = json_decode($resp,true);
	$access_token = $_SESSION["access_token"] = $obj['access_token'];
	$token_type = $_SESSION["token_type"] = $obj['token_type'];
	
	//GET FIELD VALUES
	$totalcost = 0;
	$itemlist = $queryinfo = $promoname = array();
	$itemdetail = array(
  		"A0012"=>array(
  			"Top Page Ad",
			"5.00"
  		),
  		"B0012"=>array(
  			"Home Page Ad",
			"10.00"
  		)
  	);
	foreach ($_POST['promote'] as $key => $value){
		$select_promote_type = "SELECT * FROM prop_promote WHERE id_prop='".$prop."' AND sku='".$value."';";
		$result = mysql_fetch_array(mysql_query($select_promote_type));
		$promoname[] = $itemdetail[$value][0];
		if (!empty($result)) {
			if($result["payer_id"] == NULL){
				$queryitem["query_type"] = "update";
			}else{
				continue;
			}
		}else{
			$queryitem["query_type"] = "insert";
		}
		$cost = $itemdetail[$value][1];
		$queryitem["sku_value"] = $value;
		$queryinfo[] = $queryitem;
		
		if($coupon){
			if($coupon["type"]=="percent"){
				$cost = $itemdetail[$value][1]-($itemdetail[$value][1]*$coupon["amount"]*0.01);
				$cost = number_format($cost, 2, '.', '');
			}
		}
		$itemlist[] = '
       	{
       		"quantity":"1", 
          	"name":"'.$itemdetail[$value][0].'", 
          	"price":"'.$cost.'",  
          	"sku":"'.$value.'", 
           	"currency":"CAD"
      	}';
		$totalcost = $totalcost+$cost;
	}
	if(!empty($itemlist)){
		$itemlist = implode(",", $itemlist);
		$totalcost = number_format($totalcost, 2, '.', '');
		$data = '{
			"intent":"sale",
			"redirect_urls":{
			"return_url":"http://mybestapartments.ca/thankyou",
			"cancel_url":"http://mybestapartments.ca/cancelled"
		},
		"payer":{
			"payment_method":"paypal"
		},
		"transactions":[{
				"amount": {
				"total": "'.$totalcost.'",
				"currency": "CAD",
				"details": {
				"subtotal": "'.$totalcost.'",
					"tax": "0.00",
					"shipping": "0.00"
				}
			},
			"item_list": { 
				"items":[
					'.$itemlist.'
				]
			}
			}]
		}';
		if($totalcost!=0){
			//SEND PAYMENT INFORMATION TO PAYPAL
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_HTTPHEADER => array("Content-Type: application/json","Authorization:".$token_type." ".$access_token),
				CURLOPT_URL => "https://api.paypal.com/v1/payments/payment",
				CURLOPT_POST => 1,
				CURLOPT_POSTFIELDS => $data,
				CURLOPT_RETURNTRANSFER => 1
			));
			$resp = curl_exec($curl);
			curl_close($curl);
		}
		//STORE PAYMENT INFORMATION TO DATABASE
		$obj = json_decode($resp,true);
		$today = date(c);
		parse_str($obj['links'][1]['href']);
		foreach ($queryinfo as $key => $value){
			if($totalcost==0){
				$payer_id = "FREE";
				$obj['id'] = $_POST['coupon'];
			}else{
				$payer_id = NULL;
			}
			if($queryinfo[$key]["query_type"]=="insert"){
				$insert_proppromote = "INSERT INTO prop_promote (id_prop, sku, created, pay_resource, token, payer_id, expired) VALUES ('".$prop."', '".$queryinfo[$key]["sku_value"]."', '$today','".$obj['id']."','$token', '$payer_id', '0000-00-00 00:00:00') ;";
				mysql_query($insert_proppromote) or die("A MySQL error has occurred.<br />Your Query: " . $insert_proppromote . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
			}else{
				$update_proppromote = "UPDATE prop_promote SET token='$token', pay_resource='".$obj['id']."', payer_id='$payer_id' WHERE sku='".$queryinfo[$key]["sku_value"]."' AND id_prop='".$prop."'";
				mysql_query($update_proppromote) or die("A MySQL error has occurred.<br />Your Query: " . $update_proppromote . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
			}
		}
		include '_inc/mysqlclose.php';
		//IF DISCOUNT ISN'T 100% SEND USER TO PAYPAL
		if($totalcost!=0){
			//REDIRECT TO PAYPAL FOR USER APPROVAL
			header("Location: ".$obj['links'][1]['href']);
		}else{
			header("Location: $adminHome");
		}
		exit();
	}else{
		$promoname = implode(" and ", $promoname);
		$message = "You have already purchased promotion(s): $promoname for Property ID: $prop.";
		header("Location: $adminHome");
	}
}