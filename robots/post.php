#!/usr/bin/php
<?php
/* http://simplehtmldom.sourceforge.net/manual.htm */
include_once("global.php");
include_once('simple_html_dom.php');
//include_once('geocode.opencage.class.php');
header('Content-Type: text/html; charset=utf-8');

/* GLOBAL VARIABLES */
// Images Function
	$dir = "$root/upload/server/php/files";
	$dir = str_replace("/_inc", "", $dir);
	
	// Array of Image Sizes
	$image_versions = array(
		'slide' => array(
			'crop' => true,
			'max_width' => 390,
			'max_height' => 390
		),
		'thumbnail' => array(
			'crop' => true,
			'max_width' => 115,
			'max_height' => 115
		),
		'mobile' => array(
			'crop' => true,
			'max_width' => 60,
			'max_height' => 60
		)
	);
	
	// Function To Resize All Images
	function resizeImage($filename,$max_height,$max_width,$prop,$basename,$img_type, $dir){	
		// Get new dimensions
		list($img_width, $img_height) = getimagesize($filename);
		if (($img_width / $img_height) >= ($max_width / $max_height)) {
			$new_width = $img_width / ($img_height / $max_height);
			$new_height = $max_height;
		} else {
			$new_width = $max_width;
			$new_height = $img_height / ($img_width / $max_width);
		}
		$dst_x = 0 - ($new_width - $max_width) / 2;
		$dst_y = 0 - ($new_height - $max_height) / 2;
		$new_img = imagecreatetruecolor($max_width, $max_height);
		//Make progressive
		imageinterlace($new_img, 1);
		$src_img = imagecreatefromjpeg($filename);
		// Resample
		imagecopyresampled($new_img,$src_img,$dst_x,$dst_y,0,0,$new_width,$new_height,$img_width,$img_height);
		// Use output buffering to capture outputted image stream
		ob_start();
		imagejpeg($new_img, NULL, 80);
		$i = ob_get_clean();
		// Save file
		$fp = fopen ("$dir/$prop/$img_type/$basename",'w');
		fwrite ($fp, $i);
		//Also add slide to base folder
		if($max_height==390){
			$fp = fopen ("$dir/$prop/$basename",'w');
			fwrite ($fp, $i);
		}
		//chmod($fp, 0777);
		fclose ($fp);
		// Free up memory
		imagedestroy($new_img);
	}
	
	// MySQL
	$user_id = 14;
	$today = date("M j Y");

// Get Ads
$html = new simple_html_dom();
$html->load_file($target_url);
if ($html->find('a.adLinkSB')){
	$properties = getAds($html->find('a.adLinkSB'),"div[class=VAStyleA]","table[class=viewadhdr]","h1[id=preview-local-title]","span[id=preview-local-desc]","li.weburl a","table[id=attributeTable] tr","td[imggal=thumb] img",$provinces_array);
}else{
	$properties = getAds($html->find('a.title'),"div[id=MainContainer]","div[class=breadcrumb]","h1","div[id=UserContent]","div#dealer-url a","table[class=ad-attributes] tr","div[id=ImageThumbnails] ul li div img",$provinces_array);
}

function getAds($findlink,$container,$adID,$propName,$desc,$url,$attributes,$images,$provinces_array){
	/* GLOBAL VARIABLES */
	// Simple HTML DOM
	global $target_url;
	$counter=0;
	$email_pattern = "/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i";
	$kijijiAds = array("This ad was posted with the Kijiji mobile app.", "This ad was posted from the Kijiji Classifieds app.You can download the app from Google Play.", "Cette annonce a été affichée avec l'app Kijiji.");
	$styleType = array("Apartment", "Condo");
	$bedNum = array("1", "one", "2", "two", "3", "three", "4");
	$feat = array("Balcony", "Locker", "Fireplace", "Hardwood", "Island Kitchen", "Renovated", "Ceiling Windows", "Dishwasher", "Microwave", "Stainless Steel", "TTC", "Bus", "Train", "Cable Ready", "Speed Internet", "Net Included", "Wireless Internet", "Net Lounge", "Pool", "Fitness", "Gym", "Park ", "Playground", "Rooftop", "Whirlpool", "Hot Tub", "Sauna", "BBQ", "Tennis", "Basketball", "Trail", "Dryer", "Laundry Facility", "Dryer Connections", "Parking Included", "Visitor Parking", "Garage", "Concierge", "Utilities Included", "Some Utilities", "Pet Park", "Recreation", "Billiards", "Party Room", "Emergency Maintenance", "Theatre", "Business Cent", "Conference", "Disability Access", "Elevator", "Green Com", "Housekeeping", "Smoke Free", "Non-Smoker", "No Smoking", "Assisted Living", "Independent Living");
	
	foreach($findlink as $link){
		if (strpos($link->href,"http://") === false) {
			$parse = parse_url($target_url);
			$host = $parse["host"];
			$link->href = "http://".$host.$link->href;
		}
		$links[] = $link->href;
		
		// Set Page ID 
		$random_id_length = 5; //set the random id length 
		$rnd_id = crypt(uniqid(rand(),1));  //generate a random id encrypt it and store it in $rnd_id		$rnd_id = strip_tags(stripslashes($rnd_id)); //to remove any slashes that might have come 
		$rnd_id = str_replace(".","",$rnd_id); //Removing any . or / and reversing the string 
		$rnd_id = strrev(str_replace("/","",$rnd_id)); 
		$pg_id[] = strtolower(substr($rnd_id,0,$random_id_length)); //finally I take the first 5 characters from the $rnd_id 
		$html = new simple_html_dom();
		$html->load_file($link->href);
		foreach($html->find($container) as $article)
		{
			/* REFRESH ARRAYS WITH CHILDREN */
			$item['email'] = $item['photo'] = $item['Interior Features'] = $item['Appliances']  = $item['Transportation'] = $item['TV &amp; Internet'] = $item['Health / Outdoor'] = $item['Laundry'] = $item['Parking / Security'] = $item['Lease Options'] = $item['Pets'] = $item['Additional Ameneties'] = $item['Senior'] = NULL;
			$rent = $bath = $furnished = $pets_allowed = $street = $city = $prov = $postal = $address = $noaddress = NULL;
			
			/* FIND ATTRIBUTES IN ATTRIBUTE TABLE */
			foreach($article->find($attributes) as $att){
				$title = $att->first_child()->plaintext;
				$value = $att->last_child()->plaintext;
				switch ($att) {
					case (preg_match("/^price/i", $title, $matches) != 0 || preg_match("/^prix/i", $title, $matches) != 0):
						$item['rent'] = $value;
						break;
					case (preg_match("/^address/i", $title, $matches) != 0 || preg_match("/^adresse/i", $title, $matches) != 0):
						if (strpos($value,'View map') !== false) {
    						$getaddress = explode('View map', $value);
						}else{
							$getaddress = explode('Afficher la carte', $value);
						}
						$address = trim($getaddress[0]);
						echo nl2br("\r\n".'address from scrape:'.$address."\r\n");
						
						//FIND This
						//You need to match certain types of formats from Kijiji and use regular expressions to break into parts
						$accents = explode(",","ç,æ,œ,á,é,í,ó,ú,à,è,ì,ò,ù,ä,ë,ï,ö,ü,ÿ,â,ê,î,ô,û,å,e,i,ø,u");
						$noaccents = explode(",","c,ae,oe,a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,y,a,e,i,o,u,a,e,i,o,u");
						//IF STREET ADDRESS
						$reg_street = "[0-9/]+[a-z]?[ ][0-9]?[a-z ]+";
						$reg_city = "[a-z /]+";
						$reg_prov_abv = "([A-Z]{2})*";
						$reg_comma = ", ";
						$reg_space = " ";
						$reg_postal = "[A-Z][0-9][A-Z]([ ]?[0-9][A-Z][0-9])?";
						//Match this address format:
						//955b Warwick Court, Oakville/Halton Region, ON, L7T 3Z6
						$match_pattern = $reg_street.$reg_comma.$reg_city.$reg_comma.$reg_prov_abv.$reg_comma.$reg_postal;
						if (preg_match("#^$match_pattern$#i", $address, $matches)){
							echo nl2br("\r\n".'i match: '.$matches[0]."\r\n");
							$address_all = explode(",", str_ireplace($accents, $noaccents, $matches[0]));
							$item['street'] = ucwords(trim($address_all[0]));
							$item['city'] = $item['region'] = ucwords(trim($address_all[1]));
							$item['prov'] = strtoupper($address_all[2]);
							$item['post'] = $address_all[3];
						}
						//Match this address format:
						//16b Yonge Street, Toronto, ON M5E 2A1
						//33 2e Avenue, Verdun, QC H4G 2W2
						$match_pattern2 = $reg_street.$reg_comma.$reg_city.$reg_comma.$reg_prov_abv.$reg_space.$reg_postal;
						if (preg_match("#^$match_pattern2$#i", $address, $matches)){
							echo nl2br("\r\n".'i match: '.$matches[0]."\r\n");
							$address_all = explode(",", str_ireplace($accents, $noaccents, $matches[0]));
							$item['street'] = ucwords(trim($address_all[0]));
							$item['city'] = $item['region'] = ucwords(trim($address_all[1]));
							$provpost = explode(' ', trim($address_all[2]), 2);
							$item['prov'] = strtoupper($provpost[0]);
							$item['post'] = $provpost[1];
						}
						//Match this address format:
						//80b Harrison Garden Boulevard, M2N 7E3, Toronto, ON
						$match_pattern3 = $reg_street.$reg_comma.$reg_postal.$reg_comma.$reg_city.$reg_comma.$reg_prov_abv;
						if (preg_match("#^$match_pattern3$#i", $address, $matches)){
							echo nl2br("\r\n".'i match: '.$matches[0]."\r\n");
							$address_all = explode(",", str_ireplace($accents, $noaccents, $matches[0]));
							$item['street'] = ucwords(trim($address_all[0]));
							$item['post'] = $address_all[1];
							$item['city'] = $item['region'] = ucwords(trim($address_all[2]));
							$item['prov'] = strtoupper($address_all[3]);
						}
						//Match this address format:
						//3980b Lesage, H4G1A4, Verdun
						$match_pattern4 = $reg_street.$reg_comma.$reg_postal.$reg_comma.$reg_city;
						if (preg_match("#^$match_pattern4$#i", $address, $matches)){
							echo nl2br("\r\n".'i match: '.$matches[0]."\r\n");
							$address_all = explode(",", str_ireplace($accents, $noaccents, $matches[0]));
							$item['street'] = ucwords(trim($address_all[0]));
							$item['post'] = $address_all[1];
							$item['city'] = $item['region'] = ucwords(trim($address_all[2]));
							$item['prov'] = "";
						}
						echo nl2br("\r\n".'address after regular expression:'."\r\n".'street:'.$item['street']."\r\n".'city:'.$item['city']."\r\n".'post:'.$item['post']."\r\n");
						break;
					case (preg_match("/^bathrooms/i", $title, $matches) != 0 || preg_match("/^salles de bains (nb)/i", $title, $matches) != 0):
						$item['ba'] = substr($value, 0, -9);
						break;
					case (preg_match("/^furnished/i", $title, $matches) != 0 || preg_match("/^meublé/i", $title, $matches) != 0):
						if(preg_match("/yes/i", $value, $matches) != 0){
							$item['Additional Ameneties'][] = "Furnished Apartments";
						}
						break;
					case (preg_match("/^pet/i", $title, $matches) != 0 || preg_match("/^animaux acceptés/i", $title, $matches) != 0):
						if(preg_match("/yes/i", $value, $matches) != 0){
							$item['Pets'][] = "Pets Allowed";
						}
						break;
				}
				
			}
			$item['lat'] = $html->find('meta[property=og:latitude]', 0)->content;
			$item['lng'] = $html->find('meta[property=og:longitude]', 0)->content;
				
			$item['rnd_id'] = $pg_id[$counter];
			/* KEEP NAME/INTRO FIRST - USED FOR CHARACTER SEARCH */
			$id = $article->find($adID, 0)->plaintext;
			$begin = strpos($id, "Ad ID ")+6;
			$item['id'] = substr($id, $begin, -1);
			$item['name'] = $article->find($propName, 0)->plaintext;
			$item['desc'] = strip_tags($article->find($desc, 0)->innertext, '<p><br><br /><br/>');
			$item['desc'] = str_replace($kijijiAds, "", $item['desc']);
			if(preg_match($email_pattern, $item['desc'], $matches) != 0){ $item['email'] = $matches[0]; }
			$item['url'] = $article->find($url, 0)->href;
			$item['where_posted'] = $links[$counter];
			
			/* GET STYLE OF UNIT */
			foreach($styleType as $key => $value){
				if(stripos($item['name'], $value) > 0 || stripos($item['desc'], $value) > 0){
					$item['style'] = $value;
				}else{
					$item['style'] = $styleType[0];
				}
			}
			/* GET BEDROOM INFO */
			foreach($bedNum as $key => $value){
				if( stripos($item['name'], $value.' bed') > 0 || stripos($item['desc'], $value.' bed') > 0 || stripos($item['name'], $value.' bdr') > 0 || stripos($item['desc'], $value.' bdr') > 0 ){
					if (is_numeric($value)) {$item['beds'] = $value;}else{$item['beds'] = $bedNum[$key-1];}
				}else if(stripos($item['name'], ' den ') > 0 || stripos($item['desc'], ' den ') > 0 || stripos($item['name'], '+den') > 0 || stripos($item['desc'], '+den') > 0 || stripos($item['name'], 'plusden') > 0 || stripos($item['desc'], 'plusden') > 0){
					$item['beds'] .= " plus Den";
				}else if(stripos($item['name'], "Studio") > 0 || stripos($item['desc'], "Studio") > 0 || stripos($item['name'], "Bachelor") > 0 || stripos($item['desc'], "Bachelor") > 0){
					$item['beds'] = "Studio";
				}
			}
			/* GET FEATURES */
			foreach($feat as $key => $value){
				if(stripos($item['name'], $value) > 0 || stripos($item['desc'], $value) > 0){
					switch ($value) {
						case ($value=='Balcony'): $item['Interior Features'][] = $value; break;
						case ($value=='Locker'): $item['Interior Features'][] = "Extra Storage"; break;
						case ($value=='Fireplace'): $item['Interior Features'][] = $value; break;
						case ($value=='Hardwood'): $item['Interior Features'][] = "Hardwood Flooring"; break;
						case ($value=='Island Kitchen'): $item['Interior Features'][] = $value; break;
						case ($value=='Renovated'): $item['Interior Features'][] = "New or Renovated Interior"; break;
						case ($value=='Ceiling Windows'): $item['Interior Features'][] = "Floor To Ceiling Windows"; break;
						case ($value=='Dishwasher'): $item['Appliances'][] = $value; break;
						case ($value=='Microwave'): $item['Appliances'][] = $value; break;
						case ($value=='Stainless Steel'): $item['Appliances'][] = "Stainless Steel Appliances"; break;
						case ($value=='TTC' || $value=='Bus' || $value=='Train'): $item['Transportation'][] = "Public Transportation"; break;
						case ($value=='Cable Ready'): $item['TV &amp; Internet'][] = $value; break;
						case ($value=='Speed Internet'): $item['TV &amp; Internet'][] = "High Speed Internet Access"; break;
						case ($value=='Net Included'): $item['TV &amp; Internet'][] = "Internet Included"; break;
						case ($value=='Wireless Internet'): $item['TV &amp; Internet'][] = "Wireless Internet Access"; break;
						case ($value=='Net Lounge'): $item['TV &amp; Internet'][] = "Internet Lounge"; break;
						case ($value=='Pool'): $item['Health / Outdoor'][] = "Swimming Pool"; break;
						case ($value=='Fitness' || $value=='Gym'): $item['Health / Outdoor'][] = "Fitness Center"; break;
						case ($value=='Park '): $item['Health / Outdoor'][] = $value; break;
						case ($value=='Playground'): $item['Health / Outdoor'][] = $value; break;
						case ($value=='Rooftop'): $item['Health / Outdoor'][] = "Rooftop Patio";  break;
						case ($value=='Whirlpool' || $value=='Hot Tub'): $item['Health / Outdoor'][] = "Whirlpool";  break;
						case ($value=='Sauna'): $item['Health / Outdoor'][] = $value;  break;
						case ($value=='BBQ'): $item['Health / Outdoor'][] = $value;  break;
						case ($value=='Tennis'): $item['Health / Outdoor'][] = "Tennis Court";  break;
						case ($value=='Basketball'): $item['Health / Outdoor'][] = "Basketball Court";  break;
						case ($value=='Trail'): $item['Health / Outdoor'][] = "Trail, Bike, Hike, Jog";  break;
						case ($value=='Dryer'): $item['Laundry'][] = "Washer and Dryer in Unit";  break;
						case ($value=='Laundry Facility'): $item['Laundry'][] = $value;  break;
						case ($value=='Dryer Connections'): $item['Laundry'][] = "Washer and Dryer Connections";  break;
						case ($value=='Parking Included'): $item['Parking / Security'][] = "Free Parking";  break;
						case ($value=='Visitor Parking'): $item['Parking / Security'][] = $value;  break;
						case ($value=='Garage'): $item['Parking / Security'][] = $value;  break;
						case ($value=='Concierge'): $item['Parking / Security'][] = "Full Concierge Service";  break;
						case ($value=='Utilities Included'): $item['Lease Options'][] = "All Paid Utilities";  break;
						case ($value=='Some Utilities'): $item['Lease Options'][] = "Some Paid Utilities";  break;
						case ($value=='Pet Park'): $item['Pet'][] = $value;  break;
						case ($value=='Recreation'): $item['Additional Ameneties'][] = "Recreation Room";  break;
						case ($value=='Emergency Maintenance'): $item['Additional Ameneties'][] = $value;  break;
						case ($value=='Theatre'): $item['Additional Ameneties'][] = $value;  break;
						case ($value=='Business Cent'): $item['Additional Ameneties'][] = "Business Center";  break;
						case ($value=='Conference'): $item['Additional Ameneties'][] = "Conference Room";  break;
						case ($value=='Disability Access'): $item['Additional Ameneties'][] = $value;  break;
						case ($value=='Elevator'): $item['Additional Ameneties'][] = $value;  break;
						case ($value=='Green Com'): $item['Additional Ameneties'][] = "Green Community";  break;
						case ($value=='Housekeeping'): $item['Additional Ameneties'][] = "Housekeeping Available";  break;
						case ($value=='Smoke Free' || $value=='Non-Smoker' || $value=='No Smoking'): $item['Additional Ameneties'][] = "Smoke Free";  break;
						case ($value=='Assisted Living'): $item['Senior'][] = $value;  break;
						case ($value=='Independent Living'): $item['Senior'][] = $value;  break;
					}
				}
			}
			/* GET IMAGES */
			//$image_item['rnd_id'] = $pg_id[$counter];
			foreach($article->find($images) as $img){
				$item['photo'][] = str_replace("14.JPG", "20.JPG", $img->src);
			}
			/* DEFINE ARRAYS */
			$properties[] = array_filter($item);
			$counter++;
		}
	}
	return $properties;
}
//print_r($properties);
include 'mysqlconnect.php';
foreach($properties as $k => $prop) {
	/* VARIABLES */
	$hasError = false;
	$propid = $properties[$k]['rnd_id'];
	$name = mysql_real_escape_string($properties[$k]['name']);
	$posted_id = $properties[$k]['id'];
	$beds = $properties[$k]['beds'];
	$rent = (int)str_replace('$', '', str_replace(' ', '', str_replace(',', '', $properties[$k]['rent'])));
	/* RULES: CANNOT ENTER DUPLICATE, NEEDS PHOTOS, NEEDS BEDS, NEEDS RENT PRICE, NEEDS POSTAL CODE */
	$sql="SELECT posted_id FROM properties WHERE posted_id='$posted_id'";
	$check = mysql_query($sql) or die('Query failed: ' . mysql_error() . "<br />\n$sql");  
 	$alreadyexist = mysql_num_rows($check);
	//!is_array($properties[$k]['photo'])
	//Taking out is array and just checking for a value for photos
	if ($alreadyexist != 0 || $beds == NULL || $rent == 0 || $properties[$k]['post'] == NULL || $properties[$k]['city'] == NULL || $properties[$k]['prov'] == NULL || $properties[$k]['photo'] == NULL) {
 		echo "Error Details: alreadyexist=".$alreadyexist." beds=".$beds." rent=".$rent." postalcode=".$properties[$k]['post']." city=".$properties[$k]['city']." photos=".print_r($properties[$k]['photo'])." photos are array = ".is_array($properties[$k]['photo'])."<br><br>";
		echo "Full property stuff: ".print_r($properties[$k]);
		$hasError = true;
		continue;
 	}
	/* START ADDING RECORDS */
	if($hasError == false){
		/* PROPERTIES */
		$insert_propinfo = "INSERT INTO properties (id_user, id_pg, name, address, address2, city, region, prov, post, phone1, phone2, phone3, email, url, lat, lng, date, where_posted, posted_id, created) VALUES ($user_id, '".$properties[$k]['rnd_id']."','".$name."','".$properties[$k]['street']."','','".$properties[$k]['city']."','".$properties[$k]['region']."','".$properties[$k]['prov']."','".$properties[$k]['post']."','','','','".$properties[$k]['email']."','".$properties[$k]['url']."', ".$properties[$k]['lat'].", ".$properties[$k]['lng'].", '$today','".$properties[$k]['where_posted']."','$posted_id','".date("c")."') ;";
		mysql_query($insert_propinfo);
		/* UNIT DETAILS */
		$insert_unit = "INSERT INTO prop_units (id_prop,u_order,rent,style,beds,ba,sq_ft,dep) VALUES ('".$properties[$k]['rnd_id']."','1','".$rent."','".$properties[$k]['style']."','".$beds."','".$properties[$k]['ba']."','','')";
		mysql_query($insert_unit);
		/* PROPERTY DESCRIPTION */
		$insert_propdesc = "INSERT INTO prop_intro (id_prop, text) VALUES ('".$properties[$k]['rnd_id']."','".mysql_real_escape_string($properties[$k]['desc'])."')";
		mysql_query($insert_propdesc);
		if (!empty($prop)){foreach($prop as $k2 => $items) {
			if ($k2 == 'photo'){
				$count = 1;
				$comma = '';
				$sql_images = '';
				echo 'mkdir is:'."$dir/$propid \n";
				mkdir("$dir/$propid", 0777);
				if (is_array($items)){foreach($items as $k3 => $item) {
					echo $item;
					$ext = pathinfo($item, PATHINFO_EXTENSION);
					$basename = "pic-" . uniqid() . '.'.$ext;
					$sql_images .= $comma."('".$propid."','".$count."','".$basename."')";
					$comma = ",";
					$count++;
					/* SAVE FIRST IMAGE IN MAIN DIRECTORY */
					file_put_contents("$dir/$propid/$basename",$item);
					foreach ($image_versions as $k => $v) {
						$img_type = $k;
						$path_imgtype = "$dir/$propid/$img_type";
						//if (!file_exists($path_imgtype)) {mkdir("$path_imgtype", 0777);}
						mkdir($path_imgtype, 0777);
						resizeImage($item,$image_versions[$k]['max_height'],$image_versions[$k]['max_width'],$propid, $basename, $img_type, $dir);
					}
				}}
				/* IMAGES */
				$insert_images = "INSERT INTO prop_photos (id_prop, p_order, photo) VALUES " . $sql_images;
				echo 'sql_images: '.$sql_images.'<br><br>';
				mysql_query($insert_images);
			}else if ($k2 == 'Senior' || $k2 == 'Additional Ameneties' || $k2 == 'Pets' || $k2 == 'Lease Options' || $k2 == 'Parking / Security' || $k2 == 'Laundry' || $k2 == 'Health / Outdoor' || $k2 == 'TV &amp; Internet' || $k2 == 'Transportation' || $k2 == 'Appliances' || $k2 == 'Interior Features'){
				$comma = '';
				$feat = '';
				if (is_array($items)){foreach($items as $k3 => $item) {
					$feat .= $comma."('".$propid."','".$propid.$k2.$item."','".$k2."','".$item."')";
					$comma = ",";
				}}
				/* FEATURES */
				$insert_feat = "INSERT INTO prop_feat (id_prop,feat_uniq,type,feat) VALUES " . $feat;
				mysql_query($insert_feat);
			}
		}}
	}
	echo 'Added: '.$insert_propinfo.$insert_unit.$insert_propdesc.$insert_images.$insert_feat.'<br><br>';
}
include 'mysqlclose.php';
?>