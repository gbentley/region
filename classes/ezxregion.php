<?php

class ezxRegion
{

    /**
     * Returns Region information for the current user/ip...
     *
     * @return array Returns an array with keys
     */
    static function load( $SessionName, $redirectRoot = false, $url_excludes = array() )
    {
        if ( eZSys::isShellExecution() )
        {
            return;
        }
        if ( self::isBot() )
        {
            return;
        }
        $urlCfg = new ezcUrlConfiguration();
        $urlCfg->script = 'index.php';
        $url = new ezcUrl( ezcUrlTools::getCurrentUrl(), $urlCfg );
        eZDebug::writeDebug($url,'url object');
        //exit();
        $params = $url->getParams();

        eZDebug::writeDebug($params, 'params');

        $url_excludes = array_merge( $url_excludes, eZINI::instance( 'region.ini' )->variable( 'Settings', 'URLExcludes' ) );
        # Checking for excluded URLs
        $current_url = implode( '/', $params );
        if ( count( $url_excludes ) > 0 )
        {
            foreach ( $url_excludes as $exclude )
            {
                if ( preg_match( '#^([^/]*/){0,1}' . $exclude . '#', $current_url ) )
                {
                    return;
                }
            }
        }

        if ( ! is_array( $SessionName ) && $SessionName == '' )
        {
            $SessionName = 'eZSESSID';
        }
        $foundSessionName = false;
        if ( is_array( $SessionName ) )
        {
        	eZDebug::writeDebug('isArray','Session Name');
            foreach ( $SessionName as $name )
            {
                foreach ( $_COOKIE as $cookieName => $cookieValue )
                {
                    if ( strpos( $cookieName, $SessionName ) !== false )
                    {
                        $foundSessionName = true;
                    }
                }

                if ( $foundSessionName )
                {
                    if ( $redirectRoot and array_key_exists( 'EZREGION', $_COOKIE ) and
                         is_array( $params ) and count( $params ) == 0 and
                         file_exists( 'settings/siteaccess/' . $_COOKIE['EZREGION'] ) )
                    {
                        $redirectWithCookie = true;
                    }
                    else
                    {
                    	eZDebug::writeDebug('no cookies', 'Redirect');
                        return;
                    }
                }
            }
        }
        else
        {
        	eZDebug::writeDebug('isNotArray', 'Session Name');
            foreach ( $_COOKIE as $cookieName => $cookieValue )
            {
                if ( strpos( $cookieName, $SessionName ) !== false )
                {
                    $foundSessionName = true;
                }
            }

            if ( $foundSessionName )
            {
                if ( $redirectRoot and array_key_exists( 'EZREGION', $_COOKIE ) and
                     is_array( $params ) && count( $params ) == 0 and
                     file_exists( 'settings/siteaccess/' . $_COOKIE['EZREGION'] ) )
                {
                    $redirectWithCookie = true;
                }
                else
                {
                	eZDebug::writeDebug('redirect', 'no cookies');
                    return;
                }
            }
        }

        if ( isset( $params[0] ) and file_exists( 'settings/siteaccess/' . $params[0] ) )
        {
        	eZDebug::writeDebug('exists', 'Params & siteaccess');
            $siteaccess = $params[0];
            if ( array_key_exists( 'EZREGION', $_COOKIE ) and
                 $_COOKIE['EZREGION'] === $siteaccess )
            {
                return;
            }
        }
        else
        {
        	eZDebug::writeDebug('Params & siteaccess', 'non-existant');
            if ( isset( $redirectWithCookie ) && $redirectWithCookie === true )
            {
                $siteaccess = $_COOKIE['EZREGION'];
            }
            else
            {
                $siteaccess = false;
            }
        }

        if ( isset( $params[0] ) and $params[0] == 'ezinfo' and isset( $params[1] ) and $params[1] == 'is_alive' )
        {
            return;
        }
        if ( ( isset( $params[0] ) and
               $params[0] == 'region' and $params[1] == 'index' ) or
	           ( $siteaccess and isset( $params[1] ) and
	             $params[1] == 'region' and isset( $params[1] ) and
	             $params[2] == 'index' ) )
        {
            return;
        }
        if ( $siteaccess )
        {
            $paramnew = array(
                $siteaccess ,
                'region' ,
                'index' ,
                $siteaccess
            );
        }
        else
        {
            $paramnew = array(
                'region' ,
                'index'
            );
        }
        $query = $url->getQuery();
        $params = $url->path;
        if ( $siteaccess )
        {
            array_shift( $params );
        }

        if ( count( $params ) )
        {
            $query['URL'] = join( '/', $params );
        }
        setcookie( "COOKIETEST", 1, time() + 3600 * 24 * 365, '/' );
        $query['COOKIETEST'] = 1;

        $url->setQuery( $query );
        $url->params = $paramnew;
        header( 'Location: ' . $url->buildUrl() );
        eZExecution::cleanExit();
    }

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
		if ( array_key_exists( 'EZREGION', $_COOKIE ) && $checkCookie)
		{
			eZDebug::writeDebug( $_COOKIE['EZREGION'], 'region cookie');
			$selection = $_COOKIE['EZREGION'];
		}
		else
		{
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
			setcookie("EZREGION", $regiondata['preferred_region'], time()+3600*24*365 , '/' );

			if ( array_key_exists( 'preferred_region', $regiondata ) )
			{
				$selection = $regiondata['preferred_region'];
			}
			else
			{
				$selection = false;
			}
		}

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
        self::checkRegion();

		if(
			count( $GLOBALS['eZCurrentAccess']['uri_part'] ) == 0 
			&& $GLOBALS['eZCurrentAccess']['name'] == eZINI::instance( 'site.ini' )->variable( 'SiteSettings', 'DefaultAccess' )
		) {
			$p = array(
				'Parameters'     => array( $uri->uriString() ),
				'UserParameters' => array()
			);

			$url = self::getRegionURL( $p ) . eZSys::queryString();
			header( 'Location: ' . $url );
			eZExecution::cleanExit();
		} else {
			$currentRegion = eZINI::instance( 'site.ini' )->variable( 'RegionalSettings', 'Locale' ); 
			setcookie( 'EZREGION', $currentRegion, time()+3600*24*365 , '/' );
		}
	}

    public static function checkRegion() {

        $ignoreCheck = false;
        $tempUrl = $GLOBALS['eZURIRequestInstance']->OriginalURI;
        $nodeId = eZURLAliasML::fetchNodeIDByPath( $tempUrl );

        //Check the region only for site pages
        if(!$nodeId) {
            $ignoreCheck = true;
        }

        if ( !array_key_exists( 'REGIONCHECKED', $_COOKIE ) && !$ignoreCheck){
            $siteAccessRequested = $GLOBALS['eZCurrentAccess']['name'];
            $systemIdentifiedRegion = self::getRegionData(ezxISO3166::getRealIpAddr());
            $preferredRegion = $systemIdentifiedRegion['preferred_region'];
            $systemIdentifiedSiteAccess = $systemIdentifiedRegion['preferred_regions'][$preferredRegion][0];

            if($systemIdentifiedSiteAccess != $siteAccessRequested) {
                eZSession::set( 'REGIONWARNING', 'TRUE' );

                //Get system identified SA path for URL
                $ezURIInstance = $GLOBALS['eZURIRequestInstance'];
                $originalUri = $ezURIInstance->OriginalURI;
                $listOfTranslationsForURL = ezpLanguageSwitcher::setupTranslationSAList($originalUri);

                $systemIdentifiedURL = $listOfTranslationsForURL[$systemIdentifiedSiteAccess]['url'];

                eZSession::set('SYSTEMIDENTIFIEDURL', $systemIdentifiedURL);
            }
            //setcookie('REGIONCHECKED', 'TRUE', time()+3600*24*365 , '/' );
        }
    }
}
