<?php

$module = $Params['Module'];
$regionData = ezxRegion::getRegionData();
$currentRegion = eZINI::instance( 'site.ini' )->variable( 'RegionalSettings', 'Locale' );


if(!array_key_exists( 'REGIONCHECK', $_COOKIE )) {
    if(array_key_exists( 'preferred_region', $regionData )) {
        $systemIdentifiedRegion = $regionData['preferred_region'];
    }

    if( $currentRegion != $systemIdentifiedRegion ) {
        echo false;
    }
    else {
        setcookie("REGIONCHECK", "CHECKED", time()+3600*24*365 , '/' );
        echo true;
    }
}
else {
    setcookie("REGIONCHECK", "CHECKED", time()+3600*24*365 , '/' );
    echo true;
}
eZExecution::cleanExit();
