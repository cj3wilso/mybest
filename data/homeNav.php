<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include("global.php");
require("mysqli-connect.php");

$sql = "(select COUNT(`city`) posts, prov, city from properties where prov = 'AB' AND removed = '0000-00-00 00:00:00' GROUP BY city order by posts DESC limit 6)
	union all
	(select COUNT(`city`) posts, prov, city from properties where prov = 'BC' AND city <> '' AND removed = '0000-00-00 00:00:00' GROUP BY city order by posts DESC limit 6)
	union all
	(select COUNT(`city`) posts, prov, city from properties where prov = 'MB' AND city <> '' AND removed = '0000-00-00 00:00:00' GROUP BY city order by posts DESC limit 6)
	union all
	(select COUNT(`city`) posts, prov, city from properties where prov = 'NB' AND city <> '' AND removed = '0000-00-00 00:00:00' GROUP BY city order by posts DESC limit 6)
	union all
	(select COUNT(`city`) posts, prov, city from properties where prov = 'NL' AND city <> '' AND removed = '0000-00-00 00:00:00' GROUP BY city order by posts DESC limit 6)
	union all
	(select COUNT(`city`) posts, prov, city from properties where prov = 'NS' AND city <> '' AND removed = '0000-00-00 00:00:00' GROUP BY city order by posts DESC limit 6)
	union all
	(select COUNT(`city`) posts, prov, city from properties where prov = 'ON' AND city <> '' AND removed = '0000-00-00 00:00:00' GROUP BY city order by posts DESC limit 6)
	union all
	(select COUNT(`city`) posts, prov, city from properties where prov = 'PE' AND city <> '' AND removed = '0000-00-00 00:00:00' GROUP BY city order by posts DESC limit 6)
	union all
	(select COUNT(`city`) posts, prov, city from properties where prov = 'QC' AND city <> '' AND removed = '0000-00-00 00:00:00' GROUP BY city order by posts DESC limit 6)
	union all
	(select COUNT(`city`) posts, prov, city from properties where prov = 'SK' AND city <> '' AND removed = '0000-00-00 00:00:00' GROUP BY city order by posts DESC limit 6)";
$result = $conn->query($sql);
$rows = $result->num_rows;

$outp = $newProv = $lastProv = "";
while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
	$newProv = $rs["prov"];
	$comma = ",";
	if($newProv==$lastProv){
		$outp .= ',';
	}
	if($newProv!=$lastProv){
		if($lastProv!=''){
		$outp .= '}]},';
		}
		$outp .= '{"Province":"'  . array_search ($rs["prov"], $provinces_array)  . '",';
		$outp .= '"Prov":"'  . $rs["prov"]  . '",';
		$outp .= '"Cities": [{';
	}
	$outp .= '"'.$rs["city"].'":"'.$rs["posts"].'"';
	$lastProv = $rs["prov"];
}
$outp .= '}]}';

$json ='{';
$json .='"records":['.$outp.']';
$json .= '}';
$conn->close();
echo($json);