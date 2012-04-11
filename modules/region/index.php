<?php

$module = $Params['Module'];
$http = eZHTTPTool::instance();

$tpl = eZTemplate::factory();

$regionini = eZINI::instance( 'region.ini' );
$contentini = eZINI::instance( "content.ini");
$siteini = eZINI::instance( "site.ini");
$regions = $regionini->groups();
$settings = $regions['Settings'];
unset( $regions['Settings'] );

$Result['content'] = '';

$LayoutStyle = 'index';
$layoutINI = eZINI::instance( 'layout.ini' );
if ( array_key_exists( 'TESTIP', $_GET ) and ezxISO3166::validip( $_GET['TESTIP'] ) )
{
    $regiondata = ezxRegion::getRegionData( $_GET['TESTIP'] );
    setcookie("EZREGION", $regiondata['preferred_region'], time()+3600*24*365 , '/' );
    eZDebug::writeDebug( $_GET['TESTIP'], 'TEST IP ADDRESS' );
    eZDebug::writeDebug( $regiondata, 'TEST REGIONAL DATA' );
}
else
{
//  $regiondata = ezxRegion::getRegionData( '121.245.170.194' );
    $regiondata = ezxRegion::getRegionData(  ezxISO3166::getRealIpAddr() );
    eZDebug::writeDebug( ezxISO3166::getRealIpAddr(), 'REMOTE IP ADDRESS' );
}

$cookietest = true;
if ( array_key_exists( 'COOKIETEST', $_GET ) and !array_key_exists( 'COOKIETEST', $_COOKIE ) )
{
    $cookietest = false;
}
setcookie( "COOKIETEST", 1, time() - 3600*24*365 , '/' );

$redirect = true;

eZDebug::writeDebug( 'Starting', 'region extension' );

if ( $Params['siteaccess'] == 'select' )
{
    $selection = false;
}
elseif ( $Params['siteaccess'] )
{
    $selection = $Params['siteaccess'];
    setcookie("EZREGION", $Params['siteaccess'], time()+3600*24*365 , '/' );
}
elseif ( $http->hasPostVariable( 'region' ) )
{
    setcookie("EZREGION", $http->postVariable( 'region' ), time()+3600*24*365 , '/' );
    $selection = $http->postVariable( 'region' );
}
elseif ( array_key_exists( 'EZREGION', $_COOKIE ) )
{
	eZDebug::writeDebug( $_COOKIE['EZREGION'], 'region cookie');
    $selection = $_COOKIE['EZREGION'];
}
elseif ( array_key_exists( 'preferred_region', $regiondata ) )
{
    $selection = $regiondata['preferred_region'];
    $redirect = false;
}
else
{
    $selection = false;
}

if ( $http->hasGetVariable( 'URL' ) and $http->getVariable( 'URL' ) )
{
    $url = $http->getVariable( 'URL' );
}
elseif ( $http->hasPostVariable( 'URL' ) and $http->postVariable( 'URL' ) )
{
    $url = $http->postVariable( 'URL' );
}
else
{
    $url = false;
}

eZDebug::writeDebug( $url, 'url');

if ( $redirect === false and $settings['AutomaticRedirect'] == 'enabled' )
{
    $redirect = true;
}

$found = false;

$oldaccess = $GLOBALS['eZCurrentAccess'];
$accesslist = eZSiteAccess::siteAccessList();
if ( $selection !== false )
{
	eZDebug::writeDebug( $selection, 'Selection' );
	foreach( $accesslist as $access )
    {
            eZDebug::writeDebug( $access['name'], 'Access Name' );
        if (  $access['name'] and $access['name'] == $regions[$selection]['Siteaccess'] )
        {
            $accessNew = $GLOBALS['eZCurrentAccess'];
        	eZDebug::writeDebug( $accessNew['type'], 'EZ_ACCESS_TYPE' );
            $accessNew['name'] = $access['name'];
            setcookie("EZREGION", $selection, time()+3600*24*365 , '/' );
            

            if ( $accessNew['type'] == eZSiteAccess::TYPE_URI )
            {
                eZSys::clearAccessPath();
            }
            eZSiteAccess::change( $accessNew );

            // Load the siteaccess extensions
            eZExtension::activateExtensions( 'access' );

            // Change content object default language
            unset( $GLOBALS['eZContentObjectDefaultLanguage'] );
            eZContentObject::clearCache();

            eZContentLanguage::expireCache();
            break;
        }
    }
}
else
{
    foreach( $accesslist as $access )
    {
        if (  $access['name'] and $access['name'] == $url )
        {
            $url = false;
            $access = eZSiteAccess::change( $access );
            $GLOBALS['eZCurrentAccess'] = $access;
            $found = true;
            $selection = $access['name'];
            break;
        }
    }
}

eZDebug::writeDebug( $selection, 'selection');

if ( $selection and $redirect and $cookietest )
{
    if ( $regionini->hasVariable( $selection, "Country" ) )
    {
        $country = $regionini->variable( $selection, "Country" );
    }
    if ( $regionini->hasVariable( $selection, "Currency" ) )
    {
        $preferredCurrency = $regionini->variable( $selection, "Currency" );
    }
    if ( $country )
    {
        eZShopFunctions::setPreferredUserCountry( $country );
    }
    if ( $preferredCurrency and $regionini->variable( 'Settings', 'SetCurrency' ) == 'enabled' )
    {
        eZDebug::writeDebug( $preferredCurrency, "region currency");
        eZShopFunctions::setPreferredCurrencyCode( $preferredCurrency );
    }
    if ( $_GET["URI"] )
    {
        return $module->redirectTo( $_GET["URI"], false );
    }
    else
    {
        if ( !$found )
        {
		    if ( !array_key_exists( $selection, $regions ) and array_key_exists( '*_*', $regions ) )
		    {
			    foreach( $accesslist as $access )
	            {
	                if ( $access['name'] == $regions['*_*']['Siteaccess'] )
	                {
	                    $access = eZSiteAccess::change( $access );
	                    $GLOBALS['eZCurrentAccess'] = $access;
	                    $found = true;
	                    break;
	                }
	            }
			
	        }
			else
			{
	            foreach( $accesslist as $access )
	            {
	                if ( $access['name'] == $regions[$selection]['Siteaccess'] )
	                {
	                    $access = eZSiteAccess::change( $access );
	                    $GLOBALS['eZCurrentAccess'] = $access;
	                    $found = true;
	                    break;
	                }
	            }
			}      
    	}

        if ( ( $found and $oldaccess['name'] != $access['name'] ) or ( $found and !$url ) )
        {
            if ( $found )
            {
                if ( $siteini->hasVariable('SiteSettings', 'SiteURL') )
	            {
	            	$accesspath = $siteini->variable('SiteSettings', 'SiteURL');
	            }
	            else
	            {
	            	$accesspath = '/' . $access['name'];
	            }
            }

            // @TODO we might need language options to properly fetch the correct url.
            $node = eZContentObjectTreeNode::fetch( $contentini->variable( 'NodeSettings', 'RootNode') );
            if( is_object( $node ) )
            {
                $alias = '/' . $node->attribute( 'url_alias' );
            }
            else
            {
                $alias = "/content/view/full/" . $contentini->variable( 'NodeSettings', 'RootNode');
            }

            if ( $access and !$url )
            {
                return eZHTTPTool::redirect( eZSys::serverProtocol() . "://" . $accesspath . $alias );
            }
            else
            {
                if ( strpos( $url, '/' ) === 0 )
                {
                    return eZHTTPTool::redirect( eZSys::serverProtocol()."://". $accesspath . $url );
                }
                else
                {
                    return eZHTTPTool::redirect( eZSys::serverProtocol()."://". $accesspath . '/' . $url );
                }
            }
        }
        else
        {
        	
                if ( $siteini->hasVariable('SiteSettings', 'SiteURL') )
	            {
	            	$accesspath = $siteini->variable('SiteSettings', 'SiteURL');
	            }
	            else
	            {
	            	$accesspath = '/' . $access['name'];
	            }

                if ( strpos( $url, '/' ) === 0 )
                {
                    return eZHTTPTool::redirect( eZSys::serverProtocol() . "://" . $accesspath . $url );
                }
                else
                {
                    return eZHTTPTool::redirect( eZSys::serverProtocol() . "://" . $accesspath . '/' . $url );
                }            
        }
    }
}

/* Display a list of countries for the user to choose from */

if ( $layoutINI->hasVariable( $LayoutStyle, 'PageLayout' ) )
{
    $Result['pagelayout'] = $layoutINI->variable( $LayoutStyle, 'PageLayout' );
}
else
{
    $Result['pagelayout'] = 'pagelayout.tpl';
}

if ( $cookietest === false )
{
    $tpl->setVariable( 'nocookie', 1 );
}
else
{
    $tpl->setVariable( 'nocookie', 0 );
}

$tpl->setVariable('URL', $url );

$tpl->setVariable('preferred_region', $regiondata['preferred_region'] );
$tpl->setVariable('preferred_languages', $regiondata['preferred_languages'] );
$tpl->setVariable('preferred_regions', $regiondata['preferred_regions'] );
$tpl->setVariable('regions', $regions );
$Result['content'] = $tpl->fetch( "design:region/index.tpl" );
$node = eZContentObjectTreeNode::fetch( $contentini->variable( 'NodeSettings', 'RootNode') );
if ( $node )
{
    $Result['path'] = array( array( 'url' => false,
                                    'text' => $node->attribute( 'name' ) . ' - region selector' ) );
}

?>
