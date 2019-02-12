<?php

$module = $Params['Module'];

$result = array(
    "redirectTo" => null,
    "version" => 1
);

$regionCheckResult = ezxRegion::checkRegion(true);

//Check for 'region mismatch' session variable
if ($regionCheckResult['RegionWarning']) {

    $href = $_GET['href'];
    if (!$href) {
        return;
    }

    $targetSiteaccess = $regionCheckResult['RedirectSiteAccess'];
    $currentSiteaccess = $GLOBALS['eZCurrentAccess']['name'];

    $urlPath = preg_replace("/http(s?):\/\/[^\/]*/", "", $href); // remove the http:// and port
    $urlPathFragments = array_filter(explode('/', $urlPath));
    $urlPathFragments = array_slice($urlPathFragments, 1); // remove the siteaccess name
    $urlPath = implode('/', $urlPathFragments); // recombine to form a path without server & siteaccess

    $redirectURL = ezxRegion::getRegionURL('/', false) . $urlPath;

    if ($targetSiteaccess != $currentSiteaccess) {
        $result['redirectTo'] = $redirectURL;
        $result['currentSiteaccess'] = $currentSiteaccess;
        $result['targetSiteaccess'] = $targetSiteaccess;

        // grab translations for dialog fields (for detected locale)
        $detectedLocale = $regionCheckResult['PreferredRegion'];

        $countryNames = eZINI::instance( 'site.ini' )->variable( 'RegionalSettings', 'TranslationSA' );
        $preferredRegionName = $countryNames[$targetSiteaccess];
        $currentRegionName = $countryNames[$currentSiteaccess];

        $result['goToDetectedRegionPrompt'] = regionCheckI18NLookup('Go to our %region site', array('%region' => $preferredRegionName), $detectedLocale);
        $result['continueToRegionPrompt'] = regionCheckI18NLookup('Continue to our %region site', array('%region' => $currentRegionName), $detectedLocale);
        $result['selectYourRegionPrompt'] = regionCheckI18NLookup('Select your region', null, $detectedLocale);
    }
}

function regionCheckI18NLookup($i18NKey, $arguments, $locale) {

    $value = ezpI18n::tr( 'region', $i18NKey, false, $arguments, $locale, false );
    return $value;

}

header('Content-Type: application/json');
$resultJson = json_encode($result);
print_r($resultJson);

eZExecution::cleanExit();
