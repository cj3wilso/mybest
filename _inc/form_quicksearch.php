<?php
if ( isset ($_POST['submit']) ) { 
	$prov = $_GET['prov'];
	$city = urlencode($_GET['city']);
	$search = (isset($_GET["search"])) ? urldecode ( $_GET['search'] ) : NULL;
	$pBed = ($_POST['beds'] !== '') ? $_POST['beds'] : NULL;
	$pBa = ($_POST['bath'] !== '') ? $_POST['bath'] : NULL;
	$pPrice = ($_POST['price'] != '' and $_POST['price'] != '0to10000') ? $_POST['price'] : NULL;
	$pDist = ($_POST['dist'] !== '') ? $_POST['dist'] : NULL;
	$laund = ($_POST['laund'] !== '') ? $_POST['laund'] : NULL;
	$pWashfacil = ($laund == 'Laundry Facility') ? $laund : NULL;
	$pWashunit = ($laund == 'Washer and Dryer in Unit') ? $laund : NULL;
	$pWashconn = ($laund == 'Washer and Dryer Connections') ? $laund : NULL;
	$pPetsallow = ($_POST['pets'] !== '') ? $_POST['pets'] : NULL;
	
	//Set Map and List links
	$sub = '/'.$prov.'/'.$city;
	$urloptions = array_filter(array($pBed,$pBa,$pPrice,$pDist,$pWashfacil,$pWashunit,$pWashconn,$pPetsallow), 'strlen');
	$maparray = array_filter(array('prov'=>$prov,'city'=>$city,'search'=>$search,'options'=>strtolower(implode("-", $urloptions))));
	$mapbeg = (array_filter($maparray)) ? '?' : NULL;
	$listarray = array('search'=>$search,'sort'=>$_GET['sort']);
	$listbeg = (array_filter($listarray)) ? '?' : NULL;
	$mapparam =  $mapbeg.http_build_query($maparray);
	$listparam = $sub.'/'.strtolower(urlencode(implode("-", $urloptions))).$listbeg.http_build_query($listarray);
	
	if($page=="map"){
		header('Location: '.$map.$mapparam);
	}else{
		header('Location: '.$list.$listparam);
	}
}
?>