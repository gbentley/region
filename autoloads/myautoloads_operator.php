<?php

class myAutoloads
{
    public $Operators;
    
    function operatorList()
    {
        return array( 'i18n_custom' );
    }

    function namedParameterPerOperator()
    {
        return true;
    }
    
    function namedParameterList()
    {
        return array( 'i18n_custom' => array( 'context' => array( 'type' => 'string',
                                                                  'required' => true,
                                                                  'default' => 'my_default_context' ),
                                              'language' => array( 'type' => 'string',
                                                                   'required' => false,
                                                                   'default' => 'eng-GB' ) ) );
    }

    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters )
    {
        $ret = '';

                switch ( $operatorName )
                            {
                                            case 'i18n_custom':
                                                                $operatorValue = self::i18n_custom( $namedParameters['context'], $operatorValue, $namedParameters['language'] );
                                                                                break;
                                                                        }
                    }

        /**
         *      * Retrieve translation in specific language
         *           * @params context STRING
         *                * @params source STRING
         *                     * @params language STRING
         *                          *
         *                               * @return trans STRING
         *                                    */
        function i18n_custom( $context, $source, $language)
                {
                            $comment = null;
                                    $localeCode = eZLocale::instance($language)->localeFullCode();
                                    
                                    $ini = eZINI::instance();
                                            eZTSTranslator::initialize( $context, $localeCode, 'translation.ts', false );

                                            $man = eZTranslatorManager::instance();
                                                    $trans = $man->translate( $context, $source, $comment );
                                                    
                                                    if ( $trans !== null ) {
                                                                    return $trans;
                                                                            }

                                                            eZDebug::writeDebug( "Missing translation for message in context: '$context'. The untranslated message is: '$source'", __METHOD__ );
                                                            return $source;
                                                                }
}
?>
