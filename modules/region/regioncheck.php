<?php

$module = $Params['Module'];

if(!array_key_exists( 'REGIONCHECKED', $_COOKIE )) {
    $result = array();

    //Check for session variable
    if(eZSession::issetkey('REGIONWARNING')) {
        $redirectURL = eZSession::get('SYSTEMIDENTIFIEDURL');
        $usURL = eZSession::get('USURL');
        $result = array(
            'redirectto' => $redirectURL
        );
    }
    setcookie('REGIONCHECKED', 'TRUE', time()+3600*24*365 , '/' );
}

header('Content-Type: application/json');
$resultJson = json_encode($result);
print_r($resultJson);

eZExecution::cleanExit();
