<?php
class geocoder{
	static private $url = "http://www.mapquestapi.com/geocoding/v1/address?key=Fmjtd%7Cluubnuuzlu%2C75%3Do5-9u1nqu&location=";

	static public function getLocationInfo($address){
    	$url = self::$url.urlencode($address);
       	$resp = self::curl_file_get_contents($url);
       	$resp = json_decode($resp, true);
		
		/*
		echo '<pre>';
		print_r($resp);
		echo '</pre>';
		*/
		
		$response = $resp['info']['statuscode'];
		
		if($response==0){
			$lat = $resp['results'][0]['locations'][0]['latLng']['lat'];
			$lng = $resp['results'][0]['locations'][0]['latLng']['lng'];
			$type = $resp['results'][0]['locations'][0]['geocodeQuality'];
			$city = $resp['results'][0]['locations'][0]['adminArea5'];
			$region = $resp['results'][0]['locations'][0]['adminArea4'];
			$prov = $resp['results'][0]['locations'][0]['adminArea3'];
			$provLng = $resp['results'][0]['locations'][0]['adminArea3'];
			$post = $resp['results'][0]['locations'][0]['postalCode'];
			$street = $resp['results'][0]['locations'][0]['street'];
			return array($lat, $lng, $type, $city, $region, $prov, $provLng, $post, $street);
   		}else{
         	return false;
      	}
	}

   	static private function curl_file_get_contents($URL){
   		$c = curl_init();
    	curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
      	curl_setopt($c, CURLOPT_URL, $URL);
      	$contents = curl_exec($c);
       	curl_close($c);

      	if ($contents) return $contents;
       		else return FALSE;
  	}
}
 
list($lat, $lng, $type, $city, $region, $prov, $provLng, $post, $street) = geocoder::getLocationInfo("100 toronto st s uxbridge on");
echo $lat.$lng.'my type is: '.$type.$street.$city.$region.$prov.$provLng.$post;
?>
 