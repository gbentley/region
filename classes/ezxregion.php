<?php

class ezxRegion
{

    static function isBot()
    {
        $bot_list = array(
            "Teoma" ,
            "alexa" ,
            "froogle" ,
            //"Gigabot" ,
            "inktomi" ,
            "looksmart" ,
            //"URL_Spider_SQL" ,
            "Firefly" ,
            "NationalDirectory" ,
            "Ask Jeeves" ,
            "TECNOSEEK" ,
            "InfoSeek" ,
            //"WebFindBot" ,
            //"girafabot" ,
            "crawler" ,
            "www.galaxy.com" ,
            //"Googlebot" ,
            "Scooter" ,
            "Slurp" ,
            //"msnbot" ,
            "appie" ,
            "FAST" ,
            'Slurp' ,
            //'CazoodleBot' ,
            //'msnbot' ,
            'InfoPath' ,
            //'Baiduspider' ,
            "WebBug" ,
            "Spade" ,
            "ZyBorg" ,
            "rabaz" ,
            //"Baiduspider" ,
            "Feedfetcher-Google" ,
            "TechnoratiSnoop" ,
            //"Rankivabot" ,
            "Mediapartners-Google" ,
            //"Sogou web spider" ,
            //"WebAlta Crawler",
            "bot" ,
            "spider" ,
            "search" ,
            "linkidator"
        );

        if ( preg_match( "/" . join( '|', $bot_list ) . "/i", $_SERVER['HTTP_USER_AGENT'] ) )
        {
            return true;
        }
        return false;
    }

    /**
     * Returns Region information for the current user/ip...
     *
     * @return array Returns an array with keys
     */
    static function getRegionData( $address = null, $ccode = null, $lcode = null )
    {
        eZDebug::writeDebug( 'Starting...', 'ezxRegion::getRegionData()' );
        $siteini = eZINI::instance( 'site.ini' );
        $ccode = ($ccode) ? strtoupper( $ccode ) : ezxISO3166::preferredCountry( $address );

        // IF LANG NOT SET, GET FROM BROWSER
        $lcode = ($lcode) ? array($lcode => 1) : ezxISO936::preferredLanguages();
        $regions = (array) $siteini->variableArray('RegionalSettings', 'LanguageSA');
        $regions_keys = array_keys($regions);
        eZDebug::writeDebug( $regions_keys, 'Regions keys' );
        $preferred_regions = array();

        foreach ( $regions as $key => $region )
        {
            if ( $ccode and strpos( $key, '-' . $ccode ) !== false )
                $preferred_regions[$key] = $region;
        }

        $langs = array_keys( $lcode );
        $preferred_languages = array();
        foreach ( $regions as $key => $region )
        {
            foreach ( $langs as $lang )
            {
                if ( strpos( $key, $lang . '-' ) !== false )
                {
                    $preferred_languages[$key] = $region;
                    break;
                }
            }
        }
        $preferred_region = false;
        foreach ( $langs as $lang )
        {
            if ( in_array( $lang . '-' . $ccode, $regions_keys ) )
            {
                $preferred_region = $lang . '-' . $ccode;
                break;
            }
        }

        //IF NO LOCALE MATCH, CHECK FOR REGION IN GROUPINGS
        if ( ! $preferred_region )
        {
        	$regionini = eZINI::instance( 'region.ini' );
        	$region_groups = $regionini->variableArray('Regions', 'LocaleCountryList');
        	eZDebug::writeDebug( $region_groups, 'Region groups' );
        	eZDebug::writeDebug( $ccode, 'Country code' );

        	// CHECK THAT THE REGION EXISTS IN THE LANGUAGE-SITEACCESS LIST
        	foreach ( $region_groups as $region_group => $countries )
        	{
        		if ( in_array( $ccode, $countries ) && array_key_exists($region_group, $regions) )
        		{
        			$preferred_region = $region_group;
        			break;
        		}
        	}
        }

        //IF NO LOCALE MATCH, GET REGION FROM LIST OF PREFERRED REGIONS
        if ( ! $preferred_region )
        {
            $keys = array_keys( $preferred_regions );
            $preferred_region = $keys[0];
        }

        //IF NO REGION MATCH, GET REGION FROM LIST OF PREFERRED LANGUAGES
        if ( ! $preferred_region )
        {
            $keys = array_keys( $preferred_languages );
            $preferred_region = $keys[0];
        }
        if ( ! $preferred_region )
        {
            eZDebug::writeError( 'No proper region has been found', 'ezxRegion::getRegionData()' );
            return false;
        }
        return array(
            'preferred_region' => $preferred_region ,
            'preferred_languages' => $preferred_languages ,
            'preferred_regions' => $preferred_regions
        );
    }

	public static function getRegionURL( $URLPath, $checkCookie=true ) {
/*		if ( array_key_exists( 'EZREGION', $_COOKIE ) && $checkCookie)
		{
			eZDebug::writeDebug( $_COOKIE['EZREGION'], 'region cookie');
			$selection = $_COOKIE['EZREGION'];
		}
		else
		{*/
			$lang = ( isset( $_GET['lang'] ) ) ? $_GET['lang'] : null;
			if ( array_key_exists( 'TESTIP', $_GET ) and ezxISO3166::validip( $_GET['TESTIP'] ) )
			{
				$regiondata = ezxRegion::getRegionData( $_GET['TESTIP'], null, $lang );
				eZDebug::writeDebug( $_GET['TESTIP'], 'TEST IP ADDRESS' );
				eZDebug::writeDebug( $regiondata, 'TEST REGIONAL DATA' );
			}
			elseif ( array_key_exists( 'country', $_GET ) )
			{
				$regiondata = ezxRegion::getRegionData( null , $_GET['country'], $lang);
				eZDebug::writeDebug( $_GET['country'], 'TEST COUNTRY' );
				eZDebug::writeDebug( $regiondata, 'TEST REGIONAL DATA' );
			}
			else
			{
				$regiondata = ezxRegion::getRegionData( ezxISO3166::getRealIpAddr() );
				eZDebug::writeDebug( ezxISO3166::getRealIpAddr(), 'REMOTE IP ADDRESS' );
			}
//			setcookie("EZREGION", $regiondata['preferred_region'], time()+3600*24*365 , '/' );

			if ( array_key_exists( 'preferred_region', $regiondata ) )
			{
				$selection = $regiondata['preferred_region'];
			}
			else
			{
				$selection = false;
			}
//		}

		$siteini = eZINI::instance( "site.ini");
		$destinationSiteAccess = $siteini->variable('RegionalSettings', 'LanguageSA');

		// Steps for language switcher classes

		// 0. Module params are sent to constructor to process the request.
		$handlerOptions = new ezpExtensionOptions();
		$handlerOptions->iniFile = 'site.ini';
		$handlerOptions->iniSection = 'RegionalSettings';
		$handlerOptions->iniVariable = 'LanguageSwitcherClass';
		$handlerOptions->handlerParams = array( $URLPath );
		$langSwitch = eZExtension::getHandlerClass( $handlerOptions );

		// 1. destination siteaccess is set
		$langSwitch->setDestinationSiteAccess( $destinationSiteAccess[$selection] );

		// 2. The process hook is called, it is up to each class what this step involves.
		$langSwitch->process();

		// 3. The final URL is fetched from the language switcher class. This URL must
		// point to the correct full URL including host (for host based mapping) and
		// translated URL alias where applicable.
		return $langSwitch->destinationUrl();
	}

	public static function requestInput( $uri ) {
		$mathType = (int) $GLOBALS['eZCurrentAccess']['type'];

        //Check region only if this cookie is not set
        // self::checkRegion(); // MFH: why do this check?

        $ini = eZINI::instance( 'site.ini' );

		if(
			count( $GLOBALS['eZCurrentAccess']['uri_part'] ) == 0 
			&& $GLOBALS['eZCurrentAccess']['name'] == $ini->variable( 'SiteSettings', 'DefaultAccess' )
		) {
			$p = array(
				'Parameters'     => array( $uri->uriString() ),
				'UserParameters' => array()
			);

			$url = self::getRegionURL( $p ) . eZSys::queryString();
			header( 'Location: ' . $url );
			eZExecution::cleanExit();
		}
/*		else {
			$currentRegion = eZINI::instance( 'site.ini' )->variable( 'RegionalSettings', 'Locale' ); 
			setcookie( 'EZREGION', $currentRegion, time()+3600*24*365 , '/' );
		}*/
	}

    public static function checkRegion() {

        $result = array(
            'RegionWarning' => false,
            'RedirectSiteAccess' => false,
            'PreferredRegion' => false
            );

        $ignoreCheck = false;

        $tempUrl = $GLOBALS['eZURIRequestInstance']->OriginalURI;
        $nodeId = eZURLAliasML::fetchNodeIDByPath( $tempUrl );

        //Check the region only for site pages
        if(!$nodeId) {
            $ignoreCheck = true;
        }

        if (!$ignoreCheck) {

            $siteAccessRequested = $GLOBALS['eZCurrentAccess']['name'];
            $systemIdentifiedRegion = self::getRegionData(ezxISO3166::getRealIpAddr());
            $preferredRegion = $systemIdentifiedRegion['preferred_region'];
            $systemIdentifiedSiteAccess = $systemIdentifiedRegion['preferred_regions'][$preferredRegion][0];


            if ($systemIdentifiedSiteAccess != $siteAccessRequested) {
                $result['RegionWarning'] = true;

                //Get system identified SA path for URL
                $ezURIInstance = $GLOBALS['eZURIRequestInstance'];
                $originalUri = $ezURIInstance->OriginalURI;
                $listOfTranslationsForURL = ezpLanguageSwitcher::setupTranslationSAList($originalUri);

                $systemIdentifiedURL = $listOfTranslationsForURL[$systemIdentifiedSiteAccess]['url'];

                $result['RedirectSiteAccess'] = $systemIdentifiedSiteAccess;
                $result['PreferredRegion'] = $preferredRegion;

            }
        }

        return $result;

    }
}
