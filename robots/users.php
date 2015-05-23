#!/usr/bin/php
<?php
include("global.php");
include "mysqlconnect.php";
$check = mysql_query("SELECT * FROM properties WHERE where_posted=''");
while ($row = @mysql_fetch_assoc($check)){
	if ($row['where_posted'] == ""){
		echo "Property: ".$row['id_pg']." ".$row['name']." ".$row['city']." ".$row['prov']." <br><br>\n\n";
	}
}
$check2 = mysql_query("SELECT * FROM users ORDER BY registered");
while ($row2 = @mysql_fetch_assoc($check2)){
	echo "Users: ".$row2['email']." registered: ".$row2['registered']." <br><br>\n\n";
}
include "mysqlclose.php";
?>