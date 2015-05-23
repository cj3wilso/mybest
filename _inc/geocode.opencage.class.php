<?php
//http://geocoder.opencagedata.com/api.html#quickstart
//https://api.opencagedata.com/geocode/v1/google-v3-json?key=732d35a277ee147b27bc86170a328b45&pretty=1&country=ca&address=40%20balson%20blvd%20stouffville%20on
//Test url
//https://api.opencagedata.com/geocode/v1/google-v3-json?key=d93c216008ac7066154ceb1ad8c65ae5&pretty=1&country=ca&address=40%20balson%20blvd%20stouffville%20on
//error_reporting(E_ALL); ini_set('display_errors', '1');

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

include_once('global.php');

class opencage{
 	static private $url = "https://api.opencagedata.com/geocode/v1/google-v3-json?key=732d35a277ee147b27bc86170a328b45&pretty=1&country=ca&address=";
        
	static public function getLocationInfo($address,$provinces_array){
		$city = $region = $prov = $provLng = $post = $street = NULL;
		$search = $address;
		$orig_address = $address;
		$postcodeRegex = "[ABCEGHJKLMNPRSTVXY]{1}\d{1}[A-Z]{1} *\d{1}[A-Z]{1}\d{1}";
		if (preg_match("/".$postcodeRegex."/i",$address, $matches)){
			$post = $matches[0];
			$search = str_replace($post,"",$search);
		}
		$streetnumRegex = "^\d+ ";
		if (preg_match("/".$streetnumRegex."/i",$address, $matches)){
			$street = $matches[0];
		}else{
			//If no street address (doesn't start with number) then don't get address.. geocode fails.
			return false;
		}
		$search = trim($search);
		$addressurl = str_replace(" ","+",$search);
		$url = self::$url.$addressurl;
		$resp_json = self::curl_file_get_contents($url);
        $resp = json_decode($resp_json, true);
		//print_r($resp);
 		if($resp['status']=='OK'){
			$lat = $resp['results'][0]['geometry']['location']['lat'];
			$lng = $resp['results'][0]['geometry']['location']['lng'];
			$type = "";
			foreach ($resp['results'][0]['address_components'] as $address) {
				if (in_array("house_number", $address["types"])) {
					$street = $address["short_name"];
				}
				if (in_array("road", $address["types"])) {
					$street .= ' '.$address["short_name"];
				}
				if (in_array("city", $address["types"])) {
					$city = $address["short_name"];
				}
				if (in_array("county", $address["types"])) {
					$region = $address["short_name"];
				}
				if (in_array("state", $address["types"])) {
					$prov = $provinces_array[$address["long_name"]];
				}
				if (in_array("state", $address["types"])) {
					$provLng = $address["long_name"];
				}
				if (in_array("postal_code", $address["types"])) {
					$post = $address["short_name"];
				}
			}
			$street = trim($street);
			if(is_numeric($street)){$street="";}
			if($city==NULL){$city=$region;}
			return array($lat, $lng, $orig_address, $type, $city, $region, $prov, $provLng, strtoupper($post), $street);
 		}else{
			$message = "Opencage data not working<br>For address: $addressurl<br>Check https://api.opencagedata.com/geocode/v1/google-v3-json?key=732d35a277ee147b27bc86170a328b45&pretty=1&country=ca&address=40%20balson%20blvd%20stouffville%20on";
         	$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			mail('cj3wilso@gmail.com', 'Geo Opencage Status: '.$resp['status'], $message, $headers);
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

//$test = opencage::getLocationInfo("116 George Street, Toronto, ON M5A 3S2",$provinces_array);
//$test = opencage::getLocationInfo("2167 Angus Street, Regina, SK s4t2a1, Canada",$provinces_array);
//print_r($test);
//echo 'meek';
?>