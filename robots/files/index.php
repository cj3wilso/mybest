#!/usr/bin/php
<?php
function Zip($source, $destination)
{
    if (!extension_loaded('zip') || !file_exists($source)) {
        return false;
    }

    $zip = new ZipArchive();
    if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
        return false;
    }

    $source = str_replace('\\', '/', realpath($source));

    if (is_dir($source) === true)
    {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

        
		
		foreach ($files as $file)
        {
			$file = str_replace('\\', '/', $file);

            // Ignore "." and ".." folders
			$folders = array('.','..','robots','files','stylesheets','org','getimagedata','drawform','mobile','type','cors','cgi-bin');
			foreach ($folders as $exclude)
        	{
				if (strpos($file,'/'.$exclude) !== false) {
					continue 2;
				}
			}
			echo $file."\n";
			$file = realpath($file);
			if (is_dir($file) === true){
				$zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
			}else if (is_file($file) === true){
				$zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
			}
        }
    }
    else if (is_file($source) === true)
    {
        $zip->addFromString(basename($source), file_get_contents($source));
    }

    return $zip->close();
}
Zip('/var/www/html/', '/home/Christine/files_'.date("y-m-d-i").'.zip');