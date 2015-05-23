<?php
/* See here for instructions:
http://geocoder.ca/?api=1 */
//error_reporting(E_ALL); ini_set('display_errors', '1');
class GeoCode_ca {
        function __construct()
        {
                //Nothing to do right now
        }
        function GetGeo($aData)
        {
                $search = implode(" ", $aData);
				//we have to have an address
                if (!is_array($aData))
                {
                        throw new Exception("Input parameter must be an array.");
                }

                // Set misc values needed for the curl command
                $sPathToCurl        = 'curl';
                $sPostToURL         = 'http://geocoder.ca';
                $aPostData          = array();
                $sEncapChar                 = '"';
                $aData['id'] = 'req10001';
                $aData['geoit'] = 'XML';
                // Use this for better accuracy
                $aData['recompute '] = '1';

                //Create fieldname/value pairs
                foreach($aData as $sVar => $sValue)
                {
                        // Double quotes are removed from the data and replaced with '*'.
                        // except for the encapsulation character
                        $sValue = str_replace($sEncapChar, '*', $sValue);

                        //URLencode the key and value and make them a name/value pair
                        $aPostData[] = urlencode($sVar) . '=' . urlencode($sValue);
                }

                //Seperate by & each of the name/value pairs
                $sPostData = implode('&', $aPostData);

                //Execute curl command
                $sPostString = $sPathToCurl . ' -s  --data ' .
escapeshellarg($sPostData) . ' ' . escapeshellarg($sPostToURL);

                $sResponseString = shell_exec($sPostString);

                $aResult = array();
                if (!is_null($sResponseString))
                {
                        $oResponse = @simplexml_load_string($sResponseString);
                        // Check for error
                        if (isset($oResponse->error))
                        {
                                $aResult['error'] = $oResponse->error;
                                $aResult['lat'] = '';
                                $aResult['long'] = '';
                        }
                        else
                        {
                                $aResult['error'] = '';
                                $aResult['lat'] = (string) $oResponse->latt;
                                $aResult['long'] = (string) $oResponse->longt;
                        }
                }
                //return $oResponse;
				return array($aResult['lat'], $aResult['long'], $search, "notype", $oResponse->standard->city, $oResponse->standard->city, $oResponse->standard->prov, $oResponse->standard->prov, $oResponse->standard->postal, $oResponse->standard->stnumber." ".ucwords(strtolower($oResponse->standard->staddress)));
        }

}

$aInput = array();
$aInput['stno'] = '100';
$aInput['addresst'] = 'Toronto St S';
$aInput['city'] = 'Uxbridge';
$aInput['prov'] = 'ON';
$aInput['postal'] = 'L9P 1H2';

$oGeoCoder = new GeoCode_ca();
$aResult = $oGeoCoder->GetGeo($aInput);
print_r($aResult);

?>