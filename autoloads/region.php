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
        return array( 'region_languages', 'regions', 'language_uri', 'in_region', 'canonical_url', 'canonical_language_url' );
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

					$translatedURLs = ezpLanguageSwitcher::setupTranslationSAList( $currentNodeId );
					foreach( $translatedURLs as $sa => $translation ) {
						$ini      = eZSiteAccess::getIni( $sa );
						$locale   = new eZLocale( $ini->variable( 'RegionalSettings', 'Locale' ) );
						$return[] = array(
							'language'   => strtolower( $locale->attribute( 'http_locale_code' ) ),
							'url'        => $translation['url'],
							'siteaccess' => $sa
						);
					}
					$operatorValue = $return;
				break;
        }


    }
}
?>
