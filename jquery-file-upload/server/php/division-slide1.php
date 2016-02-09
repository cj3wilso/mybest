<?php
/*
 * jQuery File Upload Plugin PHP Example 5.14
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

error_reporting(E_ALL | E_STRICT);
require('UploadHandler.php');
$divcity = str_replace(" ", "-", strtolower($_SESSION['AdminCity_session']));

$https = !empty($_SERVER['HTTPS']) && strcasecmp($_SERVER['HTTPS'], 'on') === 0;
$options = array (
	'script_url' => ($https ? 'https://' : 'http://').$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'], 
	'upload_dir' => $_SERVER['DOCUMENT_ROOT'].'/images/division/'.$divcity.'/', 
	'upload_url' => ($https ? 'https://' : 'http://').$_SERVER['SERVER_NAME'].'/images/division/'.$divcity.'/', 
	'max_number_of_files' => 1, 
	'accept_file_types' => '/\.(gif|jpe?g|png)$/i', 
	'min_width' => 960,
	'min_height' => 470,
	'image_versions' => array(
		'' => array(
			'auto_orient' => true,
			'crop' => true,
			'max_width' => 960,
			'max_height' => 470
		),
		'thumbnail' => array(
			'crop' => true,
			'max_width' => 150,
			'max_height' => 150
		)
	)
);
$upload_handler = new UploadHandler($options);