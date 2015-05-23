<?php

class walkscore{
        static private $url = "http://api.walkscore.com/score?format=json&wsapikey=e3c3e6c18057af6c2581a4e03df9c27b";
		static public function get($address, $lat, $lng){
            $url = self::$url."&address=".urlencode($address)."&lat=".$lat."&lon=".$lng;
            $resp_json = self::curl_file_get_contents($url);
            $resp = json_decode($resp_json, true);

            if($resp['status']==1){
				return '<a href="'.$resp['ws_link'].'" target="_blank">Walk score: '.$resp['walkscore'].'</a>';
            }else{
                return '<a href="http://www.walkscore.com/score/'.urlencode($address).'/lat='.$lat.'/lng='.$lng.'" target="_blank">See Walk Score</a>';
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