<?php 

$handlerOptions = new ezpExtensionOptions();
$handlerOptions->iniFile = 'site.ini';
$handlerOptions->iniSection = 'RegionalSettings';
$handlerOptions->iniVariable = 'LanguageSwitcherClass';

// 0. Module params are sent to constructor to process the request.
$handlerOptions->handlerParams = array( $Params );

$langSwitch = eZExtension::getHandlerClass( $handlerOptions );

$Module = $Params['Module'];
$tpl = eZTemplate::factory();
$lang = ( isset( $_GET['lang'] ) ) ? $_GET['lang'] : null;

$siteini = eZINI::instance( "site.ini");

if ( array_key_exists( 'TESTIP', $_GET ) and ezxISO3166::validip( $_GET['TESTIP'] ) )
{
	$regiondata = ezxRegion::getRegionData( $_GET['TESTIP'], null, $lang );
	setcookie("EZREGION", $regiondata['preferred_region'], time()+3600*24*365 , '/' );
	eZDebug::writeDebug( $_GET['TESTIP'], 'TEST IP ADDRESS' );
	eZDebug::writeDebug( $regiondata, 'TEST REGIONAL DATA' );
}
elseif ( array_key_exists( 'country', $_GET ) )
{
	$regiondata = ezxRegion::getRegionData( null , $_GET['country'], $lang);
	setcookie("EZREGION", $regiondata['preferred_region'], time()+3600*24*365 , '/' );
	eZDebug::writeDebug( $_GET['country'], 'TEST IP ADDRESS' );
	eZDebug::writeDebug( $regiondata, 'TEST REGIONAL DATA' );
}
else
{
	$regiondata = ezxRegion::getRegionData(  ezxISO3166::getRealIpAddr() );
	eZDebug::writeDebug( ezxISO3166::getRealIpAddr(), 'REMOTE IP ADDRESS' );
}
if ( array_key_exists( 'EZREGION', $_COOKIE ) )
{
	eZDebug::writeDebug( $_COOKIE['EZREGION'], 'region cookie');
	$selection = $_COOKIE['EZREGION'];
}
elseif ( array_key_exists( 'preferred_region', $regiondata ) )
{
	$selection = $regiondata['preferred_region'];
}
else
{
	$selection = false;
}

$destinationSiteAccess = $siteini->variable('RegionalSettings', 'LanguageSA');

// Steps for language switcher classes

// 1. destination siteaccess is set
$langSwitch->setDestinationSiteAccess( $destinationSiteAccess[$selection] );

// 2. The process hook is called, it is up to each class what this step involves.
$langSwitch->process();

// 3. The final URL is fetched from the language switcher class. This URL must
// point to the correct full URL including host (for host based mapping) and
// translated URL alias where applicable.
$destinationUrl = $langSwitch->destinationUrl();
var_dump( $destinationUrl ); exit();

// 4. The browser is redirected to the URL of the translated content.
$Module->redirectTo( $destinationUrl );

?>