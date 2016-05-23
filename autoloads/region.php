<?php

class Region
{
    /*!
      Constructor, does nothing by default.
    */
    function Region()
    {
    }

    /*!
     \return an array with the template operator name.
    */
    function operatorList()
    {
        return array( 'region_languages', 'regions', 'language_uri', 'in_region', 'canonical_url', 'canonical_language_url', 'detected_region', 'detected_locale' );
    }
    /*!
     \return true to tell the template engine that the parameter list exists per operator type,
             this is needed for operator classes that have multiple operators.
    */
    function namedParameterPerOperator()
    {
        return true;
    }

    /*!
     See eZTemplateOperator::namedParameterList
    */
    function namedParameterList()
    {
        return array( 'language_uri' => array( 'node_id' => array( 'type' => 'string',
                                                                     'required' => true,
                                                                     'default' => false ),
                                               'languages' => array( 'type' => 'array',
                                                                     'required' => true,
                                                                     'default' => false ) ),
                      'region_languages' => array( 'siteaccessname' => array( 'type' => 'string',
                                                                     'required' => true,
                                                                     'default' => false ) ),
                      'regions' => array( 'siteaccessname' => array( 'type' => 'string',
                                                                     'required' => true,
                                                                     'default' => false ) ),
                      'in_region' => array( 'region' => array( 'type' => 'string',
                                                               'required' => true,
                                                               'default' => false ) ),
                      'canonical_url' => array(),
                      'canonical_language_url' => array(),
                      'detected_region' => array(),
                      'detected_locale' => array()
		);

    }
    /*!
     Executes the PHP function for the operator cleanup and modifies \a $operatorValue.
    */
    function modify( &$tpl, &$operatorName, &$operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters )
    {
		$moduleResult  = $tpl->hasVariable( 'module_result' ) ? $tpl->variable( 'module_result' ) : array();
		$contentInfo   = isset( $moduleResult['content_info'] ) ? $moduleResult['content_info'] : array();
		$currentNodeId = isset( $moduleResult['node_id'] ) ? (int) $moduleResult['node_id'] : 0;

        switch ( $operatorName )
        {
            case 'language_uri':
                foreach ( $namedParameters['languages'] as $lang )
                {
                    $node = eZContentObjectTreeNode::fetch( $namedParameters['node_id'], $lang );
                    if( is_object( $node ) )
                    {
                         $operatorValue = $node->attribute( 'url_alias' );
                         return;
                    }
                }
                $operatorValue = false;
            break;
            case 'region_languages':
                $operatorValue = ezxISO3166::getLanguagesFromLocalCode($namedParameters['siteaccessname']);
            break;
            case 'regions':
                $operatorValue = ezxISO3166::getPrimaryLocales( $namedParameters['siteaccessname'] );
            break;
            case 'in_region':
	            	$regionini = eZINI::instance( 'region.ini' );
	            	$regions = $regionini->variableArray('Regions', 'RegionCountryList');

	            	$ip = ( array_key_exists( 'TESTIP', $_GET ) and ezxISO3166::validip( $_GET['TESTIP'] ) ) ? new ezxISO3166( $_GET['TESTIP']) : new ezxISO3166();
	            	$ccode = ( array_key_exists( 'country', $_GET ) ) ? strtoupper($_GET['country']) : $ip->getCCfromIP();
	            	eZDebug::writeDebug( $regions, 'Regions' );
	            	eZDebug::writeDebug( $ccode, 'Country code' );

	            	// CHECK THAT THE REGION EXISTS IN THE REGION GROUP
	            	if ( in_array( $ccode, $regions[$namedParameters['region']] ) && array_key_exists($namedParameters['region'], $regions) )
	            		{
	            			$operatorValue = true;
	            			break;
	            		}

	            	$operatorValue = false;
	          break;
				case 'canonical_url':
                	if(
						isset( $contentInfo['main_node_url_alias'] )
						&& $contentInfo['main_node_url_alias']
					) {
						$operatorValue = $contentInfo['main_node_url_alias'];
                	}
				break;
				case 'canonical_language_url':
					$return = array();

					$node   = eZContentObjectTreeNode::fetch( $currentNodeId );
					$object = false;
					if(	$node instanceof eZContentObjectTreeNode ) {
						$object = $node->attribute( 'object' );
					}

					$translatedURLs  = ezpLanguageSwitcher::setupTranslationSAList( $currentNodeId );
					$localeCountries = eZINI::instance( 'region.ini' )->variable( 'Regions', 'LocaleCountryList' );
					foreach( $translatedURLs as $sa => $translation ) {
						$ini        = eZSiteAccess::getIni( $sa );
						$localeCode = $ini->variable( 'RegionalSettings', 'Locale' );
						$locale     = new eZLocale( $localeCode );
						if( isset( $localeCountries[ $localeCode ] ) ) {
							$tmp         = explode( '-', strtolower( $locale->attribute( 'http_locale_code' ) ) );
							$countryCode = $tmp[0];
							$languages   = explode( ';', strtolower( $localeCountries[ $localeCode ] ) );
							foreach( $languages as $language ) {
								$return[] = array(
									'language'   => $countryCode . '-' . $language,
									'url'        => $translation['url'],
									'siteaccess' => $sa
								);								
							}
						} else {
							$return[] = array(
								'language'   => strtolower( $locale->attribute( 'http_locale_code' ) ),
								'url'        => $translation['url'],
								'siteaccess' => $sa
							);
						}
					}

					$operatorValue = $return;
				break;
				case 'detected_region':
			                $operatorValue = eZINI::instance( 'site.ini' )->variable('SiteSettings','DefaultAccess');
			                $systemIdentifiedRegion = ezxRegion::getRegionData(ezxISO3166::getRealIpAddr());
			                $preferredRegion = $systemIdentifiedRegion['preferred_region'];

			                if(array_key_exists('preferred_regions',$systemIdentifiedRegion)) {
			                    $preferredRegion = empty($preferredRegion) ? 'eng-US' : $preferredRegion;
			                    $systemIdentifiedSiteAccess = $systemIdentifiedRegion['preferred_regions'][$preferredRegion][0];

			                    $countryNames = eZINI::instance( 'site.ini' )->variable( 'RegionalSettings', 'TranslationSA' );
			                    $operatorValue = array_key_exists($systemIdentifiedSiteAccess, $countryNames) ? $countryNames[$systemIdentifiedSiteAccess] : 'United States';
			                }
                            break;
                 case 'detected_locale':
                            $operatorValue = eZINI::instance( 'site.ini' )->variable('SiteSettings','DefaultAccess');
                            $systemIdentifiedRegion = ezxRegion::getRegionData(ezxISO3166::getRealIpAddr());
                            $preferredRegion = $systemIdentifiedRegion['preferred_region'];
                            if(array_key_exists('preferred_regions',$systemIdentifiedRegion)) {
                                $preferredRegion = empty($preferredRegion) ? 'eng-US' : $preferredRegion;

                                $operatorValue = $preferredRegion;
                            }
                 break;

        }


    }
}
?>
