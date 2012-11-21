<?php

$destinationUrl = ezxRegion::getRegionURL( $Params );

$Params['Module']->redirectTo( $destinationUrl );

?>