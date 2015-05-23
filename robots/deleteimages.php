#!/usr/bin/php
<?php
include "global.php";
include "mysqlconnect.php";//$root
$path = "../upload/server/php/files";
$results = scandir($path);
echo "<ol>";
foreach ($results as $result) {
	if ($result === '.' or $result === '..') continue;
    if (is_dir($path . '/' . $result)) {
        $image_website = mysql_query("SELECT id_pg FROM properties WHERE id_pg='$result'");
		$image_exists = mysql_num_rows($image_website);
		if(!$image_exists){
			delTree($path . '/' . $result);
			echo "<li>Image Deleted: $result</li>";
		}
    }
}
echo "</ol>";
include "mysqlclose.php";
?>