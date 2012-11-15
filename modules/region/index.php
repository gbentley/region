<?php

$destinationUrl = ezxRegion::getRegionURL( $Params );
var_dump( $destinationUrl, $GLOBALS['eZCurrentAccess'] ); exit();

// 4. The browser is redirected to the URL of the translated content.
$Params['Module']->redirectTo( $destinationUrl );

?>