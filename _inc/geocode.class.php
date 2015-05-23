<?php

class geocoder{
 	//static private $url = "http://maps.google.com/maps/api/geocode/json?sensor=false&region=ca&address=";
	static private $url = "https://maps.googleapis.com/maps/api/geocode/json?sensor=false&region=ca&address=";
        
	static public function getLocationInfo($address){
		$search = $address;
		$addressurl = str_replace(" ","+",$search);
		$url = self::$url.$addressurl;
		$resp_json = self::curl_file_get_contents($url);
        $resp = json_decode($resp_json, true);
		
		//print '<p>&nbsp;</p>Status:'.$resp['status'].'<br>lat:';
		//var_dump($resp['results'][0]['geometry']['location']['lat']);
 		if($resp['status']='OK' && $resp['results'][0]){
			$lat = $resp['results'][0]['geometry']['location']['lat'];
			$lng = $resp['results'][0]['geometry']['location']['lng'];
			$type = $resp['results'][0]['types'][0];
			$city = $region = $prov = $provLng = $post = $street = NULL;
			foreach ($resp['results'][0]['address_components'] as $address) {
				if (in_array("street_number", $address["types"])) {
					$street .= $address["short_name"];
				}
				if (in_array("route", $address["types"])) {
					$street .= ' '.$address["short_name"];
				}
				if (in_array("locality", $address["types"])) {
					$city = $address["short_name"];
				}
				if (in_array("administrative_area_level_2", $address["types"])) {
					$region = $address["short_name"];
				}
				if (in_array("administrative_area_level_1", $address["types"])) {
					$prov = $address["short_name"];
				}
				if (in_array("administrative_area_level_1", $address["types"])) {
					$provLng = $address["long_name"];
				}
				if (in_array("postal_code", $address["types"])) {
					$post = $address["short_name"];
				}
			}
			return array($lat, $lng, $search, $type, $city, $region, $prov, $provLng, $post, $street);
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
 
?>