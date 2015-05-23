<?php
require("global.php");
require("mysqlconnect.php");
$sql = "SELECT DISTINCT p.prov
	FROM properties p 
	WHERE p.removed = '0000-00-00 00:00:00' AND p.pub=1
	ORDER BY p.prov";
$result = mysql_query($sql);
require("mysqlclose.php");
$totalrows = mysql_num_rows($result);

$return_arr = array();
while ($row = @mysql_fetch_assoc($result)){
  array_push($return_arr, array(
  'prov' => $row['prov']
  ));
 }
$return_arr = json_encode($return_arr);

//start output
if(array_key_exists('callback', $_GET)){

    header('Content-Type: text/javascript; charset=utf8');
    header('Access-Control-Allow-Origin: http://localhost/');
    header('Access-Control-Max-Age: 3628800');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

    $callback = $_GET['callback'];
    echo $callback.'('.$return_arr.');';

}else{
    // normal JSON string
    header('Content-Type: application/json; charset=utf8');
    echo '{ "repositories": '.$return_arr.'}';
}
?>
