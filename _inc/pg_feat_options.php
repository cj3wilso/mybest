<?php
if($page != "edit"){
	$feat = array();
}
if (!isset($_GET['options'])){
	$search_feat = array();
}
?>
<div class="row">
  <div class="col-lg-3">
    <h4>Interior Features</h4>
    <div>
      <input type="hidden" name="not_inter[1]" value="Air Conditioning" <?php if( in_array("Air Conditioning", $feat) || isset($search_feat['cond']) ) echo  "checked"; ?>>
      <input name="inter[1]" type="checkbox" value="Air Conditioning" <?php if( (in_array("Air Conditioning", $feat)) || isset($search_feat['cond']) ) echo  "checked"; ?>>
      Air Conditioning</div>
    <div for="2q">
   	  <input type="hidden" name="not_inter[2]" value="Balcony" <?php if( in_array("Balcony", $feat) || isset($search_feat['balcony']) ) echo  "checked"; ?>>
      <input name="inter[2]" type="checkbox" value="Balcony" <?php if( in_array("Balcony", $feat) || isset($search_feat['balcony']) ) echo  "checked"; ?>>
      Balcony</div>
    <div>
      <input type="hidden" name="not_inter[3]" value="Ceiling Fan(s)" <?php if( in_array("Ceiling Fan(s)", $feat) || isset($search_feat['ceiling']) ) echo  "checked"; ?>>
      <input name="inter[3]" type="checkbox" value="Ceiling Fan(s)" <?php if( in_array("Ceiling Fan(s)", $feat) || isset($search_feat['ceiling']) ) echo  "checked"; ?>>
      Ceiling Fan(s)</div>
    <div>
      <input type="hidden" name="not_inter[4]" value="Extra Storage" <?php if( in_array("Extra Storage", $feat) || isset($search_feat['storage']) ) echo  "checked"; ?>>
      <input name="inter[4]" type="checkbox" value="Extra Storage" <?php if( in_array("Extra Storage", $feat) || isset($search_feat['storage']) ) echo  "checked"; ?>>
      Extra Storage</div>
    <div>
      <input type="hidden" name="not_inter[5]" value="Fireplace" <?php if( in_array("Fireplace", $feat) || isset($search_feat['fireplace']) ) echo  "checked"; ?>>
      <input name="inter[5]" type="checkbox" value="Fireplace" <?php if( in_array("Fireplace", $feat) || isset($search_feat['fireplace']) ) echo  "checked"; ?>>
      Fireplace</div>
    <div>
      <input type="hidden" name="not_inter[6]" value="Garden Tub" <?php if( in_array("Garden Tub", $feat) || isset($search_feat['tub']) ) echo  "checked"; ?>>
      <input name="inter[6]" type="checkbox" value="Garden Tub" <?php if( in_array("Garden Tub", $feat) || isset($search_feat['tub']) ) echo  "checked"; ?>>
      Garden Tub</div>
    <div>
      <input type="hidden" name="not_inter[7]" value="Hardwood Flooring" <?php if( in_array("Hardwood Flooring", $feat) || isset($search_feat['hardwood']) ) echo  "checked"; ?>>
      <input name="inter[7]" type="checkbox" value="Hardwood Flooring" <?php if( in_array("Hardwood Flooring", $feat) || isset($search_feat['hardwood']) ) echo  "checked"; ?>>
      Hardwood Flooring</div>
    <div>
      <input type="hidden" name="not_inter[8]" value="Island Kitchen" <?php if( in_array("Island Kitchen", $feat) || isset($search_feat['island']) ) echo  "checked"; ?>>
      <input name="inter[8]" type="checkbox" value="Island Kitchen" <?php if( in_array("Island Kitchen", $feat) || isset($search_feat['island']) ) echo  "checked"; ?>>
      Island Kitchen</div>
    <div>
      <input type="hidden" name="not_inter[9]" value="New or Renovated Interior" <?php if( in_array("New or Renovated Interior", $feat) || isset($search_feat['new']) ) echo  "checked"; ?>>
      <input name="inter[9]" type="checkbox" value="New or Renovated Interior" <?php if( in_array("New or Renovated Interior", $feat) || isset($search_feat['new']) ) echo  "checked"; ?>>
      New/Renovated Interior</div>
    <div>
      <input type="hidden" name="not_inter[10]" value="Oversized Closets" <?php if( in_array("Oversized Closets", $feat) || isset($search_feat['closet']) ) echo  "checked"; ?>>
      <input name="inter[10]" type="checkbox" value="Oversized Closets" <?php if( in_array("Oversized Closets", $feat) || isset($search_feat['closet']) ) echo  "checked"; ?>>
      Oversized Closets</div>
    <div>
      <input type="hidden" name="not_inter[11]" value="View" <?php if( in_array("View", $feat) || isset($search_feat['view']) ) echo  "checked"; ?>>
      <input name="inter[11]" type="checkbox" value="View" <?php if( in_array("View", $feat) || isset($search_feat['view']) ) echo  "checked"; ?>>
      View</div>
    <div>
      <input type="hidden" name="not_inter[12]" value="Floor To Ceiling Windows" <?php if( in_array("Floor To Ceiling Windows", $feat) || isset($search_feat['windows']) ) echo  "checked"; ?>>
      <input name="inter[12]" type="checkbox" value="Floor To Ceiling Windows" <?php if( in_array("Floor To Ceiling Windows", $feat) || isset($search_feat['windows']) ) echo  "checked"; ?>>
      Floor To Ceiling Windows</div>
    <h4>Appliances</h4>
    <div>
      <input type="hidden" name="not_appl[1]" value="Dishwasher" <?php if( in_array("Dishwasher", $feat) || isset($search_feat['dish']) ) echo  "checked"; ?>>
      <input name="appl[1]" value="Dishwasher" type="checkbox" <?php if( in_array("Dishwasher", $feat) || isset($search_feat['dish']) ) echo  "checked"; ?>>
      Dishwasher</div>
    <div>
      <input type="hidden" name="not_appl[2]" value="Gas Range" <?php if( in_array("Gas Range", $feat) || isset($search_feat['gasrange']) ) echo  "checked"; ?>>
      <input name="appl[2]" value="Gas Range" type="checkbox" <?php if( in_array("Gas Range", $feat) || isset($search_feat['gasrange']) ) echo  "checked"; ?>>
      Gas Range</div>
    <div>
      <input type="hidden" name="not_appl[3]" value="Microwave" <?php if( in_array("Microwave", $feat) || isset($search_feat['microwave']) ) echo  "checked"; ?>>
      <input name="appl[3]" value="Microwave" type="checkbox" <?php if( in_array("Microwave", $feat) || isset($search_feat['microwave']) ) echo  "checked"; ?>>
      Microwave</div>
    <div>
      <input type="hidden" name="not_appl[4]" value="Stainless Steel Appliances" <?php if( in_array("Stainless Steel Appliances", $feat) || isset($search_feat['stainless']) ) echo  "checked"; ?>>
      <input name="appl[4]" value="Stainless Steel Appliances" type="checkbox" <?php if( in_array("Stainless Steel Appliances", $feat) || isset($search_feat['stainless']) ) echo  "checked"; ?>>
      Stainless Steel Appliances</div>
  </div>
  <div class="col-lg-3">
    <h4>Transportation</h4>
    <div>
      <input type="hidden" name="not_trans[1]" value="Campus Shuttle" <?php if( in_array("Campus Shuttle", $feat) || isset($search_feat['campus']) ) echo  "checked"; ?>>
      <input name="trans[1]" value="Campus Shuttle" type="checkbox" <?php if( in_array("Campus Shuttle", $feat) || isset($search_feat['campus']) ) echo  "checked"; ?>>
      Campus Shuttle</div>
    <div>
      <input type="hidden" name="not_trans[2]" value="Public Transportation" <?php if( in_array("Public Transportation", $feat) || isset($search_feat['pubtran']) ) echo  "checked"; ?>>
      <input name="trans[2]" value="Public Transportation" type="checkbox" <?php if( in_array("Public Transportation", $feat) || isset($search_feat['pubtran']) ) echo  "checked"; ?>>
      Public Transportation</div>
    <div>
      <input type="hidden" name="not_trans[3]" value="University Shuttle Service" <?php if( in_array("Public Transportation", $feat) || isset($search_feat['pubtran']) ) echo  "checked"; ?>>
      <input name="trans[3]" value="University Shuttle Service" type="checkbox" <?php if( in_array("Public Transportation", $feat) || isset($search_feat['pubtran']) ) echo  "checked"; ?>>
      University Shuttle Service</div>
    <h4>TV &amp; Internet</h4>
    <div>
      <input type="hidden" name="not_tv[1]" value="Cable Ready" <?php if( in_array("Cable Ready", $feat) || isset($search_feat['cableready']) ) echo  "checked"; ?>>
      <input name="tv[1]" type="checkbox" value="Cable Ready" <?php if( in_array("Cable Ready", $feat) || isset($search_feat['cableready']) ) echo  "checked"; ?>>
      Cable Ready</div>
    <div>
      <input type="hidden" name="not_tv[2]" value="High Speed Internet Access" <?php if( in_array("High Speed Internet Access", $feat) || isset($search_feat['hispeed']) ) echo  "checked"; ?>>
      <input name="tv[2]" type="checkbox" value="High Speed Internet Access" <?php if( in_array("High Speed Internet Access", $feat) || isset($search_feat['hispeed']) ) echo  "checked"; ?>>
      High Speed Internet Access</div>
    <div>
      <input type="hidden" name="not_tv[3]" value="Internet Included" <?php if( in_array("Internet Included", $feat) || isset($search_feat['netincluded']) ) echo  "checked"; ?>>
      <input name="tv[3]" type="checkbox" value="Internet Included" <?php if( in_array("Internet Included", $feat) || isset($search_feat['netincluded']) ) echo  "checked"; ?>>
      Internet Included</div>
    <div>
      <input type="hidden" name="not_tv[4]" value="Wireless Internet Access" <?php if( in_array("Wireless Internet Access", $feat) || isset($search_feat['wireless']) ) echo  "checked"; ?>>
      <input name="tv[4]" type="checkbox" value="Wireless Internet Access" <?php if( in_array("Wireless Internet Access", $feat) || isset($search_feat['wireless']) ) echo  "checked"; ?>>
      Wireless Internet Access</div>
    <div>
      <input type="hidden" name="not_tv[5]" value="Internet Lounge" <?php if( in_array("Internet Lounge", $feat) || isset($search_feat['intlounge']) ) echo  "checked"; ?>>
      <input name="tv[5]" type="checkbox" value="Internet Lounge" <?php if( in_array("Internet Lounge", $feat) || isset($search_feat['intlounge']) ) echo  "checked"; ?>>
      Internet Lounge</div>
    <h4>Health / Outdoor</h4>
    <div>
      <input type="hidden" name="not_health[1]" value="Swimming Pool" <?php if( in_array("Swimming Pool", $feat) || isset($search_feat['pool']) ) echo  "checked"; ?>>
      <input name="health[1]" type="checkbox" value="Swimming Pool" <?php if( in_array("Swimming Pool", $feat) || isset($search_feat['pool']) ) echo  "checked"; ?>>
      Swimming Pool</div>
    <div>
      <input type="hidden" name="not_health[2]" value="Fitness Center" <?php if( in_array("Fitness Center", $feat) || isset($search_feat['fitness']) ) echo  "checked"; ?>>
      <input name="health[2]" type="checkbox" value="Fitness Center" <?php if( in_array("Fitness Center", $feat) || isset($search_feat['fitness']) ) echo  "checked"; ?>>
      Fitness Center</div>
    <div>
      <input type="hidden" name="not_health[3]" value="Park" <?php if( in_array("Park", $feat) || isset($search_feat['park']) ) echo  "checked"; ?>>
      <input name="health[3]" type="checkbox" value="Park" <?php if( in_array("Park", $feat) || isset($search_feat['park']) ) echo  "checked"; ?>>
      Park</div>
    <div>
      <input type="hidden" name="not_health[4]" value="Playground" <?php if( in_array("Playground", $feat) || isset($search_feat['playground']) ) echo  "checked"; ?>>
      <input name="health[4]" type="checkbox" value="Playground" <?php if( in_array("Playground", $feat) || isset($search_feat['playground']) ) echo  "checked"; ?>>
      Playground</div>
    <div>
      <input type="hidden" name="not_health[5]" value="Rooftop Patio" <?php if( in_array("Rooftop Patio", $feat) || isset($search_feat['rooftop']) ) echo  "checked"; ?>>
      <input name="health[5]" type="checkbox" value="Rooftop Patio" <?php if( in_array("Rooftop Patio", $feat) || isset($search_feat['rooftop']) ) echo  "checked"; ?>>
      Rooftop Patio</div>
    <div>
      <input type="hidden" name="not_health[6]" value="Whirlpool" <?php if( in_array("Whirlpool", $feat) || isset($search_feat['whirlpool']) ) echo  "checked"; ?>>
      <input name="health[6]" type="checkbox" value="Whirlpool" <?php if( in_array("Whirlpool", $feat) || isset($search_feat['whirlpool']) ) echo  "checked"; ?>>
      Whirlpool</div>
    <div>
      <input type="hidden" name="not_health[7]" value="Sauna" <?php if( in_array("Sauna", $feat) || isset($search_feat['sauna']) ) echo  "checked"; ?>>
      <input name="health[7]" type="checkbox" value="Sauna" <?php if( in_array("Sauna", $feat) || isset($search_feat['sauna']) ) echo  "checked"; ?>>
      Sauna</div>
    <div>
      <input type="hidden" name="not_health[8]" value="BBQ" <?php if( in_array("BBQ", $feat) || isset($search_feat['bbq']) ) echo  "checked"; ?>>
      <input name="health[8]" type="checkbox" value="BBQ" <?php if( in_array("BBQ", $feat) || isset($search_feat['bbq']) ) echo  "checked"; ?>>
      BBQ</div>
    <div>
      <input type="hidden" name="not_health[9]" value="Tennis Court" <?php if( in_array("Tennis Court", $feat) || isset($search_feat['tennis']) ) echo  "checked"; ?>>
      <input name="health[9]" type="checkbox" value="Tennis Court" <?php if( in_array("Tennis Court", $feat) || isset($search_feat['tennis']) ) echo  "checked"; ?>>
      Tennis Court(s)</div>
    <div>
      <input type="hidden" name="not_health[10]" value="Basketball Court" <?php if( in_array("Basketball Court", $feat) || isset($search_feat['basket']) ) echo  "checked"; ?>>
      <input name="health[10]" type="checkbox" value="Basketball Court" <?php if( in_array("Basketball Court", $feat) || isset($search_feat['basket']) ) echo  "checked"; ?>>
      Basketball Court</div>
    <div>
      <input type="hidden" name="not_health[11]" value="Trail, Bike, Hike, Jog" <?php if( in_array("Trail, Bike, Hike, Jog", $feat) || isset($search_feat['trail']) ) echo  "checked"; ?>>
      <input name="health[11]" type="checkbox" value="Trail, Bike, Hike, Jog" <?php if( in_array("Trail, Bike, Hike, Jog", $feat) || isset($search_feat['trail']) ) echo  "checked"; ?>>
      Trail, Bike, Hike, Jog</div>
  </div>
  <div class="col-lg-3">
    <h4>Laundry</h4>
    <div>
      <input type="hidden" name="not_laund[1]" value="Laundry Facility" <?php if( in_array("Laundry Facility", $feat) || isset($search_feat['washfacil']) ) echo  "checked"; ?>>
      <input name="laund[1]" type="checkbox" value="Laundry Facility" <?php if( in_array("Laundry Facility", $feat) || isset($search_feat['washfacil']) ) echo  "checked"; ?>>
      Laundry Facility</div>
    <div>
      <input type="hidden" name="not_laund[2]" value="Washer and Dryer in Unit" <?php if( in_array("Washer and Dryer in Unit", $feat) || isset($search_feat['washunit']) ) echo  "checked"; ?>>
      <input name="laund[2]" type="checkbox" value="Washer and Dryer in Unit" <?php if( in_array("Washer and Dryer in Unit", $feat) || isset($search_feat['washunit']) ) echo  "checked"; ?>>
      Washer and Dryer in Unit</div>
    <div>
      <input type="hidden" name="not_laund[3]" value="Washer and Dryer Connections" <?php if( in_array("Washer and Dryer Connections", $feat) || isset($search_feat['washconn']) ) echo  "checked"; ?>>
      <input name="laund[3]" type="checkbox" value="Washer and Dryer Connections" <?php if( in_array("Washer and Dryer Connections", $feat) || isset($search_feat['washconn']) ) echo  "checked"; ?>>
      Washer and Dryer Connections</div>
    <h4>Parking / Security</h4>
    <div>
      <input type="hidden" name="not_secur[1]" value="Free Parking" <?php if( in_array("Free Parking", $feat) || isset($search_feat['freepark']) ) echo  "checked"; ?>>
      <input name="secur[1]" type="checkbox" value="Free Parking" <?php if( in_array("Free Parking", $feat) || isset($search_feat['freepark']) ) echo  "checked"; ?>>
      Free Parking</div>
    <div>
      <input type="hidden" name="not_secur[2]" value="Visitor Parking" <?php if( in_array("Visitor Parking", $feat) || isset($search_feat['visitpark']) ) echo  "checked"; ?>>
      <input name="secur[2]" type="checkbox" value="Visitor Parking" <?php if( in_array("Visitor Parking", $feat) || isset($search_feat['visitpark']) ) echo  "checked"; ?>>
      Visitor Parking</div>
    <div>
      <input type="hidden" name="not_secur[3]" value="Covered Parking" <?php if( in_array("Covered Parking", $feat) || isset($search_feat['covered']) ) echo  "checked"; ?>>
      <input name="secur[3]" type="checkbox" value="Covered Parking" <?php if( in_array("Covered Parking", $feat) || isset($search_feat['covered']) ) echo  "checked"; ?>>
      Covered Parking</div>
    <div>
      <input type="hidden" name="not_secur[4]" value="Garage" <?php if( in_array("Garage", $feat) || isset($search_feat['garage']) ) echo  "checked"; ?>>
      <input name="secur[4]" type="checkbox" value="Garage" <?php if( in_array("Garage", $feat) || isset($search_feat['garage']) ) echo  "checked"; ?>>
      Garage</div>
    <div>
      <input type="hidden" name="not_secur[5]" value="Full Concierge Service" <?php if( in_array("Full Concierge Service", $feat) || isset($search_feat['concierge']) ) echo  "checked"; ?>>
      <input name="secur[5]" type="checkbox" value="Full Concierge Service" <?php if( in_array("Full Concierge Service", $feat) || isset($search_feat['concierge']) ) echo  "checked"; ?>>
      Full Concierge Service</div>
    <div>
      <input type="hidden" name="not_secur[6]" value="Alarm" <?php if( in_array("Alarm", $feat) || isset($search_feat['alarm']) ) echo  "checked"; ?>>
      <input name="secur[6]" type="checkbox" value="Alarm" <?php if( in_array("Alarm", $feat) || isset($search_feat['alarm']) ) echo  "checked"; ?>>
      Alarm</div>
    <h4>Lease Options</h4>
    <div>
      <input type="hidden" name="not_lease[1]" value="Accepts Credit Cards" <?php if( in_array("Accepts Credit Cards", $feat) || isset($search_feat['acceptscredit']) ) echo  "checked"; ?>>
      <input name="lease[1]" type="checkbox" value="Accepts Credit Cards" <?php if( in_array("Accepts Credit Cards", $feat) || isset($search_feat['acceptscredit']) ) echo  "checked"; ?>>
      Accepts Credit Cards</div>
    <div>
      <input type="hidden" name="not_lease[2]" value="Accepts Electronic Payments" <?php if( in_array("Accepts Electronic Payments", $feat) || isset($search_feat['acceptselectron']) ) echo  "checked"; ?>>
      <input name="lease[2]" type="checkbox" value="Accepts Electronic Payments" <?php if( in_array("Accepts Electronic Payments", $feat) || isset($search_feat['acceptselectron']) ) echo  "checked"; ?>>
      Accepts Electronic Payments</div>
    <div>
      <input type="hidden" name="not_lease[3]" value="All Paid Utilities" <?php if( in_array("All Paid Utilities", $feat) || isset($search_feat['paidutil']) ) echo  "checked"; ?>>
      <input name="lease[3]" type="checkbox" value="All Paid Utilities" <?php if( in_array("All Paid Utilities", $feat) || isset($search_feat['paidutil']) ) echo  "checked"; ?>>
      All Paid Utilities</div>
    <div>
      <input type="hidden" name="not_lease[4]" value="Corporate Billing Available" <?php if( in_array("Corporate Billing Available", $feat) || isset($search_feat['corpbill']) ) echo  "checked"; ?>>
      <input name="lease[4]" type="checkbox" value="Corporate Billing Available" <?php if( in_array("Corporate Billing Available", $feat) || isset($search_feat['corpbill']) ) echo  "checked"; ?>>
      Corporate Billing Available</div>
    <div>
      <input type="hidden" name="not_lease[5]" value="Individual Leases" <?php if( in_array("Individual Leases", $feat) || isset($search_feat['indivlease']) ) echo  "checked"; ?>>
      <input name="lease[5]" type="checkbox" value="Individual Leases" <?php if( in_array("Individual Leases", $feat) || isset($search_feat['indivlease']) ) echo  "checked"; ?>>
      Individual Leases</div>
    <div>
      <input type="hidden" name="not_lease[6]" value="Short Term Available" <?php if( in_array("Short Term Available", $feat) || isset($search_feat['shortterm']) ) echo  "checked"; ?>>
      <input name="lease[6]" type="checkbox" value="Short Term Available" <?php if( in_array("Short Term Available", $feat) || isset($search_feat['shortterm']) ) echo  "checked"; ?>>
      Short Term Available</div>
    <div>
      <input type="hidden" name="not_lease[7]" value="Some Paid Utilities" <?php if( in_array("Some Paid Utilities", $feat) || isset($search_feat['someutil']) ) echo  "checked"; ?>>
      <input name="lease[7]" type="checkbox" value="Some Paid Utilities" <?php if( in_array("Some Paid Utilities", $feat) || isset($search_feat['someutil']) ) echo  "checked"; ?>>
      Some Paid Utilities</div>
    <div>
      <input type="hidden" name="not_lease[8]" value="Sublets Allowed" <?php if( in_array("Sublets Allowed", $feat) || isset($search_feat['sublet']) ) echo  "checked"; ?>>
      <input name="lease[8]" type="checkbox" value="Sublets Allowed" <?php if( in_array("Sublets Allowed", $feat) || isset($search_feat['sublet']) ) echo  "checked"; ?>>
      Sublets Allowed</div>
    <div>
      <input type="hidden" name="not_lease[9]" value="Subsidies" <?php if( in_array("Subsidies", $feat) || isset($search_feat['subsidies']) ) echo  "checked"; ?>>
      <input name="lease[9]" type="checkbox" value="Subsidies" <?php if( in_array("Subsidies", $feat) || isset($search_feat['subsidies']) ) echo  "checked"; ?>>
      Subsidies</div>
  </div>
  <div class="col-lg-3">
    <h4>Pets</h4>
    <div>
      <input type="hidden" name="not_pet[1]" value="Pets Allowed" <?php if( in_array("Pets Allowed", $feat) || isset($search_feat['petsallow']) ) echo  "checked"; ?>>
      <input name="pet[1]" type="checkbox" value="Pets Allowed" <?php if( in_array("Pets Allowed", $feat) || isset($search_feat['petsallow']) ) echo  "checked"; ?>>
      Pets Allowed</div>
    <div>
      <input type="hidden" name="not_pet[2]" value="Pet Park" <?php if( in_array("Pet Park", $feat) || isset($search_feat['petpark']) ) echo  "checked"; ?>>
      <input name="pet[2]" type="checkbox" value="Pet Park" <?php if( in_array("Pet Park", $feat) || isset($search_feat['petpark']) ) echo  "checked"; ?>>
      Pet Park</div>
    <h4>Additional Ameneties</h4>
    <div>
      <input type="hidden" name="not_amenet[1]" value="Recreation Room" <?php if( in_array("Recreation Room", $feat) || isset($search_feat['recroom']) ) echo  "checked"; ?>>
      <input name="amenet[]" type="checkbox" value="Recreation Room" <?php if( in_array("Recreation Room", $feat) || isset($search_feat['recroom']) ) echo  "checked"; ?>>
      Recreation Room</div>
    <div>
      <input type="hidden" name="not_amenet[2]" value="Emergency Maintenance" <?php if( in_array("Emergency Maintenance", $feat) || isset($search_feat['emergmain']) ) echo  "checked"; ?>>
      <input name="amenet[2]" type="checkbox" value="Emergency Maintenance" <?php if( in_array("Emergency Maintenance", $feat) || isset($search_feat['emergmain']) ) echo  "checked"; ?>>
      Emergency Maintenance</div>
    <div>
      <input type="hidden" name="not_amenet[3]" value="Theatre" <?php if( in_array("Theatre", $feat) || isset($search_feat['theatre']) ) echo  "checked"; ?>>
      <input name="amenet[3]" type="checkbox" value="Theatre" <?php if( in_array("Theatre", $feat) || isset($search_feat['theatre']) ) echo  "checked"; ?>>
      Theatre</div>
    <div>
      <input type="hidden" name="not_amenet[4]" value="Furnished Apartments" <?php if( in_array("Furnished Apartments", $feat) || isset($search_feat['furnish']) ) echo  "checked"; ?>>
      <input name="amenet[4]" type="checkbox" value="Furnished Apartments" <?php if( in_array("Furnished Apartments", $feat) || isset($search_feat['furnish']) ) echo  "checked"; ?>>
      Furnished Apartments</div>
    <div>
      <input type="hidden" name="not_amenet[5]" value="Business Center" <?php if( in_array("Business Center", $feat) || isset($search_feat['buscent']) ) echo  "checked"; ?>>
      <input name="amenet[5]" type="checkbox" value="Business Center" <?php if( in_array("Business Center", $feat) || isset($search_feat['buscent']) ) echo  "checked"; ?>>
      Business Center</div>
    <div>
      <input type="hidden" name="not_amenet[6]" value="Conference Room" <?php if( in_array("Conference Room", $feat) || isset($search_feat['confroom']) ) echo  "checked"; ?>>
      <input name="amenet[6]" type="checkbox" value="Conference Room" <?php if( in_array("Conference Room", $feat) || isset($search_feat['confroom']) ) echo  "checked"; ?>>
      Conference Room</div>
    <div>
      <input type="hidden" name="not_amenet[7]" value="Disability Access" <?php if( in_array("Disability Access", $feat) || isset($search_feat['disabil']) ) echo  "checked"; ?>>
      <input name="amenet[7]" type="checkbox" value="Disability Access" <?php if( in_array("Disability Access", $feat) || isset($search_feat['disabil']) ) echo  "checked"; ?>>
      Disability Access</div>
    <div>
      <input type="hidden" name="not_amenet[8]" value="Elevator" <?php if( in_array("Elevator", $feat) || isset($search_feat['elevat']) ) echo  "checked"; ?>>
      <input name="amenet[8]" type="checkbox" value="Elevator" <?php if( in_array("Elevator", $feat) || isset($search_feat['elevat']) ) echo  "checked"; ?>>
      Elevator</div>
    <div>
      <input type="hidden" name="not_amenet[9]" value="Green Community" <?php if( in_array("Green Community", $feat) || isset($search_feat['green']) ) echo  "checked"; ?>>
      <input name="amenet[9]" type="checkbox" value="Green Community" <?php if( in_array("Green Community", $feat) || isset($search_feat['green']) ) echo  "checked"; ?>>
      Green Community</div>
    <div>
      <input type="hidden" name="not_amenet[10]" value="Housekeeping Available" <?php if( in_array("Housekeeping Available", $feat) || isset($search_feat['housekeep']) ) echo  "checked"; ?>>
      <input name="amenet[10]" type="checkbox" value="Housekeeping Available" <?php if( in_array("Housekeeping Available", $feat) || isset($search_feat['housekeep']) ) echo  "checked"; ?>>
      Housekeeping Available</div>
    <div>
      <input type="hidden" name="not_amenet[11]" value="Smoke Free" <?php if( in_array("Smoke Free", $feat) || isset($search_feat['smokefree']) ) echo  "checked"; ?>>
      <input name="amenet[11]" type="checkbox" value="Smoke Free" <?php if( in_array("Smoke Free", $feat) || isset($search_feat['smokefree']) ) echo  "checked"; ?>>
      Smoke Free</div>
    <h4>Senior</h4>
    <div>
      <input type="hidden" name="not_senior[1]" value="Assisted Living" <?php if( in_array("Assisted Living", $feat) || isset($search_feat['assist']) ) echo  "checked"; ?>>
      <input name="senior[1]" type="checkbox" value="Assisted Living" <?php if( in_array("Assisted Living", $feat) || isset($search_feat['assist']) ) echo  "checked"; ?>>
      Assisted Living</div>
    <div>
      <input type="hidden" name="not_senior[2]" value="Independent Living" <?php if( in_array("Independent Living", $feat) || isset($search_feat['indep']) ) echo  "checked"; ?>>
      <input name="senior[2]" type="checkbox" value="Independent Living" <?php if( in_array("Independent Living", $feat) || isset($search_feat['indep']) ) echo  "checked"; ?>>
      Independent Living</div>
  </div>
</div>