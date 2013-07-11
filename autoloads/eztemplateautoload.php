<?php

// Operator autoloading

$eZTemplateOperatorArray = array();

$eZTemplateOperatorArray[] =
  array( 'script' => eZExtension::baseDirectory() . '/region/autoloads/region.php',
         'class' => 'Region',
         'operator_names' => array( 'language_uri', 'region_languages', 'regions', 'in_region', 'canonical_url', 'canonical_language_url' ) );

?>
