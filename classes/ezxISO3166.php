<?php

class ezxISO3166
{
    var $address;

    function ezxISO3166( $address = null )
    {
        if ( ! $address )
            $this->address = ezxISO3166::getRealIpAddr();
        else
            $this->address = $address;
    }

    static function validip( $ip )
    {
        if ( ! empty( $ip ) && ip2long( $ip ) != - 1 )
        {
            $reserved_ips = array( 
                array( 
                    '0.0.0.0' , 
                    '2.255.255.255' 
                ) , 
                array( 
                    '10.0.0.0' , 
                    '10.255.255.255' 
                ) , 
                array( 
                    '127.0.0.0' , 
                    '127.255.255.255' 
                ) , 
                array( 
                    '169.254.0.0' , 
                    '169.254.255.255' 
                ) , 
                array( 
                    '172.16.0.0' , 
                    '172.31.255.255' 
                ) , 
                array( 
                    '192.0.2.0' , 
                    '192.0.2.255' 
                ) , 
                array( 
                    '192.168.0.0' , 
                    '192.168.255.255' 
                ) , 
                array( 
                    '255.255.255.0' , 
                    '255.255.255.255' 
                ) 
            );
            
            foreach ( $reserved_ips as $r )
            {
                $min = ip2long( $r[0] );
                $max = ip2long( $r[1] );
                if ( ( ip2long( $ip ) >= $min ) && ( ip2long( $ip ) <= $max ) )
                    return false;
            }
            return true;
        }
        else
        {
            return false;
        }
    }

    static function getRealIpAddr()
    {
        if ( array_key_exists( "HTTP_CLIENT_IP", $_SERVER ) and ezxISO3166::validip( $_SERVER["HTTP_CLIENT_IP"] ) )
        {
            return $_SERVER["HTTP_CLIENT_IP"];
        }
        if( array_key_exists( "HTTP_X_FORWARDED_FOR", $_SERVER ) )
        {
        foreach ( explode( ",", $_SERVER["HTTP_X_FORWARDED_FOR"] ) as $ip )
        {
            if ( ezxISO3166::validip( trim( $ip ) ) )
            {
                return $ip;
            }
        }
        }
        if ( array_key_exists( "HTTP_X_FORWARDED", $_SERVER ) and ezxISO3166::validip( $_SERVER["HTTP_X_FORWARDED"] ) )
        {
            return $_SERVER["HTTP_X_FORWARDED"];
        }
        elseif ( array_key_exists( "HTTP_FORWARDED_FOR", $_SERVER ) and ezxISO3166::validip( $_SERVER["HTTP_FORWARDED_FOR"] ) )
        {
            return $_SERVER["HTTP_FORWARDED_FOR"];
        }
        elseif ( array_key_exists( "HTTP_FORWARDED", $_SERVER ) and ezxISO3166::validip( $_SERVER["HTTP_FORWARDED"] ) )
        {
            return $_SERVER["HTTP_FORWARDED"];
        }
        elseif ( array_key_exists( "HTTP_X_FORWARDED", $_SERVER ) and ezxISO3166::validip( $_SERVER["HTTP_X_FORWARDED"] ) )
        {
            return $_SERVER["HTTP_X_FORWARDED"];
        }
        else
        {
            return $_SERVER["REMOTE_ADDR"];
        }
    }

    static function defaultCountryCode()
    {
        $regionini = eZINI::instance( 'region.ini' );
        return strtoupper( $regionini->variable( 'Settings', 'DefaultCountryCode' ) );
    }

    function getALLfromIP()
    {
        // this sprintf() wrapper is needed, because the PHP long is signed by default
        $ipnum = sprintf( "%u", ip2long( $this->address ) );
        $query = "SELECT cc, cn FROM ezx_i2c_ip NATURAL JOIN ezx_i2c_cc WHERE ${ipnum} BETWEEN start AND end";
        $db = eZDB::instance();
        $result = $db->arrayQuery( $query );
        if ( isset( $result[0] ) )
            return $result[0];
    }

    function getCCfromIP()
    {
        $data = $this->getALLfromIP();
        if ( isset( $data['cc'] ) )
            return $data['cc'];
        else
            return false;
    }

    function getCOUNTRYfromIP()
    {
        $data = $this->getALLfromIP();
        if ( isset( $data['cn'] ) )
        {
            return $data['cn'];
        }
        else
            return false;
    }

    function getCCfromNAME( $name )
    {
        $ip2country = new ip2country( gethostbyname( $name ) );
        return $ip2country->getCCfromIP();
    }

    function getCOUNTRYfromNAME( $name )
    {
        $ip2country = new ip2country( gethostbyname( $name ) );
        return $ip2country->getCOUNTRYfromIP();
    
    }

    function getCountryCodeFromAccess( $accessname )
    {
        $list = preg_split( '/[_-]/', $accessname, 2 );
        return $list[0];
    }

    static function getPrimaryLocales( $Code = null, $exceptCurrent = true )
    {
        $regionini = eZINI::instance( 'region.ini' );
        $list = preg_split( '/[_-]/', $Code, 2 );
        $regionini = eZINI::instance( 'region.ini' );
        $regions = $regionini->groups();
        unset( $regions['Settings'] );
        $locales = array();
        foreach ( $regions as $key => $region )
        {
            $list2 = preg_split( '/[_-]/', $key, 2 );
            if ( array_key_exists( 1, $list2 ) and ! isset( $locales[$list2[1]] ) )
            {
                /* @TODO $exceptCurrent
                if ( $exceptCurrent and ( $Code != $region['Siteaccess'] ) )
                {

                }
                elseif( $exceptCurrent === false )
                {

                }
                */
                $region['code'] = $list2[0] . '-' . $list2[1];
                if ( $region['code'] != '*-*' )
                {
                    $region['possible_languagecodes'] = array();
                    array_push( $region['possible_languagecodes'], $list2[0] . '-' . $list2[1] );
                    array_push( $region['possible_languagecodes'], $list2[0] );
                }
                else
                {
                    $region['possible_languagecodes'] = array();
                    array_push( $region['possible_languagecodes'], $region['Siteaccess'] );
                    
                    $extralang = $regionini->variable( '*_*', 'AdditionalLanguageList' );
                    foreach ( $extralang as $lang )
                    {
                        array_push( $region['possible_languagecodes'], $lang );
                    }
                }
                $locales[$list2[1]] = $region;
            }
        }
        return $locales;
    }

    static function getLanguagesFromLocalCode( $Code, $exceptCurrent = true )
    {
        $list = preg_split( '/[_-]/', $Code, 2 );
        $regionini = eZINI::instance( 'region.ini' );
        $regions = $regionini->groups();
        unset( $regions['Settings'] );
        $languages = array();
        foreach ( $regions as $key => $region )
        {
            $list2 = preg_split( '/[_-]/', $key, 2 );
            if ( $list[1] == $list2[1] )
            {
                if ( $exceptCurrent and ( $Code != $region['Siteaccess'] ) )
                {
                    $languages[$region['Siteaccess']] = $region;
                }
                elseif ( $exceptCurrent === false )
                {
                    $languages[$region['Siteaccess']] = $region;
                }
            }
        
        }
        return $languages;
    }

    static function countries()
    {
        $regionini = eZINI::instance( 'region.ini' );
        $regions = $regionini->groups();
        unset( $regions['Settings'] );
        $countries = array();
        foreach ( $regions as $key => $region )
        {
            $list = preg_split( '/[_-]/', $key, 2 );
            if ( isset( $list[1] ) )
                $countries[$list[1]] = $list[1];
        }
        return $countries;
    }

    static function preferredCountry( $address = null )
    {
        $ip = new ezxISO3166( $address );
        $code = $ip->getCCfromIP();
        
        if ( ! $code )
            $code = ezxISO3166::defaultCountryCode();
        //$countries = ezxISO3166::countries();
//        if ( in_array( $code, $countries ) )
            return $code;
/*        else 
            if ( $code )
                return true;
            else
                return false; */
    }
}
?>
