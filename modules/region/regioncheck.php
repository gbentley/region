<?php

$module = $Params['Module'];

$result = array(
    "redirectTo" => null,
    "version" => 1
);

ezxRegion::checkRegion();

//Check for 'region mismatch' session variable
if (eZSession::issetkey("REGIONWARNING")) {

    $redirectURL = eZSession::get('SYSTEMIDENTIFIEDURL');
    $targetSiteaccess = eZSession::get('REDIRECT_SITEACCESS');

    $currentSiteaccess = $GLOBALS['eZCurrentAccess']['name'];

    if ($targetSiteaccess != $currentSiteaccess) {
        $result['redirectTo'] = $redirectURL;
        $result['currentSiteaccess'] = $currentSiteaccess;
        $result['targetSiteaccess'] = $targetSiteaccess;

        // grab translations for dialog fields (for detected locale)
        $detectedLocale = eZSession::get('PREFERRED_REGION');

        $countryNames = eZINI::instance( 'site.ini' )->variable( 'RegionalSettings', 'TranslationSA' );
        $preferredRegionName = $countryNames[$targetSiteaccess];
        $currentRegionName = $countryNames[$currentSiteaccess];

        $result['goToDetectedRegionPrompt'] = regionCheckI18NLookup('Go to our %region site', array('%region' => $preferredRegionName), $detectedLocale);
        $result['continueToRegionPrompt'] = regionCheckI18NLookup('Continue to our %region site', array('%region' => $currentRegionName), $detectedLocale);
        $result['selectYourRegionPrompt'] = regionCheckI18NLookup('Select your region', null, $detectedLocale);
    }
}

function regionCheckI18NLookup($i18NKey, $arguments, $locale) {

    $value = ezpI18n::tr( 'region', $i18NKey, false, $arguments, $locale );
    return $value;

}

header('Content-Type: application/json');
$resultJson = json_encode($result);
print_r($resultJson);

eZExecution::cleanExit();
