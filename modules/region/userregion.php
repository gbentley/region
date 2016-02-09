<?php

$module = $Params['Module'];
$regionData = ezxRegion::getRegionURL($Params, false);

if($regionData != '') {
    setcookie("REGIONCHECK", "CHECKED", time()+3600*24*365 , '/' );
    echo $regionData;
}
else {
    echo 0;
}
eZExecution::cleanExit();
