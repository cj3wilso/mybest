<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
       $url = "http://ottawa.kijiji.ca/v-1-bedroom-apartments-condos/ottawa/one-bedroom-loft-heat-water-appliances-included/600801726";
	   $agent = "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)";
	   $ch=curl_init();
       curl_setopt ($ch, CURLOPT_URL,$url );
	   //Follow the redirects for old urls.. then check if post is up
	   curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
       curl_setopt($ch, CURLOPT_USERAGENT, $agent);
       curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt ($ch,CURLOPT_VERBOSE,false);
       curl_setopt($ch, CURLOPT_TIMEOUT, 5);
       //curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, FALSE);
       //curl_setopt($ch,CURLOPT_SSLVERSION,3);
       //curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, FALSE);
       $page=curl_exec($ch);
       echo curl_error($ch);
       $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
       curl_close($ch);
	   
	   echo $page;
	   echo $httpcode;