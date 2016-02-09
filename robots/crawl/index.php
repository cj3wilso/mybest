#!/usr/bin/php
<?php
// It may take a while to crawl a site ...
set_time_limit(10000000);
// Inculde the phpcrawl-mainclass
include("libs/PHPCrawler.class.php");
$GLOBALS['xml'] = array('<?xml version="1.0" encoding="UTF-8"?>'." \n ".'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'." \n ");

// Extend the class and override the handleDocumentInfo()-method 
class MyCrawler extends PHPCrawler 
{
  function handleDocumentInfo($DocInfo) 
  {
	$bad = false;
	$exclude = array("(",".css", ".js", "document.","admin","{","_inc");
	$url = parse_url($DocInfo->url);
	foreach ( $exclude as $element ) {
		if ( strstr( $url['path'], $element ) ) {
			$bad = true;
		}
	}
	if(!isset($url['query']) && $bad == false){
		$docurl = rtrim($DocInfo->url, '/');
		$xmlitem = "<url> \n <loc>".$docurl."</loc> \n <changefreq>daily</changefreq> \n <priority>0.80</priority> \n </url> \n";
		array_push($GLOBALS['xml'], $xmlitem);
	}    
    flush();
  } 
}

// Now, create a instance of your class, define the behaviour
// of the crawler (see class-reference for more options and details)
// and start the crawling-process. 

$crawler = new MyCrawler();

// URL to crawl
$crawler->setURL("mybestapartments.ca");

// Only receive content of files with content-type "text/html"
$crawler->addContentTypeReceiveRule("#text/html#");

// Ignore links to pictures, dont even request pictures
$crawler->addURLFilterRule("#\.(jpg|jpeg|gif|png|css|js)$# i");

// Store and send cookie-data like a browser does
$crawler->enableCookieHandling(true);

// Set the traffic-limit to 1 MB (in bytes,
// for testing we dont want to "suck" the whole site)
$crawler->setTrafficLimit(19000 * 1024);

// Thats enough, now here we go
$crawler->go();

array_push($GLOBALS['xml'], "</urlset>\n");
$xml = implode("", array_unique($GLOBALS['xml']));

$file = fopen("../../sitemap.xml","w");
echo fwrite($file,$xml);
fclose($file);

?>