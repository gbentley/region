<?php

$module = $Params['Module'];

if(!array_key_exists( 'REGIONCHECKED', $_COOKIE )) {
    //Check for session variable
    if(eZSession::issetkey('REGIONWARNING')) {
        $redirectURL = eZSession::get('SYSTEMIDENTIFIEDURL');
        $usURL = eZSession::get('USURL');
        $result = array(
            'redirectto' => $redirectURL,
            'redirecttous' => $usURL
        );
        $resultJson = json_encode($result);
        print_r($resultJson);
    }
    setcookie('REGIONCHECKED', 'TRUE', time()+3600*24*365 , '/' );
}
/*else {
    if(eZSession::issetkey('REGIONWARNING')) {
        eZSession::unsetkey('REGIONWARNING');
        eZSession::unsetkey('SYSTEMIDENTIFIEDURL');
        eZSession::unsetkey('USURL');
    }
}*/
eZExecution::cleanExit();
