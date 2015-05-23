<?php
header('Content-type: text/json');
require("OAuth.php");

$cc_key  = "dj0yJmk9cmJ1WFVVMWFjQm5YJmQ9WVdrOVQwUXhUV1poTkdVbWNHbzlOamN3T0RZME1qWXkmcz1jb25zdW1lcnNlY3JldCZ4PTk1";
$cc_secret = "c2468c829f9a8e3db84a86cdafe42608d4a3e58d";
$url = "http://yboss.yahooapis.com/geo/placefinder";
$args = array();
$args["q"] = "100 toronto street uxbridge ontario";  
$args["flags"] = "J";
//$args["count"] = 1;
 
class NewsElement {
    var $abstract; 
    var $clickurl;   
    var $title; 
    var $language;
    var $date;
    var $source;
    var $sourceurl;
    var $url;
   
    function NewsElement($aa)
    {
      foreach ($aa as $k=>$v) {
            $this->$k = $aa[$k];
           
      }
    }
}
 
function parseMol($mvalues)
{
      for ($i=0; $i < count($mvalues); $i++) {
      $mol[$mvalues[$i]["tag"]] = $mvalues[$i]["value"];
    }
    return new NewsElement($mol);
}
 
function readXml($xmlResult)
{
    // read the XML database of aminoacids
    //$data = implode("", $xmlResult);
   
    $parser = xml_parser_create();
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
   
    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
    xml_parse_into_struct($parser, $xmlResult, $values, $tags);
   
    if(!xml_parser_free($parser))
      die("Failed parsing");
   
    // loop through the structures
    foreach ($tags as $key=>$val) {
      if ($key == "result") {
            $molranges = $val;
            // each contiguous pair of array entries are the
            // lower and upper range for each molecule definition
            for ($i=0; $i < count($molranges); $i+=2) {
                
                  $offset = $molranges[$i] + 1;
                 
                $len = $molranges[$i + 1] - $offset;
               
                $tdb[] = parseMol(array_slice($values, $offset, $len));
               
            }
        } else {
            continue;
        }
    }
    return $tdb;
}
 
// Create oAuth request
$consumer = new OAuthConsumer($cc_key, $cc_secret);
$request = OAuthRequest::from_consumer_and_token($consumer, NULL,"GET", $url, $args);
$request->sign_request(new OAuthSignatureMethod_HMAC_SHA1(), $consumer, NULL);
$url = sprintf("%s?%s", $url, OAuthUtil::build_http_query($args));
 
// Initalize HTTP request
$ch = curl_init();
$headers = array($request->to_header());
 
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
curl_setopt($ch, CURLOPT_HEADER, 0); 
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
$rsp = curl_exec($ch);
 
$curl_result = curl_exec( $ch ) or die ( "could not execute the request" );
curl_close( $ch ); // close curl session
 
// Read the XML and write to a single array
//$results = readXml($rsp);
$rsp = json_decode($rsp, true);
//var_dump($rsp);
$response = $rsp['bossresponse']['responsecode'];
$lat = $rsp['bossresponse']['placefinder']['results'][0]['latitude'];
$lng = $rsp['bossresponse']['placefinder']['results'][0]['longitude'];
$type = $rsp['bossresponse']['placefinder']['results'][0]['woetype'];
$street = $rsp['bossresponse']['placefinder']['results'][0]["line1"];
$city = $rsp['bossresponse']['placefinder']['results'][0]["city"];
$region = $rsp['bossresponse']['placefinder']['results'][0]["county"];
$prov = $rsp['bossresponse']['placefinder']['results'][0]["statecode"];
$provLng = $rsp['bossresponse']['placefinder']['results'][0]["state"];
$post = $address["postal"];

echo $response.$lat.$lng.'my type is: '.$type.$street.$city.$region.$prov.$provLng.$post;
 
?>