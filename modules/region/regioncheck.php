<?php

$module = $Params['Module'];

$result = array(
    "regionChecked" => 0,
    "redirectTo" => null
);

//Check for session variable
if(eZSession:: issetkey("REGIONCHECKED")) {

    $result["regionChecked"] = 1;

    if (eZSession::issetkey("REGIONWARNING")) {

        $redirectURL = eZSession::get('SYSTEMIDENTIFIEDURL');
        $targetSiteaccess = eZSession::get('REDIRECT_SITEACCESS');

        $currentSiteaccess = $GLOBALS['eZCurrentAccess']['name'];

        if ($targetSiteaccess != $currentSiteaccess) {
            $result['redirectTo'] = $redirectURL;
            $result['currentSiteaccess'] = $currentSiteaccess;
            $result['targetSiteaccess'] = $targetSiteaccess;
        }
    }
}

header('Content-Type: application/json');
$resultJson = json_encode($result);
print_r($resultJson);

eZExecution::cleanExit();
