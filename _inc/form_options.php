<?php
if ( isset ($_POST['options']) ) { 
	$prov = $_GET['prov'];
	$city = urlencode($_GET['city']);
	$address = (isset($_GET["address"])) ? urldecode ( $_GET['address'] ) : NULL;
	$beds = ($_POST['beds'] !== '') ? $_POST['beds'] : NULL;
	$bath = ($_POST['bath'] !== '') ? $_POST['bath'] : NULL;
	$price = ($_POST['price'] !== '') ? $_POST['price'] : NULL;
	$dist = ($_POST['dist'] !== '') ? $_POST['dist'] : NULL;
	
	$listarray = array('address'=>$address,'beds'=>$beds,'bath'=>$bath,'price'=>$price,'dist'=>$dist);
	$listarray = array_filter($listarray, 'strlen');
	if(!empty($_POST['inter'])) $listarray = $listarray + $_POST['inter'];
	if(!empty($_POST['appl'])) $listarray = $listarray + $_POST['appl'];
	if(!empty($_POST['trans'])) $listarray = $listarray + $_POST['trans'];
	if(!empty($_POST['tv'])) $listarray = $listarray + $_POST['tv'];
	if(!empty($_POST['health'])) $listarray = $listarray + $_POST['health'];
	if(!empty($_POST['laund'])) $listarray = $listarray + $_POST['laund'];
	if(!empty($_POST['secur'])) $listarray = $listarray + $_POST['secur'];
	if(!empty($_POST['lease'])) $listarray = $listarray + $_POST['lease'];
	if(!empty($_POST['pet'])) $listarray = $listarray + $_POST['pet'];
	if(!empty($_POST['amenet'])) $listarray = $listarray + $_POST['amenet'];
	if(!empty($_POST['senior'])) $listarray = $listarray + $_POST['senior'];
		
	//Set Map and List links
	$maparray = array('prov'=>$prov,'city'=>$city) + $listarray;
	$sub = '/'.$prov.'/'.$city;
	$mapbeg = (array_filter($maparray)) ? '?' : NULL;
	$listbeg = (array_filter($listarray)) ? '/' : NULL;
	$mapparam =  $mapbeg.strtolower(urlencode(implode("-", $maparray)));
	$listparam = $sub.$listbeg.strtolower(urlencode(implode("-", $listarray)));

	header('Location: '.$list.$listparam);
}
?>