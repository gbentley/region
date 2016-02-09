<?php
$Module = array( "name" => "Language selector",
                 "variable_params" => true );

$ViewList = array();
$ViewList["index"] = array(
    "script" => "index.php");

$ViewList["check"] = array(
    "functions" => array( 'regioncheck' ),
    "script" => "regioncheck.php");

$ViewList["foruserip"] = array(
    "functions" => array( 'foruserip' ),
    "script" => "userregion.php");

$FunctionList = array(
    'regioncheck' => array(),
    'foruserip' => array()
);


?>
