#!/usr/bin/php
<?php
    include_once("global.php");
	header("Content-Type: application/rss+xml; charset=UTF-8");
 
    $rssfeed = '<?xml version="1.0" encoding="UTF-8"?>'." \n ";
    $rssfeed .= '<rss version="2.0">'." \n ";
    $rssfeed .= '<channel>'." \n ";
    $rssfeed .= '<title>My Best Apartments</title>'." \n ";
    $rssfeed .= '<link>http://www.mybestapartments.ca</link>'." \n ";
    $rssfeed .= '<description>RSS feed for My Best Apartments</description>'." \n ";
    $rssfeed .= '<language>en-us</language>'." \n ";
    $rssfeed .= '<copyright>Copyright (C) 2014 mybestapartments.ca</copyright>'." \n ";
 	
	include 'mysqlconnect.php';
 
	$query = "SELECT p.*, u.*, i.text, c.photo, STR_TO_DATE(date, '%M %d %Y') as latest
		FROM (properties p) 
		INNER JOIN prop_units u ON (p.id_pg = u.id_prop) 
		INNER JOIN prop_intro i ON (p.id_pg = i.id_prop) 
		LEFT JOIN prop_photos c ON p.id_pg = c.id_prop AND c.p_order = 1 
		WHERE p.pub=1 AND p.deleted=0
		GROUP BY p.id_pg
		ORDER BY latest DESC
		LIMIT 0, 1000";
    $result = mysql_query($query) or die ("Could not execute query");
 
    while($row = mysql_fetch_array($result)) {
        extract($row);
		
       if ($address2){$address2=', '.$promo['address2'];}
		if($address || $address2){$comma = ', ';}else{ $comma = "";}
		$entireAddress = $address.$address2.$comma.$city.', '.$prov.', '.$cntry.', '.$post; 
		if($created != "0000-00-00 00:00:00"){$date=$created;}
			   
	    $rssfeed .= '<item>'." \n ";
        $rssfeed .= '<title>' . htmlspecialchars($name) . '</title>'." \n ";
		$rssfeed .= '<address>' . htmlspecialchars($entireAddress) . '</address>'." \n ";
        $rssfeed .= '<rent>' . $rent . '</rent>'." \n ";
		$rssfeed .= '<beds>' . $beds . '</beds>'." \n ";
		$rssfeed .= '<pageid>' . $id_pg . '</pageid>'." \n ";
		$rssfeed .= '<link>http://mybestapartments.ca/apartment/' . urlencode($prov).'/'. urlencode($city).'/'. cleanUrl(htmlspecialchars($name)).'/'.$id_pg. '</link>'." \n ";
		//If current listing has photo
		$image = '';
		if(isset($photo)){
			$image = '<img width="115" height="115" src="http://mybestapartments.ca/upload/server/php/files/'.$id_pg.'/slide/'.$photo.'" title="'.htmlspecialchars($name).'"/>';
			$rssfeed .= '<photo>' . "http://mybestapartments.ca/upload/server/php/files/".$id_pg."/slide/".$photo.'</photo>'." \n ";
		}
		$showaddress = htmlspecialchars("\n Address: ".$entireAddress."\n");
		$rssfeed .= "<description> \n <![CDATA[ \n" . $image.$showaddress.htmlspecialchars(trim($text)) . " \n ]]> \n </description>"." \n ";
        $rssfeed .= '<pubDate>' . $date . '</pubDate>'." \n ";
        $rssfeed .= '</item>'." \n ";
    }
 
    $rssfeed .= '</channel>'." \n ";
    $rssfeed .= '</rss>'." \n ";
 
include 'mysqlclose.php';
$root = str_replace('/_inc','',$root);
$file = $root."/rssfeed.xml";

$open = fopen($file,"w");
echo $file;
if($open){
	echo fwrite($open,$rssfeed);
	echo "\n successfully opened file! \n\n";
}else{
	echo "\n failed! could not open file to write. \n\n";
}
fclose($open);

echo $rssfeed;
echo "this is root: ".$root;
?>