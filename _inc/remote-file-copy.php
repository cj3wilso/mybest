<?php
/*
 * Remote File Copy PHP Script 1.0
 *
 * Copyright 2012, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

$upload_dir = getcwd()."/files";
$url = !empty($_REQUEST["url"]) && preg_match("|^http(s)?://.+$|", stripslashes($_REQUEST["url"])) ?
    stripslashes($_REQUEST["url"]) : null;
$callback = !empty($_REQUEST["callback"]) && preg_match("|^\w+$|", $_REQUEST["callback"]) ?
    $_REQUEST["callback"] : "callback";
$use_curl = defined("CURLOPT_PROGRESSFUNCTION");
$temp_file = tempnam(sys_get_temp_dir(), "upload-");
$fileinfo = new stdClass();
$fileinfo->name = trim(basename($url), ".\x00..\x20");

// 1KB of initial data, required by Webkit browsers:
echo "<span>".str_repeat("0", 1000)."</span>";

function event_callback ($message) {
    global $callback;
    echo "<script>parent.".$callback."(".json_encode($message).");</script>";
}

function get_file_path () {
    global $upload_dir, $temp_file;
    return $upload_dir."/".basename($temp_file);
}

function stream_notification_callback ($notification_code, $severity, $message, $message_code, $bytes_transferred, $bytes_max) {
    global $fileinfo;
    switch($notification_code) {
        case STREAM_NOTIFY_FILE_SIZE_IS:
            $fileinfo->size = $bytes_max;
            break;
        case STREAM_NOTIFY_MIME_TYPE_IS:
            $fileinfo->type = $message;
            break;
        case STREAM_NOTIFY_PROGRESS:
            if (!$bytes_transferred) {
                event_callback(array("send" => $fileinfo));
            }
            event_callback(array("progress" => array("loaded" => $bytes_transferred, "total" => $bytes_max)));
            break;
    }
}

function curl_progress_callback ($download_size, $downloaded_size, $upload_size, $uploaded_size) {
    global $fileinfo;
    if (!$downloaded_size) {
        if (!isset($fileinfo->size)) {
            $fileinfo->size = $download_size;
            event_callback(array("send" => $fileinfo));
        }
    }
    event_callback(array("progress" => array("loaded" => $downloaded_size, "total" => $download_size)));
}

if (!$url) {
    $success = false;
} else if ($use_curl) {
    $fp = fopen($temp_file, "w");
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_NOPROGRESS, false );
    curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, "curl_progress_callback");
    curl_setopt($ch, CURLOPT_FILE, $fp);
    $success = curl_exec($ch);
    $curl_info = curl_getinfo($ch);
    curl_close($ch);
    fclose($fp);
    $fileinfo->size = $curl_info["size_download"];
    $fileinfo->type = $curl_info["content_type"];
} else {
    $ctx = stream_context_create();
    stream_context_set_params($ctx, array("notification" => "stream_notification_callback"));
    $success = copy($url, $temp_file, $ctx);
}

if ($success) {
    rename($temp_file, get_file_path());
    event_callback(array("done" => $fileinfo));
} else {
    unlink($temp_file);
    $err = error_get_last();
    if (!$err) {
        $err = array("message" => "Invalid url parameter");
    }
    event_callback(array("fail" => $err["message"]));
}
