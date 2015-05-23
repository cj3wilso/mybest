#!/usr/bin/php
<?php
// Get latest file
function latest($dir) {
	$files = glob("$dir");
	$files = array_combine($files, array_map("filemtime", $files));
	arsort($files);
	return key($files);
}
include("bigdump.php");

// Function to restore files
function filerestore($file) {
	$zip = new ZipArchive;
	$dir = "/home/cj3wilso/public_html/dev_best/";
	if ($zip->open($file) === TRUE) {
		$zip->extractTo($dir);
		$zip->close();
		echo "Restored Files: $file<br>";
	} else {
		echo "Failed to Restore Files<br>";
	}
}

//Restore Files
$latest_files = latest("files/*.zip");
filerestore($latest_files);

